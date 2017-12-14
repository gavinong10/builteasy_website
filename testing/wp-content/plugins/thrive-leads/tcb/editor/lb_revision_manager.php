<?php

$post_id = $_POST['post_id'];

$post = get_post( $post_id );

$revisions = $revisions = wp_get_post_revisions( $post_id );
foreach ( $revisions as $revision ) {
	$modified                          = strtotime( $revision->post_modified );
	$modified_gmt                      = strtotime( $revision->post_modified_gmt );
	$now_gmt                           = time();
	$restore_link                      = str_replace( '&amp;', '&', wp_nonce_url(
		add_query_arg(
			array(
				'revision' => $revision->ID,
				'action'   => 'restore'
			),
			admin_url( 'revision.php' )
		),
		"restore-post_{$revision->ID}"
	) );
	$show_avatars                      = get_option( 'show_avatars' );
	$authors[ $revision->post_author ] = array(
		'id'     => (int) $revision->post_author,
		'avatar' => $show_avatars ? get_avatar( $revision->post_author, 32 ) : '',
		'name'   => get_the_author_meta( 'display_name', $revision->post_author ),
	);
	$autosave                          = (bool) wp_is_post_autosave( $revision );
	$revisions[ $revision->ID ]        = array(
		'id'         => $revision->ID,
		'title'      => get_the_title( $post->ID ),
		'author'     => $authors[ $revision->post_author ],
		'date'       => date_i18n( __( 'M j, Y @ G:i' ), $modified ),
		'dateShort'  => date_i18n( _x( 'j M @ G:i', 'revision date short format' ), $modified ),
		'timeAgo'    => sprintf( __( '%s ago', "thrive-cb" ), human_time_diff( $modified_gmt, $now_gmt ) ),
		'autosave'   => $autosave,
		'restoreUrl' => $restore_link,
	);
}

$first_revision = reset( $revisions );
?>
<div id="tve_revision_manager_head" class="tve_clearfix">
	<h4><?php echo __( 'Revision Manager', "thrive-cb" ) ?></h4>
	<hr class="tve_lightbox_line"/>
	<a class="tve_lightbox_link tve_lightbox_link_edit"
	   href="<?php echo add_query_arg( array( 'revision' => $first_revision['id'] ), admin_url( 'revision.php' ) ) ?>"
	   target="_blank"><?php echo __( "Show the default Wordpress Revision Manager", 'thrive-cb' ); ?></a>

	<div class="tve_clear"></div>
	<p><?php echo __( "Use the revision manager to restore your page to a previous version:", "thrive-cb" ); ?></p>
</div>
<div id="tve_revision_manager">
	<ul>
		<?php foreach ( $revisions as $key => $revision ) : ?>
			<li class="tve_clearfix">
				<div class="tve-author-card tve_left">
					<?php echo $revision['author']['avatar'] ?>
					<div class="tve-author-info">
                        <span class="tve-byline">
                            <?php printf( __( 'Revision by %s', 'thrive-cb' ), '<span class="tve-author-name">' . $revision['author']['name'] . '</span>' ); ?>
                        </span>
						<span class="tve-time-ago"><?php echo $revision['timeAgo'] ?></span>
						<span class="tve-date">(<?php echo $revision['dateShort'] ?>)</span>
					</div>
					<div class="tve_clear"></div>
				</div>
				<div class="tve_rollback_btn tve_right">
					<a class="tve_click tve_editor_button tve_editor_button_default <?php echo $first_revision['id'] == $key ? 'tve_disabled"' : '' ?>" <?php echo $first_revision['id'] == $key ? 'disabled="disabled"' : '' ?>
					   data-ctrl="controls.rollback" data-skip-undo="1" href="<?php echo $revision['restoreUrl'] ?>"><?php echo __( 'Restore This Revision', 'thrive-cb' ) ?></a>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
