<?php
    /*
     * Class meant to gracefully handle and display errors without the need for sloppy javascript validation within admin areas.
     * Due to Wordpress needing to perform a redirect after save_post, we need to hook into the redirect_post_location filter
     * and append a query argument for which error code we'd like to display. To keep URLs clean, this class will numerically
     * reference error codes. The alternative would be to append a string to the URL with the error message, but in my opinion,
     * this would look sloppy from a UX perspective.
     *
     * global $xi_error; is available to everything if this plugin is activated.
     * throw errors with $xi_error->throw_error($error_code);
     *
     * Throwing an error from here will NOT prevent a post from being updated.
     */
    class XiError {

        private $ERR_CODE = 0;

        // Add filter to redirect post location, update error code if it exists.
        public function throw_error($code = false) {
            if ($code !== false)
                $this->ERR_CODE = $code;
            add_filter('redirect_post_location', array($this, 'init_error'), 99);
        }

        // Hook function for throw_error's add_filter call. Add the error code to the URL at this time.
        public function init_error($location) {
            remove_filter('redirect_post_location', array($this, 'init_error'), 99);
            return add_query_arg(array('xi_error' => $this->ERR_CODE), $location);
        }

        // This is hooked into in XiEvents::init();
        // Basic processor to display errors or not, set up an error message, and call the HTML render.
        public function init_display_errors() {
            if (!isset($_GET['xi_error']))
                return;

            $this->ERR_CODE = intval($_GET['xi_error']);

            switch ($this->ERR_CODE) {
                case 1:
                    $message = "Event not saved - Start Date is required.";
                    break;
                default:
                    $message = "Something went wrong but I don't have an assigned ERR_CODE to tell you more information. You should yell at the plugin author for this, he'll be terribly embarassed.";
            }

            $this->show_error($message);
        }

        // Render the error code HTML. Hide #message if it exists.
        private function show_error($message) {
            ?>
                <div class="error notice">
                    <p>
                        <?=$message;?>
                    </p>
                </div>
                <style>#message{display:none!important;}</style>
            <?php
        }
    }
