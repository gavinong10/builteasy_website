
<script type="text/javascript">


//------------Do login -----------

function LoginLayout(property){
   //jQuery('.modal-dialog').css('width','400px');
   var url= '<?php echo site_url()."".$_SERVER["REQUEST_URI"] ?>';
   
   bootbox.dialog({
    backdrop: true,
    title: "<span id='title-txt'>Log in</span> to see detailed information",
    message: '<div class="row">'+

              '<input type="hidden" name="property" id="property" value="'+property+'">'+
              
              '<div id="login-layout">'+
              
              /*'<div class="col-md-6" style="margin-top: 4px;">'+

              '<div class="wp-social-login-widget">'+
                '<div class="wp-social-login-connect-with">Login with:</div>'+
                '<div class="wp-social-login-provider-list">'+

                  '<a rel="nofollow" href="http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&#038;mode=login&#038;provider=Facebook&#038;redirect_to='+url+' " title="Connect with Facebook" class="wp-social-login-provider wp-social-login-provider-facebook" data-provider="Facebook">'+
                    '<img alt="Facebook" title="Connect with Facebook" src="http://www.builteasy.com.au/wp-content/plugins/wordpress-social-login/assets/img/32x32/wpzoom//facebook.png" style="margin-right:10px"/>'+
                  '</a>'+

                  '<a rel="nofollow" href="http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&#038;mode=login&#038;provider=Google&#038;redirect_to='+url+' " title="Connect with Google" class="wp-social-login-provider wp-social-login-provider-google" data-provider="Google">'+
                    '<img alt="Google" title="Connect with Google" src="http://www.builteasy.com.au/wp-content/plugins/wordpress-social-login/assets/img/32x32/wpzoom//google.png" style="margin-right:10px"/>'+
                  '</a>'+

                  '<a rel="nofollow" href="http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&#038;mode=login&#038;provider=Twitter&#038;redirect_to='+url+'" title="Connect with Twitter" class="wp-social-login-provider wp-social-login-provider-twitter" data-provider="Twitter">'+
                    '<img alt="Twitter" title="Connect with Twitter" src="http://www.builteasy.com.au/wp-content/plugins/wordpress-social-login/assets/img/32x32/wpzoom//twitter.png" style="margin-right:10px"/>'+
                  '</a>'+
                '</div>'+
                '<div class="wp-social-login-widget-clearing"></div>'+
              '</div>'+


              '<div class="col-md-12" style="margin-top: 45px;">'+
                '<span><a href="javascript:void(0)" onclick="show_register()" style="font-weight: bold;">Click here to register</a></span>'+
              '</div>'+

              '</div>'+ */

              
              '<div class="col-md-12">'+

              '<span style="font-weight:bold; padding: 5px 30px 5px 5px; margin-bottom: 10px;">'+
                '<a href="javascript:void(0)" onclick="showforgot()">Forgot Password</a>'+
              '</span>'+
              
              
                '<div id="succ-msg"></div>'+
                '<form id="login-form">'+
                
                '<div class="form-group" style="margin-bottom: 20px; margin-top: 15px;">'+
                   '<span style="color:">Email<sup style="color:red">*</sup></sapn>'+
	               '<input type="email" name="email" id="login-email" class="form-control">'+
	               '<span id="login-email-msg"></span>'+
                '</div>'+

                '<div class="form-group" style="margin-bottom: 20px;">'+
	               '<span style="color:">Phone<sup style="color:red">*</sup></sapn>'+
	               '<input type="password" name="password" id="login-password" class="form-control">'+
                 '<span id="login-password-msg"></span>'+
                '</div>'+

                '<div class="form-group" style="margin-bottom: 30px;">'+
                   '<button type="button" class="login-formsubmt btn btn-lg btn-success" onclick="getLoggedIn()" style="width:100%;">Login</button>'+
                   
                '</div>'+

               /* '<div class="form-group" style="margin-bottom: 30px;">'+
                   '<a href="javascript:void(0)" onclick="showforgot()">Forgot Password</a>'+
                '</div>'+ */

                '</form>'+

                '<div id="logins-message"></div>'+

                '<span><a href="javascript:void(0)" onclick="show_register()" style="font-weight: bold;">Click here to register</a></span>'+

              '</div>'+


              '</div>'+
              // register layout

              '<div id="register-layout" style="display:none">'+
              
              '<div class="col-md-5">'+
               
                '<div class="wp-social-login-widget">'+
                '<div class="wp-social-login-connect-with" style="font-weight: bold; font-size: 18px;margin-left: 10px;">Login with:</div>'+
                '<div class="wp-social-login-provider-list">'+

                  '<a rel="nofollow" href="http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&#038;mode=login&#038;provider=Facebook&#038;redirect_to='+url+' " title="Connect with Facebook" class="wp-social-login-provider wp-social-login-provider-facebook" data-provider="Facebook">'+
                    '<img alt="Facebook" title="Connect with Facebook" src="http://www.builteasy.com.au/wp-content/themes/noo-citilights-child/assets/images/fblogin.png" style="margin-right: 10px; margin-bottom: 10px;"/>'+
                  '</a>'+

                  '<a rel="nofollow" href="http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&#038;mode=login&#038;provider=Google&#038;redirect_to='+url+' " title="Connect with Google" class="wp-social-login-provider wp-social-login-provider-google" data-provider="Google">'+
                    '<img alt="Google" title="Connect with Google" src="http://www.builteasy.com.au/wp-content/themes/noo-citilights-child/assets/images/googlelogin.png" style="margin-right: 10px; margin-bottom: 10px;"/>'+
                  '</a>'+

                  '<a rel="nofollow" href="http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&#038;mode=login&#038;provider=Twitter&#038;redirect_to='+url+'" title="Connect with Twitter" class="wp-social-login-provider wp-social-login-provider-twitter" data-provider="Twitter">'+
                    '<img alt="Twitter" title="Connect with Twitter" src="http://www.builteasy.com.au/wp-content/themes/noo-citilights-child/assets/images/twitterlogin.png" style="margin-right: 10px; margin-bottom: 10px;"/>'+
                  '</a>'+
                '</div>'+
                '<div class="wp-social-login-widget-clearing"></div>'+
              '</div>'+

              /*'<div id=""><a href="javascript:void(0)" onclick="show_login()" style="font-weight: bold; font-size: 13px;">Already Registered? Click here to login</a></div>'+*/

              '</div>'+
              
              '<div class="col-md-7">'+

              '<span style="font-weight:bold; padding: 5px; margin-bottom: 10px;">Or Enter Details Below To Register</span>'+
              
                '<div id="succ-msg"></div>'+
                '<form id="register-form">'+

                '<div class="form-group" style="margin-bottom: 20px; margin-top: 15px;">'+
                   '<span style="color:">First Name<sup style="color:red">*</sup></sapn>'+
                   '<input type="text" name="firstname" id="firstname" class="form-control">'+
                   '<span id="name-msg"></span>'+
                '</div>'+

                '<div class="form-group" style="margin-bottom: 20px;">'+
                   '<span style="color:">Last Name<sup style="color:red">*</sup></sapn>'+
                   '<input type="text" name="lastname" id="lastname" class="form-control">'+
                   '<span id="lastname-msg"></span>'+
                '</div>'+
                
                '<div class="form-group" style="margin-bottom: 20px;">'+
                   '<span style="color:">Email<sup style="color:red">*</sup></sapn>'+
                   '<input type="email" name="email" id="reg-email" class="form-control">'+
                   '<span id="reg-email-msg"></span>'+
                '</div>'+

                '<div class="form-group" style="margin-bottom: 20px;">'+
                   '<span style="color:">Phone<sup style="color:red">*</sup></sapn>'+
                   '<input type="text" name="phone" id="phone" class="form-control">'+
                   '<span id="phone-msg"></span>'+
                '</div>'+

                /*
                '<div class="form-group" style="margin-bottom: 20px;">'+
                   '<span style="color:">Password</sapn>'+
                   '<input type="password" name="password" id="reg-password" class="form-control">'+
                   '<span id="reg-password-msg"></span>'+
                '</div>'+

                '<div class="form-group" style="margin-bottom: 20px;">'+
                   '<span style="color:">Confirm Password</sapn>'+
                   '<input type="password" name="confirm_password" id="reg-confirm_password" class="form-control">'+
                   '<span id="reg-confirm_password-msg"></span>'+
                '</div>'+
                 */

                '<div class="form-group" style="margin-bottom: 30px;">'+
                   '<button type="button" class="reg-formsubmt btn btn-lg btn-success" onclick="get_registered()" style="width:100%;">Register</button>'+
                '</div>'+

                '</form>'+

                '<div id="message"></div>'+

                '<div id=""><a href="javascript:void(0)" onclick="show_login()" style="font-weight: bold; font-size: 13px;">Already Registered? Click here to login</a></div>'+

              '</div>'+

              '</div>'+

              // forgot password

              '<div id="forgot-layout" style="display:none">'+
                '<div class="col-md-12">'+
                   '<p style="font-weight: bold;"></p>'+
                  '<div class="form-group" style="margin-bottom: 20px;">'+
                   '<span style="color:">Please enter your email below</sapn>'+
                   '<input type="email" name="email" id="forgot-email" class="form-control">'+
                   '<span id="forgot-email-msg"></span>'+
                '</div>'+

                '<div class="form-group" style="margin-bottom: 30px;">'+
                   '<button type="button" class="forgot-formsubmt btn btn-lg btn-success" onclick="forgot_password()" style="width:100%;">Submit</button>'+
                '</div>'+

                '<div id="forgot-message"></div>'+

                '<div id=""><a href="javascript:void(0)" onclick="show_login()" style="font-weight: bold; font-size: 13px;">Click here to login</a></div>'+

                '</div>'+
              '</div>'+

              

              '</div>',

      }

    );	

  //jQuery('.modal-dialog').css('width','30%');
  show_register();
}


function show_register(){
  jQuery('#login-layout').css('display','none');
  jQuery('#register-layout').css('display','block');
  jQuery('#title-txt').html('Register');
}


function show_login(){
  jQuery('#login-layout').css('display','block');
  jQuery('#register-layout').css('display','none');
  jQuery('#forgot-layout').css('display','none');
  jQuery('#title-txt').html('Log in');
}

function showforgot(){
  jQuery('#forgot-layout').css('display','block');
  jQuery('#login-layout').css('display','none');
}



function get_registered(){
   var email= jQuery('#reg-email').val();
   var firstname= jQuery('#firstname').val();
   var lastname= jQuery('#lastname').val();
   var phone= jQuery('#phone').val();
   
   var property= jQuery('#property').val();

   var prop_url= '<?php echo site_url()."".$_SERVER["REQUEST_URI"] ?>';


   jQuery('#message').html('');
   
   if(firstname=='' || lastname=='' || phone==''){
     jQuery('#message').html('<div class="alert alert-warning">Please complete all fields</div>');
     return false;
  }

  
	if(email==''){
		//alert(jQuery('#email').val());
    jQuery('#message').html('<div class="alert alert-warning">Please complete all fields</div>');
    return false;

	}else{
		var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if(!email.match(mailformat)){
            
       jQuery('#message').html('<div class="alert alert-warning">Please Enter Valid Email</div>');
       return false;
		}

	}


	  jQuery('.reg-formsubmt').html("Please Wait...");
    jQuery('.reg-formsubmt').attr("disabled",true);
    
    var task='register';

    jQuery.ajax({
		type: "POST",
		url: "/wp-admin/admin-ajax.php",
		data: {action:'get_sign_on',
		       firstname:firstname,
           lastname:lastname,
		       email:email,
		       phone:phone,
           property:property,
           prop_url:prop_url,
		       task:task
		   },
		success: function(data){
		       document.getElementById('message').innerHTML = data;
           jQuery('#message').slideDown('slow');
        	
		       if(data.match('successfully') != null) { 
              document.getElementById('register-form').reset();
              //location.reload();
            }

            jQuery('.reg-formsubmt').html("Register");
            jQuery('.reg-formsubmt').attr("disabled",false);
		   
		}     

	});

}

//------------ Login ----------------

function getLoggedIn(){

   var email= jQuery('#login-email').val();
   var password= jQuery('#login-password').val();

   var property= jQuery('#property').val();
   
   jQuery('#logins-message').html('');

   if(email==''){
      jQuery('#logins-message').html('<div class="alert alert-warning">Please complete all fields</div>');
      return false;

  }else{
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(!email.match(mailformat)){
            
       jQuery('#logins-message').html('<div class="alert alert-warning">Please Enter Valid Email</div>');
       return false;
    }

  }

  if(password==''){ 
    jQuery('#logins-message').html('<div class="alert alert-warning">Please complete all fields</div>');
    return false;
  }

  jQuery('.login-formsubmt').html("Please Wait...");
  jQuery('.login-formsubmt').attr("disabled",true);

  jQuery.ajax({
    type: "POST",
    url: "/wp-admin/admin-ajax.php",
    data: {action:'get_login_in',
           email:email,
           password:password,
           property:property
           
       },
      success: function(data){
          var obj = jQuery.parseJSON(data);
          //alert(obj.loggedin);
          if (obj.loggedin== true){

             location.reload();
          }else{
            jQuery('.login-formsubmt').html("Login");
            jQuery('.login-formsubmt').attr("disabled",false);

            jQuery('#logins-message').html('<div class="alert alert-warning">'+obj.message+'</div>');
          }
       
    }     

  });


}


function forgot_password(){
  var email= jQuery('#forgot-email').val();
  if(email==''){
      jQuery('#forgot-email-msg').css('color','red');    
      jQuery('#forgot-email-msg').text("Please enter email");
      return false;

  }else{
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if(!email.match(mailformat)){
            jQuery('#forgot-email-msg').css('color','red');    
            jQuery('#forgot-email-msg').text("Please enter valid email");
            return false;
    }

  }

  jQuery('.forgot-formsubmt').html("Please Wait...");
  jQuery('.forgot-formsubmt').attr("disabled",true);

  jQuery.ajax({
    type: "POST",
    url: "/wp-admin/admin-ajax.php",
    data: {action:'forgot_password',
           email:email
           
       },
      success: function(data){
          document.getElementById('forgot-message').innerHTML = data;
           jQuery('#forgot-message').slideDown('slow');
          
           jQuery('.forgot-formsubmt').html("Register");
           jQuery('.forgot-formsubmt').attr("disabled",false);
       
    }     

  });

}


jQuery(document).ready(function(){
  var pre_url= '<?php echo $_SERVER["HTTP_REFERER"]; ?>';

  var url= window.location.href;
  var Fcurrnet_url= 'http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&mode=login&provider=Facebook&redirect_to='+url+'&redirect_to_provider=true';

  var Gcurrnet_url= 'http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&mode=login&provider=Google&redirect_to='+url+'&redirect_to_provider=true';

  var Tcurrnet_url= 'http://www.builteasy.com.au/wp-login.php?action=wordpress_social_authenticate&mode=login&provider=Twitter&redirect_to='+url+'&redirect_to_provider=true';

  
  if(pre_url==Fcurrnet_url || pre_url==Gcurrnet_url || pre_url==Tcurrnet_url){
    //alert('hello');
        jQuery('html, body').animate({scrollTop: '+=1490px'}, 800);
        /*
        jQuery('html, body').animate({
            scrollTop: jQuery("#target-element").offset().top
        }, 2000); */
    
        send_notification_social();
  }
});


function send_notification_social(){

fbq('track', 'CompleteRegistration');
  
   var url= window.location.href;
   var property_split= url.split('/');
   var property= property_split[property_split.length-2];
   
   jQuery.ajax({
    type: "POST",
    url: "/wp-admin/admin-ajax.php",
    data: {action:'send_notification_login_via_social',
           property:property
           
       },
      success: function(data){
          
       
    }     

  });

}


function sendNotification_Property_Access(property){

  fbq('track', 'Lead');

   jQuery.ajax({
    type: "POST",
    url: "/wp-admin/admin-ajax.php",
    data: {action:'send_notification_for_property_access',
           property:property
           
       },
      success: function(data){
          
       
    }     

  });

}



function manageslider_ht(){
  
  //jQuery('.amazingslider-space-1').css('height','340px');
  manageslider_ht102();
  manageslider_ht103();
  manageslider_ht104();
  manageslider_ht105();

  manageslider_ht106();
  manageslider_ht107();
  manageslider_ht108();
  manageslider_ht109();
  manageslider_ht110();
  manageslider_ht111();
  
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-101').css('height','540');

    jQuery('.amazingslider-nav-container-101').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-101').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-101').css('height','133px');

    

    jQuery('.amazingslider-bullet-image-101').css('height','56px');
    jQuery('.amazingslider-bullet-image-101').css('width','120px');

    jQuery('.amazingslider-nav-container-101').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-101').css('margin-right','75px');
  }
  
}


function manageslider_ht102(){
  
  //jQuery('.amazingslider-space-1').css('height','340px');
  
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-102').css('height','540');

    jQuery('.amazingslider-nav-container-102').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-102').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-102').css('height','133px');

    

    jQuery('.amazingslider-bullet-image-102').css('height','56px');
    jQuery('.amazingslider-bullet-image-102').css('width','120px');

    jQuery('.amazingslider-nav-container-102').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-102').css('margin-right','75px');
  }
  
}


function manageslider_ht103(){
  
  //jQuery('.amazingslider-space-1').css('height','340px');
  
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-103').css('height','540');

    jQuery('.amazingslider-nav-container-103').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-103').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-103').css('height','133px');

    

    jQuery('.amazingslider-bullet-image-103').css('height','56px');
    jQuery('.amazingslider-bullet-image-103').css('width','120px');

    jQuery('.amazingslider-nav-container-103').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-103').css('margin-right','75px');
  }
   
}


function manageslider_ht104(){
  
  //jQuery('.amazingslider-space-1').css('height','340px');
  
  var wid =jQuery(window).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-104').css('height','540');

    jQuery('.amazingslider-nav-container-104').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-104').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-104').css('height','133px');

    jQuery('.amazingslider-bullet-image-104').css('height','56px');
    jQuery('.amazingslider-bullet-image-104').css('width','120px');

    jQuery('.amazingslider-nav-container-104').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-104').css('margin-right','75px');
  }
   
}

function manageslider_ht105(){
  //jQuery('.amazingslider-space-1').css('height','340px');
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-105').css('height','540');

    jQuery('.amazingslider-nav-container-105').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-105').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-105').css('height','133px');

    jQuery('.amazingslider-bullet-image-105').css('height','56px');
    jQuery('.amazingslider-bullet-image-105').css('width','120px');

    jQuery('.amazingslider-nav-container-105').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-105').css('margin-right','75px');
  }
   
}

function manageslider_ht106(){
  //jQuery('.amazingslider-space-1').css('height','340px');
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-106').css('height','540');

    jQuery('.amazingslider-nav-container-106').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-106').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-106').css('height','133px');

    jQuery('.amazingslider-bullet-image-106').css('height','56px');
    jQuery('.amazingslider-bullet-image-106').css('width','120px');

    jQuery('.amazingslider-nav-container-106').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-106').css('margin-right','75px');
  }
   
}

function manageslider_ht107(){
  //jQuery('.amazingslider-space-1').css('height','340px');
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-107').css('height','540');

    jQuery('.amazingslider-nav-container-107').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-107').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-107').css('height','133px');

    jQuery('.amazingslider-bullet-image-107').css('height','56px');
    jQuery('.amazingslider-bullet-image-107').css('width','120px');

    jQuery('.amazingslider-nav-container-107').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-107').css('margin-right','75px');
  }
   
}


function manageslider_ht108(){
  //jQuery('.amazingslider-space-1').css('height','340px');
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-108').css('height','540');

    jQuery('.amazingslider-nav-container-108').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-108').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-108').css('height','133px');

    jQuery('.amazingslider-bullet-image-108').css('height','56px');
    jQuery('.amazingslider-bullet-image-108').css('width','120px');

    jQuery('.amazingslider-nav-container-108').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-108').css('margin-right','75px');
  }
   
}


function manageslider_ht109(){
  //jQuery('.amazingslider-space-1').css('height','340px');
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-109').css('height','540');

    jQuery('.amazingslider-nav-container-109').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-109').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-109').css('height','133px');

    jQuery('.amazingslider-bullet-image-109').css('height','56px');
    jQuery('.amazingslider-bullet-image-109').css('width','120px');

    jQuery('.amazingslider-nav-container-109').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-109').css('margin-right','75px');
  }
   
}

function manageslider_ht110(){
  //jQuery('.amazingslider-space-1').css('height','340px');
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-110').css('height','540');

    jQuery('.amazingslider-nav-container-110').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-110').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-110').css('height','133px');

    jQuery('.amazingslider-bullet-image-110').css('height','56px');
    jQuery('.amazingslider-bullet-image-110').css('width','120px');

    jQuery('.amazingslider-nav-container-110').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-110').css('margin-right','75px');
  }
   
}

function manageslider_ht111(){
  //jQuery('.amazingslider-space-1').css('height','340px');
  var wid =jQuery( window ).width();
  //alert(wid);
  if(wid>360){
    // desktop view
    jQuery('.amazingslider-space-111').css('height','540');

    jQuery('.amazingslider-nav-container-111').css('margin-left','47px');
    jQuery('.amazingslider-nav-container-111').css('margin-right','47px');

  }else{
    // mobile view
    jQuery('.amazingslider-space-111').css('height','133px');

    jQuery('.amazingslider-bullet-image-111').css('height','56px');
    jQuery('.amazingslider-bullet-image-111').css('width','120px');

    jQuery('.amazingslider-nav-container-111').css('margin-left','80px');
    jQuery('.amazingslider-nav-container-111').css('margin-right','75px');
  }
   
}


jQuery(document).ready(function(){
 manageslider_ht();
 manageslider_ht102();
 manageslider_ht103();
 manageslider_ht104();
 manageslider_ht105();

 manageslider_ht106();
 manageslider_ht107();
 manageslider_ht108();
 manageslider_ht109();
 manageslider_ht110();
 manageslider_ht111();
});


//var imported = document.createElement('script');
//imported.src = '<?php echo get_template_directory_uri() ?>/assets/js/sliderengine/amazingslider.js';
//document.head.appendChild(imported);



</script>