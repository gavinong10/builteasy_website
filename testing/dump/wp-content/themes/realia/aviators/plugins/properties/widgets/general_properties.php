<?php

class GeneralProperties_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'GeneralProperties_Widget',
            __('Aviators: General Properties', 'aviators'),
            array(
                'classname' => 'properties-general',
                'description' => __('General Properties', 'aviators'),
            ));
    }

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        }
        else {
            $title = __('Properties', 'aviators');
        }

        if (isset($instance['count'])) {
            $count = $instance['count'];
        }
        else {
            $count = 3;
        }

        if (isset($instance['shuffle'])) {
            $shuffle = $instance['shuffle'];
        }
        else {
            $shuffle = FALSE;
        }

        $contract_types = get_terms(array('property_contracts'));
        $locations = get_terms(array('locations'));
        $types = get_terms(array('property_types'));

        $contract_type_values = isset($instance['property_contracts']) ? $instance['property_contracts'] : array();
        $location_values = isset($instance['locations']) ? $instance['locations'] : array();
        $property_type_values = isset($instance['property_types']) ? $instance['property_types'] : array();

        $display = isset($instance['display']) ? $instance['display'] : 'row';

        ?>

        <p>
            <input class="widefat" value="random" name="<?php echo $this->get_field_name('random'); ?> " type="checkbox" <?php checked($instance['random'], 'random'); ?>>
            <?php echo __('Select Random', 'aviators'); ?>
            <div class="description">
                <?php echo __('This option will override any other filtering criteria')?>
            </div>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('property_types'); ?>">
                <?php echo __('Filter by Property Type', 'aviators'); ?>
            </label>
        <table>
            <?php foreach ($types as $type): ?>
                <tr>
                    <th>
                        <input class="widefat" value="<?php print $type->term_id; ?>"
                               name="<?php echo $this->get_field_name('property_types') . '[' . $type->term_id . ']';; ?> "
                               type="checkbox"
                               <?php checked(in_array($type->term_id, $property_type_values)) ?>
                            >
                    </th>
                    <td>
                        <?php echo $type->name; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('property_contracts'); ?>">
                <?php echo __('Filter by Contract', 'aviators'); ?>
            </label>
        <table>
            <?php foreach ($contract_types as $contract_type): ?>
                <tr>
                    <th>
                        <input
                            name="<?php echo $this->get_field_name('property_contracts') . '[' . $contract_type->term_id . ']'; ?>"
                            type="checkbox" value="<?php print $contract_type->term_id; ?>"
                            <?php checked(in_array($contract_type->term_id, $contract_type_values)) ?>
                            >

                    </th>
                    <td>
                        <?php echo $contract_type->name; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('locations'); ?>">
                <?php echo __('Filter by Location', 'aviators'); ?>
            </label>
        <table>
            <?php foreach ($locations as $location): ?>
                <tr>
                    <th>
                        <input name="<?php echo $this->get_field_name('locations') . '[' . $location->term_id . ']'; ?>"
                               type="checkbox" value="<?php print $location->term_id; ?>"
                                <?php checked(in_array($location->term_id, $location_values)) ?>
                            >
                    </th>
                    <td>
                        <?php echo $location->name; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        </p>


        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'aviators'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php echo __('Count', 'aviators'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>"
                   name="<?php echo $this->get_field_name('count'); ?>" type="text"
                   value="<?php echo esc_attr($count); ?>"/>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('shuffle'); ?>"><?php echo __('Shuffle', 'aviators'); ?></label>
            <input type="checkbox" id="<?php echo $this->get_field_id('shuffle'); ?>"
                   name="<?php echo $this->get_field_name('shuffle'); ?>" value="1" <?php checked($shuffle); ?>>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('display'); ?>"><?php echo __('Display', 'aviators'); ?></label>
            <select type="checkbox" id="<?php echo $this->get_field_id('display'); ?>"
                    name="<?php echo $this->get_field_name('display'); ?>">
                <option value="row" <?php if($display == "row") echo "selected=selected" ?>><?php echo __('Row', 'aviators'); ?></option>
                <option value="grid" <?php if($display == "grid") echo "selected=selected" ?>><?php echo __('Grid', 'aviators'); ?></option>
                <option value="sidebar" <?php if($display == "sidebar") echo "selected=selected" ?>><?php echo __('Sidebar', 'aviators'); ?></option>
            </select>

        </p>
    <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['random'] = strip_tags($new_instance['random']);
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = strip_tags($new_instance['count']);
        $instance['shuffle'] = strip_tags($new_instance['shuffle']);
        $instance['display'] = strip_tags($new_instance['display']);

        $instance['property_types'] = $new_instance['property_types'];
        $instance['property_contracts'] = $new_instance['property_contracts'];
        $instance['locations'] = $new_instance['locations'];
        $instance['display'] = $new_instance['display'];
        return $instance;
    }

    public function widget($args, $instance) {
        extract($args);
        $count = isset($instance['count']) ? $instance['count'] : 3;
        $display = isset($instance['display']) ? $instance['display'] : 'sidebar';

        $query_args = array(
            'post_type' => 'property',
            'posts_per_page' => $count,
        );

        if($instance['random']) {
            $query_args['orderby'] = 'rand';
        } else {
            if (isset($instance['property_types'])) {
                $query_args['tax_query'][] =  array(
                    'taxonomy' => 'property_types',
                    'field' => 'id',
                    'operator' => 'IN',
                    'terms' => $instance['property_types'],
                );
            }

            if (isset($instance['locations'])) {
                $query_args['tax_query'][] =  array(
                    'taxonomy' => 'locations',
                    'field' => 'id',
                    'operator' => 'IN',
                    'terms' => $instance['locations'],
                );
            }

            if (isset($instance['property_contracts'])) {
                $query_args['tax_query'][] =  array(
                    'taxonomy' => 'property_contracts',
                    'field' => 'id',
                    'operator' => 'IN',
                    'terms' => $instance['property_contracts'],
                );
            }
        }

        if (!empty($instance['shuffle']) && $instance['shuffle']) {
            $args['orderby'] = 'rand';
        }

        $query = new WP_Query($query_args);
        $properties = _aviators_properties_prepare($query);

        echo View::render('properties/widget-general.twig', array(
            'title' => apply_filters('widget_title', $instance['title']),
            'count' => $count,
            'properties' => $properties,
            'display' => $display,
            'before_widget' => $before_widget,
            'after_widget' => $after_widget,
            'before_title' => $before_title,
            'after_title' => $after_title,
        ));
    }
}