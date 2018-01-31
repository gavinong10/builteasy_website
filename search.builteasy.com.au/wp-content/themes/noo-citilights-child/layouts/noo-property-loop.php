<?php 
if($wp_query->have_posts()):
	if(!$ajax_content) :
	?>
	<div class="properties <?php echo $mode ?>" <?php echo $ajax_pagination ? 'data-paginate="loadmore"':''?>>
		<div class="properties-header">
			<h1 class="page-title"><?php echo $title ?></h1>
			<?php if($display_mode):?>
				<div class="properties-toolbar">
					<a class="<?php echo $mode == 'grid' ?'selected':'' ?>" data-mode="grid" href="<?php echo esc_url(add_query_arg( 'mode','grid'))?>" title="<?php echo esc_attr__('Grid','noo')?>"><i class="fa fa-th-large"></i></a>
					<a class="<?php echo $mode == 'list' ?'selected':'' ?>" data-mode="list" href="<?php echo esc_url(add_query_arg( 'mode','list'))?>" title="<?php echo esc_attr__('List','noo')?>"><i class="fa fa-list"></i></a>
					<?php if($show_orderby):?>
						<form class="properties-ordering" method="get">
							<div class="properties-ordering-label"><?php _e('Sorted by','noo')?></div>
							<div class="form-group properties-ordering-select">
					<?php $sub_urb_arr = get_terms( 'property_location', array( 'orderby' =>  'term_order', 'hide_empty' => true ) );?>
								<div class="dropdown">
									<?php 
									$order_arr = array(
										'date'=>__('Date','noo'),
										'price'=>__('Price','noo'),
										'area'=>__('Land size','noo'),
										'name'=>__('Name','noo'),
										//'bath'=>__('Bath','noo'),
										//'bed'=>__('Bed','noo'),
										//'rand'=>__('Random','noo'),
									);
		
									$setting_orderby = noo_get_option('noo_property_listing_orderby_default');
									$default_orderby = !empty( $setting_orderby ) ? $setting_orderby : $default_orderby;
									$default_orderby = isset($_GET['orderby']) ? $_GET['orderby'] : $default_orderby;
									$ordered = array_key_exists($default_orderby, $order_arr) ? $order_arr[$default_orderby] : __('Date','noo');
									?>
									<span data-toggle="dropdown"><?php echo $ordered ?></span>
									<ul class="dropdown-menu">
									<?php foreach ($order_arr as $k=>$v):?>
										<li><a  data-value="<?php echo esc_attr($k)?>"><?php echo $v ?></a></li>
									<?php endforeach;?>
									</ul>
								</div>
							</div>
							<input type="hidden" name="orderby" value="">
							<?php
								foreach ( $_GET as $key => $val ) {
									if ( 'orderby' === $key || 'submit' === $key )
										continue;
									
									if ( is_array( $val ) ) {
										foreach( $val as $innerVal ) {
											echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
										}
									
									} else {
										echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
									}
								}
							?>
						</form>
					<?php endif;?>
				</div>
			<?php endif;?>
		</div>
		<div class="properties-content<?php echo $ajax_pagination ? ' loadmore-wrap':''?>">
	<?php endif; ?>
	<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); global $post; ?>
			<article id="property-<?php the_ID(); ?>" <?php post_class(); ?>>
			    <div class="property-featured">
			    	<?php if('yes' === noo_get_post_meta($post->ID,'_featured')):?>
			    		<span class="featured"><i class="fa fa-star"></i></span>
			    	<?php endif;?>
			        <a class="content-thumb" href="<?php the_permalink() ?>">
						<?php echo get_the_post_thumbnail(get_the_ID(),'property-thumb') ?>
					</a>
					<?php 
					$sold = get_option('default_property_status');
					if(!empty($sold) && (has_term($sold,'property_status'))):
						$sold_term = get_term($sold, 'property_status');
					?>
						<span class="property-label sold"><?php echo $sold_term->name?></span>
					<?php endif; ?>
					<?php 
					$_label = noo_get_post_meta(null,'_label');
					if(!empty($_label) && ($property_label = get_term($_label, 'property_label'))):
						$noo_property_label_colors = get_option('noo_property_label_colors');
						$color 	= isset($noo_property_label_colors[$property_label->term_id]) ? $noo_property_label_colors[$property_label->term_id] : '';
					?>
						<span class="property-label" <?php echo (!empty($color) ? ' style="background-color:'.$color.'"':'')?>><?php echo $property_label->name?></span>
					<?php endif;?>
					<span class="property-category"><?php echo get_the_term_list(get_the_ID(), 'property_category')?></span>
			    </div>
				<div class="property-wrap">
					<h2 class="property-title">
						<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permanent link to: "%s"','noo' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
						<?php if($is_fullwidth):?>
						<small><?php echo noo_get_post_meta(null,'_address')?></small>
						<?php endif;?>
					</h2>
					<div class="property-excerpt">
						<?php if($excerpt = get_the_excerpt()):?>
							<?php 
							$num_word = 15;
							$excerpt = strip_shortcodes($excerpt);
							echo '<p>' . wp_trim_words($excerpt,$num_word,'...') . '</p>';
							echo '<p class="property-fullwidth-excerpt">' . wp_trim_words($excerpt,25,'...') . '</p>';
							?>
						<?php endif;?>
					</div>
					<div class="property-summary">
						<?php echo NooProperty::get_property_summary( array('container_class'=>'property-detail')); ?>
						<div class="property-info">
							<div class="property-price">
								<span><?php echo NooProperty::get_price_html(get_the_ID())?></span>
							</div>
							<div class="property-action">
								<a href="<?php the_permalink()?>"><?php echo __('More Details','noo')?></a>
							</div>
						</div>
						<div class="property-info property-fullwidth-info">
							<div class="property-price">
								<span><?php echo NooProperty::get_price_html(get_the_ID())?></span>
							</div>
							<?php echo NooProperty::get_property_summary(); ?>
						</div>
					</div>
				</div>
				<div class="property-action property-fullwidth-action">
					<a href="<?php the_permalink()?>"><?php echo __('More Details','noo')?></a>
				</div>
			</article> <!-- /#post- -->
	<?php endwhile; ?>
	<?php if (!$ajax_content) : ?>
		</div>
		<?php if($ajax_pagination && (1 < $wp_query->max_num_pages)):?>
		<div class="loadmore-action">
			<div class="noo-loader loadmore-loading">
	            <div class="rect1"></div>
	            <div class="rect2"></div>
	            <div class="rect3"></div>
	            <div class="rect4"></div>
	            <div class="rect5"></div>
	        </div>
			<button type="button" class="btn btn-default btn-block btn-loadmore"><?php _e('Load More','noo')?></button>
		</div>
		<?php endif;?>
		<?php
		if($show_pagination || $ajax_pagination){
			noo_pagination(array(),$wp_query);
		}
	?>
	</div>
	<?php
	endif;
endif;