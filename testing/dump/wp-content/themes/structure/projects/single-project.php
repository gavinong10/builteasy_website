<?php
/**
 * The Template for displaying all single projects.
 *
 * Override this template by copying it to yourtheme/projects/single-project.php
 *
 * @author        WooThemes
 * @package    Projects/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

get_header( 'projects' ); ?>
	<div class="content-wrapper">
		<div data-stellar-background-ratio="0.5" class="entry-header has-bg">
			<div class="container">
				<h1 itemprop="name" class="entry-title project_title entry-title"><?php the_title(); ?></h1>
				<?php if ( function_exists( 'tm_bread_crumb' ) && ! is_front_page() ) { ?>
					<div class="breadcrumb">
						<div class="container">
							<?php echo tm_bread_crumb(); ?>
						</div>
					</div><!-- .breadcrumb -->
				<?php } ?>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<?php
				/**
				 * projects_before_main_content hook
				 *
				 * @hooked projects_output_content_wrapper - 10 (outputs opening divs for the content)
				 */
				do_action( 'projects_before_main_content' );
				?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php if ( class_exists( 'WPBakeryVisualComposer' ) ) {

						global $post;
						$use_vc = get_post_meta( $post->ID, '_use_vc', true );

						// Check if Project post type is available to Visual Composer
						$pt_array = ( $pt_array = vc_editor_post_types() ) ? ( $pt_array ) : vc_default_editor_post_types(); // post type array
						if ( ! in_array( 'project', $pt_array ) ) {
							projects_get_template_part( 'content', 'single-project' );
						} else {
							if ( $use_vc == 'no' ) {
								projects_get_template_part( 'content', 'single-project' );
							} else {
								the_content();
							}
						}
					}
					?>

				<?php endwhile; // end of the loop. ?>

				<?php
				/**
				 * projects_after_main_content hook
				 *
				 * @hooked projects_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'projects_after_main_content' );
				?>

			</div>
		</div>
	</div>
<?php get_footer( 'projects' );