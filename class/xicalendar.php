<?php
    class XiEvents {

        public static $post_type_name = 'xievents';

        // Init hooks
        public static function init() {
            add_action('init', array('XiEvents', 'register_event_post_type'));
            add_action('admin_menu', array('XiEvents', 'add_settings_pages'));
            add_action('admin_menu', array('XiEvents', 'add_help_pages'));
            add_action('admin_print_styles', array('XiEvents', 'admin_styles'));
            add_action('admin_enqueue_scripts', array('XiEvents', 'admin_scripts'));
            add_action('admin_notices', array('XiError', 'init_display_errors'), 99);

            XiMetaboxes::init_meta_boxes();
        }

        public static function register_event_post_type() {
            $labels = array(
                'name'               => _x( 'Events', 'post type general name', 'xi-events' ),
                'singular_name'      => _x( 'Event', 'post type singular name', 'xi-events' ),
                'menu_name'          => _x( 'Events', 'admin menu', 'xi-events' ),
                'name_admin_bar'     => _x( 'Event', 'add new on admin bar', 'xi-events' ),
                'add_new'            => _x( 'Add New Event', '', 'xi-events' ),
                'add_new_item'       => __( 'Add New Event', 'xi-events' ),
                'new_item'           => __( 'New Event', 'xi-events' ),
                'edit_item'          => __( 'Edit Event', 'xi-events' ),
                'view_item'          => __( 'View Event', 'xi-events' ),
                'all_items'          => __( 'All Events', 'xi-events' ),
                'search_items'       => __( 'Search Events', 'xi-events' ),
                'parent_item_colon'  => __( 'Parent Events:', 'xi-events' ),
                'not_found'          => __( 'No Events found.', 'xi-events' ),
                'not_found_in_trash' => __( 'No Events found in Trash.', 'xi-events' )
            );

            $args = array(
                'labels'             => $labels,
                'description'        => __( 'Description.', 'xi-events' ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'event' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
            );
            register_post_type( self::$post_type_name, $args );
        }


        public static function add_settings_pages() {
            add_submenu_page('edit.php?post_type=' . self::$post_type_name, 'Xi Settings', 'Settings', 'manage_options', 'xisettings', array('XiSettings', 'main'));
        }

        public static function add_help_pages() {
            add_submenu_page('edit.php?post_type=' . self::$post_type_name, 'Xi Help', 'Help', 'manage_options', 'xihelp', array('XiHelp', 'main'));
        }

        public static function admin_styles() {
            wp_enqueue_style('jquery-ui-datepicker-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
            wp_enqueue_style('xi_admin_css', XI__PLUGIN_URL . 'assets/css/admin.css');
        }

        public static function admin_scripts() {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('xi_admin_js', XI__PLUGIN_URL . 'assets/js/admin.js');
        }
    }
?>
