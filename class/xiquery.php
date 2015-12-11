<?php
    class XiQuery {

        public static function get_events_for_range($calendar_id, $start_date, $end_date) {
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
            $query = new WP_Query($args);
            return $query;
        }
    }
