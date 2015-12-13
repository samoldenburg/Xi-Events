# Xi-Events

Currently in development and non-functional, Xi Events intends to be a simple to use and flexible events system for Wordpress, without any extra frills or features that are not necessary for general events/calendar usage.

While the majority of classes in this plugin are object oriented, they are mostly static and used for namespace and organizational purposes.

# Required Version
Xi Events requires Wordpress 4.4 or higher. We make use of the brand new term_meta that was only just introduced in 4.4.

# Completed Features
* Basic Events Framework and Custom Fields
* Categories with Color Pickers
* Multiple calendar support.
* Google Maps Integration and automatic Geocoding Functionality
* [xi_event_details] Basic Shortcode Implementation (Will expand further in the future)
* [xi_calendar] Shortcode with available category filter. Renders out a calendar with events color coded by category (which is also configurable directly in wp-admin for each category). Example usage: [xi_calendar id="1" show_category_filter="false"]

# Planned Features
* Event Recurrence
* Google Maps and automatic Geocoding Functionality
* List View
* Calendar View
* Map View
* Shortcodes to display views
* Widgets for upcoming events and small calendars
* Fully customizable templates and an option to completely disable front end styles. Every single template, regardless of if its for a shortcode, widget, or general template output is 100% customizable, and override-able in themes or child plugins.
* Minimally invasive taxonomies to support custom taxonomy additions
* Multiple Calendar support
