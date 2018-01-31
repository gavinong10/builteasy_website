<?php noo_get_layout( 'footer', 'widgetized' ); ?>
<?php
	$image_logo = noo_get_image_option( 'noo_bottom_bar_logo_image', '' );
	$noo_bottom_bar_content = noo_get_option( 'noo_bottom_bar_content', '' );
?>
<?php if ( $image_logo || $noo_bottom_bar_content ) : ?>
	<footer class="colophon site-info" role="contentinfo">
		<div class="container-full">
			<?php if ( $noo_bottom_bar_content != '' || $noo_bottom_bar_social ) : ?>
			<div class="footer-more">
				<div class="container-boxed max">
					<div class="row">
						<div class="col-md-6">
						<?php if ( $image_logo ) : ?>
							<?php
								echo '<img src="' . $image_logo . '" alt="' . get_bloginfo( 'name' ) . '">';
							?>
						<?php endif; ?>
						
						</div>
						<div class="col-md-1"><a href="#" class="go-to-top on"><i class="fa fa-angle-up"></i></a></div>
						<div class="col-md-5 text-right">
						<?php if ( $noo_bottom_bar_content != '' ) : ?>
							<div class="noo-bottom-bar-content">
								<?php echo $noo_bottom_bar_content; ?>
							</div>
						<?php endif; ?>
						</div>
						
					</div>
				</div>
			</div>
			<?php endif; ?>
		</div> <!-- /.container-boxed -->
	</footer> <!-- /.colophon.site-info -->
<?php endif; ?>
</div> <!-- /#top.site -->
<?php wp_footer(); ?>
<link href="<?php echo NOO_FRAMEWORK_URI . '/admin/assets/css/bootstrap-multiselect.css'; ?>" rel="stylesheet" type="text/css" />
<script src="<?php echo NOO_FRAMEWORK_URI . '/admin/assets/js/bootstrap-multiselect.js';?>" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(function () {
            jQuery('#lstFruits').multiselect({
                includeSelectAllOption: true
            });
			<!--checked -->
			 jQuery('.gsearchform :checkbox').click(function() {
			 //alert(jQuery(this).val());
			  var selected = jQuery("#lstFruits option:selected");
                var message = "";
                selected.each(function () {
                    message += jQuery(this).val() + ",";
                });
                jQuery('#sub_location').val(message);
		     });
		   <!--checked -->
        });
    </script>
</body>
</html>
