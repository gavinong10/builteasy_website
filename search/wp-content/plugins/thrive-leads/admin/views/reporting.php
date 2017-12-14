<div id="tve-content">
    <script type="text/javascript">
        var TVE_Page_Data = {
            globalSettings: <?php echo json_encode($dashboard_data['global_settings']) ?>,
        };
		
		ThriveLeads.objects.BreadcrumbsCollection = new ThriveLeads.collections.BreadcrumbsCollection(<?php echo json_encode($dashboard_data['breadcrumbs']) ?>);
        ThriveLeads.objects.groups = new ThriveLeads.collections.Groups(<?php echo json_encode( $dashboard_data['groups'] ) ?>);
    </script>
    <div id="tve-reporting">
        <div class="tve-header">
            <nav id="tl-nav">
                <div class="nav-wrapper">
                    <div class="tve-logo tvd-left">
                        <a href="<?php menu_page_url( 'thrive_leads_dashboard' ); ?>"
                           title="<?php echo __( 'Thrive Leads Home', 'thrive-leads' ) ?>">
							<?php echo '<img src="' . plugins_url( 'thrive-leads/admin/img' ) . '/tl-logo-full-white.png" > '; ?>
                        </a>
                    </div>
                    <?php require_once(dirname(__FILE__) . '/leads_menu.php') ?>
                </div>
            </nav>
        </div>
		<div class="tve-leads-breadcrumbs-wrapper">
			<?php require_once(dirname(__FILE__) . '/leads_breadcrumbs.php') ?>
		</div>
        <h3>
            <?php echo __('Reporting', 'thrive-leads'); ?>
        </h3>
        <div class="tvd-v-spacer vs-2"></div>
        <form>
            <div class="tvd-row">
                <div class="tvd-col tvd-s5 tvd-m2">
                    <div class="tvd-input-field">
                        <select name="report_type" id="report_type">
                            <option value="Conversion"><?php echo __('Conversion Report', 'thrive-leads'); ?></option>
                            <option value="ConversionRate"><?php echo __('Conversion Rate Report', 'thrive-leads'); ?></option>
                            <option value="CumulativeConversion"><?php echo __('Cumulative Conversions Report', 'thrive-leads'); ?></option>
                            <option value="ComparisonChart"><?php echo __('Comparison Report', 'thrive-leads'); ?></option>
                            <option value="ListGrowth"><?php echo __('List Growth', 'thrive-leads'); ?></option>
                            <option value="CumulativeListGrowth"><?php echo __('Cumulative List Growth', 'thrive-leads'); ?></option>
                            <option value="LeadReferral"><?php echo __('Lead Referral Report', 'thrive-leads'); ?></option>
                            <option value="LeadTracking"><?php echo __('Lead Tracking Report', 'thrive-leads'); ?></option>
                            <option value="LeadSource"><?php echo __('Content Marketing Report', 'thrive-leads'); ?></option>
                        </select>
                        <label for="report_type"><?php echo __('Show report', 'thrive-leads'); ?></label>
                    </div>
                </div>
                <div class="tvd-col tvd-s2 tvd-m8">&nbsp;</div>
                <div class="tvd-col tvd-s5 tvd-m2">
                    &nbsp;
                    <div id="tve-chart-annotations">
                        <div class="tvd-switch">
                            <label>
                                <span class="tvd-on-large-and-down">
                                    <?php echo __('Load Annotations: No', 'thrive-leads'); ?>
                                </span>
                                <input class="tve_load_annotation" type="checkbox" name="tve_load_annotations" value="1" <?php if ($tve_load_annotations): ?>checked="checked"<?php endif; ?> autocomplete="off">
                                <span class="tvd-lever"></span>
                                <span class="tvd-on-large-and-down">
                                    <?php echo __('Yes', 'thrive-leads'); ?>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tvd-row">
                <div class="tvd-col tvd-s6 tvd-m2">
                    <div class="tvd-input-field">
                        <select autocomplete="off" class="tve-chart-date-select" id="tve-chart-date-select">
                            <option selected value="<?php echo TVE_LAST_7_DAYS; ?>"><?php echo __('Last 7 days', 'thrive-leads'); ?></option>
                            <option value="<?php echo TVE_LAST_30_DAYS; ?>"><?php echo __('Last 30 days', 'thrive-leads'); ?></option>
                            <option value="<?php echo TVE_THIS_MONTH; ?>"><?php echo __('This month', 'thrive-leads'); ?></option>
                            <option value="<?php echo TVE_LAST_MONTH; ?>"><?php echo __('Last month', 'thrive-leads'); ?></option>
                            <option value="<?php echo TVE_THIS_YEAR; ?>"><?php echo __('This year', 'thrive-leads'); ?></option>
                            <option value="<?php echo TVE_LAST_YEAR; ?>"><?php echo __('Last year', 'thrive-leads'); ?></option>
                            <option value="<?php echo TVE_LAST_12_MONTHS; ?>"><?php echo __('Last 12 months', 'thrive-leads'); ?></option>
                            <option value="<?php echo TVE_CUSTOM_DATE_RANGE; ?>"><?php echo __('Custom date range', 'thrive-leads'); ?></option>
                        </select>
                        <label for="tve-chart-date-select"><?php echo __('Date interval', 'thrive-leads'); ?></label>
                    </div>
                </div>
                <div class="tvd-col tvd-s6 tvd-m4 tve-date-filter">
                    <div class="tvd-row tvd-collapse tvd-no-margin">
                        <div class="tvd-col tvd-s5">
                            <div class="tvd-input-field">
                                <input type="text" name="tve-report-start-date" id="tve-report-start-date" class=""/>
                                <label for="tve-report-start-date"><?php echo __('Start date', 'thrive-leads'); ?></label>
                            </div>
                        </div>
                        <div class="tvd-col tvd-s1 tvd-center-align">
                            <span class="tvd-icon-calendar start-date-calendar calendar-trigger-icon" data-activates="#tve-report-start-date"></span>
                        </div>
                        <div class="tvd-col tvd-s5">
                            <div class="tvd-input-field">
                                <input type="text" name="tve-report-end-date" id="tve-report-end-date" class=""/>
                                <label for="tve-report-end-date"><?php echo __('End date', 'thrive-leads'); ?></label>
                            </div>
                        </div>
                        <div class="tvd-col tvd-s1 tvd-center-align">
                            <span class="tvd-icon-calendar end-date-calendar calendar-trigger-icon" data-activates="#tve-report-end-date"></span>
                        </div>
                    </div>
                </div>
                <div class="tvd-col tvd-s6 tvd-m2">
                    <div class="tve-chart-source">
                        <div class="tvd-input-field">
                            <select class="tve-chart-source-select" name="tve-chart-source" autocomplete="off" id="tve-chart-source-select">
                                <optgroup label="">
                                    <option value="-1"><?php echo __('All', 'thrive-leads') ?></option>
                                </optgroup>
                                <optgroup label="<?php echo __('Lead Groups', 'thrive-leads'); ?>">
                                    <?php if (!empty($reporting_data['lead_groups'])): ?>
                                        <?php foreach ($reporting_data['lead_groups'] as $group) : ?>
                                            <option
                                                value="<?php echo $group->ID ?>"><?php echo $group->post_title ?></option>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <option value="-1" disabled>(<?php echo __('empty', 'thrive-leads') ?>)</option>
                                    <?php endif; ?>
                                </optgroup>
                                <optgroup label="<?php echo __('Shortcodes', 'thrive-leads'); ?>">
                                    <?php if (!empty($reporting_data['shortcodes'])): ?>
                                        <?php foreach ($reporting_data['shortcodes'] as $shortcode) : ?>
                                            <option
                                                value="<?php echo $shortcode->ID ?>"><?php echo $shortcode->post_title ?></option>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <option value="-1" disabled>(<?php echo __('empty', 'thrive-leads') ?>)</option>
                                    <?php endif; ?>
                                </optgroup>
                                <optgroup label="<?php echo __('ThriveBoxes', 'thrive-leads'); ?>">
                                    <?php if (!empty($reporting_data['two_step_lightbox'])): ?>
                                        <?php foreach ($reporting_data['two_step_lightbox'] as $tsl) : ?>
                                            <option
                                                value="<?php echo $tsl->ID ?>"><?php echo $tsl->post_title ?></option>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <option value="-1" disabled>(<?php echo __('empty', 'thrive-leads') ?>)</option>
                                    <?php endif; ?>
                                </optgroup>
                            </select>
                            <label for="tve-chart-source-select"><?php echo __('Source', 'thrive-leads'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="tvd-col tvd-s6 tvd-m2">
                    <div class="tve-referral-type" style="display:none;">
                        <div class="tvd-input-field">
                            <select autocomplete="off" class="tve-referral-type-select" name="tve-referral-type" id="tve-referral-type-select">
                                <option selected value="domain"><?php echo __('Referral Domain', 'thrive-leads'); ?></option>
                                <option value="url"><?php echo __('Referring URLs', 'thrive-leads'); ?></option>
                            </select>
                            <label for="tve-referral-type-select"><?php echo __('Referral type', 'thrive-leads'); ?></label>
                        </div>
                    </div>

                    <div class="tve-tracking-type" style="display:none;">
                        <div class="tvd-input-field">
                            <select autocomplete="off" class="tve-tracking-type-select" name="tve-tracking-type" id="tve-tracking-type">
                                <option selected value="all"><?php echo __('All', 'thrive-leads'); ?></option>
                                <option value="source"><?php echo __('Campaign Sources', 'thrive-leads'); ?></option>
                                <option value="medium"><?php echo __('Campaign Media', 'thrive-leads'); ?></option>
                                <option value="campaign"><?php echo __('Campaign Names', 'thrive-leads'); ?></option>
                            </select>
                            <label for="tve-tracking-type"><?php echo __('View', 'thrive-leads'); ?></label>
                        </div>
                    </div>

                    <div class="tve-chart-interval">
                        <div class="tvd-input-field">
                            <select autocomplete="off" class="tve-chart-interval-select" name="tve-chart-interval" id="tve-chart-interval">
                                <option selected value="day"><?php echo __('Daily', 'thrive-leads'); ?></option>
                                <option value="week"><?php echo __('Weekly', 'thrive-leads'); ?></option>
                                <option value="month"><?php echo __('Monthly', 'thrive-leads'); ?></option>
                            </select>
                            <label for="tve-chart-interval"><?php echo __('Graph interval', 'thrive-leads'); ?></label>
                        </div>
                    </div>

                    <div class="tve-source-type" style="display:none;">
                        <div class="tvd-input-field">
                            <select autocomplete="off" class="tve-source-type-select" name="tve-source-type" id="tve-source-type">
                                <option selected value="0"><?php echo __('All', 'thrive-leads'); ?></option>
                                <option value="<?php echo TVE_SCREEN_POST; ?>"><?php echo __('Blog Posts', 'thrive-leads'); ?></option>
                                <option value="<?php echo TVE_SCREEN_PAGE; ?>"><?php echo __('Pages', 'thrive-leads'); ?></option>
                            </select>
                            <label for="tve-source-type"><?php echo __('Source type', 'thrive-leads'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="tvd-col tvd-s6 tvd-m2">&nbsp;</div>
                <div class="tvd-col tvd-s6 tvd-m2">&nbsp;</div>
                <div class="tvd-col tvd-s6 tvd-m2">&nbsp;</div>
            </div>
        </form>
        <div class="tvd-relative">
            <div id="tve-report-chart" style="height: 600px;"></div>
            <div class="tve-chart-overlay" style="display: none">
                <div class="tve-overlay-text">
                    <h1><?php echo __('No Report Data (Yet)', 'thrive-leads'); ?></h1>

                    <div>
                        <?php echo __('Here you will see a graph with the report data from all of your forms. Currently there is no data to display yet.', 'thrive-leads'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="tvd-v-spacer vs-2"></div>
        <div id="pagination-top" class="tl-pagination">
        </div>
        <div id="tve-report-meta">
        </div>
        <div class="tvd-v-spacer vs-2"></div>
        <div id="pagination-bottom" class="tl-pagination">
        </div>
    </div>
    <div class="tvd-v-spacer vs-2"></div>
    <a class="tvd-btn-flat tvd-btn-flat-primary tvd-btn-flat-dark tvd-waves-effect"
       href="<?php echo admin_url('admin.php?page=thrive_leads_dashboard'); ?>"
       title="<?php echo __('Back to Thrive Leads Home') ?>"
       id="tve-asset-group-dashboard">
        &laquo;<?php echo __('Back to Thrive Leads Home', 'thrive-leads') ?>
    </a>
</div>