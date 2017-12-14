<?php
    global $wp_rewrite;
    if($wp_rewrite->permalink_structure != '/%postname%/') {
        $wp_rewrite->set_permalink_structure('/%postname%/');
        $wp_rewrite->flush_rules();
        echo '<iframe style="position:absolute;top:-5000px" src="' . site_url() . '/wp-admin/options-permalink.php"></iframe>';
    }
?>

<div class="wrap">
    <h2><?php echo __('Once Click Installation', 'aviators'); ?></h2>
    <div id="message" class="error">
        <p style="font-size: 14px;">

            <?php echo __('Dont start this process before you have <strong>installed</strong> and <strong>activated</strong> all the required plugins!', 'aviators'); ?><br/>
            <?php echo __('Note that this process might overwrite your existing installation settings, use only on fresh installations!', 'aviators'); ?><br/>
            <?php echo __('Note that some steps might execute for several minutes, do not reload this page, until the process is done!', 'aviators'); ?><br/>
            <?php echo __('Do not run this installation multiple times, all the settings will be reverted to defaults!', 'aviators'); ?>
        </p>
    </div>

    <div id="start">
        <a class="btn" data-steps="<?php print $step_ids; ?>" href="#"><?php echo __('Start Demo Setup', 'aviators'); ?></a>
    </div>


    <?php foreach ($steps as $id => $step): ?>
        <div class="postbox launcher-step lock" id="<?php echo $id ?>-step">
            <h3 class="title">
                <?php echo $step['title']; ?>
                <a href="#" class="show-report"><?php echo __('Show Report', 'aviators'); ?></a>
            </h3>
            <div class="report">

            </div>
        </div>
    <?php endforeach; ?>
</div>


<script>
    jQuery('.show-report').click(function (e) {
        jQuery('.report', jQuery(this).parent().parent()).slideDown();
        e.preventDefault();
    });

    jQuery('#start a').click(function (e) {
        var steps = jQuery(this).data('steps').split(',');

        if (steps.length) {
            step = steps.shift();
            processLauncherStep(step, steps);
        }
        e.preventDefault();
    });

    function processLauncherStep(step, steps) {
        var stepID = '#' + step + '-step';
        jQuery(stepID).removeClass('lock');
        jQuery(stepID).addClass('lock-open');

        jQuery.ajax({
            url: "<?php print home_url(); ?>/aviators-launcher/" + step
        }).done(function (data) {
                var messages = jQuery.parseJSON(data);
                jQuery(stepID).removeClass('lock-open');
                jQuery(stepID).addClass('done');

                jQuery.ajax({
                    url: "<?php print home_url(); ?>/aviators-launcher-report/" + step
                }).done(function (report) {
                        jQuery(".report", stepID).append(report);
                        if (steps.length) {
                            step = steps.shift();
                            processLauncherStep(step, steps);
                        }
                    });

            });

    }
</script>

<style>
    #start a {
        margin: 30px 0px !important;
        float: none;
        display: inline-block;
        text-decoration: none;
        background-color: #00CA71;
        color: #fff;
        padding: 15px 25px;
        font-size: 25px;
    }

    .launcher-step {
        padding: 10px 5px;;
        background-color: #fff;
        margin-bottom: 10px;
    }
    .launcher-step .report  {
        display: none;
        background-color: #eeeeee;
        padding: 15px;
        border: 1px solid #e4e4e4;
    }
    .launcher-step .show-report  {
        float: right;
        font-size: 12px;
        display: none;
    }
    .launcher-step .title {
        text-indent: 40px;
        padding: 10px 0px;
    }

    .launcher-step.done .show-report {
        display: block;
    }
    .launcher-step.done .title {
        background: url('<?php print AVIATORS_LAUNCHER_URI ?>/assets/img/check.png') no-repeat left center;
    }

    .launcher-step.lock .title {
        background: url('<?php print AVIATORS_LAUNCHER_URI ?>/assets/img/lock.png') no-repeat left center;
    }

    .launcher-step.lock-open .title {
        background: url('<?php print AVIATORS_LAUNCHER_URI ?>/assets/img/lock-open.png') no-repeat left center;
    }


</style>