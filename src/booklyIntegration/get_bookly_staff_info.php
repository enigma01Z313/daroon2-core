<?php

function daroon2_get_bookly_staff_info($staff_id)
{
    if (empty($staff_id)) return "";

    global $wpdb;

    $staff_table = $wpdb->prefix . 'bookly_staff';
    $bookly_appointments_table = $wpdb->prefix . 'bookly_appointments';
    $bookly_customer_appointments_table = $wpdb->prefix . 'bookly_customer_appointments';

    $query = "  SELECT 
                    $staff_table.id,
                    SUM($bookly_appointments_table.end_date - $bookly_appointments_table.start_date) as total_duration,
                    COUNT($bookly_appointments_table.id) as total_appointments
                FROM $staff_table 
                LEFT JOIN $bookly_appointments_table 
                ON $staff_table.id = $bookly_appointments_table.staff_id
                LEFT JOIN $bookly_customer_appointments_table
                ON $bookly_appointments_table.id = $bookly_customer_appointments_table.appointment_id
                WHERE $staff_table.id = %d 
                AND $bookly_customer_appointments_table.status = 'approved' 
                AND $bookly_appointments_table.end_date < NOW()
                GROUP BY $staff_table.id";

    $result = $wpdb->get_results($wpdb->prepare($query, $staff_id), ARRAY_A);

    return $result[0];
}
?>