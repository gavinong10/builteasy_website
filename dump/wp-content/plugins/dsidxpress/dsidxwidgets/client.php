<?php
add_filter("the_posts", array("dsIDXWidgets_Client", "Activate"));

class dsIDXWidgets_Client {
	static function Activate($posts) {
		wp_enqueue_style('dsidxwidgets-unconditional', DSIDXWIDGETS_PLUGIN_URL . 'css/client.css');

		return $posts;
	}
}
?>