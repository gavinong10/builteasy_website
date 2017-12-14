<?php

/**
 * User Roles Profile Edit Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div>
	<label for="role"><?php _e( 'Blog Role', 'codeex_theme_name' ) ?></label>

	<?php bbp_edit_user_blog_role(); ?>

</div>

<div>
	<label for="forum-role"><?php _e( 'Forum Role', 'codeex_theme_name' ) ?></label>

	<?php bbp_edit_user_forums_role(); ?>

</div>
