<?php
    $mad_next_post = get_next_post();
    $mad_prev_post = get_previous_post();
    $mad_next_post_url = $mad_prev_post_url = "";
	$mad_next_post_title = $mad_prev_post_title = "";

    if (is_object($mad_next_post)) {
        $mad_next_post_url = get_permalink($mad_next_post->ID);
        $mad_next_post_title = $mad_next_post->post_title;
    }
    if (is_object($mad_prev_post)) {
        $mad_prev_post_url = get_permalink($mad_prev_post->ID);
		$mad_prev_post_title = $mad_prev_post->post_title;
    }
?>

<?php if (!empty($mad_prev_post_url) || !empty($mad_next_post_url)): ?>

    <div class="post-link-pages">

		<div class="post-nav-left">
			<?php if (!empty($mad_prev_post_url)): ?>
				<a class="post-prev-button" href="<?php echo esc_url($mad_prev_post_url) ?>" title="">
					<?php _e('Previous Post', MAD_BASE_TEXTDOMAIN) ?>
				</a>
				<span><?php echo esc_html($mad_prev_post_title); ?></span>
			<?php endif; ?>
		</div><!--/ .post-nav-left-->

		<div class="post-nav-right">
			<?php if (!empty($mad_next_post_url)): ?>
				<a class="post-next-button" href="<?php echo esc_url($mad_next_post_url) ?>" title="">
					<?php _e('Next Post', MAD_BASE_TEXTDOMAIN) ?>
				</a>
				<span><?php echo esc_html($mad_next_post_title); ?></span>
			<?php endif; ?>
		</div><!--/ .post-nav-right-->

    </div><!--/ .post-link-pages-->

<?php endif; ?>