<?php
    class XiMetaboxes {

        public static function init() {
            add_action('edit_form_after_title', array('XiMetaboxes', 'register_meta_boxes'));
            add_action('save_post_' . XiEvents::$post_type_name, array('XiMetaboxes', 'save_event_meta'));
        }

        public static function register_meta_boxes() {
            add_meta_box(
                'xievents-eventmeta',
                'Event Details',
                array('XiMetaboxes', 'event_meta_callback'),
                XiEvents::$post_type_name,
                'normal',
                'high'
            );
        }

        public static function event_meta_callback($post) {
	        wp_nonce_field( 'save_event_meta', 'xi_event_meta_nonce' );

            include(XI__PLUGIN_DIR . '/admin/views/event_meta.php');
        }

        public static function save_event_meta($post_id) {

            if (!isset( $_POST['xi_event_meta_nonce'] ) || !wp_verify_nonce( $_POST['xi_event_meta_nonce'], 'save_event_meta'))
                return;

            // example of how we'll throw errors

            // Set up an associative array to save later (if validations check out)
            $full_start_time = $_POST['event_start_date'] . ' '
                . $_POST['event_start_time']['hours'] . ':'
                . $_POST['event_start_time']['minutes'] . ' '
                . $_POST['event_start_time']['ampm'];

            $full_end_time = $_POST['event_end_date'] . ' '
                . $_POST['event_end_time']['hours'] . ':'
                . $_POST['event_end_time']['minutes'] . ' '
                . $_POST['event_end_time']['ampm'];

            $full_address = $_POST['event_venue_address_1'] . ' '
                . $_POST['event_venue_address_2'] . ','
                . $_POST['event_venue_city'] . ' '
                . $_POST['event_venue_state'] . ','
                . $_POST['event_venue_postal_code'];

            $save_meta = array(
                'xi_event_all_day'              => intval($_POST['event_all_day']),

                'xi_event_start_date'           => XiUtilities::format_date_time(strtotime($_POST['event_start_date'])),
                'xi_event_start_date_raw'       => $_POST['event_start_date'],
                'xi_event_start_time'           => XiUtilities::json_encode($_POST['event_start_time']),
                'xi_event_start_formatted'      => XiUtilities::format_date_time(strtotime($full_start_time)),
                'xi_event_start_raw'            => $full_start_time,
                'xi_event_start_formatted_gmt'  => XiUtilities::format_date_time_gmt(strtotime($full_start_time)),
                'xi_event_start_query_friendly' => date_i18n('Y-m-d', strtotime($full_start_time)),

                'xi_event_start_time_hours'     => $_POST['event_start_time']['hours'],
                'xi_event_start_time_minutes'   => $_POST['event_start_time']['minutes'],
                'xi_event_start_time_ampm'      => $_POST['event_start_time']['ampm'],

                'xi_event_end_date'             => XiUtilities::format_date_time(strtotime($_POST['event_end_date'])),
                'xi_event_end_date_raw'         => $_POST['event_end_date'],
                'xi_event_end_time'             => XiUtilities::json_encode($_POST['event_end_time']),
                'xi_event_end_formatted'        => XiUtilities::format_date_time(strtotime($full_end_time)),
                'xi_event_end_raw'              => $full_end_time,
                'xi_event_end_formatted_gmt'    => XiUtilities::format_date_time_gmt(strtotime($full_end_time)),
                'xi_event_end_query_friendly'   => date_i18n('Y-m-d', strtotime($full_end_time)),

                'xi_event_end_time_hours'       => $_POST['event_end_time']['hours'],
                'xi_event_end_time_minutes'     => $_POST['event_end_time']['minutes'],
                'xi_event_end_time_ampm'        => $_POST['event_end_time']['ampm'],

                'xi_event_recurrence'           => $_POST['event_recurrence'],
                'xi_weekly_recurrence_days'     => XiUtilities::json_encode($_POST['weekly_recurrence_days']),
                'xi_recurrence_monthly_type'    => $_POST['recurrence_monthly_type'],
                'xi_recurrence_monthly_weeknum' => $_POST['recurrence_monthly_weeknum'],
                'xi_recurrence_monthly_weekday' => $_POST['recurrence_monthly_weekday'],
                'xi_recurrence_yearly_type'     => $_POST['recurrence_yearly_type'],
                'xi_recurrence_yearly_weeknum'  => $_POST['recurrence_yearly_weeknum'],
                'xi_recurrence_yearly_weekday'  => $_POST['recurrence_yearly_weekday'],
                'xi_recurrence_yearly_month'    => $_POST['recurrence_yearly_month'],
                'xi_custom_recurrence_dates'    => $_POST['custom_recurrence_dates'],
                'xi_recurrence_exceptions'      => $_POST['recurrence_exceptions'],
                'xi_recurrence_end'             => $_POST['recurrence_end'],                

                'xi_event_venue_name'           => $_POST['event_venue_name'],
                'xi_event_venue_address_1'      => $_POST['event_venue_address_1'],
                'xi_event_venue_address_2'      => $_POST['event_venue_address_2'],
                'xi_event_venue_city'           => $_POST['event_venue_city'],
                'xi_event_venue_state'          => $_POST['event_venue_state'],
                'xi_event_venue_country'        => $_POST['event_venue_country'],
                'xi_event_venue_postal_code'    => $_POST['event_venue_postal_code'],
                'xi_event_venue_phone'          => $_POST['event_venue_phone'],
                'xi_event_venue_website'        => $_POST['event_venue_website'],
                'xi_event_venue_google_map'     => $_POST['event_venue_google_map'],
            );

            // Only put in geocoded information if the event has an address
            if (!empty($_POST['event_venue_address_1'])) {
                $geocoded_information = XiUtilities::geocode_address($full_address);
                $geocoded_save_meta = array(
                    'xi_event_venue_formatted_address' => $geocoded_information->formatted_address,
                    'xi_evenut_venue_lat'              => $geocoded_information->geometry->location->lat,
                    'xi_evenut_venue_lng'              => $geocoded_information->geometry->location->lng,
                    'xi_event_venue_full_geocode'      => XiUtilities::json_encode($geocoded_information)
                );
                $save_meta = array_merge($save_meta, $geocoded_save_meta);
            }

            $valid = XiMetaboxes::validate_event($save_meta);

            // Save the contents of the array
            if ($valid === true) {
                foreach ($save_meta as $meta_key => $meta_value) {
                    update_post_meta($post_id, $meta_key, $meta_value);
                }
            } else {
                global $xi_error;
                $xi_error->throw_error($valid);
            }
        }

        public static function validate_event($save_meta) {
            if (empty($save_meta['xi_event_start_date_raw']))
                return 1;
            return true;
        }
    }
