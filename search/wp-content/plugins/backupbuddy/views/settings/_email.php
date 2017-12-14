<?php
if ( !is_admin() ) { die( 'Access Denied.' ); }



$settings_form->add_setting( array(
		'type'		=>		'title',
		'name'		=>		'title_email',
		'title'		=>		__( 'Email Notifications', 'it-l10n-backupbuddy' ),
	) );


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'no_new_backups_error_days',
	'title'		=>		__('Send notification after period of no backups', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('[Example: 30] - Maximum number of days (set to 0 to disable) that may pass with no new backups created before sending an error notifcation email. Create schedules to automatically back up your site regularly. Alert notification emails will only be sent once every 24 hours at most.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'required|string[0-99999]',
	'css'		=>		'width: 50px;',
	'after'		=>		' days',
	'rules'		=>		'int',
) );


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_notify_error',
	'title'		=>		__('Error notification recipient(s)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('Email address to send notifications to upon encountering any errors or problems. Use commas to separate multiple email addresses.', 'it-l10n-backupbuddy' ),
	'css'		=>		'width: 250px;',
	'after'		=>		' <a href="" class="pb_backupbuddy_customize_email_error" style="text-decoration: none;">Customize Email</a> | <a href="" id="pb_backupbuddy_email_error_test" style="text-decoration: none;" title="Send a test email to the listed email address(es) to test email sending functionality.">Test</a> &nbsp;&nbsp;&nbsp; <span id="emailErrorNotifyHiddenAlert" style="display: none;"><span class="pb_label">Tip</span> ' . __( 'This is highly suggested to be notified of backup failure.', 'it-l10n-backupbuddy' ) . '</span>',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_notify_error_subject',
	'title'		=>		' ',
	'rules'		=>		'required|string[1-500]',
	'css'		=>		'width: 360px;',
	'row_class'	=>		'pb_backupbuddy_customize_email_error_row',
	'before'	=>		'<span style="display: inline-block; width: 65px;">' . __('Subject', 'it-l10n-backupbuddy' ) . ':</span>',
) );
$settings_form->add_setting( array(
	'type'		=>		'textarea',
	'name'		=>		'email_notify_error_body',
	'title'		=>		' ',
	'classes'	=>		'regular-text',
	'rules'		=>		'required|string[1-500]',
	'css'		=>		'width: 360px; height: 75px;',
	'row_class'	=>		'pb_backupbuddy_customize_email_error_row',
	'before'	=>		'<span style="display: inline-block; width: 65px; float: left;">' . __('Body', 'it-l10n-backupbuddy' ) . ':</span>',
	'after'		=>		'<div style="margin-left: 65px; width: 360px;" class="description">
							Variables: {site_url} {backupbuddy_version} {current_datetime} {message}
						</div>',
) );


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_notify_scheduled_start',
	'title'		=>		__('Scheduled backup started email recipient(s)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('Email address to send notifications to upon scheduled backup starting. Use commas to separate multiple email addresses. Notifications will not be sent for remote destination file transfers.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'string[0-500]',
	'css'		=>		'width: 250px;',
	'after'		=>		' <a href="" class="pb_backupbuddy_customize_email_scheduled_start" style="text-decoration: none;">Customize Email</a>',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_notify_scheduled_start_subject',
	'title'		=>		' ',
	'rules'		=>		'required|string[1-500]',
	'css'		=>		'width: 360px;',
	'row_class'	=>		'pb_backupbuddy_customize_email_scheduled_start_row',
	'before'	=>		'<span style="display: inline-block; width: 65px;">' . __('Subject', 'it-l10n-backupbuddy' ) . ':</span>',
) );
$settings_form->add_setting( array(
	'type'		=>		'textarea',
	'name'		=>		'email_notify_scheduled_start_body',
	'title'		=>		' ',
	'rules'		=>		'required|string[1-500]',
	'css'		=>		'width: 360px; height: 75px;',
	'row_class'	=>		'pb_backupbuddy_customize_email_scheduled_start_row',
	'before'	=>		'<span style="display: inline-block; width: 65px; float: left;">' . __('Body', 'it-l10n-backupbuddy' ) . ':</span>',
	'after'		=>		'<div style="margin-left: 65px; width: 360px;" class="description">
							Variables: {site_url} {backupbuddy_version} {current_datetime} {message}
						</div>',
) );


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_notify_scheduled_complete',
	'title'		=>		__('Scheduled backup completed email recipient(s)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('Email address to send notifications to upon scheduled backup completion. Use commas to separate multiple email addresses.', 'it-l10n-backupbuddy' ),
	'css'		=>		'width: 250px;',
	'after'		=>		' <a href="" class="pb_backupbuddy_customize_email_scheduled_complete" style="text-decoration: none;">Customize Email</a>',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_notify_scheduled_complete_subject',
	'title'		=>		' ',
	'rules'		=>		'required|string[1-500]',
	'css'		=>		'width: 360px;',
	'row_class'	=>		'pb_backupbuddy_customize_email_scheduled_complete_row',
	'before'	=>		'<span style="display: inline-block; width: 65px;">' . __('Subject', 'it-l10n-backupbuddy' ) . ':</span>',
) );
$settings_form->add_setting( array(
	'type'		=>		'textarea',
	'name'		=>		'email_notify_scheduled_complete_body',
	'title'		=>		' ',
	'classes'	=>		'regular-text',
	'rules'		=>		'required|string[1-500]',
	'css'		=>		'width: 360px; height: 75px;',
	'row_class'	=>		'pb_backupbuddy_customize_email_scheduled_complete_row',
	'before'	=>		'<span style="display: inline-block; width: 65px; float: left;">' . __('Body', 'it-l10n-backupbuddy' ) . ':</span>',
	'after'		=>		'<div style="margin-left: 65px; width: 360px;" class="description">
							Variables: {site_url} {backupbuddy_version} {current_datetime} {message}
							{download_link} {backup_size} {backup_type} {backup_file} {backup_serial}
						</div>',
) );


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_notify_send_finish',
	'title'		=>		__('File destination send finished email recipient(s)', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('Email address to send notifications to upon remote sends finishing. Use commas to separate multiple email addresses.', 'it-l10n-backupbuddy' ),
	'rules'		=>		'string[0-500]',
	'css'		=>		'width: 250px;',
	'after'		=>		' <a href="" class="pb_backupbuddy_customize_send_finish" style="text-decoration: none;">Customize Email</a>',
) );
$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_notify_send_finish_subject',
	'title'		=>		' ',
	'rules'		=>		'required|string[1-500]',
	'css'		=>		'width: 360px;',
	'row_class'	=>		'pb_backupbuddy_customize_email_send_finish_row',
	'before'	=>		'<span style="display: inline-block; width: 65px;">' . __('Subject', 'it-l10n-backupbuddy' ) . ':</span>',
) );
$settings_form->add_setting( array(
	'type'		=>		'textarea',
	'name'		=>		'email_notify_send_finish_body',
	'title'		=>		' ',
	'rules'		=>		'required|string[1-500]',
	'css'		=>		'width: 360px; height: 75px;',
	'row_class'	=>		'pb_backupbuddy_customize_email_send_finish_row',
	'before'	=>		'<span style="display: inline-block; width: 65px; float: left;">' . __('Body', 'it-l10n-backupbuddy' ) . ':</span>',
	'after'		=>		'<div style="margin-left: 65px; width: 360px;" class="description">
							Variables: {site_url} {backupbuddy_version} {current_datetime} {message}
							{backup_size} {backup_file} {backup_serial}
						</div>',
) );


$settings_form->add_setting( array(
	'type'		=>		'text',
	'name'		=>		'email_return',
	'title'		=>		__('Email return address', 'it-l10n-backupbuddy' ),
	'tip'		=>		__('Email address listed as the `from` email address for any emails sent by BackupBuddy. Leave blank (default) to use the WordPress admin email.', 'it-l10n-backupbuddy' ) . ' Current default: ' . get_option( 'admin_email' ),
	'css'		=>		'width: 250px;',
	'after'		=>		' <span class="description">' . __( 'Blank for default', 'it-l10n-backupbuddy' ) . ': ' . get_option( 'admin_email' ) . '</span>',
	'rules'		=>		'string[0-500]|email',
) );