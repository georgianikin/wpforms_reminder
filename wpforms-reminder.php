<?php
/*
 * Plugin Name: WPForms Reminder
 * Description: Send automatic email reminders for wpforms start dates.
 * Version: 1.0
 * Author: Georgi Anikin
 * Plugin URI: https://github.com/georgianikin/wpforms_reminder
 * Text Domain: wpforms-reminder
 * Domain Path: /languages
 */
    
// Schedule the cron job when the plugin is activated
register_activation_hook(__FILE__, 'wpf_reminder_activate');
function wpf_reminder_activate() {
    wp_schedule_event(time(), 'daily', 'cr_send_reminders');
}

// Unschedule the cron job when the plugin is deactivated
register_deactivation_hook(__FILE__, 'wpf_reminder_deactivate');
function wpf_reminder_deactivate() {
    wp_clear_scheduled_hook('cr_send_reminders');
}

// Hook to run the reminder function
add_action('cr_send_reminders', 'wpf_reminder_send_reminders_function');

include(plugin_dir_path(__FILE__) . 'send_mail.php');


// Function to add a menu in the admin panel
function wpforms_reminder_menu() {
    add_menu_page(
        __('WPForms Reminder', 'wpforms-reminder'),       // Page title
        __('WPForms Reminder', 'wpforms-reminder'),       // Menu title
        'manage_options',                               // capability
        'wpforms-reminder-settings',                     // Menu slug
        'wpforms_reminder_settings',                     // Menu callback
        'dashicons-clock',                              // Icon
        30                                              // Position
        );
}
add_action('admin_menu', 'wpforms_reminder_menu');

// Function to display the content of the settings page
function wpforms_reminder_settings() {
    include(plugin_dir_path(__FILE__) . 'list.php');
}

// Function to save the template content and 'days_before'
function wpforms_reminder_save() {
    include(plugin_dir_path(__FILE__) . 'save.php');
}

add_action('admin_init', 'wpforms_reminder_save');

add_action( 'plugins_loaded', function(){
    load_plugin_textdomain( 'wpforms-reminder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

// Function to delete a template
function course_reminder_delete_template() {
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $template_id = intval($_GET['id']);
        
        // Verify nonce
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'wpforms-reminder-delete-template-' . $template_id)) {
            die('Security check');
        }
        
        // Delete the template
        wp_delete_post($template_id, true);
        
        // Redirect back to the templates list
        wp_safe_redirect(admin_url('admin.php?page=wpforms-reminder-settings'));
        exit;
    }
}
add_action('admin_init', 'course_reminder_delete_template');

// Function to initialize the plugin
function wpforms_reminder_init() {
    // Register custom post type for email templates
    register_post_type('wpforms_reminder', array(
        'labels' => array(
            'name' => 'Reminder templates',
            'singular_name' => 'Reminder template',
        ),
        'public' => false,
        'show_ui' => false,
        'capability_type' => 'post',
        'supports' => array('title', 'editor'),
        'rewrite' => false,
    ));
}
add_action('init', 'wpforms_reminder_init');
