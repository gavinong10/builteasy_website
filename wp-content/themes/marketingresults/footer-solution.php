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
<footer class="dark-bg-light py-5">
  <div class="container">
    <div class="row valign">
      <div class="col-sm-4 xs-text-center" data-valign-overlay="middle">
        <p><?php the_field("copy_right","options");?></p>
      </div>
      <div class="col-sm-4 xs-py-2 text-center" data-valign-overlay="middle"> <img class="img-center" src="<?php echo get_template_directory_uri();?>/images/logo-bottom.png" alt=""> </div>
      <div class="col-sm-4 text-right xs-text-center" data-valign-overlay="middle">
        <?php wp_nav_menu( array( 'theme_location' => 'footer_menu',"menu_class"=>"list-inline m-0 footer-menu") ); ?>          
      </div>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
<script>
jQuery(document).ready(function(){
	jQuery("#navbar a.dropdown-toggle").append('&nbsp;<span class="caret"></span>');
});
</script>
</body>
</html>
