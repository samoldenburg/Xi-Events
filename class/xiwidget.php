<?php
    class XiWidget {

        public static function init() {
            add_action('widgets_init', array('XiWidget', 'register_widgets'));
        }

        public static function register_widgets() {
            register_widget( 'XiWidget_Upcoming_Events' );            
        }
    }
