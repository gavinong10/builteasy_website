<?php
if (!class_exists('MAD_SIDEBAR')) {

	class MAD_SIDEBAR extends MAD_FRAMEWORK {
		public $sidebars  = array();
		public $stored = "mad_sidebars";
		public $paths  = array();

		function __construct() {
			$this->paths['js'] = parent::$path['assetsJsURL'];
		    $this->paths['css'] = parent::$path['assetsCssURL'];

		    $this->title = MAD_THEMENAME ." ". __('Custom Widget Area', MAD_BASE_TEXTDOMAIN);
			$this->stored = 'mad_sidebars';

			add_action('load-widgets.php', array(&$this, 'enqueue_assets') , 4);
			add_action('load-widgets.php', array(&$this, 'add_sidebar'), 99);

			add_action('widgets_init', array(&$this, 'registerSidebars') , 900 );

			// ajax
			add_action('wp_ajax_delete_custom_sidebar', array(&$this, 'delete_sidebar') , 50);
		}

		public function registerSidebars() {
			if (empty($this->sidebars)) {
				$this->sidebars = get_option($this->stored);
			}
			$args = array(
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<div class="widget-head"><h3 class="widget-title">',
				'after_title'   => '</h3></div>'
			);
			if (is_array($this->sidebars)) {
				foreach ($this->sidebars as $sidebar) {
					$args['class'] = 'mad-custom';
					$args['name']  = $sidebar;
					$args['id']  = sanitize_title($sidebar);
					register_sidebar($args);
				}
			}
		}

		public function enqueue_assets() {
			add_action('admin_print_scripts', array(&$this, 'add_field') );
			wp_enqueue_script('custom_sidebar_js' , $this->paths['js'] . 'custom_sidebar.js');
			wp_enqueue_style( 'custom_sidebar_css' , $this->paths['css'] . 'custom_sidebar.css');
		}

		public function add_field() {
            $output = "\n<script type='text/html' id='tmpl-add-widget'>";
			$output .= "\n  <form class='form-add-widget' method='POST'>";
			$output .= "\n  <h3>". $this->title ."</h3>";
			$output .= "\n    <p><input size='30' type='text' value='' placeholder = '". __('Enter Name for new Widget Area', MAD_BASE_TEXTDOMAIN) ."' name='form-add-widget' /></p>";
			$output .= "\n    <input class='button button-primary' type='submit' value='". __('Add Widget Area', MAD_BASE_TEXTDOMAIN) ."' />";
			$output .= "\n    <input type='hidden' name='custom-sidebar-nonce' value='". wp_create_nonce('custom-sidebar-nonce') ."' />";
			$output .= "\n  </form>";
			$output .= "\n</script>\n";
			echo $output;
		}

		public function add_sidebar() {
            if (!empty($_POST['form-add-widget'])) {
                $this->sidebars = get_option($this->stored);
                $name = $this->get_name($_POST['form-add-widget']);
                if (empty($this->sidebars)) {
                    $this->sidebars = array($name);
                } else {
                    $this->sidebars = array_merge($this->sidebars, array($name));
                }
                update_option($this->stored, $this->sidebars);
                wp_redirect(admin_url('widgets.php'));
                die();
            }
		}

		public function delete_sidebar() {

            check_ajax_referer('custom-sidebar-nonce');

			if (empty($_POST['name'])) return;

			$name = stripslashes($_POST['name']);
			$this->sidebars = get_option($this->stored);

			if (($key = array_search($name, $this->sidebars)) !== false) {
				unset($this->sidebars[$key]);
				update_option($this->stored, $this->sidebars);
			}

			die('widget-deleted');
		}

		public function get_name($name) {
			global $wp_registered_sidebars;
			$take = array();

			if (empty($this->sidebars)) $this->sidebars = array();
			if (empty($wp_registered_sidebars)) return $name;

            foreach ($wp_registered_sidebars as $sidebar) {
				$take[] = $sidebar['name'];
		    }
			$take = array_merge($take, $this->sidebars);

		    if (in_array($name, $take)) {

                 $counter = substr($name, -1);

                if (!is_numeric($counter))  {
					$newName = $name . " 1";
                } else {
					$newName = substr($name, 0, -1) . ((int) $counter + 1);
                }
                $name = $this->get_name($newName);
		    }
		    return $name;
		}

	}
}









