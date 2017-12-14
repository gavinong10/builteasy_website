<?php
/*
*   Template Name: dsIDX Template
*/

$idx_layout = noo_get_option('noo_property_idx_layout', 'fullwidth');
$idx_sidebar = '';
$main_class = '';
$sidebar_class = ' noo-sidebar col-md-4';
if( $idx_layout != 'fullwidth' ) {
    $idx_sidebar = noo_get_option('noo_property_idx_sidebar' , '');
}
if ($idx_layout == 'fullwidth') {
    $main_class .= ' col-md-12';
} elseif ($idx_layout == 'left_sidebar') {
    $main_class .= ' col-md-8 left-sidebar';
    $sidebar_class .= ' noo-sidebar-left pull-left';
} else {
    $main_class .= ' col-md-8 right-sidebar';
}
if ( noo_get_option('noo_property_map_form_search', false) ) {
    $map_search_form = true;
} else {
    $map_search_form = false;
}
get_header();
?>

<div class="container-wrap">
    <?php if( noo_get_option('noo_property_idx_gmap', false) && !is_page('idx-multiple-shortcode')) : ?>
    <div class="container-fullwidth">
        <div class="row">
            <div class="col-md-12">
                <div class="noo_advanced_search_property horizontal">
                    <?php
                    NooProperty::enqueue_gmap_js();
                    $args = array(
                        'gmap' => true,
                        'show_status' => false,
                        'source' => 'IDX'
                    );
                    NooProperty::advanced_map($args);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
        
    <div class="main-content container-boxed max offset">
        
        <div class="row">
            <!-- Main Content -->
            <div class="<?php echo $main_class; ?>" role="main">

                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) :
                        the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class("clearfix"); ?>>
                            <?php
                            if ( has_post_thumbnail() )
                            {
                                $image_id = get_post_thumbnail_id();
                                $image_url = wp_get_attachment_url($image_id);
                                echo '<a class="'.get_lightbox_plugin_class() .'" href="'.$image_url.'" title="'.get_the_title().'" >';
                                the_post_thumbnail('property-image');
                                echo '</a>';
                            }

                            the_content();

                            // WordPress Link Pages
                            wp_link_pages(array('before' => '<div class="pages-nav clearfix">', 'after' => '</div>', 'next_or_number' => 'next'));
                            ?>
                        </article>
                        <?php
                    endwhile;
                endif;
                ?>

            </div><!-- End Main Content -->
            <?php
            if( ! empty( $idx_sidebar ) ) :
            ?>
            <div class="<?php echo $sidebar_class; ?>">
                <div class="noo-sidebar-wrapper">
                    <?php // Dynamic Sidebar
                    if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $idx_sidebar ) ) : ?>
                
                        <!-- Sidebar fallback content -->
                
                    <?php endif; // End Dynamic Sidebar sidebar-main ?>
                </div>
            </div>
            <?php endif; // End sidebar ?> 
        </div><!--/.row-->
    </div><!--/.container-boxed-->
</div>

<?php get_footer(); ?>