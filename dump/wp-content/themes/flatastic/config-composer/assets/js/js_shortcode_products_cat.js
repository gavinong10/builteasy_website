(function ($) {

	$.mad_composer_mod = $.mad_composer_mod || {};

	$.mad_composer_mod.paramProduct = function () {
		({
			init: function() {
				this.listeners();
			},
			madRemove: function (el) {
				var vals = $('.mad-custom-val').val().split(","), newVals = '';

				for (var i = 0; i < vals.length; i++) {
					if( vals[i] != el.attr('data-val') ) {
						if (newVals != '') {
							newVals += ',';
						}
						newVals += vals[i];
					}
				}

				$('.mad-custom-val').val(newVals);
				el.remove();
			},
			madChange: function () {
				$('.mad-custom li').removeClass('selected');
				var vals = $('.mad-custom-val').val().split(",");

				for (var i = 0; i < vals.length; i++) {
					$('.mad-custom li[data-val="' + vals[i] + '"]').addClass('selected');
				}
			},
			listeners: function () {
				var base = this;

				$('.mad-custom li').on('click', function () {

					if ( $(this).hasClass('selected') ) { return true; }

					var prevVal = $('.mad-custom-val').val();
					if (prevVal != '') {
						prevVal += ',';
					}

					$('.mad-custom-val').val( prevVal + $(this).attr('data-val') );

					$('.mad-custom-vals').html($('.mad-custom-vals').html() + '<li data-val="' + $(this).attr('data-val') + '">' + $(this).html() + '<button>×</button></li>');
					$('.mad-custom-vals li button').on('click', function() {
						base.madRemove($(this).parent());
						base.madChange();
					});
					base.madChange();
				});

				$('.mad-custom-vals').on('click', 'li', function() {
					base.madRemove($(this));
					base.madChange();
				});

			}

		}.init());

	}

	$(function () {
		$.mad_composer_mod.paramProduct();
	});

})(jQuery);