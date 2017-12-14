<script type="text/javascript">
	var svg_url = "<?php echo plugins_url('/spinners/svg/',__FILE__); ?>",
		gif_url = "<?php echo plugins_url('/spinners/gif/64/',__FILE__); ?>";
</script>

<?php add_thickbox(); ?>

<div class="wrap">

	<h2><?php echo $title;?> <a href="" class="add-new-h2">Add New</a></h2>

	<div id="notification-messages"></div>	

	<?php 
		$preloader_list = new Preloader_Ultimate_List();
	    $preloader_list->prepare_items();
	?>

	 <form id="preloader-table-form" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
        <?php $preloader_list->display() ?>
    </form>

</div><!-- end .wrap -->


<div id="preloader-setting-modal" style="display:none;">
	<div class="preloader-ultimate-settings">

		<button class="button-primary save-options">Save Settings</button>

		<div class="general-options">
			
			<h2>General Settings</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="preloader-title">Title</label>
							<p class="description">Give a title for this setting for you to easily track on what post/page this setting will be apply</p>
						</th>
						<td>
							<input id="preloader-title" type="text" value="" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="available-on">Available On <span class="required">(required)</span></label>
							<p class="description">Enter the post/page ID or the slug name, category name or tag name you want this setting to be apply. If you want this to be apply on the home page just type home on the textbox</p>
						</th>
						<td>
							<input id="available-on" type="text" value="" />
						</td>
					</tr>
					<tr>
						<th>
							<label for="enable-preloader">Enable Preloader</label>
							<p class="description">Uncheck the box if you want to disabled the preloader</p>
						</th>
						<td>
							<input id="enable-preloader" type="checkbox" checked/>
						</td>
					</tr>
					<tr>
						<th>
							<label for="enable-on-mobile">Enable on Mobile</label>
							<p class="description">Uncheck the box if you want the preloader to be disabled on mobile devices</p>
						</th>
						<td>
							<input id="enable-on-mobile" type="checkbox" checked/>
						</td>
					</tr>
					<tr>
						<th>
							<label for="hide-scrollbar">Hide Scrollbar</label>
							<p class="description">Check the box if you want to hide the page scrollbar while preloader is still loading</p>
						</th>
						<td>
							<input id="hide-scrollbar" type="checkbox" />
						</td>
					</tr>
				</tbody>
			</table>

		</div><!-- end .general-options -->


		<div class="spinner-options">

			<h2>Loading Spinner Settings</h2><br>
		
			<label class="label">Choose Loading Spinner</label>
			<p class="description">
				Choose the best loading spinner you want to use on your preloader. 
				You can choose from the four different options below (CSS, GIF, SVG, Custom Upload).
				Just click on the loading spinner you want to use and it will display on loading spinner preview below.
				</p>
			<div class="spinner-tabs">
				<div class="nav-tab-wrapper">	
					<div spintype="css" class="nav-tab nav-tab-active">CSS3</div>      
			      	<div spintype="gif" class="nav-tab">GIF</div>			      
			      	<div spintype="svg" class="nav-tab">SVG</div>
			      	<div spintype="custom" class="nav-tab">Custom Upload</div>
			    </div>

			    <div class="spinner-list">  
			    	<div class="css3-loader-list"></div>	
			    	<div class="gif-img-list" style="display:none;"></div>				    	
			    	<div class="svg-img-list" style="display:none;"></div>
				    <div class="custom-upload" style="display:none;">
					    <div class="upload-preview">
					    	<img class="" src="">
					    </div>
					    <div style="text-align:right;">
					    	<button type="button" class="button-secondary upload-btn">Upload</button>
					    	<button type="button" class="button-secondary remove-btn">Remove</button>
					    </div>
					</div>
			    </div>
			</div>

			<label class="label">Loading Spinner Preview</label>
			<div id="spinner-preview">
				<!-- display preloader preview -->
			</div>

			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label>Loading Spinner Width</label>
							<p class="description">Set the width of the loading spinner (height is auto)</p>
						</th>
						<td>
							<div class="slider-con">
								<div id="spinner-width"></div>
								<div id="spinner-width-display"></div>
							</div>
						</td>
					</tr>
					<tr style="display:none;">
						<th>
							<label>Loading Spinner Width</label>
							<p class="description">Set the width of the loading spinner (height is auto)</p>
						</th>
						<td>
							<div id="css-width">
								<div>
									<label><input name="css-width" type="radio" value="la-sm">small</label>
							    </div>
							    <div>
							        <label><input name="css-width" type="radio" value="" checked>normal</label>
							    </div>
							    <div>
							        <label><input name="css-width" type="radio" value="la-2x" >medium</label>
							    </div>
							    <div>
							        <label><input name="css-width" type="radio" value="la-3x" >large</label>
							    </div>
							</div>
						</td>
					</tr>
					<tr style="display:none;">
						<th>
							<label>Loading Spinner Color</label>
							<p class="description">Choose the color you want to apply on the loading spinner</p>
						</th>
						<td>
							<input class="color-select" type="text" value="#ffffff" id="spinner-color" data-default-color="#ffffff" />
						</td>
					</tr>
					<tr>
						<th>
							<label>Background Color</label>
							<p class="description">Choose the best background color for the preloader</p>
						</th>
						<td>
							<input class="color-select" value="#1abc9c" id="spinner-background" data-alpha="true" data-reset-alpha="true" data-default-color="#1abc9c" />
						</td>
					</tr>
				</tbody>
			</table>

		</div><!-- end .spinner-options -->


		<div class="ie-spinner-options" style="display:none;">

			<h2>SVG Loading Spinner IE Fallback Settings</h2>
			<p class="description">(note: Internet Explorer doesn't suppot SVG animation so you need to set the fallback loading spinner for it)</p>
		
			<label class="label">Choose Loading Spinner</label>
			<p class="description">
				Choose the best loading spinner you want to use on your preloader. 
				You can choose from the four different options below (CSS, GIF, SVG, Custom Upload).
				Just click on the loading spinner you want to use and it will display on loading spinner preview below.
			</p>
			<div class="spinner-tabs">
				<div class="nav-tab-wrapper">      
			      <div spintype="css" class="nav-tab nav-tab-active">CSS3</div>
			      <div spintype="gif" class="nav-tab">GIF</div>
			      <div spintype="custom" class="nav-tab">Custom Upload</div>
			    </div>

			    <div class="spinner-list">
			    	<div class="css3-loader-list"></div>
			    	<div class="gif-img-list" style="display:none;"></div>				    	
				    <div class="custom-upload" style="display:none;">
					    <div class="upload-preview">
					    	<img class="" src="">
					    </div>
					    <div style="text-align:right;">
					    	<button type="button" class="button-secondary upload-btn">Upload</button>
					    	<button type="button" class="button-secondary remove-btn">Remove</button>
					    </div>
					</div>
			    </div>
			</div>

			<label class="label">Loading Spinner Preview</label>
			<div id="ie-spinner-preview">
				<!-- display preloader preview -->
			</div>

			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label>Loading Spinner Width</label>
							<p class="description">Set the width of the loading spinner (height is auto)</p>
						</th>
						<td>
							<div class="slider-con">
								<div id="ie-spinner-width"></div>
								<div id="ie-spinner-width-display"></div>
							</div>
						</td>
					</tr>
					<tr style="display:none;">
						<th>
							<label>Loading Spinner Width</label>
							<p class="description">Set the width of the loading spinner (height is auto)</p>
						</th>
						<td>
							<div id="ie-css-width">
								<div>
									<label><input name="ie-css-width" type="radio" value="la-sm">small</label>
							    </div>
							    <div>
							        <label><input name="ie-css-width" type="radio" value="" checked>normal</label>
							    </div>
							    <div>
							        <label><input name="ie-css-width" type="radio" value="la-2x" >medium</label>
							    </div>
							    <div>
							        <label><input name="ie-css-width" type="radio" value="la-3x" >large</label>
							    </div>
							</div>
						</td>
					</tr>
					<tr style="display:none;">
						<th>
							<label>Loading Spinner Color</label>
							<p class="description">Choose the color you want to apply on the loading spinner</p>
						</th>
						<td>
							<input class="color-select" type="text" value="#ffffff" id="ie-spinner-color" data-default-color="#ffffff" />
						</td>
					</tr>
					<tr>
						<th>
							<label>Background Color</label>
							<p class="description">Choose the best background color for the preloader</p>
						</th>
						<td>
							<input class="color-select" value="#1abc9c" id="ie-spinner-background" data-alpha="true" data-reset-alpha="true" data-default-color="#1abc9c" />
						</td>
					</tr>
				</tbody>
			</table>

		</div><!-- end .ie-spinner-options -->


		<div class="text-options">
			
			<h2>Loading Spinner Text Settings</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label>Loading Spinner Text</label>
							<p class="description">Enter the text you want to appear below the loading spinner. leave it blank if you don't want any text to appear</p>
						</th>
						<td>
							<input id="text-spinner" type="text" value="">
							<p class="description">
								if you want apply additional css styling to the text, just wrap it with a span element and add inline styling on it.
								ex: &lt;span style="font-family:arial"&gt;Please Wait...&lt;/span&gt;
							</p>
						</td>
					</tr>
					<tr>
						<th>
							<label>Text Color</label>
							<p class="description">Choose the color for the text</p>
						</th>
						<td>
							<input class="color-select" type="text" value="#ffffff" id="text-color" data-default-color="#ffffff" />
						</td>
					</tr>
					<tr>
						<th>
							<label>Text Font Size</label>
							<p class="description">Set the font size for the text</p>
						</th>
						<td>
							<div class="slider-con">
								<div id="text-size"></div>
								<div id="text-size-display"></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

		</div><!-- end .text-options -->
		

		<div class="exit-options">
			
			<h2>Preloader Exit Settings</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label>Exit Animation Effect</label>
							<p class="description">Select the preloader exit animation effect</p>
						</th>
						<td>
							<select id="exit-animation">
					            <optgroup label="Sliding Exits">
					              	<option value="slideOutUp">slideOutUp</option>
					              	<option value="slideOutDown">slideOutDown</option>
					              	<option value="slideOutLeft">slideOutLeft</option>
					              	<option value="slideOutRight">slideOutRight</option>     
					            </optgroup>
					            <optgroup label="Fading Exits">
					              	<option value="fadeOut">fadeOut</option>
					              	<option value="fadeOutDown">fadeOutDown</option>
					              	<option value="fadeOutDownBig">fadeOutDownBig</option>
					              	<option value="fadeOutLeft">fadeOutLeft</option>
					              	<option value="fadeOutLeftBig">fadeOutLeftBig</option>
					              	<option value="fadeOutRight">fadeOutRight</option>
					              	<option value="fadeOutRightBig">fadeOutRightBig</option>
					              	<option value="fadeOutUp">fadeOutUp</option>
					              	<option value="fadeOutUpBig">fadeOutUpBig</option>
					            </optgroup>
					            <optgroup label="Zoom Exits">
					              	<option value="zoomOut">zoomOut</option>
					              	<option value="zoomOutDown">zoomOutDown</option>
					              	<option value="zoomOutLeft">zoomOutLeft</option>
					              	<option value="zoomOutRight">zoomOutRight</option>
					              	<option value="zoomOutUp">zoomOutUp</option>
					            </optgroup>
					            <optgroup label="Bouncing Exits">
					              	<option value="bounceOut">bounceOut</option>
					              	<option value="bounceOutDown">bounceOutDown</option>
					              	<option value="bounceOutLeft">bounceOutLeft</option>
					              	<option value="bounceOutRight">bounceOutRight</option>
					              	<option value="bounceOutUp">bounceOutUp</option>
					            </optgroup>
					            <optgroup label="Rotating Exits">
					              	<option value="rotateOut">rotateOut</option>
					              	<option value="rotateOutDownLeft">rotateOutDownLeft</option>
					              	<option value="rotateOutDownRight">rotateOutDownRight</option>
					              	<option value="rotateOutUpLeft">rotateOutUpLeft</option>
					              	<option value="rotateOutUpRight">rotateOutUpRight</option>
					            </optgroup>
					            <optgroup label="Others">
					            	<option value="hinge">hinge</option>
					            	<option value="rollOut">rollOut</option>
					            	<option value="lightSpeedOut">lightSpeedOut</option>
					            </optgroup>
				            </select>
						</td>
					</tr>
					<tr>
						<th>
							<label>Exit Animation Duration</label>
							<p class="description">Set the duration of preloader exit animation. recomended 1s</p>
						</th>
						<td>
							<div class="slider-con">
								<div id="exit-duration"></div>
								<div id="exit-duration-display"></div>
							</div>		
						</td>
					</tr>
					<tr>
						<th>
							<label>Exit Delay</label>
							<p class="description">Set the preloader delay before starting to exit.</p>
						</th>
						<td>
							<div class="slider-con">
								<div id="exit-delay"></div>
								<div id="exit-delay-display"></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>

		</div><!-- end .exit-options -->


		<div class="entrance-options">
			
			<h2>Page Entrance Effect</h2>
			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label>Entrance Animation</label>
							<p class="description">Select the page content entrance effect you want apply after the preloader exit.</p>
						</th>
						<td>
							<select id="page-entrance">
								<option value="none">-- none --</option>
					    		<optgroup label="Sliding Entrances">
					              	<option value="slideInUp">slideInUp</option>
					              	<option value="slideInDown">slideInDown</option>
					              	<option value="slideInLeft">slideInLeft</option>
					              	<option value="slideInRight">slideInRight</option>
					            </optgroup>
					            <optgroup label="Fading Entrances">
					              	<option value="fadeIn">fadeIn</option>
					              	<option value="fadeInDown">fadeInDown</option>
					              	<option value="fadeInDownBig">fadeInDownBig</option>
					              	<option value="fadeInLeft">fadeInLeft</option>
					              	<option value="fadeInLeftBig">fadeInLeftBig</option>
					              	<option value="fadeInRight">fadeInRight</option>
					              	<option value="fadeInRightBig">fadeInRightBig</option>
					              	<option value="fadeInUp">fadeInUp</option>
					              	<option value="fadeInUpBig">fadeInUpBig</option>
					            </optgroup>
					            <optgroup label="Zoom Entrances">
			              			<option value="zoomIn">zoomIn</option>
					              	<option value="zoomInDown">zoomInDown</option>
					              	<option value="zoomInLeft">zoomInLeft</option>
					              	<option value="zoomInRight">zoomInRight</option>
					              	<option value="zoomInUp">zoomInUp</option>
					            </optgroup>
					            <optgroup label="Bouncing Entrances">
					              	<option value="bounceIn">bounceIn</option>
					              	<option value="bounceInDown">bounceInDown</option>
					              	<option value="bounceInLeft">bounceInLeft</option>
					              	<option value="bounceInRight">bounceInRight</option>
					              	<option value="bounceInUp">bounceInUp</option>
					            </optgroup>
					            <optgroup label="Rotating Entrances">
					              	<option value="rotateIn">rotateIn</option>
					              	<option value="rotateInDownLeft">rotateInDownLeft</option>
					              	<option value="rotateInDownRight">rotateInDownRight</option>
					              	<option value="rotateInUpLeft">rotateInUpLeft</option>
					              	<option value="rotateInUpRight">rotateInUpRight</option>
					            </optgroup>
					            <optgroup label="Attention Seekers">
					              <option value="bounce">bounce</option>
					              <option value="flash">flash</option>
					              <option value="pulse">pulse</option>
					              <option value="rubberBand">rubberBand</option>
					              <option value="shake">shake</option>
					              <option value="swing">swing</option>
					              <option value="tada">tada</option>
					              <option value="wobble">wobble</option>
					              <option value="jello">jello</option>
					            </optgroup>
					            <optgroup label="Others">
					            	<option value="rollIn">rollIn</option>
					            	<option value="lightSpeedIn">lightSpeedIn</option>
					            </optgroup>
				            </select>
				            <p class="description">The animation duration and delay is automatically computed based on the preloader exit settings to make the content entrance effect synchronize with the preloader exit effect.</p>
						</td>
					</tr>
				</tbody>
			</table>

		</div><!-- end .exit-options -->


		<hr><button class="button-primary save-options">Save Settings</button>
		<div class="clear"></div>
	</div><!-- end .preloader-ultimate-settings -->
</div><!-- end #preloader-setting-modal -->