<?php
/** Google Map block **/	

class Homeland_GMap_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Google Map', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_gmap_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_lat' => '',
			'homeland_lng' => '',
			'homeland_zoom' => ''
		);

		$instance = wp_parse_args($instance, $defaults);
		extract($instance);

		$homeland_zoom_options = array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => '7',
			'8' => '8',
			'9' => '9',
			'10' => '10',
			'11' => '11',
			'12' => '12',
			'13' => '13',
			'14' => '14',
			'15' => '15',
			'16' => '16',
			'17' => '17',
			'18' => '18',
			'19' => '19',
			'20' => '20',
		);
		
		?>
		
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_lat'); ?>">
				<?php 
					_e( 'Latitude', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_lat', $block_id, $homeland_lat); 
				?>
				<small><?php _e( 'Add your google map latitude', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_lng'); ?>">
				<?php 
					_e( 'Longitude', 'aqpb-l10n' );
					echo aq_field_input('homeland_lng', $block_id, $homeland_lng); 
				?>
				<small><?php _e( 'Add your google map longitude', 'aqpb-l10n' ); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_zoom'); ?>">
				<?php 
					_e( 'Zoom', 'aqpb-l10n' ); 
					echo aq_field_select('homeland_zoom', $block_id, $homeland_zoom_options, $homeland_zoom); 
				?>
				<small><?php _e(' Enter your number of testimonial to be display', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $post, $wp_query;
		$homeland_map_pointer_icon = get_option('homeland_map_pointer_icon');
		$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
		$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 
		$homeland_price_format = get_option('homeland_price_format');
		$homeland_map_pointer_clusterer_icon = esc_attr( get_option('homeland_map_pointer_clusterer_icon') );

		?>
		<script type="text/javascript">
			(function($) {
			  	"use strict";
			  	var map;
			   $(document).ready(function(){
			    	map = new GMaps({
				      div: '#map-homepage',
				      scrollwheel: false,
				      lat: <?php echo $homeland_lat; ?>,
						lng: <?php echo $homeland_lng; ?>,
						zoom: <?php echo $homeland_zoom; ?>,
						markerClusterer: function(map) {
					   	return new MarkerClusterer(map, [], {
					      	gridSize: 60,
					      	maxZoom: 14,
						      <?php
						      	if(!empty($homeland_map_pointer_clusterer_icon)) : ?>
						      		styles: [{
											width: 50,
											height: 50,
											url: "<?php echo $homeland_map_pointer_clusterer_icon; ?>"
										}] <?php
						      	endif;
						      ?>
					    	});
					 	}
			      });		 			    

	      		<?php
	      			if(!empty($homeland_map_pointer_icon)) : ?>var image = '<?php echo $homeland_map_pointer_icon; ?>';<?php endif;

	      			$args = array( 'post_type' => 'homeland_properties' );
	      			$wp_query = new WP_Query( $args );	

						while ( $wp_query->have_posts() ) : 
							$wp_query->the_post(); 	

							$homeland_property_lat = get_post_meta( $post->ID, 'homeland_property_lat', true );
							$homeland_property_lng = get_post_meta( $post->ID, 'homeland_property_lng', true );
							$homeland_price = esc_attr( get_post_meta( $post->ID, 'homeland_price', true ) );
							$homeland_price_per = esc_attr( get_post_meta( $post->ID, 'homeland_price_per', true ) );
							
							?>
								map.addMarker({
									lat: <?php echo $homeland_property_lat; ?>,
									lng: <?php echo $homeland_property_lng; ?>,
							      title: '<?php the_title(); ?>',
							      <?php if(!empty($homeland_map_pointer_icon)) : ?>icon: image, <?php endif; ?>
							      infoWindow: {
									   content: '<div class="marker-window"><a href="<?php the_permalink(); ?>"><?php if ( has_post_thumbnail() ) : the_post_thumbnail('homeland_property_thumb'); endif; ?></a><h6><?php the_title(); ?></h6><?php if(!empty($homeland_price)) : ?><h5><?php homeland_property_price_format(); ?></h5><?php endif; ?><a href="<?php the_permalink(); ?>" class="view-gmap">View More</a></div>'
									   }
							    	});
							<?php
				    	endwhile; 
				    	wp_reset_query();
	      		?>			        
			   });
			})(jQuery);					
		</script>

		<section id="map-homepage"></section><?php
	}
	
}

function homeland_widget_enqueue_gmap() {
	wp_register_script( 'homeland_gmap-sensor', 'http://maps.google.com/maps/api/js?sensor=true' );
	wp_register_script( 'homeland_gmap-marker-clusterer', 'http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/1.0/src/markerclusterer.js' );
	wp_register_script( 'homeland_gmap', get_template_directory_uri() . '/js/gmaps.min.js' );

	wp_enqueue_script( 'homeland_gmap-sensor' );
	wp_enqueue_script( 'homeland_gmap-marker-clusterer' );	
	wp_enqueue_script( 'homeland_gmap' );	
}
add_action( 'wp_enqueue_scripts', 'homeland_widget_enqueue_gmap' );