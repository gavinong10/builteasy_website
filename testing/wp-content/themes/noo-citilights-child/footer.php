<?php session_start(); ?>

<?php //noo_get_layout( 'footer', 'widgetized' ); ?>

<?php

	$image_logo = noo_get_image_option( 'noo_bottom_bar_logo_image', '' );

	$noo_bottom_bar_content = noo_get_option( 'noo_bottom_bar_content', '' );

?>

<?php if ( $image_logo || $noo_bottom_bar_content ) : ?>

	<footer class="colophon site-info" role="contentinfo">

		<div class="container-full">

			<?php if ( $noo_bottom_bar_content != '' || $noo_bottom_bar_social ) : ?>

			<div class="footer-more">

				<div class="container-boxed max">

					<div class="row">

						<div class="col-md-4">

						<?php if ( $image_logo ) : ?>

							<?php

								echo '<img src="' . $image_logo . '" alt="' . get_bloginfo( 'name' ) . '">';

							?>

						<?php endif; ?>

						

						</div>

						<div class="col-md-4"><a href="#" class="go-to-top on"><i class="fa fa-angle-up"></i></a></div>

						<div class="col-md-4">

						<?php if ( $noo_bottom_bar_content != '' ) : ?>

							<div class="noo-bottom-bar-content">

								<?php echo $noo_bottom_bar_content; ?>

							</div>

						<?php endif; ?>

						</div>

						

					</div>

				</div>

			</div>

			<?php endif; ?>

		</div> <!-- /.container-boxed -->

	</footer> <!-- /.colophon.site-info -->

<?php endif; ?>

</div> <!-- /#top.site -->


<?php require_once('layouts/my-custom-js.php'); ?>

<?php wp_footer(); ?>

<link href="<?php //echo NOO_FRAMEWORK_URI . '/admin/assets/css/bootstrap-multiselect.css'; ?>" rel="stylesheet" type="text/css" />







<script src="<?php echo NOO_FRAMEWORK_URI . '/admin/assets/js/bootstrap-multiselect.js';?>" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo NOO_FRAMEWORK_URI . '/admin/assets/js/bootbox.min.js'?>"></script>
<script type="text/javascript" src="<?php echo NOO_FRAMEWORK_URI . '/admin/assets/js/bootbox.js'?>"></script>

<script type="text/javascript">

    jQuery(function () {
        jQuery('#lstFruits').multiselect({
            includeSelectAllOption: true
        });

		
		 jQuery('.gsearchform :checkbox').click(function() {
		 var favorite = [];
         jQuery.each(jQuery("input[type='checkbox']:checked"), function(){            
            favorite.push(jQuery(this).val());
         });

         jQuery('#location').val(favorite);

	     });

	   

    });



//--------- on click pdf title --------------

jQuery(function () {
  jQuery('.pdf-title').click(function(){
  	var pdf_file= jQuery(this).attr('id');
  	var property_title= jQuery('#pro-title').text();
  	var pdf_title= jQuery('.pdf-title').text();

  	//alert(pdf_title);
  	bootbox.dialog({
    backdrop: true,
    title: "Download PDF",

    message: '<div class="row">'+
              '<div class="col-md-4">'+
                '<p>We will never spam you or sell your email to third parties.</p>'+

              '</div>'+
              '<div class="col-md-8">'+
                '<div id="succ-msg"></div>'+
                '<form id="pdf-form">'+
                '<input type="hidden" id="pdf_file" value='+pdf_file+'>'+
                '<input type="hidden" id="property_title" value="'+property_title+'">'+
                '<input type="hidden" id="pdf_title" value="'+pdf_title+'">'+
                '<div class="form-group" style="margin-bottom: 20px;">'+
	               '<span style="color:">Name</sapn>'+
	               '<input type="text" name="downloader_name" value="<?php echo $_SESSION['downloader_name_session'] ?>" id="downloader_name" class="form-control" required="required">'+
                   '<span id="downloader_name-msg"></span>'+
                '</div>'+

                '<div class="form-group" style="margin-bottom: 20px;">'+
                   '<span style="color:">Email</sapn>'+
	               '<input type="email" name="email" value="<?php echo $_SESSION['downloader_email_session'] ?>" id="downloader_email" class="form-control">'+
	               '<span id="email-msg"></span>'+
                '</div>'+

                '<div class="form-group" style="margin-bottom: 30px;">'+
                   '<button type="button" class="formsubmt btn btn-success" onclick="formsubmit()">Download PDF</button>'+
                '</div>'+

                '</form>'+

              '</div></div>',

      }

    );

  });

});	







function formsubmit(){

	var downloader_name= jQuery('#downloader_name').val();
	var email= jQuery('#downloader_email').val();

    var pdf_file= jQuery('#pdf_file').val();
    var property_title= jQuery('#property_title').val();
    var pdf_title= jQuery('#pdf_title').val();
    //alert(property_title);

    // reset msg

    jQuery('#downloader_name-msg').text("");
    jQuery('#email-msg').text("");

	if(downloader_name==''){
		//alert(downloader_name);
	  jQuery('#downloader_name-msg').css('color','red');	
      jQuery('#downloader_name-msg').text("Please Enter Name");
      return false;

	}


	if(email==''){

		//alert(jQuery('#email').val());
	  jQuery('#email-msg').css('color','red');		
      jQuery('#email-msg').text("Please Enter Email");
      return false;

	}else{

		var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		if(!email.match(mailformat)){
            jQuery('#email-msg').css('color','red');		
            jQuery('#email-msg').text("Please Enter Valid Email");
            return false;
		}

	}

    //return false;

    jQuery('.formsubmt').html("Please Wait...");
    jQuery('.formsubmt').attr("disabled",true);

	jQuery.ajax({
		type: "POST",
		url: "/wp-admin/admin-ajax.php",
		data: {action:'send_pdf_link',downloader_name:downloader_name,email:email,pdf_file:pdf_file,property_title:property_title,pdf_title:pdf_title},
		success: function(response){
		   if(response=='send'){
		   	  jQuery('#succ-msg').empty();
              jQuery('#succ-msg').append('<div class="alert alert-success nomargin" style="padding:10px;">Download link has been sent to your email - if this does not arrive shortly, please check your spam filter</div>');
		      
		   }

		   jQuery('.formsubmt').html("Download PDF");
           jQuery('.formsubmt').attr("disabled",false);
		}     

	});

}




</script>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

</body>

</html>

