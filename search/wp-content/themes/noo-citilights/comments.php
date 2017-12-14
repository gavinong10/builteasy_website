<?php

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
	return;

?>

<div id="comments" class="comments-area hidden-print">

	<?php if ( have_comments() ) : ?>

		<h2 class="comments-title">
		<?php
			printf( _n( 'There are <span>One Comment</span> in this post', 'There are <span>%1$s Comments</span> in this post', get_comments_number(), 'noo' ),number_format_i18n( get_comments_number() ));
		?>
		</h2>
		<ol class="comments-list">
			<?php
			wp_list_comments( array(
				'callback'	 => 'noo_list_comments',
				'style'      => 'ol',
				'avatar_size'=> 70,
				) );
				?>
		</ol> <!-- /.comments-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-below" class="navigation" role="navigation">
				<h1 class="sr-only"><?php _e( 'Comment navigation', 'noo' ); ?></h1>
				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'noo' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'noo' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="nocomments"><?php _e( 'Comments are closed.' , 'noo' ); ?></p>
		<?php endif; ?>

	<?php endif; // end have_comments() ?>
		<?php
		noo_comment_form( array(
			'comment_notes_after' => '',
			'id_submit'           => 'entry-comment-submit',
			'label_submit'        => __( 'Send Comment' , 'noo' )
			) );
			?>
</div> <!-- /#comments.comments-area -->