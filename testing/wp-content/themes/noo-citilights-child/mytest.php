 <?php
/*
Template Name: Mytest Template

*/
?>

<?php get_header() ?>

<div id="container">
 <div id="content" role="main">
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('.set_custom_images').click(function(e){
      e.preventDefault();
      var button = jQuery(this);
      var id = button.prev();
      wp.media.editor.send.attachment = function(props, attachment) {
          id.val(attachment.id);
      };
      wp.media.editor.open(button);
      return false;
  });
  
})

</script>
 <?php

$content = '';
$editor_id = 'mycustomeditor';

wp_editor( $content, $editor_id );

?>
 </div> 
</div>
<?php get_footer() ?>