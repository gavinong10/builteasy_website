<?php if(is_front_page()) { ?>
<section class="fullscreen py-0 text-white text-center">
	<div class="banner grey-overlay" style="background: url('<?php the_field('banner_image','options'); ?>') no-repeat 0 0; background-size: cover;" data-overlay="5">
		<div class="ver-center">
			<div class="container">
				<div class="row">
					<div class="col-md-offset-1 col-md-10">
						<div class="banner-text">
							<h1><b><?php the_field('banner_title','options'); ?></b></h1>
							<?php the_field('banner_description','options'); ?>
							<a target="_blank" type="button" class="btn btn-default" href="<?php the_field('banner_button_url','options'); ?>"><b><?php the_field('banner_button_text','options'); ?></b></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php } 
    else if(get_field("solution_banner_title"))
    {
        ?>
        <section class="inner-intro grey-overlay"  data-overlay="5" data-jarallax='{"speed": 0.6}' style="background-image: url(<?php the_post_thumbnail_url();?>);">
          <div class="container">
            <div class="row  intro-title">
              <div class="col-md-8 col-sm-12">
                <?php the_field("solution_banner_title"); ?>
                <?php while(have_rows("banner_buttons"))
                        {
                            the_row();
							$link=get_sub_field('button_url'); 
                            ?>
                            <a href="<?php echo $link; ?>" target="<?php the_sub_field("target"); ?>" type="button" class="btn btn-default outline"><b><?php the_sub_field("button_title"); ?></b></a>
                            <?php   
                        }
                 ?>                
              </div>
            </div>
          </div>
        </section>
        <?php	
    }
	else if(has_post_thumbnail()){
		?>
		<section class="inner-intro grey-overlay"  data-overlay="6" data-jarallax='{"speed": 0.6}' style="background-image: url('<?php echo the_post_thumbnail_url(); ?>');">
		  <div class="container">
			<div class="row  intro-title text-center">            
			<?php if(get_field('banner_title') || get_field('banner_subtitle')) { ?>
			  <div class="col-sm-12">
				<?php if(get_field('banner_title')) { ?>
					<h2 class="text-white"><?php the_field('banner_title'); ?></h2>
				<?php } ?>
				<?php if(get_field('banner_title')) { ?>
					<p class="text-white"><?php the_field('banner_subtitle'); ?></p>
				<?php } ?>
			  </div>
			<?php } ?>
			<?php if(function_exists('bcn_display_list')) { ?>
			  <div class="col-sm-12 ">
				<ul class="page-breadcrumb">
					<?php bcn_display_list(); ?>
				</ul>
			  </div>
			<?php } ?>
			</div>
		  </div>
		</section>
		<?php
	}
?>