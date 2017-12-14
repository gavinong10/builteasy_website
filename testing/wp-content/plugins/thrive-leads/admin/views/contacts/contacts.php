<script>
    ThriveLeads.objects.BreadcrumbsCollection = new ThriveLeads.collections.BreadcrumbsCollection(<?php echo json_encode($dashboard_data['breadcrumbs']) ?>);
    ThriveLeads.objects.groups = new ThriveLeads.collections.Groups(<?php echo json_encode( $dashboard_data['groups'] ) ?>);
</script>
<div id="tve-content">
    <div id="tve-contacts">
        <div class="tve-header">
            <nav id="tl-nav">
                <div class="nav-wrapper">
                    <div class="tve-logo tve_leads_clearfix tvd-left">
                        <a href="<?php menu_page_url( 'thrive_leads_dashboard' ); ?>"
                           title="<?php echo __( 'Thrive Leads Home', 'thrive-leads' ) ?>">
                           <?php echo '<img src="' . plugins_url( 'thrive-leads/admin/img' ) . '/tl-logo-full-white.png" > '; ?>
                        </a>
                    </div>
                    <?php require_once(dirname(dirname(__FILE__)) . '/leads_menu.php') ?>
                </div>
            </nav>
        </div>
        <div class="tve-leads-breadcrumbs-wrapper">
            <?php require_once(dirname(dirname(__FILE__)) . '/leads_breadcrumbs.php') ?>
        </div>
        <h3 class="tvd-title"><?php echo __('Leads Export', 'thrive-leads'); ?></h3>
        <div class="tvd-v-spacer"></div>
        <div class="tve-contact-wrapper">
            <form method="get" action="<?php admin_url('admin.php'); ?>">
                <input type="hidden" name="page" value="thrive_leads_contacts"/>

                <div id="tve-contacts-table">
                    <?php $contacts_list->display(); ?>
                </div>
            </form>
        </div>
        <div id="tve-download-manager"><?php require_once(dirname(__FILE__) . '/contacts_download.php') ?></div>
    </div>
</div>
<div class="tve-email-modal">
<div id="tve-email-lb" class="tvd-modal" style="display: none;">
    <div class="tvd-modal-content">
        <h3 class="tvd-modal-title">
            <?php echo __('Send Contact Information by Email', 'thrive-leads'); ?>
        </h3>
        <div class="tvd-v-spacer"></div>
        <div class="tvd-row">
            <div class="tvd-col tvd-s9 tvd-offset-s1">
                <div class="tvd-input-field">
                    <input type="text" id="tve-email-address" data-default-value="<?php echo $saved_email; ?>" >
                    <label  for="tve-email-address"><?php echo __('Email address', 'thrive-leads'); ?></label>
                </div>
                <p>
                    <input type="checkbox" id="tve-save-email" <?php echo empty($saved_email) ? '' : 'checked'; ?>>
                    <label for="tve-save-email">
                        <?php echo __('Remember this email address for future use.', 'thrive-leads'); ?>
                    </label>
                </p>
            </div>
        </div>
    </div>
    <div class="tvd-modal-footer">
        <div class="tvd-row tvd-s12">
            <a href="javascript:void(0)" class="tvd-waves-effect tvd-waves-light tvd-btn tvd-btn-green tvd-right tve-send-email"><?php echo __('Send', 'thrive-leads'); ?></a>
            <input type="hidden" id="tve-contact-id">
            <p class="tvd-center-align" id="tve-email-response"></p>
        </div>
    </div>
    <a href="javascript:voide(0)" class="tvd-modal-action tvd-modal-close tvd-modal-close-x">
        <i class="tvd-icon-close2"></i>
    </a>
</div>
</div>
<div class="tvd-v-spacer"></div>
<a class="tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-dark tvd-waves-effect"
   href="<?php echo admin_url('admin.php?page=thrive_leads_dashboard'); ?>"
   title="<?php echo __('Back to Thrive Leads Home') ?>"
   id="tve-asset-group-dashboard">
    &laquo; <?php echo __('Back to Thrive Leads Home', 'thrive-leads') ?>
</a>