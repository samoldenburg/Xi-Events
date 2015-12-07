<?php
    class XiError {

        public static function throw_error($err_code = 0) {
            add_filter('redirect_post_location', array('XiError', 'init_error'), 99);
        }

        public static function init_error($location) {
            remove_filter('redirect_post_location', array('XiError', 'init_error'), 99);
            return add_query_arg(array('xi_error' => 'true'), $location);
        }

        public static function init_display_errors() {
            if (!isset($_GET['xi_error']))
                return;
            ?>
                <div id="message" class="error notice is-dismissible below-h2">
                    <p>
                        Event not updated or published - start date is required.
                    </p>
                </div>
            <?php
        }
    }
