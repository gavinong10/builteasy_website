<?php

$mad_post_id = mad_post_id();
$mad_header_after_content = rwmb_meta('mad_header_after', '', $mad_post_id); ?>

<?php if ($mad_header_after_content && $mad_header_after_content > 0): ?>

	<?php
		$mad_page = get_pages(array(
			'include' => $mad_header_after_content
		));
	?>

	<?php if (!empty($mad_page)): ?>

		<div class="header-after-content">
			<div class="container">
				<?php echo do_shortcode($mad_page[0]->post_content); ?>
			</div><!--/ .container-->
		</div><!--/ .header-after-content-->

	<?php endif; ?>

<?php endif; ?>
