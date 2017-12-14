<?php
/*
Template Name: Search Property Results
*/
?>
<?php 
$tax_query = array('relation' => 'AND');
if( isset( $_GET['location'] ) && $_GET['location'] !=''){
    $suburb=array();
	$suburb=explode(',',chop($_GET['location'],','));
	//print_r($suburb);
	$tax_query[] = array(
		'taxonomy'     => 'property_location',
		'field'        => 'slug',
		'terms'        => $suburb
	);
}
if( isset( $_GET['keyword'] ) && $_GET['keyword'] !=''){
   $keyword=$_GET['keyword']; 
}

/*if( isset( $_GET['sub_location'] ) && $_GET['sub_location'] !=''){
	$tax_query[] = array(
			'taxonomy'     => 'property_sub_location',
			'field'        => 'slug',
			'terms'        => $_GET['sub_location']
	);
}

if( isset( $_GET['status'] ) && $_GET['status'] !=''){
	$tax_query[] = array(
			'taxonomy'     => 'property_status',
			'field'        => 'slug',
			'terms'        => $_GET['status']
	);
}
if( isset( $_GET['category'] ) && $_GET['category'] !=''){
	$tax_query[] = array(
			'taxonomy'     => 'property_category',
			'field'        => 'slug',
			'terms'        => $_GET['category']
	);
}*/
$meta_query = array('relation' => 'AND');
if(isset( $_GET['bedroom'] ) && is_numeric($_GET['bedroom'])){
	$bedroom['key'] = '_bedrooms';
	$bedroom['value'] = $_GET['bedroom'];
	$meta_query[] = $bedroom;
}
if(isset( $_GET['bathroom'] ) && is_numeric($_GET['bathroom'])){
	$bathroom['key'] = '_bathrooms';
	$bathroom['value'] = $_GET['bathroom'];
	$meta_query[] = $bathroom;
}
if(isset( $_GET['parking'] ) && is_numeric($_GET['parking'])){
	$parking['key'] = '_parking';
	$parking['value'] = $_GET['parking'];
	$meta_query[] = $parking;
}
if(isset( $_GET['min_area'] ) && !empty( $_GET['min_area'] )){
	$min_area['key']      = '_area';
	$min_area['value']    = intval($_GET['min_area']);
	$min_area['type']     = 'NUMERIC';
	$min_area['compare']  = '>=';
	$meta_query[]     = $min_area;
}
if(isset( $_GET['max_area'] ) && !empty( $_GET['max_area'] )){
	$max_area['key']      = '_area';
	$max_area['value']    = intval($_GET['max_area']);
	$max_area['type']     = 'NUMERIC';
	$max_area['compare']  = '<=';
	$meta_query[]     = $max_area;
}
if(isset( $_GET['min_price'] ) && !empty( $_GET['min_price'] )){
	$min_price['key']      = '_price';
	$min_price['value']    = floatval($_GET['min_price']);
	$min_price['type']     = 'NUMERIC';
	$min_price['compare']  = '>=';
	$meta_query[]     = $min_price;
}
if(isset( $_GET['max_price'] ) && !empty( $_GET['max_price'] )){
	$max_price['key']      = '_price';
	$max_price['value']    = floatval($_GET['max_price']);
	$max_price['type']     = 'NUMERIC';
	$max_price['compare']  = '<=';
	$meta_query[]     	   = $max_price;
}
$get_keys = array_keys($_GET);
foreach ($get_keys as $get_key){
	if(strstr( $get_key, '_noo_property_field_' )){
		$value = $_GET[$get_key];
		if(!empty($value)){
			$meta_query[]	= array(
				'key'=>$get_key,
				'value'=>$value,
				'compare'=>'LIKE'
			);
		}
	}elseif (strstr($get_key, '_noo_property_feature_')){
		$meta_query[]	= array(
			'key'=>$get_key,
			'value'=>'1',
		);
	}
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if( is_front_page() && !get_query_var('paged') ) {
	$paged = get_query_var( 'page' );
}
$args = array(
		'post_type'     => 'noo_property',
		'post_status'   => 'publish',
		// 'posts_per_page' => -1,
		// 'nopaging'      => true,
		'paged'			=> $paged,
		'meta_query'    => $meta_query,
		'tax_query'     => $tax_query,
		's'             => $keyword,
		'meta_key'      => '_price',
		// 'meta_type'     => 'CHAR',
		// 'orderby'       => 'meta_value menu_order',
		'order'         => 'ASC'
);
if( function_exists( 'pll_current_language' ) ) {
	$args['lang'] = pll_current_language();
}
$r = new WP_Query($args);

?>
<?php 
$show_map = noo_get_option('noo_property_listing_map',1);
$show_search = noo_get_option('noo_property_listing_search',1);
$disable_map = ( ! $show_map && $show_search ) ? ' disable_map="true"' : '';
$disable_search_form = ( $show_map && ! $show_search )  ? ' disable_search_form="true"' : '';
$search_layout = noo_get_option('noo_property_listing_map_layout','horizontal');
$advanced_search = ($show_search && noo_get_option('noo_property_listing_advanced_search',0)) ? ' advanced_search="true"' : '';
?>
<?php get_header(); 
//------synchronize search result with map--------------------
    $ids='';
    while ( $r->have_posts() ) : $r->the_post();
     $ids=$ids.','.$r->post->ID;
    endwhile;
   $ids=' ids='.ltrim($ids, ',');
//--------------------------------------------------------------
?>
<script type="text/javascript">
jQuery( document ).ready(function() {
  jQuery('html,body').animate({ scrollTop: jQuery('.gsubmit').offset().top}, 100);
  return false;
  e.preventDefault();
});
</script>
<div class="container-wrap">
	<?php if(!empty($show_map) || !empty($show_search)):?>
	<?php echo do_shortcode('[noo_advanced_search_property style="'.$search_layout.'"' . $disable_map . $disable_search_form . $advanced_search .$ids. ']');?>
	<?php endif;?>
	<div class="main-content container-boxed max offset">
		<div class="row">
			<div class="<?php noo_main_class(); ?>" role="main">
				<?php if ( $r->have_posts() ) : ?>
			<?php NooProperty::display_content($r,get_the_title(),true,'',true,false,noo_get_option('noo_property_listing_orderby', 1))?>
				<?php else : ?>
					<?php noo_get_layout( 'no-content' ); ?>
				<?php endif; ?>
				<?php 
					wp_reset_query();
					wp_reset_postdata();
				?>
			</div> <!-- /.main -->
			<?php get_sidebar(); ?>
		</div><!--/.row-->
	</div><!--/.container-boxed-->
</div><!--/.container-wrap-->
<?php get_footer(); ?>