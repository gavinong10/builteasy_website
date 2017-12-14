<?php
/** Advance Search block **/
class Homeland_Advance_Search_Block extends AQ_Block {
	
	//set and create block
	function __construct() {
		$block_options = array(
			'name' => __('Advance Search', 'aqpb-l10n'),
			'size' => 'span12',
		);
		
		//create the block
		parent::__construct('homeland_advance_search_block', $block_options);
	}
	
	function form($instance) {
		
		$defaults = array(
			'homeland_hide_id' => 0,
			'homeland_hide_location' => 0,
			'homeland_hide_type' => 0,
			'homeland_hide_status' => 0,
			'homeland_hide_beds' => 0,
			'homeland_hide_bath' => 0,
			'homeland_hide_min_price' => 0,
			'homeland_hide_max_price' => 0,
			'homeland_id_label' => '',
			'homeland_location_label' => '',
			'homeland_type_label' => '',
			'homeland_status_label' => '',
			'homeland_beds_label' => '',
			'homeland_bath_label' => '',
			'homeland_min_price_label' => '',
			'homeland_max_price_label' => '',
			'homeland_button_label' => '',
			'homeland_min_price_range' => '',
			'homeland_max_price_range' => '',
			'homeland_bed_number' => '',
			'homeland_bath_number' => ''
		);
		
		$instance = wp_parse_args($instance, $defaults);
		extract($instance);
		
		?>

		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_hide_id'); ?>">
				<?php 
					_e( 'Hide ID', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide_id', $block_id, $homeland_hide_id); 
				?>
			</label>
		</p>	
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_hide_location'); ?>">
				<?php 
					_e( 'Hide Location', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide_location', $block_id, $homeland_hide_location); 
				?>
			</label>
		</p>

		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_id_label'); ?>">
				<?php 
					_e( 'ID', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_id_label', $block_id, $homeland_id_label); 
				?>
				<small><?php _e('Enter your property id label', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_location_label'); ?>">
				<?php 
					_e( 'Location', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_location_label', $block_id, $homeland_location_label); 
				?>
				<small><?php _e('Enter your property location label', 'aqpb-l10n'); ?></small>
			</label>
		</p>

		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_hide_type'); ?>">
				<?php 
					_e( 'Hide Type', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide_type', $block_id, $homeland_hide_type); 
				?>
			</label>
		</p>	
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_hide_status'); ?>">
				<?php 
					_e( 'Hide Status', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide_status', $block_id, $homeland_hide_status); 
				?>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_type_label'); ?>">
				<?php 
					_e( 'Type', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_type_label', $block_id, $homeland_type_label); 
				?>
				<small><?php _e('Enter your property type label', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_status_label'); ?>">
				<?php 
					_e( 'Status', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_status_label', $block_id, $homeland_status_label); 
				?>
				<small><?php _e('Enter your property status label', 'aqpb-l10n'); ?></small>
			</label>
		</p>

		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_hide_beds'); ?>">
				<?php 
					_e( 'Hide Bedroom', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide_beds', $block_id, $homeland_hide_beds); 
				?>
			</label>
		</p>	
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_hide_bath'); ?>">
				<?php 
					_e( 'Hide Bathroom', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide_bath', $block_id, $homeland_hide_bath); 
				?>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_beds_label'); ?>">
				<?php 
					_e( 'Bedroom', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_beds_label', $block_id, $homeland_beds_label); 
				?>
				<small><?php _e('Enter your property bedroom label', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_bath_label'); ?>">
				<?php 
					_e( 'Bathroom', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_bath_label', $block_id, $homeland_bath_label); 
				?>
				<small><?php _e('Enter your property bathroom label', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_bed_number'); ?>">
				<?php 
					_e( 'Bedroom Options', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_bed_number', $block_id, $homeland_bed_number); 
				?>
				<small><?php _e('Enter your property bedroom options', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_bath_number'); ?>">
				<?php 
					_e( 'Bathroom Options', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_bath_number', $block_id, $homeland_bath_number); 
				?>
				<small><?php _e('Enter your property bathroom options', 'aqpb-l10n'); ?></small>
			</label>
		</p>

		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_hide_min_price'); ?>">
				<?php 
					_e( 'Hide Minimum Price', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide_min_price', $block_id, $homeland_hide_min_price); 
				?>
			</label>
		</p>	
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_hide_max_price'); ?>">
				<?php 
					_e( 'Hide Maximum Price', 'aqpb-l10n' ); 
					echo aq_field_checkbox('homeland_hide_max_price', $block_id, $homeland_hide_max_price); 
				?>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_min_price_label'); ?>">
				<?php 
					_e( 'Minimum Price', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_min_price_label', $block_id, $homeland_min_price_label); 
				?>
				<small><?php _e('Enter your property minimum price label', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_max_price_label'); ?>">
				<?php 
					_e( 'Maximum Price', 'aqpb-l10n' ); 
					echo aq_field_input('homeland_max_price_label', $block_id, $homeland_max_price_label); 
				?>
				<small><?php _e('Enter your property maximum price label', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half">
			<label for="<?php echo $this->get_field_id('homeland_min_price_range'); ?>">
				<?php 
					_e( 'Minimum Price Range', 'aqpb-l10n' ); 
					echo aq_field_textarea('homeland_min_price_range', $block_id, $homeland_min_price_range, $size = 'full'); 
				?>
				<small><?php _e('Enter your minimum price range', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_max_price_range'); ?>">
				<?php 
					_e( 'Maximum Price Range', 'aqpb-l10n' );
					echo aq_field_textarea('homeland_max_price_range', $block_id, $homeland_max_price_range, $size = 'full'); 
				?>
				<small><?php _e('Enter your maximum price range', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		<p class="description half last">
			<label for="<?php echo $this->get_field_id('homeland_button_label'); ?>">
				<?php 
					_e( 'Button Label', 'aqpb-l10n' );
					echo aq_field_input('homeland_button_label', $block_id, $homeland_button_label); 
				?>
				<small><?php _e('Enter your submit button label', 'aqpb-l10n'); ?></small>
			</label>
		</p>
		
		<?php
	}
	
	function block($instance) {
		extract($instance);

		global $homeland_advance_search_page_url;

		$homeland_currency = esc_attr( get_option('homeland_property_currency') ); 
		$homeland_property_currency_sign = esc_attr( get_option('homeland_property_currency_sign') ); 
		$homeland_price_format = get_option('homeland_price_format');
		$homeland_property_decimal = esc_attr( get_option('homeland_property_decimal') );
		$homeland_property_decimal = !empty($homeland_property_decimal) ? $homeland_property_decimal : 0;
		$homeland_prefix = "-- ";
		$homeland_property_currency_after = "";
		$homeland_property_currency_before = "";

		$homeland_search_term = isset($_GET['pid']);
		$homeland_search_term = isset($_GET['location']);
		$homeland_search_term = isset($_GET['type']);
		$homeland_search_term = isset($_GET['status']);
		$homeland_search_term = isset($_GET['bed']);
		$homeland_search_term = isset($_GET['bath']);
		$homeland_search_term = isset($_GET['min-price']);
		$homeland_search_term = isset($_GET['max-price']);

		$homeland_array_bed = explode(", ", $homeland_bed_number);
		$homeland_array_bath = explode(", ", $homeland_bath_number);
		$homeland_array_min_price = explode(", ", $homeland_min_price_range);
		$homeland_array_max_price = explode(", ", $homeland_max_price_range);

		if(!empty( $homeland_id_label )) : $homeland_id_label = $homeland_id_label;
		else : $homeland_id_label = __( 'Property ID', 'aqpb-l10n' );
		endif;

		if(!empty( $homeland_location_label )) : $homeland_location_label = $homeland_location_label;
		else : $homeland_location_label = __( 'Location', 'aqpb-l10n' );
		endif;

		if(!empty( $homeland_type_label )) : $homeland_type_label = $homeland_type_label;
		else : $homeland_type_label = __( 'Type', 'aqpb-l10n' );
		endif;

		if(!empty( $homeland_status_label )) : $homeland_status_label = $homeland_status_label;
		else : $homeland_status_label = __( 'Status', 'aqpb-l10n' );
		endif;

		if(!empty( $homeland_beds_label )) : $homeland_beds_label = $homeland_beds_label;
		else : $homeland_beds_label = __( 'Bedrooms', 'aqpb-l10n' );
		endif;

		if(!empty( $homeland_bath_label )) : $homeland_bath_label = $homeland_bath_label;
		else : $homeland_bath_label = __( 'Bathrooms', 'aqpb-l10n' );
		endif;

		if(!empty( $homeland_min_price_label )) : $homeland_min_price_label = $homeland_min_price_label;
		else : $homeland_min_price_label = __( 'Minimum Price', 'aqpb-l10n' );
		endif;

		if(!empty( $homeland_max_price_label )) : $homeland_max_price_label = $homeland_max_price_label;
		else : $homeland_max_price_label = __( 'Maximum Price', 'aqpb-l10n' );
		endif;

		?>

		<section class="advance-search-block">
			<div class="inside">
				<form action="<?php echo $homeland_advance_search_page_url; ?>" method="get" id="searchform">
					<ul class="clear">
						<?php
							if(empty( $homeland_hide_id )) : ?>
								<li>
									<input type="text" name="pid" class="property-id" value="<?php if($homeland_search_term) : echo $_GET['pid']; endif; ?>" placeholder="<?php echo $homeland_id_label; ?>" />
								</li><?php
							endif; 

							if(empty( $homeland_hide_location )) : ?>
								<li>
									<select name="location">
										<option value="" selected="selected"><?php echo $homeland_location_label; ?></option>
										<?php
											$args = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => 0 );
											$homeland_terms = get_terms('homeland_property_location', $args);

											foreach ($homeland_terms as $homeland_plocation) : ?>
											   <option value="<?php echo $homeland_plocation->slug; ?>" <?php if($homeland_search_term == $homeland_plocation->slug) : echo "selected='selected'"; endif; ?>>
											   	<?php echo $homeland_plocation->name; ?>
											   </option><?php

											   //Child

											   $args_child = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => $homeland_plocation->term_id );
												$homeland_terms_child = get_terms('homeland_property_location', $args_child);

												foreach ($homeland_terms_child as $homeland_plocation_child) : ?>
												   <option value="<?php echo $homeland_plocation_child->slug; ?>" <?php if($homeland_search_term == $homeland_plocation_child->slug) : echo "selected='selected'"; endif; ?>>
												   	<?php echo $homeland_prefix . $homeland_plocation_child->name; ?>
												   </option><?php
												endforeach;
											endforeach;
										?>						
									</select>									
								</li><?php
							endif;

							if(empty( $homeland_hide_type )) : ?>
								<li>
									<select name="type">
										<option value="" selected="selected"><?php echo $homeland_type_label; ?></option>
										<?php
											$args = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => 0 );
											$homeland_terms = get_terms('homeland_property_type', $args);

											if(!empty($homeland_terms)) :
												foreach ($homeland_terms as $homeland_ptype) : ?>
												   <option value="<?php echo $homeland_ptype->slug; ?>" <?php if($homeland_search_term == $homeland_ptype->slug) : echo "selected='selected'"; endif; ?>>
												   	<?php echo $homeland_ptype->name; ?>
												   </option><?php

												   //Child

												   $args_child = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => $homeland_ptype->term_id );
													$homeland_terms_child = get_terms('homeland_property_type', $args_child);

													foreach ($homeland_terms_child as $homeland_ptype_child) : ?>
													   <option value="<?php echo $homeland_ptype_child->slug; ?>" <?php if($homeland_search_term == $homeland_ptype_child->slug) : echo "selected='selected'"; endif; ?>>
													   	<?php echo $homeland_prefix . $homeland_ptype_child->name; ?>
													   </option><?php
													endforeach;
												endforeach;
											endif;
										?>
									</select>
								</li><?php
							endif;

							if(empty( $homeland_hide_status )) : ?>
								<li>
									<select name="status">
										<option value="" selected="selected"><?php echo $homeland_status_label; ?></option>
										<?php
											$args = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => 0 );
											$homeland_terms = get_terms('homeland_property_status', $args);

											foreach ($homeland_terms as $homeland_pstatus) : ?>
											   <option value="<?php echo $homeland_pstatus->slug; ?>" <?php if($homeland_search_term == $homeland_pstatus->slug) : echo "selected='selected'"; endif; ?>>
											   	<?php echo $homeland_pstatus->name; ?>
											   </option><?php

											   //Child

											   $args_child = array( 'hide_empty' => 0, 'hierarchical' => 0, 'parent' => $homeland_pstatus->term_id );
												$homeland_terms_child = get_terms('homeland_property_status', $args_child);

												foreach ($homeland_terms_child as $homeland_pstatus_child) : ?>
												   <option value="<?php echo $homeland_pstatus_child->slug; ?>" <?php if($homeland_search_term == $homeland_pstatus_child->slug) : echo "selected='selected'"; endif; ?>>
												   	<?php echo $homeland_prefix . $homeland_pstatus_child->name; ?>
												   </option><?php
												endforeach;
											endforeach;
										?>						
									</select>
								</li><?php
							endif;

							if(empty( $homeland_hide_beds )) : ?>
								<li>
									<select name="bed" class="small">
										<option value="" selected="selected"><?php echo $homeland_beds_label; ?></option>
										<?php
											foreach($homeland_array_bed as $homeland_number_option) : ?>
							               <option value="<?php echo $homeland_number_option; ?>" <?php if($homeland_search_term == $homeland_number_option) : echo "selected='selected'"; endif; ?>>
							               	<?php echo $homeland_number_option; ?>
							               </option><?php
							            endforeach;
										?>						
									</select>
								</li><?php
							endif;

							if(empty( $homeland_hide_bath )) : ?>
								<li>
									<select name="bath" class="small">
										<option value="" selected="selected"><?php echo $homeland_bath_label; ?></option>
										<?php
											foreach($homeland_array_bath as $homeland_number_option) : ?>
							               <option value="<?php echo $homeland_number_option; ?>" <?php if($homeland_search_term == $homeland_number_option) : echo "selected='selected'"; endif; ?>>
							               	<?php echo $homeland_number_option; ?>
							               </option><?php
							            endforeach;
										?>		
									</select>
								</li><?php
							endif;

							if(empty( $homeland_hide_min_price )) : ?>
								<li>
									<select name="min-price" class="small">
										<option value="" selected="selected"><?php echo $homeland_min_price_label; ?></option>			
										<?php
											foreach($homeland_array_min_price as $homeland_number_option) :
												//Currency Position
												if( $homeland_property_currency_sign == "After" ) : $homeland_property_currency_after = $homeland_currency; 
												else : $homeland_property_currency_before = $homeland_currency; 
												endif;

												//Price Format
												if($homeland_price_format == "Dot") :
													$homeland_price_format_result = number_format ( $homeland_number_option, $homeland_property_decimal, ".", "." );
												elseif($homeland_price_format == "Comma") : 
													$homeland_price_format_result = number_format ( $homeland_number_option, $homeland_property_decimal );
												elseif($homeland_price_format == "Brazil" || $homeland_price_format == "Europe") :
													$homeland_price_format_result = number_format( $homeland_number_option, $homeland_property_decimal, ",", "." );
												else : 
													$homeland_price_format_result = $homeland_number_option;
												endif;

												?>	
												<option value="<?php echo $homeland_number_option; ?>" <?php if($homeland_search_term == $homeland_number_option) : echo "selected='selected'"; endif; ?>>
													<?php 
														echo $homeland_property_currency_before . $homeland_price_format_result . $homeland_property_currency_after;
													?>
												</option><?php
							            endforeach;
										?>					
									</select>
								</li><?php
							endif;

							if(empty( $homeland_hide_max_price )) : ?>
								<li>
									<select name="max-price" class="small">
										<option value="" selected="selected"><?php echo $homeland_max_price_label; ?></option>	
										<?php
											foreach($homeland_array_max_price as $homeland_number_option) : 
												//Currency Position
												if( $homeland_property_currency_sign == "After" ) : $homeland_property_currency_after = $homeland_currency; 
												else : $homeland_property_currency_before = $homeland_currency; 
												endif;

												//Price Format
												if($homeland_price_format == "Dot") :
													$homeland_price_format_result = number_format ( $homeland_number_option, $homeland_property_decimal, ".", "." );
												elseif($homeland_price_format == "Comma") : 
													$homeland_price_format_result = number_format ( $homeland_number_option, $homeland_property_decimal );
												elseif($homeland_price_format == "Brazil" || $homeland_price_format == "Europe") :
													$homeland_price_format_result = number_format( $homeland_number_option, $homeland_property_decimal, ",", "." );
												else : 
													$homeland_price_format_result = $homeland_number_option;
												endif;

												?>
												<option value="<?php echo $homeland_number_option; ?>" <?php if($homeland_search_term == $homeland_number_option) : echo "selected='selected'"; endif; ?>>
													<?php 
														echo $homeland_property_currency_before . $homeland_price_format_result . $homeland_property_currency_after;
													?>
												</option><?php
							            endforeach;
										?>	
									</select>
								</li><?php
							endif;
						?>
						
						<li class="last"><input type="submit" value="<?php echo $homeland_button_label; ?>" /></li>
					</ul>

				</form>	
			</div>
		</section>
		
		<?php

	}
	
}