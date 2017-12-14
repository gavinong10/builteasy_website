<?php

/**
 * Created by PhpStorm.
 * User: radu
 * Date: 19.09.2014
 * Time: 10:15
 */

if ( ! class_exists( 'TCB_Landing_Page' ) ) {

	class TCB_Landing_Page {
		const HOOK_HEAD = 'tcb_landing_head';
		const HOOK_BODY_OPEN = 'tcb_landing_body_open';
		const HOOK_FOOTER = 'tcb_landing_footer';
		const HOOK_BODY_CLOSE = 'tcb_landing_body_close';

		/**
		 * landing page id
		 * @var int
		 */
		protected $id;

		/**
		 * holds the configuration array for the landing page
		 * @var array
		 */
		protected $config = array();

		/**
		 * holds the tve_globals meta configuration values
		 * @var array
		 */
		protected $globals = array();

		/**
		 * currently used landing page template
		 * @var string
		 */
		protected $template = '';

		/**
		 * javascripts for the head and footer section, if any
		 *
		 * @var array
		 */
		protected $globalScripts = array();

		/**
		 * stores the configuration for a template downloaded from the cloud, if this landing page is using one
		 *
		 * @var array
		 */
		protected $cloudTemplateData = array();

		/**
		 * flag that holds whether or not this is a template downloaded from the cloud
		 *
		 * @var bool
		 */
		protected $isCloudTemplate = false;

		/**
		 * holds the events setup from page event manager
		 *
		 * @var array
		 */
		protected $page_events = array();

		/**
		 * sent all necessary parameters to avoid extra calls to get_post_meta
		 *
		 * @param int $landing_page_id
		 * @param string $landing_page_template
		 */
		public function __construct( $landing_page_id, $landing_page_template ) {
			$this->id            = $landing_page_id;
			$this->globals       = tve_get_post_meta( $landing_page_id, 'tve_globals' );
			$this->config        = tve_get_landing_page_config( $landing_page_template );
			$this->template      = $landing_page_template;
			$this->globalScripts = get_post_meta( get_the_ID(), 'tve_global_scripts', true );
			$this->page_events   = tve_get_post_meta( $landing_page_id, 'tve_page_events' );
			$this->page_events   = empty( $this->page_events ) ? array() : $this->page_events;

			if ( tve_is_cloud_template( $landing_page_template ) ) {
				$this->isCloudTemplate   = true;
				$this->cloudTemplateData = tve_get_cloud_template_config( $landing_page_template );
			}

			if ( empty( $this->globals ) ) {
				$this->globals = array();
			}
		}

		/**
		 * outputs the HEAD section specific to the landing page
		 * finally, it calls the tcb_landing_head hook to allow injecting other stuff in the head
		 */
		public function head() {
			/* I think the favicon should be added using the wp_head hook and not like this */
			if ( function_exists( 'thrive_get_options_for_post' ) ) {
				$options = thrive_get_options_for_post();
				if ( ! empty( $options['favicon'] ) ) : ?>
					<link rel="shortcut icon" href="<?php echo $options['favicon']; ?>"/>
				<?php endif;
			}

			$this->fonts();

			if ( ! empty( $this->globalScripts['head'] ) && ! is_editor_page() ) {
				$this->globalScripts['head'] = $this->removeJQuery( $this->globalScripts['head'] );
				echo $this->globalScripts['head'];
			}

			empty( $this->globals['do_not_strip_css'] ) ?
				$this->stripHeadCss() : wp_head();

			/* finally, call the tcb_landing_head hook */
			apply_filters( self::HOOK_HEAD, $this->id );
		}

		/**
		 * outputs <link>s for each font used by the page
		 * fonts come from the configuration array
		 *
		 * @return TCB_Landing_Page allows chained calls
		 */
		protected function fonts() {
			if ( empty( $this->config['fonts'] ) ) {
				return $this;
			}
			foreach ( $this->config['fonts'] as $font ) {
				echo sprintf( '<link href="%s" rel="stylesheet" type="text/css" />', $font );
			}

			return $this;
		}

		/**
		 * this calls the WP wp_head() function, it will remove every <style>..</style> from the head
		 */
		protected function stripHeadCss() {
			/* capture the output and strip out some of the <style></style> nodes */
			ob_start();
			wp_head();
			$contents = ob_get_clean();
			/* keywords to search for within the CSS rules */
			$tcb_rules_keywords = array(
				'.ttfm',
				'data-tve-custom-colour',
				'.tve_more_tag',
				'.thrive-adminbar-icon',
				'#wpadminbar',
				'html { margin-top: 32px !important; }'
			);
			/* keywords to search for within CSS style node - classes and ids for the <style> element */
			$tcb_style_classes = array( 'tve_user_custom_style', 'tve_custom_style' );

			if ( preg_match_all( '#<style(.*?)>(.+?)</style>#ms', $contents, $m ) ) {
				foreach ( $m[2] as $index => $css_rules ) {
					$css_node  = $m[1][ $index ];
					$remove_it = true;
					foreach ( $tcb_rules_keywords as $tcb_keyword ) {
						if ( strpos( $css_rules, $tcb_keyword ) !== false ) {
							$remove_it = false;
							break;
						}
					}
					if ( $remove_it ) {
						foreach ( $tcb_style_classes as $style_class ) {
							if ( strpos( $css_node, $style_class ) !== false ) {
								$remove_it = false;
								break;
							}
						}
					}
					if ( $remove_it ) {
						$contents = str_replace( $m[0][ $index ], '', $contents );
					}
				}
			}
			echo $contents;
		}

		/**
		 * get all the css data needed for this landing page that's been previously saved from the editor
		 * example: body background, content background (if content is outside tve_editor) etc
		 *
		 * @return array
		 */
		public function getCssData() {
			$config  = $this->globals;
			$lp_data = array(
				'custom_color' => ! empty( $config['lp_bg'] ) ? ' data-tve-custom-colour="' . $config['lp_bg'] . '"' : '',
				'class'        => ! empty( $config['lp_bgcls'] ) ? ' ' . $config['lp_bgcls'] : '',
				'css'          => '',
				'main_area'    => array(
					'css' => ''
				)
			);
			if ( ! empty( $config['lp_bg'] ) && $config['lp_bg'] == '#ffffff' ) {
				$lp_data['custom_color'] = '';
				$lp_data['css'] .= 'background-color:#ffffff;';
			}
			if ( ! empty( $config['lp_bgp'] ) ) {
				if ( $config['lp_bgp'] === 'none' ) {
					$background_string = "background-image:none;";
				} else {
					$background_string = "background-image:url('{$config['lp_bgp']}');";
				}
				$lp_data['css'] .= $background_string . "background-repeat:repeat;background-size:auto;";
			} elseif ( ! empty( $config['lp_bgi'] ) ) {
				if ( $config['lp_bgi'] === 'none' ) {
					$background_string = "background-image:none;";
				} else {
					$background_string = "background-image:url('{$config['lp_bgi']}');";
				}
				$lp_data['css'] .= $background_string . "background-repeat:no-repeat;background-size:cover;background-position:center center;";
			}
			if ( ! empty( $config['lp_bga'] ) ) {
				$lp_data['css'] .= "background-attachment:{$config['lp_bga']};";
				if ( $config['lp_bga'] == 'fixed' ) {
					$lp_data['class'] .= ( $lp_data['class'] ? ' ' : '' ) . 'tve-lp-fixed';
				}
			}
			if ( ! empty( $config['lp_cmw'] ) && ! empty( $config['lp_cmw_apply_to'] ) ) { // landing page - content max width
				if ( $config['lp_cmw_apply_to'] == 'tve_post_lp' ) {
					$lp_data['main_area']['css'] .= "max-width: {$config['lp_cmw']}px;";
				}
			}

			$lp_data['class'] .= ! empty( $lp_data['class'] ) ? ' tve_lp' : 'tve_lp';

			return $lp_data;
		}

		/**
		 * called right after <body> open tag
		 */
		public function afterBodyOpen() {
			if ( ! empty( $this->globalScripts['body'] ) && ! is_editor_page() ) {
				$this->globalScripts['body'] = $this->removeJQuery( $this->globalScripts['body'] );
				echo $this->globalScripts['body'];
			}

			apply_filters( self::HOOK_BODY_OPEN, $this->id );
		}

		/**
		 * called before the WP get_footer hook
		 */
		public function footer() {
			apply_filters( self::HOOK_FOOTER, $this->id );
		}

		/**
		 * called right before the <body> end tag
		 */
		public function beforeBodyEnd() {
			apply_filters( self::HOOK_BODY_CLOSE, $this->id );

			if ( ! empty( $this->globalScripts['footer'] ) && ! is_editor_page() ) {
				$this->globalScripts['footer'] = $this->removeJQuery( $this->globalScripts['footer'] );
				echo $this->globalScripts['footer'];
			}
		}

		/* general usability functions - implemented like this - more developer friendly */

		/**
		 * whether or not this landing page should have lightbox associated
		 */
		public function needsLightbox() {
			return ! empty( $this->config['has_lightbox'] );
		}

		/**
		 * check if the associated lightbox exists and, if not, create it
		 */
		public function checkLightbox() {
			$this->replaceDefaultTexts();

			if ( ! $this->needsLightbox() ) {
				return;
			}

			if ( ! empty( $this->globals['lightbox_id'] ) ) {
				$lightbox = get_post( $this->globals['lightbox_id'] );
				if ( $lightbox && ( $lightbox->post_status === 'trash' || $lightbox->post_type != 'tcb_lightbox' ) ) {
					$lightbox = array();
				}
			}

			if ( empty( $lightbox ) ) {

				$this->globals['lightbox_id'] = $this->newLightbox();

				tve_update_post_meta( $this->id, 'tve_globals', $this->globals );
			}
			if ( ! empty( $this->config['lightbox'] ) && ! empty( $this->config['lightbox']['exit_intent'] ) && ! $this->has_page_exit_intent() ) {
				/* setup the lightbox to be triggered on exit intent */
				$this->page_events    = empty( $this->page_events ) ? array() : $this->page_events;
				$this->page_events [] = array(
					't'      => 'exit',
					'a'      => 'thrive_lightbox',
					'config' => array(
						'e_mobile' => '1',
						'e_delay'  => '30',
						'l_id'     => $this->globals['lightbox_id'],
						'l_anim'   => 'slide_top',
					)
				);
				tve_update_post_meta( $this->id, 'tve_page_events', $this->page_events );
			}

			/* check if the id of the lightbox from the content is different than the id of the generated lightbox */
			$post_content = tve_get_post_meta( $this->id, 'tve_updated_post' );

			/* 12.10.2015 - lightbox events can also be setup with a simple string: tcb_open_lightbox */
			$open_lightbox_event = '{tcb_open_lightbox}';
			$events_config       = array(
				array(
					't'      => 'click',
					'a'      => 'thrive_lightbox',
					'config' => array(
						'l_id'   => empty( $this->globals['lightbox_id'] ) ? '' : $this->globals['lightbox_id'],
						'l_anim' => 'slide_top'
					)
				)
			);
			$post_content        = str_replace( $open_lightbox_event, '__TCB_EVENT_' . htmlentities( json_encode( $events_config ) ) . '_TNEVE_BCT__', $post_content, $number_of_replacements );
			$save_it             = $number_of_replacements;

			if ( strpos( $post_content, "&quot;l_id&quot;:&quot;{$this->globals['lightbox_id']}&quot;" ) === false ) {
				$post_content = preg_replace( '#&quot;l_id&quot;:(null|&quot;(.*?)&quot;)#', '&quot;l_id&quot;:&quot;' . $this->globals['lightbox_id'] . '&quot;', $post_content );
				$save_it      = true;
			}

			if ( $save_it ) {
				tve_update_post_meta( $this->id, 'tve_updated_post', $post_content );
				tve_update_post_meta( $this->id, 'tve_save_post', $post_content );
			}

		}

		/**
		 * generate new lightbox specific for this landing page
		 */
		public function newLightbox() {
			$landing_page = get_post( $this->id );

			$tcb_content = $this->lightboxDefaultContent();

			$lightbox_globals = array(
				'l_cmw' => isset( $this->config['lightbox']['max_width'] ) ? $this->config['lightbox']['max_width'] : '600px',
				'l_cmh' => isset( $this->config['lightbox']['max_height'] ) ? $this->config['lightbox']['max_height'] : '600px',
			);
			$all_lightboxes   = get_posts( array(
				'posts_per_page' => - 1,
				'post_type'      => 'tcb_lightbox',
			) );

			return tve_create_lightbox( 'Lightbox - ' . $landing_page->post_title . ' (' . $this->config['name'] . ')' . '-' . ( count( $all_lightboxes ) + 1 ), $tcb_content, $lightbox_globals, array( 'tve_lp_lightbox' => $this->template ) );
		}

		/**
		 * fetch default lightbox content from one of the files inside landing-page/lightbox/ folder
		 */
		public function lightboxDefaultContent() {
			if ( $this->isCloudTemplate ) {
				/* if it's a cloud template, the lightbox content needs to be fetched from wp-uploads/tcb_lp_templates/lightboxes/{template_name}.tpl */
				$lb_file  = trailingslashit( $this->config['base_dir'] ) . 'lightboxes/' . $this->template . '.tpl';
				$contents = '';
				if ( file_exists( $lb_file ) ) {
					$contents = file_get_contents( $lb_file );
				}

				return $this->replaceDefaultTexts( $contents );
			}

			/**
			 * from this point forward => this is a regular template - the lightbox content is available in a local php file from the plugin
			 */

			ob_start();
			if ( file_exists( dirname( dirname( __FILE__ ) ) . '/lightboxes/' . $this->template . '.php' ) ) {
				include dirname( dirname( __FILE__ ) ) . '/lightboxes/' . $this->template . '.php';
			}
			$contents = ob_get_contents();
			ob_end_clean();

			return $this->replaceDefaultTexts( $contents );
		}

		/**
		 * removes references to jquery loaded directly from CDN - this will break the editor scripts on this page
		 *
		 * @param string $custom_script
		 *
		 * @return string
		 */
		public function removeJQuery( $custom_script ) {
			if ( ! is_editor_page() ) {
				return $custom_script;
			}

			$js_search = '/src=(["\'])(.+?)((code.jquery.com\/jquery-|ajax.googleapis.com\/ajax\/libs\/jquery\/))(\d)(.+?)\1/si';

			return preg_replace( $js_search, 'src=$1$1', $custom_script );
		}

		/**
		 * replace all occurences of custom texts we currently use for generating server-specifing data
		 *
		 * {tcb_timezone}
		 *
		 * @param string $post_content if null it will take by default this contents of this landing page
		 */
		public function replaceDefaultTexts( $post_content = null ) {
			if ( null === $post_content ) {
				$update_post_meta = true;
				$post_content     = tve_get_post_meta( $this->id, 'tve_updated_post' );
			}

			if ( empty( $post_content ) ) {
				return '';
			}

			$save_it = false;

			/**
			 * {tcb_timezone}
			 */
			if ( strpos( $post_content, 'data-timezone="{tcb_timezone}"' ) !== false ) {
				$timezone_offset = get_option( 'gmt_offset' );
				$sign            = ( $timezone_offset < 0 ? '-' : '+' );
				$min             = abs( $timezone_offset ) * 60;
				$hour            = floor( $min / 60 );
				$tzd             = $sign . str_pad( $hour, 2, '0', STR_PAD_LEFT ) . ':' . str_pad( $min % 60, 2, '0', STR_PAD_LEFT );
				$post_content    = str_replace( 'data-timezone="{tcb_timezone}"', 'data-timezone="' . $tzd . '"', $post_content );
				$save_it         = true;
			}

			if ( strpos( $post_content, '{tcb_lp_base_url}' ) !== false ) {
				$replacement  = $this->isCloudTemplate ? trailingslashit( $this->cloudTemplateData['base_url'] ) . 'templates' : TVE_LANDING_PAGE_TEMPLATE;
				$post_content = str_replace( '{tcb_lp_base_url}', untrailingslashit( $replacement ), $post_content );
				$save_it      = true;
			}

			if ( isset( $update_post_meta ) && $save_it ) {
				tve_update_post_meta( $this->id, 'tve_updated_post', $post_content );
				tve_update_post_meta( $this->id, 'tve_save_post', $post_content );
			}

			return $post_content;
		}

		/**
		 * enqueue the CSS file needed for this template
		 */
		public function enqueueCss() {
			$handle = 'tve_landing_page_' . $this->template;

			if ( $this->isCloudTemplate ) {
				tve_enqueue_style( $handle, trailingslashit( $this->config['base_url'] ) . 'templates/css/' . $this->template . '.css', 100 );
			} else {
				tve_enqueue_style( $handle, TVE_LANDING_PAGE_TEMPLATE . '/css/' . $this->template . '.css', 100 );
			}
		}

		public function ensure_external_assets() {

			$lightbox_ids = array();

			/**
			 * look for page events
			 */
			foreach ( $this->page_events as $event ) {
				if ( isset( $event['a'] ) && $event['a'] === 'thrive_lightbox' && ! empty( $event['config'] ) && ! empty( $event['config']['l_id'] ) ) {
					$lightbox_ids[] = $event['config']['l_id'];
				}
			}

			/**
			 * look for page invents in content
			 */
			$post_content = tve_get_post_meta( $this->id, 'tve_updated_post' );
			if ( preg_match_all( '#&quot;l_id&quot;:(null|&quot;(.*?)&quot;)#', $post_content, $matches ) ) {
				$lightbox_ids = array_merge( $lightbox_ids, $matches[2] );
			}

			$lightbox_ids = array_unique( $lightbox_ids );

			global $post;
			$old_post = $post;

			/**
			 * This code is executed really early in the request - and sometimes it generates output ( before the <html> tag )
			 * we need to catch and ignore this output
			 */
			ob_start();

			/**
			 * let the others do their content and add their scripts
			 */
			foreach ( $lightbox_ids as $id ) {
				$post = get_post( $id );
				apply_filters( 'the_content', '' );
			}

			/**
			 * get rid of any undesired output.
			 */
			ob_end_clean();

			$post = $old_post;
		}

		/**
		 * check if this landing page has a "Exit Intent" event setup to display a lightbox
		 */
		public function has_page_exit_intent() {
			if ( empty( $this->page_events ) ) {
				return false;
			}
			foreach ( $this->page_events as $page_event ) {
				if ( ! empty( $page_event['t'] ) && ! empty( $page_event['a'] ) && $page_event['t'] == 'exit' && ( $page_event['a'] == 'thrive_lightbox' || $page_event['a'] == 'thrive_leads_2_step' ) ) {
					return true;
				}
			}

			return false;
		}

	}
}