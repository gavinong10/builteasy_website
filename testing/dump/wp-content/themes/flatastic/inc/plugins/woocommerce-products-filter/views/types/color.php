	<?php
		global $_chosen_attributes, $woocommerce, $_attributes_array;

		$current_term = $_attributes_array && is_tax( $_attributes_array ) ? get_queried_object()->term_id : '';
		$query_type  = isset( $query_type ) ? $query_type : 'and';
		$attribute = substr($tax_slug, 3);

		if ( function_exists( 'wc_attribute_taxonomy_name' ) ) {
			$taxonomy = wc_attribute_taxonomy_name( $attribute );
		} else {
			$taxonomy = $woocommerce->attribute_taxonomy_name( $attribute );
		}

		if ( ! taxonomy_exists( $taxonomy ) ) { return; }

		$terms = get_terms( $taxonomy, array(
			'hide_empty' => '0',
			'orderby' => 'slug'
		) );
	?>

	<?php if ( count( $terms ) > 0 ) : ?>

		<ul class="woof_list woof_list_color">

			<?php foreach ($terms as $term) : ?>

				<?php
					$transient_name = 'wc_ln_count_' . md5( sanitize_key( $taxonomy ) . sanitize_key( $term->term_id ) );
					$_products_in_term = get_objects_in_term( $term->term_id, $taxonomy );
					set_transient( $transient_name, $_products_in_term );

					$arg = 'filter_' . sanitize_title( $attribute );

					$current_filter = ( isset( $_GET[$arg] ) ) ? explode( ',', $_GET[$arg] ) : array();
					if ( ! is_array( $current_filter ) ) {
						$current_filter = array();
					}

					$current_filter = array_map( 'esc_attr', $current_filter );
					if ( ! in_array( $term->term_id, $current_filter ) ) {
						$current_filter[] = $term->term_id;
					}

					// Base Link decided by current page
					if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
						$link = home_url();
					} elseif ( is_post_type_archive( 'product' ) || is_page( function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'shop' ) : woocommerce_get_page_id( 'shop' ) ) ) {
						$link = get_post_type_archive_link( 'product' );
					} else {
						$link = get_term_link( get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					}


					// All current filters
					if ( $_chosen_attributes ) {
						foreach ( $_chosen_attributes as $name => $data ) {
							if ( $name !== $taxonomy ) {

								// Exclude query arg for current term archive term
								while ( in_array( $current_term, $data['terms'] ) ) {
									$key = array_search( $current_term, $data );
									unset( $data['terms'][$key] );
								}

								// Remove pa_ and sanitize
								$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );

								if ( ! empty( $data['terms'] ) ) {
									$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
								}

								if ( $data['query_type'] == 'or' ) {
									$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
								}
							}
						}
					}

					// Min/Max
					if ( isset( $_GET['min_price'] ) ) {
						$link = add_query_arg( 'min_price', $_GET['min_price'], $link );
					}

					if ( isset( $_GET['max_price'] ) ) {
						$link = add_query_arg( 'max_price', $_GET['max_price'], $link );
					}

					// Current Filter = this widget
					if ( isset( $_chosen_attributes[ $taxonomy ] ) && is_array( $_chosen_attributes[ $taxonomy ]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[ $taxonomy ]['terms'] ) ) {

						$class = 'class="chosen"';

						// Remove this term is $current_filter has more than 1 term filtered
						if ( sizeof( $current_filter ) > 1 ) {
							$current_filter_without_this = array_diff( $current_filter, array( $term->term_id ) );
							$link = add_query_arg( $arg, implode( ',', $current_filter_without_this ), $link );
						}

					} else {
						$class = '';
						$link = add_query_arg( $arg, implode( ',', $current_filter ), $link );
					}

					// Search Arg
					if ( get_search_query() ) {
						$link = add_query_arg( 's', get_search_query(), $link );
					}

					// Post Type Arg
					if ( isset( $_GET['post_type'] ) ) {
						$link = add_query_arg( 'post_type', $_GET['post_type'], $link );
					}

					// Query type Arg
					if ( $query_type == 'or' && ! ( sizeof( $current_filter ) == 1 && isset( $_chosen_attributes[$taxonomy]['terms'] ) && is_array( $_chosen_attributes[$taxonomy]['terms'] ) && in_array( $term->term_id, $_chosen_attributes[$taxonomy]['terms'] ) ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( $attribute ), 'or', $link );
					}

				?>

				<?php if ($colors[$term->slug] != ''): ?>
					<li <?php echo $class ?>>
						<a style="background-color: <?php echo $colors[$term->slug] ?>" href="<?php echo esc_url( apply_filters( 'woocommerce_layered_nav_link', $link ) ) ?>" data-tax="<?php echo esc_attr($tax_slug) ?>" title="<?php echo $term->name ?>"><?php echo $term->name ?></a>
					</li>
				<?php endif; ?>

			<?php endforeach; ?>

		</ul><!--/ .woof_list-->

	<?php endif; ?>
