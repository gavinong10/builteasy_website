<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_render_list_page(){

    // Filter report type
    $report_filter = 'all';
    if(isset($_GET['filter'])) {
        $report_filter = $_GET['filter'];
    }

    $GPI_ListTable = new GPI_List_Table();
    $gpi_options = $GPI_ListTable->getOptions();

    $post_per_page = ( isset($_GET['post-per-page']) ) ? $_GET['post-per-page'] : 25 ;

    $GPI_ListTable->prepare_items(false, $report_filter, $post_per_page);

    ?>

    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="reports-filter" action="" method="get">

    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <input type="hidden" name="render" value="list" />
    <?php $GPI_ListTable->display(); ?>

    </form>
    <?php
}

