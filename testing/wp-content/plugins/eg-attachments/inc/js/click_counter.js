function TrackClick(link, attah_id, post_id) {
	jQuery.ajax({
		type: "POST",
		url: EgaAjax.ajax_url,
		cache: false,
		data: {
			action: 'ega_record_click',
			attach_id: attah_id,
			parent_id: post_id,
			nonce: EgaAjax.nonce,
			title: link.getAttribute("title")
		}
	}); // End of jQuery Index

}