
<?php 
$ltr = ! is_rtl();
?>
<?php if ( ! is_front_page() && noo_get_option( 'noo_breadcrumbs', true ) ) : ?>
	<div class="breadcrumb-wrap">
		<?php the_breadcrumbs(); ?>
	</div>
<?php endif; ?>