<?php


if ( ! class_exists( 'Tve_Dash_Thrive_Icon_Manager_View' ) ) {

	/**
	 * handles the output of the partial views, as well as assigning variables to them
	 * Class Thrive_Icon_Manager_View
	 */
	class Tve_Dash_Thrive_Icon_Manager_View {
		/**
		 * @var string path to the view templates
		 */
		protected $path = '';

		/**
		 * this will hold the view data
		 * (accessible inside the view with $this->xxx)
		 * @var array
		 */
		protected $data = array();

		/**
		 * @param string $basePath
		 */
		public function __construct( $basePath ) {
			$this->path = $basePath;
		}

		/**
		 * render a view file, with optional data to assign to it
		 *
		 * @param $file
		 * @param array $withData
		 */
		public function render( $file, $withData = array() ) {
			if ( strpos( $file, '.php' ) === false ) {
				$file .= '.php';
			}

			if ( ! is_file( $this->path . '/' . $file ) ) {
				echo "No template found for {$file}";

				return;
			}

			if ( ! is_array( $withData ) ) {
				$withData = array( 'data' => $withData );
			}
			if ( ! empty( $withData ) ) {
				$this->data = array_merge_recursive( $this->data, $withData );
			}

			include $this->path . '/' . $file;
		}

		/**
		 * magic getter - get data from $this->data array
		 *
		 * @param $key
		 *
		 * @return
		 */
		public function __get( $key ) {
			return isset( $this->data[ $key ] ) ? $this->data[ $key ] : null;
		}

		/**
		 * magic setter
		 *
		 * @param $key
		 * @param $value
		 */
		public function __set( $key, $value ) {
			$this->data[ $key ] = $value;
		}

	}
}