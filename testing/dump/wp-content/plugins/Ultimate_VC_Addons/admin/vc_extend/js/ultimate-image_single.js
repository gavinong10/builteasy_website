
/**
 * Callback function for the 'click' event of the 'Set Footer Image'
 * anchor in its meta box.
 *
 * Displays the media uploader for selecting an image.
 *
 * @since 0.1.0
 */
 
;(function ( $, window, undefined ) {

	/* 	= Image Up loader
	 *-------------------------------------------------*/ 
  	var pn = 'ULT_Image_Single',
  	    document = window.document,
  	    defaults = {
  	      add: ".ult_add_image",
  	      remove: "#remove-thumbnail",
  	    };
	
  	function ult( element, options ) {
  	  this.element = element;
	  this.options = $.extend( {}, defaults, options) ;
	  this._defaults = defaults;
  	  this._name = pn;
	  this.init();
  	}
	
	ult.prototype.save_and_show_image = function(id, url, caption) {
  		var $t = $(this.element);

		$t.find( '.ult_selected_image_list .inner' )
	        .children( 'img' )
	            .attr( 'src', url )
	            .attr( 'alt', caption )
	            .attr( 'title', title )
	            .show()
		        .parent()
		        .removeClass( 'hidden' );

		$t.find('.ult-image_single-value').val( id + '|' + url);
		//	show image
		$t.find( '.ult_selected_image' ).show();
	};

	/* = {start} wp media uploader
	 *------------------------------------------------------------------------*/ 
	ult.prototype.renderMediaUploader = function() {
	    'use strict';
	 
	    var fn, image_data, json;
	    var self = this;
	    if ( undefined !== fn ) {
	        fn.open();
	        return;	 
	    }

		fn = wp.media.frames.fn = wp.media({
			frame:    'post',
			state:    'insert',
			library: { type: 'image' },
			editing:   false,
			multiple: false,
		});
	 	
		//	Insert from {SELECT}
		fn.on( 'insert', function() {
	
	        // Read the JSON data returned from the Media Uploader
			json = fn.state().get( 'selection' ).first().toJSON();

			if ( 0 > $.trim( json.url.length ) ) {
				return;
			}

			//	{save} image - id & src - for {SELECT}
			var id 		= json.id || null;
			var url 	= json.url || null;
			var caption = json.caption || null;
			self.save_and_show_image(id, url, caption);
	    });

	 	//	Insert from {URL}
	    fn.state('embed').on( 'select', function() {
			var state = fn.state(),
				type = state.get('type'),
				embed = state.props.toJSON();

			//	{save} image - id & src - for {INSERT FROM URL}
			var id 		= null;
			var caption = embed.caption || null;
			var url 	= embed.url || null;
			self.save_and_show_image(id, url, caption);
		});

	    // Now display the actual fn
	    fn.open();
	};

	ult.prototype.resetUploadForm = function () {
  		var $t = $(this.element);
		$t.find( '.ult_selected_image' ).hide();
		//	{Remove} image - ID & SRC
		$t.find('.ult-image_single-value').val('');
		//$t.find('.ult-image_single-value').val('null|null');
	};

	ult.prototype.renderFeaturedImage = function ( ) {
		var $t = $(this.element);

		var v = $t.find( '.ult-image_single-value' ).val();
		if ( '' !== $.trim ( v ) ) {

			var tm = v.split('|');

			//	Saved Image - ID
			if( tm[0] != 'undefined' && tm[0] != 'null' ) {

				if( !tm[1] ) {
					// set process
					$t.find( '.spinner.ult_img_single_spinner').css('visibility', 'visible');
					var data = {
						action : 'ult_get_attachment_url',
						attach_id : parseInt(tm[0]),
					}
					$.post(ajaxurl, data, function(img_url) {
						$t.find( '.spinner.ult_img_single_spinner').css('visibility', 'hidden');
						$t.find( '.ult_selected_image_list .inner' ).children( 'img' ).attr('src', img_url );
					});
				}
			}

			//	Saved Image - SRC
			if( tm[1] != 'undefined' && tm[1] != 'null' ) {
				$t.find( '.ult_selected_image_list .inner' ).children( 'img' ).attr('src', tm[1] );
				$t.find( '.ult_selected_image' ).show();
			} else {
				$t.find( '.ult_selected_image' ).hide();
			}
		} else {

			$t.find( '.ult_selected_image' ).hide();
			//	{Default} image - ID & SRC
			$t.find('.ult-image_single-value').val('');
			//$t.find('.ult-image_single-value').val('null|null');
		}
	};
	/* = {end} wp media uploader
	 *------------------------------------------------------------------------*/ 

  	ult.prototype.init = function () {
  		var self = this;
  		var i = self._defaults;
  		var $t = $(self.element);
  		
  		self.renderFeaturedImage( );
  		//	add image
  		$t.find(i.add).click(function(event) {
  			// Stop the anchor's default behavior
            event.preventDefault();
  			self.renderMediaUploader();
  		});

  		// remove image
  		$t.find(i.remove).click(function(event) {
			event.preventDefault();
			self.resetUploadForm( );
  		});

  	};
	
  	$.fn[pn] = function ( options ) {
  	  return this.each(function () {
  	    if (!$.data(this, 'plugin_' + pn)) {
  	      $.data(this, 'plugin_' + pn, new ult( this, options ));
  	    }
  	  });
  	}

  	//	initial call
	$(document).ready(function() {
		$('.ult-image_single').ULT_Image_Single();
	});

}(jQuery, window));