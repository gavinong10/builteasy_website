<?php
if ( $this->success_message ) : ?>
	<br>
	<div class="tve_message tve_success" id="tve_landing_page_msg"><?php echo $this->success_message ?></div>
<?php endif ?>
<h5><?php echo $title ?></h5>

<table class="tve_no_brdr">
	<tr>
		<td width="35%"><?php echo __( 'Which lightbox state should be displayed ?', 'thrive-leads' ) ?></td>
		<td width="65%">
			<div class="tve_lightbox_select_holder">
				<select name="s" class="tve_ctrl_validate" data-validators="required">
					<option value=""><?php echo __( 'Select lightbox state', 'thrive-leads' ) ?></option>
					<?php foreach ( $this->states as $state ) : ?>
						<option value="<?php echo $state['key'] ?>"<?php
						echo ! empty( $this->config['s'] ) && $this->config['s'] == $state['key'] ? ' selected="selected"' : '' ?>><?php echo $state['state_name'] ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</td>
	</tr>
	<?php if ( $this->animation_settings ) : ?>
		<tr>
			<td>Lightbox animation</td>
			<td>
				<div class="tve_lightbox_select_holder">
					<select name="a" id="tve-animation-preview">
						<?php foreach ( $this->animations as $key => $animation ) : ?>
							<option
								value="<?php echo $key ?>"<?php echo ! empty( $this->config['a'] ) && $this->config['a'] == $key ? ' selected="selected"' : '' ?>><?php echo $animation->get_title() ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top">Animation preview</td>
			<td>
				<div id="tve-animation-target" class="tve-animation-preview" style="height: 50px;width:100%">
					<img id="tve-anim-target" class="tve-tl-anim" src="<?php echo TVE_LEADS_URL . 'admin/img/logo.png' ?>" height="30">
				</div>
			</td>
		</tr>
	<?php endif ?>
</table>
<script type="text/javascript">
	jQuery(function () {
		var $target = jQuery('#tve-anim-target'),
			$targetParent = jQuery('#tve-animation-target'),
			$select = jQuery('#tve-animation-preview').on('change', function () {
				animate();
			});

		function animate() {
			var animation = $select.val(), t_id = 0;

			function do_animation() {
				clearTimeout(t_id);
				jQuery('#tve_lightbox_content').css('overflow-x', 'hidden');
				$target.css('display', 'none').removeClass(function (index, cls) {
					var _list = cls.split(' '), toRemove = '';
					_.each(_list, function (item) {
						if (item.indexOf('tl-anim') === 0) {
							toRemove += item;
						}
					});
					return toRemove;
				}).removeClass('tve-leads-triggered').addClass('tl-anim-' + animation);
				$targetParent.removeClass(function (index, cls) {
					var _list = cls.split(' '), toRemove = '';
					_.each(_list, function (item) {
						if (item.indexOf('tl-anim') === 0) {
							toRemove += item;
						}
					});
					return toRemove;
				}).addClass('tl-anim-' + animation);
				setTimeout(function () {
					$target.css('display', '');
				}, 400);

				setTimeout(function () {
					$target.addClass('tve-leads-triggered');
				}, 500);

			}

			setTimeout(do_animation, 100);
		}

		animate();
	});
</script>