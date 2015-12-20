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
                    array(
                        'key'       => 'xi_event_start_query_friendly',
                        'value'     => date_i18n('Y-m-d'),
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
                }
            }
            wp_reset_postdata();
            return $events;
        }
    }
