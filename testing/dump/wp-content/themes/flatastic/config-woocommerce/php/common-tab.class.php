<?php

if (!class_exists('MAD_CUSTOM_TABS')) {

	class MAD_CUSTOM_TABS {

		public $paths = array();

		public function path($name, $file = '') {
			return $this->paths[$name] . (strlen($file) > 0 ? '/' . preg_replace('/^\//', '', $file) : '');
		}

		public function assetUrl($file)  {
			return $this->paths['BASE_URI'] . $this->path('ASSETS_DIR_NAME', $file);
		}

		function __construct() {

			$this->paths = array(
				'BASE_URI' => trailingslashit(get_template_directory_uri()) . trailingslashit('config-woocommerce'),
				'ASSETS_DIR_NAME' => 'assets'
			);

			if (is_admin()) {
				add_action('add_meta_boxes', array(&$this, 'dynamic_add_custom_tab'));

				add_action('load-post.php', array($this, 'add_assets') , 4);
				add_action('load-post-new.php', array($this, 'add_assets') , 4 );

				/* Do something with the data entered */
				add_action('save_post', array(&$this, 'dynamic_save_postdata') );
			}

			add_filter('woocommerce_product_tabs', array(&$this, 'product_custom_tabs'));

		}

		function product_custom_tabs ($tabs) {
			global $post;

			$mad_custom_tabs = get_post_meta($post->ID, 'mad_custom_tabs', true);
			$priority = 50;

			if (!mad_custom_get_option('show_review_tab')) {
				unset($tabs['reviews']);
			}

			if (isset($mad_custom_tabs) && !empty($mad_custom_tabs) && count($mad_custom_tabs) > 0) {
				foreach(@$mad_custom_tabs as $id => $tab) {
					if (isset($tab['mad_title_product_tab']) && $tab['mad_title_product_tab'] != '' && isset($tab['mad_content_product_tab'])) {
						$tabs[$id] = array(
							'title' => $tab['mad_title_product_tab'],
							'priority' => $priority,
							'callback' => 'mad_woocommerce_product_custom_tab'
						);
					}
					$priority = $priority + 1;
				}
			}

			return $tabs;
		}

		function add_assets() {
			add_action('print_media_templates', array(&$this, 'add_tmpl') );
			wp_enqueue_script('mad_custom_tab_js', $this->assetUrl('js/custom_tab.js'));
			wp_enqueue_style('mad_custom_tab_css', $this->assetUrl('css/custom_tab.css'));
		}

		public function add_tmpl() {

			$settings = array(
				'textarea_name' => 'mad_custom_tabs[__REPLACE_SSS__][mad_content_product_tab]',
				'textarea_rows' => 3,
				'quicktags' => true,
				'tinymce' => false
			);

			ob_start(); ?>

			<script type="text/html" id="tmpl-add-custom-tab">
				<li>
					<div class="handle-area"></div>
					<div class="item">
						<h3><?php _e('Title Custom Tab', 'flatastic'); ?></h3>
						<input type="text" name="mad_custom_tabs[__REPLACE_SSS__][mad_title_product_tab]" value=""/>
						<p class="desc"><?php _e('Enter a title for the tab (required field)', 'flatastic'); ?></p>
					</div>
					<div class="item wp-editor">
						<h3><?php _e('Content Custom Tab', 'flatastic'); ?></h3>
						<?php wp_editor( '', '__REPLACE_SSS__', $settings ); ?>
					</div>
					<div class="item">
						<a href="javascript:void(0)" class="button button-secondary remove-custom-tab"><?php _e('Remove Custom Tab', 'flatastic'); ?></a>
					</div>
				</li>
			</script>

			<?php echo ob_get_clean();
		}

		function dynamic_add_custom_tab() {
			add_meta_box('mad_dynamic_custom_tab', __( 'Custom Tabs', 'flatastic' ), array(&$this, 'dynamic_inner_custom_tab'), 'product', 'advanced', 'high');
		}

		/* Prints the box content */
		function dynamic_inner_custom_tab() {
			global $post;

			// Use nonce for verification
			wp_nonce_field( 'mad-custom-tab', 'dynamicMeta_noncename' );
			?>

			<div id="meta_custom_tabs">

				<?php
					// get the saved meta as an array
					$mad_custom_tabs = get_post_meta($post->ID, 'mad_custom_tabs', true);
				?>

				<ul class="custom-box-holder">

					<?php if (isset($mad_custom_tabs) && !empty($mad_custom_tabs) && count($mad_custom_tabs) > 0): ?>

						<?php foreach(@$mad_custom_tabs as $id => $tab): ?>

							<?php if (isset($tab['mad_title_product_tab']) || isset($tab['mad_content_product_tab'])): ?>

								<li>
									<div class="handle-area"></div>
									<div class="item">
										<h3><?php _e('Title Custom Tab', 'flatastic'); ?></h3>
										<input type="text" name="mad_custom_tabs[<?php echo esc_attr($id); ?>][mad_title_product_tab]" value="<?php echo esc_attr($tab['mad_title_product_tab']); ?>" />
										<p class="desc"><?php _e('Enter a title for the tab (required field)', 'flatastic'); ?></p>
									</div>
									<div class="item wp-editor">
										<h3><?php _e('Content Custom Tab', 'flatastic'); ?></h3>
										<?php wp_editor( $tab['mad_content_product_tab'], esc_attr($id), array('textarea_name' => 'mad_custom_tabs['. $id .'][mad_content_product_tab]', 'textarea_rows' => 3, 'tinymce' => false) ); ?>
									</div>
									<div class="item">
										<a href="javascript:void(0)" class="button button-secondary remove-custom-tab"><?php _e('Remove Custom Tab', 'flatastic'); ?></a>
									</div>
								</li>

							<?php endif; ?>

						<?php endforeach; ?>

					<?php endif; ?>

				</ul><!--/ .custom-tabs-->

				<a href="javascript:void(0);" class="button button-primary add-custom-tab"><?php _e('Add Custom Tab'); ?></a>

			</div><?php

		}

		/* When the post is saved, saves our custom data */
		function dynamic_save_postdata ($post_id) {

			// verify if this is an auto save routine.
			// If it is our form has not been submitted, so we dont want to do anything
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return;

			// verify this came from the our screen and with proper authorization,
			// because save_post can be triggered at other times
			if ( !isset( $_POST['dynamicMeta_noncename'] ) )
				return;

			if ( !wp_verify_nonce( $_POST['dynamicMeta_noncename'], 'mad-custom-tab' ) )
				return;

			if ( !isset( $_POST['mad_custom_tabs'] ) ) {
				$_POST['mad_custom_tabs'] = '';
			}

			$tabs = $_POST['mad_custom_tabs'];

			update_post_meta($post_id, 'mad_custom_tabs', $tabs);
		}

	}

	new MAD_CUSTOM_TABS();
}

