<?php



/*	class pluginbuddy_settings
 *	@author Dustin Bolton
 *	
 *	Handles setting up and parsing submitted data for settings pages. Uses form class for handling forms.
 *	If a savepoint is passed to the constructor then settings will be auto-saved on save.
 *	If false is passed to the savepoint then the process() function may be used to validate and grab submitted form data for custom processing.
 *	@see pluginbuddy_form
 *	
 */
class pb_backupbuddy_settings {
	
	
	
	// ********** PUBLIC PROPERTIES **********
	
	
	
	// ********** PRIVATE PROPERTIES **********
	private $_form;
	private $_form_name = '';
	private $_prefix = '';
	private $_savepoint;
	private $_settings = array();
	private $_custom_title_width = '';
	
	
	
	// ********** FUNCTIONS **********
	
	
	
	/*	pluginbuddy_settings->__construct()
	 *	
	 *	Default constructor.
	 *	
	 *	@param		string			$form_name					Name / slug of the form.
	 *	@param		string			$save_point_or_custom_mode	Location in pb_backupbuddy::$options array to save to. Ex: groups#5 saves into: pb_backupbuddy::$options['groups'][5].
	 *				false										If false the process() function will not save but will return results instead including form name => value pairs in an array for processing.
	 *				array										If array then these will be treated as the defaults. Works the same as being false other than this.
	 *	@param		string			$additional_query_string	Additional querystring variables to pass in the form action URL.
	 *	@param		int				$custom_title_width			Custom title width in pixels. Formats table sizing.
	 *	@return		null
	 */
	function __construct( $form_name, $save_point_or_custom_mode, $additional_query_string = '', $custom_title_width = '' ) {
		$this->_form_name = $form_name;
		$this->_prefix = 'pb_' . pb_backupbuddy::settings( 'slug' ) . '_';
		$this->_savepoint = $save_point_or_custom_mode;
		$this->_custom_title_width = $custom_title_width;
		
		// TODO: no need to pass savepoint here? below:
		$this->_form = new pb_backupbuddy_form( $form_name, $save_point_or_custom_mode, $additional_query_string );
	} // End __construct().
	
	
	
	/*	pluginbuddy_settings->add_setting()
	 *	
	 *	Register and add a setting to the settings form system.
	 *	
	 *	@param		array		$settings		Array of settings for this added setting. See $default_settings for list of options that can be defined.
	 *	@return		
	 */
	function add_setting( $settings ) {
		$default_settings = array (
			'type'				=>		'',
			'name'				=>		'',
			'title'				=>		'',
			'tip'				=>		'',
			'css'				=>		'',
			'before'			=>		'',
			'after'				=>		'',
			'rules'				=>		'',
			'default'			=>		'',					// IMPORTANT: Overrides default array. Also useful if savepoint is === false to override.
			'options'			=>		array(),
			'orientation'		=>		'horizontal',		// Used by radio and checkboxes. TODO: still need to add to checkboxes.
			'class'				=>		'',
			'classes'			=>		'',					// String of additional classes.
			'row_class'			=>		'',					// Class to apply to row td's in row.
		);
		$settings = array_merge( $default_settings, $settings );
		$this->_settings[] = $settings;
		
		
		// Figure out defaults.
		if ( $settings['default'] != '' ) { // Default was passed to add_setting().
			$default_value = $settings['default'];
		} else { // No default explictly set.
			$savepoint = $this->_savepoint;
			$raw_name = $settings['name'];
			
			if ( stristr( $settings['name'], '#' ) );
			if ( false !== ( $last_hashpoint = strrpos( $settings['name'], '#' ) ) ) {
				$temp_savepoint = substr( $settings['name'], 0, $last_hashpoint );
				if ( ( $savepoint === false ) || ( $savepoint == '' ) ) {
					$savepoint = $temp_savepoint;
				} else {
					$savepoint = $savepoint . '#' . $temp_savepoint;
				}
				$raw_name = substr( $settings['name'], $last_hashpoint + 1 ); // Item name with savepoint portion stripped out.
			}
			if ( $savepoint !== false ) {
				
				if ( is_array( $savepoint ) ) { // Array of defaults was passed instead of savepoint.
					if ( isset( $savepoint[ $raw_name ] ) ) {
						$default_value = $savepoint[ $raw_name ];
					} else {
						$default_value = '';
					}
				} else { // No defaults provided, seek them out in plugins options array.
					
					// Default values are overwritten after a process() run with the latest data if a form was submitted.
					$group = pb_backupbuddy::get_group( $savepoint );
					if ( $group === false ) {
						$default_value = '';
					} else {
						if ( isset( $group[ $raw_name ] ) ) { // Default is defined.
							$default_value = $group[ $raw_name ];
						} else { // Default not defined.
							$default_value = '';
						}
					}
					
				} // end finding defaults in plugin options.
			} else { // Custom mode without a savepoint provided so no default set unless passed to add_setting().
				$default_value = '';
			}
		}
		
		
		// Process adding form item for the setting based on type.
		switch( $settings['type'] ) {
			case 'text':
				$this->_form->text( $settings['name'], $default_value, $settings['rules'] );
				break;
			case 'plaintext':
				$this->_form->plaintext( $settings['name'], $default_value );
				break;
			case 'color':
				$this->_form->color( $settings['name'], $default_value, $settings['rules'] );
				break;
			case 'hidden':
				$this->_form->hidden( $settings['name'], $default_value, $settings['rules'] );
				break;
			case 'wysiwyg':
				$this->_form->wysiwyg( $settings['name'], $default_value, $settings['rules'], $settings['settings'] );
				break;
			case 'textarea':
				$this->_form->textarea( $settings['name'], $default_value, $settings['rules'] );
				break;
			case 'select':
				$this->_form->select( $settings['name'], $settings['options'], $default_value, $settings['rules'] );
				break;
			case 'password':
				$this->_form->password( $settings['name'], $default_value, $settings['rules'] );
				break;
			case 'radio':
				$this->_form->radio( $settings['name'], $settings['options'], $default_value, $settings['rules'] );
				break;
			case 'checkbox':
				$this->_form->checkbox( $settings['name'], $settings['options'], $default_value, $settings['rules'] );
				break;
			case 'submit':
				$this->_form->submit( $settings['name'], 'DEFAULT' ); // Submit button text is set in display_settings() param.
				break;
			case 'title':
				$this->_form->title( $settings['name'], $default_value, $settings['rules'] ); // Submit button text is set in display_settings() param.
				break;
			default:
				echo '{Error: Unknown settings type.}';
				break;
		} // End switch().
		
	} // End add_setting().
	
	
	
	/*	pluginbuddy_settings->process()
	 *	
	 *	Processes the form if applicable (if it was submitted).
	 *	TODO: Perhaps add callback ability to this?
	 *	This must come after all form elements have been added.
	 *	This should usually happen in the controller prior to loading a view.
	 *	IMPORTANT: Applies trim() to all submitted form values!
	 *	
	 *	@return		null/array				When a savepoint was defined in class constructor nothing is returned. (normal operation)
	 *										When savepoint === false an array is returned for custom form processing.
	 *										Format: array( 'errors' => false/array, 'data' => array( 'form_keys' => 'form_values' ) ).
	 */
	public function process() {
		if ( ( '' != ( $form_name = pb_backupbuddy::_POST( $this->_prefix ) ) ) && ( pb_backupbuddy::_POST( $this->_prefix ) == $this->_form_name ) ) { // This form was indeed submitted. PROCESS IT!
			// TODO:
			$errors = array();
			$_posts = pb_backupbuddy::_POST();
			
			// Cleanup
			foreach( $_posts as &$post_value ) {
				$post_value = trim( $post_value );
			}
			
			// loop through all posted variables, if its prefix matches this form's name then
			foreach( $_posts as $post_name => $post_value ) {
				if ( substr( $post_name, 0, strlen( $this->_prefix ) ) == $this->_prefix ) { // This settings form.
					$item_name = substr( $post_name, strlen( $this->_prefix ) );
					if ( ( $item_name != '' ) && ( $item_name != 'settings_submit' ) ) { // Skip the form name input; also settings submit button since it is not registered until view.
						if ( true !== ( $test_result = $this->_form->test( $item_name, $post_value ) ) ) {
							foreach( $this->_settings as $setting_index => $setting ) {
								if ( $setting['type'] == 'title' ) {
									continue;
								}
								if ( $setting['name'] == $item_name ) {
									$this->_settings[$setting_index]['error'] = true;
									$item_title = $this->_settings[$setting_index]['title'];
								}
							}
							$errors[] = 'Validation failure on `' . $item_title . '`: ' . implode( ' ', $test_result );
							unset( $_posts[$post_name] ); // Removes this form item so it will not be updated during save later.
						} else { // Item validated. Remove prefix for later processing.
							$_posts[$item_name] = $_posts[$post_name];
							$this->_form->set_value( $item_name, $post_value ); // Set value to be equal to submitted value so if one or more item failed validation the entire form is not wiped out. Don't want user to have to re-enter valid data.
							unset( $_posts[$post_name] );
						}
					} else { // Submit button. Can unset it to clean up array for later.
						unset( $_posts[$post_name] );
					}
				} else { // Not for this form. Can unset it to clean up array for later.
					unset( $_posts[$post_name] );
				}
			}
			
			// Process!
			
			
			// Only save if in normal settings mode; if savepoint === false no saving here.
			if ( ( $this->_savepoint === false ) || is_array( $this->_savepoint ) ) {
				//foreach( $_posts as $post_name => $post_value ) { // Validation above should haveo only left items for this form. Strip prefixes before passing on.
				//	$post_name = substr( $post_name, strlen( $this->_prefix ) );
				//}
				$return = array(
								'errors'	=>		$errors,
								'data'		=>		$_posts,
							);
				return $return;
			} else { // Normal settings since savepoint !== false. Save into savepoint!
				
				if ( count( $errors ) > 0 ) { // Errors.
					pb_backupbuddy::alert( 'Error validating one or more fields as indicated below. Error(s):<br>' . implode( '<br>', $errors ), true );
				}
				// Prepare savepoint.
				if ( $this->_savepoint != '' ) {
					$savepoint_root = $this->_savepoint . '#';
				} else {
					$savepoint_root = '';
				}
				
				// The hard work.
				foreach( $_posts as $post_name => $post_value ) { // Loop through all post items (not all may be our form). @see 83429594837534987.
					//if ( substr( $post_name, 0, strlen( $this->_prefix ) ) == $this->_prefix ) { // If prefix matches, its this form.
						//$post_name = substr( $post_name, strlen( $this->_prefix ) ); // Set stripped prefix.
						//if ( $post_name != '' ) { // Skips the empty prefix name we used for verifying form. @see 83429594837534987.
							
							// Update form item value.
							$this->_form->set_value( $post_name, $post_value );
							
							// From old save_settings():
							$savepoint_subsection = &pb_backupbuddy::$options;
							$savepoint_levels = explode( '#', $savepoint_root . $post_name );
							foreach ( $savepoint_levels as $savepoint_level ) {
								$savepoint_subsection = &$savepoint_subsection{$savepoint_level};
							}
							// Apply settings.
							$savepoint_subsection = stripslashes_deep( $post_value ); // Remove WordPress' magic-quotes-nonsense.
						//}
					//}
				}
				
				// Add a note to the save alert that some values are skipped due to errors.
				$error_note = '';
				if ( count( $errors ) > 0 ) {
					$error_note = ' One or more fields skipped due to error.';
				}
				
				pb_backupbuddy::save();
				pb_backupbuddy::alert( __( 'Settings saved.' . $error_note, 'it-l10n-backupbuddy' ) );
				
				$return = array(
								'errors'	=>		$errors,
								'data'		=>		$_posts,
							);
				return $return;
				//} // end no errors.
			} // End if savepoint !=== false.
		} // end submitted form.
		
		
	} // End process().
	
	
	
	/*	pluginbuddy_settings->display_settings()
	 *	
	 *	Displays all the registered settings in this object. Entire form and HTML is echo'd out.
	 *	@see pluginbuddy_settings->get_settings()
	 *	
	 *	@param		string		$submit_button_title		Text to display in the submit button.
	 *	@param		string		$before						Content before submit after.
	 *	@param		string		$after						Content after submit button.
	 *	@return		null
	 */
	public function display_settings( $submit_button_title, $before = '', $after = '', $save_button_class = '' ) {
		echo $this->get_settings( $submit_button_title, $before, $after, $save_button_class );
	} // End display_settings().
	
	
	
	/*	pluginbuddy_settings->get_settings()
	 *	
	 *	Returns all the registered settings in this object. Entire form and HTML is returned.
	 *	@see pluginbuddy_settings->display_settings()
	 *	radio button additional options:  orientation [ vertical / horizontal ]
	 *	
	 *	@param		string		$submit_button_title		Text to display in the submit button.
	 *	@param		string		$before						Content before submit after.
	 *	@param		string		$after						Content after submit button.
	 *	@return		string									Returns entire string with everything in it to display.
	 */
	public function get_settings( $submit_button_title, $before = '', $after = '', $save_button_class = '' ) {
		$first_title = true; // first title's CSS top padding differs so must track.
		
		$return = $this->_form->start();
		$return .= '<table class="form-table">'; // style="max-width: 675px;">';
		foreach ( $this->_settings as $settings ) {
			$th_css = '';
			
			if ( $settings['title'] == '' ) { // blank title so hide left column.
				$th_css .= ' display: none;';
			}
			
			if ( $settings['type'] == 'title' ) { // Title item.
				if ( $first_title === true ) { // First title in list.
					$return .= '<tr style="border: 0;"><th colspan="2" style="border: 0; padding-top: 0; padding-bottom: 0;" class="' . $settings['row_class'] . '"><h3 class="title ' . $settings['class'] . '"';
					$return .= ' style="margin-top: 0; margin-bottom: 0.5em;"';
					$first_title = false;
				} else { // Subsequent titles.
					$return .= '<tr style="border: 0;"><th colspan="2" style="border: 0;" class="' . $settings['row_class'] . '"><h3 class="title ' . $settings['class'] . '"';
					$return .= ' style="margin: 0.5em 0;"';
				}
				
				$return .= '>' . $settings['title'] . '</h3></th>';
			} elseif ( $settings['type'] == 'hidden' ) { // hidden form item. no title.
				$return .= $this->_form->get( $settings['name'], $settings['css'], $settings['classes'] );
			} else { // Normal item.
				$return .= '<tr class="' . $settings['row_class'] . '">';
				$return .= '<th scope="row" class="' . $settings['row_class'] . '"';
				if ( $this->_custom_title_width != '' ) {
					$return .= ' style="width: ' . $this->_custom_title_width . 'px; ' . $th_css . '"';
				} else {
					$return .= ' style="' . $th_css . '"';
				}
				$return .= '>';
				$return .= $settings['title'];
				if ( isset( $settings['tip'] ) && ( $settings['tip'] != '' ) ) {
					$return .= pb_backupbuddy::$ui->tip( $settings['tip'], '', false );
				}
				$return .= '</th>';
				if ( $settings['type'] == 'title' ) { // Extend width full length for title item.
					$return .= ' colspan="2"';
				}
				
				$return .= '<td class="' . $settings['row_class'] . '"';
				if ( $settings['title'] == '' ) { // No title so hide left column.
					$return .= ' colspan="2"';
				}
				$return .= '>';
				$return .= $settings['before'];
				if ( isset( $settings['error'] ) && ( $settings['error'] === true ) ) {
					$settings['css'] .= 'border: 1px dashed red;';
				}
				$return .= $this->_form->get( $settings['name'], $settings['css'], $settings['classes'], $settings['orientation'] );
				$return .= $settings['after'];
				$return .= '</td>';
				$return .= '</tr>';
			}
			
			
			
			/*
			$form->add_setting( array(
				'type'		=>		'text',
				'name'		=>		'image_width',
				'title'		=>		'Image Width',
				'tip'		=>		'This controls the size of the images in the Carousel. Images will be generated from the original images uploaded. Images will not be upscaled larger than the originals. You may change this at any time.',
				'css'		=>		'text-align: right;',
				'after'		=>		'px',
				'rules'		=>		'required',
			) );
			*/
		}
		$return .= '</table><br>';

		// Submit button
		$return .= $before;
		$return .= $this->_form->submit( 'settings_submit', $submit_button_title, $save_button_class );
		$return .= $this->_form->get( 'settings_submit', '', $save_button_class ); //, $settings['name'], $settings['classes'] );
		$return .= $after;

		$return .= $this->_form->end();

		return $return;
	} // End get_settings().
	
	
	
	/*	clear_values()
	 *	
	 *	Clears the value of all form items setting the value to an empty string ''.
	 *	
	 *	@return		null
	 */
	public function clear_values() {
		$this->_form->clear_values();
		
		return;
	} // End clear_values().
	
	
	
	/*	set_value()
	 *	
	 *	Replace the value of a form item.
	 *	
	 *	@param		string	$item_name		Name of the form setting item to update.
	 *	@param		string	$value			Value to set the item to.
	 *	@return		null
	 */
	public function set_value( $item_name, $value ) {
		$this->_form->set_value( $item_name, $value );
		return;
	} // End set_value().
	
	
} // End class pluginbuddy_settings.



?>