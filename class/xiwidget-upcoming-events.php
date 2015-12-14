<?php
    class XiWidget_Upcoming_Events extends WP_Widget {

        function __construct() {
            parent::__construct(
                'xievents_upcoming_events',
                'Upcoming Events',
                array( 'description' => 'Display a number of upcoming events in your widget area.')
            );
        }

        public function widget($args, $instance) {
            global $xi_widget_args, $xi_widget_instance, $upcoming_events;
            $xi_widget_args = $ags;
            $xi_widget_instance = $instance;
            
            if (!is_array($instance['terms']))
                $upcoming_events = XiQuery::get_upcoming_events($instance['num_events']);
            else
                $upcoming_events = XiQuery::get_upcoming_events($instance['num_events'], $instance['terms']);

            XiUtilities::include_template('widget_upcoming-events.php');
        }

        public function form($instance) {
            $taxonomies = get_object_taxonomies(XiEvents::$post_type_name, 'objects');
            include(XI__PLUGIN_DIR . '/admin/views/widget_upcoming-events-form.php');
        }

        public function update($new_instance, $old_instance) {
            $taxonomies = get_object_taxonomies(XiEvents::$post_type_name, 'objects');
            $instance = array();
            $instance['title'] = $_POST[$this->get_field_id('title')];
            $instance['num_events'] = intval($_POST[$this->get_field_id('num_events')]);
            $instance['terms'] = array();
            foreach ($taxonomies as $taxonomy => $object) {
                $instance['terms'][$taxonomy] = $_POST[$this->get_field_id($taxonomy)];
                $instance['terms'][$taxonomy]['filter'] = $_POST[$this->get_field_id('filter' . $taxonomy)];
            }

            return $instance;
        }
    }
