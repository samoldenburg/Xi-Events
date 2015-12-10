<?php
    class XiCategorymeta {

        public static function init() {
            add_action('xicategories_add_form_fields', array('XiCategorymeta', 'add_meta_fields'));
            add_action('xicategories_edit_form_fields', array('XiCategorymeta', 'add_meta_fields'));

            add_action('edited_' . XiEvents::$category_taxonomy_name, array('XiCategorymeta', 'save_category_meta'));
            add_action('create_' . XiEvents::$category_taxonomy_name, array('XiCategorymeta', 'save_category_meta'));

            // See comments below. TODO: Implement.
            //add_filter('manage_edit-' . XiEvents::$category_taxonomy_name . '_columns', array('XiCategorymeta', 'category_edit_columns'));
            //add_action('manage_' . XiEvents::$category_taxonomy_name . '_custom_column', array('XiCategorymeta', 'category_custom_columns'));
        }

        public static function add_meta_fields() {
	        wp_nonce_field( 'save_category_meta', 'xi_category_meta_nonce' );

            include(XI__PLUGIN_DIR . '/admin/views/category_meta.php');
        }

        public static function save_category_meta($term_id) {
            if (!isset( $_POST['xi_category_meta_nonce'] ) || !wp_verify_nonce( $_POST['xi_category_meta_nonce'], 'save_category_meta'))
                return;

            update_term_meta($term_id, 'xi_category_color', $_POST['xi_category_color']);
        }

        public static function category_edit_columns($columns) {
            // For reasons unknown the category_custom_columns function below is missing arg 2/3, which renders this useless for now.
            // Bug in 4.4?
            // TODO: Implement.
            //$columns['xicolor'] = "Color";
            return $columns;
        }

        public static function category_custom_columns($content, $column_name, $term_id) {
            switch ($column_name) {
                case "xicolor":
                    echo get_term_meta($term_id, 'xi_category_color');
                    break;
            }
        }
    }
