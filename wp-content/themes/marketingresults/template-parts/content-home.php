<div class="main-content">
	<section class="grey-bg">
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
				<?php
if (have_rows('builds')) {
    while (have_rows('builds')) {the_row();?>
					<div class="col-sm-6 col-md-6 box text-center">
						<div class="icon-box-1 white-bg pxy-6 box-shadow divLink" style="cursor: pointer;">
						<a href="<?php the_sub_field('read_more_link');?>"></a>
							<div class="icon "><a href="#"><i class="<?php the_sub_field('icon');?>" aria-hidden="true"></i></a></div>
								<div class="icon-dec">
								<h4 class="text-black mb-4 text-uppercase"><?php the_sub_field('title');?></h4>
								<?php the_sub_field('description');?>
								<a class="btn btn-default btn-sm radius">Read More <i class="fa fa-plus ml-1" aria-hidden="true"></i>
								</a>
							</div>
						</div>
					</div>
					<?php }?>
				<?php }?>
				</div>
			</div>
		</div>
	</section>
	<section class="p-0 marketing">
		<div class="container-fluid p-0">
			<div class="row row-eq-height no-gutter valign">
				<div class="col-md-6 col-sm-12 promo-bg" style="background: url('<?php echo get_field('marketing_image'); ?>') no-repeat 0 0; background-size: cover;">
					<div class="clipper hidden-xs"></div>
				</div>
				<!--div class="col-md-6 col-sm-12">
					<img class="img-responsive" src="<?php echo get_field('marketing_image'); ?>" alt="">
				</div-->
				<div class="col-md-6 col-sm-12 dark-bg"  data-valign-overlay="middle">
					<div class=" pxy-7">
						<h5 class=""><?php echo get_field('marketing_sub_title'); ?></h5>
						<h2 class="text-white"><?php echo get_field('marketing_title'); ?></h2>
						<?php echo get_field('marketing_content'); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-md-7 col-sm-12 pull-right">
					<div class="videoimg">
						<img class="img-responsive" src="<?php echo get_field('video_image'); ?>" alt="">
						<!-- <a class="popup-youtube" href="<?php echo get_field('video_link'); ?>"> <i class="fa fa-play"></i> </a> -->
					</div>
				</div>
				<div class="col-md-5 col-sm-12">
					<?php echo get_field('video_content'); ?>
				</div>
			</div>
		</div>
	</section>
	<section class="grey-bg">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<img class="img-responsive" src="<?php the_field('service_image');?>" alt="">
				</div>
				<div class="col-sm-6 ">
					<div class="mortgage-con xs-mb-4 xs-pb-2">
						<h5><?php the_field('service_sub_title');?></h5>
						<h2 class="text-black pb-2"><?php the_field('service_title');?></h2>
						<?php if (have_rows('service_lists')) {?>
						<ul class="list-unstyled icon-list">
							 <?php while (have_rows('service_lists')) {the_row();?>
								<li class="mb-1">
									<!--span class="ico-img"><img class="img-center pr-3" src="<?php the_sub_field('icon_image');?>" alt=""></span-->
									<span class="ico-text"><i class="fa fa-chevron-circle-right mr-1" aria-hidden="true"></i><?php the_sub_field('title');?></span>
								</li>
							 <?php }?>
						</ul>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="dark-bg">
		<div class="container">
			<div class="row valign">
				<div class="col-sm-10 col-xs-12">
					<h3 class="text-white"><?php the_field('link_to_title');?></h3>
				</div>
				<div class="col-sm-2 col-xs-12" data-valign-overlay="middle">
					<a href="<?php the_field('link_to_link');?>" class="btn btn-default">Read More</a>
				</div>
				</div>
			</div>
	</section>
	<section class="text-center">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 text-center">
					<h5><?php the_field('digital_sub_title');?></h5>
					<h2 class="text-black"><?php the_field('digital_title');?></h2>
				</div>
			</div>
			<div class="row" >
			 <?php
$i = 0;
?>
			 <?php while (have_rows('digital_services')) {the_row();?>
				<div class="col-sm-4">
					<div class="icon-box center">
						<div class="icon">
						<span class="hi-icon"><img class="img-center" src="<?php the_sub_field('icon');?>" alt=""></span>
						</div>
						<div class="icon-dec">
							<h4 class="text-black"><?php the_sub_field('title');?></h4>
							<p class="mt-1 mb-3"><?php the_sub_field('description');?></p>
						</div>
					</div>
				</div>
			 <?php
$i++;
    if ($i == 3) {
        ?>
						</div>
						<div class="row" >
						<?php
$i = 0;
    }
}?>
			</div>
		</div>
	</section>
	<!--section class="darkblue parallax" style="background: url('<?php echo get_field('test_background'); ?>') no-repeat 0 0; background-size: cover;" data-overlay="8">
		<div class="container">
			<div class="row text-center">
				<div class="col-sm-12">
					<h5><?php the_field('test_sub_title');?></h5>
					<h2 class="text-white pb-4"><?php the_field('test_title');?></h2>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="owl-carousel" data-nav-dots="true" data-items="3" data-md-items="3" data-sm-items="2" data-xs-items="1" data-space="30">
						<?php while (have_rows('testimonial_lists')) {the_row();?>
						<div class="item">
							<div class="testimonials-block text-center">
								<a class="popup-youtube" href="<?php the_sub_field('video_link');?>">
								<img src="<?php the_sub_field('image');?>" class="img-responsive  img-center"></a>
								<h3><?php the_sub_field('title');?></h3>
							</div>
						</div>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</section-->
	<section class="home-promotion pt-0 pb-0">
		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->
			<ol class="carousel-indicators">
				<?php $i = 0;
						$flag = true;while (have_rows('testimonial_lists')) {the_row();?>
				<li data-target="#carousel-example-generic" data-slide-to="<?php echo $i++; ?>
				" <?php if ($flag) {echo 'class="active"'; $flag=false;}?>
					>
				</li>
				<?php }?>
			</ol>
			<!-- Wrapper for slides -->
			<div class="carousel-inner" role="listbox">
				<?php $flag = true;?>
				<?php while (have_rows('testimonial_lists')) {the_row();?>
				<div class="item <?php if ($flag) {echo 'active';
		$flag = false;}?>
			">
					<div class="row row-eq-height no-gutter">
						<div class="col-sm-6 promo-bg" style="background: url('<?php the_sub_field('image');?>
			') no-repeat 0 0; background-size: cover;">
							<div class="clipper hidden-xs">

							</div>
						</div>
						<div class="col-sm-6">
							<div class="promo-wrapper">
								<div class="promo-slider">
									<div class="promo">
										<h2>
											<?php the_sub_field('title');?>
										</h2>
										<p class="promo-text">
											<?php the_sub_field('content');?>
										</p>
										<a href="<?php the_sub_field('download_link'); ?>
			" class="btn btn-default btn-danger" tabindex="0">
											Download now</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php }?>
				<!--div class="item">
							<div class="col-sm-6 promo-bg" style="background: url('images/img2.jpg') no-repeat 0 0; background-size: cover;">
								<div class="clipper hidden-xs">

			</div>
						</div>
							<div class="col-sm-6">
									<div class="promo-wrapper">
										<div class="promo-slider">
											<div class="promo">
												<h2>
				Download your FREE Home Building Guide</h2>
											<p class="promo-text">
				Thinking about building a new home? Check out our new home building guide. From your initial plans to the construction process, this free guide covers everything you need to know.</p>
											<a href="#" class="btn btn-default btn-danger" tabindex="0">
				Download now</a>
									</div>
								</div>
							</div>
						</div>
					</div-->
			</div>
			<!-- Controls -->
			 <?php
				if ($testimonial_rows > 1):
			?>
			<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
				<span class="glyphicon glyphicon-chevron-left" aria-hidden="true">

				</span>
				<span class="sr-only">
					Previous</span>
			</a>
			<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
				<span class="glyphicon glyphicon-chevron-right" aria-hidden="true">

				</span>
				<span class="sr-only">
					Next</span>
			</a>
			<?php endif ?>
		</div>
	</section>


<section class="white-bg" id="testimonial">

  <div class="container">



	<div class="row">

		<div class="col-md-offset-2 col-md-8">

        <div class="testimonial">

            <div class="owl-carousel" data-nav-dots="true" data-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1" >

            <?php

if (have_rows('testimonial', 123)):
	$testimonial_rows = 0;
    while (have_rows('testimonial', 123)): the_row();
		$testimonial_rows++;
        ?>

						            	<div class="item">

						                	<div class="testimonial-description-block text-center">

						                    	<i class="fa fa-comments" aria-hidden="true"></i>

						                    	<h4 class="text-black"><?php the_sub_field('testimonial_title');?></h4>

						                        <div><?php the_sub_field('testimonial_sub_title');?></div>

												<div><?php the_sub_field('testimonial_sub_title2');?></div>

						                    </div>

						                </div>

						           <?php

    endwhile;

endif;

?>

            </div>

        </div>

        </div>

	</div>

</div>

</section>


	<!--section>
		<div class="container">
			<div class="row row-eq-height">
				<div class="col-sm-4 text-right pr-5 xs-text-center xs-mb-4">
					<img class="img-responsive img-center" src="<?php the_field('ess_image');?>" alt="">
				</div>
				<div class="col-sm-8 text-center">
					<div class="containt">
						<div class="containt-text">
							<h4 class="text-uppercase text-black"><?php the_field('ess_sub_title');?></h4>
							<h2 class="text-uppercase text-black"><?php the_field('ess_title');?></h2>

							<?php the_field('ess_content');?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section-->
	<section class="text-black newsleeter" >
		<div class="container">
			<div class="row">
			<div class="col-sm-6">
				<?php the_field('news_content');?>
			</div>

			<div class="col-sm-6">
			<?php
			echo do_shortcode('[mc4wp_form id="1041"]');
			?>
			</div>
			</div>
		</div>
	</section>

	<section class="dark-bg">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h3 class="text-white text-center mb-7"><?php the_field('footer_title', 'options');?></h3>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4 border-r">
        <div class="contact-info text-center"> <span class="icon-round"> <i class="fa fa-phone" onclick="window.location.href='tel:<?php the_field('call_us_number', 'options');?>';"></i> </span>
          <h3><?php the_field('footer_call_title', 'options');?><br>
            <a href="tel:<?php echo str_replace(' ', '', get_field('
call_us_number', 'options')); ?>"><?php the_field('call_us_number', 'options');?></a></h3>
        </div>
      </div>
      <div class="col-sm-4 border-r">
        <div class="contact-info text-center"> <span class="icon-round"> <i class="fa fa-home"></i> </span>
          <?php the_field('footer_center_content', 'options');?>
          <a target="_blank" href="<?php the_field('search_new_home', 'options');?>" class="btn btn-white btn-danger mt-1 f-w-bold">Search New Homes</a> </div>
      </div>
      <div class="col-sm-4 border">
        <div class="contact-info text-center"> <span class="icon-round"> <i class="fa fa-group"></i> </span>
          <h3>FOLLOW US</h3>
          <ul class="list-inline social">
            <li><a target="_blank" href="<?php the_field('facebook', 'options');?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
            <li><a target="_blank" href="<?php the_field('linked_in', 'options');?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
            <li><a target="_blank" href="<?php the_field('instagram', 'options');?>"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
</div>