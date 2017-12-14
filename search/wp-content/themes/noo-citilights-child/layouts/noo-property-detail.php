<style type="text/css">


table {
	background: #f5f5f5;
	border-collapse: separate;
	box-shadow: inset 0 1px 0 #fff;
	/*font-size: 12px;*/
	line-height: 24px;
	margin: 30px auto;
	text-align: left;
	
}	

th {
	background: url(http://jackrugile.com/images/misc/noise-diagonal.png), linear-gradient(#777, #444);
	border-left: 1px solid #555;
	border-right: 1px solid #777;
	border-top: 1px solid #555;
	border-bottom: 1px solid #333;
	box-shadow: inset 0 1px 0 #999;
	color: #fff;
    font-weight: bold;
	padding: 10px 15px;
	position: relative;
	text-shadow: 0 1px 0 #000;	
}

th:after {
	background: linear-gradient(rgba(255,255,255,0), rgba(255,255,255,.08));
	content: '';
	display: block;
	height: 25%;
	left: 0;
	margin: 1px 0 0 0;
	position: absolute;
	top: 25%;
	width: 100%;
}

th:first-child {
	border-left: 1px solid #777;	
	box-shadow: inset 1px 1px 0 #999;
}

th:last-child {
	box-shadow: inset -1px 1px 0 #999;
}

td {
	border-right: 1px solid #fff;
	border-left: 1px solid #e8e8e8;
	border-top: 1px solid #fff;
	border-bottom: 1px solid #e8e8e8;
	padding: 10px 15px;
	position: relative;
	transition: all 300ms;
}

td:first-child {
	box-shadow: inset 1px 0 0 #fff;
}	

td:last-child {
	border-right: 1px solid #e8e8e8;
	box-shadow: inset -1px 0 0 #fff;
}	

tr {
	background: url(http://jackrugile.com/images/misc/noise-diagonal.png);	
}

tr:nth-child(odd) td {
	background: #f1f1f1 url(http://jackrugile.com/images/misc/noise-diagonal.png);	
}

tr:last-of-type td {
	box-shadow: inset 0 -1px 0 #fff; 
}

tr:last-of-type td:first-child {
	box-shadow: inset 1px -1px 0 #fff;
}	

tr:last-of-type td:last-child {
	box-shadow: inset -1px -1px 0 #fff;
}	

tbody:hover td {
	color: transparent;
	text-shadow: 0 0 3px #aaa;
}

tbody:hover tr:hover td {
	color: #444;
	text-shadow: 0 1px 0 #fff;
}

.amazingslider-box-1 a {
    display: none;
}

.amazingslider-box-2 a {
    display: none;
}

.amazingslider-box-101 a {
    display: none;
}

.amazingslider-box-102 a {
    display: none;
}

.amazingslider-box-103 a {
    display: none;
}

.amazingslider-box-104 a {
    display: none;
}

.amazingslider-box-105 a {
    display: none;
}

.amazingslider-box-106 a {
    display: none;
}

.amazingslider-box-107 a {
    display: none;
}

.amazingslider-box-108 a {
    display: none;
}

.amazingslider-box-109 a {
    display: none;
}

.amazingslider-box-110 a {
    display: none;
}

.amazingslider-box-111 a {
    display: none;
}

.amazingslider-bottom-shadow-101 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-102 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-103 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-104 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-105 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-106 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-107 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-108 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-109 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-110 > img {
    display: none !important;
}

.amazingslider-bottom-shadow-111 > img {
    display: none !important;
}

/*
.amazingslider-box-101 > div {
    background-color: rgb(28, 38, 66) !important;
}

.amazingslider-box-102 > div {
    background-color: rgb(28, 38, 66) !important;
}

.amazingslider-box-103 > div {
    background-color: rgb(28, 38, 66) !important;
}
*/
.amazingslider-box-1 {
    border-bottom-color: #dedede;
    border-bottom-style: solid;
    border-bottom-width: 6px;
    border-left-color: #dedede;
    border-left-style: solid;
    border-left-width: 6px;
    border-right-color: #dedede;
    border-right-style: solid;
    border-right-width: 6px;
    border-top-color: #dedede;
    border-top-style: solid;
    border-top-width: 6px;
    display: block;
    height: auto;
    left: 0;
    margin-left: -6px;
    position: relative;
    top: 0;
    width: 100%;
}

</style>





<!-- ------------------------------------------- -->

<?php 
while ($query->have_posts()): $query->the_post(); global $post;
?>
	<article id="post-<?php the_ID(); ?>" class="property">
		<h1 class="property-title">
			<span id="pro-title"><?php the_title(); ?></span>
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
		$property_parking = noo_get_post_meta(get_the_ID(),'_parking');

		$pdf_title= noo_get_post_meta(get_the_ID(),'pdf_title');
		$post_id= noo_get_post_meta(get_the_ID(),'pdf_attachment');
		$property_pdf= get_post_meta($post_id,'_wp_attached_file',true);
		//var_dump($property_pdf);
        
		
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
				<div class="property-detail col-md-4 col-sm-4"><!--style="float:right;"-->
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
								<span class="col-sm-5 detail-field-label price-label"><?php echo __('Priced from','noo')?></span>
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
							<?php if( !empty($property_parking) ) : ?>
								<span class="col-sm-5 detail-field-label bathrooms-label"><?php echo __('Parking','noo')?></span>
								<span class="col-sm-7 detail-field-value bathrooms-value"><?php echo $property_parking;?></span>
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
				<div class="property-desc col-md-8 col-sm-8"><!--style="float:left;"-->
					<h4 class="property-detail-title"><?php _e('Property Description','noo')?></h4>
				</div>
				<div class="property-content">
					<?php the_content();?>
				</div>
                
                

                <!-- download pdf -->
                <?php if($property_pdf){ ?>
                 <br>
				<div id="wpba_attachment_list" class="wpba wpba-wrap" style="width: 100%;">
					<ul class="wpba-attachment-list unstyled">
						<li id="wpba_attachment_list" class="wpba-list-item pull-left">
							<img src="http://www.builteasy.com.au/wp-content/plugins/wp-better-attachments/assets/img/icons/file-icon.png" width="16" height="20" class="wpba-icon pull-left">
							<a href="javascript:void(0)" id="<?php echo $property_pdf ?>" class="pdf-title" onclick="goog_report_conversion ('http://www.builteasy.com.au/')"><?php echo $pdf_title ?></a>
						</li>
					</ul>
				</div>
				<?php } ?>
               <!-- end -->


			</div>


			<!-- pdf coapse start -->
		      <?php 
		        $pdf_id=getPropertyPdfId($property_id);
                
		      ?>

		      <?php if($pdf_id){ ?>
             
		      <?php $pdf_data= getPropertyPdfData($pdf_id); ?>
		      <?php $packages= getPropertyPackageData($pdf_id); ?>
		      <?php $faqs= getPropertyFaqData($pdf_id); ?>

		      <?php $fimages= getPropertyFeatureImage($pdf_id); ?>

		      <?php $bpackages= getPropertyBuildingPackage($pdf_id); ?>


		      <!-- check user login  -->
              
		      <?php if(!is_user_logged_in ()){ ?>
		      	
               <div class="panel panel-default" style="border: 2px solid #dedede;">
                  <div class="panel-body" style="cursor:pointer;text-align:center" onclick="LoginLayout('<?php the_title(); ?>')">
                    <b><a href="javascript:void(0)">Detailed Information</a></b>
					  
				  </div>
               </div>
		      <?php }else{ ?>
              
	          <div class="panel panel-default" style="border: 2px solid #dedede;">
				  <div class="panel-body" data-toggle="collapse" href="#collapseExample" aria-expanded="true" aria-controls="collapseExample" style="cursor:pointer;text-align:center" onclick="sendNotification_Property_Access('<?php the_title(); ?>')">
                    <b><a href="javascript:void(0)">Detailed Information </a></b>
					  
				  </div>
                  
				  <div class="collapse <?php echo ($_SESSION['clickToLogin']=='yes') ? 'in' : '' ?>" id="collapseExample">
					  <div class="well" style="background-color:white;">
					    <div class="page-header" style="margin-top:0;margin-bottom: 8;">
						  <h4><?php echo $pdf_data->pdf_title ?></h4>
						  <small style="font-weight: bold; font-size: 18px;"><?php echo $pdf_data->land_area. "m<sup>2</sup> | $".number_format($pdf_data->land_price) ?></small>
						  <br><br>
						  <small style="font-weight: bold; font-size: 18px;">Home and Land Package Options</small>
						</div>


						<small>Package Price includes additional $<?php echo number_format($pdf_data->site_cost) ?> allowance for site costs.</small>
                        
                        <div class="table-responsive" style="margin-top: 20px;">
                        <table class="table table-bordered pricing">
						    <thead>
						      <tr>
						        <th>Build Price</th>
						        <th>Size (m<sup>2</sup>)</th>
						        <th>Bed | Bath | Car</th>
						        <th>Storeys</th>
						        <th>Package Price</th>
						      </tr>
						    </thead>
						    <tbody>
                              <?php foreach($packages as $package){ ?>
						      <tr>
						        <td>$<?php echo number_format($package->build_price) ?></td>
						        <td><?php echo $package->size ?></td>
						        <td><?php echo $package->bed ?> | <?php echo $package->bath ?> | <?php echo $package->car ?></td>
						        <td><?php echo $package->storeys ?></td>
						        <td>$<?php echo number_format($package->package_price) ?></td>
						      </tr>
						      <?php } ?>
						    </tbody>
						  </table>
                          </div>
                          
                          <small>
                          	  <?php echo $pdf_data->acknowledgement ?> 
                          </small>
                          

<!-- ****************************Gallery Slider******************************** -->
                          <hr style="border-top: 4px solid #dedede;">
                          
                            <?php // echo displayAmazingGallery1($pdf_id); ?>
                            <?php echo displayAmazingGalleryReal($pdf_id); ?>


                            <br>
                            
<!-- ************************************************************************ -->                          
 
                          <!-- Accordian start -->
                          
                          <div class="mobile-colapse" style="display:block;">
                          <hr style="border-top: 4px solid #dedede;">

                          <small style="font-weight: bold; font-size: 18px;">Plan Options - click to reveal detail</small> <br><br>

                          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							  <?php $i=1; foreach($bpackages as $pkg){ ?>
							  <div class="panel panel-default">
                                
							    <div class="panel-heading" role="tab" id="heading_<?php echo $i ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i ?>" aria-expanded="<?php // echo ($i==1) ? 'true' : '' ?>" aria-controls="collapse_<?php echo $i ?>" style="cursor:pointer" onclick="manageslider_ht()">
							      <h4 class="panel-title">
							        <a role="button">
							          <?php echo $pkg->package_name ?>
							        </a>
							      </h4>
							    </div>

							    <div id="collapse_<?php echo $i ?>" class="panel-collapse collapse <?php // echo ($i==1) ? 'in' : '' ?>" role="tabpanel" aria-labelledby="heading_<?php echo $i ?>">
							      <div class="panel-body">
                                    
                                    <!-- get facade floar spaci -->

                                    <?php // echo getFacadeFloarSpac($pkg->id); ?>

                                    <?php echo getRealFacadeFloarSpac($pkg->id,$i); ?>


                                    <br>
                                    
                                    <hr>
                                    
                                     <div class="col-md-12">
						         	    <div role="alert" class="alert alert-danger" style="text-align:center;background-color:#E1E1E1 !important;color:black">
						         	     TURN KEY HOME<br> 
						         	     <span style="font-size:35px;"><strong> $ <?php echo number_format($pkg->package_price) ?></strong></span>
						         	     </div>
						         	 </div>	
                                    
						         	
						         	
							         
							      </div>
							    </div>
							  </div>
							  <?php $i++; } ?>



							</div>

						</div>
                          <!-- Accordian end -->


                          <hr style="border-top: 4px solid #dedede;">
                          <h4>MORE INFORMATION</h4>
                          
                          <hr>
                         
                          <?php echo $pdf_data->faq ?>
						  
                          <?php unset($_SESSION['clickToLogin']); ?>
					  </div>
						
				  </div>

				</div> 

				<?php } ?>
		        <!-- end of chck user logged in -->
		        <?php } ?>
        <!-- pdf colapse end -->

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



//require_once('my-custom-js.php'); 
?>