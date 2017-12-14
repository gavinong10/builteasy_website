<?php
/**
 * Main class
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Navigation
 * @version 1.1.4
 */

if ( !defined( 'YITH_WOOCOMPARE' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_WOOCOMPARE' ) ) {
    /**
     * YITH WooCommerce Ajax Navigation Widget
     *
     * @since 1.0.0
     */
    class YITH_Woocompare_Widget extends WP_Widget {

        function __construct() {
            $widget_ops = array('classname' => 'yith-woocompare-widget', 'description' => __( 'The widget show the list of products added in the compare table.', MAD_BASE_TEXTDOMAIN) );
            parent::__construct('yith-woocompare-widget', __('YITH Woocommerce Compare Widget', MAD_BASE_TEXTDOMAIN), $widget_ops);
        }


        function widget( $args, $instance ) {
            global $yith_woocompare;

            /**
             * WPML Support
             */
            $lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;

            extract( $args );

            $localized_widget_title = function_exists( 'icl_translate' ) ? icl_translate( 'Widget', 'widget_yit_compare_title_text', $instance['title'] ) : $instance['title'];

            echo $before_widget . $before_title . $localized_widget_title . $after_title; ?>

			<div class="widget-content">
				<ul class="products-list" data-lang="<?php echo $lang ?>">
					<?php echo $yith_woocompare->obj->list_products_html(); ?>
				</ul>

				<a href="<?php echo esc_url(add_query_arg( array( 'iframe' => 'true' ), $yith_woocompare->obj->view_table_url() )) ?>" class="compare button"><?php _e( 'Go to Compare', MAD_BASE_TEXTDOMAIN ) ?></a>
				<a href="<?php echo esc_url($yith_woocompare->obj->remove_product_url('all')) ?>" data-product_id="all" class="clear-all"><?php _e( 'Clear all', MAD_BASE_TEXTDOMAIN ) ?></a>
				<div class="clear"></div>
			</div><!--/ .widget-content-->

            <?php echo $after_widget;
        }


        function form( $instance ) {
            global $woocommerce;

            $defaults = array(
                'title' => '',
            );

            $instance = wp_parse_args( (array) $instance, $defaults ); ?>

            <p>
                <label>
                    <strong><?php _e( 'Title', MAD_BASE_TEXTDOMAIN ) ?>:</strong><br />
                    <input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
                </label>
            </p>
        <?php
        }

        function update( $new_instance, $old_instance ) {
            $instance = $old_instance;

            $instance['title'] = strip_tags( $new_instance['title'] );

            return $instance;
        }

    }
}