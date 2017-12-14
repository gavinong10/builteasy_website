<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_render_summary_page($default_strategy) {

    global $wpdb;

    require_once( GPI_DIRECTORY . '/includes/helper.php' );

    //Create an instance of our package class...
    $GPI_ListTable = new GPI_List_Table();

    $gpi_options = $GPI_ListTable->getOptions();

    // Mobile or Desktop reports?
    $strategy = ( isset($_GET['strategy']) ) ? $_GET['strategy'] : $default_strategy;
    $score_column = $strategy . '_score';
    $page_stats_column = $strategy . '_page_stats';

    // Create our empty arrays for use later when divying up our data for display
    $rule_stats = $scores = $html_sizes = $css_sizes = $image_sizes = $js_sizes = $other_sizes = array();

    // Filter report type
    $filter = 'all';
    if(isset($_GET['filter'])) {
        $filter = $_GET['filter'];
    }

    // Page Data Query
    $data_typestocheck = $GPI_ListTable->getTypesToCheck($filter);
    $gpi_page_stats = $wpdb->prefix . 'gpi_page_stats';
    if(!empty($data_typestocheck)) {

        $allpagedata =  $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT ID, URL, $score_column, $page_stats_column
                                FROM $gpi_page_stats
                                WHERE ($data_typestocheck[0])",
                                $data_typestocheck[1]
                            ),
                            ARRAY_A 
                        );
    } else {
        $allpagedata = array();
    }

    // Page Reports Query
    $reports_typestocheck = $GPI_ListTable->getTypesToCheck($filter);
    $gpi_page_reports = $wpdb->prefix . 'gpi_page_reports';

    if(!empty($reports_typestocheck)) {

        $allpagereports =   $wpdb->get_results(
                                $wpdb->prepare( 
                                    "SELECT     r.rule_key, r.rule_name, r.rule_impact
                                    FROM        $gpi_page_stats d
                                    INNER JOIN  $gpi_page_reports r 
                                                ON r.page_id = d.ID
                                                AND r.rule_impact > 0
                                                AND r.strategy = '$strategy'
                                    WHERE       ($reports_typestocheck[0])",
                                    $reports_typestocheck[1]
                                ),
                                ARRAY_A
                            ); 
    } else {
        $allpagereports = array();
    }

    foreach($allpagereports as $pagereports)
    {
        $rulekey = $pagereports['rule_key'];
        if(!isset($rule_stats[$rulekey])) {
            $rule_stats[$rulekey]['name'] = $pagereports['rule_name'];
            $rule_stats[$rulekey]['total_impact'] = $pagereports['rule_impact'];
            $rule_stats[$rulekey]['occurances'] = 1;
        } else {
            $rule_stats[$rulekey]['total_impact'] = abs($rule_stats[$rulekey]['total_impact'] + $pagereports['rule_impact']);
            $rule_stats[$rulekey]['occurances'] = $rule_stats[$rulekey]['occurances'] + 1;
        }
    }
    usort($rule_stats, "cmpfloat_rules");
    foreach($rule_stats as $key => $rule)
    {
        $total_impact = $rule['total_impact'];
        $occurances = $rule['occurances'];
        $avg_impact = number_format($total_impact / $occurances, 2);
        $rule_stats[$key]['avg_impact'] = $avg_impact;
    }

    // Count all pages (needed for calculating averages)
    $total_pages = count($allpagedata);
    $total_scores = 0;

    if(!empty($total_pages) && !empty($allpagereports)) {

        foreach($allpagedata as $key => $pagedata)
        {
            $id = $pagedata['ID'];

            $total_scores = $total_scores + $pagedata[$score_column];
            $pagedata[$page_stats_column] = unserialize($pagedata[$page_stats_column]);
            $allpagedata[$key]['page_stats'] = $pagedata[$page_stats_column];

            $scores[$id]['score'] = (isset($pagedata[$score_column])) ? $pagedata[$score_column] : '';
            $scores[$id]['url'] = (isset($pagedata['URL'])) ? $pagedata['URL'] : '';
            $scores[$id]['id'] = $id;
            $html_sizes[$id] = (isset($pagedata[$page_stats_column]['htmlResponseBytes'])) ? $pagedata[$page_stats_column]['htmlResponseBytes'] : '0';
            $css_sizes[$id] = (isset($pagedata[$page_stats_column]['cssResponseBytes'])) ? $pagedata[$page_stats_column]['cssResponseBytes'] : '0';
            $image_sizes[$id] = (isset($pagedata[$page_stats_column]['imageResponseBytes'])) ? $pagedata[$page_stats_column]['imageResponseBytes'] : '0';
            $js_sizes[$id] = (isset($pagedata[$page_stats_column]['javascriptResponseBytes'])) ? $pagedata[$page_stats_column]['javascriptResponseBytes'] : '0';
            $other_sizes[$id] = (isset($pagedata[$page_stats_column]['otherResponseBytes'])) ? $pagedata[$page_stats_column]['otherResponseBytes'] : '0';
        }

        $average_score = number_format($total_scores / $total_pages);

        usort($scores, "cmpfloat_scores");
        usort($html_sizes, "cmpfloat");
        usort($css_sizes, "cmpfloat");
        usort($image_sizes, "cmpfloat");
        usort($js_sizes, "cmpfloat");
        usort($other_sizes, "cmpfloat");

        $average_resource_sizes = array(
            'HTML' => array(
                        number_format(end($html_sizes) / 1000, 0, '', ''),
                        number_format((array_sum($html_sizes) / $total_pages) / 1000, 0, '', ''),
                        number_format($html_sizes[0] / 1000, 0, '', '')
                      ),
            'CSS' => array(
                        number_format(end($css_sizes) / 1000, 0, '', ''),
                        number_format((array_sum($css_sizes) / $total_pages) / 1000, 0, '', ''),
                        number_format($css_sizes[0] / 1000, 0, '', '')
                      ),
            'IMAGES' => array(
                        number_format(end($image_sizes) / 1000, 0, '', ''),
                        number_format((array_sum($image_sizes) / $total_pages) / 1000, 0, '', ''),
                        number_format($image_sizes[0] / 1000, 0, '', '')
                      ),
            'JS' => array(
                        number_format(end($js_sizes) / 1000, 0, '', ''),
                        number_format((array_sum($js_sizes) / $total_pages) / 1000, 0, '', ''),
                        number_format($js_sizes[0] / 1000, 0, '', '')
                      ),
            'OTHER' => array(
                        number_format(end($other_sizes) / 1000, 0, '', ''),
                        number_format((array_sum($other_sizes) / $total_pages) / 1000, 0, '', ''),
                        number_format($other_sizes[0] / 1000, 0, '', '')
                      )
        );

        $highest_scores = array();
        $lowest_scores = array();
        $x=0;
        foreach($scores as $score)
        {
            if($x > 4) { break; }
            array_push($lowest_scores, $score);
            $x++;
        }
        $x=0;
        $scores = array_reverse($scores);
        foreach($scores as $score)
        {
            if($x > 4) { break; }
            array_push($highest_scores, $score);
            $x++;
        }
        ?>

        <!--Load the AJAX API-->
        <script type="text/javascript" src="https://www.google.com/jsapi"></script>

        <script type="text/javascript">
            // Figure out Pagespeed score into degrees for gauge
            var score = <?php echo $average_score; ?>;
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
                            Create resource size bar chart
                ************************************************/

                var sizes = google.visualization.arrayToDataTable([
                  ['Type', 'High', 'Average', 'Low'],
                  <?php
                  foreach($average_resource_sizes as $key => $values)
                  {
                    echo "['$key', $values[0], $values[1], $values[2]],\r\n";
                  }
                  ?>
                ]);
                // Set chart options
                var sizes_options = {
                    'legend':{position: 'none'},
                    'backgroundColor':'transparent',
                    'width': 615,
                    'height': 200,
                    'chartArea': {top:10,width:"80%",height:"80%"},
                };

                // Instantiate and draw our chart, passing in some options.
                var sizes_chart = new google.visualization.BarChart(document.getElementById('sizes_chart_div'));
                sizes_chart.draw(sizes, sizes_options);

            }
        </script>

        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="get" action="" id="filter" name="filter">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'];?>" />
                    <input type="hidden" name="render" value="summary" />
                    <div class="tablenav top">
                        <?php
                            $GPI_ListTable->extra_tablenav('top');
                        ?>
                    </div>
                </form>
            </div>

            <br class="clear">
        </div>

        <!--Div's to hold output from google charts-->
        <div class="row">
            <div class="top-row boxsizing" id="pagespeed_gauge_wrapper">
                <div id="score_chart_div">
                    <img id="pagespeed_needle" src="<?php echo GPI_PUBLIC_PATH; ?>/images/pagespeed_gauge_needle.png" width="204" height="204" alt="" />
                    <div id="score_text"><?php echo $average_score; ?><span><?php _e('score', 'gpagespeedi'); ?></span></div>
                </div>
            </div>
            <div class="top-row boxsizing framed" id="pagespeed_avg_sizes_wrapper">
                <div class="boxheader">
                    <span class="left"><?php _e('Size of Resources (in kB)', 'gpagespeedi'); ?></span>
                    <span class="right light"><span class="legend low"></span><?php _e('Lowest', 'gpagespeedi'); ?></span>
                    <span class="right light"><span class="legend avg"></span><?php _e('Average', 'gpagespeedi'); ?></span>
                    <span class="right light"><span class="legend"></span><?php _e('Highest', 'gpagespeedi'); ?></span>
                </div>
                <div id="sizes_chart_div"></div>
            </div>
        </div>
        <div class="row boxsizing framed" id="largest_improvement">
            <div class="boxheader">
                <span class="left"><?php _e('Largest Areas for Improvement', 'gpagespeedi'); ?></span>
                <span class="right"><?php _e('Pages Effected', 'gpagespeedi'); ?></span>
                <span class="right"><?php _e('Average Impact', 'gpagespeedi'); ?></span>
            </div>
            <table id="stats">
                <tbody>
                    <?php
                    $x=0;
                    foreach($rule_stats as $rule)
                    {
                        if($x == 5) { break; }
                        $rule_name = $rule['name'];
                        $avg_impact = $rule['avg_impact'];
                        $occurances = $rule['occurances'];
                        $altclass = 'class="alt"';
                        if($x % 2 == 0) { $altclass = ''; }
                        ?>
                        <tr <?php echo $altclass;?>>
                            <td class="leftcol"><?php echo $rule_name; ?></td>
                            <td class="rightcol"><?php echo $avg_impact; ?></td>
                            <td class="rightcol"><?php echo $occurances; ?></td>
                        </tr>
                        <?php
                        $x++;
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <div class="row scores_div">
            <div class="halfwidth boxsizing framed left">
                <div class="boxheader">
                    <span class="left"><?php _e('Highest Scoring Pages', 'gpagespeedi'); ?></span>
                    <span class="right"><?php _e('Score', 'gpagespeedi'); ?></span>
                </div>
                <table id="stats">
                    <tbody>
                        <?php
                        $x=0;
                        $request = $_REQUEST['page'];
                        foreach($highest_scores as $score)
                        {
                            if($x == 5) { break; }
                            $url = $score['url'];
                            $thescore = $score['score'];
                            $id = $score['id'];
                            $altclass = 'class="alt"';
                            if($x % 2 == 0) { $altclass = ''; }
                            ?>
                            <tr <?php echo $altclass;?>>
                                <td class="leftcol"><?php echo "<a href=\"?page=$request&render=details&page_id=$id\">$url</a>"; ?></td>
                                <td class="rightcol"><?php echo $thescore; ?></td>
                            </tr>
                            <?php
                            $x++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="halfwidth boxsizing framed right">
                <div class="boxheader">
                    <span class="left"><?php _e('Lowest Scoring Pages', 'gpagespeedi'); ?></span>
                    <span class="right"><?php _e('Score', 'gpagespeedi'); ?></span>
                </div>
                <table id="stats">
                    <tbody>
                        <?php
                        $x=0;
                        $request = $_REQUEST['page'];
                        foreach($lowest_scores as $score)
                        {
                            if($x == 5) { break; }
                            $url = $score['url'];
                            $thescore = $score['score'];
                            $id = $score['id'];
                            $altclass = 'class="alt"';
                            if($x % 2 == 0) { $altclass = ''; }
                            ?>
                            <tr <?php echo $altclass;?>>
                                <td class="leftcol"><?php echo "<a href=\"?page=$request&render=details&page_id=$id\">$url</a>"; ?></td>
                                <td class="rightcol"><?php echo $thescore; ?></td>
                            </tr>
                            <?php
                            $x++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php
    } else { ?>
        <div class="tablenav top">
            <div class="alignleft actions">
                <form method="get" action="" id="filter" name="filter">
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'];?>" />
                    <input type="hidden" name="render" value="summary" />
                    <div class="tablenav top">
                        <?php
                            $GPI_ListTable->extra_tablenav('top');
                        ?>
                    </div>
                </form>
            </div>

            <br class="clear">
        </div>
        <?php
            _e( 'No Pagespeed Reports Found. Google Pagespeed may still be checking your pages. If problems persist, see the following possible solutions:', 'gpagespeedi' );
        ?>
        <ul class="no-items">
            <?php if(isset($_GET['filter']) && $_GET['filter'] != 'all') { ?>
            <li><?php _e( 'There may not be any results for the "' . $_GET['filter'] . '" filter. Try another filter.', 'gpagespeedi' );?></li>
            <?php } ?>
            <?php if($gpi_options['strategy'] == 'both') { ?>
            <li><?php _e( 'There may not be any ' . $default_strategy . ' reports completed yet.', 'gpagespeedi' );?></li>
            <?php } ?>
            <li><?php _e( 'Make sure that you have entered your Google API key on the ', 'gpagespeedi' );?><a href="?page=<?php echo $_REQUEST['page']; ?>&render=options">Options</a> page.</li>
            <li><?php _e( 'Make sure that you have enabled "PageSpeed Insights API" from the Services page of the ', 'gpagespeedi' );?><a href="https://code.google.com/apis/console/">Google Console</a>.</li>
            <li><?php _e( 'The Google Pagespeed API may be temporarily unavailable.', 'gpagespeedi' );?></li>
        </ul>
    <?php
    }
}


