<?php

class ProjectManager_func

{


function addPdfData($post,$FILES){

  global $wpdb;
  
  $sql="INSERT {$wpdb->prefix}property_pdf SET 

	             pdf_title='".$post['pdf_title']."',
               land_area='".$post['land_area']."',
               land_price='".$post['land_price']."',
               site_cost='".$post['site_cost']."',
	             acknowledgement='".$post['acknowledgement']."',
               faq='".$post['faq']."',
	             
	             created='".date('Y-m-d')."'";
  
  $wpdb->query($sql);


 //-----------------------

  $LastId= ProjectManager_func::getProjectPdfLastId();
  // insert in lookup
  ProjectManager_func::setLookupData($post,$LastId);

  // save package
  ProjectManager_func::savePackageData($post,$LastId);
  // save faq
  //ProjectManager_func::saveFaqData($post,$LastId);
  // save building package
  ProjectManager_func::saveBuildingPackage($post,$LastId);
  // save building package
  ProjectManager_func::saveGalleryImage($post,$LastId);

  return true;
}


function setLookupData($post,$LastId){
   global $wpdb;
   
   $properties= $post['property'];
   foreach($properties as $key=>$val){
   	  $sql="INSERT {$wpdb->prefix}pdf_property_lookup SET 
	             property_pdf_id='".$LastId."',
	             property_id='".$val."',
	            
	             created='".date('Y-m-d')."'";

      $wpdb->query($sql);
   }

   
}



function savePackageData($post,$LastId){

   global $wpdb;

   $build_price_arr= $post['build_price'];
   $size_arr= $post['size'];
   $bed_arr= $post['bed'];
   $bath_arr= $post['bath'];
   $car_arr= $post['car'];
   $storeys_arr= $post['storeys'];
   $package_price_arr= $post['package_price'];

   $counter= count($build_price_arr);
   $arr1= array();
   $finalarr= array();

   for($i=0; $i<=$counter-1; $i++){
      $arr1['build_price']= $build_price_arr[$i];
      $arr1['size']= $size_arr[$i];
      $arr1['bed']= $bed_arr[$i];
      $arr1['bath']= $bath_arr[$i];
      $arr1['car']= $car_arr[$i];
      $arr1['storeys']= $storeys_arr[$i];
      $arr1['package_price']= $package_price_arr[$i];
      $finalarr[]= $arr1;
    }



    foreach($finalarr as $arr){



	  	 $sql="INSERT {$wpdb->prefix}pdf_packages SET 

	             property_pdf_id='".$LastId."',
	             build_price='".$arr['build_price']."',
	             size='".$arr['size']."',
	             bed='".$arr['bed']."',
	             bath='".$arr['bath']."',
	             car='".$arr['car']."',
	             storeys='".$arr['storeys']."',
	             package_price='".$arr['package_price']."',
	             created='".date('Y-m-d')."'";



	    $wpdb->query($sql);

	  }





}



function saveFaqData($post,$LastId){

   global $wpdb;

   $question_arr= $post['question'];
   $answer_arr= $post['answer'];

   $counter= count($question_arr);
   $arr1= array();
   $finalarr= array();



   for($i=0; $i<=$counter-1; $i++){

      $arr1['question']= $question_arr[$i];

      $arr1['answer']= $answer_arr[$i];

      

      $finalarr[]= $arr1;

    }



    foreach($finalarr as $arr){



	  	 $sql="INSERT {$wpdb->prefix}faqs SET 

	             property_pdf_id='".$LastId."',
	             question='".$arr['question']."',
	             answer='".$arr['answer']."',
	             created='".date('Y-m-d')."'";

	    $wpdb->query($sql);

	  }



}



function saveBuildingPackage($post,$LastId){
  global $wpdb;

  $pkg_name_arr= $post['building_pakg_name'];
  $i=0;
  foreach($pkg_name_arr as $arr){

      $sql="INSERT {$wpdb->prefix}pdf_building_package SET 
               property_pdf_id='".$LastId."',
               package_name='".$post['building_pakg_name'][$i]."',
               package_price='".$post['build_package_price'][$i]."',
               facade='".$post['facade'][$i]."',
               floar_plan='".$post['floar_plan'][$i]."',
               specification='".$post['specification'][$i]."',
               created='".date('Y-m-d')."'";


      $wpdb->query($sql);

      $i++;
  }

}


/*
function saveBuildingPackage($FILES,$post,$LastId){

  global $wpdb;

  $facade_name_arr= $FILES['facade']['name'];
  $facade_type_arr=$FILES['facade']['type'];
  $facade_tmp_arr=$FILES['facade']['tmp_name'];
  
  $floar_plan_name_arr= $FILES['floar_plan']['name'];
  $floar_plan_type_arr=$FILES['floar_plan']['type'];
  $floar_plan_tmp_arr=$FILES['floar_plan']['tmp_name'];

  $specification_name_arr= $FILES['specification']['name'];
  $specification_type_arr=$FILES['specification']['type'];
  $specification_tmp_arr=$FILES['specification']['tmp_name'];
  
  $package_price_arr= $post['build_package_price'];

  $package_name_arr= $post['building_pakg_name'];

  //print_r($feature_img_arr);

  $i=0;
    foreach($facade_name_arr as $arr){
      
      // upload fcade image
       $date = new DateTime();
       $tmstmp=$date->getTimestamp();
       $filename= $tmstmp.$facade_name_arr[$i];
       $pathfilename =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/noo-citilights/doc/".$filename;

       move_uploaded_file($facade_tmp_arr[$i],$pathfilename);

       // upload floar plan image

       $date = new DateTime();
       $tmstmp=$date->getTimestamp();
       $filename_floar_plan= $tmstmp.$floar_plan_name_arr[$i];
       $pathfilename_floar_plan =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/noo-citilights/doc/".$filename_floar_plan;

       move_uploaded_file($floar_plan_tmp_arr[$i],$pathfilename_floar_plan);

       // upload spacification image

       $date = new DateTime();
       $tmstmp=$date->getTimestamp();
       $filename_specification= $tmstmp.$specification_name_arr[$i];
       $pathfilename_specification =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/noo-citilights/doc/".$filename_specification;

       move_uploaded_file($specification_tmp_arr[$i],$pathfilename_specification);


       $sql="INSERT {$wpdb->prefix}pdf_building_package SET 
               property_pdf_id='".$LastId."',
               package_name='".$package_name_arr[$i]."',
               package_price='".$package_price_arr[$i]."',
               facade='".$filename."',
               floar_plan='".$filename_floar_plan."',
               specification='".$filename_specification."',
               created='".date('Y-m-d')."'";


      $wpdb->query($sql);

      $i++;
    }


}
*/


function saveGalleryImage($post,$LastId){
  global $wpdb;
  
  $gallarystr= $post['gallery_img'];

  $gall_explode= explode(",", $gallarystr);
  
  foreach($gall_explode as $key=>$val){
      if($val){
         $sql="INSERT {$wpdb->prefix}pdf_gallery_images SET 
                 property_pdf_id='".$LastId."',
                 gallery_image='".$val."',
                 created='".date('Y-m-d')."'";


         $wpdb->query($sql);
      }
    
  }

  

}



/*
function saveGalleryImage($FILES,$post,$LastId){
  global $wpdb;

  $gallery_img_name_arr= $FILES['gallery_img']['name'];
  $gallery_img_type_arr=$FILES['gallery_img']['type'];
  $gallery_img_tmp_arr=$FILES['gallery_img']['tmp_name'];
  
  $counter= count($gallery_img_name_arr);
  $arr1= array();
  $finalarr= array();

  for($i=0; $i<=$counter-1; $i++){
      $arr1['gallery_name']= $gallery_img_name_arr[$i];
      $arr1['gallery_type']= $gallery_img_type_arr[$i];
      $arr1['gallery_tmp_name']= $gallery_img_tmp_arr[$i];

      $finalarr[]= $arr1;

    }

    

   foreach($finalarr as $arr){
       // upload feature image
       $date = new DateTime();
       $tmstmp=$date->getTimestamp();
       $filename= $tmstmp.$arr['gallery_name'];
       $pathfilename =  $_SERVER['DOCUMENT_ROOT']."/wp-content/themes/noo-citilights/doc/".$filename;

       move_uploaded_file($arr['gallery_tmp_name'],$pathfilename);

       $sql="INSERT {$wpdb->prefix}pdf_gallery_images SET 
               property_pdf_id='".$LastId."',
               gallery_image='".$filename."',
               created='".date('Y-m-d')."'";


      $wpdb->query($sql);
   }



}*/


function getPropertyPdfById($id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}property_pdf where id='".$id."' ";  
  $result=$wpdb->get_row($sql);     
  return $result;
}


function getTablePackages($id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}pdf_packages where property_pdf_id='".$id."' ";  
  $result=$wpdb->get_results($sql);     
  return $result;
}


function getBuildingPackages($id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}pdf_building_package where property_pdf_id='".$id."' ";  
  $result=$wpdb->get_results($sql);     
  return $result;
}


function getPdfGallery($id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}pdf_gallery_images where property_pdf_id='".$id."' ";  
  $result=$wpdb->get_results($sql);     
  return $result;
}


function getFaqs($id){
  global $wpdb;
  $sql="SELECT * FROM {$wpdb->prefix}faqs where property_pdf_id='".$id."' ORDER BY {$wpdb->prefix}faqs.id ASC ";  
  $result=$wpdb->get_results($sql);     
  return $result;
}


function getProject($id){
	global $wpdb;
	$sql="SELECT {$wpdb->prefix}project.*,{$wpdb->prefix}unit_details.* FROM {$wpdb->prefix}project LEFT JOIN {$wpdb->prefix}unit_details ON({$wpdb->prefix}project.id={$wpdb->prefix}unit_details.project_id) where {$wpdb->prefix}project.id='".$id."' ORDER BY {$wpdb->prefix}unit_details.id ASC ";	
	$result=$wpdb->get_results($sql);			
	return $result;

}





function getProjects(){
	global $wpdb;
	$sql="SELECT * FROM {$wpdb->prefix}projects where 1";	
	$result=$wpdb->get_results($sql);			
	return $result;
}





function getProjectPdfLastId(){

   global $wpdb;
   $sql="SELECT id FROM {$wpdb->prefix}property_pdf ORDER BY id DESC limit 1";	
   $result=$wpdb->get_var($sql);			
   return $result;	

}	

	
function getPropertyIdsFromLookup($pdf_id){
   global $wpdb;
   $sql="SELECT property_id FROM {$wpdb->prefix}pdf_property_lookup where property_pdf_id='".$pdf_id."' ";  
   $results=$wpdb->get_results($sql);
   
   $finalarr= array();
   foreach($results as $result){
      $finalarr[]= $result->property_id;
   }   
   
   return $finalarr;  
}


function updatePdfData($post,$FILES){
  global $wpdb;

  //echo "<pre>";
  //print_r($post);
  //exit;
  

  $sql="UPDATE {$wpdb->prefix}property_pdf SET 

               pdf_title='".$post['pdf_title']."',
               land_area='".$post['land_area']."',
               land_price='".$post['land_price']."',
               site_cost='".$post['site_cost']."',
               acknowledgement='".$post['acknowledgement']."',
               faq='".$post['faq']."' 
               where id='".$post['pdf_id']."'";

  $wpdb->query($sql);

  
  $LastId=$post['pdf_id'];

  // insert in lookup
  ProjectManager_func::deleteByPdfId("pdf_property_lookup",$LastId);
  ProjectManager_func::setLookupData($post,$LastId);

  // save package
  ProjectManager_func::deleteByPdfId("pdf_packages",$LastId);
  ProjectManager_func::savePackageData($post,$LastId);
  
  // save gallary package
  ProjectManager_func::saveGalleryImage($post,$LastId);

  // save building package
  ProjectManager_func::deleteByPdfId("pdf_building_package",$LastId);
  ProjectManager_func::saveBuildingPackage($post,$LastId);
  


  return true;

}


function deleteByPdfId($table,$pdfId){
   global $wpdb;

   $sql="DELETE FROM {$wpdb->prefix}".$table." WHERE property_pdf_id='".$pdfId."' ";
   $wpdb->query($sql);

}


function deletePdfById($pdfId){
  global $wpdb;
  // delete from property_pdf
  $sql="DELETE FROM {$wpdb->prefix}property_pdf WHERE id='".$pdfId."' ";
  $wpdb->query($sql);

  ProjectManager_func::deleteByPdfId("pdf_property_lookup",$pdfId);
  ProjectManager_func::deleteByPdfId("pdf_packages",$pdfId);
  ProjectManager_func::deleteByPdfId("pdf_building_package",$pdfId);
  ProjectManager_func::deleteByPdfId('pdf_gallery_images',$pdfId);

  return true;

}


function delGalleryImg($pdf_id,$gall_id){
  global $wpdb;
  $sql="DELETE FROM {$wpdb->prefix}pdf_gallery_images WHERE id='".$gall_id."' AND property_pdf_id='".$pdf_id."' ";
  $wpdb->query($sql);
  
  return true;
}


}

?>

