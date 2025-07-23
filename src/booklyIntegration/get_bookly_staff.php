<?php

function daroon2_get_bookly_staff()
{
    global $wpdb;
    $table = $wpdb->prefix . 'bookly_staff';

    // Pull id, first_name, last_name, and any other columns you need
    $query = "
        SELECT 
          id,
          full_name,
          email
        FROM $table
        ORDER BY position DESC
    ";

    $rows = $wpdb->get_results($query, ARRAY_A);

    return $rows;
}
?>