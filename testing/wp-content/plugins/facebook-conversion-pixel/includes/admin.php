<?php
/**
 * Facebook Conversion Pixel Options
 * @since 1.0
 */
class Fb_Pxl_Admin {
 
 	/**
 	 * Option key, and option page slug
 	 * @var string
 	 */
	public static $key = 'fb_pxl_options';
 
	/**
	 * Define Options array
	 * @var array
	 */
	public static $fb_pxl_options;
 
	/**
	 * Constructor
	 * @since 1.0
	 */
	public function __construct() {
		// Set our title
		$this->title = __( 'Facebook Conversion Pixel', 'facebook-conversion-pixel' );
		$this->hooks();
 	}
 
	/**
	 * Initiate hooks
	 * @since 1.0
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_init', array( $this, 'update_options' ) );

		// Only show plugin settings page for users who can manage options
		if ( current_user_can( 'manage_options' ) ) {
			add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		}
	}
 
	/**
	 * Register setting to WP
	 * @since  1.0
	 */
	public function init() {
		register_setting( self::$key, self::$key );
	}

	/**
	 * Update Options Array
	 * @since  1.0
	 */
	public function update_options() {

		// If this is not the plugin settings page, bail
		if ( ! isset( $_GET['page'] ) || 'fb_pxl_options' !== $_GET['page'] ) {
			return;
		}

		$options = get_option( 'fb_pxl_options' );
		$post_types = get_post_types();

		// Remove any options that don't have a corresponding post type
		if ( $options ) {
			foreach ( $options as $option_key => $option_value ) {
				if ( ! array_key_exists( $option_key, $post_types ) ) {
					unset( $options[ $option_key ] );
				}
			}
		}

		// Add any post types missing from the options array
		foreach ( $post_types as $post_type ) {
			if ( ! array_key_exists( $post_type, $options ) ) {
				$options[ $post_type ] = '';
			}
		}

		// Save changes to the options array
		self::$fb_pxl_options = $options;
		update_option( 'fb_pxl_options', $options );
	}
 
	/**
	 * Add menu options page
	 * @since 1.0
	 */
	public function add_options_page() {
		$this->options_page = add_options_page( $this->title, $this->title, 'manage_options', self::$key, array( $this, 'admin_page_display' ) );
	}
 
	/**
	 * Admin page markup
	 * @since  1.0
	 */
	public function admin_page_display() {

		// If this is not the plugin settings page, bail
		if ( ! isset( $_GET['page'] ) || 'fb_pxl_options' !== $_GET['page'] ) {
			return;
		}

		// Only allow users who can manage options
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have sufficient permissions to change options.' );
		}

		$this->admin_page_setup();
		?>
		<div class="wrap cmb_options_page <?php echo self::$key; ?>">
			<h2><?php echo esc_html( get_admin_page_title() ) . ' ' . __( 'Settings', 'facebook-conversion-pixel' ); ?></h2>
		    <form method="post" action="options.php">
		    	<?php settings_fields( self::$key ); ?>
		    	<?php do_settings_sections( self::$key ); ?>
		    	<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Defines the plugin option page sections and fields
	 * @since  1.0
	 * @return array
	 */
	public function admin_page_setup() {

		add_settings_section(
		    'fb_pxl_display_on',
		    __( 'Enable Facebook Conversion Pixel field on these post types:', 'facebook-conversion-pixel' ),
		    '',
		    self::$key
		);

		// Display settings field for each post type
		if ( ! empty( self::$fb_pxl_options ) ) {
			foreach ( self::$fb_pxl_options as $option => $value ) {
				add_settings_field(
				    'fb_pxl_display_on_' . $option,
				    ucfirst( $option),
				    array( $this, 'fb_pxl_display_on_output' ),
				    self::$key,
				    'fb_pxl_display_on',
				    array( $option, $value )
				);
			}
		}
    }

    /**
	 * Display settings field values
	 * @since  1.0
	 */
	public function fb_pxl_display_on_output( $args ) {
		$option_key = $args[ 0 ];
		$option_value = $args[ 1 ];
		$html = '<input type="checkbox" id="fb_pxl_disable_' . $option_key . '" name="fb_pxl_options[' . $option_key . ']" value="on"' . checked( $option_value, "on", false ) . '/>';
		echo $html;
	}
}
 
/**
 * Get the party started
 * @since  1.0
 */
$Fb_Pxl_Admin = new Fb_Pxl_Admin();