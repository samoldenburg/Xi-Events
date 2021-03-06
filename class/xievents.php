<?php
    class XiEvents {

        public static $post_type_name = 'xievents';
        public static $calendar_taxonomy_name = 'xicalendars';
        public static $category_taxonomy_name = 'xicategories';
        public static $category_taxonomy_slug = 'event-category';
        public static $tag_taxonomy_name = 'xitags';

        /**
         * Initial hooks
         * Add more hooks from other classes init functions. To me, a giant list of add_action/add_filter is not
         * an appealing way to organize a plugin. The hooks here are the general hooks that handle the base
         * functionality of the plugin, such as setting up post types, taxonomies, admin menus, etc.
         */
        public static function init() {
            global $xi_error;

            add_action('init', array('XiEvents', 'register_event_post_type'));
            add_action('init', array('XiEvents', 'register_taxonomies'));
            add_action('admin_menu', array('XiEvents', 'add_settings_pages'));
            add_action('admin_menu', array('XiEvents', 'add_help_pages'));

            add_action('admin_print_styles', array('XiEvents', 'admin_styles'));
            add_action('admin_enqueue_scripts', array('XiEvents', 'admin_scripts'));

            add_action('wp_enqueue_scripts', array('XiEvents', 'frontend_assets'));

            add_action('admin_notices', array($xi_error, 'init_display_errors'), 99);

            add_filter('the_content', array('XiEvents', 'apply_event_information'));

            XiMetaboxes::init();
            XiCategorymeta::init();
            XiShortcode::init();
            XiWidget::init();
            XiAjax::init();
        }

        public static function plugin_activation_hook() {
            if (!current_user_can('activate_plugins'))
                return;
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }

        public static function plugin_deactivation_hook() {
            if (!current_user_can('activate_plugins'))
                return;
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }

        /**
         * @filter xi_post_type_labels - Override the display labels
         * @filter xi_post_type_arguments - Override the register_post_type arguments.
         */
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
            $labels = apply_filters('xi_post_type_labels', $labels);

            $args = array(
                'labels'             => $labels,
                'description'        => __( 'Description.', 'xi-events' ),
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'events' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => null,
                'menu_icon'          => 'dashicons-calendar',
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
            );
            $args = apply_filters('xi_post_type_arguments', $args);
            register_post_type( self::$post_type_name, $args );
        }

        /**
         * @filter xi_enable_tag_taxonomy - Defaults to true, enable or disable tag taxonomy.
         *     for now the other two taxonomies are required, they may be configurable in the future
         *     but tags are minimally invasive to begin with
         * @filter xi_calendar_taxonomy_labels - Override the display labels
         * @filter xi_category_taxonomy_labels - Override the display labels
         * @filter xi_tag_taxonomy_labels - Override the display labels
         * @filter xi_calendar_taxonomy_arguments - Override the register_taxonomy arguments.
         * @filter xi_category_taxonomy_arguments - Override the register_taxonomy arguments.
         * @filter xi_tag_taxonomy_arguments - Override the register_taxonomy arguments.
         */
        public static function register_taxonomies() {
            $labels = array(
        	    'name'              => _x( 'Calendars', 'taxonomy general name' ),
        		'singular_name'     => _x( 'Calendar', 'taxonomy singular name' ),
        		'search_items'      => __( 'Search Calendars' ),
        		'all_items'         => __( 'All Calendars' ),
        		'parent_item'       => __( 'Parent Calendar' ),
        		'parent_item_colon' => __( 'Parent Calendar:' ),
        		'edit_item'         => __( 'Edit Calendar' ),
        		'update_item'       => __( 'Update Calendar' ),
        		'add_new_item'      => __( 'Add New Calendar' ),
        		'new_item_name'     => __( 'New Calendar Name' ),
        		'menu_name'         => __( 'Calendars' ),
        	);
            $labels = apply_filters('xi_calendar_taxonomy_labels', $labels);

        	$args = array(
        		'hierarchical'      => true,
        		'labels'            => $labels,
        		'show_ui'           => true,
        		'show_admin_column' => true,
        		'query_var'         => true,
        		'rewrite'           => array( 'slug' => 'calendar' ),
        	);
            $args = apply_filters('xi_calendar_taxonomy_arguments', $args);
        	register_taxonomy( self::$calendar_taxonomy_name, array( self::$post_type_name ), $args );

            $labels = array(
        	    'name'              => _x( 'Event Categories', 'taxonomy general name' ),
        		'singular_name'     => _x( 'Event Category', 'taxonomy singular name' ),
        		'search_items'      => __( 'Search Event Categories' ),
        		'all_items'         => __( 'All Event Categories' ),
        		'parent_item'       => __( 'Parent Event Category' ),
        		'parent_item_colon' => __( 'Parent Event Category:' ),
        		'edit_item'         => __( 'Edit Event Category' ),
        		'update_item'       => __( 'Update Event Category' ),
        		'add_new_item'      => __( 'Add New Event Category' ),
        		'new_item_name'     => __( 'New Event Category Name' ),
        		'menu_name'         => __( 'Event Categories' ),
        	);
            $labels = apply_filters('xi_category_taxonomy_labels', $labels);

        	$args = array(
        		'hierarchical'      => true,
        		'labels'            => $labels,
        		'show_ui'           => true,
        		'show_admin_column' => true,
        		'rewrite'           => array( 'slug' => self::$category_taxonomy_slug ),
        	);
            $args = apply_filters('xi_category_taxonomy_arguments', $args);
        	register_taxonomy( self::$category_taxonomy_name, array( self::$post_type_name ), $args );

            $xi_enable_tag_taxonomy = apply_filters('xi_enable_tag_taxonomy', true);

            if ($xi_enable_tag_taxonomy) {
                $labels = array(
            	    'name'              => _x( 'Event Tags', 'taxonomy general name' ),
            		'singular_name'     => _x( 'Event Tag', 'taxonomy singular name' ),
            		'search_items'      => __( 'Search Event Tags' ),
            		'all_items'         => __( 'All Event Tags' ),
            		'parent_item'       => __( 'Parent Event Tag' ),
            		'parent_item_colon' => __( 'Parent Event Tag:' ),
            		'edit_item'         => __( 'Edit Event Tag' ),
            		'update_item'       => __( 'Update Event Tag' ),
            		'add_new_item'      => __( 'Add New Event Tag' ),
            		'new_item_name'     => __( 'New Event Tag Name' ),
            		'menu_name'         => __( 'Event Tags' ),
            	);
                $labels = apply_filters('xi_post_type_labels', $labels);

            	$args = array(
            		'hierarchical'      => false,
            		'labels'            => $labels,
            		'show_ui'           => true,
            		'show_admin_column' => true,
            		'rewrite'           => array( 'slug' => 'event-tag' ),
            	);
                $args = apply_filters('xi_tag_taxonomy_labels', $args);
            	register_taxonomy( self::$tag_taxonomy_name, array( self::$post_type_name ), $args );
            }
        }


        public static function add_settings_pages() {
            add_submenu_page('edit.php?post_type=' . self::$post_type_name, 'Xi Settings', 'Settings', 'manage_options', 'xisettings', array('XiSettings', 'main'));
        }

        public static function add_help_pages() {
            add_submenu_page('edit.php?post_type=' . self::$post_type_name, 'Xi Help', 'Help', 'manage_options', 'xihelp', array('XiHelp', 'main'));
        }

        public static function admin_styles() {
            wp_enqueue_style('jquery-ui-datepicker-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style('xi_admin_css', XI__PLUGIN_URL . 'assets/css/admin.css');
        }

        public static function admin_scripts() {
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('xi_admin_js', XI__PLUGIN_URL . 'assets/js/admin.js');
        }

        public static function frontend_assets() {
            wp_enqueue_script('jquery');
            wp_enqueue_style('fullcalendar', XI__PLUGIN_URL . 'vendor/fullcalendar-2.5.0/fullcalendar.min.css');
            wp_enqueue_script('moment', XI__PLUGIN_URL . 'vendor/moment/moment.min.js');
            wp_enqueue_script('fullcalendar', XI__PLUGIN_URL . 'vendor/fullcalendar-2.5.0/fullcalendar.min.js');
            wp_enqueue_script('xi_frontend_javascript', XI__PLUGIN_URL . 'assets/js/frontend.js');
            wp_enqueue_style('xi_frontend_css', XI__PLUGIN_URL . 'assets/css/frontend.css');
        }

        /**
         * @filter xi_enable_apply_event_information -
         *     Disable this whole function by returning false with your callback function.
         * @filter xi_single_event_template -
         *     Override how the event meta is displayed.
         *     By default will invoke the [xi_event_details] shortcode
         * @filter xi_single_event_prepend -
         *     Control whether the event meta is prepended or appended to the post content for the single view.
         *     Default is prepend.
         */
        public static function apply_event_information($content) {
            global $post;

            $xi_enable_apply_event_information = apply_filters('xi_enable_apply_event_information', true);

            if ($xi_enable_apply_event_information && is_singular(self::$post_type_name)) {
                $single_event_content = apply_filters('xi_single_event_template', '[xi_event_details]');
                $single_event_prepend = apply_filters('xi_single_event_prepend', true);

                if ($single_event_prepend) {
                    return $single_event_content . $content;
                } else {
                    return $content . $single_event_content;
                }
            } else {
                return $content;
            }
        }
    }
?>
