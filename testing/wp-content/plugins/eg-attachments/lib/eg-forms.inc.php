<?php
/*
Package Name: EG-Forms
Package URI:
Description: Class for WordPress plugins
Version: 2.2.4
Author: Emmanuel GEORJON
Author URI: http://www.emmanuelgeorjon.com/
*/

/*
    Copyright 2009-2013 Emmanuel GEORJON  (email : blog@emmanuelgeorjon.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!class_exists('EG_Form_224')) {

	/**
	  * Class EG_Form_224
	  *
	  * Provide some functions to create a WordPress plugin
	  *
	 */
	Class EG_Form_224 {

		var $page_id;
		var $options_entry;
		var $options_group;
		var $textdomain;
		var $sidebar_callback;
		var $sidebar_action;
		var $title;
		var $header;
		var $footer;

		var $tabs	  = array();
		var $sections = array();
		var $fields	  = array();

		/**
		  * Class contructor
		  * Define the plugin url and path. Declare action INIT and HEAD.
		  *
		  * @package EG-Forms
		  * @return object
		  */
		function __construct($page_id, $options_group, $title, $options_entry, $textdomain=FALSE, $header='', $footer='', $sidebar_callback=FALSE) {

			$this->page_id 			 = $page_id;
			$this->title 			 = $title;
			$this->header 			 = $header;
			$this->footer			 = $footer;
			$this->options_entry     = $options_entry;
			$this->options_group     = $options_group;
			$this->textdomain		 = $textdomain;
			$this->sidebar_action    = 'eg_form_sidebar_'.$page_id;

			if ($sidebar_callback !== FALSE) {
				add_action($this->sidebar_action, $sidebar_callback);
			}
		} // End of __construct

		function set_form($tabs, $sections, $fields) {
			$this->tabs 	= $tabs;
			$this->sections = $sections;
			$this->fields	= $fields;

		} // End of set_form

		function add_tab($label, $header='') {
			$this->tabs[] = array(
					'label' => $label,
					'header' => $header
				);
			return (sizeof($this->tabs)-1);
		} // End of add_tab

		function add_section($label, $tab=1, $header='', $footer='') {
			$this->sections[] = array(
					'label' => $label,
					'tab' => $tab,
					'header' => $header,
					'footer' => $footer
				);
			return (sizeof($this->sections)-1);
		} // End of add_section

		function add_field($name, $label, $type, $section=0, $group=0,
						$before='', $after='', $desc='', $options='',
						$size='regular', $status='', $multiple=FALSE) {
			$this->fields[] = array(
					'name'		=> $name,
					'label'		=> $label,
					'type'		=> $type,
					'section'	=> $section,
					'group'		=> $group,
					'before'	=> $before,
					'after'		=> $after,
					'desc'		=> $desc,
					'options'	=> $options,
					'size'		=> $size,
					'status'	=> ( '' == $status || 'active' == $status ? '' : 'disabled' ),
					'multiple'	=> $multiple
				);
			return (sizeof($this->fields)-1);
		} // End of add_field

		/**
		 * display_comment
		 *
		 * Display a hidden field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 */
		function display_comment($field, $entry_name, $default_value) {
			return '';
		} // End of display_comment

		/**
		 * display_hidden
		 *
		 * Display a hidden field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 *
		 */
		function display_hidden($field, $entry_name, $default_value) {
			return ($this->display_text($field, $entry_name, $default_value));
		} // End of display_hidden

		/**
		 * display_password
		 *
		 * Display a password field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 *
		 */
		function display_password($field, $entry_name, $default_value) {
			return ($this->display_text($field, $entry_name, $default_value));
		} // End of display_password

		/**
		 * display_textarea
		 *
		 * Display a textarea field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 *
		 */
		function display_textarea($field, $entry_name, $default_value) {
			$string = '<textarea class="'.$field['size'].'-text" name="'.$entry_name.'" id="'.$field['name'].'" '.$field['status'].'>'.htmlspecialchars($default_value).'</textarea>';
			return ($string);
		} // End of display_textarea

		/**
		 * display_radio_table
		 *
		 * Display a list of radio field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 *
		 */
		function display_radio_table($field, $entry_name, $default) {

			$output = '<fieldset>'.
						'<legend class="screen-reader-text">'.
						'<span>'.__($field['label'], $this->textdomain).'</span>'.
						'</legend>'.
						'<div style="width: 90%; height: 200px; overflow:auto">'.
						'<table class="wp-list-table widefat">'.
						'<thead>'.
						'<tr>'.
							'<th class="check-column" scope="col">&nbsp;</th>';
			foreach ($field['list_options']['titles'] as $title) {
				$output .=	'<th scope="col" class="manage-column">'.__($title, $this->textdomain).'</th>';
			}
			$output .=	'</tr>'.
						'</thead>'.
						'<tbody>';
			$num = 0;
			$alternate = '';
			if ($field['options'] && sizeof($field['options'])>0) {
				foreach ($field['options'] as $value => $item) {
					$checked = ($value == $default ? ' checked' : '');
					$output .= '<tr '.('' == $alternate ? $alternate = 'class="alternate"' : $alternate = '').'>'.
									'<th class="check-column" scope="row">'.
										'<label for="'.$entry_name.'">'.
										'<input type="radio" value="'.esc_attr($value).'" name="'.$entry_name.'"'.$checked.' '.$field['status'].'/> '.
										'</label>'.
									'</th>';
					foreach ($field['list_options']['fields'] as $value) {
						$output .= '<td>'.esc_html__($item->$value, $this->textdomain).'</td>';
					}
					$output .= '</tr>';
				} // End foreach
			} // End of options available
			else {
				$output .= '<tr>'.
							'<td colspan="'.(sizeof($field['list_options']['titles'])+1).'">'.__($field['list_options']['no_option'], $this->textdomain).'</td>'.
							'</tr>';
			}
			$output .= '</tbody>'.
						'</table>'.
						'</div>'.
						'</fieldset>';
			return ($output);
		} // End of display_radio

		/**
		 * display_radio
		 *
		 * Display a radio field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 *
		 */
		function display_radio($field, $entry_name, $default) {

			$output = '<fieldset>'.
						'<legend class="screen-reader-text">'.
						'<span>'.__($field['label'], $this->textdomain).'</span>'.
						'</legend>';
			$num = 0;
			foreach ($field['options'] as $value => $text) {
				$checked = ($value == $default ? ' checked' : '');
				$output .= '<label for="'.$entry_name.'">'.
							'<input type="radio" value="'.esc_attr($value).'" name="'.$entry_name.'"'.$checked.' '.$field['status'].' /> '.
							__($text, $this->textdomain).
							'</label>'.
							(++$num != sizeof($field['options']) ? '<br />' : '');
			} // End foreach
			$output .= '</fieldset>';
			return ($output);
		} // End of display_radio

		/**
		 * display_checkbox
		 *
		 * Display a checkbox field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 *
		 */
		function display_checkbox($field, $entry_name, $default) {
	
			$output = '<fieldset>'.
						'<legend class="screen-reader-text">'.
						'<span>'.esc_html__($field['label'], $this->textdomain).'</span>'.
						'</legend>';
			if (sizeof($field['options']) == 1 && !$field['multiple']) {
				$output .= '<label for="'.$entry_name.'">'.
							'<input type="hidden" value="0" name="'.$entry_name.'" /> '.
							'<input type="checkbox" value="1" id="'.$field['name'].'" name="'.$entry_name.'"'.($default ? ' checked' : '').' '.$field['status'].' /> '.
							__(current($field['options']), $this->textdomain).
							'</label>';
			}
			else {
				$num = 0;
				if ( '' == $default || !$default ) 
					$default = array();
				elseif ( !is_array($default) )
					$default = array($default);
	
				if (is_array($field['options']) && 0 < sizeof($field['options']) ) {
					foreach ($field['options'] as $value => $text) {
						$checked = (in_array($value, $default) ? ' checked' : '');
						$options = (isset($field['list_options']) && isset($field['list_options'][$value]) ? $field['list_options'][$value] : '');
						$output .=  ('disabled' == $options ? '' : '<input type="hidden" value="" name="'.$entry_name.'['.$num.']" />').
									'<label for="'.$entry_name.'['.$num.']">'.
									'<input type="checkbox" value="'.esc_attr($value).'" name="'.$entry_name.'['.$num.']"'.$checked.' '.$options.' '.$field['status'].' /> '.
									('disabled' == $options && '' != $checked ? '<input type="hidden" value="'.esc_attr($value).'" name="'.$entry_name.'['.$num.']" />' : '').
									__($text, $this->textdomain).
									'</label>'.
									(++$num != sizeof($field['options']) ? '<br />' : '');
					} // End foreach
				} // End of list of options not empty
			} // Multiple checkbox
			$output .= '</fieldset>';
			return ($output);
		} // End of display_checkbox

		/**
		 * display_text
		 *
		 * Display a text field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 *
		 */
		function display_text($field, $entry_name, $default) {
			if ($field['type'] != 'hidden') $class = 'class="'.$field['size'].'-text" ';
			else $class = '';

			return '<input '.$class.'type="'.$field['type'].'" value="'.esc_attr($default).'" name="'.$entry_name.'" id="'.$field['name'].'" '.' '.$field['status'].' />';
		} // End of display_text

		/**
		 * display_select
		 *
		 * Display a select field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param	array	field		all parameters of the field to display
		 * @param	string	entry_name	name of the html input tag
		 * @param 	array	default		current values of options
		 *
		 * @return	string				output html form
		 *
		 */
		function display_select($field, $entry_name, $default) {

			$output = '<select id="'.$field['name'].'" name="'. $entry_name.'" '.($field['multiple'] ? 'multiple' : '').' '.$field['status'].' >';
			foreach ($field['options'] as $id => $value) {
				$selected = ( $default == $id ) ? ' selected' : '';
				$output .= '<option value="'.esc_attr($id).'" '.$selected.'>'.esc_html__($value, $this->textdomain).'</option>';
			}
			$output .= '</select>';
			return ($output);
		} // End of display_select

		/**
		 * display_field
		 *
		 * Display a field
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param 	array	default_options	current values of options
		 * @param	array	field			all parameters of the field to display
		 *
		 * @return	string				output html form
		 *
		 */
		function display_field($field, $default_options) {

// eg_plugin_error_log('EG-Form', "default options", $default_options);


			$entry_name 	= $this->options_entry.'['.$field['name'].']';
			$default_value  = (isset($default_options[$field['name']]) ? $default_options[$field['name']] : '');
			$th_label 		= ! in_array($field['type'], array('checkbox', 'radio'));

			$output = '<tr valign="top">'.
						'<th scope="row">'.
						($th_label ? '<label for="'.$entry_name.'">' : '').
						esc_html__($field['label'], $this->textdomain).
						($th_label ? '</label>' : '').
						'</th><td>'.
						($field['before']=='' ? '' : '<p>'.__($field['before'], $this->textdomain).'</p>').
						call_user_func(array(&$this, 'display_'.$field['type']), $field, $entry_name, $default_value).
						($field['after']=='' ? '' : ' <span>'.__($field['after'], $this->textdomain).'</span>').
						($field['desc']=='' ? '' : '<p class="description">'.__($field['desc'], $this->textdomain).'</p>').
						'</td>'.
						'</tr>';
			return ($output);
		} // End of display_field

		/**
		 * display_section
		 *
		 * Display a section
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param 	array	default_options	current values of options
		 * @param	string	section_id		id of the section to display
		 *
		 * @return	string				output html form
		 *
		 */
		function display_section($default_options, $box) {
			$section_id	= substr($box['id'], strlen('eg-form-section-'));
			$section    = $this->sections[$section_id];
			
			$output = ('' == $section['header'] ? '' : '<p>'.__($section['header'], $this->textdomain).'</p>'."\n");
			$output .= '<table class="form-table">'."\n".
					'<tbody>'."\n";
			foreach ($this->fields as $id => $field) {
				if ($field['section'] == $section_id) {
					$output .= $this->display_field($field, $default_options);
				} // Endif
			} // End Foreach

			$output .= '</tbody>'."\n".
						'</table>'."\n".
						('' == $section['footer'] ? '' : '<p>'.__($section['footer'], $this->textdomain).'</p>'."\n").
						get_submit_button()."\n".
						'<br class="clear" />';

			echo $output;
		} // End of display_section


		/**
		 * display_sections
		 *
		 * Display all sections of the page
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param 	array	default_options	current values of options
		 * @param	string	current_tab		current displated tab
		 *
		 * @return	string				output html form
		 *
		 */
		function display_sections($default_options, $current_tab='') {
			foreach ($this->sections as $id => $section) {
				if ( !isset($section['tab']) || FALSE == $section['tab'] || (isset($section['tab']) && $current_tab == $section['tab']) ) {
					// Creating metaboxes
					add_meta_box( 'eg-form-section-'.$id,
								esc_html__($section['label'], $this->textdomain),
								array(&$this, 'display_section'),
								null,
								'normal'
							);
				} // End of if
			} // End of foreach
			do_meta_boxes( null, 'normal', $default_options);
		} // End of display_sections

		/**
		 * display_tabs
		 *
		 * Display tabs on the top of the page
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param 	string	current_page	current displayed page
		 * @param	string	current_tab		current displated tab
		 *
		 * @return	string				output html form
		 *
		 */
		function display_tabs($current_page, &$current_tab=0) {
			$output = '';
			if ( $this->tabs && sizeof($this->tabs)>1) {

				if ( $current_tab > sizeof($this->tabs) ) 
					$current_tab = 1;

				$output = '<h2 class="nav-tab-wrapper">';
				foreach( $this->tabs as $id => $tab ){
					$class = ( $id == $current_tab ) ? ' nav-tab-active' : '';
					$output .= '<a class="nav-tab'.$class.'" href="?page='.$current_page.'&tab='.$id.'"> '.
						esc_html__($tab['label'], $this->textdomain).
						'</a>';
				}
				$output .= '</h2>';
				if ( '' != $this->tabs[$current_tab]['header']) 
					$output .= '<p>'.esc_html__($this->tabs[$current_tab]['header'], $this->textdomain).'</p>';
			}
			return ($output);
		} // End of display_tabs

		/**
		 * display
		 *
		 * display the whole page
		 *
		 * @package EG-Forms
		 * @since 	1.0
		 * @param 	array	current values of options
		 *
		 */
		function display($default_options) {

			$current_page = (isset($_REQUEST['page'])?$_REQUEST['page']:'');
			$current_tab  = (isset($_REQUEST['tab'])?$_REQUEST['tab']:1);

			$display_sidebar = has_action($this->sidebar_action);
?>
			<div class="wrap">
				<?php screen_icon(); ?>
				<h2><?php esc_html_e($this->title, $this->textdomain); ?></h2>
				<?php echo $this->display_tabs($current_page, $current_tab); ?>
				<div class="metabox-holder <?php echo ($display_sidebar ? 'has-right-sidebar' : ''); ?>">
					<?php  if ($display_sidebar) { ?>
					<div class="inner-sidebar">
						<?php do_action($this->sidebar_action); ?>
					</div><!-- .inner-sidebar -->
					<?php } ?>
					<div id="post-body">
						<div id="post-body-content">
							<form method="post" action="<?php echo esc_url( add_query_arg( array( 'tab' => $current_tab ), 'options.php' ) ); ?>">
								<?php settings_fields($this->options_group); ?>
								<?php $this->display_sections($default_options, $current_tab); ?>
							</form>
						</div> <!-- #post-body-content -->
					</div> <!-- #post-body -->
				</div> <!-- .metabox-holder -->
				<div class="clear" /></div>
			</div><!-- .wrap-->
<?php
		} // End of display_options_page

	} // End of class
} // End of class_exists

?>