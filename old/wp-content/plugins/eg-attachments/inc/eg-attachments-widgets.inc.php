<?php

if (! class_exists('EG_Attachments_Widget')) {

	define('EGA_WIDGET_ID', 'eg-attach' );

	Class EG_Attachments_Widget extends EG_Widget_220 {

		var $textdomain = EGA_TEXTDOMAIN;
		var $cache_id	= 'ega-widget-cache';
		/**
		 * __construct
		 *
		 * Constructor of the widget. Define parameters
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	none
		 * @return	none
		 *
		 */
		function __construct() {
			global $EGA_SHORTCODE_DEFAULTS;
			global $EGA_FIELDS_ORDER_LABEL;

			$plugin_options = get_option(EGA_OPTIONS_ENTRY);

			// widget settings
			$widget_ops = array('classname' => 'widget_attachments',
								'description' => __('Display attachments of the current post', $this->textdomain )
							);

			// create the widget
			parent::__construct(EGA_WIDGET_ID, 'EG-Attachment Widget', $widget_ops);

			$templates_list = EG_Attachments_Common::get_templates($plugin_options, 'all');

			$this->fields = array(
				'title'				=> array( 'type'  => 'large_text',	'label'  => 'Title'),
				'template'			=> array( 'type'  => 'select',	'label'  => 'Template', 'list' => $templates_list),
				'doctype'			=> array( 'type'  => 'select',	'label'  => 'Document type',
					'list' => array( 'all'   => 'All', 	'document' => 'Documents', 'image' => 'Images')),
				'exclude_thumbnail'	=> array( 'type'  => 'checkbox', 'label'  => 'Check to exclude the feature image from the attachments list',
												'open' => 'Exclude thumbnail', 'close' => ''),
				'orderby'			=> array( 'type'  => 'select',   'label' => 'Order by',
					'list' => $EGA_FIELDS_ORDER_LABEL), /*array_intersect_key($EG_ATTACH_FIELDS_TITLE, $EG_ATTACH_FIELDS_ORDER_KEY)) */
				'order'				=> array( 'type'  => 'select',   'label'  => 'Order',
					'list' => array( 'ASC'   => 'Ascending', 'DESC'  => 'Descending')),
				'force_saveas'		=> array( 'type'  => 'checkbox', 'label'  => 'Force "Save As" when users click on the attachments', 'open' => '"Save As" activation', 'close' => ''),
				'limit'				=> array( 'type'  => 'small_text', 'label' => 'Number of documents to display'),
				'nofollow'	  		=> array( 'type'  => 'checkbox', 'label'  => 'add automatically <code>rel="nofollow"</code> to attachment links', 'open' => 'Attributs'),
				'target'	  		=> array( 'type'  => 'checkbox', 'label'  => 'Add automatically <code>target="_blank"</code> to attachment links', 'close' => ''),
				'icon_image'    	=> array( 'type' => 'radio', 'label' => 'When a list of attachments includes images, do you want to display',
									'list' => array( 'icon'	=> 'The icon of the file type', 'thumbnail' => 'The Thumbnail of the image')),
				'logged_users' 		 => array( 'type'  => 'select', 'label' => 'Attachments access',
					'list' => array( -1 => 'Use default parameter', 
							  0 => 'Display attachments for all users', 
							  1 => 'Show attachments for everyone, but the url, for logged users only',
							  2 => 'Display attachments for logged users only'))
			);

			if ($plugin_options['tags_assignment']) {
//				$fields['tags'] = array( 'type'  => 'select', 'label' => 'Tags', 'list' => eg_attach_get_tags_select('array'));
				$this->fields['tags'] = array( 'type'  => 'text',	'label'  => 'Tags:');
			}

			$this->default_options = EG_Attachments_Common::get_shortcode_defaults($plugin_options);
			list($this->default_options['orderby'], $this->default_options['order']) = explode(' ', $this->default_options['orderby']);
			$this->default_options['title'] = __('Attachments', $this->textdomain);

		} // End of constructor

		/**
		 * widget
		 *
		 * Display the widget
		 *
		 * @package EG-Attachments
		 * @since 	1.0
		 *
		 * @param 	array	$args		sidebar parameters
		 * @param	array	$instance	widget parameters
		 * @return	none
		 *
		 */
		function widget($args, $instance) {
			global $eg_attach_public;
			global $EGA_SHORTCODE_DEFAULTS;

			$output = '';

			if (is_singular() && isset($eg_attach_public)) {
			
				// Added in WP 3.9.x in order to manage widget preview
				if ( EGA_ENABLE_CACHE && ! $this->is_preview() ) {
					$output = wp_cache_get( $this->cache_id );    
				}
				if ( empty( $output ) ) {

					/* --- Extract parameters --- */
					extract($args);

					$values = wp_parse_args( (array) $instance, $this->default_options );

					// Put title to '', before calling the shortcode
					$widget_title 		= $values['title'];
					$values['title']    = '';
					$values['orderby'] .= ' '.$values['order'];
					unset($values['order']);

					$shortcode = '[attachments';
					foreach ($values as $key => $value) {
						if ($value != $EGA_SHORTCODE_DEFAULTS[$key])
							$shortcode .= ' '.$key.'='.(is_numeric($value) ? $value : '"'.$value.'"');
					} // End of foreach
					$shortcode .= ']';
					$output = do_shortcode($shortcode);

					if ($output != '') {
						$title = apply_filters('widget_title', $widget_title, $values, $this->id_base);

						$output = $before_widget.
							('' != $title ? $before_title.esc_html($title, $this->textdomain).$after_title:'').
							$output.
							$after_widget;
							
						if ( EGA_ENABLE_CACHE && ! $this->is_preview() ) {
							 wp_cache_set( $this->cache_id, $output );			
						}

					} // End of $output != ''
					
				} // End of cache empty
						
				echo $output;

			} // End of (is_singular && $eg_attach)
		} // End of widget

	} // End of class

} // End of class_exists EG_Attach_Widget

function eg_attachments_widgets_init() {
	register_widget('EG_Attachments_Widget');
}
add_action('init', 'eg_attachments_widgets_init', 1);

?>