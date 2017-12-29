<?php
/* Template Name: About */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'about' );

				// If comments are open or we have at least one comment, load up the comment template.
			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
<script type="text/javascript">
jQuery(function() {
    jQuery('.nav > a').bind('click', function(event) {
        var $anchor = jQuery(this);
		var hg = jQuery('header').height();
		var scroll_h = jQuery($anchor.attr('href')).offset().top - (hg+100);
        jQuery('html, body').stop().animate({
            scrollTop: scroll_h
        }, 1200);
        event.preventDefault();
    });
});
</script>
<script type="text/javascript">
 jQuery(window).scroll(function () { 
  var scrollTop = jQuery(window).scrollTop();
      var elementOffset = jQuery('.estate-header--wrapper').offset().top - 80;
      var currentElementOffset = (elementOffset - scrollTop );
if (scrollTop >= elementOffset) {
            jQuery('.estate-header--wrapper').addClass("fixed");
        } 
        else {
            jQuery('.estate-header--wrapper').removeClass("fixed");
        }
      if(jQuery(window).scrollTop() == 0) {
		jQuery('.estate-header--wrapper').removeClass("fixed");
	}
});

</script>
<?php
get_footer();
?>
