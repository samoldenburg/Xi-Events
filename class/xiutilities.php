<?php
    // General utility functions
    class XiUtilities {

        public static function format_date_time($timestamp) {
            if (empty($timestamp))
                return 0;
            return date_i18n("Y-m-d H:i:s", $timestamp);
        }

        public static function format_date_time_gmt($timestamp) {
            if (empty($timestamp))
                return 0;
            return date_i18n("Y-m-d H:i:s", $timestamp, true);
        }

        // This is mostly useful for update_post_meta
        // see: https://codex.wordpress.org/Function_Reference/update_post_meta#Character_Escaping
        public static function json_encode($content) {
            return wp_slash(json_encode($content));
        }

        public static function json_decode($content) {
            return json_decode($content);
        }

        // Unless someone is using this plugin to enter 2500+ events per day, this will be sufficient. (API limits)
        public static function geocode_address($address) {
            $json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address));
            $decoded = json_decode($json);
            return $decoded->results[0];
        }

        // Set value based on content in database
        public static function set_value($meta_key) {
            global $post;
            $post_id = $post->ID;
            $value_in_db = get_post_meta($post_id, $meta_key, true);
            return ' value="' . $value_in_db . '"';
        }

        // Set checkbox (output checked="checked" if conditions met)
        // Assumes the global $post is available.
        public static function set_checkbox($meta_key, $value, $default = false) {
            global $post;
            $post_id = $post->ID;
            $value_in_db = get_post_meta($post_id, $meta_key, true);
            if ($value == $value_in_db)
                return ' checked="checked"';
            elseif (empty($value_in_db) && $default)
                return ' checked="checked"';
            else
                return '';
        }

        public static function set_select($meta_key, $value, $default = false) {
            global $post;
            $post_id = $post->ID;
            $value_in_db = get_post_meta($post_id, $meta_key, true);
            if ($value == $value_in_db)
                return ' selected="selected"';
            elseif (empty($value_in_db) && $default)
                return ' selected="selected"';
            else
                return '';
        }
    }
