<?php
/*
Package Name: EG-Widgets
Plugin URI:
Description:  Abstract class to create and manage widget
Version: 2.2.0
Author: Emmanuel GEORJON
Author URI: http://www.emmanuelgeorjon.com/
*/

/*  Copyright 2009-2014  Emmanuel GEORJON  (email : blog@emmanuelgeorjon.com)

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


if (!class_exists('EG_Widget_220')) {

	class EG_Widget_220 extends WP_Widget {

		var $textdomain			= '';
		var $fields				= null;
		var $default_options	= null;
		var $cache_id			= null;

		/**
		 *
		 *
		 *
		 */
		public function set_cache( $cache_id ) {
			$this->cache_id = $cache_id;
		} // End of set_cache
		
		/**
		 *
		 *
		 *
		 */
		public function form( $instance ) {

			if ( isset( $this->default_options ) )
				$instance = wp_parse_args( (array) $instance, $this->default_options);

			$output   = '';

			if ( isset( $this->fields ) ) {

				foreach ($this->fields as $id => $field) {
					$form_field_id   = $this->get_field_id($id);
					$form_field_name = $this->get_field_name($id);

					if ( ! isset($field['enabled']) )
						$field['enabled'] = TRUE;

					if ( ! isset($field['description']) )
						$field['description'] = '';

					$output .= call_user_func(array(&$this, 'display_'.$field['type']), $instance[$id], $form_field_id, $form_field_name, $field);

				} // End of foreach
			}

			echo $output;

		} // End of form


		/**
		 *
		 *
		 *
		 */
		function display_large_text($default_value, $form_field_id, $form_field_name, $field) {

			$output = "\n".'<p>'.
					"\n".'<label for="'.$form_field_id.'">'.esc_html__($field['label'], $this->textdomain).': </label>'.
					"\n".'<input class="widefat" type="text" id="'.$form_field_id.'" name="'.$form_field_name.'" value="'.esc_attr($default_value).'"'.($field['enabled'] ? '' : ' disabled').' />'.
					"\n".($field['description'] ? '<br /><em>'.esc_html__($field['description'], $this->textdomain).'</em>' : '').
					"\n".'</p>';

			return ($output);

		} // End of display_large_text


		/**
		 *
		 *
		 *
		 */
		function display_small_text($default_value, $form_field_id, $form_field_name, $field) {

			$output = "\n".'<p>'.
					"\n".'<label for="'.$form_field_id.'">'.esc_html__($field['label'], $this->textdomain).': </label>'.
					"\n".'<input id="'.$form_field_id.'" name="'.$form_field_name.'" type="text" value="'.esc_attr($default_value).'" size="3"'.($field['enabled'] ? '' : ' disabled').' />'.
					"\n".($field['description'] ? '<br /><em>'.esc_html__($field['description'], $this->textdomain).'</em>' : '').
					"\n".'</p>';

			return ($output);

		} // End of display_small_text


		/**
		 *
		 *
		 *
		 */
		function display_select($default_value, $form_field_id, $form_field_name, $field) {
			$output = "\n".'<p>'.
					"\n".'<label for="'.$form_field_id.'">'.esc_html__($field['label'], $this->textdomain).':</label>'.
					"\n".'<select class="widefat" type="text" id="'.$form_field_id.'" name="'.$form_field_name.'"'.($field['enabled'] ? '' : ' disabled').' >';

			foreach ($field['list'] as $value => $label) {
				$output .= "\n".'<option value="'.$default_value.'" '.selected($value, $default_value, FALSE).'>'.esc_html__($label, $this->textdomain).'</option>';
			}
			$output .= "\n".'</select>'.
					"\n".($field['description'] ? '<br /><em>'.esc_html__($field['description'], $this->textdomain).'</em>' : '').
						"\n".'</p>';

			return ($output);

		} // End of display_select


		/**
		 *
		 *
		 *
		 */
		function display_textarea($default_value, $form_field_id, $form_field_name, $field) {

			$output .= "\n".'<p>'.
					"\n".'<label for="'.$form_field_name.'">'.esc_html__($field['label'], $this->textdomain).': <br />'.
					"\n".'<textarea cols="30" rows="3" id="'.$form_field_id.'" name="'.$form_field_name.'">'.esc_textarea($default_value).'</textarea>'.
					"\n".'</label>'.
					"\n".($field['description'] ? '<br /><em>'.esc_html__($field['description'], $this->textdomain).'</em>' : '').
					"\n".'</p>';

			return ($output);

		} // End of display_textarea


		/**
		 *
		 *
		 *
		 */
		function display_checkbox($default_value, $form_field_id, $form_field_name, $field) {

			if ( ! isset($field['open']) )
				$field['open'] = FALSE;

			if ( ! isset($field['close']) )
				$field['close'] = FALSE;

			$output = "\n".( FALSE !== $field['open'] ? '<p>'.('' != $field['open'] ? '<strong>'.esc_html__($field['open']).'</strong><br />' : '') : '').
					"\n".'<input type="hidden" name="'.$form_field_name.'" value="0" />'.
					"\n".'<input class="checkbox" type="checkbox" id="'.$form_field_id.'" name="'.$form_field_name.'" value="1" '. checked($default_value, true, FALSE) .' /> '.
					"\n".'<label for="'.$form_field_id.'">'.esc_html__($field['label'], $this->textdomain).'</label>'.
					( FALSE !== $field['close'] ? "\n".('' == $field['close'] ? '' : '<br />'.esc_html__($field['close'])).'</p>' : '<br />');
			return ($output);
		} // End of display_checkbox

		/**
		 *
		 *
		 *
		 */
		function display_radio($default_value, $form_field_id, $form_field_name, $field) {
			$output = "\n".'<p>'.
					"\n".esc_html__($field['label'], $this->textdomain).':<br />';
			$num = 0;
			foreach ($field['list'] as $value => $label) {
				$output .= "\n".'<input type="radio" id="'.$form_field_id.'" name="'.$form_field_name.'" value="'.$value.'"'.checked($value, $default_value, FALSE).'/> '.
							"\n".'<label for="'.$form_field_id.'">'.esc_html__($label, $this->textdomain).'</label>'.
							"\n".(++$num == sizeof($field['list']) ? '' : '<br />');
			}
			$output .= '</p>';
			return ($output);
		} // End of display_radio



		public function update( $new_instance, $old_instance ) {

			if ( $this->cache_id ) {
				wp_cache_delete( $this->cache_id );
// eg_plugin_error_log('Widget', 'Cache delete');
			}

			foreach ($new_instance as $key => $value) {
				if (isset($this->fields[$key])) {
					if ($this->fields[$key]['type'] == 'text') {
						$new_instance[$key] = strip_tags($new_instance[$key]);
					} // End of type text
				} // End of isset $key
			} // End of foreach
			return ($new_instance);
		}

		/**
		 *
		 *
		 *
		 */
		public function is_preview( ) {

			if ( method_exists('WP_Widget', 'is_preview') ) {
				return is_preview();
			}
			else {
				return (FALSE);
			}
		}

	} // End of class

} // End of class_exists

?>