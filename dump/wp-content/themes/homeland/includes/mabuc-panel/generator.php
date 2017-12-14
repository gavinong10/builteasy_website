<?php
	function homeland_mytheme_admin() {
		global $themename, $shortname, $options, $version, $help, $author;
		$i=0;

		if ( isset($_REQUEST['saved']) ) : ?>
			<div id="message" class="updated fade">
				<p><strong><?php echo $themename . "&nbsp;"; _e( 'Settings Saved', 'codeex_theme_name' ); ?></strong></p>
			</div>
			<script type="text/javascript">
				(function($) {
					"use strict";
					setTimeout(function(){
				   	$(".fade").fadeOut("slow", function () {
				         $(".fade").remove();
				      });
				   }, 5000);
				})(jQuery);
			</script><?php
		endif;

		if ( isset($_REQUEST['reset']) ) : ?>
			<div id="message" class="updated fade">
				<p><strong><?php echo $themename . "&nbsp;"; _e( 'Settings Reset', 'codeex_theme_name' ); ?></strong></p>
			</div><?php
		endif;
		
		?>

		<div class="wrap mabuc_wrap">
			<h2><?php echo $themename; ?> <p class="version"><?php _e( 'version', 'codeex_theme_name' ); echo "&nbsp;" . $version; ?></p></h2>
			<div class="mabuc_opts">
				<p>
					<?php 
						printf ( __( 'Enter your Settings for %1$s. If you have any questions please refer to the user manual.', 'codeex_theme_name' ), $themename ); 
					?> 
				</p>	
				<form method="post">
					<?php 
						foreach ($options as $value) {
							$homeland_value_id = @$value['id'];
							$homeland_value_name = @$value['name'];
							$homeland_value_type = @$value['type'];
							$homeland_value_desc = @$value['desc'];
							$homeland_value_std = @$value['std'];
							$homeland_value_icon = @$value['icon'];

						switch ( $homeland_value_type ) {
							case "open":
							break;
							case "close":
					?>
			</div>
		</div>

		<?php 
			break;	

			//Headers
			case "headers":
				echo "<h3>". $homeland_value_name ."</h3>";
				if(!empty($homeland_value_desc)) :
					echo "<span class='header-desc'>". $homeland_value_desc ."</span>";
				endif;
			break;

			//Top Sections
			case "top_section":
				$i++;
				echo "<div class='mabuc_section'><h4>". $homeland_value_name ."</h4><div class='mabuc_options'>";
			break;

			//Sections
			case "section":
				$i++;
				?>
					<div class="mabuc_section">
						<div class="mabuc_title">
							<h3>
								<i class="<?php echo $homeland_value_icon; ?>"></i>
								<?php echo $homeland_value_name; ?> <span>&nbsp;</span>
							</h3>
							<div class="clearfix"></div>
						</div>
					<div class="mabuc_options">
				<?php 
			break;

			//Slide Range
			case 'slide_amount':
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
				else : 
					$homeland_value_option = $homeland_value_std; 
				endif;
				?>
					<div class="mabuc_input mabuc_text <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<div id="<?php echo $homeland_value_id; ?>_value" class="slider-range"></div>
						<input type="text" id="<?php echo $homeland_value_id; ?>" name="<?php echo $homeland_value_id; ?>" readonly class="slide-amount">
						<small><?php echo $homeland_value_desc; ?></small><div class="clearfix"></div>
						
						<script type="text/javascript">
							(function($) {
					    	"use strict";
								$(function() {
									$( "#<?php echo $homeland_value_id; ?>_value" ).slider({
								      range: "max",
								      min: 1,
								      max: 20,
								      value: <?php echo $homeland_value_option; ?>,
								      slide: function( event, ui ) {
								        $( "#<?php echo $homeland_value_id; ?>" ).val( ui.value );
								      }
								   });
								   $( "#<?php echo $homeland_value_id; ?>" ).val( $( "#<?php echo $homeland_value_id; ?>_value" ).slider( "value" ) );
								});
							})(jQuery);
						</script>

					</div>
				<?php
			break;


			//Slide Range
			case 'slide_amount_fonts':
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
				else : 
					$homeland_value_option = $homeland_value_std; 
				endif;
				?>
					<div class="mabuc_input mabuc_text <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<div id="<?php echo $homeland_value_id; ?>_value" class="slider-range"></div>
						<input type="text" id="<?php echo $homeland_value_id; ?>" name="<?php echo $homeland_value_id; ?>" readonly class="slide-amount">
						<small><?php echo $homeland_value_desc; ?></small><div class="clearfix"></div>
						
						<script type="text/javascript">
							(function($) {
					    	"use strict";
								$(function() {
									$( "#<?php echo $homeland_value_id; ?>_value" ).slider({
								      range: "max",
								      min: 12,
								      max: 150,
								      value: <?php echo $homeland_value_option; ?>,
								      slide: function( event, ui ) {
								        $( "#<?php echo $homeland_value_id; ?>" ).val( ui.value );
								      }
								   });
								   $( "#<?php echo $homeland_value_id; ?>" ).val( $( "#<?php echo $homeland_value_id; ?>_value" ).slider( "value" ) );
								});
							})(jQuery);
						</script>

					</div>
				<?php
			break;


			//TextFields
			case 'text':
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
				else : 
					$homeland_value_option = $homeland_value_std; 
				endif; 
				?>
					<div class="mabuc_input mabuc_text <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<input name="<?php echo $homeland_value_id; ?>" id="<?php echo $homeland_value_id; ?>" type="<?php echo $homeland_value_type; ?>" value="<?php echo $homeland_value_option; ?>" />
						<small><?php echo $homeland_value_desc; ?></small><div class="clearfix"></div>
					</div>
				<?php
			break;

			//Textarea
			case 'textarea':
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
				else : 
					$homeland_value_option = $homeland_value_std; 
				endif; 
				?>
					<div class="mabuc_input mabuc_textarea <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<textarea name="<?php echo $homeland_value_id; ?>" type="<?php echo $homeland_value_type; ?>" id="<?php echo $homeland_value_id; ?>" cols="" rows=""><?php echo $homeland_value_option; ?></textarea>
						<small><?php echo $homeland_value_desc; ?></small><div class="clearfix"></div>
					</div>
				<?php
			break;

			//Uploads
			case 'upload':
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
				else : 
					$homeland_value_option = $homeland_value_std; 
				endif; 
				?>
					<div class="mabuc_input mabuc_text <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<input name="<?php echo $homeland_value_id; ?>" id="<?php echo $homeland_value_id; ?>" type="text" value="<?php echo $homeland_value_option; ?>"  style="width:422px;" />
						<input id="upload_image_button_<?php echo $homeland_value_id; ?>" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" />
						<small><?php echo $homeland_value_desc; ?></small><div class="clearfix"></div>
					</div>		
				<?php
			break;

			//Image Previews
			case 'img_preview':
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
				else : 
					$homeland_value_option = $homeland_value_std; 
				endif; 
				?>
					<div class="mabuc_input mabuc_text <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<input name="<?php echo $homeland_value_id; ?>" id="<?php echo $homeland_value_id; ?>" type="text" value="<?php echo $homeland_value_option; ?>"  style="width:422px;" />			
						<input id="upload_image_button_<?php echo $homeland_value_id; ?>" type="button" value="<?php _e( 'Upload', 'codeex_theme_name' ); ?>" class="button-secondary" />
						<small><?php echo $homeland_value_desc; ?></small>
						<div class="ipreview">
							<?php
								if ( get_option( $homeland_value_id ) != "") : ?>
									<img src="<?php echo stripslashes( get_option( $homeland_value_id) ); ?>" style="background:#EEE;"><?php 
								endif;
							?>
						</div>
						<div class="clearfix"></div>
					</div>		
				<?php
			break;
			
			//SelectBox
			case 'select':
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
				else : 
					$homeland_value_option = $homeland_value_std; 
				endif; 
				?>
					<div class="mabuc_input mabuc_select <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<select name="<?php echo $homeland_value_id; ?>" id="<?php echo $homeland_value_id; ?>">
							<?php 
								foreach ($value['options'] as $option) : 
									//$option_rep = str_replace(" ", "-", $option);
									//option value = echo strtolower( $option_rep );
									?>
									<option <?php if (get_option( $homeland_value_id ) == $option) : echo 'selected="selected"'; endif; ?>><?php echo $option; ?></option><?php 
								endforeach; 
							?>
						</select>
						<small><?php echo $homeland_value_desc; ?></small><div class="clearfix"></div>
					</div>
				<?php
			break;

			//Checkboxes
			case "checkbox":
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
					$homeland_checked = "checked=\"checked\""; 
				else : 
					$homeland_value_option = $homeland_value_std; 
					$homeland_checked = ""; 
				endif; 
				?>
					<div class="mabuc_input mabuc_checkbox <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<input type="checkbox" name="<?php echo $homeland_value_id; ?>" id="<?php echo $homeland_value_id; ?>" value="true" <?php echo $homeland_checked; ?> />
						<small><?php echo $homeland_value_desc; ?></small><div class="clearfix"></div>
					</div>
				<?php 
			break;
			
			//Color Selections
			case 'color':
				if ( get_option( $homeland_value_id ) != "") :
					$homeland_value_option = stripslashes( get_option( $homeland_value_id ) ); 
				else : 
					$homeland_value_option = $homeland_value_std; 
				endif; 
				?>
					<div class="mabuc_input mabuc_text <?php echo $homeland_value_id; ?>">
						<label for="<?php echo $homeland_value_id; ?>"><?php echo $homeland_value_name; ?></label>
						<input name="<?php echo $homeland_value_id; ?>" type="text" id="<?php echo $homeland_value_id; ?>" value="<?php echo $homeland_value_option; ?>">
						<small><?php echo $homeland_value_desc; ?></small><div class="clearfix"></div>
					</div>
				<?php
			break;

		}
	}
?>
			<input type="hidden" name="action" value="save" />
			
			<div class="clear">
				<div class="btn-actions">
					<span><input name="save" type="submit" value="<?php _e( 'Save Changes', 'codeex_theme_name' ); ?>" class="button-primary" /></span>	
				</div>
				<div class="btn-actions-right">
					<span>
						<input name="support" type="button" value="<?php _e( 'Ask for Help?', 'codeex_theme_name' ); ?>" class="button-primary" onClick="window.open('<?php echo $help; ?>')" />
					</span>	
					<span><input name="themes" type="button" value="<?php _e( 'More Themes', 'codeex_theme_name' ); ?>" class="button-primary" onClick="window.open('http://themeforest.net/user/<?php echo $author; ?>/portfolio')" /></span>	
				</div>
			</div>

		</form>
	</div>

	</div> 
<?php
}