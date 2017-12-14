jQuery( document ).ready( function ($) {
		if($('[data-paginate="loadmore"]').find('.loadmore-action').length){
			$('[data-paginate="loadmore"]').each(function(){
				var $this = $(this);
				$this.nooLoadmore({
					agentID: $this.closest('.agent-properties').data('agent-id'),
					navSelector  : $this.find('div.pagination'),            
			   	    nextSelector : $this.find('div.pagination a.next'),    
			   	    itemSelector : '.noo_property',
			   	    finishedMsg: nooPropertyL10n.ajax_finishedMsg
				});
			});
		}
		if($('.properties-ordering').length){
			$('.properties-ordering').find('.dropdown-menu > li > a').on('click',function(e){
				e.stopPropagation();
				e.preventDefault();
				var value = $(this).data('value');
				$(this).closest('.properties-ordering').find('input[name=orderby]').prop('value',value);
				$(this).closest('.dropdown').children('[data-toggle="dropdown"]').dropdown('toggle');
				$(this).closest('form').submit();
				//console.log($(this).closest('form'));
			});
		}
		
		if($('#conactagentform').length){
			$('#conactagentform').each(function(){
				var form = $(this);
				form.ajaxForm({
					beforeSubmit: function(arr, $form, options) {
						$form.find('.form-control').closest('div').removeClass("has-error").find('span.error').remove();
						$form.children('.msg').remove();
						$form.find('img.ajax-loader').css({ visibility: 'visible' });
						return true;
					},
					url: nooPropertyL10n.ajax_url,
					type: 'POST',
					dataType: 'json',
					success: function(data, status, xhr, $form) {
						$form.find('img.ajax-loader').css({ visibility: 'hidden' });
						if (! $.isPlainObject(data) || $.isEmptyObject(data))
							return;
						$.each(data.error,function(i,err){
							$form.find('[name='+err.field+']').closest('div').addClass('has-error').append('<span class="error">' + err.message + '</span>').slideDown('fast')
						});
						if(data.msg != ''){
							$form.append('<span class="msg">'+data.msg+'</span>').slideDown('fast');
							$form.find('[placeholder].form-control').each(function(i, n) {
								$(n).val('');
							});
						}
					},
					error: function(xhr, status, error, $form) {
						
					}
				});
			});
		}
		$('.recent-properties-slider').each(function(){
			var $this = $(this);
			var play = $(this).data("auto") == true;
			var time = $(this).data("slider-time");
			var speed = $(this).data("slider-speed");
			var recentCarouselOptions = {
				responsive: true,
				circular: false,
				infinite:true,
				auto: {
					play : play,
					pauseOnHover: true
				},
				prev: $this.find('.caroufredsel-prev'),
				next: $this.find('.caroufredsel-next'),
				swipe: {
					onTouch: true
				},
				scroll: {
					items: 1,
					duration: speed,
					fx: 'scroll',
					timeoutDuration: time,
					easing: 'swing'
				},
				items: {
					visible: 1
				}
			};
			$this.find('ul').carouFredSel(recentCarouselOptions);
			imagesLoaded($this,function(){
				$this.find('ul').trigger("destroy").carouFredSel(recentCarouselOptions);
			});
			$(window).resize(function(){
				$this.find('ul').trigger("destroy").carouFredSel(recentCarouselOptions);
			});
		});
		$('.recent-agents-slider').each(function(){
			var $this = $(this);
			var recentCarouselOptions = {
				responsive: true,
				circular: true,
				infinite:true,
				// width: '100%',
				// height: 'auto',
				auto: {
					play : false,
					pauseOnHover: true
				},
				prev: $this.find('.caroufredsel-prev'),
				next: $this.find('.caroufredsel-next'),
				mousewheel: true,
				swipe: {
					onMouse: true,
					onTouch: true
				},
				scroll: {
					items: null,
					duration: 600,
					fx: 'scroll',
					timeoutDuration: 2000,
					easing: 'swing'
				},
				items: {
					height:'variable',
					//	height: '30%',	//	optionally resize item-height
					visible: {
						min: 1,
						max: 3
					}
				}
			};
			$this.find('ul').carouFredSel(recentCarouselOptions);
			imagesLoaded($this,function(){
				$this.find('ul').trigger("destroy").carouFredSel(recentCarouselOptions);
			});
			$(window).resize(function(){
				$this.find('ul').trigger("destroy").carouFredSel(recentCarouselOptions);
			});
		});
		$('.recent-properties-featured').each(function(){
			var $this = $(this);
			var play = $(this).data("auto") == true;
			var time = $(this).data("slider-time");
			var speed = $(this).data("slider-speed");
			var recentCarouselOptions = {
				responsive: true,
				circular: false,
				infinite:true,
				auto: {
					play : play,
					pauseOnHover: true
				},
				prev: $this.find('.caroufredsel-prev'),
				next: $this.find('.caroufredsel-next'),
				swipe: {
					onTouch: true
				},
				scroll: {
					items: 1,
					duration: speed,
					fx: 'scroll',
					timeoutDuration: time,
					easing: 'swing'
				},
				width: '100%',
				height: 'variable',
				items: {
					height:'variable',
					visible: 1
				}
			};
			$this.find('ul').carouFredSel(recentCarouselOptions);
			imagesLoaded($this,function(){
				$this.find('ul').trigger("destroy").carouFredSel(recentCarouselOptions);
			});
			$(window).resize(function(){
				$this.find('ul').trigger("destroy").carouFredSel(recentCarouselOptions);
			});
		});
		if($('.property-featured .images').length){
			var $this = $(this);
			var featuredCarouselOptions = {
				responsive: true,
				circular: true,
				infinite:true,
				auto: {
					play : false,
					pauseOnHover: true
				},
				prev: $this.find('.slider-control.prev-btn'),
				next: $this.find('.slider-control.next-btn'),
				swipe: {
					onTouch: true
				},
				scroll: {
					items: 1,
					duration: 600,
					fx: 'scroll',
					timeoutDuration: 2000,
					easing: 'swing'
				},
				width: '100%',
				height: 'variable',
				items: {
					height:'variable',
					visible: 1
				}
			};
			$('.property-featured .images').find('ul').carouFredSel(featuredCarouselOptions);
			imagesLoaded($('.property-featured .images'),function(){
				$('.property-featured .images').find('ul').trigger("destroy").carouFredSel(featuredCarouselOptions);
			});
			$(window).resize(function(){
				$('.property-featured .images').find('ul').trigger("destroy").carouFredSel(featuredCarouselOptions);
			});
		}
		if($('.property-featured .thumbnails').length){
			$('.property-featured .thumbnails').each(function(){
				var $this = $(this);
				var thumbnailsCarouselOptions = {
					responsive: true,
					circular: false,
					infinite:true,
					auto: {
						play : false,
						pauseOnHover: true
					},
					prev: $this.find('.caroufredsel-prev'),
					next: $this.find('.caroufredsel-next'),
					swipe: {
						onTouch: true
					},
					scroll: {
						items: 1,
						duration: 600,
						fx: 'scroll',
						timeoutDuration: 2000,
						easing: 'swing'
					},
					width: '100%',
					height: 'variable',
					items: {
						width: 138,
						height:'variable',
						visible: {
							min: 1,
							max: 5
						}
					}
				};
				$this.find('ul').carouFredSel(thumbnailsCarouselOptions);
				imagesLoaded($this,function(){
					$this.find('ul').trigger("destroy").carouFredSel(thumbnailsCarouselOptions);
				});
				$(window).resize(function(){
					$this.find('ul').trigger("destroy").carouFredSel(thumbnailsCarouselOptions);
				});
			});
			//Single image
			$(document).on('click','.property-featured .thumbnails ul li > a',function(e){
				e.stopPropagation();
				e.preventDefault();
				$(this).closest('.thumbnails').find('.selected').removeClass('selected');
				$(this).closest('li').addClass('selected');
				var rel = $(this).data('rel');
				$('.property-featured .images').find('ul').trigger('slideTo',rel);
			});
		}
		$('.properties-toolbar a').tooltip({html: true,container:$('body'),placement:'bottom'});
		$(document).on('click','.properties-toolbar a',function(e){
			e.stopPropagation();
			e.preventDefault();
			var $this = $(this);
			$this.closest('.properties-toolbar').find('.selected').removeClass('selected');
			$this.addClass('selected');
			$this.closest('.properties').removeClass('grid').removeClass('list').addClass($this.data('mode'));
		});

		if($('.gsearch .glocation').length && $('.gsearch .gsub-location').length) {
			$('.gsearch .glocation').find('.dropdown-menu > li > a').on('click',function(e){
				e.stopPropagation();
			    e.preventDefault();
			    var val = $(this).data('value');
			    $('.gsearch .gsub-location .dropdown').children('[data-toggle="dropdown"]').text($('.gsearch .gsub-location .dropdown-menu > li:first a').text());
			    $('.gsearch .gsub-location .gsub_location_input').val('');
			    $('.gsearch .gsub-location').find('.dropdown-menu > li').each( function() {
			    	var parent = $(this).data('parent-location');
			    	if( typeof(parent) !== "undefined" && parent != val ) {
			    		$(this).hide();
			    	} else {
			    		$(this).show();
			    	}
			    });
			});
		}
});