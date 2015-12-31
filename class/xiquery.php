<?php
    class XiQuery {

        public static function get_events($calendar_id, $start_date, $end_date, $category_id = 0) {
            // First we should make sure this calendar_id exists.
            if (!term_exists($calendar_id, XiEvents::$calendar_taxonomy_name))
                return false;

            $args = array(
                'post_type'     => XiEvents::$post_type_name,
                'meta_query'    => array(
                    'relation'      => 'OR',
                    array(
                        'key'       => 'xi_event_start_query_friendly',
                        'value'     => array($start_date, $end_date),
                        'compare'   => 'BETWEEN',
                        'type'      => 'DATE'
                    ),
                    array(
                        'key'       => 'xi_recurrence_date',
                        'value'     => array($start_date, $end_date),
                        'compare'   => 'BETWEEN',
                        'type'      => 'DATE'
                    )
                ),
                'tax_query'     => array(
                    array(
                        'taxonomy'  => XiEvents::$calendar_taxonomy_name,
                        'terms'     => $calendar_id
                    )
                )
            );

            if ($category_id != 0 && !empty($category_id)) {
                $args['tax_query'][] = array(
                    'taxonomy'  => XiEvents::$category_taxonomy_name,
                    'terms'     => $category_id
                );
            }

            $query = new WP_Query($args);
            return $query;
        }

        public static function get_all_events_for_range($start_date, $end_date) {

            $args = array(
                'post_type'     => XiEvents::$post_type_name,
                'meta_query'    => array(
                    'relation'      => 'OR',
                    array(
                        'key'       => 'xi_event_start_query_friendly',
                        'value'     => array($start_date, $end_date),
                        'compare'   => 'BETWEEN',
                        'type'      => 'DATE'
                    ),
                    array(
                        'key'       => 'xi_recurrence_date',
                        'value'     => array($start_date, $end_date),
                        'compare'   => 'BETWEEN',
                        'type'      => 'DATE'
                    )
                )
            );
            $query = new WP_Query($args);
            return $query;
        }

        public static function get_categories_for_calendar($calendar_id) {
            // We should only show the categories for the current calendar that are being used.
            // So step one is to get all the events for the current category.
            // TODO: Is performance an issue with this? If so - leverage a direct database query, but for now this is a start.
            $args = array(
                'post_type'     => XiEvents::$post_type_name,
                'tax_query'     => array(
                    array(
                        'taxonomy'  => XiEvents::$calendar_taxonomy_name,
                        'terms'     => $calendar_id
                    )
                )
            );
            $query = new WP_Query($args);
            $cats = array();
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $xi_event_id = get_the_ID();
                    $categories = wp_get_post_terms($xi_event_id, XiEvents::$category_taxonomy_name);
                    foreach ($categories as $category) {
                        $cats[$category->term_id] = $category->name;
                    }
                }
            }
            wp_reset_postdata();
            asort($cats);
            return $cats;
        }

        public static function get_upcoming_events($limit = 3, $terms = false) {
            $args = array(
                'post_type'     => XiEvents::$post_type_name,
                'meta_key'      => 'xi_event_start_query_friendly',
                'meta_type'    => 'DATE',
                'orderby'       => 'meta_value title',
                'order'         => 'ASC',
                'posts_per_page' => $limit,
                'meta_query'    => array(
                    'relation'      => 'OR',
                    array(
                        'key'       => 'xi_event_start_query_friendly',
                        'value'     => date('Y-m-d'),
                        'compare'   => '>=',
                        'type'      => 'DATE'
                    ),
                    array(
                        'key'       => 'xi_recurrence_date',
                        'value'     => date('Y-m-d'),
                        'compare'   => '>=',
                        'type'      => 'DATE'
                    )
                ),
            );

            if ($terms !== false && is_array($terms)) {
                $args['tax_query'] = array();
                $args['tax_query']['relation'] = "AND";
                foreach ($terms as $taxonomy => $value) {
                    if ($value['filter'] == "1") {
                        unset($value['filtered']);
                        $args['tax_query'][] = array(
                            'taxonomy'  => $taxonomy,
                            'terms'     => $value,
                            'operator'  => "IN"
                        );
                    }
                }
            }

            $query = new WP_Query($args);
            $events = array();
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();

                    $event = new stdClass();
                    $event->ID = get_the_ID();
                    $event->permalink = get_the_permalink();
                    $event->title = get_the_title();

                    $start_timestamp = strtotime(get_post_meta($event->ID, 'xi_event_start_raw', true));
                    $start_timestamp = strtotime(get_post_meta($event->ID, 'xi_event_end_raw', true));
                    $date_format = apply_filters('xi_events_date_format', 'm/d/Y');
                    $event->date = date_i18n($date_format, $start_timestamp);
                    $event->start_timestamp = $start_timestamp;
                    $event->end_timestamp = $end_timestamp;

                    $events[] = $event;

                    $date1 = new DateTime(get_post_meta($event->ID, 'xi_event_start_query_friendly', true));
                    $date2 = new DateTime(get_post_meta($event->ID, 'xi_event_end_query_friendly', true));
                    $days_diff = $date2->diff($date1)->format("%a");


                    // List out the recurrence dates if they exist
                    $recurrence_dates = get_post_meta($event->ID, 'xi_recurrence_date', false);
                    foreach ($recurrence_dates as $recurrence_date) {
                        $recurrence_event = new stdClass();
                        $recurrence_event->ID = $event->ID;
                        $recurrence_event->title = $event->title;
                        $recurrence_event->start_timestamp = date_i18n('U', strtotime($recurrence_date));
                        if ($days_diff > 1) // dates can get buggy with strtotime("+0 days") for some reason.
                            $recurrence_event->end_timestamp = date_i18n('U', strtotime('+{$days_diff} days', strtotime($recurrence_date)));
                        else
                            $recurrence_event->end_timestamp = $recurrence_event->start_timestamp;
                        $recurrence_event->date = date_i18n($date_format, $recurrence_event->start_timestamp);
                        $recurrence_event->permalink = $event->permalink . ((parse_url($event->permalink, PHP_URL_QUERY) == NULL) ? '?' : '&') . 'instance=' . $recurrence_date;
                        $events[] = $recurrence_event;
                    }

                    // The events can be out of order and contain more events than desired now, so we need to filter down
                    $events = XiQuery::filter_events($events, $limit);
                }
            }
            wp_reset_postdata();
            return $events;
        }

        public static function filter_events($events, $limit, $time = '', $start_key = 0) {
            // First, lets sort them, this function is located in XI__PLUGIN_DIR/lib/functions.php
            usort($events, 'xi_date_cmp_sort');

            if ($start_key == 0) {
                if (empty($time))
                    $time = strtotime(date('m/d/Y'));
                else
                    $time = strtotime($time);


                // Then just return a slice of the events based on the limit.
                // get the start for the slice..
                foreach ($events as $key => $event) {
                    if (strtotime(date('m/d/Y', $event->end_timestamp)) > $time)
                        break;
                    $start_key = $key;
                }

                // this almost works, but start_key will be the end of the array regardless if the last event is past the
                // start time...
                if ($start_key == (sizeof($events) - 1) && end($events)->end_timestamp < $time)
                    $start_key++;

                return array_slice($events, $start_key, $limit);
            }
            else {
                return array_slice($events, $start_key, $limit);
            }
        }
    }
