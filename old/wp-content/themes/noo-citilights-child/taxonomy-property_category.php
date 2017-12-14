<?php 
$show_map = noo_get_option('noo_property_listing_map',1);
$show_search = noo_get_option('noo_property_listing_search',1);
$disable_map = ( ! $show_map && $show_search ) ? ' disable_map="true"' : '';
$disable_search_form = ( $show_map && ! $show_search )  ? ' disable_search_form="true"' : '';
$search_layout = noo_get_option('noo_property_listing_map_layout','horizontal');
$advanced_search = ($show_search && noo_get_option('noo_property_listing_advanced_search',0)) ? ' advanced_search="true"' : '';
//------synchronize search result with map--------------------
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    if( is_front_page() && !get_query_var('paged') ) {
	$paged = get_query_var( 'page' );
    }

//------------------------------------------    
    $status_url= $_SERVER["REQUEST_URI"];
    $exp_url= explode('/', $status_url);
    //print_r($exp_url);
    if($exp_url[1]=='status' && $exp_url[2]=='under-contract'){
    	
    	$args = array('post_type' => 'noo_property','property_status'=>'under-contract','post_status'  => 'publish','paged' => $paged,'meta_key' => '_price','order'  => 'ASC','posts_per_page' => '-1');
    }else if($exp_url[1]=='status' && $exp_url[2]=='under-offer'){
        $args = array('post_type' => 'noo_property','property_status'=>'under-offer','post_status'  => 'publish','paged' => $paged,'meta_key' => '_price','order'  => 'ASC','posts_per_page' => '-1');
    }else{
    	$args = array('post_type' => 'noo_property','post_status'  => 'publish','paged' => $paged,'meta_key' => '_price','order'  => 'ASC','posts_per_page' => '-1');
    }
//----------------------------------------
  
	$r = new WP_Query($args);
    $ids='';
    while ( $r->have_posts() ) : $r->the_post();
     $ids=$ids.','.$r->post->ID;
    endwhile;
    $ids=' ids='.ltrim($ids, ',');
    
    //print_r($ids);


//--------------------------------------------------------------
?>
<?php get_header(); ?>
<div class="container-wrap">
	<?php if(!empty($show_map) || !empty($show_search)):?>
	<?php echo do_shortcode('[noo_advanced_search_property style="'.$search_layout.'"' . $disable_map . $disable_search_form . $advanced_search .$ids. ']');?>
	<?php endif;?>
	<div class="main-content container-boxed max offset">
		<div class="row">
			<div class="<?php noo_main_class(); ?>" role="main">
				<!-- Begin The loop -->
				<?php if ( have_posts() ) : ?>
					<?php NooProperty::display_content($r,single_term_title( "", false ),true,'',true,false,noo_get_option('noo_property_listing_orderby', 1))?>
				<?php else : ?>
					<?php noo_get_layout( 'no-content' ); ?>
				<?php endif; ?>
				<!-- End The loop -->
				<?php //noo_pagination(); ?>
			</div> <!-- /.main -->
			<?php get_sidebar(); ?>
		</div><!--/.row-->
	</div><!--/.container-boxed-->
</div><!--/.container-wrap-->
	
<?php get_footer(); ?>