<?php
function daroon2_get_bookly_services() {
    global $wpdb;
    
    // Query the Bookly services table directly since services are stored there, not as posts
    $services = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}bookly_services"
    );

    if (!$services) {
        return array(); // Return empty array if no services found
    }

    return $services;
}
?>