<!--How can we help you?-->

<section class="grey-bg">

  <div class="container">

    <div class="row">

      <div class="col-sm-12">

        <div class="text-center">

          <h2 class="text-black">How can we help you?</h2>

          <p><?php the_field('help_description'); ?></p>

        </div>

      </div>

    </div>

    <div class="row">

      <div class="contact-tiles">

	  <?php while(have_rows('blocks')) { the_row(); ?>

        <div class="col-md-4 col-sm-6 sm-mb-5">

          	<div class="contact-tile">

            	<a href="#" class="contact-tile-image">

                	<img src="<?php the_sub_field('image'); ?>" alt="">

                </a>

         

            <div class="contact-tile-content text-center">

            	<?php the_sub_field('description'); ?>

            </div>

        </div>

          </div>

	  <?php } ?>

      </div>

    </div>

  </div>

</section>

<!--How can we help you?-->



<!-- Contact Details -->

<section class="white-bg">

  <div class="container">

    <div class="row">

      <div class="col-sm-12">

        <div class="text-center">

          <h2 class="text-black">Contact Details</h2>

        </div>

      </div>

    </div>

	<div class="row mt-5">

      <div class="col-sm-offset-2 col-sm-4 sm-mb-5">

      		<div class="find-out-tile vcard text-center">

            	<h5 class="text-black"><?php the_field('location_title'); ?> </h5>

                <div class="content">

                	<div class="adr">

                    	<span class="streetAddress"><?php the_field('street_address'); ?></span>

                        <span class="locality"><?php the_field('locality'); ?></span>

                        <abbr class="region"><?php the_field('region'); ?></abbr>

                        <span class="postal-code"><?php the_field('postal-code'); ?></span>

                    </div>

                    <span class="pretty-phone"><a href="tel:+61<?php the_field('telephone'); ?>" class="tel " itemprop="telephone"><?php the_field('telephone'); ?></a></span>

                    <span class="workhours" >  <?php the_field('workhours'); ?>      </span>

                </div>

            </div>

      </div>

      <div class="col-sm-4">

      		<div class="find-out-tile vcard text-center">

            	<h5 class="text-black">General Enquiries</h5>

                <div class="content">

                	<p><?php the_field('general_enquiries'); ?></p>

                    <span class="pretty-phone"><a href="tel:+61<?php the_field('pretty-phone'); ?>" class="tel " itemprop="telephone"><?php the_field('pretty-phone'); ?></a></span>

                    <span class="workhours" > <?php the_field('workhours2'); ?> </span>

                </div>

            </div>

      </div>

    </div>

 </div>

</section>

<!-- Contact Details -->



<!-- Contact form -->

	<section class="grey-bg">

  		<div class="container">

        	<div class="row mb-7 xs-mb-4" >

               <div class="col-md-offset-2 col-md-8">

                <div class="text-center">

                  <h2 class="text-black">Contact form</h2>

                </div>

              </div>

            </div>

            <div class="row">

            <div id="content-form" class="col-md-offset-2 col-md-8">

            	<?php echo do_shortcode('[contact-form-7 id="187" title="Contact form"]'); ?>

                

                </div>

            </div>

    	</div>

    </section>

<!-- Contact form -->



<section class="dark-bg"> 

		<div class="container">

			<div class="row">

				<div class="col-sm-12">

				<h3 class="text-white text-center mb-7"><?php the_field('footer_title','options'); ?></h3>

				</div>

			</div>

			<div class="row">
				
				<div class="col-sm-4 border-r">
        <div class="contact-info text-center"> <span class="icon-round"> <i class="fa fa-phone" onclick="window.location.href='tel:<?php the_field('call_us_number','options'); ?>';"></i> </span>
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
            <li><a href="<?php the_field('facebook','options'); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
            <li><a href="<?php the_field('linked_in','options'); ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
            <li><a href="<?php the_field('instagram','options'); ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
          </ul>
        </div>
      </div>
	  
				<?php /* 
				<div class="col-sm-4 border-r">

					<div class="contact-info text-center">

						<span class="icon-round">

							<i class="fa fa-phone"></i>

						</span>

						<h3>CALL US TODAY<br>

						<a href="tel:<?php echo str_replace(' ','',get_field('

call_us_number','options')); ?>"><?php the_field('call_us_number','options'); ?></a></h3>

					</div>

				</div>

				<div class="col-sm-4 border-r">

					<div class="contact-info text-center">

						<span class="icon-round">

							<i class="fa fa-home"></i>

						</span>

						<?php the_field('download_pack','options'); ?>

					</div>

				</div>

				<div class="col-sm-4 ">

					<div class="contact-info text-center">

						<span class="icon-round">

						<i class="fa fa-group"></i>

						</span>

						<h3>FOLLOW US</h3>

						<ul class="list-inline social">

							<li><a target="_blank" href="<?php the_field('facebook','options'); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>

            <li><a target="_blank" href="<?php the_field('linked_in','options'); ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>

            <li><a target="_blank" href="<?php the_field('instagram','options'); ?>"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>

						</ul>

					</div>

				</div>
				*/ ?>
			</div>

		</div>

	</section>