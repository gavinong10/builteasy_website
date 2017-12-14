<?php global $post; ?>

<?php
	if ((!is_admin()) && is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
?>

<!-- - - - - - - - - - - - Comments - - - - - - - - - - - - - - -->

<div class="theme-respond">

	<?php if ( have_comments() ): ?>

		<!-- - - - - - - - - - - end Comments - - - - - - - - - - - - - -->

		<div id="comments">

			<h2 class="m_bottom_30">
				<?php echo get_comments_number() . " " . __('Comments', MAD_BASE_TEXTDOMAIN); ?>
			</h2>

			<ol class="comments-list m_bottom_45">
				<?php wp_list_comments('avatar_size=70&callback=mad_output_comments'); ?>
			</ol>

			<?php if (get_comment_pages_count() > 1 && get_option( 'page_comments' )): ?>
				<nav class="comments-pagination">
					<?php paginate_comments_links(); ?>
				</nav>
			<?php endif; ?>

			<?php if (! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' )): ?>
				<p class="nocomments"><?php _e('Comments are closed.', MAD_BASE_TEXTDOMAIN); ?></p>
			<?php endif; ?>

		</div><!--/ #comments-->

	<?php endif; ?>

	<!-- - - - - - - - - - - - Respond - - - - - - - - - - - - - - - -->

	<?php if (comments_open()) : ?>
		<?php comment_form(); ?>
	<?php endif; ?>

	<!-- - - - - - - - - - -/ end Respond - - - - - - - - - - - - - -->

</div><!--/ .theme-respond-->