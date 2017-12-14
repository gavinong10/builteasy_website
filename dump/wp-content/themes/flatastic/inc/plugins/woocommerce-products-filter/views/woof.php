<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<?php
	global $wp_query;
	global $_chosen_attributes, $woocommerce, $_attributes_array;

	if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( (array) $_attributes_array, array( 'product_cat', 'product_tag' ) ) ) ) {
		return;
	}
?>

<div class="woof">

	<?php if (!empty($taxonomies)) {

		$show_reset = get_option('woof_show_reset');

        $taxonomies_tmp = $taxonomies;
        $taxonomies = array();

        foreach ($this->settings['tax'] as $key => $value) {
            $taxonomies[$key] = $taxonomies_tmp[$key];
        }

        foreach ($taxonomies_tmp as $key => $value) {
            if (!in_array(@$taxonomies[$key], $taxonomies_tmp)) {
                $taxonomies[$key] = $taxonomies_tmp[$key];
            }
        }

		?>

       	<?php foreach ($taxonomies as $tax_slug => $terms) {

            $args = array();
            $args['taxonomy_info'] = $taxonomies_info[$tax_slug];
            $args['tax_slug'] = $tax_slug;
			$args['colors'] = (isset($woof_settings['colors'][$tax_slug])) ? $woof_settings['colors'][$tax_slug] : '';
			$args['query_type'] = (isset($woof_settings['query_type'][$tax_slug])) ? $woof_settings['query_type'][$tax_slug] : '';
            $args['show_count'] = get_option('woof_show_count');
            ?>

            <div class="woof_container">

				<button class="close_woof_container"></button>

                <div class="woof_container_inner">

					<?php switch ($woof_settings['tax_type'][$tax_slug]) {
                        case 'checkbox': ?>
                            <span class="woof_label"><?php echo $taxonomies_info[$tax_slug]->labels->name; ?></span>
                            <?php echo $this->render_html(WOOF_PATH . 'views/types/checkbox.php', $args);
                        break;
						case 'color': ?>
							<span class="woof_label"><?php echo $taxonomies_info[$tax_slug]->labels->name; ?></span>
							<?php echo $this->render_html(WOOF_PATH . 'views/types/color.php', $args);
						break;
						case 'price': ?>
							<span class="woof_label"><?php echo $taxonomies_info[$tax_slug]->labels->name; ?></span>
							<?php echo $this->render_html(WOOF_PATH . 'views/types/wc-price-filter.php', $args);
						break;
                        default:
                            ?>
							<span class="woof_label"><?php echo $taxonomies_info[$tax_slug]->labels->name; ?></span>
                            <?php echo $this->render_html(WOOF_PATH . 'views/types/radio.php', $args);
                        break;
                    } ?>

                </div><!--/ .woof_container_inner-->

            </div><!--/ .woof_container-->

            <?php
        }

		if ($show_reset) {

			if ( ! function_exists('mad_pageURL') ) {
				function mad_pageURL() {
					$pageURL = 'http';
					if ( isset( $_SERVER["HTTPS"] ) AND $_SERVER["HTTPS"] == "on" ) {
						$pageURL .= "s";
					}

					$pageURL .= "://";

					if ( isset( $_SERVER["SERVER_PORT"] ) AND $_SERVER["SERVER_PORT"] != "80" ) {
						$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
					}
					else {
						$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
					}

					return $pageURL;
				}
			}

			// Price
			$min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : 0;
			$max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : 0;

			ob_start();

			if ( count( $_chosen_attributes ) > 0 || $min_price > 0 || $max_price > 0 ) {
				$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) : '';
				$label = isset( $instance['label'] ) ? apply_filters( 'yith-wcan-reset-navigation-label', $instance['label'], $instance, $this->id_base ) : '';

				//clean the url
				$link = mad_pageURL();

				foreach ( (array) $_chosen_attributes as $taxonomy => $data ) {
					$taxonomy_filter = str_replace( 'pa_', '', $taxonomy );

					$link = remove_query_arg( 'filter_' . $taxonomy_filter, $link );
					$link = remove_query_arg( 'query_type_' . $taxonomy_filter, $link );
				}

				if ( isset( $_GET['min_price'] ) ) {
					$link = remove_query_arg( 'min_price', $link );
				}
				if ( isset( $_GET['max_price'] ) ) {
					$link = remove_query_arg( 'max_price', $link );
				}

				echo "<a class='woof-reset-navigation' href='{$link}'>" . __( 'Reset', MAD_BASE_TEXTDOMAIN) . "</a>";
				echo ob_get_clean();
			}

		}

    }

    ?>
</div><!--/ .woof-->

