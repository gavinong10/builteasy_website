<div class="white-bg " >

  <div class="estate-header--wrapper">

    <header class="estate-header py-3" ng-style="{top: $root.REALTY_SITE ? '112px' : 'default'}" style="">

    <div class="estate-anchor-nav container">

      <div class="container">

        <div class="row">

          <div class="col-sm-4">

            <h5 class="landlord-logo">Working with Built Easy</h5>

          </div>

          <div class="col-sm-8">

            <nav class="nav"><a href="#information">About Us</a><a href="#services"> Our Services</a><a href="#ourteam">Our Team</a><a href="#tips-advice"> Tips &amp; Advice </a><a href="#download">Downloads</a><a href="#testimonial">Testimonials</a></nav>

          </div>

        </div>

        </div>

    	</div>

        </header>

  </div>

</div>

<div class="main-content">



<!-- information -->

<div class="information" id="information">

	<section class="white-bg" >

    	<div class="container">

        	   <div class="row mb-5">

            <div class="col-sm-12">

             <div class="text-center">

          		<h2 class="text-black">About Us</h2>

                </div>

            </div>

        </div>

        	<div class="row">

      			<div class="col-sm-5">

                	<img class="img-responsive" src="<?php the_field('white_image')?>" alt="">

                </div>

                <div class="col-sm-7 xs-mt-3">

                	<div class="information-info">

                    	<h4 class="text-black"><?php the_field('white_title')?></h4>

                        <?php the_field('white_sub_title')?>

                     </div>

                </div>

            </div>

        </div>

    </section>

    <section class="grey-bg" >

    	<div class="container">

        	<div class="row">

            <div class="col-sm-5  col-sm-push-7">

                	<img class="img-responsive" src="<?php the_field('grey_image')?>" alt="">

                </div>

                <div class="col-sm-7 col-sm-pull-5 xs-mt-3">

                	<div class="information-info">

                    	<h4 class="text-black"><?php the_field('grey_title')?></h4>

                        <?php the_field('grey_sub_title')?>

                     </div>

                </div>

                

            </div>

        </div>

    </section>

</div>



<!-- OUR SERVICES -->



	<section class="white-bg services" id="services">

    	<div class="container">

             <div class="row mb-5">

                <div class="col-sm-12 col-md-offset-2 col-md-8">

                     <div class="text-center">

                  		<h2 class="text-black"><?php the_field('title')?></h2>

                        <?php the_field('content')?>

                     </div>

                </div>

             </div>

             <div class="row">

                  <?php 

                   if( have_rows('our_services') ):

                   while( have_rows('our_services') ) : the_row();

                  ?>

                 	<div class="col-md-4 col-sm-6">

                        <div class="icon-box center">

                            <div class="icon"><span class="hi-icon"><i class="<?php the_sub_field('icon')?>" aria-hidden="true"></i></span></div>

                            <div class="icon-dec">

                              <h4 class="text-black"><?php the_sub_field('icon_title')?></h4>

                              <div class="mt-1 mb-3"><?php the_sub_field('icon_sub_title')?></div>

                            </div>

                        </div>

                    </div>

                    <?php

                        endwhile;

                      endif;

                    ?>

            </div>

      </div>

 </section>

<!-- OUR SERVICES -->

<!-- New Section -->

<section class="dark-bg"> 

  <div class="container">

   <div class="row valign">

    <div class="col-sm-10 col-xs-12">

     <h3 class="text-white"><?php if(get_field("link_title")) echo get_field("link_title");?></h3>

    </div>

    <div class="col-sm-2 col-xs-12" data-valign-overlay="middle">

     <a href="<?php if(get_field("link_url")) echo get_field("link_url");?>" class="btn btn-default">Read More</a>

    </div>

    </div>

   </div>

 </section>

<!-- New Section -->

<!--OUR TEAM-->

<section class="grey-bg" id="ourteam">

  <div class="container">

    <div class="row mb-5">

      <div class="col-sm-12 col-md-offset-2 col-md-8">

        <div class="text-center">

          <h2 class="text-black">OUR TEAM</h2>

          <?php the_field('our_page_description'); ?>

        </div>

      </div>

    </div>

    <div class="row">

      <div class="our-time">

	  <?php while(have_rows('teams')) { the_row();?>

			<div class="col-sm-4">

			  <div class="icon-box left">

				<div class="icon"><span class="hi-icon"><img class="img-center" src="<?php the_sub_field('image'); ?>" alt=""></span></div>

				<div class="icon-dec">

				  <h4 class="text-black"> <?php the_sub_field('name'); ?></h4>

				  <span class="ng-binding"><?php the_sub_field('position'); ?></span>

				  <p class="mt-1 mb-3"><?php the_sub_field('description'); ?> </p>

				  <a href="tel:<?php the_sub_field('telephone'); ?>">

				  <i class="fa fa-phone teamMembersForm--icon"></i><?php the_sub_field('telephone'); ?>

				  </a> </div>

			  </div>

        </div>

	  <?php } ?>

		

      </div>

    </div>

  </div>

</section>

<!--OUR TEAM--> 

<!--TIPS & ADVICE-->

<section class="white-bg" id="tips-advice">

  <div class="container">

    <div class="row">

      <div class="col-sm-12">

        <div class="text-center">

          <h2 class="text-black"><?php the_field('tips_title'); ?></h2>

          <p><?php the_field('tips_subtitle'); ?></p>

        </div>

      </div>

    </div>

    <div class="row">

      <div class="advice">

        <?php while(have_rows('tips')) { the_row();?>

			<div class="col-md-3 col-sm-6 sm-mb-5">

			  <div class="feature-box">

				<div class="feature-box-img" style="background-image:url('<?php the_sub_field('image'); ?>')">

				  <div class="feature-box-content text-center">

					<h4><?php the_sub_field('title'); ?></h4>

					<p><?php the_sub_field('sub_title'); ?></p>

					<?php if(get_sub_field('link_1')) { ?>

            <a href="<?php the_sub_field('link_1'); ?>" class="btn btn-sm" >Learn More</a>

					<?php } ?>

          <?php if(get_sub_field('link_2')) { ?>
            
            <a href="<?php the_sub_field('link_2'); ?>" class="btn btn-sm" >Learn More</a>

					<?php } ?>

				  </div>

				</div>

			  </div>

			</div>

		<?php } ?>

      </div>

    </div>

  </div>

</section>

<!--TIPS & ADVICE-->



<!--DOWNLOADS & RESOURCES-->

<section class="grey-bg" id="download">

  <div class="container">

    <div class="row mb-5">

      <div class="col-sm-12 col-md-offset-2 col-md-8">

        <div class="text-center">

          <h2 class="text-black"><?php the_field('resources_title'); ?></h2>

          <p><?php the_field('resources_sub_title'); ?></p>

        </div>

      </div>

    </div>

    <div class="row">

    	<div class="downloads">

        <?php 

        if( have_rows('resources') ):

            while( have_rows('resources') ) : the_row();

            if(get_sub_field('download_class')=='feature-box active')

            {

        ?>

        <div class="col-md-3 col-sm-6 sm-mb-5">

          <div class="<?php the_sub_field('download_class');?>">

            <div class="feature-box-img">

              <div class="feature-box-content text-center">

              <i class="fa fa-download" aria-hidden="true"></i>

                <h4><?php the_sub_field('download_title'); ?></h4>

                <?php the_sub_field('download_sub_title'); ?>

              </div>

            </div>

          </div>

        </div>

        <?php

        }

        else

        {

        ?>

        <div class="col-md-3 col-sm-6 sm-mb-5">

          <div class="feature-box">

            <div class="feature-box-img active">

              <div class="feature-box-content text-center">

                <h4><?php the_sub_field('download_title'); ?></h4>

                <?php the_sub_field('download_sub_title'); ?>

                <button class=" btn btn-sm" ><?php the_sub_field('download_button'); ?></button>

              </div>

            </div>

          </div>

        </div>

        

        <?php

        }

            endwhile;

          endif;

        ?>

    </div>

    </div>

    

</div>

</section>

<!--DOWNLOADS & RESOURCES-->



<!--testimonial-->



<section class="white-bg" id="testimonial">

  <div class="container">

    

	<div class="row">

		<div class="col-md-offset-2 col-md-8">

        <div class="testimonial">

            <div class="owl-carousel" data-nav-dots="true" data-items="1" data-md-items="1" data-sm-items="1" data-xs-items="1" >

            <?php 

            if( have_rows('testimonial') ):

                while( have_rows('testimonial') ) : the_row();

            ?>

            	<div class="item">

                	<div class="testimonial-description-block text-center">

                    	<i class="fa fa-comments" aria-hidden="true"></i>

                    	<h4 class="text-black"><?php the_sub_field('testimonial_title'); ?></h4>

                        <div><?php the_sub_field('testimonial_sub_title'); ?></div>

						<div><?php the_sub_field('testimonial_sub_title2'); ?></div>

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
					<img class="img-responsive img-center" src="<?php the_field('ess_image'); ?>" alt="">
				</div>
				<div class="col-sm-8 text-center">
					<div class="containt">
						<div class="containt-text">
							<h4 class="text-uppercase text-black"><?php the_field('ess_sub_title'); ?></h4>
							<h2 class="text-uppercase text-black"><?php the_field('ess_title'); ?></h2>
							
							<?php the_field('ess_content'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section-->
<!--testimonial-->

<?php /*

<!--FRIENDLY ADVICE COMES STANDARD-->

<section class="grey-bg" id="friendly-advice" >

  <div class="container">

    <div class="row mb-5">

      <div class="col-sm-12 col-md-offset-2 col-md-8">

        <div class="text-center">

          <h2 class="text-black"><?php the_field('friendly_advice_comes_standard_title')?></h2>

          <?php the_field('friendly_advice_comes_standard_sub_title')?>

        </div>

      </div>

    </div>

	

   <div class="row">

      <div class="advice">

      <?php 

            if( have_rows('friendly_advice_comes_standard') ):

                while( have_rows('friendly_advice_comes_standard') ) : the_row();

            ?>

        <div class="col-md-3 col-sm-6 sm-mb-5">

          <div class="feature-box">

            <div class="feature-box-img" style="background-image:url(<?php the_sub_field('advice_image'); ?>)">

              <div class="feature-box-content text-center">

                <h4><?php the_sub_field('advice_title'); ?></h4>

                <?php the_sub_field('advice_sub_title'); ?>

                <button class="btn btn-sm" ><?php the_sub_field('advice_button');?></button>

              </div>

            </div>

          </div>

        </div>

        <?php

                endwhile;

              endif;

            ?>

      </div>

    </div>

</div>

</section>

    */ ?>

<!--FRIENDLY ADVICE COMES STANDARD-->



<!--Enquire-->



<section class="white-bg" id="enquire">

  <div class="container">

    <div class="row mb-5">

      <div class="col-sm-12 col-md-offset-2 col-md-8">

        <div class="text-center">

          <h2 class="text-black"><?php the_field('enquire_title')?></h2>

         <?php the_field('enquire_sub_title')?>

          </div>

      </div>

    </div>

    <div class="row">

    	<div class="col-sm-3">

        	<form>

            	<h6>Select your preferred sales consultant</h6>

            	<div class="form-group">

                	<select class="form-control">
						<?php while(have_rows('enquire_location')) { the_row(); ?>
                      <option value="<?php the_sub_field('location'); ?>"><?php the_sub_field('location'); ?></option>
						<?php } ?>

                    </select>

                </div>

                <div class="form-group">

                	<select class="form-control">

                      <option>Choose a Consultant</option>
						<?php while(have_rows('enquire_consultant')) { the_row(); ?>
                      <option value="<?php the_sub_field('consultant'); ?>"><?php the_sub_field('consultant'); ?></option>
						<?php } ?>

                    </select>

                </div>

            </form>

        </div>

        <div class="col-sm-9">

        	<div class="enquire">

            	<ul>

                 <?php 

            if( have_rows('enquire') ):

                while( have_rows('enquire') ) : the_row();

            ?>

                	<li>

                        <div class="icon-box left">

                            <div class="icon"><span href="#" class="hi-icon"><img class="img-center" src="<?php the_sub_field('enquire_image'); ?>" alt=""></span></div>

                            <div class="icon-dec">

                                <h6 class="text-black mb-0"><?php the_sub_field('enquire_title'); ?></h6>

                                <a href="tel:<?php the_sub_field('enquire_number'); ?>">  <i class="fa fa-phone teamMembersForm--icon"></i><?php the_sub_field('enquire_number'); ?></a> 

                                <span class="text-green"><?php the_sub_field('enquire_postiton'); ?></span>

                            </div>

                        </div>

                    </li>

                        <?php

                    endwhile;

                  endif;

                ?>     

                </ul>

            </div>

        </div>

    </div>

    <hr>

    <div class="row mt-5">.

        <style>

    	.ajax-loader{

            display: none!important;

        }

    	</style>

    	<?php echo do_shortcode( '[contact-form-7 id="244" title="Enter your details" html_class="enquire-form"]' ); ?>

    </div>

    

    </div>

    </section>

<!--Enquire-->



<section class="dark-bg">

  <div class="container">

    <div class="row">

      <div class="col-sm-12">

        <h3 class="text-white text-center mb-7"><?php the_field('footer_title','options'); ?></h3>

      </div>

    </div>

    <div class="row">

      <div class="col-sm-4 border-r">

        <div class="contact-info text-center"> <span class="icon-round"> <i class="fa fa-phone"></i> </span>

          <h3><?php the_field('footer_call_title','options'); ?><br>

            <a href="tel:<?php echo str_replace(' ','',get_field('

call_us_number','options')); ?>"><?php the_field('call_us_number','options'); ?></a></h3>

        </div>

      </div>

      <div class="col-sm-4 border-r">

        <div class="contact-info text-center"> <span class="icon-round"> <i class="fa fa-home"></i> </span>

          <?php the_field('footer_center_content','options'); ?>

          <a  target="_blank" href="<?php the_field('search_new_home','options'); ?>" class="btn btn-white btn-danger mt-1 f-w-bold">Search New Homes</a> </div>

      </div>

      <div class="col-sm-4 border">

        <div class="contact-info text-center"> <span class="icon-round"> <i class="fa fa-group"></i> </span>

          <h3>FOLLOW US</h3>

          <ul class="list-inline social">

            <li><a target="_blank" href="<?php the_field('facebook','options'); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>

            <li><a target="_blank" href="<?php the_field('linked_in','options'); ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>

            <li><a target="_blank" href="<?php the_field('instagram','options'); ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>

          </ul>

        </div>

      </div>

    </div>

  </div>

</section>

</div>