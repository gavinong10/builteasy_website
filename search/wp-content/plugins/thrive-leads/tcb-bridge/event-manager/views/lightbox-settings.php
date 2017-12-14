<?php $animation_classes = '';

if ( $this->success_message ) : ?>
	<br>
	<div class="tve_message tve_success" id="tve_landing_page_msg"><?php echo $this->success_message ?></div>
<?php endif ?>
<h5><?php echo __( 'Thrive Leads ThriveBox', 'thrive-leads' ) ?></h5>

<table class="tve_no_brdr">
	<tr>
		<td width="35%"><?php echo __( 'Which ThriveBox should be displayed ?', 'thrive-leads' ) ?></td>
		<td width="65%">
			<div class="tve_lightbox_select_holder">
				<select name="l_id" class="tve_ctrl_validate" id="tl-choose-2-step" data-validators="required">
					<option value=""><?php echo __( 'Select Lighbox', 'thrive-leads' ) ?></option>
					<?php foreach ( $this->lightboxes as $lightbox ) : ?>
						<option value="<?php echo $lightbox->ID ?>"<?php
						echo ! empty( $this->config['l_id'] ) && $this->config['l_id'] == $lightbox->ID ? ' selected="selected"' : '' ?>><?php echo $lightbox->post_title ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</td>
	</tr>
	<tr>
		<td><?php echo __( 'Lightbox animation', 'thrive-leads' ) ?></td>
		<td>
			<div class="two-step-edit-message tl-no-lightbox" style="display:none">
				<?php echo __( 'Choose a ThriveBox', 'thrive-leads' ) ?>
			</div>
			<div class="two-step-edit-message tl-test-running" style="display:none">
				<?php echo __( 'You cannot change the animation settings, as you currently have an A/B test running on this lightbox.', 'thrive-leads' ) ?>
				<?php echo sprintf(
					__( 'Click %shere%s to view the test (it will open in a new tab)', 'thrive_leads' ),
					'<a href="#" target="_blank" class="tl-admin-link">',
					'</a>'
				) ?>
			</div>
			<div class="two-step-edit-message tl-edit-two-step" style="display:none">
				<?php echo __( 'You can change the animation from your Thrive Leads Dashboard page.', 'thrive-leads' ) ?>
				<?php echo sprintf(
					__( 'Click %shere%s to change the settings (it will open in a new tab)', 'thrive_leads' ),
					'<a href="#" target="_blank" class="tl-admin-link">',
					'</a>'
				) ?>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>

<script type="text/javascript">
	var Thrive_Leads_Two_Steps = <?php echo json_encode( $this->lightboxes ) ?>;
	var Thrive_Leads_Admin_Url = <?php echo json_encode( admin_url( 'admin.php?page=thrive_leads_dashboard' ) . '#2step-lightbox/%ID%' ) ?>;
	var Thrive_Leads_Admin_Test_Url = <?php echo json_encode( admin_url( 'admin.php?page=thrive_leads_dashboard' ) . '#test/%ID%' ) ?>;
	jQuery(function () {
		jQuery('#tl-choose-2-step').on('change', function () {
			var $this = jQuery(this),
				$messages = jQuery('.two-step-edit-message').hide();

			if (!$this.val()) {
				return $messages.filter('.tl-no-lightbox').show();
			}
			if (Thrive_Leads_Two_Steps[$this.val()].active_test) {
				var test_id = Thrive_Leads_Two_Steps[$this.val()].active_test.id;
				$messages.find('.tl-admin-link').attr('href', Thrive_Leads_Admin_Test_Url.replace('%ID%', test_id));
				$messages.filter('.tl-test-running').show();
			} else {
				$messages.find('.tl-admin-link').attr('href', Thrive_Leads_Admin_Url.replace('%ID%', $this.val()));
				$messages.filter('.tl-edit-two-step').show();
			}
		}).trigger('change');
	});
</script>