<?php
    class XiShortcode {

        /**
         * All calls to add_shortcode should be here.
         */
        public static function init() {
            add_shortcode('xi_event_details', array('XiShortcode', 'event_details'));
            add_shortcode('xi_calendar', array('XiShortcode', 'calendar'));
            add_shortcode('xi_events_list', array('XiShortcode', 'events_list'));
            add_shortcode('xi_event_list', array('XiShortcode', 'events_list'));
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
            global $xi_event_id, $xi_event_meta, $xi_shortcode_attributes;
            $xi_event_id = $atts['id'];
            $xi_event_meta = XiEventmeta::clean_meta(get_post_meta($xi_event_id));
            $xi_shortcode_attributes = $atts;
            $template = XiUtilities::get_include_template('shortcode_event-details.php');
            return $template;
        }

        /**
         * Show a calendar. Requires a calendar ID.
         */
        public static function calendar($atts) {
            $atts = shortcode_atts(
                array(
                    'id' => '',
                    'show_category_filter' => 'true'
                ),
                $atts,
                'xi_calendar'
            );

            if (empty($atts['id'])) {
                return "A calendar ID is required. Use [xi_calendar id=\"1\"].";
            }

            global $xi_calendar_id, $xi_shortcode_attributes;
            $xi_calendar_id = intval($atts['id']);
            $xi_shortcode_attributes = $atts;
            $template = XiUtilities::get_include_template('shortcode_event-calendar.php');
            return $template;
        }

        /**
         * Get a list of events, optionally filterable by category
         */
        public static function events_list($atts) {
            $atts = shortcode_atts(
                array(
                    'calendar_id' => '',
                    'category_id' => '',
                    'events_per_page' => 10,
                    'show_category_filter' => 'true'
                ),
                $atts,
                'xi_events_list'
            );

            $terms = array();

            $terms[XiEvents::$category_taxonomy_name] = array();
            $terms[XiEvents::$calendar_taxonomy_name] = array();

            if (!empty($atts['category_id'])) {
                $terms[XiEvents::$category_taxonomy_name] = explode(',', $atts['category_id']);
                $terms[XiEvents::$category_taxonomy_name] = array_map('trim', $terms[XiEvents::$category_taxonomy_name]);
            }

            if (!empty($atts['calendar_id'])) {
                $terms[XiEvents::$calendar_taxonomy_name] = explode(',', $atts['calendar_id']);
                $terms[XiEvents::$calendar_taxonomy_name] = array_map('trim', $terms[XiEvents::$calendar_taxonomy_name]);
            }

            global $xi_shortcode_attributes, $xi_events;
            $xi_shortcode_attributes = $atts;
            $xi_events = XiQuery::get_upcoming_events($atts['events_per_page'], $terms);
            $template = XiUtilities::get_include_template('shortcode_event-list.php');
            return $template;
        }
    }
