<?php

//Load bootstrap file
require_once( dirname( dirname( dirname(__FILE__) ) ) .'/eg-attachments-bootstrap.php');

if (! defined('EGA_TEXTDOMAIN'))
	require_once( dirname( dirname( __FILE__) ).'/eg-attachments-common.inc.php');

//Check for rights
if (!is_user_logged_in() || (!current_user_can('edit_posts') &&
	!current_user_can('edit_pages') && !current_user_can('edit_topics') && !current_user_can('edit_replies')))
	wp_die(esc_html__('You are not allowed to access this file.', EGA_TEXTDOMAIN));

$ega_options 	= get_option(EGA_OPTIONS_ENTRY);
$default_values = EG_Attachments_Common::get_shortcode_defaults($ega_options);
list($default_values['orderby'], $default_values['order']) = explode(' ', $default_values['orderby']);
$current_values = $default_values;

if ($ega_options['shortcode_auto_default_opts']) {
	$current_values = array_merge($current_values, array(
		'orderby'  		=> $ega_options['shortcode_auto_orderby'],
		'order'			=> $ega_options['shortcode_auto_order'],
		'template' 		=> $ega_options['shortcode_auto_template'],
		'doctype'  		=> $ega_options['shortcode_auto_doc_type'],
		'title'    		=> $ega_options['shortcode_auto_title'],
		'titletag' 		=> $ega_options['shortcode_auto_title_tag'],
		'limit' 	 	=> $ega_options['shortcode_auto_limit'],
		'icon_image' 	=> $ega_options['icon_image'])
	);
}

$select_fields = array(
	'template' => EG_Attachments_Common::get_templates($ega_options, 'all'),
	'orderby'  => $EGA_FIELDS_ORDER_LABEL,
	'force_saveas'  => array(
		'-1'        => __('Use default parameter', EGA_TEXTDOMAIN),
		'0'         => __('No', EGA_TEXTDOMAIN),
		'1'         => __('Yes', EGA_TEXTDOMAIN)
	),
	'logged_users'  => array(
		'-1'        => __('Use default parameter', EGA_TEXTDOMAIN),
		'0'         => __('Display attachments for all users', EGA_TEXTDOMAIN),
		'1'         => __('Show attachments for everyone, but the url, for logged users only', EGA_TEXTDOMAIN),
		'2'         => __('Display attachments for logged users only', EGA_TEXTDOMAIN)
	),
	'order'	   		=> array(
		'ASC' 		=> __('Ascending', EGA_TEXTDOMAIN),
		'DESC' 		=> __('Descending', EGA_TEXTDOMAIN)
	),
	'icon_image'    => array(
		'icon'		=> __('The icon of the file type', EGA_TEXTDOMAIN),
		'thumbnail' => __('The Thumbnail of the image', EGA_TEXTDOMAIN),
	),
	'doctype'	    => array(
		'all'	    => __('All',       EGA_TEXTDOMAIN),
		'document'  => __('Documents', EGA_TEXTDOMAIN),
		'image'     => __('Images',    EGA_TEXTDOMAIN)
	)
);

function get_select($html_id, $key, $current_values, $default_values, $blank_value=FALSE) {
	global $select_fields;

	$string = '<select id="'.$html_id.'" name="'.$html_id.'">';
	if ($blank_value)
		$string .= '<option value=""> </option>';
	foreach ($select_fields[$key] as $id => $value) {
		if ($current_values[$key] == $id) $selected = 'selected'; else $selected = '';
		$string .= '<option value="'.$id.'" '.$selected.'>'.$value.'</option>';
	}
	$string .= '</select><input type="hidden" name="'.$html_id.'_def" id="'.$html_id.'_def" value="'.$default_values[$key].'" />';
	return $string;
} // End of get_select


?>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>EG-Attachments shortcode</title>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />

	<base target="_self" />

    <script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript">

		function init() {
			tinyMCEPopup.resizeToInnerSize();
		}

		function getCheckedValue(radioObj) {
			string = "";
			if(!radioObj)
				return "";
			var radioLength = radioObj.length;
			if(radioLength == undefined)
				if(radioObj.checked)
					return radioObj.value;
				else
					return "";
			for(var i = 0; i < radioLength; i++) {
				if(radioObj[i].checked) {
					if (string=="") string = "\"" + radioObj[i].value;
					else string = string + ',' + radioObj[i].value;
				}
			}
			if (string!="") string = string + "\"";
			return string;
		} // End of getCheckedValue

		function insertEGAttachmentsShortCode() {

			var title 			 = document.getElementById('title').value;
			var title_def 		 = document.getElementById('title_def').value;
			var titletag 		 = document.getElementById('titletag').value;
			var titletag_def 	 = document.getElementById('titletag_def').value;
			var template 		 = document.getElementById('template').value;
			var template_def	 = document.getElementById('template_def').value;
			var doctype 		 = document.getElementById('doctype').value;
			var doctype_def		 = document.getElementById('doctype_def').value;
			var limit			 = parseInt(document.getElementById('limit').value);
			var limit_def		 = parseInt(document.getElementById('limit_def').value);
			var nofollow		 = document.getElementById('nofollow');
			var nofollow_def	 = parseInt(document.getElementById('nofollow_def').value)
			var target		 	 = document.getElementById('target');
			var target_def	 	 = parseInt(document.getElementById('target_def').value)
			var force_saveas	 = document.getElementById('force_saveas').value;
			var force_saveas_def = document.getElementById('force_saveas_def').value;
			var logged_users	 = document.getElementById('logged_users').value;
			var logged_users_def = document.getElementById('logged_users_def').value;
			var orderby 		 = document.getElementById('orderby').value;
			var orderby_def		 = document.getElementById('orderby_def').value;
			var order			 = document.getElementById('order').value;
			var order_def		 = document.getElementById('order_def').value;
			var icon_image		 = document.getElementById('icon_image').value;
			var icon_image_def	 = document.getElementById('icon_image_def').value;
			
			
			if (document.getElementById('tags')) {
				var tags		 = document.getElementById('tags').value;
				var tags_and	 = document.getElementById('tags_and');
			}
			var default_doclist	 = document.getElementById('default_doclist');
			var doclist 		 = getCheckedValue(document.getElementsByName('doclist'));

			var tagtext = "[attachments";
			if (title != title_def )
				tagtext = tagtext + " title=\"" + title + "\"";

			if (titletag != titletag_def )
				tagtext = tagtext + " titletag=\"" + titletag + "\"";

			if (template != template_def )
				tagtext = tagtext + " template=" + template;

			if (doctype != doctype_def )
				tagtext = tagtext + " doctype=" + doctype;

			if (limit != limit_def )
				tagtext = tagtext + " limit=" + limit;

			if (nofollow.checked) nofollow_val=1;
			else nofollow_val=0;
			
			if (nofollow_val != nofollow_def)
				tagtext = tagtext + " nofollow=" + nofollow_val;

			if ( icon_image_def != icon_image) 
				tagtext = tagtext + " icon_image=" + icon_image;
				
			if (target.checked) target_val=1;
			else target_val=0;

			if (target_val != target_def)
				tagtext = tagtext + " target=" + target_val;

			if (force_saveas > force_saveas_def )
				tagtext = tagtext + " force_saveas=1";

			if (logged_users != logged_users_def )
				tagtext = tagtext + " logged_users=" + logged_users;

			if (typeof tags_and != "undefined" && typeof tags != "undefined" ) {
				if (tags_and.checked) {
					tags_option=" tags_and";
				} else {
					tags_option=" tags";
				}
				if ( tags != "" )
					tagtext = tagtext + tags_option + "=\"" + tags + "\"";
			}

			orderby_string = "";
			if ( orderby != orderby_def )
				orderby_string = orderby;

			if ( order != order_def ) {
				if ( orderby_string != "" )
					orderby_string = orderby_string + " " + order;
				else
					orderby_string = orderby + " " + order;
			}

			if ( orderby_string != "" )
				tagtext = tagtext + " orderby=\"" + orderby_string + "\"";

			if ( default_doclist && !default_doclist.checked) {
				if (doclist!="")
					tagtext = tagtext + " include=" + doclist;
			}

			var tagtext = tagtext + "]";
			
			// EGE - 2.0.2 - Run with TinyMCE 3.x and 4.x
			if(window.tinyMCE) {
				window.tinyMCE.activeEditor.execCommand('mceInsertContent', 0, tagtext);
			}
			
			// Run only with TinyMCE 33.x
			// if(window.tinyMCE) {
			//	window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
			// }
			tinyMCEPopup.close();
			return;

		} // End of insertEGAttachmentsShortCode
	</script>

  </head>
  <body onload="tinyMCEPopup.executeOnLoad('init();');">
  	<div class="mceActionPanel panel_wrapper">
		<form action="" method="post" name="ega-mcebox">
			<div style="float: left; margin:0; width:49%;">
				<p>
					<label for="title"><strong><?php _e('Title: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<input id="title" name="title" type="text" value="<?php echo $default_values['title']; ?>" />
					<input type="hidden" name="title_def" id="title_def" value="<?php echo $default_values['title']; ?>" />
				</p>
				<p>
					<label for="titletag"><strong><?php _e('HTML Tag for title: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<input id="titletag" name="titletag" type="text" value="<?php echo $default_values['titletag']; ?>" />
					<input type="hidden" name="titletag_def" id="titletag_def" value="<?php echo $default_values['titletag']; ?>" />
				</p>
				<p>
					<label for="template"><strong><?php _e('Template: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<?php echo get_select('template', 'template', $current_values, $default_values, FALSE); ?>
				</p>
				<p>
					<label for="doctype"><strong><?php _e('Document type: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<?php echo get_select('doctype', 'doctype', $current_values, $default_values); ?>
				</p>
				<p>
					<label for="limit"><strong><?php _e('Number of documents to display: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<input type="text" id="limit" value="<?php echo $default_values['limit']; ?>" />
					<input type="hidden" name="limit_def" id="limit_def" value="<?php echo $default_values['limit']; ?>" />
				</p>
				<p>
					<input type="hidden" name="nofollow_def" id="nofollow_def" value="<?php echo $default_values['nofollow']; ?>" />
					<input type="checkbox" id="nofollow" <?php echo ($default_values['nofollow']>0?'checked':''); ?> />
					<label for="nofollow"><strong><?php _e('Add &laquo;Nofollow&raquo; attribut',EGA_TEXTDOMAIN); ?></strong></label>
				</p>
				<p>
					<input type="hidden" name="target_def" id="target_def" value="<?php echo $default_values['target']; ?>" />
					<input type="checkbox" id="target" <?php echo ($default_values['target']>0?'checked':''); ?> />
					<label for="target"><strong><?php _e('Add target="blank" attribut',EGA_TEXTDOMAIN); ?></strong></label>
				</p>
				<p>
					<label for="logged_users"><strong><?php _e('Attachments access: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<?php echo get_select('logged_users', 'logged_users', $current_values, $default_values); ?>
				</p>
			</div>
			<div style="float: left; margin:0 0 0 1%; width:49%;">
				<p>
					<label for="force_saveas"><strong><?php _e('Force "saveas": ', EGA_TEXTDOMAIN); ?></strong></label><br />
					<?php echo get_select('force_saveas', 'force_saveas', $current_values, $default_values); ?>
				</p>
				<p>
					<label for="icon_image"><strong><?php _e('Icons for the images: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<?php echo get_select('icon_image', 'icon_image', $current_values, $default_values); ?>
				</p>
				<p>
					<label for="orderby"><strong><?php _e('Order by: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<?php echo get_select('orderby', 'orderby', $current_values, $default_values); ?>
				</p>
				<p>
					<label for="order"><strong><?php _e('Order: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<?php echo get_select('order', 'order', $current_values, $default_values); ?>
				</p>
				<?php if ($ega_options['tags_assignment'])  { ?>
				<p>
					<label for="tags"><strong><?php esc_html_e('Filter attachments using tags', EGA_TEXTDOMAIN); ?></strong></label><br />
					<input type="text" id="tags" name="tags" value="" /><br />
					<input type="checkbox" id="tags_and" /><label for="tags_and"><?php esc_html_e('Check if you want only the posts linked to ALL tags', EGA_TEXTDOMAIN); ?></label>
				</p>
				<?php }
				$attachment_string = '';
				if (isset($_GET['post_id'])) {
					$attachment_string = __('No attachment available for this post', EGA_TEXTDOMAIN);
					$attachment_list = get_children('post_type=attachment&post_parent='.absint($_GET['post_id']));
					if ($attachment_list) {
						$attachment_string = '<input type="checkbox" id="default_doclist" value="all" checked /> '.__('All',EGA_TEXTDOMAIN).'<br />';
						foreach ($attachment_list as $key => $attachment) {
								$attachment_string .= '<input type="checkbox" name="doclist" value="'.$attachment->ID.'" /> '.$attachment->post_title.'<br />';
						}
					}
				}

				if ('' != $attachment_string) { ?>
				<p>
					<label for="doclist"><strong><?php _e('Document list: ',EGA_TEXTDOMAIN); ?></strong></label><br />
					<?php echo $attachment_string; ?>
				<?php } ?>
				</p>
			</div>
			<br style="clear: both;" />
			<div class="mceActionPanel">
				<input id="insert" type="submit" onclick="insertEGAttachmentsShortCode();" value="<?php esc_html_e('Insert', EGA_TEXTDOMAIN); ?>" name="insert">
				<input id="cancel" type="button" onclick="tinyMCEPopup.close();" value="<?php esc_html_e('Cancel', EGA_TEXTDOMAIN); ?>" name="cancel">
			</div>
		</form>
	</div>
  </body>
</html>