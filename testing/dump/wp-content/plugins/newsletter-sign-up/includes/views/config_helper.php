<?php defined( 'ABSPATH' ) or exit; ?>
<div class="wrap" id="nsu-admin">


  <?php include_once NSU_PLUGIN_DIR . 'includes/views/parts/navigation.php'; ?>
  
   <h2>Newsletter Sign-Up :: Config Extractor</h2>

   
  <div id="nsu-main">   

   <?php if( isset( $result ) && ! is_array( $result ) ) { ?>
    <div id="message" class="notice updated"><p>Oops, I couldn't make any sense of that. Are you sure you submitted a form snippet?</p></div>
   <?php } ?>





   <?php if(isset($result) && is_array($result)) { ?>
   <table class="form-table">	
    <tr valign="top">
     <th scope="row" style="font-weight:bold;">Form action:</th>
     <td><?php echo $result['form_action']; ?></td>
   </tr>
   <tr valign="top">
     <th scope="row" style="font-weight:bold;">Email identifier:</th>
     <td><?php echo $result['email_name']; ?></td>
   </tr>
   <tr valign="top">
     <th scope="row" style="font-weight:bold;">Name identifier:</th>
     <td><?php echo $result['name_name']; ?></td>
   </tr>
   <?php if(count($result['additional_data']) > 0) { ?>
      <tr valign="top">
          <th scope="row" colspan="2" style="font-weight:bold;">Additional data ( name / value):</th>
      </tr>
    <?php foreach($result['additional_data'] as $data) { ?>
        <tr valign="top">
          <td><?php echo esc_html( $data[0] ); ?></td>
          <td><?php echo esc_html( $data[1] ); ?></td>
        </tr>
     <?php } ?>
   <?php } ?>

 </table>

 <p>The above settings are there to help you, though they may not be right. Check out <a href="https://dannyvankooten.com/571/configuring-newsletter-sign-up-the-definitive-guide/">this post on my blog</a> for more information on how to manually
   configure Newsletter Sign-up.</p>

   <p>The form code below is a stripped down version of your sign-up form which will make it easier for you to extract the right    values. Please also use this form when asking for support.</p>
   <textarea class="widefat" rows="10"><?php echo esc_textarea( $result['simpler_form'] ); ?></textarea>

   <?php } else { ?>

   <p>This tool was designed to help you extract the right configuration settings to make Newsletter Sign-Up work properly.</p>
   <p>Please copy and paste a sign-up form you would normally embed on a HTML page in the textarea below and hit the extract button. The NSU Config Tool will then try to extract the right configuration settings for you. </p>
   <form method="post" action="" id="ns_settings_page">
     <textarea name="form" class="widefat" rows="10" placeholder="Copy paste your form code here." required></textarea>

     <p class="submit">
        <input type="submit" class="button-primary" style="margin:5px;" value="<?php _e('Extract') ?>" />
    </p>


  </form>
  <?Php } ?>

</div>

<?php include_once NSU_PLUGIN_DIR . 'includes/views/parts/sidebar.php'; ?>

</div>
