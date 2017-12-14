<?php /* displays the controls required for adding / updating an event*/ ?>

<h4><?php echo empty( $is_edit ) ? 'Add New Event' : 'Edit Event' ?></h4>
<hr class="tve_lightbox_line"/>
<div class="tve_event_manager" id="tve_current_event_settings">
	<table class="tve_event_manager_list">
		<thead>
		<tr>
			<th width="35%"><?php echo __( "Trigger", "thrive-cb" ) ?></th>
			<th width="65%"><?php echo __( "Action", "thrive-cb" ) ?></th>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<td>
				<div class="tve_lightbox_select_holder">
					<select name="t" id="tve_event_trigger" class="tve_ctrl_validate tve_event_onchange"
					        data-validators="required" data-action="action_settings">
						<option
							value=""<?php echo empty( $selected_trigger_code ) ? ' selected="selected"' : '' ?>><?php echo __( "Select Trigger", "thrive-cb" ) ?>
						</option>
						<?php foreach ( $triggers as $code => $trigger ) : ?>
							<option
								value="<?php echo $code ?>"<?php echo $selected_trigger_code == $code ? ' selected="selected"' : '' ?>><?php echo $trigger->getName() ?></option>
						<?php endforeach ?>
					</select>
				</div>
			</td>
			<td>
				<div class="tve_lightbox_select_holder">
					<select class="tve_event_onchange tve_ctrl_validate" name="a" id="tve_event_action"
					        data-action="action_settings" data-validators="required">
						<option
							value=""<?php echo empty( $selected_action_code ) ? ' selected="selected"' : '' ?>><?php echo __( "Select Action", "thrive-cb" ) ?>
						</option>
						<?php foreach ( $actions as $code => $action ) : ?>
							<option
								value="<?php echo $code ?>"<?php echo $selected_action_code == $code ? ' selected="selected"' : '' ?>><?php echo $action->getName() ?></option>
						<?php endforeach ?>
					</select>
				</div>
				<span
					id="tve-action-warning" <?php echo empty( $selected_action_code ) || $selected_action_code === 'thrive_wistia' || $selected_action_code === 'thrive_tooltip' ? '' : ' style="display: none"' ?>
					class="tve_message tve_error_box"></span>
			</td>
		</tr>
		</tfoot>
	</table>
	<div id="tve_event_settings">
		<?php echo $item_settings; ?>
	</div>

</div>
<div class="tve_clear" style="height: 20px;"></div>
<div class="tve_landing_pages_actions">
	<div class="tve_editor_button tve_editor_button_cancel tve_right tve_event_onclick tve_button_margin"
	     data-action="list">
		<?php echo __( "Cancel", "thrive-cb" ) ?>
	</div>
	<div class="tve_editor_button tve_editor_button_success tve_right tve_event_onclick" data-action="save"
	     id="tve_event_save">
		<?php echo __( "Save Event", "thrive-cb" ) ?>
	</div>
</div>
<div class="tve_clear"></div>
<script type="text/javascript">
	(function ( $ ) {
			var c_action = <?php echo json_encode( $selected_action_code ) ?>,
				$t = $( '#tve_event_trigger' ),
				$a = $( '#tve_event_action' ),
				wistia_wm = '<?php echo __( 'Currently, you can only use the click trigger for Wistia popover embeds' ) ?>',
				tooltip_wm = '<?php echo __( 'Currently, you can only use the mouseover trigger for tooltips' ) ?>',
				next_step_wm = '<?php echo __( 'Currently, you can only use the click trigger for Next Step Event' ) ?>',
				$wm = $( '#tve-action-warning' );

			function set_trigger() {
				if ( c_action === 'thrive_wistia' ) {
					$t.val( 'click' );
					$wm.html( wistia_wm );
					$wm.show();
				} else if ( c_action === 'thrive_tooltip' ) {
					$t.val( 'mouseover' );
					$wm.html( tooltip_wm );
					$wm.show();
				} else if ( c_action === 'thrive_quiz_next_step' ) {
					$t.val( 'click' );
					$wm.html( next_step_wm );
					$wm.show();
				} else {
					$wm.hide();
				}
			}

			set_trigger();

			$t.add( $a ).on( 'change', function () {
				c_action = $a.val();
				set_trigger();
			} );

		})( jQuery );
</script>