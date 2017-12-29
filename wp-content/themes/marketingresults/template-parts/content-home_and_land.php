<section class="white-bg">
     <div class="container">
         <div class="row mb-5">
            <div class="col-sm-12">
             <div class="text-center section-title text-black">
            <h2><?php if(get_field("package_question")) echo get_field("package_question"); ?></h2>
            <?php if(get_field("package_answer")) echo get_field("package_answer"); ?>
            <button class="btn btn-sm btn-danger text-white mt-5">EXPLORE PACKAGES</button>

                </div>
            </div>
        </div>        
 </div>
</section>
<?php
if(have_rows("questions_and_answer"))
{
    $flag=true;$color="";$font_color="";
    while(have_rows("questions_and_answer"))
    {
        the_row();
        if($flag==true)
        {
            $color="blue";$font_color="white";
            $flag=false;   
        }
        else
        {
            $color="white";$font_color="black";
            $flag=true;
        }                        
        ?>
        <section class="<?php echo $color;?>-bg">
            	<div class="container">
                	<div class="row mb-5">
                    <div class="col-sm-12">
                     <div class="text-center section-title">
                  		<h4 class="text-<?php echo $font_color; ?>"><?php if(get_sub_field("question")) echo get_sub_field("question");?></h4>
                        </div>
                    </div>
                </div>
                <div class="row-eq-height content-module">
                	<?php 
                    if($flag==true)
                    {
                        ?>
                        <div class="col-sm-6 ">
                        	<img class="img-responsive" src="<?php if(get_sub_field("image")) echo get_sub_field("image");?>" alt="">
                        </div>
                        <?php
                    }
                    
                    ?>
                	<div class="col-sm-6 text-<?php echo $font_color; ?> xs-mt-3">
                        <?php if(get_sub_field("answer")) echo get_sub_field("answer");?>
                                    
                    </div>
                    <?php 
                    if($flag==false)
                    {
                        ?>
                        <div class="col-sm-6 ">
                        	<img class="img-responsive" src="<?php if(get_sub_field("image")) echo get_sub_field("image");?>" alt="">
                        </div>
                        <?php
                    }                    
                    ?>
                    
                </div>
        	</div>
        </section>
        <?php
    }
}
?>