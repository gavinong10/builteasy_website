<?php 
$show_map = noo_get_option('noo_property_detail_map',1);
$show_search = noo_get_option('noo_property_detail_search',1);
$disable_map = ( ! $show_map && $show_search ) ? ' disable_map="true"' : '';
$disable_search_form = ( $show_map && ! $show_search )  ? ' disable_search_form="true"' : '';
$search_layout = noo_get_option('noo_property_detail_map_layout','horizontal');
$advanced_search = ($show_search && noo_get_option('noo_property_detail_advanced_search',0)) ? ' advanced_search="true"' : '';
?>
<?php get_header(); ?>
<div class="container-wrap">
	<?php if(!empty($show_map) || !empty($show_search)):?>
	<?php echo do_shortcode('[noo_advanced_search_property style="'.$search_layout.'"' . $disable_map . $disable_search_form . $advanced_search . ']');?>
	<?php endif;?>
	<div class="main-content container-boxed max offset">
		<div class="row">
			<div class="<?php noo_main_class(); ?>" role="main">
				<?php NooProperty::display_detail()?>
				<?php if ( comments_open() ) : ?>
					<?php comments_template( '', true ); ?>
				<?php endif; ?>
			</div>
			<?php get_sidebar(); ?>
		</div> <!-- /.row -->
	
	</div> <!-- /.container-boxed.max.offset -->
</div>
<?php get_footer(); ?>