<?php

/**
 * Class Thrive_Dash_List_CustomHTML
 *
 * handled generation of forms from custom HTML code taken from autoresponders
 */
class Thrive_Dash_Api_CustomHtml {
	/**
	 * @var string
	 */
	protected $_originalCode = '';

	protected $_isMailchimp = null;

	protected $_usedIds = array();

	/**
	 * @var Thrive_Dash_Api_Html_Renderer
	 */
	protected $_tableRenderer;

	public function __construct() {
		$this->_tableRenderer = new Thrive_Dash_Api_Html_Renderer();
	}

	/**
	 * strips <script>, <link>, <!-- --> (comments) tags from the html code
	 *
	 * @param string $code
	 *
	 * @return string $code striped version of the code
	 */
	protected function _stripExtraTags( $code ) {
		$code = stripslashes( $code );
		$code = preg_replace( '#<script(.*?)>(.*?)</script>#smi', '', $code );
		$code = preg_replace( '#<style(.*?)>(.*?)</style>#smi', '', $code );
		$code = preg_replace( '#<link(.*?)>#smi', '', $code );
		$code = str_replace( "</link>", "", $code );

		$code = preg_replace( '#<!--(.*?)-->#smi', '', $code );
		$code = preg_replace( '#<!(–|-)(.*?)(–|-)>#smi', '', $code );

		return $code;
	}

	/**
	 * check whether or not the custom html form code looks like a mailchimp code
	 * @return bool|null
	 */
	protected function _isMailchimp() {
		if ( ! isset( $this->_isMailchimp ) ) {
			$this->_isMailchimp = stripos( $this->_originalCode, "mailchimp" ) !== false ||
			                      stripos( $this->_originalCode, 'mc_embed_signup' ) !== false ||
			                      stripos( $this->_originalCode, 'mc-embedded-subscribe' ) !== false;
		}

		return $this->_isMailchimp;
	}

	/**
	 * get the type of form we're editing
	 * @return string
	 */
	public function getFormType() {
		$form_type_id = $_POST['post_id'];
		if ( function_exists( 'tve_leads_get_form_type' ) ) {
			$form_type = tve_leads_get_form_type( $form_type_id, array( 'get_variations' => false ) );
			if ( $form_type && $form_type->post_name ) {
				if($form_type->post_type == "tve_lead_shortcode" || $form_type->post_type == "tve_lead_2s_lightbox") {
					return $form_type->post_type;
				}
				return $form_type->post_name;
			}
		}

		return 'lead_generation';
	}

	public function getFormVariations() {
		$variation_id = $_POST['_key'];

		if ( function_exists( 'tve_leads_get_form_related_states' ) ) {
			$states = tve_leads_get_form_related_states($variation_id);
			if ( $states ) {
				foreach($states as $key => $state) {
					if($state['key'] == $variation_id) {
						unset($states[$key]);
					}
				}

				return $states;
			}
		}

		return false;
	}

	/**
	 * prepare an action filter which can be used to append fields to the custom form html code
	 *
	 * @param string $code
	 *
	 * @return string
	 */
	public function prepareFilterHook( $code = null ) {
		/**
		 * Apply filters that will append inputs at the end of the single form that exists inside the autoresponder code
		 */
		if ( $code !== null && stripos( $code, '</form>' ) === false ) {
			return '';
		}

		$group     = null;
		$form_type = null;
		$variation = null;

		$form_type_id = $_POST['post_id'];

		if ( function_exists( 'tve_leads_get_form_type' ) ) {
			$form_type = tve_leads_get_form_type( $form_type_id, array( 'get_variations' => false ) );
			if ( $form_type && $form_type->post_parent ) {
				$group = get_post( $form_type->post_parent );
			}
		}

		if ( ! empty( $_POST['custom'] ) && ! empty( $_POST['custom']['_key'] ) && function_exists( 'tve_leads_get_form_variation' ) ) {
			$variation = tve_leads_get_form_variation( null, $_POST['custom']['_key'] );
		}

		$tve_leads_ids = array(
			'group_id'     => $group ? $group->ID : null,
			'form_type_id' => $form_type ? $form_type->ID : null,
			'variation_id' => $variation ? $variation['key'] : null,
		);

		return '__CONFIG_tve_leads_additional_fields_filters__' . json_encode( $tve_leads_ids ) . '__CONFIG_tve_leads_additional_fields_filters__';
	}

	/**
	 * @param string $html raw HTML containing the form
	 *
	 * @return array
	 */
	public function parseHtmlCode( $html ) {
		$this->_originalCode = $html;

		$autoresponder_code = $this->_stripExtraTags( $html );

		$additional_fields = $this->prepareFilterHook( $autoresponder_code );

		$parsed_responder_code = $this->domParse( $autoresponder_code );

		if ( $parsed_responder_code['parse_status'] == 0 || empty( $parsed_responder_code['elements'] ) ) {
			echo 0;
			die;
		}
		$parsed_responder_code['additional_fields'] = $additional_fields;

		$html = $this->_tableRenderer->fieldsTable( $parsed_responder_code['elements'] );

		$html .= '</div><a href="javascript:void(0)" id="thrive_btn_save_autoresponder_fields" class="tve_editor_button tve_editor_button_success tve_click" data-ctrl="function:auto_responder.save_custom_code">Save</a>';

		$parsed_responder_code['stripped_code']    = ( $this->_isMailchimp() && strpos( $autoresponder_code, 'mailchimp' ) === false ? '<span style="display:none">mailchimp</span>' : '' ) . $autoresponder_code;
		$parsed_responder_code['html']             = $html;
		$captcha_api                               = Thrive_Dash_List_Manager::credentials( 'recaptcha' );
		$parsed_responder_code['captcha_site_key'] = empty( $captcha_api['site_key'] ) ? '' : $captcha_api['site_key'];

		return $parsed_responder_code;
	}

	/**
	 * Parse the autoresponder code and generate a response array
	 *
	 * @param string $code The autoresponder code
	 *
	 * @return array Response array with the form fields and attributes *
	 */
	public function domParse( $code ) {
		$response = array(
			'form_action'        => "",
			'hidden_inputs'      => "",
			'form_method'        => 'POST',
			'parse_status'       => 0,
			'not_visible_inputs' => '',
			'is_mailchimp'       => false,
			'elements'           => array(),
			'element_order'      => array()
		);

		if ( empty( $code ) ) {
			return $response;
		}

		$response['is_mailchimp'] = $this->_isMailchimp();

		$DOM = new DOMDocument;

		try {

			$loadDom = @$DOM->loadHTML( '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . $code );
			if ( ! $loadDom ) {
				return $response;
			}

			$form_elements = @$DOM->getElementsByTagName( 'form' );

			if ( $form_elements->length > 0 ) {
				$response['form_action'] = $form_elements->item( 0 )->getAttribute( 'action' );
				$response['form_method'] = $form_elements->item( 0 )->getAttribute( 'method' );
			}

			$xpath  = new DOMXPath( $DOM );
			$inputs = $xpath->query( '//input | //select | //textarea | //text()', $form_elements->item( 0 ) );
			$length = $inputs->length;
			/**
			 * an over simplified version of shortcode regex - we are only interested in something that looks like a shortcode
			 */
			$shortcode_regex = '#\[(.+)\]#s';

			for ( $i = 0; $i < $length; $i ++ ) {
				$element = $inputs->item( $i );
				if ( $element->nodeType == XML_TEXT_NODE && ( $text = trim( $element->nodeValue ) ) ) {
					if ( preg_match( $shortcode_regex, $text ) ) {
						$shortcode_index                                            = isset( $shortcode_index ) ? $shortcode_index : 0;
						$response['elements'][ 'shortcode_' . $shortcode_index ++ ] = array(
							'encoded_name' => 'shortcode_' . $shortcode_index ++,
							'type'         => 'shortcode',
							'value'        => $text
						);
						continue;
					}
				}

				$type = ucfirst( strtolower( $element->nodeName ) );

				$method = 'readElement' . $type;

				if ( method_exists( $this, $method ) ) {
					$this->{$method}( $element, $response );
				}
			}
			$response['parse_status'] = 1;
			foreach ( $response['elements'] as $n => $elem ) {
				$response['elements'][ $n ]['display'] = 1;
			}
			/**
			 * we need to store the element order, as javascript will not respect the same order in a json object
			 */
			$response['element_order'] = array_keys( $response['elements'] );
		} catch ( Exception $e ) {
			$response['parse_status'] = 0;
		}

		return $response;
	}

	/**
	 *
	 * @param DOMElement $element current element (input / select / textarea)
	 * @param array $response
	 */
	public function readElementInput( DOMElement $element, & $response ) {
		$element_type = $element->getAttribute( 'type' );
		if ( empty( $element_type ) ) {
			$element_type = 'text';
		}
		if ( $element_type == "hidden" ) {
			$element_name      = $element->getAttribute( 'name' );
			$element_value     = $element->getAttribute( 'value' );
			$temp_hidden_input = "<input type='hidden' name='" . $element_name . "' value='" . $element_value . "' />";
			$response['hidden_inputs'] .= $temp_hidden_input;

			return;
		}

		$text_input_types = array(
			'text',
			'email',
			'tel',
			'url',
			'time',
			'week',
			'color',
			'date',
			'datetime',
			'datetime-local',
			'month',
			'number',
			'search'
		);

		$o_name = $element->getAttribute( 'name' );
		if ( substr( $o_name, - 2 ) == '[]' ) {
			if ( ! isset( $this->_usedIds[ $o_name ] ) ) {
				$this->_usedIds[ $o_name ] = 0;
			} else {
				++ $this->_usedIds[ $o_name ];
			}
			$o_name = substr( $o_name, 0, - 2 ) . '[' . $this->_usedIds[ $o_name ] . ']';
		}


		$element_name = $this->attrName( $o_name );
		if ( in_array( $element_type, $text_input_types ) ) {
			//hot fix for mailchimp
			if ( $this->_isMailchimp() && strlen( $element_name ) > 30 ) {
				$element_value     = str_replace( " ", "", $element->getAttribute( 'value' ) );
				$temp_hidden_input = '<input type="text" style="position: absolute !important; left: -5000px !important;" name="' . $o_name . '" value="' . $element_value . '" />';
				$response['not_visible_inputs'] .= $temp_hidden_input;
			} else {
				$response['elements'][ $element_name ] = array(
					'encoded_name' => $element_name,
					'type'         => 'text',
					'name'         => $o_name
				);
			}
		} elseif ( $element_type === 'radio' ) {
			$value = $element->getAttribute( 'value' );
			if ( ! isset( $response['elements'][ $element_name ] ) ) {
				$response['elements'][ $element_name ] = array(
					'encoded_name' => $element_name,
					'type'         => 'radio',
					'name'         => $o_name,
					'options'      => array()
				);
			}
			$response['elements'][ $element_name ]['options'][ sanitize_title( $value ) ] = $value;
		} elseif ( $element_type === 'checkbox' ) {
			$value                                 = $element->getAttribute( 'value' );
			$response['elements'][ $element_name ] = array(
				'encoded_name' => $element_name,
				'type'         => 'checkbox',
				'name'         => $o_name,
				'value'        => $value,
			);
		}

	}

	/**
	 *
	 * @param DOMElement $DOMDropDown current element (input / select / textarea)
	 * @param array $response
	 */
	public function readElementSelect( DOMElement $DOMDropDown, & $response ) {
		$options        = array();
		$o_name         = $DOMDropDown->getAttribute( 'name' );
		$drop_down_name = $this->attrName( $o_name );

		$DOMOptions = $DOMDropDown->getElementsByTagName( 'option' );
		for ( $j = 0; $j < $DOMOptions->length; $j ++ ) {
			/* @var $option DOMElement */
			$DOMOption         = $DOMOptions->item( $j );
			$value             = $DOMOption->hasAttribute( 'value' ) ? $DOMOption->getAttribute( 'value' ) : $DOMOption->textContent;
			$options[ $value ] = $DOMOption->textContent;
		}

		if ( ! isset( $options[''] ) ) {
			$new_options = array(
				'' => $o_name,
			);
			$options     = array_merge( $new_options, $options );
		}

		$sorted = array();
		foreach ( $options as $value => $label ) {
			$sorted [] = array(
				'label' => $label,
				'value' => $value
			);
		}

		$response['elements'][ $drop_down_name ] = array(
			'encoded_name'  => $drop_down_name,
			'type'          => 'select',
			'default_value' => $options[''],
			'name'          => $o_name,
			'options'       => $sorted
		);
	}

	/**
	 *
	 * @param DOMElement $DOMTextarea current element (input / select / textarea)
	 * @param array $response
	 */
	public function readElementTextarea( DOMElement $DOMTextarea, & $response ) {
		$o_name                                 = $DOMTextarea->getAttribute( 'name' );
		$textarea_name                          = $this->attrName( $o_name );
		$response['elements'][ $textarea_name ] = array(
			'encoded_name' => $textarea_name,
			'type'         => 'textarea',
			'name'         => $o_name
		);
	}

	public function attrName( $attr ) {
		$attr = str_replace( "[", "_tbl_", $attr );
		$attr = str_replace( "]", "_tbr_", $attr );
		$attr = str_replace( "(", "_tbl2_", $attr );
		$attr = str_replace( ")", "_tbr2_", $attr );
		$attr = str_replace( " ", "_tsp_", $attr );
		$attr = str_replace( ".", "_tspnt_", $attr );
		$attr = str_replace( "/", "_ts_", $attr );
		$attr = str_replace( ",", "_tc_", $attr );
		$attr = str_replace( ":", "_tcol_", $attr );

		return $attr;
	}

	public function attrNameFixed( $attr ) {
		$attr = str_replace( "_tbl_", "[", $attr );
		$attr = str_replace( "_tbr_", "]", $attr );
		$attr = str_replace( "_tbl2_", "(", $attr );
		$attr = str_replace( "_tbr2_", ")", $attr );
		$attr = str_replace( "_tsp_", " ", $attr );
		$attr = str_replace( "_tspnt_", ".", $attr );
		$attr = str_replace( "_ts_", "/", $attr );
		$attr = str_replace( "_tc_", ",", $attr );
		$attr = str_replace( "_tcol_", ":", $attr );

		return $attr;
	}

}