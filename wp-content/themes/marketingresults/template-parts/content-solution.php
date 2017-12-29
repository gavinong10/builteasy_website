<div class="main-content">
    <section class="marketing">
        <div class="container">
            <div class="row row-eq-height no-gutter valign">
                <div class="col-md-6 col-sm-12" data-valign-overlay="middle">
                    <div class="">
                        <h5 class="text-black"><?php the_field("marketing_sub_title") ?></h5>
                        <h2 class="text-black"><?php the_field("marketing_title") ?></h2>
                        <ul class="list-unstyled">
                            <?php 
                            if(have_rows("marketing_points")){
                                while(have_rows("marketing_points"))
                                {
                                    the_row();
                                    ?>
                                    <li class="mb-1"><i class="fa fa-chevron-circle-right mr-1" aria-hidden="true"></i> <?php the_sub_field("poitns") ?></li>      
                                    <?php
                                }
                            }
                                ?>                          
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <?php the_field("marketing_content"); ?>
                </div>
            </div>
        </div>
    </section>
    <section class="dark-bg services">
    	<div class="container">
             <div class="row mb-5">
                <div class="col-sm-12 col-md-offset-1 col-md-10">
                     <div class="text-center text-white">
                        <h5><?php the_field("service_sub_title") ?></h5>
                  		<h2><?php the_field("service_title") ?></h2>
                     </div>
                </div>
            </div>
             <div class="row">
                <?php 
                if(have_rows("services")){
                    $i=1;
                    while(have_rows("services")){
                        the_row();
                        
                        ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="icon-box center">
                                <div class="icon intelligent-way-content"><span class="hi-icon"><i class="fa fa-<?php the_sub_field("fa_class")?>" aria-hidden="true"></i></span></div>
                                <div class="icon-dec">
                                    <h4 class="text-white"> <?php the_sub_field("title")?></h4>
                                    <p class="mt-2"><?php the_sub_field("content")?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                        
                        if($i>4)
                        {
                            ?>
                            </div>
                            <div class="row">
                            <?php
                            $i=0;
                        }    
                        $i++;                    
                    }    
                }
                
                ?>                                
            </div>
        </div>
    </section>
    <section class="">
        <div class="container">
            <div class="row">
                <div class="downloads">
                    <div class="col-md-4 col-sm-5 building-consultation__talk-to-us-now text-center">
                        <h4 class="building-title"><?php the_field("book_title") ?></h4>
                        <div class="building-consultation__header-line divider__item--green"></div>
                        <a href="tel:<?php the_field("book_number") ?>" class="building-consultation__phone-number"><i class="fa fa-phone"></i> <?php the_field("book_number") ?></a>
                        <div class="row">
                            <div class="col-xs-4">
                                <img class="building-consultation__australia" src="<?php the_field("book_image_svg_link") ?>">
                            </div>
                            <div class="col-xs-8">
                                <?php 
                                if(have_rows("book")){
                                    while(have_rows("book")){
                                        the_row();
                                        ?>
                                        <span class="building-consultation__working-time"><?php the_sub_field("book_day")?> <strong><?php the_sub_field("book_time")?></strong></span>
                                        <?php    
                                    }
                                }
                                ?>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 mt-3">
                                <?php $book_button_url=get_field('book_button_url'); if ($book_button_url): ?>
                                    <a href="<?php echo $book_button_url; ?>" type="button" class="btn btn-default btn-book"> <i class="fa fa-calendar"></i> <?php the_field("book_button_name") ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8 col-sm-7 conveyan pl-5">
                        <h5 class="text-black"><?php the_field("conveyancing_sub_title") ?></h5>
                        <h2 class="ps--header text-black"><?php the_field("conveyancing_title") ?></h2>
                        <div class="panel-group mt-5" id="accordion" role="tablist" aria-multiselectable="true">
                            <?php 
                            if(have_rows("conveyancing")){
                                $i=0;
                                while(have_rows("conveyancing")){
                                    the_row();
                                    ?>
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="heading<?php echo $i;?>">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
                                                <?php the_sub_field("conveyancing_question") ?>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse<?php echo $i;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $i;?>">
                                            <div class="panel-body">
                                                <?php the_sub_field("conveyancing_answer") ?>
                                            </div>
                                        </div>
                                    </div> 
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                                                       
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    </section>
</div>