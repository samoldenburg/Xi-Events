<?php
    class XiAjax {

        public static function init() {
            add_action('wp_ajax_events_json', array('XiAjax', 'events_json'));
            add_action('wp_ajax_no_priv_events_json', array('XiAjax', 'events_json'));
        }

        /**
         * This is more or less proof of concept at this point. Needs to be significantly expanded upon.
         * TODO: Deal with multiple categorization
         * TODO: Other filters?
         * TODO: Nonce input for security and stuff.
         */
        public static function events_json() {
            global $xi_json_data;

            $calendar_id = intval($_POST['calendar_id']);
            $start_date = $_POST['start'];
            $end_date = $_POST['end'];

            $results = XiQuery::get_events_for_range($calendar_id, $start_date, $end_date);
            if ($results === false)
                die(json_encode(array()));

            $events = array();

            if ($results->have_posts()) {
                while ($results->have_posts()) {
                    $results->the_post();
                    $xi_event_id = get_the_ID();
                    $xi_event_meta = XiEventmeta::clean_meta(get_post_meta($xi_event_id));

                    // Set up the event information, conforms to requirements of fullcalendar
                    $event = new stdClass();
                    $event->id = $xi_event_id;
                    $event->title = get_the_title();
                    $event->allDay = boolval($xi_event_meta['xi_event_all_day']);
                    $event->start = date_i18n('c', strtotime($xi_event_meta['xi_event_start_raw']));
                    $event->end = date_i18n('c', strtotime($xi_event_meta['xi_event_end_raw']));
                    $event->url = get_the_permalink();

                    // The last two fields depend on categories set up. This needs more attention for filtering.
                    $categories = wp_get_post_terms($xi_event_id, XiEvents::$category_taxonomy_name);
                    if (count($categories) == 1) {
                        // Is there a more clever way to handle multiple categorization??
                        $event->className = 'category-' . $categories[0]->slug;
                        $event->color = get_term_meta($categories[0]->term_id, 'xi_category_color', true);
                    } else {
                        $event->className = 'uncategorized';
                        // TODO: color?
                    }
                    $events[] = $event;
                }
            }
            wp_reset_postdata();

            $xi_json_data = $events;
            include(XI__PLUGIN_DIR . '/ajax/json-endpoint.php');
            die();
        }
    }
