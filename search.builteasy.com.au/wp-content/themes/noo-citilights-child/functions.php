<?php

/**

 * Theme functions for NOO Citilights Child Theme.

 *

 * @package    NOO Citilights Child Theme

 * @version    1.0.0

 * @author     Kan Nguyen <khanhnq@nootheme.com>

 * @copyright  Copyright (c) 2014, NooTheme

 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later

 * @link       http://nootheme.com

 */



// If you want to override function file ( like noo-property.php, noo-agent.php ... ),

// you should copy function file to the same folder ( like /framework/admin/ ) on child theme, then use similar require_one 

// statement like this code.

// require_once dirname(__FILE__) . '/framework/admin/noo-property.php';


//------------------Property pdf-----------------------------

update_option('siteurl','http://search.builteasy.com.au');
update_option('home','http://search.builteasy.com.au');

function getPropertyPdfId($property_id){
  global $wpdb;
  $sql="SELECT property_pdf_id FROM {$wpdb->prefix}pdf_property_lookup where property_id=".$property_id." "; 
  $result=$wpdb->get_var($sql);     
  return $result;
}


function getPropertyPdfData($pdf_id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}property_pdf where id=".$pdf_id." "; 
  $result=$wpdb->get_row($sql);     
  return $result;

}


function getPropertyPackageData($pdf_id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}pdf_packages where property_pdf_id=".$pdf_id." "; 
  $result=$wpdb->get_results($sql);    
  return $result;

}


function getPropertyFaqData($pdf_id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}faqs where property_pdf_id=".$pdf_id." ORDER BY {$wpdb->prefix}faqs.id ASC "; 
  $result=$wpdb->get_results($sql);    
  return $result;
}


function getPropertyFeatureImage($pdf_id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}pdf_feature_images where property_pdf_id=".$pdf_id." "; 
  $result=$wpdb->get_results($sql);    
  return $result;
}



function getPropertyBuildingPackage($pdf_id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}pdf_building_package where property_pdf_id=".$pdf_id." "; 
  $result=$wpdb->get_results($sql);    
  return $result;
}



function displayAmazingGallery($pdf_id){
  global $wpdb;

  // get gallery images
  $sql="SELECT * FROM {$wpdb->prefix}pdf_gallery_images where property_pdf_id=".$pdf_id." "; 
  $images=$wpdb->get_results($sql);    
  $sliderimg='';
  foreach($images as $img){
    $src= $img->gallery_image;
    $sliderimg.='<li>
                    <img src="'.$src.'"/>
                </li>';
  }

  
  $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/sliderengine/initslider-1.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/sliderengine/amazingslider-1.css" rel="stylesheet">
            <div id="amazingslider-wrapper-1" style="display:block;position:relative;max-width:900px;margin:0px auto 98px;">
              <div class="amazingslider-1" style="display:block;position:relative;margin:0 auto;">
                  <ul class="amazingslider-slides" style="display:none;">
                      '.$sliderimg.'
                  </ul>
                  <ul class="amazingslider-thumbnails" style="display:none;">
                      '.$sliderimg.'
                  </ul>
              
              </div>
          </div>';

    return $sliderstr;
}


function displayAmazingGallery1($pdf_id){
   global $wpdb;

  // get gallery images
  $sql="SELECT * FROM {$wpdb->prefix}pdf_gallery_images where property_pdf_id=".$pdf_id." "; 
  $images=$wpdb->get_results($sql);    
  $sliderimg='';
  foreach($images as $img){
    $src= $img->gallery_image;
    $sliderimg.='<li>
                    <img src="'.$src.'"/>
                </li>';
  }


  $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/sliderengine-up/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/sliderengine-up/initslider-2.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/sliderengine-up/amazingslider-2.css" rel="stylesheet">
           
           <div id="amazingslider-wrapper-2" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
              <div id="amazingslider-2" style="display:block;position:relative;margin:0 auto;">
                  <ul class="amazingslider-slides" style="display:none;">
                      '.$sliderimg.'
                  </ul>
                  <ul class="amazingslider-thumbnails" style="display:none;">
                      '.$sliderimg.'
                  </ul>
              
              </div>
          </div>';

  return $sliderstr;

}


function displayAmazingGalleryReal($pdf_id){
   global $wpdb;

  // get gallery images
  $sql="SELECT * FROM {$wpdb->prefix}pdf_gallery_images where property_pdf_id=".$pdf_id." "; 
  $images=$wpdb->get_results($sql);    
  $sliderimg='';
  foreach($images as $img){
    $src= $img->gallery_image;
    $sliderimg.='<li>
                    <img src="'.$src.'"/>
                </li>';
  }


  $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-1/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-1/sliderengine/initslider-101.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-1/sliderengine/amazingslider-101.css" rel="stylesheet">
           
           <div id="amazingslider-wrapper-101" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
             <div id="amazingslider-101" style="display:block;position:relative;margin:0 auto;">
               <ul class="amazingslider-slides" style="display:none;">
                      '.$sliderimg.'
                  </ul>
                  <ul class="amazingslider-thumbnails" style="display:none;">
                      '.$sliderimg.'
                  </ul>
              
              </div>
          </div>';

  return $sliderstr;

}



function getFacadeFloarSpac($id){
  global $wpdb;

  // get gallery images
  $sql="SELECT * FROM {$wpdb->prefix}pdf_building_package where id=".$id." "; 
  $result=$wpdb->get_results($sql);    
  //echo "<pre>";
  //print_r($result);

  
  $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/sliderengine/initslider-1.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/sliderengine/amazingslider-1.css" rel="stylesheet">
            <div id="amazingslider-wrapper-1" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
              <div class="amazingslider-1" style="display:block;position:relative;margin:0 auto;">
                  <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';

    return $sliderstr;
}


function getRealFacadeFloarSpac($id,$index){
  global $wpdb;

  // get gallery images
  $sql="SELECT * FROM {$wpdb->prefix}pdf_building_package where id=".$id." "; 
  $result=$wpdb->get_results($sql);    
  //echo "<pre>";
  //print_r($result);
  $sliderstr='';
  if($index==1){
  $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-2/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-2/sliderengine/initslider-102.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-2/sliderengine/amazingslider-102.css" rel="stylesheet">
            <div id="amazingslider-wrapper-102" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
        <div id="amazingslider-102" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';

        }else if($index==2){
           
           $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-3/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-3/sliderengine/initslider-103.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-3/sliderengine/amazingslider-103.css" rel="stylesheet">
            <div id="amazingslider-wrapper-103" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
        <div id="amazingslider-103" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';
           
        }else if($index==3){

           $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-4/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-4/sliderengine/initslider-104.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-4/sliderengine/amazingslider-104.css" rel="stylesheet">
            <div id="amazingslider-wrapper-104" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
            <div id="amazingslider-104" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';

        }else if($index==4){
           $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-5/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-5/sliderengine/initslider-105.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-5/sliderengine/amazingslider-105.css" rel="stylesheet">
            <div id="amazingslider-wrapper-105" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
            <div id="amazingslider-105" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';
        }else if($index==5){
          $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-6/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-6/sliderengine/initslider-106.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-6/sliderengine/amazingslider-106.css" rel="stylesheet">
            <div id="amazingslider-wrapper-106" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
            <div id="amazingslider-106" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';
        }else if($index==6){
          $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-7/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-7/sliderengine/initslider-107.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-7/sliderengine/amazingslider-107.css" rel="stylesheet">
            <div id="amazingslider-wrapper-107" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
            <div id="amazingslider-107" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';
        }else if($index==7){
          $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-8/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-8/sliderengine/initslider-108.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-8/sliderengine/amazingslider-108.css" rel="stylesheet">
            <div id="amazingslider-wrapper-108" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
            <div id="amazingslider-108" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';
        }else if($index==8){
          $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-9/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-9/sliderengine/initslider-109.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-9/sliderengine/amazingslider-109.css" rel="stylesheet">
            <div id="amazingslider-wrapper-109" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
            <div id="amazingslider-109" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';
        }else if($index==9){
          $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-10/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-10/sliderengine/initslider-110.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-10/sliderengine/amazingslider-110.css" rel="stylesheet">
            <div id="amazingslider-wrapper-110" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
            <div id="amazingslider-110" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';
        }else if($index==10){
          $sliderstr='<script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-11/sliderengine/amazingslider.js"></script>
           <script type="text/javascript" src="'.get_template_directory_uri().'/assets/js/real-slider/slider-11/sliderengine/initslider-111.js"></script>  
           <link  href="'.get_template_directory_uri().'/assets/js/real-slider/slider-11/sliderengine/amazingslider-111.css" rel="stylesheet">
            <div id="amazingslider-wrapper-111" style="display:block;position:relative;max-width:725px;margin:0px auto 98px;">
            <div id="amazingslider-111" style="display:block;position:relative;margin:0 auto;">
            <ul class="amazingslider-slides" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
                  
                  <ul class="amazingslider-thumbnails" style="display:none;">
                    
                    <li>
                      <img src="'.$result[0]->facade.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->floar_plan.'"/>
                    </li>

                    <li>
                      <img src="'.$result[0]->specification.'"/>
                    </li>

                  </ul>
              
              </div>
          </div>';
        }

    return $sliderstr;
}


//------------------- Register ------------------------

add_action( 'wp_ajax_nopriv_get_sign_on', 'getSignOn' );
add_action( 'wp_ajax_get_sign_on', 'getSignOn');

function getSignOn(){
  if(!$_POST) exit;

  // Email address verification, do not edit.
  function isEmail($email) {
    return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
  }
  
  if (!defined("PHP_EOL")) define("PHP_EOL", "\r\n");
  
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  //$pwd1  = $_POST['password'];
  //$pwd2 = $_POST['confirm_password'];

  $prop_url= $_POST['prop_url'];
  
  $role="subscriber";

  if(trim($firstname) == '') {
    echo '<div class="alert alert-error">'.__('You must enter your firstname.','framework').'</div>';
    exit();
  }else if(trim($lastname) == ''){
    echo '<div class="alert alert-error">'.__('You must enter your lastname.','framework').'</div>';
    exit();
  }else if(trim($email) == '') {
    echo '<div class="alert alert-error">'.__('You must enter email address.','framework').'</div>';
    exit();
  } else if(!isEmail($email)) {
    echo '<div class="alert alert-error">'.__('You must enter a valid email address.','framework').'</div>';
    exit();
  }
  

  $err = '';
  $success = '';
  
  global $wpdb, $PasswordHash, $current_user, $user_ID;
  
  if(isset($_POST['task']) && $_POST['task'] == 'register') {
    $firstname = esc_sql(trim($_POST['firstname']));
    $lastname = esc_sql(trim($_POST['lastname']));
    $phone = esc_sql(trim($_POST['phone']));
    //$pwd1 = esc_sql(trim($_POST['password']));
    //$pwd2 = esc_sql(trim($_POST['confirm_password']));
    $email = esc_sql(trim($_POST['email']));
     
    if ($email == "" || $firstname == "" || $lastname=='' || $phone=='') {
      $err = 'Please enter (incomplete fields)';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $err = 'Invalid email address.';
    } else if (email_exists($email)) {
      $err = 'Email already registered, please login';
    } else {

      $token=md5(rand());

      $user_id = wp_insert_user(
              array(
                'user_pass' => apply_filters('pre_user_user_pass', $phone), 
                'user_login' => apply_filters('pre_user_user_login', $email), 
                'user_email' => apply_filters('pre_user_user_email', $email),
                'display_name' => $firstname." ".$lastname,
                 
                'role' => $role)
              );
              
      if (is_wp_error($user_id)) {
        $err = 'Error on user creation.';
      } else {

          $sql="UPDATE {$wpdb->prefix}users SET 
               token='".$token."',
               user_status='1'
               WHERE ID='".$user_id."'";

          $wpdb->query($sql);

          add_user_meta( $user_id, 'phone', $phone);
          //add_user_meta( $user_id, 'first_name', $firstname);
          //add_user_meta( $user_id, 'last_name', $lastname);   

          do_action('user_register', $user_id);
          $success = 'Please check your email for verification link.';
                                $info_register = array();
                                $info_register['user_login'] = $email;
                                $info_register['user_password'] = $pwd1;
                                //wp_signon( $info_register, false );
     }
   }
 }
  

/* ######### Send Notification ####### */
  
  if(!empty($success)){
     $fullname= $firstname." ".$lastname;
     sendPropertyNotification($fullname,$email,$_POST['property']);
     sendActivationLink($prop_url,$token,$email,$firstname);
     // notification to admin
     newRegistrationNotifyAdmin($fullname,$email,$phone);
  }

/* ############################## */

  if (!empty($err)) :
    echo '<div class="alert alert-warning">' . $err . '</div>';
  endif;
  
  if (!empty($success)) :
    echo '<div class="alert alert-success">' . $success . '</div>';
  endif;

  die();
}


function sendActivationLink($prop_url,$token,$email,$firstname){
  //$to= $email;
  $to= $email;

  $subject="Builteasy registration";
  $headers .= "Reply-To: enquire@builteasy.com.au\r\n";

  $message="Dear ".$firstname." <br><br>";
  $message.="Thank you for registering with Built Easy. <br>";
  $message.="To complete your registration, please click the following link. <br><br>";
  
  $message.="<a href='".site_url()."/verification?propurl=".$prop_url."&token=".$token."'>".site_url()."/verification?propurl=".$prop_url."&token=".$token."</a>";
  
  $message.="<br><br>Regards,<br><br>Built Easy";

  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

  wp_mail($to, $subject, $message, $headers);
  
}


function newRegistrationNotifyAdmin($fullname,$email,$phone){
  // send mail to admin
  $to= "enquire@builteasy.com.au";

  //$to= "aloktripathiprofessional@gmail.com";

  $subject="New user registered";
  $headers .= "Reply-To: enquire@builteasy.com.au\r\n";

  $message="Dear Admin <br><br>";
  $message.="A user registered with following details: <br><br>";
  $message.="Name: ".$fullname."<br>";
  $message.="Email: ".$email."<br><br>";
  $message.="Phone: ".$phone." <br></br>";

  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

  wp_mail($to, $subject, $message, $headers);
}


function getRowByToken($token){
   global $wpdb;  
   
   $sql= "SELECT * FROM {$wpdb->prefix}users WHERE token='".$token."' ";
   $result=$wpdb->get_row($sql);
   return $result;
}


function setUserStatus($token){
   global $wpdb;
   $sql="UPDATE {$wpdb->prefix}users SET 
               user_status='0'
               WHERE token='".$token."'";

    $wpdb->query($sql);
    return true;
}


function sendPassword($email,$pwd1){
  //send mail to user

  $to= $email;

  $subject="Builteasy registration";
  $headers .= "Reply-To: enquire@builteasy.com.au\r\n";

  $message="Dear User <br><br>";
  $message.="Please login with following details : <br><br>";
  $message.="Email: ".$email."<br>";
  $message.="password: ".$pwd1."<br><br>";
  
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

  wp_mail($to, $subject, $message, $headers);


}



function sendPropertyNotification($name,$email,$property){
  // send mail to admin
  $to= "enquire@builteasy.com.au";

  //$to= "kvermasanjay555@gmail.com";

  $subject="Website Information Pack Access for ".$property."";
  $headers .= "Reply-To: enquire@builteasy.com.au\r\n";

  $message="Dear Admin <br><br>";
  $message.="A user access property details: <br><br>";
  $message.="Name: ".$name."<br>";
  $message.="Email: ".$email."<br><br>";
  $message.="Property: ".$property." <br></br>";

  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

  wp_mail($to, $subject, $message, $headers);


}




add_action( 'wp_ajax_nopriv_send_notification_login_via_social', 'sendNotificationLoginViaSocial' );
add_action( 'wp_ajax_send_notification_login_via_social', 'sendNotificationLoginViaSocial');

function sendNotificationLoginViaSocial(){
  $current_user= wp_get_current_user();
  $name= $current_user->display_name;
  $email=$current_user->user_email;
  sendPropertyNotification($name,$email,$_POST['property']);

  die();
}


add_action( 'wp_ajax_nopriv_send_notification_for_property_access', 'sendNotificationPropertyAccess' );
add_action( 'wp_ajax_send_notification_for_property_access', 'sendNotificationPropertyAccess');

function sendNotificationPropertyAccess(){
  $current_user= wp_get_current_user();
  $name= $current_user->display_name;
  $email=$current_user->user_email;
  sendPropertyNotification($name,$email,$_POST['property']);

  die();
}


//--------------Forgot Password-----------

add_action( 'wp_ajax_nopriv_forgot_password', 'forgotPassword' );
add_action( 'wp_ajax_forgot_password', 'forgotPassword');

function forgotPassword(){
   global $wpdb;	
   $email=$_REQUEST['email'];
   // check email is valid

   
   $esql= "SELECT * FROM {$wpdb->prefix}users WHERE user_email='".$email."' ";
   $data=$wpdb->get_row($esql);
   if(!empty($data)){
   	  
   	  $user_id = $data->ID;
      // get phone
      $phone=get_user_meta($user_id, "phone", true);

      if($phone){
        $password= $phone;
      }else{
        $password= wp_generate_password();
      }
	    //$password= wp_generate_password();

	    wp_set_password($password, $user_id);
	    sendForgotPassword($email,$password);
      $str="Your password sent to your email. please check your inbox";
   }else{
   	 $str= "email not exist.";
   }

   echo '<div class="alert alert-success">' . $str . '</div>';

   die();	
}


function sendForgotPassword($email,$password){
  //send mail to user

  $to= $email;

  $subject="Builteasy Forgot Password";
  $headers .= "Reply-To: enquire@builteasy.com.au\r\n";

  $message="Dear User <br><br>";
  $message.="Please login with following details : <br><br>";
  $message.="Email: ".$email."<br>";
  $message.="password: ".$password."<br><br>";
  
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

  wp_mail($to, $subject, $message, $headers);


}



  /**
   * Returns a inline CSS passage that resizes
   * wp_editor()'s width and height.
   *
   * @param int $width
   * @param int $height
   */
  function wp_editor_resize($width = 0, $height = 0) {
    $style = '<style type="text/css">';
    if($width) {
      $style .= '.wp-editor-container { width:' . $width . 'px !important; }';
    }
    if($height) {
      $style .= '.wp-editor-area { height:' . $height . 'px !important; }';
    }
    $style .= "</style>";
    echo $style;
  }


//--------------------login------------------------

add_action( 'wp_ajax_nopriv_get_login_in', 'getSignIn' );
add_action( 'wp_ajax_get_login_in', 'getSignIn');


function getSignIn(){
    global $wpdb;

    $info = array();
    $info['user_login'] = $_POST['email'];
    $info['user_password'] = $_POST['password'];
    if($_POST['rememberme']=='true') {
      $info['remember'] = true; 
    }else{
    
      $info['remember'] = false; 
    }

    // check status
    $sql= "SELECT {$wpdb->prefix}users.user_status FROM {$wpdb->prefix}users WHERE user_email='".$_POST['email']."' ";
    $status=$wpdb->get_var($sql);
    if($status=='1'){
       echo json_encode(array('loggedin'=>false, 'message'=>__('Your account is not activated yet.','framework')));
       die();
    }


    $user_signon = wp_signon($info, false);
        

      if(is_wp_error($user_signon)){
          echo json_encode(array('loggedin'=>false, 'message'=>__('Incorrect Email Or Phone','framework')));
      }else{
          sendPropertyNotificationFromLogin($_POST['email'],$_POST['property']);
          $_SESSION['clickToLogin']='yes';
          echo json_encode(array('loggedin'=>true, 'message'=>__('Login successful, redirecting...','framework')));
      }

      die();

}


function sendPropertyNotificationFromLogin($email,$property){
  global $wpdb;
  $user = get_user_by('email',$email);
  //print_r($user);
  $name= $user->display_name;
  sendPropertyNotification($name,$email,$property);
}



//--------------------Login menu----------------------
add_filter( 'wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2 );

function wti_loginout_menu_link( $items, $args ) {
   if ($args->theme_location == 'primary') {
      if (is_user_logged_in()) {
         $items .= '<li class="right"><a href="'. wp_logout_url() .'">Log Out</a></li>';
      } else {
        // $items .= '<li class="right"><a href="javascript:void(0)" onclick="LoginLayout()">Log In</a></li>';
      }
   }
   return $items;
}

// filter for the logout function 



function my_custom_lougout() {
    $path=$_SERVER['HTTP_REFERER'];

    wp_redirect($path);
    exit();
}
add_action('wp_logout', 'my_custom_lougout');



//-------------------------------------------------------
add_action( 'wp_ajax_nopriv_send_pdf_link', 'sendPdfLink' );
add_action( 'wp_ajax_send_pdf_link', 'sendPdfLink' );

function sendPdfLink(){

  session_start();
  global $wpdb;

  $downloader_name= $_REQUEST['downloader_name'];
  $email= $_REQUEST['email'];
  $property_title= $_REQUEST['property_title'];
  $pdf_title= $_REQUEST['pdf_title'];
  $pdf_file= $_REQUEST['pdf_file'];

  $pdf_path= get_site_url()."/wp-content/uploads/".$pdf_file;

  $pdf_file_explode= explode("/", $pdf_file);
  

  //---- store downloader detail in session

  $_SESSION['downloader_name_session']= $downloader_name;

  $_SESSION['downloader_email_session']= $email;


  //-------------------------------------



  //send link to user

  $to= $email;

  $subject="Download link for ". $property_title." information pack";

  //$headers = "From: enquire@builteasy.com.au";

  $headers .= "Reply-To: enquire@builteasy.com.au\r\n";

  $message="Hi ".$downloader_name."<br><br>";

  $message.="Thank you for requesting the information pack for ".$property_title.". Please click <a href=".$pdf_path.">here</a> to download the pack. <br><br>";

  $message.="Should you have any questions, feel free to contact us anytime.<br><br>";

  $message.="We look forward to hearing from you!<br><br>";

  $message.="The Built Easy Team ";

  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";



  wp_mail( $to, $subject, $message, $headers);





  /******** save downloader detail**********/
  //get email
  $esql= "SELECT downloader_email FROM {$wpdb->prefix}download_details WHERE downloader_email='".$email."' ";
  $db_email=$wpdb->get_var($esql);

  if(!$db_email){
    $sql="INSERT {$wpdb->prefix}download_details SET 
               downloader_name='".$downloader_name."',
               downloader_email='".$email."',
               created='".date('Y-m-d H:i:s')."'";

    $wpdb->query($sql);
  }

  

  /**********end**************/

  // send mail to admin
  
  $to= "admin@builteasy.com.au";
  $subject="Website Information Pack Download for ".$property_title."";

  
  $headers .= "Reply-To: enquire@builteasy.com.au\r\n";

  $message="Dear Admin <br><br>";

  $message.="A user downloaded property details: <br><br>";

  $message.="Name: ".$downloader_name." \n Email: ".$email." <br><br>";

  $message.="PDF Title: ".$pdf_file_explode[2]." <br></br>";


  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

  //$headers .= "From: enquire@builteasy.com.au";

  wp_mail( $to, $subject, $message, $headers);



  echo "send";



  die();

}


function get_user_role() {
  global $current_user;

  $user_roles = $current_user->roles;
  $user_role = array_shift($user_roles);

  return $user_role;
}


function wpse121308_redirect_homepage() {
    // Check for blog posts index
    // NOT site front page, 
    // which would be is_front_page()
    //is_home
    if ( is_front_page() ) {
        wp_redirect( 'http://search.builteasy.com.au/listings/new-homes/' );
        exit();
    }
}
add_action( 'template_redirect', 'wpse121308_redirect_homepage' );



?>