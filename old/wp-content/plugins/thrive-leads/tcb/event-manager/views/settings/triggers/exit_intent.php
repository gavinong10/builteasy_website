<?php
$config = $this->config;
?>
<div class="tve-sp"></div>
<h5><?php echo __( "Exit Intent Settings", "thrive-cb" ) ?></h5>

<table>
	<tbody>
	<tr <?php if ( empty( $config['e_mobile'] ) )
		echo ' class="tve_first_row"' ?>>
		<td width="35%">
			<?php echo __( "Perform Action also on Mobile Devices", "thrive-cb" ) ?>
		</td>
		<td width="65%">
			<div class="tve_lightbox_input_holder tve_lightbox_no_label">
				<input id="ei_show_mobile" type="checkbox" name="e_mobile"
				       value="1"<?php if ( ! empty( $config['e_mobile'] ) )
					echo ' checked="checked"' ?>>
				<label for="ei_show_mobile"></label>
			</div>
		</td>
	</tr>
	<tr<?php if ( empty( $config['e_mobile'] ) )
		echo ' class="tve_hidden"' ?> id="e_use_mobile">
		<td width="35%">
			<?php echo __( "It is not possible to detect exit intent on a mobile device, so instead the selected action will be performed after a time delay. How long after page load before the action is performed on mobile devices ?", "thrive-cb" ) ?>
		</td>
		<td width="65%">
			<div class="tve_slider" style="width: 300px; display: inline-block;">
				<div class="tve_slider_element" id="tve_e_timer_slider"></div>
			</div>
			&nbsp;&nbsp;
			<input class="tve_lightbox_input tve_lightbox_small_input" type="text" name="e_delay" id="tve_e_timer"
			       value="<?php echo ! empty( $config['e_delay'] ) ? (int) $config['e_delay'] : '30' ?>" size="3">
			&nbsp; <?php echo __( "Seconds", "thrive-cb" ) ?>

			<div class="clear"></div>
		</td>
	</tr>
	</tbody>
</table>
<script type="text/javascript">
	(function ($) {
		var $e_slider_input = $('#tve_e_timer').change(function () {
				$e_slider.slider('value', parseInt(this.value));
			}),
			$e_slider = $('#tve_e_timer_slider').slider({
				min: 1,
				max: 300,
				step: 1,
				slide: function (event, ui) {
					$e_slider_input.val(ui.value);
				}
			});
		$e_slider_input.change();
		$('#ei_show_mobile').change(function () {
			var $this = $(this), $target = $('#e_use_mobile');
			$this.is(':checked') && $target.removeClass('tve_hidden');
			!$this.is(':checked') && $target.addClass('tve_hidden');
		});
	})(jQuery);
</script>