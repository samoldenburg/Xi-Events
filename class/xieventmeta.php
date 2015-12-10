<?php
    class XiEventmeta {

        /**
         * Cleans the arrays for get_post_meta up if used without a key or the single bool set to true.
         */
        public static function clean_meta($meta) {
            $meta_holder = array();
            foreach ($meta as $key => $val) {
                $meta_holder[$key] = $val[0];
            }
            return $meta_holder;
        }

        /**
         * Considerations are taken here to deal with all day events, single day events, multiple day events, etc.
         * Its handy to have this in one function, but the templates do nothing to prevent developers from ignoring it.
         * Takes the event meta as a parameter to avoid unnecessary database requests.
         * @filter xi_events_date_format
         *     used to override the plugin's default date format.
         * @filter xi_events_time_format
         *     used to override the plugin's default time format.
         */
        public static function render_event_time($event_id, $event_meta) {
            $same_day_event = $event_meta['xi_event_start_raw'] == $event_meta['xi_event_end_raw'];
            $all_day_event = boolval($event_meta['xi_event_all_day']);

            $start_timestamp = strtotime($event_meta['xi_event_start_raw']);
            $end_timestamp = strtotime($event_meta['xi_event_end_raw']);

            $date_format = apply_filters('xi_events_date_format', 'm/d/Y');
            $time_format = apply_filters('xi_events_time_format', 'g:i:s a');

            // Lots of nested if statements below, could be a bit unsightly but its easy to read as is.
            // If this function needs to be expanded out, some consideration should be taken to change this.
            if ($same_day_event) {
                if ($all_day_event) {
                    $string = date_i18n($date_format, $start_timestamp);
                    return $string;
                } else {
                    $string = date_i18n($date_format . ", " . $time_format, $start_timestamp) . " - "
                            . date_i18n($time_format, $end_timestamp);
                    return $string;
                }
            } else {
                if ($all_day_event) {
                    $string = date_i18n($date_format, $start_timestamp) . " - "
                            . date_i18n($date_format, $end_timestamp);
                    return $string;
                } else {
                    $string = date_i18n($date_format . ", " . $time_format, $start_timestamp) . " - "
                            . date_i18n($date_format . ", " . $time_format, $end_timestamp);
                    return $string;
                }
            }
        }
    }
