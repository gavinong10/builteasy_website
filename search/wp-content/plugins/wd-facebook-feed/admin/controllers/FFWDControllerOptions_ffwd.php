<?php

class FFWDControllerOptions_ffwd {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = ((isset($_POST['task'])) ? esc_html(stripslashes($_POST['task'])) : '');
    $id = ((isset($_POST['current_id'])) ? esc_html(stripslashes($_POST['current_id'])) : 0);
    if($task != ''){
      if(!WDW_FFWD_Library::verify_nonce('options_ffwd')){
        die('Sorry, your nonce did not verify.');
      }
    }
    if (method_exists($this, $task)) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelOptions_ffwd.php";
    $model = new FFWDModelOptions_ffwd();

    require_once WD_FFWD_DIR . "/admin/views/FFWDViewOptions_ffwd.php";
    $view = new FFWDViewOptions_ffwd($model);
    $view->display();
  }

  public function reset() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelOptions_ffwd.php";
    $model = new FFWDModelOptions_ffwd();

    require_once WD_FFWD_DIR . "/admin/views/FFWDViewOptions_ffwd.php";
    $view = new FFWDViewOptions_ffwd($model);
    echo WDW_FFWD_Library::message('Changes must be saved.', 'error');
    $view->display(true);
  }

  public function save() {
    $this->save_db();
    $this->display();
  }

  public function save_db() {
    //facebook user_id pahel autoupdate_interval
    global $wpdb;
		$id = 1;
		$autoupdate_interval = (isset($_POST['autoupdate_interval_hour']) && isset($_POST['autoupdate_interval_min']) ? ((int) $_POST['autoupdate_interval_hour'] * 60 + (int) $_POST['autoupdate_interval_min']) : 30);
    /*minimum autoupdate interval is 1 min*/
    $autoupdate_interval = ($autoupdate_interval >= 1 ? $autoupdate_interval : 1 );
		$facebook_app_id = (isset($_POST[WD_FB_PREFIX . '_app_id']) ? esc_html(stripslashes($_POST[WD_FB_PREFIX . '_app_id'])) : '');
		$facebook_app_secret = (isset($_POST[ WD_FB_PREFIX . '_app_secret']) ? esc_html(stripslashes($_POST[WD_FB_PREFIX . '_app_secret'])) : '');
		$date_timezone = (isset($_POST[ WD_FB_PREFIX . '_date_timezone']) ? esc_html(stripslashes($_POST[WD_FB_PREFIX . '_date_timezone'])) : 'Pacific/Midway');
		$post_date_format = (isset($_POST[ WD_FB_PREFIX . '_post_date_format']) ? esc_html(stripslashes($_POST[WD_FB_PREFIX . '_post_date_format'])) : 'Pacific/Midway');
		$event_date_format = (isset($_POST[ WD_FB_PREFIX . '_event_date_format']) ? esc_html(stripslashes($_POST[WD_FB_PREFIX . '_event_date_format'])) : 'Pacific/Midway');
		$save = $wpdb->update($wpdb->prefix . 'wd_fb_option', array(
			'autoupdate_interval' => $autoupdate_interval,
			'app_id' => $facebook_app_id,
			'app_secret' => $facebook_app_secret,
			'date_timezone' => $date_timezone,
			'post_date_format' => $post_date_format,
			'event_date_format' =>$event_date_format,
    ), array('id' => 1));

    if ($save !== FALSE) {
      echo WDW_FFWD_Library::message('Item Succesfully Saved.', 'updated');
			/*
			 * Clear hook for scheduled events,
			 * refresh filter according to new time interval,
			 * then add new schedule with the same hook name
			*/

			update_option('ffwd_autoupdate_time',$autoupdate_interval*60+current_time('timestamp'));

			wp_clear_scheduled_hook( 'wd_fb_schedule_event_hook' );
			remove_filter( 'cron_schedules', 'wd_fb_add_autoupdate_interval' );
			add_filter( 'cron_schedules', 'wd_fb_add_autoupdate_interval' );
			wp_schedule_event( time(), 'wd_fb_autoupdate_interval', 'wd_fb_schedule_event_hook' );
			// $schedule_info = wp_get_schedules();
			// print_r($schedule_info);
    }
    else {
      echo WDW_FFWD_Library::message('Error. Please install plugin again.', 'error');
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}
