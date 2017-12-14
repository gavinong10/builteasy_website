<?php
	// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) : ?>
		<div class="password-protect-content">
			<?php _e('This post is password protected. Enter the password to view comments', 'codeex_theme_name'); ?>
		</div><?php
		return;
	endif;
?>

<!--COMMENTS-->
<div class="comments" id="comments">
	<?php 
		if ( have_comments() ) : ?>
			<h3>
				<?php 
					comments_number( 
						__( 'No Comments', 'codeex_theme_name' ), 
						__( 'One Comment', 'codeex_theme_name' ), 
						__( '% Comments', 'codeex_theme_name' ) 
					); 
				?>
			</h3>
			<ul class="comment-list">
		 		<?php 
		 			$args = array ('type' => 'comment', 'callback' => 'homeland_theme_comment');
					wp_list_comments( $args ); 		
		 		?>
		 	</ul>
	 		<?php
	 	else :    
			if ('open' == $post->comment_status) : ?>
				<h3 style="color:#FF0000 !important; margin-bottom:0;">
					<?php 
						comments_number( 
							__( 'No Comments', 'codeex_theme_name' ), 
							__( 'One Comment', 'codeex_theme_name' ), 
							__( '% Comments', 'codeex_theme_name' ) 
						); 
					?>
				</h3><?php 
			else : ?>            
				<h3><?php sanitize_title( _e('Comments are closed.', 'codeex_theme_name') ); ?></h3><?php 
			endif; 
	 	endif;
	?>
</div>

<!--COMMENT FORM-->
<?php 
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields = array(
			'author' => '<li><input type="text" id="author" placeholder="'. __( 'Name', 'codeex_theme_name' ) .'" name="author" ' . $aria_req . ' value="' . esc_attr( $commenter['comment_author'] ) . '" tabindex="1" /></li>',
			'email' => '<li><input type="text" id="email" placeholder="'. __( 'Email Address', 'codeex_theme_name' ) .'" name="email" ' . $aria_req . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" tabindex="2" /></li>',
			'URL' => '<li class="last"><input type="text" id="url" placeholder="' . __( 'Website', 'codeex_theme_name' ) . '" name="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" tabindex="3" /></li>'
	);

	$args = array(
		'fields' => apply_filters( 'comment_form_default_fields', $fields),
		'title_reply' => __( 'Leave a Comment', 'codeex_theme_name' ),
		'cancel_reply_link' => __( 'Cancel reply', 'codeex_theme_name'),
		'comment_field' => '<li><textarea id="comment" placeholder="'. __( 'Message', 'codeex_theme_name' ) .'" name="comment" ' . $aria_req . ' tabindex="4" rows="0" cols="0"></textarea></li>',
		'label_submit' => __( 'Post a Message', 'codeex_theme_name' ),
		'comment_notes_before' => '<ul class="clear">',
		'comment_notes_after' => '</ul>',
	);
	comment_form($args); 
?>
