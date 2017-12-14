<?php
/**
 * Add/Remove user fields for NOO Framework.
 *
 * @package    NOO Framework
 * @subpackage NOO Function
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@vietbrain.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

if ( ! function_exists( 'noo_author_profile_fields' ) ) :
	function noo_author_profile_fields ( $contactmethods ) {
		
		$contactmethods['google_profile'] = __( 'Google+ Profile URL', 'noo');
		$contactmethods['twitter_profile'] = __( 'Twitter Profile URL', 'noo');
		$contactmethods['facebook_profile'] = __( 'Facebook Profile URL', 'noo');
		$contactmethods['linkedin_profile'] = __( 'LinkedIn Profile URL', 'noo');
		
		return $contactmethods;
	}
	add_filter( 'user_contactmethods', 'noo_author_profile_fields', 10, 1);
endif;