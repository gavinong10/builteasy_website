/**
 * NOO Meta Boxes Package.
 *
 * Javascript used in meta-boxes for Post, Page and Portfolio.
 *
 * @package    NOO Framework
 * @subpackage NOO Meta Boxes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

// Post Format
jQuery( document ).ready( function ( $ ) {
	if ($('#post-formats-select').length > 0) {
		// Add class for the child boxes

		// Image
		$('#_noo_wp_post_meta_box_image').addClass('post-formats post-format-image');
		$('label[for="_noo_wp_post_meta_box_image-hide"]').addClass('post-formats post-format-image');

		// Gallery
		$('#_noo_wp_post_meta_box_gallery').addClass('post-formats post-format-gallery');
		$('label[for="_noo_wp_post_meta_box_gallery-hide"]').addClass('post-formats post-format-gallery');

		// Video
		$('#_noo_wp_post_meta_box_video').addClass('post-formats post-format-video');
		$('label[for="_noo_wp_post_meta_box_video-hide"]').addClass('post-formats post-format-video');
		
		// Link
		$('#_noo_wp_post_meta_box_link').addClass('post-formats post-format-link');
		$('label[for="_noo_wp_post_meta_box_link-hide"]').addClass('post-formats post-format-link');

		// Quote
		$('#_noo_wp_post_meta_box_quote').addClass('post-formats post-format-quote');
		$('label[for="_noo_wp_post_meta_box_quote-hide"]').addClass('post-formats post-format-quote');

		// Status
		$('#_noo_wp_post_meta_box_status').addClass('post-formats post-format-status');
		$('label[for="_noo_wp_post_meta_box_status-hide"]').addClass('post-formats post-format-status');

		// Audio
		$('#_noo_wp_post_meta_box_audio').addClass('post-formats post-format-audio');
		$('label[for="_noo_wp_post_meta_box_audio-hide"]').addClass('post-formats post-format-audio');

		// Show the active format type
		var checkedElement = $('#post-formats-select').find('input:checked');
		$('.post-formats').hide();
		$('.' + checkedElement.attr('id')).show();

		// When click, display the according format type.
		$('#post-formats-select').find('input').click(function () {
			$this = $(this);
			$('.post-formats').hide();
			$('.' + $this.attr('id')).show();
		});
	}
} );

// Page Template
jQuery( document ).ready( function ( $ ) {
	if ($('#page_template').length > 0) {
		// Add class for the child boxes

		// Sidebar
		$('#_noo_wp_page_meta_box_sidebar').addClass('page-templates page-template-page-left-sidebar_php page-template-page-right-sidebar_php');
		$('label[for="_noo_wp_page_meta_box_sidebar-hide"]').addClass('page-templates page-template-page-left-sidebar_php page-template-page-right-sidebar_php');

		$('.noo-form-group._noo_wp_page_enable_one_page').addClass('page-templates page-template-default page-template-page-full-width_php page-template-page-left-sidebar_php page-template-page-right-sidebar_php');

		// Show the active format type
		var selectedVal = $('#page_template option:selected').val();
		selectedVal = selectedVal.replace(".", "_");
		$('.page-templates').hide();
		$('.page-template-' + selectedVal).show();

		// When choose template with sidebar, display the Sidebar meta-box
		$('#page_template').change(function () {
			var selectedVal = $('#page_template option:selected').val();
			selectedVal = selectedVal.replace(".", "_");
			$('.page-templates').hide();
			$('.page-template-' + selectedVal).show();
		});
	}
} );