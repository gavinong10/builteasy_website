<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package marketingresults
 */

?>
<footer class=" footer py-7 text-center">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<?php the_field('footer_content','options'); ?>

				<div class="footer-logo">
					<img class="img-center" src="<?php echo get_template_directory_uri();?>/images/logo-bottom.png" alt="">
				</div>
			</div>
		</div>
	</div>
</footer>

<!--footer class="dark-bg-light py-5">
	<div class="container">
		<div class="row valign">
			<div class="col-sm-4 xs-text-center" data-valign-overlay="middle">
				<p><?php //the_field('copyright_text','options'); ?></p>
			</div>
		<div class="col-sm-4 xs-py-2 text-center" data-valign-overlay="middle">
			<img class="img-center" src="<?php //echo get_template_directory_uri();?>/images/logo-bottom.png" alt="">
		</div>
		<div class="col-sm-4 text-right xs-text-center" data-valign-overlay="middle">
		<?php /*$defaults = array(
						'menu'  =>'Footer',
						'container' => 'ul',
						'menu_class'=>'list-inline m-0 footer-menu',						
					);*/
					?>
			<?php //wp_nav_menu( $defaults ); ?>
		</div>
		</div>
	</div>
</footer-->
<?php wp_footer(); ?>
<script>
jQuery(document).ready(function(){
	jQuery("#navbar a.dropdown-toggle").append('&nbsp;<span class="caret"></span>');
});
jQuery(document).ready(function () {

	// Open in new window
//	jQuery(".divLink").click(function () {
//		window.open(jQuery(this).find("a:first").attr("href"));
//		return false;
//	});
		
	// Or use this to Open link in same window (similar to target=_blank)
	jQuery(".divLink").click(function(){
		window.location = jQuery(this).find("a:first").attr("href");
		return false;
	});

	// Show URL on Mouse Hover
	jQuery(".divLink").hover(function () {
		window.status = jQuery(this).find("a:first").attr("href");
	}, function () {
		window.status = "";
	});

});
</script>
</body>
</html>
