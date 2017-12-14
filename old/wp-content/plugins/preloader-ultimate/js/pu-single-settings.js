jQuery(document).ready(function($){

	//preloader ultimate table list object
	var pu_list = {
	   
	    init: function() {
	        // This will have its utility when dealing with the page number input
	        var timer;
	        var delay = 500;
	        // Pagination links, sortable link
	        $('.tablenav-pages a, .manage-column.sortable a, .manage-column.sorted a').on('click', function(e) {
	            // We don't want to actually follow these links
	            e.preventDefault();
	            // Simple way: use the URL to extract our needed variables
	            var query = this.search.substring( 1 );
	            
	            var data = {
	                paged: pu_list.__query( query, 'paged' ) || '1',
	                order: pu_list.__query( query, 'order' ) || 'desc',
	                orderby: pu_list.__query( query, 'orderby' ) || 'time'
	            };
	            pu_list.update( data );
	        });
	        // Page number input
	        $('input[name=paged]').on('keyup', function(e) {
	            // If user hit enter, we don't want to submit the form
	            // We don't preventDefault() for all keys because it would
	            // also prevent to get the page number!
	            if ( 13 == e.which )
	                e.preventDefault();
	            // This time we fetch the variables in inputs
	            var data = {
	                paged: parseInt( $('input[name=paged]').val() ) || '1',
	                order: $('input[name=order]').val() || 'desc',
	                orderby: $('input[name=orderby]').val() || 'time'
	            };
	            // Now the timer comes to use: we wait half a second after
	            // the user stopped typing to actually send the call. If
	            // we don't, the keyup event will trigger instantly and
	            // thus may cause duplicate calls before sending the intended
	            // value
	            window.clearTimeout( timer );
	            timer = window.setTimeout(function() {
	                pu_list.update( data );
	            }, delay);
	        });

			//set the height of the preloader preview
			$(".table-preview").each(function(){
				var w = $(this).attr('spinwidth'),
					h = (parseInt(w) + 40)+'px';

				if(w == 'la-3x' || w == 'la-2x' || w == 'la-sm' || w == ''){
					h = ($(this).children().eq(0).height()+40) +'px';
				}
			  	$(this).css('height',h);
			});

	    },
	    /** AJAX call
	     * Send the call and replace table parts with updated version!
	     * @param    object    data The data to pass through AJAX
	     */
	    update: function( data ) {
	        $.ajax({
	            // /wp-admin/admin-ajax.php
	            url: ajaxurl,
	            // Add action and nonce to our collected data
	            data: $.extend(
	                {
	                    _ajax_custom_list_nonce: $('#_ajax_custom_list_nonce').val(),
	                    action: '_ajax_fetch_custom_list',
	                },
	                data
	            ),
	            // Handle the successful result
	            success: function( response ) {

	                // WP_List_Table::ajax_response() returns json
	                var response = $.parseJSON( response );

	                // Add the requested rows
	                if ( response.rows.length )
	                    $('#the-list').html( response.rows );
	                // Update column headers for sorting
	                if ( response.column_headers.length )
	                    $('thead tr, tfoot tr').html( response.column_headers );
	                // Update pagination for navigation
	                if ( response.pagination.bottom.length )
	                    $('.tablenav.top .tablenav-pages').html( $(response.pagination.top).html() );
	                if ( response.pagination.top.length )
	                    $('.tablenav.bottom .tablenav-pages').html( $(response.pagination.bottom).html() );
	                // Init back our event handlers
	                pu_list.init();
	            }
	        });
	    },
	    /**
	     * Filter the URL Query to extract variables
	     * 
	     * @see http://css-tricks.com/snippets/javascript/get-url-variables/
	     * 
	     * @param    string    query The URL query part containing the variables
	     * @param    string    variable Name of the variable we want to get
	     * 
	     * @return   string|boolean The variable value if available, false else.
	     */
	    __query: function( query, variable ) {
	        var vars = query.split("&");
	        for ( var i = 0; i <vars.length; i++ ) {
	            var pair = vars[ i ].split("=");
	            if ( pair[0] == variable )
	                return pair[1];
	        }
	        return false;
	    },
	}
	//initialize the preloader table listing;
	pu_list.init();
	pu_list.update({paged: "1", order: "desc", orderby: "time"});


	$('#preloader-table-form').on('change','.column-cb input[type="checkbox"]',function(){
		if($(this).is(':checked')){
			$('#preloader-table-form tr input[type="checkbox"]').attr('checked','checked');
		}else{
			$('#preloader-table-form tr input[type="checkbox"]').removeAttr('checked');
		}
    });

	$('.add-new-h2').on('click',function(e){
		e.preventDefault();

		$('.save-options').text('Save Settings');
		set_settings(pu_default_settings);
		tb_show("Add New Preloader", "#TB_inline?width=600&height=550&inlineId=preloader-setting-modal");
	});

	$('#preloader-table-form').on('click','.delete-preloader',function(e){
		e.preventDefault();
		var id = $(this).attr('id');

		if(confirm('Are you sure you want to delete this preloader?')){
			delete_preloader(id);
		}
	});

	$('#preloader-table-form').on('click','.edit-preloader',function(e){
		e.preventDefault();
		var id = $(this).attr('id');
		
		retrieve_preloader(id);
	});

	function retrieve_preloader(id){
		var data = {
	      'action': 'retrieve_pu_single_page_settings',
	      'preloader_id':id,
	    };

	    preloader.show(pu_default_settings);

	    $.post(ajaxurl, data, function(res) {

	    	if(res['response'] = 1){

	    		preloader.hide(pu_default_settings);
	    		$('.save-options').text('Update Settings').attr('preloader_id',id);
	    		set_settings(res['settings']);
				tb_show("Update Preloader", "#TB_inline?width=600&height=550&inlineId=preloader-setting-modal");

	    	}else{
	    		notification.show('error','Oops, something went wrong. Please try again.');
	    	}

	    },'json');
	}

	function delete_preloader(id){
		var data = {
	      'action': 'delete_pu_single_page_settings',
	      'preloader_id':id,
	    };

	    preloader.show(pu_default_settings);

	    $.post(ajaxurl, data, function(res) {

	    	if(res['response'] = 1){
	    		notification.show('updated','Preloader sucessfully removed.');
	    	}else{
	    		notification.show('error','Oops, something went wrong. Please try again.');
	    	}

	    	var data = {paged: "1", order: "desc", orderby: "time"};
	    	pu_list.update(data);

	    	preloader.hide(pu_default_settings);

	    },'json');
	}


	var preloader_title 		= $('#preloader-title'),
		available_on 			= $('#available-on'),
		enable_preloader 		= $('#enable-preloader'),
		enable_on_mobile 		= $('#enable-on-mobile'),
		hide_scrollbar   		= $('#hide-scrollbar'),
		spinner_color 			= $('#spinner-color'),
		spinner_background 		= $('#spinner-background'),
		text_spinner 			= $('#text-spinner'),
		text_color 				= $('#text-color'),
		ie_spinner_color 		= $('#ie-spinner-color'),
		ie_spinner_background 	= $('#ie-spinner-background'),
		exit_animation 			= $('#exit-animation'),
		page_entrance 	 		= $('#page-entrance');

	var preview = {
		con 	:$('#spinner-preview'), 	//preview container
		clr 	:spinner_color, 			//spinner color input field
		csw 	:$('#css-width'), 			//css width option container
		bgd 	:spinner_background, 		//background color input field
		cur 	:'',						//hold the current used spinner
		tpe     :'svg',						//spinner type being used
		up_width:function(w){
			if(this.tpe == 'css')
				var spinner = $('#spinner-preview > *').removeClass('la-sm la-2x la-3x').addClass(w);			
			else
				var spinner = $('#spinner-preview > *').css('width',w);

			if(this.tpe == 'gif') this.up_gifsrc(parseInt(w));

			var h = (spinner.height() == 0) ? parseInt(w) : spinner.height();
				
			this.con.css('height',(h+60)+'px');
		},
		up_spinner:function(spinner){
			this.con.html(spinner);
			$('#spinner-preview > *').addClass('center-spin').attr('spintype',this.tpe);

			if(this.tpe != 'custom') this.cur = this.con.html();
			
			var w = (this.tpe == 'css') ? $('input[name="css-width"]:checked').val() : parseInt(spinner_width.noUiSlider.get())+'px';
			this.up_width(w);	
			this.up_options(this.tpe);

			(this.tpe == 'svg') ? preview_ie.opt.show() : preview_ie.opt.hide();
		},
		up_color:function(clr){
			$('#spinner-preview > *').css('color',clr);
			this.con.html(this.con.html());
		},
		up_background:function(clr){
			this.con.css('background-color',clr);
		},
		up_options:function(type){		
			if(type == "css"){
				this.clr.closest('tr').show();
				this.csw.closest('tr').show().prev().hide();
				this.up_color(this.clr.val());
			}else{
				this.clr.closest('tr').hide();
				this.csw.closest('tr').hide().prev().show();
			}
		},
		up_gifsrc: function(w){
	        var src = '128';
	        if(w <= 40) src = '32';      
	        else if(w <= 80) src = '64';

	        var el  = $('#spinner-preview > img');
	        if(el.length != 0){
	        	var str = el.attr('src').replace(/32|64|128/g,src);
	        	el.attr('src',str);
	        } 
		}
	}

	var preview_ie = {
		opt     :$('.ie-spinner-options'),
		con 	:$('#ie-spinner-preview'), 	
		clr 	:ie_spinner_color, 		
		csw 	:$('#ie-css-width'), 			
		bgd 	:ie_spinner_background, 	
		cur 	:'',							
		tpe     :'css',							
		up_width:function(w){
			if(this.tpe == 'css')
				var spinner = $('#ie-spinner-preview > *').removeClass('la-sm la-2x la-3x').addClass(w);			
			else
				var spinner = $('#ie-spinner-preview > *').css('width',w);

			if(this.tpe == 'gif') this.up_gifsrc(parseInt(w));

			var h = (spinner.height() == 0) ? parseInt(w) : spinner.height();
				
			this.con.css('height',(h+60)+'px');
		},
		up_spinner:function(spinner){
			this.con.html(spinner);
			$('#ie-spinner-preview > *').addClass('center-spin').attr('spintype',this.tpe);

			if(this.tpe != 'custom') this.cur = this.con.html();

			var w = (this.tpe == 'css') ? $('input[name="ie-css-width"]:checked').val() : parseInt(ie_spinner_width.noUiSlider.get())+'px';
			this.up_width(w);	
			this.up_options(this.tpe);
		},
		up_color:function(clr){
			$('#ie-spinner-preview > *').css('color',clr);
			this.con.html(this.con.html());
		},
		up_background:function(clr){
			this.con.css('background-color',clr);
		},
		up_options:function(type){
			if(type == "css"){
				this.clr.closest('tr').show();
				this.csw.closest('tr').show().prev().hide();
			}else{
				this.clr.closest('tr').hide();
				this.csw.closest('tr').hide().prev().show();
			}
		},
		up_gifsrc: function(w){
	        var src = '128';
	        if(w <= 40) src = '32';      
	        else if(w <= 80) src = '64';

	        var el  = $('#ie-spinner-preview > img');
	        if(el.length != 0){
	        	var str = el.attr('src').replace(/32|64|128/g,src);
	        	el.attr('src',str);
	        } 
		}
	}

	var spinner_width = document.getElementById('spinner-width'),
		spinner_width_display = document.getElementById('spinner-width-display');

	noUiSlider.create(spinner_width, {
		start: [50],
		step: 1,
		connect: 'lower',
		range: {
			'min': [20],
			'max': [200]
		}
	});
	spinner_width.noUiSlider.on('update', function( values, handle ) {
		var width = parseInt(values[handle])+'px';
		spinner_width_display.innerHTML = width;
		preview.up_width(width);
	});


	var ie_spinner_width = document.getElementById('ie-spinner-width'),
		ie_spinner_width_display = document.getElementById('ie-spinner-width-display');

	noUiSlider.create(ie_spinner_width, {
		start: [50],
		step: 1,
		connect: 'lower',
		range: {
			'min': [20],
			'max': [200]
		}
	});
	ie_spinner_width.noUiSlider.on('update', function( values, handle ) {
		var width = parseInt(values[handle])+'px';
		ie_spinner_width_display.innerHTML = width;
		preview_ie.up_width(width);
	});


	var exit_delay = document.getElementById('exit-delay'),
		exit_delay_display = document.getElementById('exit-delay-display');

	noUiSlider.create(exit_delay, {
		start: [1],
		step: 0.1,
		connect: 'lower',
		range: {
			'min': [0],
			'max': [10]
		}
	});
	exit_delay.noUiSlider.on('update', function( values, handle ) {
		exit_delay_display.innerHTML = (Math.round( values[handle] * 10 ) / 10)+'s';
	});


	var exit_duration = document.getElementById('exit-duration'),
		exit_duration_display = document.getElementById('exit-duration-display');

	noUiSlider.create(exit_duration, {
		start: [1],
		step: 0.1,
		connect: 'lower',
		range: {
			'min': [0],
			'max': [10]
		}
	});
	exit_duration.noUiSlider.on('update', function( values, handle ) {
		exit_duration_display.innerHTML = (Math.round( values[handle] * 10 ) / 10)+'s';
	});

	var text_size = document.getElementById('text-size'),
		text_size_display = document.getElementById('text-size-display');

	noUiSlider.create(text_size, {
		start: [14],
		step: 1,
		connect: 'lower',
		range: {
			'min': [10],
			'max': [50]
		}
	});
	text_size.noUiSlider.on('update', function( values, handle ) {
		var width = parseInt(values[handle])+'px';
		text_size_display.innerHTML = width;
	});


	function set_settings(opt){

		(opt['enable'] == '1') ? enable_preloader.attr('checked','checked') : enable_preloader.removeAttr('checked');
		(opt['mobile'] == '1') ? enable_on_mobile.attr('checked','checked') : enable_on_mobile.removeAttr('checked');
		(opt['scrollbar'] == '1') ? hide_scrollbar.attr('checked','checked') : hide_scrollbar.removeAttr('checked');

		preloader_title.val(opt['title']);
		available_on.val(opt['available_on']);
		spinner_color.val(opt['color']);
		spinner_background.val(opt['background']);
		ie_spinner_color.val(opt['ie_color']);
		ie_spinner_background.val(opt['ie_background']);

		text_spinner.val(opt['text_spinner']),
	    text_color.val(opt['text_color']);
	    text_size.noUiSlider.set(opt['text_size']);

		//update the color picker value
		$('.color-select').trigger('change')
	
		exit_animation.val(opt['exit_anim']);
		page_entrance.val(opt['page_entrance']);

		exit_delay.noUiSlider.set(opt['exit_delay']);
		exit_duration.noUiSlider.set(opt['exit_duration']);
	
		preview.tpe = opt['type'];
		if(preview.tpe == 'css'){
			$('input[name="css-width"][value="'+opt['width']+'"]').attr('checked','checked').siblings().removeAttr('checked');
			spinner_width.noUiSlider.set(50);
		}else{
			$('input[name="css-width"][value=""]').attr('checked','checked').siblings().removeAttr('checked');
			spinner_width.noUiSlider.set(opt['width']);
		}
		preview.up_spinner(opt['html']);


		preview_ie.tpe = opt['ie_type'];
		if(preview_ie.tpe == 'css'){
			$('input[name="ie-css-width"][value="'+opt['ie_width']+'"]').attr('checked','checked').siblings().removeAttr('checked');
			ie_spinner_width.noUiSlider.set(50);
		}else{
			$('input[name="ie-css-width"][value=""]').attr('checked','checked').siblings().removeAttr('checked');
			ie_spinner_width.noUiSlider.set(opt['ie_width']);
		}
		preview_ie.up_spinner(opt['ie_html']);

		var ths = $('.spinner-options .nav-tab-wrapper > div[spintype="'+preview.tpe+'"]');
	    ths.addClass('nav-tab-active').siblings().removeClass('nav-tab-active');
	    ths.closest('.spinner-tabs').find('.spinner-list > div').hide().eq(ths.index()).show();
	    if(preview.tpe == 'custom') $('.spinner-options .upload-preview > img').attr('src',opt['spinner']); 

	    var ths = $('.ie-spinner-options .nav-tab-wrapper > div[spintype="'+preview_ie.tpe+'"]');
	    ths.addClass('nav-tab-active').siblings().removeClass('nav-tab-active');
	    ths.closest('.spinner-tabs').find('.spinner-list > div').hide().eq(ths.index()).show();
	    if(preview_ie.tpe == 'custom') $('.ie-spinner-options .upload-preview > img').attr('src',opt['ie_spinner']);
	}


	$('.save-options').on('click',function(){

		if(available_on.val().trim() == ""){
			alert("Available On field is required.");
			available_on.focus();
			return false;
		}

		var action = $(this).text().trim();

		var preloader_options = {
			preloader_id  : '0',
			title     	  : preloader_title.val().trim(),
			available     : available_on.val().trim().toLowerCase(),
			enable        : (enable_preloader.is(':checked')) ? '1' : '0',
	        mobile        : (enable_on_mobile.is(':checked')) ? '1' : '0',
	        scrollbar     : (hide_scrollbar.is(':checked')) ? '1' : '0',
	        type          : preview.con.find('[spinner]').attr('spintype').trim(),
	        spinner       : preview.con.find('[spinner]').attr('spinner').trim(),
	        width         : (preview.con.find('[spinner]').attr('spintype') == 'css') ? $('input[name="css-width"]:checked').val() : parseInt(spinner_width.noUiSlider.get()),
	        color         : spinner_color.val(),
	        background    : spinner_background.val(),
	        html 		  : preview.con.html().trim(),    
	        exit_anim     : exit_animation.val(),
	        exit_delay    : (Math.round(exit_delay.noUiSlider.get() * 10) / 10),
	        exit_duration : (Math.round(exit_duration.noUiSlider.get() * 10) / 10),
	        ie_type       : preview_ie.con.find('[spinner]').attr('spintype').trim(),
	        ie_spinner    : preview_ie.con.find('[spinner]').attr('spinner').trim(),
	        ie_width      : (preview_ie.con.find('[spinner]').attr('spintype') == 'css') ? $('input[name="ie-css-width"]:checked').val() : parseInt(ie_spinner_width.noUiSlider.get()),
	        ie_color      : ie_spinner_color.val(),
	        ie_background : ie_spinner_background.val(),
	        ie_html       : preview_ie.con.html().trim(),
	        page_entrance : page_entrance.val(),
	        text_spinner  : text_spinner.val().trim(),
	        text_color 	  : text_color.val(),
	        text_size     : parseInt(text_size.noUiSlider.get())	
		};

		if(action == "Update Settings") 
			preloader_options.preloader_id = $(this).attr('preloader_id');

		var data = {
	      'action': 'save_pu_single_page_settings',
	      'preloader_options':preloader_options,
	    };

	    preloader.show(pu_default_settings);

	    $.post(ajaxurl, data, function(res) {

	    	if(res['response'] == 'exist'){

	    		notification.show('error','Oops, Available On field value already exist. Please try again.');

	    	}else if(res['response'] == '1'){
	    		if(preloader_options.preloader_id == "0")
		    		notification.show('updated','Preloader added.');
		    	else
		    		notification.show('updated','Preloader sucessfully updated.');
	    	}else{
	    		notification.show('error','Oops, something went wrong. Please try again.');
	    	}

	    	var data = {paged: "1", order: "desc", orderby: "time"};
	    	pu_list.update(data);

	    	preloader.hide(pu_default_settings);

	    },'json');

	});


	spinner_background.on('blur',function(){
		preview.up_background($(this).val());
	});

	$('.nav-tab-wrapper').on('click','div',function(e){
		var ths = $(this);
	    ths.addClass('nav-tab-active').siblings('div').removeClass('nav-tab-active');
	    ths.closest('.spinner-tabs').find('.spinner-list > div').hide().eq(ths.index()).show();	    
	});


	$(".spinner-options").on("click", ".img-con", function(e){
		preview.tpe = $(this).closest('.spinner-tabs').find('.nav-tab-active').attr('spintype');
		preview.up_spinner($(this).html());
	});
	$(".ie-spinner-options").on("click", ".img-con", function(e){
		preview_ie.tpe = $(this).closest('.spinner-tabs').find('.nav-tab-active').attr('spintype');
		preview_ie.up_spinner($(this).html());	
	});


	$('input[name="css-width"]').change(function(){
		preview.up_width($(this).val());
    });
    $('input[name="ie-css-width"]').change(function(){
		preview_ie.up_width($(this).val());
    });


	$('.spinner-options .upload-preview').on('click','img',function(){
		preview.tpe = 'custom';
		preview.up_spinner($(this).parent().html());
	});
	$('.ie-spinner-options .upload-preview').on('click','img',function(){
		preview_ie.tpe = 'custom';
		preview_ie.up_spinner($(this).parent().html());
	});

	$('.upload-btn').click(function(e) {
		var ths_btn = $(this);

		var image = wp.media({ 
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e){
			// This will return the selected image from the Media Uploader, the result is an object
			var uploaded_image = image.state().get('selection').first();
			var image_url = uploaded_image.toJSON().url;
			
			ths_btn.closest('.custom-upload').find('img').attr({'src':image_url,'spinner':image_url});

			var el 	= ths_btn.closest('.custom-upload').find('.upload-preview');
			var ie = ths_btn.closest('.spinner-tabs').parent().hasClass('ie-spinner-options');

			if(ie){
				preview_ie.tpe = 'custom';	
				preview_ie.up_spinner(el.html());
			}else{
				preview.tpe = 'custom';	
				preview.up_spinner(el.html());
			}
		});
	});

	$('.spinner-options .remove-btn').click(function(e) {
		$('.spinner-options .upload-preview > img').removeAttr('src');	
		var html = (preview.cur == '') ? '<div spintype="css" spinner="ball-clip-rotate" class="la-ball-clip-rotate center-spin"><div></div></div>' : preview.cur;
		preview.con.html(html);
		preview.tpe = preview.con.find('[spinner]').attr('spintype');
		preview.up_spinner(preview.con.html());
	});

	$('.ie-spinner-options .remove-btn').click(function(e) {
		$('.ie-spinner-options .upload-preview > img').removeAttr('src');
		var html = (preview_ie.cur == '') ? '<div spintype="css" spinner="ball-clip-rotate" class="la-ball-clip-rotate center-spin"><div></div></div>' : preview_ie.cur;
		preview_ie.con.html(html);
		preview_ie.tpe = preview_ie.con.find('[spinner]').attr('spintype');
		preview_ie.up_spinner(preview_ie.con.html());
	});


	$('.color-select').wpColorPicker({
		change: function(event, ui){
			var ths = $(this),
				id  = ths.attr('id');

			if(id == "spinner-background"){
				setTimeout(function(){
					preview.up_background(ths.val());
				},100);			
			}else if(id == "ie-spinner-background"){
				setTimeout(function(){
					preview_ie.up_background(ths.val());
				},100);			
			}else if(id == "spinner-color"){
				setTimeout(function(){
					preview.up_color(ths.val());
				},100);			
			}else if(id == "ie-spinner-color"){
				setTimeout(function(){
					preview_ie.up_color(ths.val());
				},100);			
			}
		}
	});


	var preloader = {
		bd: $('body'),
		show:function(opt){
			if($('#TB_window').is(':visible')){
				$('#TB_window').hide();
				$('#TB_overlay').hide();
				tb_remove();
			}
			
			this.bd.prepend('<div class="preloader-ultimate-container">'+opt.html+'</div>');
			this.bd.find('.preloader-ultimate-container').css('background-color','rgba(26,188,156,0.6)');
		},
		hide:function(opt){
			var anim_class = 'animated '+opt.exit_anim;

		    $('.preloader-ultimate-container').addClass(anim_class).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
		      	$(this).remove(); //remove the loading element
		    });	
		}
	}

	var notification = {
		con: $('#notification-messages'),
		show:function(cls, msg){
			$('.is-dismissible').remove();
			this.con.html('<div class="'+cls+' notice is-dismissible below-h2"><p>'+msg+'</p><button type="button" class="notice-dismiss"></button></div>')
		},
		hide:function(){
			this.con.find('.notice').fadeOut(function(){
				$(this).remove();
			});
		}
	}
	$('#notification-messages').on('click','.notice-dismiss',function(){
		var el = $(this).parent();
		el.fadeOut(function(){
			el.remove();
		});
	});

});