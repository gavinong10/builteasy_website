<?php 
while ($query->have_posts()): $query->the_post(); global $post;
?>
	<article id="post-<?php the_ID(); ?>" class="property">
		<h1 class="property-title">
			<?php the_title(); ?>
			<small><?php echo noo_get_post_meta(null,'_address')?></small>
		</h1>
		<?php NooProperty::social_share( get_the_id() ); ?>
		<?php if (has_post_thumbnail()) { ?>
		<?php 
		$gallery = noo_get_post_meta(get_the_ID(),'_gallery','');
		$gallery_ids = explode(',',$gallery);
		$gallery_ids = array_filter($gallery_ids);

		$property_category	= get_the_term_list(get_the_ID(), 'property_category');
		$property_status	= get_the_term_list(get_the_ID(), 'property_status');
		$property_location	= get_the_term_list(get_the_ID(), 'property_location');
		$property_sub_location	= get_the_term_list(get_the_ID(), 'property_sub_location');
		$property_price		= NooProperty::get_price_html(get_the_ID());
		$property_area		= trim(NooProperty::get_area_html(get_the_ID()));
		$property_bedrooms	= noo_get_post_meta(get_the_ID(),'_bedrooms');
		$property_bathrooms	= noo_get_post_meta(get_the_ID(),'_bathrooms');
		?>
	    <div class="property-featured clearfix">
	    	<div class="images">
	    		<div class="caroufredsel-wrap">
		    		<ul>
		    		<?php 
		    		$image = wp_get_attachment_image_src(get_post_thumbnail_id(),'property-full');
		    		?>
			    		<li>
			    			<a class="noo-lightbox-item" data-lightbox-gallery="gallert_<?php the_ID()?>" href="<?php echo $image[0]?>"><?php echo get_the_post_thumbnail(get_the_ID(), get_thumbnail_width()) ?></a>
			    		</li>
			    		<?php if(!empty($gallery_ids)): ?>
			    		<?php foreach ($gallery_ids as $gallery_id): $gallery_image = wp_get_attachment_image_src($gallery_id,'property-full')?>
			    		<li>
			    			<a class="noo-lightbox-item" data-lightbox-gallery="gallert_<?php the_ID()?>" href="<?php echo $gallery_image[0]?>"><?php echo wp_get_attachment_image( $gallery_id, get_thumbnail_width() ); ?></a>
			    		</li>
			    		<?php endforeach;?>
			    		<?php endif;?>
		    		</ul>
			    	<a class="slider-control prev-btn" role="button" href="#"><span class="slider-icon-prev"></span></a>
			    	<a class="slider-control next-btn" role="button" href="#"><span class="slider-icon-next"></span></a>
	    		</div>
	    	</div>
	    	<?php 
	    	
	    	if(!empty($gallery_ids)):
	    	?>
	    	<div class="thumbnails">
	    		<div class="thumbnails-wrap">
		    		<ul>
		    		<li>
		    			<a data-rel="0" href="<?php echo $image[0]?>"><?php echo get_the_post_thumbnail(get_the_ID(), 'property-thumb') ?></a>
		    		</li>
		    		<?php $i = 1;?>
		    		<?php foreach ($gallery_ids as $gallery_id): //$gallery_image = wp_get_attachment_image_src($gallery_id,'property-thumb')?>
		    		<li>
		    			<a data-rel="<?php echo $i ?>" href="<?php echo $gallery_image[0]?>"><?php echo wp_get_attachment_image( $gallery_id, 'property-thumb'); ?></a>
		    		</li>
		    		<?php $i++;?>
		    		<?php endforeach;?>
		    		</ul>
		    	</div>
		    	<a class="caroufredsel-prev" href="#"></a>
		    	<a class="caroufredsel-next" href="#"></a>
	    	</div>
	    	<?php endif;?>
	    </div>
	    <?php } ?>
		<div class="property-summary">
			<div class="row">
				<div class="property-detail col-md-4 col-sm-4">
					<h4 class="property-detail-title"><?php _e('Property Detail','noo')?></h4>
					<div class="property-detail-content">
						<div class="detail-field row">
							<?php if( !empty($property_category) ) : ?>
								<span class="col-sm-5 detail-field-label type-label"><?php echo __('Type','noo')?></span>
								<span class="col-sm-7 detail-field-value type-value"><?php echo $property_category?></span>
							<?php endif; ?>
							<?php if( !empty($property_status) ) : ?>
								<span class="col-sm-5 detail-field-label status-label"><?php echo __('Status','noo')?></span>
								<span class="col-sm-7 detail-field-value status-value"><?php echo $property_status?></span>
							<?php endif; ?>
							<?php if( !empty($property_location) ) : ?>
								<span class="col-sm-5 detail-field-label location-label"><?php echo __('Location','noo')?></span>
								<span class="col-sm-7 detail-field-value location-value"><?php echo $property_location?></span>
							<?php endif; ?>
							<?php if( !empty($property_sub_location) ) : ?>
								<span class="col-sm-5 detail-field-label sub_location-label"><?php echo __('Sub Location','noo')?></span>
								<span class="col-sm-7 detail-field-value sub_location-value"><?php echo $property_sub_location?></span>
							<?php endif; ?>
							<?php if( !empty($property_price) ) : ?>
								<span class="col-sm-5 detail-field-label price-label"><?php echo __('Price','noo')?></span>
								<span class="col-sm-7 detail-field-value price-value"><?php echo $property_price?></span>
							<?php endif; ?>
							<?php if( !empty($property_area) ) : ?>
								<span class="col-sm-5 detail-field-label area-label"><?php echo __('Area','noo')?></span>
								<span class="col-sm-7 detail-field-value area-value"><?php echo $property_area?></span>
							<?php endif; ?>
							<?php if( !empty($property_bedrooms) ) : ?>
								<span class="col-sm-5 detail-field-label bedrooms-label"><?php echo __('Bedrooms','noo')?></span>
								<span class="col-sm-7 detail-field-value bedrooms-value"><?php echo $property_bedrooms?></span>
							<?php endif; ?>
							<?php if( !empty($property_bathrooms) ) : ?>
								<span class="col-sm-5 detail-field-label bathrooms-label"><?php echo __('Bathrooms','noo')?></span>
								<span class="col-sm-7 detail-field-value bathrooms-value"><?php echo $property_bathrooms?></span>
							<?php endif; ?>
						<?php $custom_fields = NooProperty::get_custom_field_option('custom_field');
							$property_id = get_the_ID();
							if( function_exists('pll_get_post') ) $property_id = pll_get_post( $property_id );
						?>
						<?php foreach ((array)$custom_fields  as $field): ?> 
							<?php  $custom_field_value = trim(noo_get_post_meta($property_id,'_noo_property_field_'.sanitize_title(@$field['name']),null)); ?>
							<?php if(!empty($custom_field_value)):?>
							<span class="col-sm-5 detail-field-label <?php echo sanitize_title(@$field['name'])?>"><?php echo isset( $field['label_translated'] ) ? $field['label_translated'] : @$field['label']?></span>
							<span class="col-sm-7 detail-field-value <?php echo sanitize_title(@$field['name'])?>"><?php echo $custom_field_value ?></span>
							<?php endif;?>
						<?php endforeach;?>
						</div>
					</div>
				</div>
				<div class="property-desc col-md-8 col-sm-8">
					<h4 class="property-detail-title"><?php _e('Property Description','noo')?></h4>
				</div>
				<div class="property-content">
					<?php the_content();?>
				</div>
			</div>
		</div>
		<?php $features = (array) NooProperty::get_custom_features();
		if( !empty( $features ) && is_array( $features ) ) : ?>
		<div class="property-feature">
			<h4 class="property-feature-title"><?php _e('Property Features','noo')?></h4>
			<div class="property-feature-content">
				<?php $show_no_feature = ( NooProperty::get_feature_option('show_no_feature') == 'yes' );
				?>
				<?php foreach ($features as $key => $feature):?>
					<?php if(noo_get_post_meta(get_the_ID(),'_noo_property_feature_'.$key)):
					?>
					<div class="has">
						<i class="fa fa-check-circle"></i> <?php echo $feature?>
					</div>
					<?php elseif( $show_no_feature ) : ?>
					<div class="no-has">
						<i class="fa fa-times-circle"></i> <?php echo $feature?>
					</div>
					<?php endif;?>
				
				<?php endforeach;?>
			</div>
		</div>
		<?php endif; ?>
		<?php if($_video_embedded = noo_get_post_meta(get_the_ID(),'_video_embedded','')):?>
		<?php 
		$video_w = ( isset( $content_width ) ) ? $content_width : 1200;
		$video_h = $video_w / 1.61; //1.61 golden ratio
		global $wp_embed;
		$embed = $wp_embed->run_shortcode( '[embed]' . $_video_embedded . '[/embed]' );
		?>
		<div class="property-video">
			<h4 class="property-video-title"><?php _e('Property Video','noo')?></h4>
			<div class="property-video-content">
				<?php echo $embed; ?>
			</div>
		</div>
		<?php endif;?>
		<div class="property-map">
			<h4 class="property-map-title"><?php _e('Find this property on map','noo')?></h4>
			<div class="property-map-content">
				<div class="property-map-search">
					<input placeholder="<?php echo __('Search your map','noo')?>" type="text" autocomplete="off" id="property_map_search_input">
				</div>
				<?php 
				$property_category_terms          =   get_the_terms(get_the_ID(),'property_category' );
				$property_category_marker = '';
				if($property_category_terms && !is_wp_error($property_category_terms)){
					$map_markers = get_option( 'noo_category_map_markers' );
					foreach($property_category_terms as $category_term){
						if(empty($category_term->slug))
							continue;
						$property_category = $category_term->slug;
						if(isset($map_markers[$category_term->term_id]) && !empty($map_markers[$category_term->term_id])){
							$property_category_marker = wp_get_attachment_url($map_markers[$category_term->term_id]);
						}
						break;
					}
				}
				?>
				<div id="property-map-<?php echo get_the_ID()?>" class="property-map-box" data-marker="<?php echo esc_attr($property_category_marker)?>" data-zoom="<?php echo esc_attr(noo_get_post_meta(get_the_ID(), '_noo_property_gmap_zoom', '16'))?>" data-latitude="<?php echo esc_attr(noo_get_post_meta(get_the_ID(),'_noo_property_gmap_latitude'))?>" data-longitude="<?php echo esc_attr(noo_get_post_meta(get_the_ID(),'_noo_property_gmap_longitude'))?>"></div>
			</div>
		</div>
	</article> <!-- /#post- -->
	<?php NooProperty::contact_agent()?>
	<?php NooProperty::get_similar_property();?>
<?php endwhile;