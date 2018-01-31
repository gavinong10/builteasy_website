jQuery( document ).ready( function ( $ ) {
	$(".noo-add-toggle").click( function() {
		$(this).closest(".noo-add-parent").toggleClass( "wp-hidden-children" );
		return false;
	});

	// Unlimited listing
	$("input[name=noo-membership-packages-listing_num_unlimited]").click( function() {
		if( $(this).is(":checked") ) {
			$("input[name=noo-membership-packages-listing_num").prop("disabled", true);
		} else {
			$("input[name=noo-membership-packages-listing_num").prop("disabled", false);
		}
	});

	$("#noo-membership-packages-add-submit").click( function() {
		var form = $("#noo-membership-packages-add");
		var title = form.find("#noo-membership-packages-title:input").val();
		if( "" === title ) {
			form.find("#noo-membership-packages-title:input").focus();
			return;
		}
		var interval = form.find("#noo-membership-packages-interval:input").val();
		if( "" === interval ) {
			form.find("#noo-membership-packages-interval:input").focus();
			return;
		}
		var interval_unit = form.find("#noo-membership-packages-interval_unit option:selected").val();
		if( "" === interval_unit ) {
			form.find("#noo-membership-packages-interval_unit:input").focus();
			return;
		}
		var price = form.find("#noo-membership-packages-price:input").val();
		if( "" === price ) {
			form.find("#noo-membership-packages-price:input").focus();
			return;
		}
		var listing_num = form.find("#noo-membership-packages-listing_num:input").val();
		var listing_num_unlimited = form.find("#noo-membership-packages-listing_num_unlimited:input").is(":checked");
		if( "" === listing_num && !listing_num_unlimited) {
			form.find("#noo-membership-packages-listing_num:input").focus();
			return;
		}
		var featured_num = form.find("#noo-membership-packages-featured_num:input").val();
		if( "" === featured_num ) {
			form.find("#noo-membership-packages-featured_num:input").focus();
			return;
		}

		var data = {
			'action': 'noo_create_membership',
			'title': title,
			'interval': interval,
			'interval_unit': interval_unit,
			'price': price,
			'listing_num': listing_num,
			'listing_num_unlimited': listing_num_unlimited ? "1" : "0",
			'featured_num': featured_num
		};

		$.post(ajaxurl, data, function(data) {
			if( data == parseInt(data) && data != '-1') {
				var package_select_el = $("#noo-membership-packages-add").closest('.noo-form-group').find('.noo_package_select');
				package_select_el.find("option:selected").attr('selected', '');
				package_select_el.append($('<option/>').val(data).text(title).attr('selected', 'selected'));

				// Clear form
				form.find("#noo-membership-packages-title:input").val('');
				form.find("#noo-membership-packages-interval:input").val('');
				form.find("#noo-membership-packages-interval_unit option:selected").attr('selected', '');
				form.find("#noo-membership-packages-interval_unit option:first-child").attr('selected', 'selected');
				form.find("#noo-membership-packages-price:input").val('');
				form.find("#noo-membership-packages-listing_num:input").val('');
				form.find("#noo-membership-packages-listing_num_unlimited:input").attr('checked', false);
				form.find("#noo-membership-packages-featured_num:input").val('');
			} else {
				return false;
			}
		});
	});

	$('.add_membership_add_info').click( function() {
		var $this = $(this),
		$container = $this.siblings('.noo-membership-additional'),
		$max_fields = $container.attr('data-max'),
		name = $container.attr('data-name'),
		count = $container.find('.additional-field').length;
		if( count >= $max_fields) {
			$this.attr('disabled','disabled');
			return;
		}
		count++;

		$container.append(
			$('<div class="additional-field"></div>')
			.append('<input type="text" value="" name="noo_meta_boxes[' + name + '_' + count + ']" style="max-width:350px;padding-right: 10px;display: inline-block;float: left;" />')
			.append('<input class="button button-secondary delete_membership_add_info" type="button" value="Delete" style="display: inline-block;float: left;" />')
			.append('<br/>'));

		if( count >= $max_fields) {
			$this.attr('disabled','disabled');
		}
	});

	$('.noo-membership-additional').on("click", ".delete_membership_add_info", function() {
		var $this = $(this),
		$container = $this.parents('.noo-membership-additional'),
		$max_fields = $container.attr('data-max'),
		name = $container.attr('data-name'),
		count = $container.find('.additional-field').length;

		$this.parent('.additional-field').remove();
		count--;

		if( count < $max_fields) {
			$this.removeAttr('disabled');
		}

		var count_field = 1;
		$container.find('.additional-field').each(function() {
			$(this).find('input[type=text]').attr('name', 'noo_meta_boxes[' + name + '_' + count_field + ']');
			count_field++;
		});
	});
} );

