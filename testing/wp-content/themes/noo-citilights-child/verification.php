 <?php
/*
Template Name: Verification template

*/
?>

<?php get_header() ?>


<?php

$prop_url= $_REQUEST['propurl'];
$prop_explode= explode("/",$prop_url);
$redi_url= "/Properties/".$prop_explode[4]."/";
//print_r($prop_explode);

$token= $_REQUEST['token'];

if(!$token){
  $msg='<div class="alert alert-warning">  
           User Could not be verified. 
         </div>';
}

// get row by token
$user_row= getRowByToken($token);

if(empty($user_row)){
   $msg='<div class="alert alert-warning">  
           User Could not be verified. 
         </div>';
}

// set status

$status=setUserStatus($token);

// user verified and signin
if($status){
    $phone= get_user_meta($user_row->ID, "phone", true);
    
    $info_register = array();
    $info_register['user_login'] = $user_row->user_login;
    $info_register['user_password'] = $phone;
    $user_signon= wp_signon($info_register, false);
    //echo $redi_url;
    if(is_wp_error($user_signon) ){
       $msg='<div class="alert alert-warning">  
           User Could not be verified. 
         </div>';
    }
    
    echo "<script>location.href='".$redi_url."';</script>";

}

?>


<div class="container-boxed  max offset">
<div class="row" style="min-height: 450px;">
<div class="col-md-8">

<div class="content-wrap" style="margin-bottom:30px;">
  <header class="content-header">
   <h1 class="content-title">User Verification</h1>
  </header>

  <div class="content">
     <?php echo $msg ?>
 </div>
</div>

</div>

<div class"col-md-4">
  <?php get_sidebar(); ?>
</div>

</div>
</div>

<?php get_footer() ?>
