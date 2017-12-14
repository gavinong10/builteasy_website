<?php
/**
 * NOO Customizer Package.
 *
 * Register Customized Controls
 * This file register customized control used in NOO-Customizer
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

function noo_customizer_add_custom_controls( $wp_customize ) {

	class NOO_Customizer_Sub_Section extends WP_Customize_Control {
		public $type = 'noo-customizer-sub-section';
		public function render_content() {
			?>
			<h4 class="noo-sub-section-heading"><?php echo esc_html( $this->label ); ?></h4>
			<?php if ( isset( $this->json['description'] ) && !empty( $this->json['description'] ) ) : ?>
			<p class="noo-sub-section-desc"><?php echo ($this->json['description']); ?></p>
			<?php
			endif;
		}
	}

	//
	// Convert checkbox button to switch
	// And include function for controlling child options.
	//
	class NOO_Customizer_Control_Checkbox extends WP_Customize_Control {
		public $type = 'checkbox';
		public $data_on = 'ON';
		public $data_off = 'OFF';

		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );

			if ( isset( $args['data_on'] ) && !empty( $args['data_on'] ) ) {
				$this->data_on = $args['data_on'];
			} else {
				$this->data_on = __( 'ON', 'noo' );
			}
			
			if ( isset( $args['data_off'] ) && !empty( $args['data_off'] ) ) {
				$this->data_off = $args['data_off'];
			} else {
				$this->data_off = __( 'OFF', 'noo' );
			}
		}

		public function render_content() {
		?>
		<label>
			<input id="noo-checkbox-<?php echo esc_attr($this->id); ?>" type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
			<strong><?php echo esc_html( $this->label ); ?></strong>
		</label>
		<?php // Add script if there's child-options setting.
			$this->_get_inline_script();
		}

		function _get_inline_script() {
			$on_child_options = null;
			$off_child_options = null;
			if ( isset( $this->json['on_child_options'] )
				&& !empty( $this->json['on_child_options'] ) ) {
				$on_child_options = $this->json['on_child_options'];
				$on_child_options = explode( ',', $on_child_options );
			}

			if ( isset( $this->json['off_child_options'] )
				&& !empty( $this->json['off_child_options'] ) ) {
				$off_child_options = $this->json['off_child_options'];
				$off_child_options = explode( ',', $off_child_options );
			}

			if ( !empty( $on_child_options ) || !empty( $off_child_options ) ) :
			?>
			<script>
			jQuery(window).load(function() {
				<?php
				if ( !empty( $on_child_options ) ) :
					foreach ( $on_child_options as $on_option ) :
						if ( trim( $on_option ) == '' ) continue;
				?>
				jQuery('#customize-control-<?php echo esc_attr(trim( $on_option )); ?>').addClass('child_<?php echo esc_attr($this->id); ?> <?php echo esc_attr($this->id); ?>_val_on');
				<?php
					endforeach;
				endif;
				?>
				<?php
				if ( !empty( $off_child_options ) ) :
					foreach ( $off_child_options as $off_option ) :
						if ( trim( $off_option ) == '' ) continue;
				?>
				jQuery('#customize-control-<?php echo esc_attr(trim( $off_option )); ?>').addClass('child_<?php echo esc_attr($this->id); ?> <?php echo esc_attr($this->id); ?>_val_off');
				<?php
					endforeach;
				endif;
				?>
				var customize_control    = jQuery('#customize-control-<?php echo esc_attr($this->id); ?>');

				// Bind the toggle event, we use this event to enable unlimitedly chained child toggle.
				customize_control.bind("toggle_children", function() {
					$this = jQuery(this);
					if($this.hasClass('hide-option')) {
						jQuery('.child_<?php echo esc_attr($this->id); ?>').addClass("hide-option").trigger("toggle_children");

						return;
					}

					var checkbox    = $this.find("input");
					if(checkbox.is( ':checked' )) {
						jQuery('.<?php echo esc_attr($this->id); ?>_val_off').addClass("hide-option").trigger("toggle_children");
						jQuery('.<?php echo esc_attr($this->id); ?>_val_on').removeClass("hide-option").trigger("toggle_children");
					} else {
						jQuery('.<?php echo esc_attr($this->id); ?>_val_on').addClass("hide-option").trigger("toggle_children");
						jQuery('.<?php echo esc_attr($this->id); ?>_val_off').removeClass("hide-option").trigger("toggle_children");
					}
				});

				// Trigger toggle event the first time
				customize_control.trigger("toggle_children");

				// Trigger the toggle event when there's click
				customize_control.find('input').click( function() {
					customize_control.trigger("toggle_children");
				});
			});
			</script>
			<?php
			endif;
		}
	}

	//
	// Enhance the radio with switch button effect.
	//
	class NOO_Customizer_Control_Switch extends NOO_Customizer_Control_Checkbox {
		public $type = 'noo-switch';

		public function render_content() {
		?>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<div class="noo-switch">
			<input id="noo-switch-<?php echo esc_attr($this->id); ?>" type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
			<label for="noo-switch-<?php echo esc_attr($this->id); ?>" data-on="<?php echo esc_attr($this->data_on); ?>" data-off="<?php echo esc_attr($this->data_off); ?>"></label>
		</div>
		<?php // Add script if there's child-options setting.
			$this->_get_inline_script();
		}
	}

	//
	// Create the slider option for better experience when selecting number.
	//
	class NOO_Customizer_Control_UI_Slider extends WP_Customize_Control {
		public $type = 'ui_slider';
		public function render_content() {
			$data_min  = ( isset( $this->json['data_min'] ) && !empty( $this->json['data_min'] ) ) ? 'data-min="' . $this->json['data_min'] . '"': 'data-min="0"';
			$data_max  = ( isset( $this->json['data_max'] ) && !empty( $this->json['data_max'] ) ) ? 'data-max="' . $this->json['data_max'] . '"': 'data-max="100"';
			$data_step = ( isset( $this->json['data_step'] ) && !empty( $this->json['data_step'] ) ) ? 'data-step="' . $this->json['data_step'] . '"': 'data-step="1"';
			$html   = array();
			?>
			<label class="noo-control">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="text" <?php $this->link(); ?> class="noo-slider" value="<?php echo esc_textarea( $this->value() ); ?>" <?php echo ( $data_min . ' ' . $data_max . ' ' . $data_step ); ?>/>
			</label>
			<?php
		}
	}

	//
	// Extended Radio button of WP Customizer included function for controlling child options.
	//
	class NOO_Customizer_Control_Radio extends WP_Customize_Control {
		public $type = 'radio';
		public function render_content() {
			if ( empty( $this->choices ) )
				return;

			$name = '_customize-radio-' . $this->id;

		?>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php
			foreach ( $this->choices as $value => $label ) :
		?>
			<label>
				<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
				<?php echo esc_html( $label ); ?><br/>
			</label>
		<?php
			endforeach;

			// Add script if there's child-options setting.
			if ( isset( $this->json['child_options'] )
				&& !empty( $this->json['child_options'] )
				&& is_array( $this->json['child_options'] ) ) :

				$child_options = $this->json['child_options'];
			$refined_child_options  = array();
			foreach ( $this->choices as $value => $label ) {
				if ( isset( $child_options[$value] ) && !empty( $child_options[$value] ) ) {
					$refined_child_options[$value] = explode( ',', $child_options[$value] );
				}
			}

			if ( !empty( $refined_child_options ) ) :
		?>
        <script>
          jQuery(window).load(function() {
            <?php
				foreach ( $refined_child_options as $option_value => $options ) :
					if ( empty( $options ) || !is_array( $options ) ) continue;
					foreach ( $options as $child_option ) :
						if ( trim( $child_option ) == "" ) continue;
			?>
                jQuery('#customize-control-<?php echo trim( $child_option ); ?>').addClass('child_<?php echo esc_attr($this->id); ?> <?php echo esc_attr($this->id); ?>_val_<?php echo esc_attr($option_value); ?>');
                <?php
			endforeach;
			endforeach;
		?>
			var customize_control    = jQuery('#customize-control-<?php echo esc_attr($this->id); ?>');

            // Bind the toggle event, we use this event to enable unlimitedly chained child toggle.
            customize_control.bind("toggle_children", function() {
            	$this = jQuery(this);
            	if($this.hasClass("hide-option")) {
            		jQuery('.child_<?php echo esc_attr($this->id); ?>').addClass("hide-option").trigger("toggle_children");

	            	return;
            	}

            	var checkedElement = $this.find('input:checked');
            	jQuery('.child_<?php echo esc_attr($this->id); ?>:not(.<?php echo esc_attr($this->id); ?>_val_' + checkedElement.val() + ')')
            		.addClass("hide-option")
            		.trigger("toggle_children");
            	jQuery('.<?php echo esc_attr($this->id); ?>_val_' + checkedElement.val())
            		.removeClass("hide-option")
            		.trigger("toggle_children");
            });

            // Trigger toggle event the first time
            customize_control.trigger("toggle_children");

            // Trigger the toggle event when there's click
            customize_control.find('input').click( function() {
            	customize_control.trigger("toggle_children");
            });
          });
        </script>
        <?php endif;
			endif;
		}
	}

	//
	// Add the checkbox to the first input so that all the radio could become the "same as" option.
	//
	class NOO_Customizer_Control_Same_As_Radio extends WP_Customize_Control {
		public $type = 'radio';
		public function render_content() {
			if ( empty( $this->choices ) )
				return;

			$name = '_customize-radio-' . $this->id;

		?>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php
        		reset($this->choices);
        		$same_as_value = key($this->choices);
        		$same_as_label = current($this->choices);
        		unset($this->choices[$same_as_value]);
        		?>
        	<label>
	            <input type="checkbox" value="<?php echo esc_attr( $same_as_value ); ?>" name="<?php echo esc_attr( $name ); ?>-checkbox" <?php if($this->value() == $same_as_value) { echo ' checked="checked"'; } ?> />
	            <?php echo esc_html( $same_as_label ); ?><br/>
	        </label>
	        <?php
			foreach ( $this->choices as $value => $label ) :
		?>
          <label <?php if($same_as_value == $this->value()) echo ' class="hide-option"';?>>
            <input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>-radio" <?php if($this->value() != $same_as_value) { checked( $this->value(), $value ); } ?> />
            <?php echo esc_html( $label ); ?><br/>
          </label>
          <input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?>>
          <?php
			endforeach;

			// Add script if there's child-options setting.
			if ( isset( $this->json['child_options'] )
				&& !empty( $this->json['child_options'] )
				&& is_array( $this->json['child_options'] ) ) :

				$child_options = $this->json['child_options'];
				$refined_child_options  = array();
				foreach ( $this->choices as $value => $label ) {
					if ( isset( $child_options[$value] ) && !empty( $child_options[$value] ) ) {
						$refined_child_options[$value] = explode( ',', $child_options[$value] );
					}
				}

				if ( !empty( $refined_child_options ) ) :
		?>
        <script>
          jQuery(window).load(function() {
            <?php
				foreach ( $refined_child_options as $option_value => $options ) :
					if ( empty( $options ) || !is_array( $options ) ) continue;
					foreach ( $options as $child_option ) :
						if ( trim( $child_option ) == "" ) continue;
		?>
                jQuery('#customize-control-<?php echo trim( $child_option ); ?>').addClass('child_<?php echo esc_attr($this->id); ?> <?php echo esc_attr($this->id); ?>_val_<?php echo esc_attr($option_value); ?>');
                <?php
			endforeach;
			endforeach;
		?>
			var customize_control    = jQuery('#customize-control-<?php echo esc_attr($this->id); ?>');

            // Bind the toggle event, we use this event to enable unlimitedly chained child toggle.
            customize_control.bind("toggle_children", function() {
            	$this = jQuery(this);
            	if($this.hasClass("hide-option")) {
            		jQuery('.child_<?php echo esc_attr($this->id); ?>').addClass("hide-option").trigger("toggle_children");

	            	return;
            	}

            	var checkedElement = $this.find('input[type=radio]:checked');
            	jQuery('.child_<?php echo esc_attr($this->id); ?>:not(.<?php echo esc_attr($this->id); ?>_val_' + checkedElement.val() + ')')
            		.addClass("hide-option")
            		.trigger("toggle_children");
            	jQuery('.<?php echo esc_attr($this->id); ?>_val_' + checkedElement.val())
            		.removeClass("hide-option")
            		.trigger("toggle_children");
            });

            // Trigger toggle event the first time
            customize_control.trigger("toggle_children");

            // Trigger the toggle event when there's click
            customize_control.find('input[type=checkbox]').click( function() {
            	var $checkbox    = jQuery(this);
            	if($checkbox.is( ':checked' )) {
            		customize_control.find('input[type=radio]').each(function() {
            			$radio = jQuery(this);
            			$radio.removeAttr('checked');
            			$radio.parent().addClass("hide-option");
            		});
            		customize_control.find('input[type=hidden]').val($checkbox.val()).change();
            	} else {
            		customize_control.find('input[type=radio]').each(function() {
            			$radio = jQuery(this);
            			$radio.attr('name', '<?php echo esc_html($name); ?>')
            				.attr('data-customize-setting-link', '<?php echo esc_attr($this->id); ?>');
            			$radio.parent().removeClass("hide-option");
            		});
            		var $first_radio = customize_control.find('input[type=radio]').first();
            		$first_radio.attr('checked', 'checked');
            		customize_control.find('input[type=hidden]').val($first_radio.val()).change();
            	}

            	customize_control.trigger("toggle_children");
            });

            // Trigger the toggle event when there's click
            customize_control.find('input[type=radio]').click( function() {
            	customize_control.find('input[type=hidden]').val(jQuery(this).val()).change();
            	customize_control.trigger("toggle_children");
            });
          });
        </script>
        <?php endif;
			endif;
		}
	}

	class NOO_Customize_Alpha_Color extends WP_Customize_Control {

	    public $type = 'color';
	    public $palette = 'true';
	    public $default = '';

	    protected function render() {
	        $id = 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) );
	        $class = 'customize-control customize-control-' . $this->type; ?>
	        <li id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $class ); ?>">
	            <?php $this->render_content(); ?>
	        </li>
	    <?php }

	    public function render_content() { ?>
	        <label>
	            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	            <input type="text" data-palette="<?php echo esc_attr($this->palette); ?>" data-default-color="<?php echo esc_attr($this->default); ?>" value="<?php echo intval( $this->value() ); ?>" class="noo-color-control" <?php $this->link(); ?>  />
	        </label>
	    <?php }
	}

	class NOO_Customize_Image_Control extends WP_Customize_Image_Control {
		public $type = 'image';

		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );

			$this->remove_tab( 'upload-new' );
			$this->remove_tab( 'uploaded' );
			$this->add_tab( 'library',  __( 'Media Library', 'noo' ), array( $this, 'media_library' ) );

			$this->get_url = array( $this, 'get_attachment_image' );
		}

		public function media_library() {
			?>
				<a class="choose-from-library-link button" data-controller="<?php echo esc_attr($this->id); ?>">
					<?php _e( 'Open Library', 'noo' ); ?>
				</a>
			<?php
		}

		public function get_attachment_image( $id ) {
			if( is_numeric($id) ) {
				$image_src = wp_get_attachment_image_src( $id );
				if( is_array( $image_src ) ) {
					return $image_src[0];
				}
			}

			return $id;
		}
	}

	/**
	 * Import Customize Setting File
	 */
	class NOO_Customize_Settings_Upload extends WP_Customize_Control {
		public $type    = 'noo-upload';

		public function render_content() {
			?>
			<label>
				<!-- Placeholder -->
				<a href="#" class="button-primary" onclick="jQuery('#noo-customizer-settings-upload').trigger('click');"><?php echo esc_html( $this->label ); ?></a>
				<!--  -->
			</label>
			<?php
		}
	}

	/**
	 * Export Customize Setting File
	 */
	class NOO_Customize_Settings_Download extends WP_Customize_Control {
		public $type    = 'noo-download';

		public function render_content() {
			?>
			<label>
				<!-- Placeholder -->
				<a id="noo-customizer-settings-download" href="#" class="button-primary" ><?php echo esc_html( $this->label ); ?></a>
				<!--  -->
			</label>
			<?php
		}
	}

	class NOO_Customizer_Control_Textarea extends WP_Customize_Control {
		public $type = 'textarea';
		public function render_content() {
			$size = isset($this->json['size']) ? $this->json['size'] : 'normal';
			$row = 10;
			if($size == 'small') {
				$row = 5;
			} elseif ($size == 'big') {
				$row = 20;
			}
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea <?php $this->link(); ?> rows="<?php echo esc_attr($row); ?>" style="width: 98%;"><?php echo esc_attr($this->value()); ?></textarea>
			</label>
			<?php
		}
	}

	class NOO_Customizer_Control_Multiple_Select extends WP_Customize_Control {
		public $type = 'multiple-select';
		public function render_content() {
		?>
      <label>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <select <?php $this->link(); ?> multiple="multiple" style="height: 156px;">
          <?php
			foreach ( $this->choices as $value => $label ) {
				$selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
				echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
			}
		?>
        </select>
      </label>
      <?php
		}
	}

	class NOO_Customizer_Control_Divider extends WP_Customize_Control {
		public $type = 'divider';
		public function render_content() {
			echo '<hr/>';
		}
	}

	//
	// Widgets Area Select List.
	//
	class NOO_Customizer_Control_Widgets_Select extends WP_Customize_Control {
		private static $widget_list = array();
		public $type = 'noo-widgets-select';

		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			if ( empty( self::$widget_list ) ) {
				self::$widget_list = smk_get_all_sidebars();
			}

			parent::__construct( $manager, $id, $args );
		}

		public function render_content() {
			if ( empty( self::$widget_list ) )
				return;
		?>
      <label>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <select <?php $this->link(); ?>>
          <option value=""><?php echo __( '- Select Sidebar -', 'noo' ); ?></option>
          <?php
			foreach ( self::$widget_list as $value => $label )
				echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
		?>
        </select>
      </label>
      <?php
		}
	}

	//
	// Pages Select.
	//
	class NOO_Customizer_Control_Pages_Select extends WP_Customize_Control {
		public $type = 'noo-pages-select';

		public function render_content() {
			$dropdown = wp_dropdown_pages(
				array(
					'name'              => '_customize-dropdown-pages-' . $this->id,
					'echo'              => 0,
					'show_option_none'  => ' ',
					'option_none_value' => '',
					'selected'          => $this->value(),
				)
			);

			// Hackily add in the data link parameter.
			$dropdown = str_replace( '<select', '<select ' . $this->get_link() . 'class="noo-customize-chosen" data-placeholder="' . __( '- Select Page -', 'noo' ) . '"', $dropdown );
		?>
      <label>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php echo $dropdown; ?>
      </label>
      <?php
		}
	}

	//
	// Posts Select.
	//
	class NOO_Customizer_Control_Posts_Select extends WP_Customize_Control {
		public $type = 'noo-posts-select';

		public function render_content() {
			$post_type = isset( $this->json['post_type'] ) && !empty( $this->json['post_type'] ) ? $this->json['post_type'] : 'post';
			$select_holder = isset( $this->json['select_holder'] ) && !empty( $this->json['select_holder'] ) ? $this->json['select_holder'] : '';
			$post_list = get_terms( array('post_type' => $post_type) );
		?>
		<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php echo $this->get_link(); ?>>
				<option value="" <?php selected('', $this->value()); ?>><?php echo esc_html($select_holder); ?></option>
					<?php foreach ($post_list as $post): ?>
					<option value="<?php echo esc_attr($post->ID); ?>" <?php selected($post->ID, $this->value()); ?>><?php echo esc_html($post->post_title); ?></option>
				<?php endforeach; ?>
				?>
			</select>
		</label>
      <?php
		}
	}

	//
	// Terms Select.
	//
	class NOO_Customizer_Control_Terms_Select extends WP_Customize_Control {
		public $type = 'noo-terms-select';

		public function render_content() {
			$term = isset( $this->json['term'] ) && !empty( $this->json['term'] ) ? $this->json['term'] : 'category';
			$select_holder = isset( $this->json['select_holder'] ) && !empty( $this->json['select_holder'] ) ? $this->json['select_holder'] : '';
			$terms = get_terms( $term );
		?>
		<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php echo $this->get_link(); ?>>
				<option value="" <?php selected('', $this->value()); ?>><?php echo esc_html($select_holder); ?></option>
					<?php foreach ($terms as $item): ?>
					<option value="<?php echo esc_attr($item->term_id); ?>" <?php selected($item->term_id, $this->value()); ?>><?php echo esc_html($item->name); ?></option>
				<?php endforeach; ?>
				?>
			</select>
		</label>
      <?php
		}
	}

	/**
	 * Select List for all Google fonts
	 */
	class NOO_Customizer_Control_Google_Fonts extends WP_Customize_Control {
		public static $fonts = array();
		public $type = 'google-fonts';

		private $weight = '';
		private $style  = '';
		private $subset = '';

		private static $font_weight_name = array();

		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			if ( empty( self::$fonts ) ) {
				self::$fonts = $this->get_fonts( 'all' );
			}

			parent::__construct( $manager, $id, $args );

			// Get selected style variants
			$this->weight = $this->manager->get_setting( $id.'_weight' )->value();
			$this->weight = empty( $this->weight ) ? noo_default_font_weight() : $this->weight;

			$this->style  = $this->manager->get_setting( $id.'_style' )->value();

			// Get selected subset
			$this->subset = $this->manager->get_setting( $id.'_subset' )->value();
			$this->subset = empty( $this->subset ) ? 'latin' : $this->subset;

			if ( empty ( self::$font_weight_name ) )
				self::$font_weight_name = array(
					'100'       => __( 'Extra Light', 'noo' ),
					'100italic' => __( 'Extra Light Italic', 'noo' ),
					'200'       => __( 'Light', 'noo' ),
					'200italic' => __( 'Light Italic', 'noo' ),
					'300'       => __( 'Book', 'noo' ),
					'300italic' => __( 'Book Italic', 'noo' ),
					'400'       => __( 'Regular', 'noo' ),
					'400italic' => __( 'Regular Italic', 'noo' ),
					'500'       => __( 'Medium', 'noo' ),
					'500italic' => __( 'Medium Italic', 'noo' ),
					'600'       => __( 'Semi-bold', 'noo' ),
					'600italic' => __( 'Semi-bold Italic', 'noo' ),
					'700'       => __( 'Bold', 'noo' ),
					'700italic' => __( 'Bold Italic', 'noo' ),
					'800'       => __( 'Extra-bold', 'noo' ),
					'800italic' => __( 'Extra-bold Italic', 'noo' ),
					'900'       => __( 'Heavy', 'noo' ),
					'900italic' => __( 'Heavy Italic', 'noo' ),
				);
		}

		public function render_content() {
			if ( !empty( self::$fonts ) ) {
		?>
          <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
          <div class="noo-font-selects">
            <select class="noo-font-family noo-customize-chosen" data-placeholder="<?php _e( '- Font Family -', 'noo' ); ?>" <?php $this->link(); ?>>
            <option value=""></option>
              <?php
				$selected_font_id = 0;
				foreach ( self::$fonts as $k => $v ) {
					printf( '<option value="%s" %s data-style="%s" data-subset="%s">%s</option>', $v->family, selected( $this->value(), $v->family, false ), implode( ',', $v->variants ), implode( ',', $v->subsets ), $v->family );
					if ( $this->value() == $v->family )
						$selected_font_id = $k;
				}
		?>
            </select>
            <select class="noo-font-weight-and-style">
              <option value=""><?php echo __( '- Styles -', 'noo' ); ?></option>
              <?php
				$variants = isset( self::$fonts[$selected_font_id] ) && isset( self::$fonts[$selected_font_id]->variants )
					? self::$fonts[$selected_font_id]->variants : array();
				foreach ( self::$font_weight_name as $v => $font_weight ) {
					$hide = in_array( $v, $variants ) ? '' : 'class="hidden"';
					printf( '<option value="%s" %s %s>%s</option>', $v, selected( $this->weight . $this->style, $v, false ), $hide, $font_weight );
				}
				// if ( isset( self::$fonts[$selected_font_id] ) && isset( self::$fonts[$selected_font_id]->variants ) ) {
				//  foreach ( self::$fonts[$selected_font_id]->variants as $v ) {
				//   printf( '<option value="%s" %s>%s</option>', $v, selected( $this->weight . $this->style, $v, false ), self::$font_weight_name[$v] );
				//  }
				// }
		?>
            </select>
            <select class="noo-font-subset" data-customize-setting-link="<?php echo esc_attr($this->id) . '_subset'; ?>">
              <option value=""><?php echo __( '- Subsets -', 'noo' ); ?></option>
              <?php
				if ( isset( self::$fonts[$selected_font_id] ) && isset( self::$fonts[$selected_font_id]->subsets ) ) {
					foreach (self::$fonts[$selected_font_id]->subsets as $v ) {
						printf( '<option value="%s" %s>%s</option>', $v, selected( $this->subset, $v, false ), $v );
					}
				}
		?>
            </select>
            <input type="hidden" class="noo-font-weight" value="<?php echo esc_attr($this->weight); ?>" data-customize-setting-link="<?php echo esc_attr($this->id) . '_weight'; ?>">
            <input type="hidden" class="noo-font-style" value="<?php echo esc_attr($this->style); ?>" data-customize-setting-link="<?php echo esc_attr($this->id) . '_style'; ?>">
          </div>
        <?php
			}
		}

		// Get the Google fonts from data file
		public function get_fonts( $amount = 30 ) {
			$fontFile = dirname( __FILE__ ) . '/data/google-web-fonts.txt';
			$content = json_decode( file_get_contents( $fontFile ) );

			if ( empty( $content ) || !isset( $content->items ) ) {
				return array();
			}

			if ( $amount == 'all' ) {
				return apply_filters( 'noo_google_font_list', $content->items );
			} else {
				return apply_filters( 'noo_google_font_list', array_slice( $content->items, 0, $amount ) );
			}
		}
	}

	class NOO_Customizer_Control_Font_Size extends WP_Customize_Control {
		public $type = 'select';

		private static $size_list = array();

		public function __construct( $manager, $id, $args = array(), $options = array() ) {
			parent::__construct( $manager, $id, $args );

			if ( empty ( self::$size_list ) )
				self::$size_list = array(
					'9'        => '9px',
					'10'       => '10px',
					'11'       => '11px',
					'12'       => '12px',
					'13'       => '13px',
					'14'       => '14px',
					'15'       => '15px',
					'16'       => '16px',
					'17'       => '17px',
					'18'       => '18px',
					'19'       => '19px',
					'20'       => '20px',
					'21'       => '21px',
					'22'       => '22px',
					'23'       => '23px',
					'24'       => '24px',
					'25'       => '25px',
					'26'       => '26px',
					'27'       => '27px',
					'28'       => '28px',
					'29'       => '29px',
					'30'       => '30px',
				);
		}

		public function render_content() {
			if ( empty( $this->choices ) )
				$this->choices = self::$size_list;

		?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<select <?php $this->link(); ?>>
					<?php
			foreach ( $this->choices as $value => $label )
				echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
		?>
				</select>
			</label>
			<?php
		}
	}
}

add_action( 'customize_register', 'noo_customizer_add_custom_controls' );
