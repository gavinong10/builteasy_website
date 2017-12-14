<?php

if(!defined('GPI_DIRECTORY')) {
    die();
}

function gpi_render_ignored_urls_page(){

    $GPI_ListTable = new GPI_List_Table();
    $gpi_options = $GPI_ListTable->getOptions();

    $post_per_page = ( isset($_GET['post-per-page']) ) ? $_GET['post-per-page'] : 25 ;

    $GPI_ListTable->prepare_items(true, 'ignored', $post_per_page);
    
    ?>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="reports-filter" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <input type="hidden" name="render" value="ignored-urls" />
        <!-- Now we can render the completed list table -->
        <?php $GPI_ListTable->display() ?>
    </form>
    <?php
}

