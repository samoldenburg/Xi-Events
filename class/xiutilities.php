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

        /**
         * This is mostly useful for update_post_meta
         * @see https://codex.wordpress.org/Function_Reference/update_post_meta#Character_Escaping
         */
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

        /**
         * Set value based on content in database
         */
        public static function set_value($meta_key) {
            global $post;
            $post_id = $post->ID;
            $value_in_db = get_post_meta($post_id, $meta_key, true);
            return ' value="' . $value_in_db . '"';
        }

        /**
         * Set checkbox (output checked="checked" if conditions met)
         * Assumes the global $post is available.
         */
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

        /**
         * Set select (output selected="selected" if conditions met)
         * Assumes the global $post is availalbe.
         */
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

        /**
         * Load a template.
         * @source https://codex.wordpress.org/Function_Reference/load_template
         */
        public static function load_template($template_name) {
            if ($overridden_template = locate_template($template_name)) {
                // locate_template() returns path to file
                // if either the child theme or the parent theme have overridden the template
                load_template($overridden_template);
            } else {
                // If neither the child nor parent theme have overridden the template,
                // we load the template from the 'templates' sub-directory of the directory this file is in
                $template_name = XiUtilities::parse_template_location($template_name);
                load_template(XI__PLUGIN_DIR . '/templates/' . $template_name);
            }
        }

        /**
         * Include a template
         * Slightly modified version of load_template. It may be better to do this if variables are needed.
         * load_template will only have a set of wordpress globals available.
         * @see http://wordpress.stackexchange.com/a/112464
         */
        public static function include_template($template_name) {
            if ($overridden_template = locate_template($template_name)) {
                // locate_template() returns path to file
                // if either the child theme or the parent theme have overridden the template
                include($overridden_template);
            } else {
                // If neither the child nor parent theme have overridden the template,
                // we load the template from the 'templates' sub-directory of the directory this file is in
                $template_name = XiUtilities::parse_template_location($template_name);
                include(XI__PLUGIN_DIR . '/templates/' . $template_name);
            }
        }

        /**
         * Include a template and return its contents. Useful for shortcodes.
         * Slightly modified version of load_template. Makes use of output buffering.
         * Needless to say, this cannot be used if headers have already been sent.
         */
        public static function get_include_template($template_name) {
            ob_start();
            if ($overridden_template = locate_template($template_name)) {
                // locate_template() returns path to file
                // if either the child theme or the parent theme have overridden the template
                include($overridden_template);
            } else {
                // If neither the child nor parent theme have overridden the template,
                // we load the template from the 'templates' sub-directory of the directory this file is in
                $template_name = XiUtilities::parse_template_location($template_name);
                include(XI__PLUGIN_DIR . '/templates/' . $template_name);
            }
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        }

        /**
         * The templates folder here makes use of some organization to keep things clean.
         * Templates are named based on their purpose, for example
         * shortcode_template.php will exist in XI__PLUGIN_DIR/templates/shortcodes/template.php
         * This function allows for the above theme overrides to work as expected, yet still keeps this plugin nicely
         * organized. All available folders are:
         *  shortcode
         *  widget
         */
        public static function parse_template_location($template) {
            if (strpos($template, '_') !== false) {
                $template = explode("_", $template)[0] . 's/' . $template;
            }
            return $template;
        }
    }
