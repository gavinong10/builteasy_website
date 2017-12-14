<?php ob_start();

include_once("inc/ProjectManager_func.php");
$obj= new ProjectManager_func();


if(isset($_REQUEST['save']))  /*If Project is Saved*/

{	 

   //echo "<pre>";
   //print_r($_REQUEST);
   //exit;
    
   $return=$obj->updatePdfData($_REQUEST,$_FILES);

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
  <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__).'js/nicEdit.js' ?>"></script>



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
                  
                  '<td>Pakage Name<br><input type="text" name="building_pakg_name[]" class="validate[required]" style="width:200px;" /></td>'+
                  '<td>Facade<br><input type="file" name="facade[]" class="validate[required]" value="" style="width:200px;" /></td>'+
                  '<td>Floar Plan<br><input type="file" name="floar_plan[]" class="validate[required]" value="" style="width:200px;" /></td>'+
                  '<td>Specification<br><input type="file" name="specification[]" class="validate[required]" value="" style="width:200px;" /></td>'+
                
                  '<td>Price<br><input type="text" name="build_package_price[]" class="validate[required]" value="" style="width:90px;height:30px; " /></td>'+

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



//------------------Faqs---------------------------



$(document).ready(function() {

    var max_fields      = 1000; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap_faq"); //Fields wrapper
    var add_button      = $(".add_field_button_faq"); //Add button ID

    var x = 100; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $('#tableid_faq').append('<tr class="form-field form-required">'+
									'<td></td>'+
									'<td>Question<br><input type="text" name="question[]" class="validate[required]" value="" style="width:350px;height:30px; "/></td>'+
				          '<td><textarea name="answer[]" id="answer_'+x+'" placeholder="Answer" class="validate[required]" style="width:350px;height:65px;"></textarea></td>'+
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


<?php $result=$obj->getPropertyPdfById($_REQUEST['id']); ?>
	

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

                     // get lookup
                     $property_ids=$obj->getPropertyIdsFromLookup($_REQUEST['id']); 
                     
                     ?>

                    <select name="property[]" class="chosen-select" multiple style="width:350px;height:65px;">
                    <?php while($wp_query->have_posts()) : ?>
                    <?php $wp_query->the_post();  ?>
                     <option value="<?php echo $wp_query->post->ID  ?>" <?php echo (in_array($wp_query->post->ID, $property_ids)) ? 'selected' : '' ?> ><?php echo the_title();?></option>
                    <?php endwhile; ?>
                    
                    </select>

                </td>

            </tr>

        <tr class="form-field form-required">
				 <th scope="row" align="right"><b><label for="phase">PDF Title<span class="description">*</span> :</label></b></th>
				 <td><input type="text" name="pdf_title" class="validate[required]" value="<?php echo $result->pdf_title;?>" style="width:350px;height:30px; " /></td>
			  </tr>

        <tr class="form-field form-required">
         <th scope="row" align="right"><b><label for="phase">Site Cost<span class="description">*</span> :</label></b></th>
         <td>
          <input type="number" name="site_area" value="<?php echo $result->site_area;?>" placeholder="Site area" class="validate[required]" style="width:150px;height:30px; " />
          m<sup>2</sup> &nbsp;&nbsp; 
          <input type="text" name="site_cost" value="<?php echo $result->site_cost;?>" placeholder="Site cost" class="validate[required]" style="width:158px;height:30px; " />
        </td>
         
        </tr>



			<tr class="form-field form-required">
				<th scope="row" align="right"><b><label for="phase">Acknowledgement<span class="description">*</span> :</label></b></th>
				<td><textarea name="acknowledgement" class="validate[required]" style="width:350px;height:65px;"><?php echo $result->acknowledgement;?></textarea></td>
			</tr>
      
      <?php /*
			<tr class="form-field form-required">
				<th scope="row" align="right"><b><label for="phase">Map Feature Image<span class="description">*</span> :</label></b></th>
				<td><input type="file" class="" name="map_feature_img">
				 <span><img src="<?php echo site_url() ?>/wp-content/themes/noo-citilights/doc/<?php echo $result->map_feature_img ?>" style="width:80px"></span>
				</td>

			</tr>
      */ ?>
		</tbody>
	  </table>

      </div>

	</div>


<!-- ######################## -->

<?php $packages=$obj->getTablePackages($_REQUEST['id']);
//echo "<pre>";
//print_r($packages);
 ?>

	<div class="postbox">
    <span style="display: block;font-size:20px;margin-left:212px;margin-top:15px;height:41px">
    	Table Packages
    </span>
  </div>



    <div class="postbox">
    	<div class="input_fields_wrap" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;">	
        <table class="form-table" id="tableid">
		    <tbody>

        <?php foreach($packages as $package){ ?>
          <tr class="form-field form-required">
          <td></td>  
          <td>Build Price<br><input type="text" name="build_price[]" class="validate[required]" value="<?php echo $package->build_price ?>" style="width:75px;height:30px; " /></td>
          <td>Size(m<sup>2</sup>)<br><input type="text" name="size[]" class="validate[required]" value="<?php echo $package->size ?>" style="width:75px;height:30px; " /></td>
          <td>Bed<br><input type="text" name="bed[]" class="validate[required]" value="<?php echo $package->bed ?>" style="width:75px;height:30px; " /></td>
          <td>Bath<br><input type="text" name="bath[]" class="validate[required]" value="<?php echo $package->bath ?>" style="width:75px;height:30px; " /></td>
          <td>car<br><input type="text" name="car[]" class="validate[required]" value="<?php echo $package->car ?>" style="width:75px;height:30px; " /></td>
          <td>Storeys<br><input type="text" name="storeys[]" class="validate[required]" value="<?php echo $package->storeys ?>" style="width:75px;height:30px; " /></td>
          <td>Package Price<br><input type="text" name="package_price[]" class="validate[required]" value="<?php echo $package->package_price ?>" style="width:75px;height:30px; " /></td>
          </tr>
          <?php } ?> 
		   </tbody>
	   </table>
	  <span style="margin-left:80%"><button class="add_field_button button" title="Add more field"> + </button></span><br>
    </div>
  </div>


    <!-- ######################## -->

<?php $build_packages=$obj->getBuildingPackages($_REQUEST['id']);
//echo "<pre>";
//print_r($build_packages);
 ?>

    <div class="postbox">
        <span style="display: block;font-size:20px;margin-left:212px;margin-top: 15px;height:41px">
          Building Package
        </span>
    </div>

    <div class="postbox">
      <div class="input_fields_wrap" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;"> 
        <table class="form-table" id="tablebp">
        <tbody>
          <?php foreach($build_packages as $bpkg){ ?>
          <tr class="form-field form-required">
          
          <td>Pakage Name<br><input type="text" name="building_pakg_name[]" value="<?php echo $bpkg->package_name ?>" class="validate[required]" style="width:200px;" /></td>

          <td>Fcade<br><input type="file" name="facade[]" class="" style="width:200px;" />
            <span><img src="<?php echo site_url() ?>/wp-content/themes/noo-citilights/doc/<?php echo $bpkg->facade ?>" style="width:80px"></span>
          </td>
          <td>Floar Plan<br><input type="file" name="floar_plan[]" class="" style="width:200px;" />
            <img src="<?php echo site_url() ?>/wp-content/themes/noo-citilights/doc/<?php echo $bpkg->floar_plan ?>" style="width:80px">
          </td>
          <td>Specification<br><input type="file" name="specification[]" class="" style="width:200px;" />
            <img src="<?php echo site_url() ?>/wp-content/themes/noo-citilights/doc/<?php echo $bpkg->specification ?>" style="width:80px">
          </td>
          
          <td>Price<br><input type="text" name="build_package_price[]" class="validate[required]" value="<?php echo $bpkg->package_price ?>" style="width:75px;height:30px; " /></td>
          
        </tr> 
        <?php } ?>
       </tbody>
     </table>
    <span style="margin-left:80%"><button class="add_field_button_bp button" title="Add more field"> + </button></span><br>
    </div>
  </div>





    <!-- ######################## -->

<?php $galleries=$obj->getPdfGallery($_REQUEST['id']);
//echo "<pre>";
//print_r($galleries);
 ?>

	  <div class="postbox">
        <span style="display: block;font-size:20px;margin-left:212px;margin-top: 15px;height:41px">
        	Gallery Image Upload 
        </span>
    </div>


    <div class="postbox">
      <div class="" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;height:200px">	

	    <input type="file" id="files" name="gallery_img[]" multiple />

      <?php foreach($galleries as $gall){ ?>
      <img src="<?php echo site_url() ?>/wp-content/themes/noo-citilights/doc/<?php echo $gall->gallery_image ?>" class="imageThumb">
      <?php } ?>

     </div>

    </div>

    <!-- ######################## -->

<?php $faqs=$obj->getFaqs($_REQUEST['id']);
//echo "<pre>";
//print_r($faqs);
 ?>

	<div class="postbox">
        <span style="display: block;font-size:20px;margin-left:212px;margin-top: 15px;height:41px">
        	FAQs
        </span>

    </div>

    <div class="postbox">
    	<div class="input_fields_wrap_faq" style="padding-bottom: 10px;padding-left: 10px;padding-right: 10px;padding-top: 0;">
        <table class="form-table" id="tableid_faq">
		    <tbody>
          <?php $i=1; foreach($faqs as $faq){ ?>
          <tr class="form-field form-required">

    				<td></td>
    				<td>Question<br><input type="text" name="question[]" value="<?php echo $faq->question ?>" class="validate[required]" style="width:350px;height:30px; " /></td>
    				<td><textarea name="answer[]" placeholder="Answer" id="answer_<?php echo $i ?>" class="validate[required]" style="width:350px;height:65px;"><?php echo $faq->answer ?></textarea></td>
            
            <script type="text/javascript">
              bkLib.onDomLoaded(function() {
               new nicEditor().panelInstance('answer_<?php echo $i ?>'); 
             });
            </script>
  			</tr>
        <?php $i++; } ?> 
		</tbody>

	  </table>
	  <span style="margin-left:80%"><button class="add_field_button_faq button" title="Add more field"> + </button></span><br>
     </div>

    </div>
    <input type="hidden" name="pdf_id" value="<?php echo $_REQUEST['id']; ?>">
	  <span style="float: right; margin-right: 206px;">
	  <input type="submit" class="button" name="save" value="Update" style="width: 162px; height: 53px; background: lightgreen none repeat scroll 0% 0%;"/>
	  </span>
   </form>
	</div>

<?php ob_flush(); exit;?>