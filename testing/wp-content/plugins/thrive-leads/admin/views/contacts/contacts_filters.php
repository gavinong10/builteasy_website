<?php

$per_page_options = array( 20, 50, 100 );

$dashboard_data = array(
	'global_settings' => array(
		'ajax_load' => tve_leads_get_option( 'ajax_load' ),
	)
);

$contacts_data = array(
	'lead_groups'       => tve_leads_get_groups(
		array(
			'full_data'       => false,
			'tracking_data'   => false,
			'completed_tests' => false,
			'active_tests'    => false,
		)
	),
	'shortcodes'        => tve_leads_get_shortcodes(
		array( 'active_test' => false )
	),
	'two_step_lightbox' => tve_leads_get_two_step_lightboxes(
		array( 'active_test' => false )
	),
	'one_click_signup'  => tve_leads_get_one_click_signups()
);

?>

<script type="text/javascript">
	var TVE_Page_Data = {
		globalSettings: <?php echo json_encode( $dashboard_data['global_settings'] ) ?>,
	};
</script>

<?php if ( $which == "top" ): ?>
	<input type="hidden" name="tve_template_redirect_contacts" value="true"/>
<?php endif; ?>
<div class="tvd-input-field tve-inline-block-top tvd-margin-left-small">
	<input type="text" <?php if ( $which == "top" ): ?>name="tve-start-date"<?php endif; ?> class="tve-contacts-start-date" value="<?php echo $start_date; ?>" id="tve-contacts-start-date"/>
	<label for="tve-contacts-start-date" class="tvd-active"><?php echo __( 'From', "thrive-leads" ); ?></label>
</div>
<div class="tvd-input-field tve-inline-block-top tvd-margin-left-small">
	<input type="text" <?php if ( $which == "top" ): ?>name="tve-end-date"<?php endif; ?> class="tve-contacts-end-date" value="<?php echo $end_date; ?>" id="tve-contacts-end-date"/>
	<label for="tve-contacts-end-date" class="tvd-active"><?php echo __( 'To', "thrive-leads" ); ?></label>
</div>
<div class="tvd-input-field tve-inline-block-top tvd-margin-left-small">
	<select class="tve-contacts-source" <?php if ( $which == "top" ): ?>name="tve-source"<?php endif; ?> autocomplete="off" id="tve-contacts-source">
		<option value="-1"><?php echo __( 'All', 'thrive-leads' ) ?></option>
		<optgroup label="<?php echo __( 'Lead Groups', 'thrive-leads' ); ?>">
			<?php if ( ! empty( $contacts_data['lead_groups'] ) ): ?>
				<?php foreach ( $contacts_data['lead_groups'] as $group ) : ?>
					<option value="<?php echo $group->ID ?>" <?php echo $source == $group->ID ? 'selected' : ''; ?>><?php echo $group->post_title ?></option>
				<?php endforeach ?>
			<?php else: ?>
				<option value="-1" disabled>(<?php echo __( 'empty', 'thrive-leads' ) ?>)</option>
			<?php endif; ?>
		</optgroup>
		<optgroup label="<?php echo __( 'Shortcodes', 'thrive-leads' ); ?>">
			<?php if ( ! empty( $contacts_data['shortcodes'] ) ): ?>
				<?php foreach ( $contacts_data['shortcodes'] as $shortcode ) : ?>
					<option value="<?php echo $shortcode->ID ?>" <?php echo $source == $shortcode->ID ? 'selected' : ''; ?>><?php echo $shortcode->post_title ?></option>
				<?php endforeach ?>
			<?php else: ?>
				<option value="-1" disabled>(<?php echo __( 'empty', 'thrive-leads' ) ?>)</option>
			<?php endif; ?>
		</optgroup>
		<optgroup label="<?php echo __( 'ThriveBoxes', 'thrive-leads' ); ?>">
			<?php if ( ! empty( $contacts_data['two_step_lightbox'] ) ): ?>
				<?php foreach ( $contacts_data['two_step_lightbox'] as $tsl ) : ?>
					<option value="<?php echo $tsl->ID ?>" <?php echo $source == $tsl->ID ? 'selected' : ''; ?>><?php echo $tsl->post_title ?></option>
				<?php endforeach ?>
			<?php else: ?>
				<option value="-1" disabled>(<?php echo __( 'empty', 'thrive-leads' ) ?>)</option>
			<?php endif; ?>
		</optgroup>
		<optgroup label="<?php echo __( 'Signup Segue', 'thrive-leads' ); ?>">
			<?php if ( ! empty( $contacts_data['one_click_signup'] ) ): ?>
				<?php foreach ( $contacts_data['one_click_signup'] as $ocs ) : ?>
					<option value="<?php echo $ocs->ID ?>" <?php echo $source == $ocs->ID ? 'selected' : ''; ?>><?php echo $ocs->post_title ?></option>
				<?php endforeach ?>
			<?php else: ?>
				<option value="-1" disabled>(<?php echo __( 'empty', 'thrive-leads' ) ?>)</option>
			<?php endif; ?>
		</optgroup>
	</select>
	<label for="tve-contacts-source">
		<?php echo __( 'Source', "thrive-leads" ); ?>
	</label>
</div>
<div class="tvd-input-field tve-inline-block-top tvd-margin-left-small">
	<select class="tve-contacts-per-page" <?php if ( $which == "top" ): ?>name="tve-per-page"<?php endif; ?> autocomplete="off" id="tve-contacts-per-page">
		<?php foreach ( $per_page_options as $value ): ?>
			<option <?php echo ( $value == $per_page ) ? "selected" : ""; ?> value="<?php echo $value; ?>"><?php echo $value . ' '; ?><?php echo __( 'per page', "thrive-leads" ); ?></option>
		<?php endforeach; ?>
	</select>
	<label for="tve-contacts-per-page"><?php echo __( 'Show', "thrive-leads" ); ?></label>
</div>
<div class="tvd-margin-left-small tve-inline-block-top tve-margin-top-13">
	<input type="submit" class="button action" value="<?php echo __( 'Filter', "thrive-leads" ); ?>">
</div>
