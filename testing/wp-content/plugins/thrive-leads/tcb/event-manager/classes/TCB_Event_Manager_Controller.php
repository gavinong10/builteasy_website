<?php
/**
 * Created by PhpStorm.
 * User: radu
 * Date: 06.08.2014
 * Time: 10:08
 */

/**
 * controller class to handle all the server-side logic for the Event Manager setup
 *
 * it will read the requested "step" / "route" from POST and return the appropriate HTML for that screen
 * Class TCB_Event_Manager_Controller
 */
class TCB_Event_Manager_Controller {
	/**
	 * @var TCB_Event_Manager_Controller
	 */
	private static $_instance = null;

	/**
	 * @var array
	 */
	protected $request = array();

	/**
	 * the response
	 * @var string
	 */
	protected $response = '';

	protected $viewRoute = false;

	/**
	 * singleton getInstance implementation
	 * @return TCB_Event_Manager_Controller
	 */
	public static final function getInstance() {
		if ( null === self::$_instance ) {
			self::$_instance = new TCB_Event_Manager_Controller( $_REQUEST );
		}

		return self::$_instance;
	}

	/**
	 *
	 * @param $data array request data
	 */
	public function __construct( $data ) {
		$this->request = $data;
	}

	/**
	 * get a parameter from the request. If the requested parameter is not present, returns the $default value
	 *
	 * @param $key
	 * @param null $default
	 */
	protected function param( $key, $default = null ) {
		return isset( $this->request[ $key ] ) ? $this->request[ $key ] : $default;
	}

	/**
	 * dispatch an AJAX request:
	 * call the propriate method based on 'route' parameter
	 */
	public function dispatch() {
		/* check if we can execute the requested route */
		$route = $this->param( 'route' );
		$fn    = $route . 'Action';

		if ( ! method_exists( $this, $fn ) ) {
			$this->send();
		}

		$viewVariables = call_user_func( array( $this, $fn ) );

		$this->renderView( $this->viewRoute ? $this->viewRoute : $route, $viewVariables )
		     ->send();
	}

	/**
	 * render the associated view for the matched route
	 * this will search in the views/ folder for a file that matches the $route
	 *
	 * @param $route
	 * @param array $data
	 *
	 * @return TCB_Event_Manager_Controller
	 */
	protected function renderView( $route, $data = array() ) {
		$view = dirname( dirname( __FILE__ ) ) . '/views/' . $route . '.php';

		if ( ! file_exists( $view ) ) {
			$this->response = 'No view found for ' . $route;

			return $this;
		}
		extract( $data );

		$scope = $this->param( 'scope' );
		ob_start();
		include $view;
		$this->response = ob_get_contents();
		ob_end_clean();

		return $this;
	}

	/**
	 * list the Events associated for an element, or display a message if no event is added
	 */
	public function listAction() {
		$scope = $this->param( 'scope', '' );

		return array(
			'triggers' => tve_get_event_triggers( $scope ),
			'actions'  => $this->getActions( $scope ),
			'events'   => $this->param( 'events', array() )
		);
	}

	/**
	 * display controls for adding / editing new events
	 */
	public function formAction() {
		$scope = $this->param( 'scope', '' );

		$actions = $this->getActions( $scope );

		/** @var TCB_Event_Trigger_Abstract[] $triggers */
		$triggers = tve_get_event_triggers( $scope );

		$data = array(
			'triggers'              => $triggers,
			'actions'               => $actions,
			'selected_trigger_code' => $this->param( 't', '' ),
			'selected_action_code'  => $this->param( 'a', '' ),
			'is_edit'               => $this->param( 'edit_page', '' ),
			'item_settings'         => $this->renderForm()
		);

		return $data;
	}

	/**
	 * render the actual individual settings for the selected trigger / event
	 */
	protected function renderForm( $data = array() ) {
		$html  = '';
		$scope = $this->param( 'scope', '' );

		$config = $this->param( 'config', array() );

		/** @var TCB_Event_Action_Abstract[] $actions */
		$actions = $this->getActions( $scope );
		/** @var TCB_Event_Trigger_Abstract[] $triggers */
		$triggers = tve_get_event_triggers( $scope );

		$trigger_code = $this->param( 't' );
		$trigger      = empty( $trigger_code ) || ! isset( $triggers[ $trigger_code ] ) ? null : $triggers[ $trigger_code ];
		if ( ! empty( $trigger ) ) {
			$html .= $trigger->setConfig( $config )->renderSettings( $data );
		}

		$action_code = $this->param( 'a' );
		$action      = empty( $action_code ) || ! isset( $actions[ $action_code ] ) ? null : $actions[ $action_code ];

		if ( ! empty( $action ) ) {
			$html .= $action->setConfig( $config )->renderSettings( $data );
		}

		return $html;
	}

	/**
	 * render the settings control for a specific action
	 */
	public function settingsAction( $extra_data = array() ) {
		$this->response = $this->renderForm( $extra_data );

		$this->send();
	}

	/**
	 * wrapper over the tve_get_event_actions function
	 * return all actions and checks for disabled actions in the request data
	 * disabled actions might be set from API
	 *
	 * @param string $scope
	 *
	 * @return array
	 */
	protected function getActions( $scope ) {
		/** @var TCB_Event_Action_Abstract[] $actions */
		$actions = tve_get_event_actions( $scope );

		if ( ( $disabled_controls = $this->param( 'disabled_controls', null ) ) !== null ) {
			if ( ! empty( $disabled_controls['event_manager'] ) ) {
				foreach ( $disabled_controls['event_manager'] as $key ) {
					unset( $actions[ $key ] );
				}
			}
		}

		foreach ( $actions as $key => $impl ) {
			if ( ! $impl->isAvailable() ) {
				unset( $actions[ $key ] );
			}
		}

		return $actions;
	}

	/**
	 * create a new lightbox, either for a landing page or for a regular page
	 */
	public function add_lightboxAction() {
		$post_id = $this->param( 'post_id' );
		if ( ! $post_id ) {
			$this->send();
		}

		$post = get_post( $post_id );

		$landing_page_template = tve_post_is_landing_page( $post_id );
		if ( $landing_page_template ) {
			$config = tve_get_landing_page_config( $landing_page_template );
		}
		$all_lightboxes = get_posts( array(
			'posts_per_page' => - 1,
			'post_type'      => 'tcb_lightbox',
		) );
		$lightbox_title = 'Lightbox - ' . $post->post_title . ( ! empty( $config['name'] ) ? ' (' . $config['name'] . ')' : '' ) . '-' . ( count( $all_lightboxes ) + 1 );
		if ( $landing_page_template ) {
			require_once dirname( dirname( dirname( __FILE__ ) ) ) . '/landing-page/inc/TCB_Landing_Page.php';
			$tcb_landing_page = new TCB_Landing_Page( $post_id, $landing_page_template );
			$lightbox_id      = $tcb_landing_page->newLightbox();
		} else {
			$lightbox_id = tve_create_lightbox( $lightbox_title, '', array(), array() );
		}

		$this->response = $this->renderForm( array(
			'success_message' => sprintf(
				'<strong>%s</strong> has been created. Click <a href="%s" target="_blank"><strong style="color: #000000">here</strong></a> to edit it (will open in a new tab).',
				$lightbox_title,
				tcb_get_editor_url( $lightbox_id )
			)
		) );

		$this->send();
	}

	/**
	 * output the response
	 */
	protected function send() {
		echo $this->response;
		exit();
	}
} 