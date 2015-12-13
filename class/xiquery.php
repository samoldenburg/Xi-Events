<?php
    class XiQuery {

        public static function get_events($calendar_id, $start_date, $end_date, $category_id = 0) {
            // First we should make sure this calendar_id exists.
            if (!term_exists($calendar_id, XiEvents::$calendar_taxonomy_name))
                return false;

            $args = array(
                'post_type'     => XiEvents::$post_type_name,
                'meta_query'    => array(
                    array(
                        'key'       => 'xi_event_start_query_friendly',
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
                    array(
                        'key'       => 'xi_event_start_query_friendly',
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
            asort($cats);
            return $cats;
        }
    }
