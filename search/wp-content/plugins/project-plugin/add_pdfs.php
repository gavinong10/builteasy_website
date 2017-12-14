<?php // ob_start();



include_once("inc/ProjectManager_func.php");
$obj= new ProjectManager_func();

if(isset($_REQUEST['save']))  /*If Project is Saved*/

{  
   
   $return=$obj->addPdfData($_REQUEST,$_FILES);

  if($return){

    echo "<script>location.href='".site_url()."/wp-admin/admin.php?page=pdfs';</script>";

  }

}



?>



<div class="wrap">
  <h2>PDF Managements</h2>

  <form  method="POST" name="plotform" id="plotform" enctype="multipart/form-data">

  <div class="postbox">
  <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__).'css/validationEngine.jquery.css' ?>" type="text/css">
  <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__).'css/chosen.css' ?>" type="text/css">
  
  <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__).'js/jquery-1.7.2.min.js' ?>"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__).'js/chosen.jquery.js' ?>"></script>
 

<?php /*
  <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__).'js/nicEdit.js' ?>"></script>
<script type="text/javascript">
  bkLib.onDomLoaded(function() {
   new nicEditor().panelInstance('answer_1'); 
 });
</script>
*/ ?>



<script type="text/javascript">
jQuery(document).ready(function(){
   jQuery("#plotform").validationEngine();
});


$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();

        if(x < max_fields){ //max input box allowed
            x++; //text box increment

            $('#tableid').append('<tr class="form-field form-required">'+

                  '<td></td>'+
                  '<td>Build Price<br><input type="text" name="build_price[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>Size(m<sup>2</sup>)<br><input type="text" name="size[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>Bed<br><input type="text" name="bed[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>Bath<br><input type="text" name="bath[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>car<br><input type="text" name="car[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>Storeys<br><input type="text" name="storeys[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>Package Price<br><input type="text" name="package_price[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+

                  '<td><a href="#" class="remove_field button"> X </a></td>'+

                '</tr>'); //add input box

        }

    });

   

    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text

        e.preventDefault(); 
        //$(this).parent('div').remove();
        $(this).closest('tr').remove();
        x--;
    })

});



//-----------Building Package------------
/*
$(document).ready(function() {

    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap_faq"); //Fields wrapper
    var add_button      = $(".add_field_button_bp"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $('#tablebp').append('<tr class="form-field form-required">'+
                  
                  '<td>Package Includes<br><textarea name="package_includes[]" class="validate[required]" value="" style="width:200px;height:30px;"></textarea><br><span style="font-size: 10px;">eg. Tiles,Carpet,AC</span></td>'+
                  '<td>Feature Image<br><input type="file" name="feature_image[]" class="validate[required]" value="" style="width:100px;" /></td>'+
                  '<td>Building Model<br><input type="file" name="building_model[]" class="validate[required]" value="" style="width:100px;" /></td>'+
                  

                  '<td>Price<br><input type="text" name="package_price[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>Size(m<sup>2</sup>)<br><input type="text" name="package_size[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>Bed<br><input type="text" name="package_bed[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>Bath<br><input type="text" name="package_bath[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+
                  '<td>car<br><input type="text" name="package_car[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>'+

                  '<td><a href="#" class="remove_field button" style="margin-top:19px;"> X </a></td>'+
                '</tr>'); //add input box
        }

    });

   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        //$(this).parent('div').remove();
        $(this).closest('tr').remove();
        x--;
    })
});
*/


//-----------Building Package------------

$(document).ready(function() {

    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap_faq"); //Fields wrapper
    var add_button      = $(".add_field_button_bp"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $('#tablebp').append('<tr class="form-field form-required">'+
                  
                  '<td>Package Name<br><input type="text" name="building_pakg_name[]" class="validate[required]" style="width:200px;" /></td>'+
                  
                  '<td>'+
                    'Facade<br>'+
                    
                    '<input type="text" class="regular-text facade_custom_images" id="facade_custom_images_'+x+'" name="facade[]" style="width:50px" readonly>'+
                    '<button type="button" class="set_custom_images button">Set Image ID</button>'+
                  '</td>'+

                  '<td>'+
                   'Floar Plan<br>'+
                    '<input type="text" class="regular-text floar_custom_images" id="floar_custom_images_'+x+'" name="floar_plan[]" style="width:50px" readonly>'+
                    '<button type="button" class="set_custom_images button">Set Image ID</button>'+
                  '</td>'+

                  '<td>'+
                   ' Specification<br>'+
                    
                    '<input type="text" class="regular-text specification_custom_images" id="specification_custom_images_'+x+'" name="specification[]" style="width:50px" readonly>'+
                    '<button type="button" class="set_custom_images button">Set Image ID</button>'+
                  '</td>'+
                
                  '<td>Price<br><input type="text" name="build_package_price[]" class="validate[required]" value="" style="width:90px;height:30px; " /></td>'+

                  '<td><a href="#" class="remove_field button" style="margin-top:19px;"> X </a></td>'+
                '</tr>'); //add input box


               jQuery(document).ready(function(){
                jQuery('.set_custom_images').click(function(e){
                    
                    e.preventDefault();
                    var button = $(this);
                    var id = button.prev();
                    console.log(id);
                    wp.media.editor.send.attachment = function(props, attachment) {
                        id.val(attachment.url);
                    };
                    wp.media.editor.open(button);
                    return false;
                });
                
              })

        }

    });

   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        //$(this).parent('div').remove();
        $(this).closest('tr').remove();
        x--;
    })
});




//------------------Faqs---------------------------



$(document).ready(function() {

    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap_faq"); //Fields wrapper
    var add_button      = $(".add_field_button_faq"); //Add button ID

    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $('#tableid_faq').append('<tr class="form-field form-required">'+
                  '<td></td>'+
                  '<td>Question<br><input type="text" name="question[]" class="validate[required]" value="" style="width:350px;height:30px; "/></td>'+
                  '<td><textarea name="answer[]" placeholder="Answer" id="answer_'+x+'" class="validate[required]" style="width:350px;height:65px;"></textarea></td>'+
                  '<td><a href="#" class="remove_field button"> X </a></td>'+
                '</tr>'); //add input box
            
              new nicEditor().panelInstance('answer_'+x);
            
        }

    });

   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        //$(this).parent('div').remove();
        $(this).closest('tr').remove();
        x--;
    })
});





//------------Image preview------------



$(document).ready(function() {

 if(window.File && window.FileList && window.FileReader) {
 $("#files").on("change",function(e) {

   var files = e.target.files ,
   filesLength = files.length ;
   for (var i = 0; i < filesLength ; i++) {
   var f = files[i]
   var fileReader = new FileReader();
   fileReader.onload = (function(e) {
   var file = e.target;
   $("<img></img>",{
   class : "imageThumb",
   src : e.target.result,
   title : file.name
   }).insertAfter("#files");

   });

   fileReader.readAsDataURL(f);

 }

});

 } else { alert("Your browser doesn't support to File API") }

});



//-------------choosen-----------------



$(document).ready(function(){

 var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }

    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
});


</script>



  <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__).'js/jquery.validationEngine.js' ?>"></script>
  <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__).'js/jquery.validationEngine-en.js' ?>"></script>

<style type="text/css">
input[type="file"] {
 display:block;
}

.imageThumb {
 max-height: 75px;
 border: 2px solid;
 margin: 10px 10px 0 0;
 padding: 1px;
 }

</style>



  

  <div style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;">

  <table class="form-table" >

    <tbody>

            <tr class="form-field form-required">
                <th scope="row" align="right"><b><label for="phase">Property<span class="description">*</span> :</label></b></th>
                <td>
                    <?php $args = array('post_type' => 'noo_property','posts_per_page' =>'-1');
                     $wp_query = new WP_Query($args);
                     //echo "<pre>";
                     //print_r($wp_query);
                     ?>

                    <select name="property[]" class="chosen-select" multiple style="width:350px;height:65px;">
                    <?php while($wp_query->have_posts()) : ?>
                    <?php $wp_query->the_post();  ?>
                     <option value="<?php echo $wp_query->post->ID  ?>"><?php echo the_title();?></option>
                    <?php endwhile; ?>
                    
                    </select>

                </td>

            </tr>

        <tr class="form-field form-required">
         <th scope="row" align="right"><b><label for="phase">PDF Title<span class="description">*</span> :</label></b></th>
         <td><input type="text" name="pdf_title" class="validate[required]" style="width:350px;height:30px; " /></td>
        </tr>

        <tr class="form-field form-required">
         <th scope="row" align="right"><b><label for="phase">Site Cost<span class="description">*</span> :</label></b></th>
         <td>
          <input type="number" name="land_area" placeholder="Land area" class="validate[required]" style="width:150px;height:30px; " />
          m<sup>2</sup> &nbsp;&nbsp; 
          <input type="text" name="land_price" placeholder="Land price" class="validate[required]" style="width:158px;height:30px; " />
          <input type="text" name="site_cost" placeholder="Site cost" class="validate[required]" style="width:158px;height:30px; " />
        </td>
         
        </tr>



      <tr class="form-field form-required">
        <th scope="row" align="right"><b><label for="phase">Acknowledgement<span class="description">*</span> :</label></b></th>
        <td><textarea name="acknowledgement" class="validate[required]" style="width:350px;height:65px;"><?php echo $result[0]->description;?></textarea></td>
      </tr>
      
      <?php /*
      <tr class="form-field form-required">
        <th scope="row" align="right"><b><label for="phase">Map Feature Image<span class="description">*</span> :</label></b></th>
        <td><input type="file" class="" name="map_feature_img">
        </td>
      </tr> */ ?>

  

     
    </tbody>
    </table>

      </div>

  </div>


<!-- ######################## -->

  <div class="postbox">
        <span style="display: block;font-size:20px;margin-left:212px;margin-top:15px;height:41px">
          Table Packages
        </span>
    </div>



    <div class="postbox">
      <div class="input_fields_wrap" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;"> 
        <table class="form-table" id="tableid">
        <tbody>

          <tr class="form-field form-required">

          <td></td>
          <td>Build Price<br><input type="text" name="build_price[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>Size(m<sup>2</sup>)<br><input type="text" name="size[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>Bed<br><input type="text" name="bed[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>Bath<br><input type="text" name="bath[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>car<br><input type="text" name="car[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>Storeys<br><input type="text" name="storeys[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>Package Price<br><input type="text" name="package_price[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>

        </tr> 
       </tbody>
     </table>
    <span style="margin-left:80%"><button class="add_field_button button" title="Add more field"> + </button></span><br>
    </div>
  </div>


    <!-- ######################## -->
<!--
    <div class="postbox">
        <span style="display: block;font-size:20px;margin-left:212px;margin-top: 15px;height:41px">
          Building Package
        </span>
    </div>

    <div class="postbox">
      <div class="input_fields_wrap" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;"> 
        <table class="form-table" id="tablebp">
        <tbody>

          <tr class="form-field form-required">

          <td>Package Includes<br><textarea name="package_includes[]" class="validate[required]" value="" style="width:200px;height:30px;"></textarea><br><span style="font-size: 10px;">eg. Tiles,Carpet,AC</span></td>
          <td>Feature Image<br><input type="file" name="feature_image[]" class="validate[required]" value="" style="width:100px;" /></td>
          <td>Building Model<br><input type="file" name="building_model[]" class="validate[required]" value="" style="width:100px;" /></td>
          
          <td>Price<br><input type="text" name="package_price[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>Size(m<sup>2</sup>)<br><input type="text" name="package_size[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>Bed<br><input type="text" name="package_bed[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>Bath<br><input type="text" name="package_bath[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
          <td>car<br><input type="text" name="package_car[]" class="validate[required]" value="" style="width:75px;height:30px; " /></td>
        </tr> 
       </tbody>
     </table>
    <span style="margin-left:80%"><button class="add_field_button_bp button" title="Add more field"> + </button></span><br>
    </div>
  </div>
-->

  <!-- ######################### -->


  <div class="postbox">
        <span style="display: block;font-size:20px;margin-left:212px;margin-top: 15px;height:41px">
          Building Package
        </span>
    </div>

    <div class="postbox">
      <div class="input_fields_wrap" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;"> 
        <table class="form-table" id="tablebp">
        <tbody>

          <tr class="form-field form-required">

          <td>Package Name<br><input type="text" name="building_pakg_name[]" class="validate[required]" style="width:200px;" /></td>
          
          <td>
            Facade<br>
            <!--<input type="file" name="facade[]" class="validate[required]" value="" style="width:200px;" /> -->
            <input type="text" class="regular-text facade_custom_images" id="facade_custom_images_1" name="facade[]" style="width:50px" readonly>
            <button type="button" class="set_custom_images button">Set Image ID</button>
          </td>

          <td>
            Floor Plan<br>
            <!--<input type="file" name="floar_plan[]" class="validate[required]" value="" style="width:200px;" />-->
            <input type="text" class="regular-text floar_custom_images" id="floar_custom_images_1" name="floar_plan[]" style="width:50px" readonly>
            <button type="button" class="set_custom_images button">Set Image ID</button>
          </td>

          <td>
            Specification<br>
            <!--<input type="file" name="specification[]" class="validate[required]" value="" style="width:200px;" />-->
            <input type="text" class="regular-text specification_custom_images" id="specification_custom_images_1" name="specification[]" style="width:50px" readonly>
            <button type="button" class="set_custom_images button">Set Image ID</button>
          </td>
          
          <td>Price<br><input type="text" name="build_package_price[]" class="validate[required]" value="" style="width:90px;height:30px; " /></td>
          
        </tr> 
       </tbody>
     </table>
    <span style="margin-left:80%"><button class="add_field_button_bp button" title="Add more field"> + </button></span><br>
    </div>
  </div>



    <!-- ######################## -->

    <div class="postbox">
        <span style="display: block;font-size:20px;margin-left:212px;margin-top: 15px;height:41px">
          Gallery Image Upload
        </span>
    </div>


    <div class="postbox">
      <div class="" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;height:200px">  

      <!--<input type="file" id="files" name="gallery_img[]" multiple /> -->
      <!--<input type="hidden" name="gallery_img" id="IMGsrc"> -->

      <div style="display:none"> <textarea name="gallery_img" id="IMGsrc"></textarea> </div>
      
      <button type="button" class="button-gallery button">Upload Image</button>
      <div id="IMGdiv"></div>
     </div>

    </div>

    <!-- ######################## -->

  <div class="postbox">
        <span style="display: block;font-size:20px;margin-left:212px;margin-top: 15px;height:41px">
          More Information
        </span>

    </div>
    
    <?php /*
    <div class="postbox">
      <div class="input_fields_wrap_faq" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;">
        <table class="form-table" id="tableid_faq">
        <tbody>
        <tr class="form-field form-required">

          <td></td>
          <td>Question<br><input type="text" name="question[]" class="validate[required]" style="width:350px;height:30px; " /></td>
          <td><textarea name="answer[]" placeholder="Answer" id="answer_1" class="validate[required]" style="width:350px;height:65px;"></textarea></td>

      </tr> 

    </tbody>

    </table>
    <span style="margin-left:80%"><button class="add_field_button_faq button" title="Add more field"> + </button></span><br>
     </div>
    </div>
    */ ?>

    <div class="postbox">
      <div class="input_fields_wrap_faq" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;">
        <table class="form-table" id="tableid_faq">
        <tbody>
        <tr class="form-field form-required">

          <td colspan="2">
            <?php wp_editor_resize('','200'); ?>
            <?php wp_editor($content='', $id = 'faq', $prev_id = 'title', $media_buttons = false, $tab_index = 2); ?>

          </td>
          
        </tr> 

    </tbody>

    </table>
    
     </div>
    </div>

    <input type="hidden" name="project_id" value="">
    <span style="float: right; margin-right: 151px;">
    <input type="submit" class="button" name="save" value="Save" style="width: 162px; height: 53px; background: lightgreen none repeat scroll 0% 0%;"/>
    </span>
   </form>
  </div>

<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('.set_custom_images').click(function(e){
      
      e.preventDefault();
      var button = $(this);
      var id = button.prev();
      //console.log(id);
      wp.media.editor.send.attachment = function(props, attachment) {
          id.val(attachment.url);
      };
      wp.media.editor.open(button);
      return false;
  });
  
})
</script>

<script type="text/javascript">
    var file_frame;

    //jQuery('.button-gallery').live('click', function( event ){
    jQuery('.button-gallery').click(function(event){
        event.preventDefault();

        if ( file_frame ) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media(
            {
                title: 'Select File',
                button: {
                    text: jQuery( this ).data( 'uploader_button_text' )
                },
                multiple: true
            }
        );

        file_frame.on('select', function() {
            attachment = file_frame.state().get('selection').first().toJSON();
            console.log(attachment);
            jQuery('#IMGsrc').append(attachment.url);

            var moresrc= jQuery('#IMGsrc').val();

            jQuery('#IMGsrc').val(attachment.url +","+ moresrc);

            jQuery('#IMGdiv').append('<img src="'+attachment.url+'" class="imageThumb">');
        });

        file_frame.open();
    });
</script>