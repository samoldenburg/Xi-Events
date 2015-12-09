<?php
    class XiShortcode {

        public static function init() {
            add_shortcode('xi_event_details', array('XiShortcode', 'event_details'));
        }

        /**
         * Render out the event details. Can be used anywhere, but by default is prepended to the single event post type
         */
        public static function event_details($atts) {
            global $post;
            $atts = shortcode_atts(
                array(
                    'id' => $post->ID
                ),
                $atts,
                'xi_event_details'
            );
            // $xi_event_id is the ID of the event invoking the shortcode
            global $xi_event_id, $xi_event_meta;
            $xi_event_id = $atts['id'];
            $xi_event_meta = XiEventmeta::clean_meta(get_post_meta($xi_event_id));
            $template = XiUtilities::get_include_template('shortcode_event-details.php');
            return $template;
        }
    }
