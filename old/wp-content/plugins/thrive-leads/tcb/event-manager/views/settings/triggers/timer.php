<?php
$config = $this->config;
?>
<div class="tve-sp"></div>
<h5><?php echo __( "Timer (duration after page load) Settings", "thrive-cb" ) ?></h5>

<table>
	<tbody>
	<tr>
		<td width="35%" style="vertical-align: middle"><?php echo __( "How many seconds after page load should the event be triggered ?", "thrive-cb" ) ?>
		</td>
		<td width="65%" style="vertical-align: middle">
			<div class="tve_slider" style="width: 300px; display: inline-block;">
				<div class="tve_slider_element" id="tve_t_timer_slider"></div>
			</div>
			&nbsp;&nbsp;
			<input class="tve_lightbox_input tve_lightbox_small_input" type="text" name="t_delay" id="tve_t_timer"
			       value="<?php echo ! empty( $config['t_delay'] ) ? (int) $config['t_delay'] : '30' ?>" size="3">
			&nbsp; <?php echo __( "Seconds", "thrive-cb" ) ?>

			<div class="clear"></div>
		</td>
	</tr>
	</tbody>
</table>
<script type="text/javascript">
	(function ($) {
		$(function () {
			var $slider_input = $('#tve_t_timer').change(function () {
					$t_slider.slider('value', parseInt(this.value));
				}),
				$t_slider = $('#tve_t_timer_slider').slider({
					min: 1,
					max: 300,
					step: 1,
					slide: function (event, ui) {
						$slider_input.val(ui.value);
					}
				});
			$slider_input.change();
		});
	})(jQuery);
</script>