<?php

if (!class_exists('MAD_HTML_BUILD')) {

	class MAD_HTML_BUILD extends MAD_FRAMEWORK {

		public $default = array();
		public $actionResetOptions  = 'ajax_reset_options';
		public $actionSaveOptions   = 'ajax_save_options_page';
		public $actionImportOptions = 'ajax_import_options_page';

		public $actionImportConfigOptions = 'ajax_import_config_options';

		function __construct($globalObject = '') {
			$this->globalObject = $globalObject;

			$options = get_option($this->globalObject->option_prefix);

			foreach($globalObject->option_pages as $page) {
				if (isset($options[$page['parent']]) && $options[$page['parent']] != "") {
					$this->default[$page['slug']] = true;
				}
			}
		}

		function get_page_elements($slug) {
			$page_elements = array();

			if (isset($this->globalObject->option_page_data)) {
				foreach($this->globalObject->option_page_data as $key => $value) {
					if ($value['slug'] == $slug) {
						$page_elements[$key] = $value;
					}
				}
			}

			return $page_elements;
		}

		function create_element($element) {

			if (method_exists($this, $element['type'])) {
				$output = "";

				if ( !isset( $element['name']  ) )   { $element['name']  = ""; }
				if ( !isset( $element['id'] ) ) 	 { $element['id'] = "";    }
				if ( !isset( $element['desc']  ) )   { $element['desc']  = ""; }
				if ( !isset( $element['label'] ) )   { $element['label'] = ""; }
				if ( !isset( $element['std'] ) )     { $element['std'] = "";   }

				if (isset($this->globalObject->page_slug) && isset($this->default[$element['slug']]) && isset($this->globalObject->options[$this->globalObject->page_slug][$element['id']])) {
					$element['std'] = $this->globalObject->options[$this->globalObject->page_slug][$element['id']];
				}

				if (isset($element['desc']) && $element['desc'] == false || $element['type'] == 'heading') {
					$output .= $this->element_type($element);
				} else {
					$output .= $this->section_start($element);
					$output .= $this->element_type($element);
					$output .= $this->section_description($element);
					$output .= $this->section_end($element);
				}
				return $output;
			}
		}

		function create_container($option_page) {
			$output = "";

			// Get page elements
			$page_elements = $this->get_page_elements($option_page['slug']);

			// Container start
			$output .= $this->container_start($option_page);

			// Render elements
			foreach ($page_elements as $element) {
				$output .= $this->create_element($element);
			}

			// Container end
			$output .= $this->container_end();
			return $output;
		}

		function text($element) {
			return '<input type="text" value="' . $element['std'] . '" id="' . $element['id'] . '" name="' . $element['id'] . '"/>';
		}

		function number($element) {
			$output = '';
			if (isset($element['min']) && !empty($element['min'])) {
				$min = "min='{$element['min']}'";
			}
			if (isset($element['max']) && !empty($element['max'])) {
				$max = "max='{$element['max']}'";
			}
			$output .= '<input type="number" '. $min .' '. $max .' value="' . $element['std'] . '" id="' . $element['id'] . '" name="' . $element['id'] . '"/>';
			return $output;
		}

		function textarea($element) {
			$output  = '<textarea rows="6" cols="25" name="' . $element['id'] . '" id="' . $element['id'] . '">';
			$output .= $element['std'] . '</textarea>';
			return $output;
		}

		function editor($element) {
			ob_start();

			$args = array(
				'textarea_rows' => (isset($element['rows'])) ? $element['rows'] : 2,
				'media_buttons' => false,
				'wpautop' => false,
				'textarea_name'=> $element['id'],
				'tinymce' => false,
				'quicktags' => true
			);

			wp_editor($element['std'], $element['id'], $args);
			return ob_get_clean();
		}

		function upload($element) {
			$output = "";
			$image_url = $element['std'];

			$output .= '<div class="upload-wrap">';
			$output .= '<input type="text" value="' . $image_url . '" class="uploader-upload-input" name="' . $element['id'] . '" id="' . $element['id'] . '" />';
			$output .= '<a href="#" title="' . $element['name'] . '" class="mad-option-button uploader-upload-button" id="'. $element['type'] . '_' . $element['id'] .'">Upload</a>';

			if (preg_match('!\.jpg$|\.jpeg$|\.ico$|\.png$|\.gif$!', $image_url) ) {
				$image = '<a href="#" class="uploader-remove-preview">Remove</a><img src="'. $image_url .'" alt="" />';
			} else {
				$image = '';
			}
			$output .= '<div class="preview-thumbnail-container">' . $image;
			$output .= '</div>';

			$output .= '</div>';
			return $output;
		}

		function checkbox($element) {
			$checked = "";
			if ($element['std'] != '' && $element['std'] != '0') {
				$checked = 'checked = "checked"';
			}
			$output = '<div class="checkbox-wrap">';
			$output .= '<input '. $checked .' type="checkbox" value="' . $element['id'] . '" id="' . $element['id'] . '" name="' . $element['id'] . '"/>';
			if (!empty($element['label'])) {
				$output .= '<label for="'. $element['id'] .'">'. $element['label'] .'</label>';
			}
			$output .= '</div><!--/ .checkbox-wrap-->';
			return $output;
		}

		function radio($element) {
			$count = 1;
			$output = '';
			foreach($element['radiobuttons'] as $key => $label) {
				$checked = "";
				if ( $element['std'] == $key ) { $checked = 'checked = "checked"'; }
				$output .= '<div class="radio-wrap">';
				$output .= '<input type="radio" '. $checked .' value="' . $key . '" name="' . $element['id'] . '" id="' . $element['id'] . '_' . $count . '" />';
				$output .= '<label for="' . $element['id'] . '_' . $count . '">' . $label . '</label>';
				$output .= '</div>';
				$count++;
			}
			return $output;
		}

		function buttons_set($element) {

			$output = '<div class="buttonsset">';

			if (!empty($element['options'])) {

				foreach ($element['options'] as $key => $option) {
					$active = "";

					if ($key == $element['std'])
						$active = " active";

					$output .= "<a href='#' data-value=" . $key . " class='buttonset" . $active ."'>". $option ."</a>";
				}
			}

			$output .= '<input type="hidden" name="'. $element['id'] .'" id="'. $element['id'] .'" value="'. $element['std'] .'" />';

			$output .= "</div>";

			return $output;

		}

		function switch_set($element) {

			$output = $cb_enabled = $cb_disabled = '';

			if ( (int) $element['std'] == 1 ) {
				$cb_enabled = ' selected';
			} else {
				$cb_disabled = ' selected';
			}

			if (!empty($element['options'])) {
				$output = '<div class="switch_set">';
					$output .= '<label class="cb-enable' . $cb_enabled . '" data-value="1"><span>' . $element['options']['on'] . '</span></label>';
					$output .= '<label class="cb-disable' . $cb_disabled . '" data-value="0"><span>' . $element['options']['off'] . '</span></label>';
					$output .= '<input type="hidden" id="' . $element['id'] . '" name="' . $element['id'] . '" value="' . $element['std'] . '" />';
				$output .= '</div>';
			}

			return $output;
		}

		function widget_positions ($element) {

			$columns = $element['columns'];
			$footer_row_widgets_array = json_decode(html_entity_decode($element['std']), true);

			ob_start();

			?>
			<div class="meta-set">

				<?php if (is_array($footer_row_widgets_array)): ?>

					<div class="meta-list-set">

						<span><?php _e('Columns', MAD_BASE_TEXTDOMAIN); ?>:</span>

						<ul class="options-columns">
							<?php for ( $i = 1; $i < $columns + 1; $i++ ) : $active_class = '';
								if ( $i == key($footer_row_widgets_array) ) { $active_class = 'active'; }
								?>

								<li data-val="<?php echo (int) $i ?>" class="<?php echo esc_attr($active_class) ?>"><?php echo (int) $i ?></li>

							<?php endfor; ?>
						</ul>

					</div><!--/ .meta-list-set-->

					<div class="meta-columns-set">

						<?php $letters = array('a','b','c','d', 'e', 'f') ?>

						<?php for ($i = 1; $i < $columns + 1; $i++):
							$css_class = $col = '';
							if ($i > key($footer_row_widgets_array)) {
								$css_class = 'hidden';
							} else {
								$col = $footer_row_widgets_array[key($footer_row_widgets_array)][0][$i-1];
							}
							?>

							<div class="mod-columns <?php if (!empty($col)) { echo "mod-grid-{$col}"; } ?> <?php echo esc_attr($css_class) ?>">
								<span><?php echo $letters[$i-1]; ?></span>
							</div><!--/ .mod-columns-->

						<?php endfor; ?>

					</div><!--/ .meta-columns-set-->

					<input id="<?php echo $element['id'] ?>" type="hidden" class="data-widgets-hidden" data-columns="<?php echo key($footer_row_widgets_array); ?>" name="<?php echo $element['id'] ?>" value='<?php echo $element['std'] ?>' />

				<?php endif; ?>

			</div><!--/ .meta-set-->

			<?php return ob_get_clean();
		}

		function logo($element) {

		}

		function color_schemes ($element) {
			$output = '<div class="color-schemes-list">';

			if (!empty($element['options'])) {

				foreach ($element['options'] as $key => $scheme) {

					$data = $active = $style = "";

					if ($scheme['color_scheme'] == $element['std']) {
						$active = " active";
					}

					if (isset($scheme['style'])) {
						$style = " style='". $scheme['style'] ."' ";
					}

					foreach($scheme as $schemekey => $value) {
						if (is_array($value)) $value = implode(", ",$value);
						$data .= " data-$schemekey='$value' ";
					}

					$output .= "<a href='#' ". $data ." ". $style ." class='color-scheme-link" . $active ."'>". $key ."</a>";
				}
			}

			$output .= '<input type="hidden" name="'. $element['id'] .'" id="'. $element['id'] .'" value="'. $element['std'] .'" />';

			$output .= "</div>";
			return $output;
		}

		function heading ($element) {
			$output  = '<div class="heading-section mad_'. $element['type'] .'">';
			$heading = 'h3';

			if (!empty($element['heading'])) {
				$heading = $element['heading'];
			}

			if ($element['name'] != "") {
				$output .= "<{$heading} class='heading-section-title'>". $element['name'] ."</{$heading}>";
			}
			if (!empty($element['desc'])) {
				$output .= '<span>' . $element['desc'] .'</span>';
			}
			$output .= '</div>';
			return $output;
		}

		function select($element) {
			if ($element['options'] == 'post') {
				$options = get_posts('title_li=&orderby=name&numberposts=-1');
			} else if($element['options'] == 'page') {
				$options = get_pages('title_li=&orderby=name');
			} else if($element['options'] == 'cat')	{
				$taxonomy = "";
				if (!empty($element['taxonomy'])) {
					$taxonomy = "&taxonomy=". $element['taxonomy'];
				}
				$options = get_categories('title_li=&orderby=name&hide_empty=0'. $taxonomy);
			} else if ($element['options'] == 'range') {
				$options['Default'] = "";
				$range = explode("-", $element['range']);
				$unit = !isset($element["unit"]) ? 'px' : $element["unit"];
				$increment 	= !isset($element["increment"]) ? 1 : $element['increment'];

				for ($i = $range[0]; $i <= $range[1]; $i += $increment) {
					$options[$i . $unit] = $i . $unit;
				}
			} else if ($element['options'] == 'custom_sidebars') {
				global $wp_registered_sidebars;
				$custom_sidebars = array('' => 'General Widget Area');
				$exclude = array('General Widget Area');
				foreach ($wp_registered_sidebars as $sidebar) {
					if (!in_array($sidebar['name'], $exclude)) {
						if (strpos($sidebar['name'], 'Footer Row') === false) {
							$custom_sidebars[$sidebar['name']] = $sidebar['name'];
						}
					}
				}
				$options = $custom_sidebars;
			} else {
				$options = $element['options'];
			}

			$output = '<div class="select-wrap">';
			$output .= '<select id="'. $element['id'] .'" name="'. $element['id'] . '"> ';

			foreach ($options as $key => $entry) {

				if (!empty($entry)) {
					if ($element['options'] == 'page' || $element['options'] == 'post') {
						$std = $entry->ID;
						$title = $entry->post_title;
					} else if($element['type'] == 'cat') {
						if (isset($entry->term_id)) {
							$std = $entry->term_id;
							$title = $entry->name;
						}
					} else {
						$std = $key;
						$title = $entry;
					}

					$selected = "";
					if ($element['std'] == $std) {
						$selected = "selected='selected'";
					}

					$output .= "<option $selected value='". $std ."'>". $title ."</option>";
				}
			}
			$output .= '</select>';
			$output .= '</div>';
			return $output;
		}

		function color($element) {
			$output  = '<div class="colorpicker-wrap">';
			$output .= '<input type="text" class="wp-color-picker" id="'. $element['id'] .'" data-default-color="'. $element['default'] .'" name="'. $element['id'] .'" value="'. $element['std'] .'" />';
			$output .= '</div>';
			return $output;
		}

		function section_start($element) {
			$el_class = $required ='';

			if (isset($element['required'])) {
				$required = '<input type="hidden" value="'. $element['required'][0] .':'. $element['required'][1] .'" class="mad_required" />';
				$el_class = 'mad_required_section';
			}

			if (isset($element['class'])) {
				$el_class .= ' '. $element['class'];
			}

			$output  = '<div class="mad-section '. $el_class .' mad_' . $element['type'] . '" id="prefix_' . $element['id'] . '">';
			$output .= $required;
			if ($element['name'] != "") {
				$output .= '<h5 class="mad-title-section">' . $element['name'] . '</h5>';
			}

			$output .= '<div class="mad-control-container clearfix '. $element['type'] .'">';
			$output .= '<div class="mad-control">';
			return $output;
		}

		function element_type($element) {
			return $this->$element['type']($element);
		}

		function section_description($element) {
			ob_start(); ?>

			</div><!--/ .mad-control-->
			<div class="mad-description">
				<?php echo $element['desc']; ?>
			</div><!--/ .mad-description-->

			<?php return ob_get_clean();
		}

		function section_end($element) {
			ob_start(); ?>

			</div><!--/ .mad-control-container-->
			</div><!--/ .mad-section-->

			<?php if (isset($element['clear']) && !empty($element['clear'])): ?>
				<div class="clear"></div>
			<?php endif; ?>

			<?php return ob_get_clean();
		}

		function page_header() {

			ob_start(); ?>

			<form id="mad-options-page" class="mad-admin-main" action="#" method="post">

				<div class="mad-admin-bar">
					<a class="help" target="_blank" href="http://velikorodnov.com/online-documentation/flatastic/"><?php _e('Help', MAD_BASE_TEXTDOMAIN); ?></a>
					<a class="logout" href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', MAD_BASE_TEXTDOMAIN); ?></a>
				</div><!--/ .mad-admin-bar-->

				<aside class="mad-admin-aside">

					<div id="mad-admin-logo">

						<?php
							global $mad_theme_data;
							$title = $mad_theme_data['title'];
							$prefix = substr($title, 0, 4);
							$title = substr($title, 4);
							$version = $mad_theme_data['version'];
						?>

						<?php if ($title): ?>
							<a class="mad-admin-link" href="<?php echo admin_url('admin.php?page=mad'); ?>">
								<h1 class="mad-admin-logo <?php if (is_child_theme()): ?>child-theme<?php endif; ?>"><span><?php echo esc_html($prefix) ?></span><?php echo esc_html($title) ?></h1>
							</a>
						<?php endif; ?>

						<?php if ($version): ?>
							<span class="mad-admin-version">v.<?php echo esc_html($version) ?></span>
						<?php endif; ?>

						<?php do_action('options_admin_logo'); ?>

					</div><!--/ #mad-admin-logo-->

					<nav id="mad-admin-nav" class="mad-admin-nav"></nav>

				</aside><!--/ .mad-admin-aside-->

				<?php echo $this->header_holder(); ?>

				<section class="mad-admin-content">

					<div class="mad-main-holder">

			<?php return ob_get_clean();
		}

		function header_holder() {
			$title = $this->globalObject->theme_data['title'];

			ob_start(); ?>

			<div class="mad-heading-holder">
				<div class="mad-heading">
					<h3 class="mad-heading-title"><?php echo strip_tags($title) . ' ' . __('Theme Options', MAD_BASE_TEXTDOMAIN); ?></h3>
				</div><!--/ .mad-heading-->

				<ul class="mad-optional-links">
					<li><?php echo $this->save_button(); ?></li>
				</ul><!--/ .mad-optional-links-->
			</div><!--/ .mad-heading-holder-->

			<?php return ob_get_clean();
		}

		function page_footer() {
			ob_start(); ?>

			</div><!--/ .mad-main-holder-->

			<?php echo $this->hidden_fields(); ?>

			</section><!--/ .mad-admin-content-->

			<?php echo $this->button_set(); ?>

			</form><!--/ #mad-options-page-->

			<?php return ob_get_clean();
		}

		function footer_holder() {
			global $mad_theme_data;
			$author = $mad_theme_data['author'];

			ob_start(); ?>

			<li class="mad-footer-holder clearfix">
				<span>
					<?php _e('Copyright &copy; 2015.', MAD_BASE_TEXTDOMAIN) ?>
						<a target="_blank" href="http://themeforest.net/user/mad_velikorodnov?WT.ac=item_profile_text&WT.z_author=mad_velikorodnov"><?php echo esc_html($author) ?></a>
					<?php _e('All rights reserved.', MAD_BASE_TEXTDOMAIN) ?>
				</span>
			</li><!--/ .mad-footer-holder-->

			<?php return ob_get_clean();
		}

		function button_set() {
			ob_start(); ?>

			<div class="mad-button-set">
				<ul class="mad-optional-links">
					<li><?php echo $this->reset_button(); ?></li>
					<?php echo $this->footer_holder(); ?>
					<li><?php echo $this->save_button(); ?></li>
				</ul><!--/ .mad-optional-links-->
			</div><!--/ .mad-button-set-->

			<?php return ob_get_clean();
		}

		function reset_button() {
			return '<a href="#" class="mad_button_reset mad-icon-reset">' . __('Reset Options', MAD_BASE_TEXTDOMAIN) . '</a>';
		}

		function save_button() {
			return '<a href="#" class="mad_button_save mad-icon-save">' . __('Save All Changes', MAD_BASE_TEXTDOMAIN) . '</a>';
		}

		function import($element) {
			ob_start(); ?>

			<div class="import-wrap">
				<a class="mad_button_import" <?php echo (isset($element['path'])) ? 'data-source="'. $element['source']. '"' : ''; ?> <?php echo (isset($element['path'])) ? 'data-path="'. $element['path']. '"' : ''; ?> href="#">

					<?php if (isset($element['image'])): ?>
						<img src="<?php echo MAD_BASE_URI . $element['image']; ?>" alt=""/>
					<?php endif; ?>

					<div class="import-overlay">
						<span class="overlay-button"><?php _e('Click import data', MAD_BASE_TEXTDOMAIN); ?></span>
					</div><!--/ .import-overlay-->

					<span class="import-started">
						<span class="import-loading"></span>
						<strong><?php _e('Starting import.', MAD_BASE_TEXTDOMAIN) ?></strong><br>
						<?php _e('Please dont reload the page. It may take a few minutes...', MAD_BASE_TEXTDOMAIN) ?>
					</span><!--/ .import-started-->

				</a>
			</div><!--/ .import-wrap-->

			<?php return ob_get_clean();
		}

		function export_config_file() {
			ob_start(); ?>

			<span class="export-wrap">
			<a href="<?php echo admin_url('admin.php?page=mad&theme_settings_export=1&generate_file=1'); ?>" class="button button-hero mad_button_export">
				<?php _e('Export Theme Settings', MAD_BASE_TEXTDOMAIN); ?>
			</a>
		</span><!--/ .export-wrap-->

			<?php return ob_get_clean();
		}

		function import_config_file() {
			ob_start(); ?>

			<?php $nonce_import_config = wp_create_nonce($this->actionImportConfigOptions); ?>
			<span class="export-wrap">
				<a href="javascript:void(0)" data-url="<?php echo admin_url("admin-ajax.php") ?>" data-_wpnonce="<?php echo $nonce_import_config ?>" data-action="<?php echo esc_attr($this->actionImportConfigOptions) ?>" class="button button-hero uploader-config-button">
					<?php _e('Import Theme Settings', MAD_BASE_TEXTDOMAIN); ?>
				</a>
			</span><!--/ .export-wrap-->

			<?php return ob_get_clean();
		}

		function container_start($page) {
			ob_start(); ?>

			<div class="sub-tab-content" id="to_<?php echo esc_attr($page['slug']); ?>">

			<div class="mad-sub-heading">
				<h4 class="mad-sub-heading-title"><?php echo esc_html($page['title']); ?></h4>
			</div><!--/ .mad-sub-heading-->

			<div class="admin-menu-link <?php echo esc_attr($page['class']); ?>" data-to="to_<?php echo esc_attr($page['slug']); ?>">
				<span><?php echo esc_html($page['title']); ?></span>
			</div><!--/ .admin-menu-link-->

			<?php return ob_get_clean();
		}

		function container_end() {
			return '</div><!--/ .sub-tab-content -->';
		}

		function tab_group_start($element) {
			$title = "";

			if (isset($element['name']) && $element['name'] != '') {
				$title = 'data-tab-title="'. $element['name'] .'"';
			}
			$output  = '<div '. $title .' class="mad-render-group mad_'. $element['type'] . ' ' . $element['class'] .' " id="'. $element['id'] .'">';
			return $output;
		}

		function tab_group_end() {
			return '</div><!--/ .mad-render-group-->';
		}

		function hidden_fields() {
			$nonce_reset =   wp_create_nonce($this->actionResetOptions);
			$nonce_save =  	 wp_create_nonce($this->actionSaveOptions);
			$nonce_import =  wp_create_nonce($this->actionImportOptions);

			ob_start(); ?>

			<div id="hidden_data" class="hidden_fields">
				<input type="hidden" name="options_page_slug" value="<?php echo esc_attr($this->globalObject->page_slug) ?>" />
				<input type="hidden" name="options_prefix" value="<?php echo esc_attr($this->globalObject->option_prefix) ?>" />
				<input type="hidden" name="admin_ajax_url" value="<?php echo admin_url("admin-ajax.php") ?>" />
				<input type="hidden" name="reset_action" value="<?php echo esc_attr($this->actionResetOptions) ?>" />
				<input type="hidden" name="save_action" value="<?php echo esc_attr($this->actionSaveOptions) ?>" />
				<input type="hidden" name="import_action" value="<?php echo esc_attr($this->actionImportOptions) ?>" />
				<input type="hidden" name="nonce-reset" value="<?php echo esc_attr($nonce_reset) ?>" />
				<input type="hidden" name="nonce-save" value="<?php echo esc_attr($nonce_save) ?>" />
				<input type="hidden" name="nonce-import" value="<?php echo esc_attr($nonce_import) ?>" />
			</div><!--/ #hidden_data-->

			<?php return ob_get_clean();
		}

		public function draw_page($pagepath, $data = array()) {
			@extract($data);
			ob_start();
			include $pagepath;
			return ob_get_clean();
		}

	}

}
