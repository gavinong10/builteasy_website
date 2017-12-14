<div class="tve-sp"></div>
<h6><?php echo __( 'Cycle day:', TVE_DASH_TRANSLATE_DOMAIN ) ?></h6>
<div class="tvd-row tvd-collapse">
	<div class="tvd-col tvd-s4">
		<div class="tvd-input-field">
			<input id="get-response_cycleday" type="text"
			       maxlength="3"
			       class="tve-api-extra tve_lightbox_input tve_lightbox_input_inline"
			       name="get-response_cycleday"
			       value="<?php echo ! empty( $data['cycleday'] ) ? $data['cycleday'] : '0' ?>"
			       size="40"/>
			<p><?php echo __( 'Number of the cycle day(between 0 and 103)', TVE_DASH_TRANSLATE_DOMAIN ) ?></p>
		</div>
	</div>
</div>
<script type="text/javascript">
	(function ( $ ) {
		var TVE_Content_Builder = TVE_Content_Builder || {};
		if ( TVE_Content_Builder.auto_responder ) {
			TVE_Content_Builder.auto_responder['get-response'] = TVE_Content_Builder.auto_responder['get-response'] || {};

			TVE_Content_Builder.auto_responder['get-response'].validate = function () {
				var $input = $( '#get-response_cycleday' ),
					value = $input.val();


				if ( isNaN( value ) || value < 0 || value > 103 ) {
					alert( 'Invalid cycle day' );
					return false;
				}

				return true;
			}
		}
	})( jQuery );
</script>
