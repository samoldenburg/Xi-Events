<?php
    class XiMetaboxes {

        public static function init_meta_boxes() {
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
            #XiError::throw_error();

            $event_all_day = $_POST['event_all_day'];
            update_post_meta($post_id, 'xi_event_all_day', $event_all_day);

            $event_start_date = $_POST['event_start_date'];
            update_post_meta($post_id, 'xi_event_start_date', $event_start_date);

            $event_start_time = json_encode($_POST['event_start_time']);
            update_post_meta($post_id, 'xi_event_start', $event_start_time);
        }
    }
