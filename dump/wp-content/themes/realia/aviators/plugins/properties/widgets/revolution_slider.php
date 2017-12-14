<?php

class AviatorsRS_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'AviatorsRS_Widget',
            __('Aviators: Revolution Slider', 'aviators'),
            array(
                'classname' => 'revolution-slider-widget',
                'description' => __('revolution slider', 'aviators'),
            )
        );
    }

    public function form($instance) {

        if (isset($instance['show_filter'])) {
            $show_filter = $instance['show_filter'];
        }
        else {
            $show_filter = TRUE;
        }

        if (isset($instance['horizontal_filter'])) {
            $horizontal_filter = $instance['horizontal_filter'];
        }
        else {
            $horizontal_filter = FALSE;
        }
        $slider = new RevSlider();
        $arrSliders = $slider->getArrSlidersShort();

        $field = "revolution_slider";
        $fieldID = $this->get_field_id($field);
        $fieldName = $this->get_field_name($field);
        $sliderID = UniteFunctionsRev::getVal($instance, $field);
        $select = UniteFunctionsRev::getHTMLSelect(
            $arrSliders,
            $sliderID,
            'name="' . $fieldName . '" id="' . $fieldID . '"',
            TRUE
        );
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('revolution_slider'); ?>"><?php echo __(
                    'Revolution Slider',
                    'aviators'
                ); ?></label>
            <?php print $select; ?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show_filter'); ?>"><?php echo __(
                    'Show filter',
                    'aviators'
                ); ?></label>
            <input type="checkbox" id="<?php echo $this->get_field_id('show_filter'); ?>"
                   name="<?php echo $this->get_field_name('show_filter'); ?>" value="1" <?php checked($show_filter); ?>>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('horizontal_filter'); ?>"><?php echo __(
                    'Horizontal filter',
                    'aviators'
                ); ?></label>
            <input type="checkbox" id="<?php echo $this->get_field_id('horizontal_filter'); ?>"
                   name="<?php echo $this->get_field_name('horizontal_filter'); ?>" value="1" <?php checked(
                $horizontal_filter
            ); ?>>
        </p>
    <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['revolution_slider'] = strip_tags($new_instance['revolution_slider']);
        $instance['show_filter'] = strip_tags($new_instance['show_filter']);
        $instance['horizontal_filter'] = strip_tags($new_instance['horizontal_filter']);

        return $instance;
    }

    public function widget($args, $instance) {
        extract($args);
        $revolution_slider = UniteFunctionsRev::getVal($instance, "revolution_slider");

        $price_from = array();
        $price_from_parts = explode("\n", aviators_settings_get_value('properties', 'filter', 'from'));
        foreach ($price_from_parts as $price) {
            $price_from[] = trim($price);
        }

        $price_to = array();
        $price_to_parts = explode("\n", aviators_settings_get_value('properties', 'filter', 'to'));
        foreach ($price_to_parts as $price) {
            $price_to[] = trim($price);
        }
        echo View::render(
            'properties/widget-revolution-slider.twig',
            array(
                'revolution_slider' => $revolution_slider,
                'show_filter' => $instance['show_filter'],
                'price_from'    => $price_from,
                'price_to'      => $price_to,
                'horizontal_filter' => !empty($instance['horizontal_filter']) ? TRUE : FALSE,
                'before_widget' => $before_widget,
                'after_widget' => $after_widget,
                'before_title' => $before_title,
                'after_title' => $after_title,
            )
        );
    }
}