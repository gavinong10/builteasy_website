<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_render_details_page($default_strategy, $page_id){

    global $wpdb;

    require_once( GPI_DIRECTORY . '/includes/helper.php' );
    
    //Create an instance of our package class...
    $GPI_ListTable = new GPI_List_Table();

    $gpi_options = $GPI_ListTable->getOptions();

    $strategy = ( isset($_GET['strategy']) ) ? $_GET['strategy'] : $default_strategy ;
    $page_stats_column = $strategy . '_page_stats';
    $score_column = $strategy . '_score';
    $last_checked_column = $strategy . '_last_modified';

    if(!empty($page_id)) {
        $gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
        $query = "
            SELECT *
            FROM $gpi_page_stats
            WHERE ID = $page_id
        ";
        $page_stats = $wpdb->get_row($query, ARRAY_A);

        $page_stats[$page_stats_column] = unserialize($page_stats[$page_stats_column]);
        $resource_sizes = array();
        foreach($page_stats[$page_stats_column] as $key => $value)
        {
            $adjusted_value = number_format($value / 1000, 2, '.', '');
            switch($key)
            {
                case 'htmlResponseBytes':
                    $resource_sizes['HTML'] = $adjusted_value;
                    break;
                case 'cssResponseBytes':
                    $resource_sizes['CSS'] = $adjusted_value;
                    break;
                case 'imageResponseBytes':
                    $resource_sizes['IMAGES'] = $adjusted_value;
                    break;
                case 'javascriptResponseBytes':
                    $resource_sizes['JS'] = $adjusted_value;
                    break;
                case 'otherResponseBytes':
                    $resource_sizes['OTHER'] = $adjusted_value;
                    break;
            }
        }

        $gpi_page_reports = $wpdb->prefix . 'gpi_page_reports';
        $query2 = "
            SELECT rule_key, rule_name, rule_impact, rule_blocks
            FROM $gpi_page_reports
            WHERE page_id = $page_id
            AND strategy = '$strategy'
        ";
        $page_report = $wpdb->get_results($query2, ARRAY_A);
        foreach($page_report as $report_key => $report)
        {
            if($report['rule_blocks'] !== null) {
                $report['rule_blocks'] = unserialize($report['rule_blocks']);

                foreach($report['rule_blocks'] as $rule_key => $ruleblock)
                {
                    // Format description for each rule
                    if(isset($ruleblock['header']['args'])) {
                        $x=1;
                        foreach($ruleblock['header']['args'] as $arg)
                        {
                            $report['rule_blocks'][$rule_key]['header']['format'] = str_replace('$'.$x, $arg['value'], $report['rule_blocks'][$rule_key]['header']['format']);
                            if($arg['type'] == 'HYPERLINK') {
                                $report['rule_blocks'][$rule_key]['header']['hyperlink'] = $arg['value'];
                            }
                            $x++;
                        }
                    }
                    $report['rule_blocks'][$rule_key]['description'] = $report['rule_blocks'][$rule_key]['header']['format'];
                    if( isset( $report['rule_blocks'][$rule_key]['header']['hyperlink'] ) ) {
                        $report['rule_blocks'][$rule_key]['hyperlink']['url'] = $report['rule_blocks'][$rule_key]['header']['hyperlink'];
                        $report['rule_blocks'][$rule_key]['hyperlink']['name'] = $report['rule_name'];
                    }
                    unset($report['rule_blocks'][$rule_key]['header']);

                    //Format description for each instance of broken rule
                    if(isset($ruleblock['urls'])) {
                        foreach($ruleblock['urls'] as $url_key => $url) {
                            $x=1;
                            foreach($url['result']['args'] as $arg)
                            {
                                $report['rule_blocks'][$rule_key]['urls'][$url_key]['result']['format'] = str_replace('$'.$x, $arg['value'], $report['rule_blocks'][$rule_key]['urls'][$url_key]['result']['format']);
                                $x++;
                            }
                            $report['rule_blocks'][$rule_key]['results'][] = $report['rule_blocks'][$rule_key]['urls'][$url_key]['result']['format'];
                        }
                    }
                    unset($report['rule_blocks'][$rule_key]['urls']);
                }

                $page_report[$report_key]['rule_blocks'] = json_encode($report['rule_blocks']);
            }
        }

        usort($page_report, "cmpfloat_impact");
        $page_report = array_reverse($page_report);
        ?>
        <?php
        $url_to_nonce = '?page=' . $_REQUEST['page'] . '&render=details&page_id=' . $_GET['page_id'] . '&action=single-recheck';
        $nonced_url = ( function_exists('wp_nonce_url') ) ? wp_nonce_url($url_to_nonce, 'bulk-gpi_page_reports') : $url_to_nonce;
        ?>
        <h3 class="subTitle"><?php echo $page_stats['URL']; ?></h3>
        <div class="toolbar">
            <div class="left">
                <a href="<?php echo $page_stats['URL']; ?>" class="button-gpi view" target="_blank"><?php _e('View Page', 'gpagespeedi'); ?></a>
                <a href="<?php echo $nonced_url; ?>" class="button-gpi recheck"><?php _e('Recheck Results', 'gpagespeedi'); ?></a>
            </div>
        </div>
        <?php if(isset($message)) { ?>
            <div id="message" class="error">
                <p><strong><?php echo $message; ?></strong></p>
            </div>
        <?php } ?>
        <!--Load the AJAX API-->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>
        <script type="text/javascript">

            var page_report = <?php echo json_encode($page_report); ?>;

            // Figure out Pagespeed score into degrees for gauge
            var score = <?php echo $page_stats[$score_column]; ?>;
            var degrees = (3.38 * score) + 11;
            jQuery(document).ready(function() {
                jQuery("#pagespeed_needle").rotate(degrees);
            });

            // Load the Visualization API and the piechart package.
            google.load('visualization', '1.0', {'packages':['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.setOnLoadCallback(drawCharts);

            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.
            function drawCharts() {

                /***********************************************
                            Create impact pie chart
                ************************************************/

                var impact = new google.visualization.DataTable();
                impact.addColumn('string', 'Rule');
                impact.addColumn('number', 'Impact');
                impact.addRows([
                    <?php
                    foreach($page_report as $rule) {
                        $rule_name = htmlentities($rule['rule_name'], ENT_QUOTES, "UTF-8");
                        $rule_impact = htmlentities($rule['rule_impact'], ENT_QUOTES, "UTF-8");
                        if($rule_impact > 0) {
                            echo '["'.$rule_name.'", '.$rule_impact.'],'."\r\n";
                        }
                    }
                    ?>
                ]);

                // Set chart options
                var impact_options = {
                    'width': 463,
                    'height': 320,
                    'chartArea': {top:15,width:"85%",height:"91%"},
                    'legend': 'none',
                    'tooltip': {trigger:'none'},
                    'backgroundColor':'transparent',
                    'colors': ["#3366cc","#dc3912","#ff9900","#109618","#990099","#0099c6","#dd4477","#66aa00","#b82e2e","#316395","#994499","#22aa99","#aaaa11","#6633cc","#e67300","#8b0707","#651067","#329262","#5574a6","#3b3eac","#b77322","#16d620","#b91383","#f4359e","#9c5935","#a9c413","#2a778d","#668d1c","#bea413","#0c5922","#743411"],
                    'pieSliceTextStyle': {color: 'black', fontSize: 14}
                };

                // Instantiate and draw our chart, passing in some options.
                var impact_chart = new google.visualization.PieChart(document.getElementById('impact_chart_div'));
                impact_chart.draw(impact, impact_options);

                // google.visualization.table exposes a 'page' event.
                google.visualization.events.addListener(impact_chart, 'select', impactSelectHandler);
                google.visualization.events.addListener(impact_chart, 'onmouseover', highlightHover);
                google.visualization.events.addListener(impact_chart, 'onmouseout', clearHover);

                /***********************************************
                            Create resource size bar chart
                ************************************************/

                var sizes = new google.visualization.DataTable();
                sizes.addColumn('string', 'Rule');
                sizes.addColumn('number', '');
                sizes.addRows([
                    <?php
                    foreach($resource_sizes as $key => $value) {
                        echo "['$key', $value],\r\n";
                    }
                    ?>
                ]);

                // Set chart options
                var sizes_options = {
                    'legend':'none',
                    'backgroundColor':'transparent',
                    'chartArea': {top:10,width:"75%",height:"80%"},
                };

                // Instantiate and draw our chart, passing in some options.
                var sizes_chart = new google.visualization.BarChart(document.getElementById('sizes_chart_div'));
                sizes_chart.draw(sizes, sizes_options);

                function impactSelectHandler() {
                    var selected = impact_chart.getSelection();
                    if(typeof selected[0] != 'undefined') {
                        var ruleindex = selected[0].row;
                        var rule_object = page_report[ruleindex];
                        var html = buildHTML(rule_object);

                        jQuery('.impact_chart_right tr').removeClass('active');
                        jQuery('.impact_chart_right tr a[data-pieslice=' + ruleindex + ']').closest('tr').addClass('active');

                        jQuery('#impact_rule_report').css('display', 'block');
                        jQuery('#impact_rule_report').html(html);
                        clearHover();
                    } else {
                        jQuery('#impact_rule_report').css('display', 'none');
                        jQuery('#impact_rule_report').html('');
                        clearActive();
                    }
                }

                function buildHTML(rule_object) {
                    var rule_blocks = JSON.parse(rule_object.rule_blocks);
                    var html = '';
                    var rule_blocks_count = rule_blocks.length;

                    for(i=0;i<rule_blocks_count;i++) {
                        html = html + '<h3>' + rule_blocks[i].description + '</h3>';
                        if (typeof rule_blocks[i].results != 'undefined') {
                            var results_count = rule_blocks[i].results.length;
                            html = html + '<ul>';
                            for(y=0;y<results_count;y++) {
                                html = html + '<li>' + rule_blocks[i].results[y] + '</li>';
                            }
                            html = html + '</ul>';
                        }
                        if(typeof rule_blocks[i].hyperlink != 'undefined') {
                            html = html + '<a class="rule_docs" href="' + rule_blocks[i].hyperlink.url + '" target="_blank" alt="<?php _e("Read Documentation", "gpagespeedi"); ?>" ><?php _e("Read Documentation", "gpagespeedi"); ?></a>';
                        }
                    }
                    return html;
                }

                function highlightHover(e) {
                    var current = jQuery('.impact_chart_right tr a[data-pieslice=' + e.row + ']');

                    jQuery('.impact_chart_right tr').removeClass('hover');

                    if(!current.closest('tr').hasClass('active')) {
                        current.closest('tr').addClass('hover');
                    }
                }

                function clearHover() {
                    jQuery('.impact_chart_right tr').removeClass('hover');
                }

                function clearActive() {
                    jQuery('.impact_chart_right tr').removeClass('active');
                }

                jQuery('.legend-item').on('click', function() {

                    var selected = jQuery(this).data('pieslice');
                    impact_chart.setSelection([{row: selected}]);
                    impactSelectHandler();
                    impact_chart.getSelection([{row: selected}]);

                    jQuery('.impact_chart_right tr').removeClass('active');

                    jQuery(this).closest('tr').addClass('active');

                    return false;

                });

            }

        </script>

        <!--Div's to hold output from google charts-->
        <div class="row">
            <div class="top-row boxsizing" id="pagespeed_gauge_wrapper">
                <div id="score_chart_div">
                    <img id="pagespeed_needle" src="<?php echo GPI_PUBLIC_PATH; ?>/images/pagespeed_gauge_needle.png" width="204" height="204" alt="" />
                    <div id="score_text"><?php echo $page_stats[$score_column]; ?><span><?php _e('score', 'gpagespeedi'); ?></span></div>
                </div>
            </div>
            <div class="top-row boxsizing framed" id="pagespeed_stats_wrapper">
                <div class="boxheader">
                    <span class="left"><?php _e('Page Statistics', 'gpagespeedi'); ?></span>
                    <span class="right" style="padding-right: 19px;"><?php _e('Value', 'gpagespeedi'); ?></span>
                </div>
                <table id="stats">
                    <tbody>
                        <tr>
                            <td class="leftcol"><?php _e('Last Checked', 'gpagespeedi'); ?></td>
                            <td class="rightcol"><?php echo date_i18n('M d' ,$page_stats[$last_checked_column]); ?></td>
                        </tr>
                        <tr class="alt">
                            <td class="leftcol"><?php _e('Number of Hosts', 'gpagespeedi'); ?></td>
                            <td class="rightcol"><?php echo $page_stats[$page_stats_column]['numberHosts']; ?></td>
                        </tr>
                        <tr>
                            <td class="leftcol"><?php _e('Total Request Bytes', 'gpagespeedi'); ?></td>
                            <td class="rightcol"><?php echo $page_stats[$page_stats_column]['totalRequestBytes']; ?></td>
                        </tr>
                        <tr class="alt">
                            <td class="leftcol"><?php _e('Total Resources', 'gpagespeedi'); ?></td>
                            <td class="rightcol"><?php echo $page_stats[$page_stats_column]['numberResources']; ?></td>
                        </tr>
                        <tr>
                            <td class="leftcol"><?php _e('JavaScript Resources', 'gpagespeedi'); ?></td>
                            <td class="rightcol"><?php echo $page_stats[$page_stats_column]['numberJsResources']; ?></td>
                        </tr>
                        <tr class="alt">
                            <td class="leftcol"><?php _e('CSS Resources', 'gpagespeedi'); ?></td>
                            <td class="rightcol"><?php echo $page_stats[$page_stats_column]['numberCssResources']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="top-row boxsizing framed" id="pagespeed_sizes_wrapper">
                <div class="boxheader">
                    <span class="left"><?php _e('Total Size of Resources', 'gpagespeedi'); ?></span>
                    <span class="right light"><span class="legend"></span><?php _e('Size (kB)', 'gpagespeedi'); ?></span>
                </div>
                <div id="sizes_chart_div"></div>
            </div>
        </div>
        <div class="row boxsizing framed">
            <div class="boxheader">
                <span class="left"><?php _e('Opportunities for improvement', 'gpagespeedi'); ?></span><span class="light"><?php _e('(Click for detailed report)', 'gpagespeedi'); ?></span>
            </div>
            <div id="opportunities">
                <div class="impact_chart_left boxsizing">
                    <div id="impact_chart_div"></div>
                    <div id="impact_rule_report"></div>
                </div>
                <div class="impact_chart_right">
                    <table>
                        <tbody>
                            <th>
                                <?php _e('Insights Key', 'gpagespeedi'); ?>
                            </th>
                            <?php
                            $x = 0;
                            $color_array = array("#3366cc","#dc3912","#ff9900","#109618","#990099","#0099c6","#dd4477","#66aa00","#b82e2e","#316395","#994499","#22aa99","#aaaa11","#6633cc","#e67300","#8b0707","#651067","#329262","#5574a6","#3b3eac","#b77322","#16d620","#b91383","#f4359e","#9c5935","#a9c413","#2a778d","#668d1c","#bea413","#0c5922","#743411");
                            foreach($page_report as $rule) {
                                $rule_name = $rule['rule_name'];
                                $rule_impact = $rule['rule_impact'];
                                if($rule_impact > 0) {
                                    ?>
                                    <tr>
                                        <td class="leftcol">
                                            <span class="swatch" style="background: <?php echo $color_array[$x]; ?>"></span>
                                            <div class="box"><a data-pieslice="<?php echo $x; ?>" class="legend-item"><?php echo $rule_name; ?></a></div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $x++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php
    }
}

