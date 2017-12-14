<?php
$is_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;
if ( ! $is_ajax && ! is_editor_page() ) {
	return;
}
global $variation; // this is the main variation (variation parent)
if ( ! isset( $current_variation ) ) {
	$current_variation = $variation; // this is the variation being edited now
}
/**
 * Shows a bar at the bottom of the page having all of the states defined for this form
 */
$states             = tve_leads_get_form_related_states( $variation );
$parent_form_type   = tve_leads_get_form_type_from_variation( $variation, true );
$already_subscribed = false; ?>
<?php if ( empty( $do_not_wrap ) ) : ?>
	<div class="tve_event_root">
	<div class="tl-form-states-container" id="tl-form-states">
<?php endif ?>
	<div class="multistep-lightbox"<?php echo ! empty( $_COOKIE['tve_leads_state_collapse'] ) ? ' style="display:none"' : '' ?>>
		<div class="multistep-lightbox-heading">
			<button
				data-ctrl="function:ext.tve_leads.state.toggle_manager"
				title="<?php echo __( 'Minimize', 'thrive-leads' ) ?>" class="tve_click multistep-lightbox-minimize">
				<span class="tve_icm tve-ic-backward"></span>
			</button>
		</div>
		<div class="multistep-lightbox-steps-body">
			<ul class="multistep-lightbox-steps<?php echo count( $states ) > 6 ? ' tl-smaller' : '' ?>">
				<?php foreach ( $states as $index => $v ) : if ( $v['form_state'] == 'already_subscribed' ) {
					$already_subscribed = true;
				} ?>
					<li
						data-ctrl="function:ext.tve_leads.state.state_click"
						data-id="<?php echo $v['key'] ?>"
						class="tve_click<?php echo $v['key'] == $current_variation['key'] ? ' lightbox-step-active' : '' ?>">
						<?php if ( $v['form_state'] == 'already_subscribed' ): ?>
							<div style="left: 7px;top: 7px;position: absolute;">
								<a
									href="javascript:void(0)"
									data-ctrl="function:ext.tve_leads.state.visibility"
									data-id="<?php echo $v['key'] ?>"
									data-state="<?php echo $v['form_state'] ?>"
									data-visible="<?php echo tve_leads_check_variation_visibility( $v ) ? 0 : 1; ?>"
									class="lightbox-step-visibility tve_click">
									<i data-title="<?php echo __( 'This is the content that displays when a visitor has already subscribed to the form. Click the icon if you would prefer to simply hide the form completely to already subscribed visitors!', 'thrive-leads' ) ?>"
									   class="<?php echo $v['key'] == $current_variation['key'] ? 'tve_tooltip' : ''; ?> tve-icon-<?php if ( tve_leads_check_variation_visibility( $v ) ): ?>in<?php endif; ?>visible"></i>
								</a>
							</div>
						<?php else: ?>
							<button
								data-ctrl="function:ext.tve_leads.state.duplicate"
								data-id="<?php echo $v['key'] ?>"
								data-state="<?php echo $v['form_state'] ?>"
								title="<?php echo __( 'Duplicate state', 'thrive-leads' ) ?>"
								class="lightbox-step-duplicate tve_click"></button>
						<?php endif; ?>
						<?php if ( $index > 0 ) : ?>
							<button
								data-ctrl="function:ext.tve_leads.state.remove"
								data-id="<?php echo $v['key'] ?>"
								title="<?php echo __( 'Delete state', 'thrive-leads' ) ?>"
								class="lightbox-step-delete tve_click"></button>
						<?php endif ?>

						<span class="lightbox-step-name"><?php echo $v['state_name'] ?></span>
					</li>
				<?php endforeach ?>
				<li
					data-ctrl="function:ext.tve_leads.state.toggle_add"
					class="lightbox-step-add tve_click">
					<span class="lightbox-step-add-title"><?php echo __( 'Add', 'thrive-leads' ) ?></span>
                        <span class="lightbox-step-add-menu">
                            <ul>
	                            <li><a class="tve_click" data-ctrl="function:ext.tve_leads.state.add" data-state="default" href="javascript:void(0)"><?php echo __( 'New State', 'thrive-leads' ) ?></a></li>
	                            <li><a class="tve_click" <?php echo ( $already_subscribed ) ? 'data-subscribed="1" ' : '' ?>data-ctrl="function:ext.tve_leads.state.add" data-state="already_subscribed" href="javascript:void(0)"><?php echo __( 'Already Subscribed', 'thrive-leads' ) ?></a></li>
	                            <?php if ( $parent_form_type != 'lightbox' && $parent_form_type != 'screen_filler' ) : ?>
		                            <li><a class="tve_click" data-ctrl="function:ext.tve_leads.state.add" data-state="lightbox" href="javascript:void(0)"><?php echo __( 'Lightbox', 'thrive-leads' ) ?></a></li>
	                            <?php endif ?>
                            </ul>
                        </span>
				</li>
			</ul>
		</div>
	</div>
<?php
if ( empty( $do_not_wrap ) ) :
	$position = isset( $_COOKIE['tve_leads_states_pos'] ) ? json_decode( stripslashes( $_COOKIE['tve_leads_states_pos'] ), true ) : array();
	?>
	</div>
	<div class="tl-state-minimized"<?php echo ! empty( $position['top'] ) && ! empty( $position['left'] ) ? sprintf( ' style="right:auto;top:%spx;left:%spx"', (int) $position['top'], (int) $position['left'] ) : '' ?>>
		<div class="multistep-lightbox-heading">
			<button
				data-ctrl="function:ext.tve_leads.state.toggle_manager"
				data-expand="1"
				title="<?php echo __( 'Restore', 'thrive-leads' ) ?>" class="tve_click multistep-lightbox-minimize">
				<span class="tve_icm tve-ic-forward"></span>
			</button>
		</div>
		<div class="multistep-lightbox-steps-body">
			<div class="tl-body-shadow">
				<span class="tve_icm tve-ic-my-library-books"></span> <?php echo __( 'States', 'thrive-leads' ) ?>
			</div>
		</div>
	</div>
	</div>
<?php endif ?>