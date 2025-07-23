<?php

function daroon2_get_staff_nearest_available_time($staff_id)
{
    global $wpdb;

    // Get current datetime in WordPress timezone
    $current_time = current_time('Y-m-d H:i:s');
    $current_date = current_time('Y-m-d');

    // Get staff schedule and appointments tables
    $schedule_table = $wpdb->prefix . 'bookly_staff_schedule_items';
    $appointments_table = $wpdb->prefix . 'bookly_appointments';

    // Look for slots in the next 30 days
    $max_days_to_check = 30;
    $checked_days = 0;

    while ($checked_days < $max_days_to_check) {
        // Calculate the date we're checking
        $check_date = date('Y-m-d', strtotime($current_date . " +{$checked_days} days"));
        $day_of_week = date('N', strtotime($check_date)); // 1-7, 1 = Monday

        // Get staff schedule for this day
        $schedule_query = $wpdb->prepare(
            "SELECT * FROM $schedule_table 
            WHERE staff_id = %d 
            AND day_index = %d 
            AND start_time IS NOT NULL",
            $staff_id,
            $day_of_week
        );

        $schedule = $wpdb->get_row($schedule_query);

        // Skip if staff doesn't work on this day
        if (!$schedule) {
            $checked_days++;
            continue;
        }

        // Get all appointments for this day
        $appointments_query = $wpdb->prepare(
            "SELECT start_date, end_date 
            FROM $appointments_table 
            WHERE staff_id = %d 
            AND DATE(start_date) = %s
            ORDER BY start_date ASC",
            $staff_id,
            $check_date
        );

        $appointments = $wpdb->get_results($appointments_query);

        // Create DateTime objects for schedule
        $schedule_start = new DateTime($check_date . ' ' . $schedule->start_time);
        $schedule_end = new DateTime($check_date . ' ' . $schedule->end_time);
        $now = new DateTime($current_time);

        // For today, start looking from current time
        // For future days, start from schedule start time
        $start_looking_from = ($checked_days === 0 && $now > $schedule_start) ? $now : $schedule_start;

        // Skip if we're checking today and it's already past end time
        if ($checked_days === 0 && $now > $schedule_end) {
            $checked_days++;
            continue;
        }

        // If no appointments on this day, return the start time
        if (empty($appointments)) {
            return array(
                'success' => true,
                'next_available_time' => $start_looking_from->format('Y-m-d H:i:s'),
                'message' => 'Found slot on ' . $check_date
            );
        }

        // Check for gaps between appointments
        foreach ($appointments as $index => $appointment) {
            $appt_start = new DateTime($appointment->start_date);
            
            // Check gap between day start/previous slot and first appointment
            if ($index === 0 && $start_looking_from < $appt_start) {
                return array(
                    'success' => true,
                    'next_available_time' => $start_looking_from->format('Y-m-d H:i:s'),
                    'message' => 'Found slot on ' . $check_date
                );
            }

            // Check gaps between appointments
            if (isset($appointments[$index + 1])) {
                $next_appt_start = new DateTime($appointments[$index + 1]->start_date);
                $current_appt_end = new DateTime($appointment->end_date);
                
                if ($current_appt_end < $next_appt_start) {
                    return array(
                        'success' => true,
                        'next_available_time' => $current_appt_end->format('Y-m-d H:i:s'),
                        'message' => 'Found slot on ' . $check_date
                    );
                }
            } else {
                // Check after last appointment
                $last_appt_end = new DateTime($appointment->end_date);
                if ($last_appt_end < $schedule_end) {
                    return array(
                        'success' => true,
                        'next_available_time' => $last_appt_end->format('Y-m-d H:i:s'),
                        'message' => 'Found slot on ' . $check_date
                    );
                }
            }
        }

        $checked_days++;
    }

    return array(
        'success' => false,
        'message' => 'No available slots found in the next ' . $max_days_to_check . ' days'
    );
}
?>