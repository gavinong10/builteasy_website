<?php

class FFWDControllerUninstall_ffwd {
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

	  global  $ffwd_options;
	  if(!class_exists("DoradoWebConfig")){
		  include_once (WD_FFWD_DIR . "/wd/config.php");
	  }
	  $config = new DoradoWebConfig();

	  $config->set_options( $ffwd_options );

	  $deactivate_reasons = new DoradoWebDeactivate($config);
//$deactivate_reasons->add_deactivation_feedback_dialog_box();	
	  $deactivate_reasons->submit_and_deactivate();
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = ((isset($_POST['task'])) ? esc_html(stripslashes($_POST['task'])) : '');
    if($task != ''){
      if(!WDW_FFWD_Library::verify_nonce('uninstall_ffwd')){
        die('Sorry, your nonce did not verify.');
      }
    }
    if (method_exists($this, $task)) {
      $this->$task();
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelUninstall_ffwd.php";
    $model = new FFWDModelUninstall_ffwd();

    require_once WD_FFWD_DIR . "/admin/views/FFWDViewUninstall_ffwd.php";
    $view = new FFWDViewUninstall_ffwd($model);
    $view->display();
  }

  public function uninstall() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelUninstall_ffwd.php";
    $model = new FFWDModelUninstall_ffwd();

    require_once WD_FFWD_DIR . "/admin/views/FFWDViewUninstall_ffwd.php";
    $view = new FFWDViewUninstall_ffwd($model);
    $view->uninstall();
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