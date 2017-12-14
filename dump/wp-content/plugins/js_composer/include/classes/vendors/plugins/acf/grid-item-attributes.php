<?php
/**
 * Get ACF data
 *
 * @param $value
 * @param $data
 *
 * @return string
 */
function vc_gitem_template_attribute_acf( $value, $data ) {
	$label = '';
	/**
	 * @var null|Wp_Post $post ;
	 * @var string $data ;
	 */
	extract( array_merge( array(
		'post' => null,
		'data' => ''
	), $data ) );

		if ( strstr( $data, 'field_from_group_' ) ) {
			$group_id = preg_replace( '/(^field_from_group_|_labeled$)/', '', $data );
			$fields = function_exists( 'acf_get_fields' ) ? acf_get_fields( $group_id ) : apply_filters( 'acf/field_group/get_fields', array(), $group_id );
			$field = is_array( $fields ) && isset( $fields[0] ) ? $fields[0] : false;
			if ( is_array( $field ) && isset( $field['key'] ) ) {
				$data = $field['key'] . ( strstr( $data, '_labeled' ) ? str_replace( $data, '', '_labeled' ) : '' );
			}

		}
		if ( preg_match( '/_labeled$/', $data ) ) {
			$data = preg_replace( '/_labeled$/', '', $data );
			$field = apply_filters( 'acf/load_field', array(), $data );
			$label = is_array( $field ) ? '<span class="vc_gite-acf-label">' . $field['label'] . ':</span> ' : '';
		}
	if ( get_field( $data ) ) {
		$value = apply_filters( 'vc_gitem_template_attribute_acf_value', get_field( $data, $post->ID ) );
	}

	return strlen( $value ) > 0 ? $label . $value : '';
}

add_filter( 'vc_gitem_template_attribute_acf', 'vc_gitem_template_attribute_acf', 10, 2 );
