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
            $last_time = $start_time;

            while($last_time <= strtotime($max_date)) {
                $next_date = date("m/d/Y", strtotime("+1 day", $last_time));

                $last_time = strtotime($next_date);
                if (!in_array($next_date, $exclude_dates) && $last_time <= strtotime($max_date))
                    $recurrence_dates[] = $next_date;
            }

            return XiEventMeta::convert_dates_to_query_friendly($recurrence_dates);
        }

        /**
         * Build out weekly recurrence dates based on values in save_meta.
         * These are pretty straight forward, just loop through the week, calculate how many days to add at a time to
         * each previous date. date("N") is handy for this (and is required based on how the metaboxes are set up).
         */
        public static function get_weekly_recurrence_dates($save_meta, $start_time, $max_date) {
            $exclude_dates = explode(",", $save_meta['xi_recurrence_exceptions']);
            $weekly_days = json_decode(stripslashes($save_meta['xi_weekly_recurrence_days']));

            $recurrence_dates = array();
            $last_time = $start_time;

            while($last_time <= strtotime($max_date)) {
                $last_day_num = date("N", $last_time);
                $days_elapsed = 0;

                // This is safe because we validate that at least one date exists before this code can be reached.
                // At most it will loop 7 times.
                while (true) {
                    $last_day_num = ($last_day_num + 1) > 7 ? 1 : ($last_day_num + 1);
                    $days_elapsed++;
                    if (isset($weekly_days->{$last_day_num}))
                        break;
                }

                $next_date = date("m/d/Y", strtotime("+{$days_elapsed} days", $last_time));
                $last_time = strtotime($next_date);
                if (!in_array($next_date, $exclude_dates) && $last_time <= strtotime($max_date))
                    $recurrence_dates[] = $next_date;
            }

            return XiEventMeta::convert_dates_to_query_friendly($recurrence_dates);
        }

        /**
         * Build out monthly recurrence dates based on values in save_meta.
         * There are two types available here:
         *  Monthly By Date -
         *      The event falls on the same day every month (e.g. Jan 1, Feb 1, Mar 1, etc.)
         *  Monthly By Day of Week -
         *      The event falls on the same day of the week for a given week in the month (e.g. Third Friday every month)
         */
        public static function get_monthly_recurrence_dates($save_meta, $start_time, $max_date) {
            $exclude_dates = explode(",", $save_meta['xi_recurrence_exceptions']);
            $recurrence_dates = array();

            if ($save_meta['xi_recurrence_monthly_type'] == "date") {
                // Just repeat on +1 month
                $last_time = $start_time;

                while($last_time <= strtotime($max_date)) {
                    $next_date = date("m/d/Y", strtotime("+1 month", $last_time));

                    $last_time = strtotime($next_date);
                    if (!in_array($next_date, $exclude_dates) && $last_time <= strtotime($max_date))
                        $recurrence_dates[] = $next_date;
                }
            }
            else {
                // A little more complicated, but strtotime is magical...
                $last_time = $start_time;
                $weeknum = strtolower($save_meta['xi_recurrence_monthly_weeknum']);
                $weekday = $save_meta['xi_recurrence_monthly_weekday'];

                while($last_time <= strtotime($max_date)) {
                    $new_start_month = date('F Y', strtotime("next month", $last_time));
                    $next_date = date("m/d/Y", strtotime("$weeknum $weekday of $new_start_month"));

                    $last_time = strtotime($next_date);
                    if (!in_array($next_date, $exclude_dates) && $last_time <= strtotime($max_date))
                        $recurrence_dates[] = $next_date;
                }
            }

            return XiEventMeta::convert_dates_to_query_friendly($recurrence_dates);
        }

        /**
         * Build out yearly recurrence dates based on values in save_meta.
         * This works just like the previous function, just accounts for +1 year instead of +1 month.
         */
        public static function get_yearly_recurrence_dates($save_meta, $start_time, $max_date) {
            $exclude_dates = explode(",", $save_meta['xi_recurrence_exceptions']);
            $recurrence_dates = array();

            if ($save_meta['xi_recurrence_yearly_type'] == "date") {
                // Just repeat on +1 year
                $last_time = $start_time;

                while($last_time <= strtotime($max_date)) {
                    $next_date = date("m/d/Y", strtotime("+1 year", $last_time));

                    $last_time = strtotime($next_date);
                    if (!in_array($next_date, $exclude_dates) && $last_time <= strtotime($max_date))
                        $recurrence_dates[] = $next_date;
                }
            }
            else {
                // A little more complicated, but strtotime is magical...
                $last_time = $start_time;
                $weeknum = strtolower($save_meta['xi_recurrence_yearly_weeknum']);
                $weekday = $save_meta['xi_recurrence_yearly_weekday'];
                $month = $save_meta['xi_recurrence_yearly_month'];

                while($last_time <= strtotime($max_date)) {
                    $new_next_year = intval(date('Y', strtotime("+1 year", $last_time)));
                    $new_start_month = date('F Y', strtotime("$month $new_next_year"));
                    $next_date = date("m/d/Y", strtotime("$weeknum $weekday of $new_start_month"));

                    $last_time = strtotime($next_date);
                    if (!in_array($next_date, $exclude_dates) && $last_time <= strtotime($max_date))
                        $recurrence_dates[] = $next_date;
                }
            }

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
