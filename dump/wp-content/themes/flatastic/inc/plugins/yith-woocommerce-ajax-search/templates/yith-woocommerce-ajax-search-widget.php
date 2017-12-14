<?php
/**
 * YITH WooCommerce Ajax Search template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */

if ( !defined( 'YITH_WCAS' ) ) { exit; } // Exit if accessed directly

wp_enqueue_script('yith_wcas_jquery-autocomplete' );
wp_enqueue_script('yith_wcas_frontend' );

$yith_wcas_search_input_label = get_option('yith_wcas_search_input_label');
if (empty($yith_wcas_search_input_label)) {
    $yith_wcas_search_input_label = __('Search for products', MAD_BASE_TEXTDOMAIN);
}

?>

<div class="yith-ajaxsearchform-container">
    <form role="search" method="get" id="yith-ajaxsearchform" action="<?php echo esc_url( home_url( '/'  ) ) ?>">
        <div>
            <input type="search"
                   value="<?php echo get_search_query() ?>"
                   name="s"
                   id="yith-s-widget"
				   placeholder="<?php echo esc_attr($yith_wcas_search_input_label); ?>"
                   data-loader-icon="<?php echo str_replace( '"', '', apply_filters('yith_wcas_ajax_search_icon', '') ) ?>"
                   data-min-chars="<?php echo get_option('yith_wcas_min_chars'); ?>" />

			<button type="submit" id="yith-searchsubmit"><?php echo get_option('yith_wcas_search_submit_label') ?></button>
            <input type="hidden" name="post_type" value="product" />
        </div>
    </form>
</div>