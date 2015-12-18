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


        /**
         * Switch function for rendering recurrence dates out.
         */
        public static function create_recurrence($post_id, $save_meta) {
            $recurrence_type = $save_meta['xi_event_recurrence'];

            // Remove any existing dates of they exist
            delete_post_meta($post_id, 'xi_recurrence_date');

            // Set up the absolute end - we need to make an assumption here or database space will be ruined!
            $max_date = !empty($save_meta['xi_recurrence_end']) ? $save_meta['xi_recurrence_end'] : date('m/d/Y', strtotime("+10 years"));

            // They can't even get here due to an error being thrown if start date isn't set, so we can just do this:
            $start_time = strtotime($save_meta['xi_event_start_date_raw']);

            switch ($recurrence_type) {
                case "daily":
                    $recurrence_dates = XiEventMeta::get_daily_recurrence_dates($save_meta, $start_time, $max_date);
                    break;
                case "weekly":
                    $recurrence_dates = XiEventMeta::get_weekly_recurrence_dates($save_meta, $start_time, $max_date);
                    break;
                case "monthly":
                    $recurrence_dates = XiEventMeta::get_monthly_recurrence_dates($save_meta, $start_time, $max_date);
                    break;
                case "yearly":
                    $recurrence_dates = XiEventMeta::get_yearly_recurrence_dates($save_meta, $start_time, $max_date);
                    break;
                case "custom":
                    $recurrence_dates = XiEventMeta::get_custom_recurrence_dates($save_meta, $start_time, $max_date);
                    break;
                default:
                    //none!
                    break;
            }

            // Now add them all
            foreach ($recurrence_dates as $date) {
                add_post_meta($post_id, 'xi_recurrence_date', $date);
            }
        }

        /**
         * Build out daily recurrence dates based on values in save_meta.
         */
        public static function get_daily_recurrence_dates($save_meta, $start_time, $max_date) {
            $exclude_dates = explode(",", $save_meta['xi_recurrence_exceptions']);

            $recurrence_dates = array();

            return XiEventMeta::convert_dates_to_query_friendly($recurrence_dates);
        }

        /**
         * Build out weekly recurrence dates based on values in save_meta.
         */
        public static function get_weekly_recurrence_dates($save_meta, $start_time, $max_date) {
            $exclude_dates = explode(",", $save_meta['xi_recurrence_exceptions']);
            $recurrence_dates = array();

            return XiEventMeta::convert_dates_to_query_friendly($recurrence_dates);
        }

        /**
         * Build out monthly recurrence dates based on values in save_meta.
         */
        public static function get_monthly_recurrence_dates($save_meta, $start_time, $max_date) {
            $exclude_dates = explode(",", $save_meta['xi_recurrence_exceptions']);
            $recurrence_dates = array();

            return XiEventMeta::convert_dates_to_query_friendly($recurrence_dates);
        }

        /**
         * Build out yearly recurrence dates based on values in save_meta.
         */
        public static function get_yearly_recurrence_dates($save_meta, $start_time, $max_date) {
            $exclude_dates = explode(",", $save_meta['xi_recurrence_exceptions']);
            $recurrence_dates = array();

            return XiEventMeta::convert_dates_to_query_friendly($recurrence_dates);
        }

        /**
         * Build out custom recurrence dates based on values in save_meta.
         * This is the easiest one of them all since they'll have to provide a list of days anyway.
         */
        public static function get_custom_recurrence_dates($save_meta, $start_time, $max_date) {
            $exclude_dates = explode(",", $save_meta['xi_recurrence_exceptions']);
            $custom_dates  = explode(",", $save_meta['xi_custom_recurrence_dates']);

            // Then just diff them..done!
            $recurrence_dates = array_diff($custom_dates, $exclude_dates);

            return XiEventMeta::convert_dates_to_query_friendly($recurrence_dates);
        }

        /**
         * Convert dates to query friendly versions (Y-m-d)
         */
        public static function convert_dates_to_query_friendly($dates) {
            $new_dates = array();
            foreach ($dates as $date) {
                $new_dates[] = date("Y-m-d", strtotime($date));
            }
            return $new_dates;
        }
    }
