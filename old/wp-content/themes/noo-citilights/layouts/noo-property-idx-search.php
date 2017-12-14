
	<?php 
		$formAction = get_home_url() . "/idx/"; 
		$propertyTypes = dsSearchAgent_ApiRequest::FetchData("AccountSearchSetupFilteredPropertyTypes", array(), false, 60 * 60 * 24);
		$propertyTypes = $propertyTypes["response"]["code"] == "200" ? json_decode($propertyTypes["body"]) : null;
	?>

	<div class="gsearch idx">
		<div class="container-boxed">
			<div class="gsearch-wrap">
	   			<h3 class="gsearch-title">
	   				<i class="fa fa-search"></i>
	   				<span><?php _e( "SEARCH FOR IDX PROPERTY", 'noo' ); ?></span>
	   			</h3>
		   		<form action="<?php echo $formAction; ?>" class="gsearchform dsidx-resp-search-form" method="get" role="search" onsubmit="return dsidx_w.searchWidget.validate();">
		   			<style type="text/css">
		   				.noo-map .gsearch .idxs .gsearch-field > .form-group {
							width: 33.333%;
		   				}
		   			</style>
		   			<div class="gsearch-content idxs">
		   				<div class="gsearch-field">
				   			<div class="form-group glocation">
   								<div class="dropdown">
						   				<span class="glocation-label" data-toggle="dropdown"><?php _e( "Property Type", 'noo' ); ?></span>
						   					<ul class="dropdown-menu">
											<li><a data-value="" href="#"><?php _e( "Property Type", 'noo' ); ?></a></li>
											<?php
												if (is_array($propertyTypes)) {
													foreach ($propertyTypes as $propertyType) {
														$name = htmlentities($propertyType->DisplayName);
														echo "<li class=\"level-0\" ><a href=\"#\" data-value=\"{$propertyType->SearchSetupPropertyTypeID}\">{$name}</a></li>";
													}
												}
											?>
										</ul>
   										<input type="hidden" class="dsidx-search-widget-propertyTypes" name="idx-q-PropertyTypes" value="">
   								</div>
   							</div>
				   			<div class="form-group baths">
   								<div class="dropdown">
						   				<span class="glocation-label" data-toggle="dropdown"><?php _e( "Baths", 'noo' ); ?></span>
						   					<ul class="dropdown-menu">
						   						<li><a data-value="" href="#"><?php _e( "Baths", 'noo' ); ?></a></li>
											<?php
												foreach (range(1, 9) as $num) {
													echo "<li class=\"level-0\" ><a href=\"#\" data-value=\"{$num}\">{$num}+</a></li>";
												}
											?>
										</ul>
   										<input type="hidden" id="idx-q-BathsMin" class="dsidx-baths" name="idx-q-BathsMin" value="">
   								</div>
   							</div>
				   			<div class="form-group beds">
   								<div class="dropdown">
						   				<span class="glocation-label" data-toggle="dropdown"><?php _e( "Beds", 'noo' ); ?></span>
						   					<ul class="dropdown-menu">
						   						<li><a data-value="" href="#"><?php _e( "Beds", 'noo' ); ?></a></li>
											<?php
												foreach (range(1, 9) as $num) {
													echo "<li class=\"level-0\" ><a href=\"#\" data-value=\"{$num}\">{$num}+</a></li>";
												}
											?>
										</ul>
   										<input type="hidden" id="idx-q-BedsMin" class="idx-q-BedsMin" name="idx-q-BedsMin" value="">
   								</div>
   							</div>
							<div class="form-group gprice">
					   			<span class="gprice-label"><?php _e( "Price", 'noo' ); ?></span>
					   			<div class="gprice-slider-range"></div>
					   			<input type="hidden" name="idx-q-PriceMin" id="idx-q-PriceMin" class="gprice_min" data-min="0" placeholder="Any" value="0" />
					   			<input type="hidden" name="idx-q-PriceMax" id="idx-q-PriceMax" class="gprice_max" data-max="500000" placeholder="Any" value="500000" />
					   		</div>
							<div class="form-group gbath">
								<style type="text/css">
									.gprice-slider-range input[type="text"] {
										width: 100%;
										padding: 7px;
										/* border-radius: 3px; */
										height: 50px;
										line-height: 50px;
										padding: 0 20px;
										display: block;
										cursor: pointer;
										border: 1px solid #e5e5e5;
										-webkit-border-radius: 4px;
										border-radius: 4px;
										overflow: hidden;
										position: relative;
									}
								</style>
					   			<div class="gprice-slider-range">
					   				<input id="dsidx-resp-location" name="idx-q-Locations" type="text" class="text dsidx-search-omnibox-autocomplete ui-autocomplete-input" placeholder="<?php _e( "Location", 'noo' ); ?>" value="" />
					   			</div>
   							</div>
						</div>
					   	<div class="gsearch-action">
					   		<div  class="gsubmit ">
					   			<button type="submit" class="submit"><?php _e( "Search Property", 'noo' ); ?></button>
					   		</div>
					   	</div>
			   		</div>
			   	</form>
			</div>
			</div>
			</div>
			</div>
		</div>
		</div>
	</div>
