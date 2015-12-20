# Xi-Events

Currently in development and non-functional, Xi Events intends to be a simple to use and flexible events system for Wordpress, without any extra frills or features that are not necessary for general events/calendar usage.

Built with developers in mind, this plugin will ship with bare-bones at best styling, and in most cases no styling at all. This plugin is not intended to be used as a plug and play option with any theme, rather as a tool to assist with developing a beautiful front end interface for an events system.

The plugin will also ship with a comprehensive feature list, intended to hopefully reduce the need to code in custom features. That being said, every web project is unique and has its own needs, and thus this plugin intends to be easy to modify, not only from a templating and styling perspective, but to edit the code itself as well.

Once complete, a full list of filters and actions available will be published. Most simple modifications can be done in the theme/child theme/child plugin, but more invasive modifications may require modifying the plugin source, and as such is laid out in a way that encourages this.

While the majority of code in this plugin is object oriented, the class functions are mostly static and used for namespace and organizational purposes.

# Required Version
Xi Events requires Wordpress 4.4 or higher. We make use of the brand new term_meta that was only just introduced in 4.4.

# Completed Features
* Basic Events Framework and Custom Fields
* Categories with Color Pickers
* Multiple calendar support.
* Google Maps Integration and automatic Geocoding Functionality
* [xi_event_details] Basic Shortcode Implementation (Will expand further in the future)
* [xi_calendar] Shortcode with available category filter. Renders out a calendar with events color coded by category (which is also configurable directly in wp-admin for each category). Example usage: [xi_calendar id="1" show_category_filter="false"]
* Upcoming Events Widget - with flexible taxonomy support to allow for filtering by specific terms. All taxonomies registered to the xievents post type are automatically filterable with this widget.

# Planned Features
* Event Recurrence
* Map View
* Miniature calendar widget
