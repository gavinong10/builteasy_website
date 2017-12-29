<!--=================================

 inner-intro--> 





<div class="main-content">

<!--=================================

 First Home Buyer--> 

<!--section class="white-bg" >

    	<div class="container">

        	   <div class="row">

            <div class="col-sm-12">

             <div class="text-center">

          		<h2 class="text-black"><?php the_field('main_title');?></h2>

                <?php the_field('main_content');?>

                </div>

            </div>

        </div>

	</div>

</section-->



<!--=================================

  Why buy a new home--> 



<section class="white-bg" >

    	<div class="container">

        	<div class="row mb-5">

            <div class="col-sm-12">

             <div class="text-center section-title">

          		<h4 class="text-black "><?php the_field('home_title');?></h4>

                </div>

            </div>

        </div>

       

        <div class="row-eq-height content-module">

        	<div class="col-sm-6 col-sm-push-6">

            	<img class="img-responsive" src="<?php the_field('home_image');?>" alt="">

            </div>

        	<div class="col-sm-6 text-black  col-sm-pull-6 xs-mt-3">

                <?php the_field('home_content');?>
				<?php $home_button_url=get_field('home_button_url'); ?>
            	<button class="btn btn-sm btn-danger text-white" onClick="window.location.href='<?php echo $home_button_url; ?>';"><?php the_field('home_button');?></button>

            </div>

            

        </div>

	</div>

</section>



<!--=================================

  Financing your dream--> 





<section class="blue-bg" >

    	<div class="container">

        	<div class="row mb-5">

            <div class="col-sm-12">

             <div class="text-center section-title">

          		<h4 class="text-white"><?php the_field('finding_your_perfect_home_design_title');?></h4>

                </div>

            </div>

        </div>

        <div class="row-eq-height content-module">

        	<div class="col-sm-6 ">

            	<img class="img-responsive" src="<?php the_field('financing_your_dream_image');?>" alt="">

            </div>

        	<div class="col-sm-6 text-white xs-mt-3">

                <?php the_field('financing_your_dream_content');?>

                
				<?php $financing_your_dream_button_url=get_field('financing_your_dream_button_url'); ?>
            	<button class="btn btn-sm btn-danger text-white" onClick="window.location.href='<?php echo $financing_your_dream_button_url; ?>';"><?php the_field('financing_your_dream_button');?></button>

            </div>

            

        </div>

	</div>

</section>



<!--=================================

  Where to live? Land considerations--> 

  

<section class="white-bg" >

	<?php the_content();?>

</section>

  

<!--=================================

 Finding your perfect home design--> 









<!--=================================

 Why choose Metricon for your first home?--> 

 

<section class="blue-bg">

    	<div class="container">

        	<div class="row mb-5">

            <div class="col-sm-12 col-md-offset-3 col-md-6">

             <div class="text-center section-title">

          		<h4 class="text-white "><?php the_field('why_coose_title');?></h4>

                </div>

            </div>

        </div>

        <div class="row content-module">

        	<div class="col-sm-6 text-white">

            <h5><?php the_field('one_title');?></h5>

            <?php the_field('one_content');?>

            <img class="img-responsive mt-3" src="<?php the_field('one_image');?>" alt="">

            </div>

            <div class="col-sm-6 text-white xs-mt-3">

            	<h5><?php the_field('two_title');?></h5>

                <?php the_field('two_content');?>

            <img class="img-responsive mt-3" src="<?php the_field('two_image');?>" alt="">

            </div>

        </div>

        

        

	</div>

</section>



<section class="white-bg" >

    	<div class="container">

        <div class="row content-module">

        	<div class="col-sm-6 text-black">

            <h5><?php the_field('three_title');?></h5>

            <?php the_field('three_content');?>

            <img class="img-responsive mt-3" src="<?php the_field('three_image');?>" alt="">

            </div>

            <div class="col-sm-6 text-black xs-mt-3">

            	<h5><?php the_field('four_title');?></h5>

                <?php the_field('four_content');?>

            <img class="img-responsive mt-3" src="<?php the_field('four_image');?>" alt="">

            </div>

        </div>

        

        

	</div>

</section>



<section class="blue-bg" >

    	<div class="container">

        	 <div class="row-eq-height content-module">

        	

        	<div class="col-sm-6 text-white col-sm-push-6">

            <h5><?php the_field('five_title');?></h5>

                <?php the_field('five_content');?>
				<?php $five_url=get_field('five_button_url'); ?>
            	<a class="btn btn-sm btn-danger text-white" href="<?php echo $five_url; ?>" target="_blank"><?php the_field('five_button'); ?></a>

            </div>

            <div class="col-sm-6 col-sm-pull-6  xs-mt-3">

            	<img class="img-responsive" src="<?php the_field('five_image');?>" alt="">

            </div>

   

        

        </div>

        

        

        

        </div>

        </section>



<!--=================================

 Why choose Metricon for your first home?--> 





<!--FRIENDLY ADVICE COMES STANDARD-->

<section class="grey-bg" id="friendly-advice" >

  <div class="container">

    <div class="row mb-5">

      <div class="col-sm-12 col-md-offset-2 col-md-8">

        <div class="text-center">

          <h2 class="text-black"><?php the_field('friendly_advice_comes_standard_title')?></h2>

          <p><?php the_field('friendly_advice_comes_standard_sub_title')?></p>

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

                <p><?php the_sub_field('advice_sub_title'); ?></p>
				<?php $advice_button_url=get_sub_field('advice_button_url'); ?>
                <button class="btn btn-sm" onClick="window.location.href='<?php echo $advice_button; ?>';"><?php the_sub_field('advice_button');?></button>

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

                            <div class="icon"><a href="#" class="hi-icon"><img class="img-center" src="<?php the_sub_field('enquire_image'); ?>" alt=""></a></div>

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

          <a target="_blank" href="<?php the_field('search_new_home','options'); ?>" class="btn btn-white btn-danger mt-1 f-w-bold">Search New Homes</a> </div>

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