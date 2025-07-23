<?php

function daroon2_dashboard_appointments_func()
{
    $current_user_id = get_current_user_id();

    // Get Bookly customer info by user ID
    $bookly_customer = daroon2_get_bookly_customer_info($current_user_id);
    $bookly_customer_id = $bookly_customer->id;
    $bookly_customer_appointments = daroon2_get_bookly_customer_appointments($bookly_customer_id);
    $future_appointment = daroon2_get_bookly_customer_future_appointment($bookly_customer_id);

    $complete_appointments = array_merge($bookly_customer_appointments, $future_appointment);
    $appointments_staff = daroon2_get_staff_list_info_from_appointments($complete_appointments);

    // d($future_appointment);
    // d($current_user_id);
    // d($bookly_customer);
    // d($bookly_customer_id);
    // d($bookly_customer_appointments);
    // d($appointments_staff);
?>
    <style>
        .session-items a {
            text-decoration: none;
        }
    </style>

    <div class="daroon2-profile-dashboard" style="width: 100%">
        <?php
        if (sizeof($future_appointment) > 0) {
            $appointment = $future_appointment[0];

            $date = formatDateWithOffset($appointment->start_date, $appointment->time_zone_offset);
            $date_offset = formatTimezoneOffset($appointment->time_zone_offset);
            $date_str = $date . " (GMT" . $date_offset . ")";

            $staff_id = $appointment->staff_id;
            $staff_image = $appointments_staff->$staff_id->image;
            $staff_url = $appointments_staff->$staff_id->url;
            $job_title = $appointments_staff->$staff_id->job_title;

            $can_join = false;
            $current_time = time();
            $start_time = strtotime($appointment->start_date);
            $time_diff = $start_time - $current_time;
            $time_diff_minutes = floor($time_diff / 60);
            if ($time_diff_minutes < 30) $can_join = true;
        ?>
            <section id="next-session" class="mb-4">
                <h2 class="title3 color-content-white mb-2">Next session details</h2>

                <article id="session-0" class="session-items d-flex flex-wrap">
                    <a href="<?= $staff_url; ?>" class="therapist-img" style="background-image: url('<?= $staff_image; ?>')"></a>
                    <div href="" class="thrapist-title grow-1 ml-2">
                        <h3>
                            <a href="<?= $staff_url; ?>" class="title1 color-content-primary">
                                <?= $appointment->staff_name; ?>
                            </a>
                        </h3>
                        <p class="therapist-expercy caption color-action-aqua"><?= $job_title; ?></p>
                    </div>

                    <time class="w-100 mt-3 d-flex justify-content-start" datetime="2025-07-11">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.5625 2.95125V1.78125C12.5625 1.47375 12.3075 1.21875 12 1.21875C11.6925 1.21875 11.4375 1.47375 11.4375 1.78125V2.90625H6.56249V1.78125C6.56249 1.47375 6.30749 1.21875 5.99999 1.21875C5.69249 1.21875 5.43749 1.47375 5.43749 1.78125V2.95125C3.41249 3.13875 2.42999 4.34625 2.27999 6.13875C2.26499 6.35625 2.44499 6.53625 2.65499 6.53625H15.345C15.5625 6.53625 15.7425 6.34875 15.72 6.13875C15.57 4.34625 14.5875 3.13875 12.5625 2.95125Z" fill="black" />
                            <path d="M15 7.66127H3C2.5875 7.66127 2.25 7.99877 2.25 8.41127V13.0313C2.25 15.2813 3.375 16.7813 6 16.7813H12C14.625 16.7813 15.75 15.2813 15.75 13.0313V8.41127C15.75 7.99877 15.4125 7.66127 15 7.66127ZM6.9075 13.9388C6.87 13.9688 6.8325 14.0063 6.795 14.0288C6.75 14.0588 6.705 14.0813 6.66 14.0963C6.615 14.1188 6.57 14.1338 6.525 14.1413C6.4725 14.1488 6.4275 14.1563 6.375 14.1563C6.2775 14.1563 6.18 14.1338 6.09 14.0963C5.9925 14.0588 5.9175 14.0063 5.8425 13.9388C5.7075 13.7963 5.625 13.6013 5.625 13.4063C5.625 13.2113 5.7075 13.0163 5.8425 12.8738C5.9175 12.8063 5.9925 12.7538 6.09 12.7163C6.225 12.6563 6.375 12.6413 6.525 12.6713C6.57 12.6788 6.615 12.6938 6.66 12.7163C6.705 12.7313 6.75 12.7538 6.795 12.7838C6.8325 12.8138 6.87 12.8438 6.9075 12.8738C7.0425 13.0163 7.125 13.2113 7.125 13.4063C7.125 13.6013 7.0425 13.7963 6.9075 13.9388ZM6.9075 11.3138C6.765 11.4488 6.57 11.5313 6.375 11.5313C6.18 11.5313 5.985 11.4488 5.8425 11.3138C5.7075 11.1713 5.625 10.9763 5.625 10.7813C5.625 10.5863 5.7075 10.3913 5.8425 10.2488C6.0525 10.0388 6.3825 9.97127 6.66 10.0913C6.7575 10.1288 6.84 10.1813 6.9075 10.2488C7.0425 10.3913 7.125 10.5863 7.125 10.7813C7.125 10.9763 7.0425 11.1713 6.9075 11.3138ZM9.5325 13.9388C9.39 14.0738 9.195 14.1563 9 14.1563C8.805 14.1563 8.61 14.0738 8.4675 13.9388C8.3325 13.7963 8.25 13.6013 8.25 13.4063C8.25 13.2113 8.3325 13.0163 8.4675 12.8738C8.745 12.5963 9.255 12.5963 9.5325 12.8738C9.6675 13.0163 9.75 13.2113 9.75 13.4063C9.75 13.6013 9.6675 13.7963 9.5325 13.9388ZM9.5325 11.3138C9.495 11.3438 9.4575 11.3738 9.42 11.4038C9.375 11.4338 9.33 11.4563 9.285 11.4713C9.24 11.4938 9.195 11.5088 9.15 11.5163C9.0975 11.5238 9.0525 11.5313 9 11.5313C8.805 11.5313 8.61 11.4488 8.4675 11.3138C8.3325 11.1713 8.25 10.9763 8.25 10.7813C8.25 10.5863 8.3325 10.3913 8.4675 10.2488C8.535 10.1813 8.6175 10.1288 8.715 10.0913C8.9925 9.97127 9.3225 10.0388 9.5325 10.2488C9.6675 10.3913 9.75 10.5863 9.75 10.7813C9.75 10.9763 9.6675 11.1713 9.5325 11.3138ZM12.1575 13.9388C12.015 14.0738 11.82 14.1563 11.625 14.1563C11.43 14.1563 11.235 14.0738 11.0925 13.9388C10.9575 13.7963 10.875 13.6013 10.875 13.4063C10.875 13.2113 10.9575 13.0163 11.0925 12.8738C11.37 12.5963 11.88 12.5963 12.1575 12.8738C12.2925 13.0163 12.375 13.2113 12.375 13.4063C12.375 13.6013 12.2925 13.7963 12.1575 13.9388ZM12.1575 11.3138C12.12 11.3438 12.0825 11.3738 12.045 11.4038C12 11.4338 11.955 11.4563 11.91 11.4713C11.865 11.4938 11.82 11.5088 11.775 11.5163C11.7225 11.5238 11.67 11.5313 11.625 11.5313C11.43 11.5313 11.235 11.4488 11.0925 11.3138C10.9575 11.1713 10.875 10.9763 10.875 10.7813C10.875 10.5863 10.9575 10.3913 11.0925 10.2488C11.1675 10.1813 11.2425 10.1288 11.34 10.0913C11.475 10.0313 11.625 10.0163 11.775 10.0463C11.82 10.0538 11.865 10.0688 11.91 10.0913C11.955 10.1063 12 10.1288 12.045 10.1588C12.0825 10.1888 12.12 10.2188 12.1575 10.2488C12.2925 10.3913 12.375 10.5863 12.375 10.7813C12.375 10.9763 12.2925 11.1713 12.1575 11.3138Z" fill="black" />
                        </svg>
                        <span class="title1" style="margin-left: calc(.5 * var(--bp))"><?= $date_str; ?></span>
                    </time>

                    <a href="#" class="link-angle btn btn-size-m btn-style-plain">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.9998 14.9238C11.8098 14.9238 11.6198 14.8538 11.4698 14.7038L7.11984 10.3538C6.82984 10.0638 6.82984 9.58379 7.11984 9.29379C7.40984 9.00379 7.88984 9.00379 8.17984 9.29379L11.9998 13.1138L15.8198 9.29379C16.1098 9.00379 16.5898 9.00379 16.8798 9.29379C17.1698 9.58379 17.1698 10.0638 16.8798 10.3538L12.5298 14.7038C12.3798 14.8538 12.1898 14.9238 11.9998 14.9238Z" fill="#686B6A" />
                        </svg>
                    </a>

                    <div class="opened-session">
                        <div class="content p-4">
                            <div href="#" class="close btn btn-size-m btn-style-plain">
                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.3759 17.4592L13.4166 12.4999L18.3759 7.54066C18.7632 7.15336 18.7632 6.51102 18.3759 6.12372C17.9886 5.73643 17.3462 5.73643 16.9589 6.12372L11.9997 11.083L7.04041 6.12372C6.65312 5.73643 6.01077 5.73643 5.62348 6.12372C5.23618 6.51102 5.23618 7.15336 5.62348 7.54066L10.5827 12.4999L5.62348 17.4592C5.23618 17.8465 5.23618 18.4888 5.62348 18.8761C6.01077 19.2634 6.65312 19.2634 7.04041 18.8761L11.9997 13.9169L16.9589 18.8761C17.3462 19.2634 17.9886 19.2634 18.3759 18.8761C18.7632 18.4888 18.7632 17.8465 18.3759 17.4592Z" fill="#686B6A" />
                                </svg>
                            </div>

                            <div class="therapist-info d-flex align-items-strech justify-content-start">
                                <a href="<?= $staff_url; ?>" class="therapist-img" style="background-image: url('<?= $staff_image; ?>')"></a>
                                <div class="therapist-title d-flex grow-1 direction-column align-items-start justify-content-around ml-2">
                                    <h3 class="title1">
                                        <a href="<?= $staff_url; ?>" class="title1 color-content-primary">
                                            <?= $appointment->staff_name; ?>
                                        </a>
                                    </h3>
                                    <p class="therapist-expercy caption color-action-aqua"><?= $job_title; ?></p>
                                </div>
                            </div>

                            <div class="session-info">
                                <div class="d-flex mb-1">
                                    <time class="d-inline-flex align-items-center" datetime="2025-07-11">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.5625 2.95125V1.78125C12.5625 1.47375 12.3075 1.21875 12 1.21875C11.6925 1.21875 11.4375 1.47375 11.4375 1.78125V2.90625H6.56249V1.78125C6.56249 1.47375 6.30749 1.21875 5.99999 1.21875C5.69249 1.21875 5.43749 1.47375 5.43749 1.78125V2.95125C3.41249 3.13875 2.42999 4.34625 2.27999 6.13875C2.26499 6.35625 2.44499 6.53625 2.65499 6.53625H15.345C15.5625 6.53625 15.7425 6.34875 15.72 6.13875C15.57 4.34625 14.5875 3.13875 12.5625 2.95125Z" fill="black" />
                                            <path d="M15 7.66127H3C2.5875 7.66127 2.25 7.99877 2.25 8.41127V13.0313C2.25 15.2813 3.375 16.7813 6 16.7813H12C14.625 16.7813 15.75 15.2813 15.75 13.0313V8.41127C15.75 7.99877 15.4125 7.66127 15 7.66127ZM6.9075 13.9388C6.87 13.9688 6.8325 14.0063 6.795 14.0288C6.75 14.0588 6.705 14.0813 6.66 14.0963C6.615 14.1188 6.57 14.1338 6.525 14.1413C6.4725 14.1488 6.4275 14.1563 6.375 14.1563C6.2775 14.1563 6.18 14.1338 6.09 14.0963C5.9925 14.0588 5.9175 14.0063 5.8425 13.9388C5.7075 13.7963 5.625 13.6013 5.625 13.4063C5.625 13.2113 5.7075 13.0163 5.8425 12.8738C5.9175 12.8063 5.9925 12.7538 6.09 12.7163C6.225 12.6563 6.375 12.6413 6.525 12.6713C6.57 12.6788 6.615 12.6938 6.66 12.7163C6.705 12.7313 6.75 12.7538 6.795 12.7838C6.8325 12.8138 6.87 12.8438 6.9075 12.8738C7.0425 13.0163 7.125 13.2113 7.125 13.4063C7.125 13.6013 7.0425 13.7963 6.9075 13.9388ZM6.9075 11.3138C6.765 11.4488 6.57 11.5313 6.375 11.5313C6.18 11.5313 5.985 11.4488 5.8425 11.3138C5.7075 11.1713 5.625 10.9763 5.625 10.7813C5.625 10.5863 5.7075 10.3913 5.8425 10.2488C6.0525 10.0388 6.3825 9.97127 6.66 10.0913C6.7575 10.1288 6.84 10.1813 6.9075 10.2488C7.0425 10.3913 7.125 10.5863 7.125 10.7813C7.125 10.9763 7.0425 11.1713 6.9075 11.3138ZM9.5325 13.9388C9.39 14.0738 9.195 14.1563 9 14.1563C8.805 14.1563 8.61 14.0738 8.4675 13.9388C8.3325 13.7963 8.25 13.6013 8.25 13.4063C8.25 13.2113 8.3325 13.0163 8.4675 12.8738C8.745 12.5963 9.255 12.5963 9.5325 12.8738C9.6675 13.0163 9.75 13.2113 9.75 13.4063C9.75 13.6013 9.6675 13.7963 9.5325 13.9388ZM9.5325 11.3138C9.495 11.3438 9.4575 11.3738 9.42 11.4038C9.375 11.4338 9.33 11.4563 9.285 11.4713C9.24 11.4938 9.195 11.5088 9.15 11.5163C9.0975 11.5238 9.0525 11.5313 9 11.5313C8.805 11.5313 8.61 11.4488 8.4675 11.3138C8.3325 11.1713 8.25 10.9763 8.25 10.7813C8.25 10.5863 8.3325 10.3913 8.4675 10.2488C8.535 10.1813 8.6175 10.1288 8.715 10.0913C8.9925 9.97127 9.3225 10.0388 9.5325 10.2488C9.6675 10.3913 9.75 10.5863 9.75 10.7813C9.75 10.9763 9.6675 11.1713 9.5325 11.3138ZM12.1575 13.9388C12.015 14.0738 11.82 14.1563 11.625 14.1563C11.43 14.1563 11.235 14.0738 11.0925 13.9388C10.9575 13.7963 10.875 13.6013 10.875 13.4063C10.875 13.2113 10.9575 13.0163 11.0925 12.8738C11.37 12.5963 11.88 12.5963 12.1575 12.8738C12.2925 13.0163 12.375 13.2113 12.375 13.4063C12.375 13.6013 12.2925 13.7963 12.1575 13.9388ZM12.1575 11.3138C12.12 11.3438 12.0825 11.3738 12.045 11.4038C12 11.4338 11.955 11.4563 11.91 11.4713C11.865 11.4938 11.82 11.5088 11.775 11.5163C11.7225 11.5238 11.67 11.5313 11.625 11.5313C11.43 11.5313 11.235 11.4488 11.0925 11.3138C10.9575 11.1713 10.875 10.9763 10.875 10.7813C10.875 10.5863 10.9575 10.3913 11.0925 10.2488C11.1675 10.1813 11.2425 10.1288 11.34 10.0913C11.475 10.0313 11.625 10.0163 11.775 10.0463C11.82 10.0538 11.865 10.0688 11.91 10.0913C11.955 10.1063 12 10.1288 12.045 10.1588C12.0825 10.1888 12.12 10.2188 12.1575 10.2488C12.2925 10.3913 12.375 10.5863 12.375 10.7813C12.375 10.9763 12.2925 11.1713 12.1575 11.3138Z" fill="black" />
                                        </svg>
                                        <span class="title1" style="margin-left: calc(.5 * var(--bp))"><?= $date_str; ?></span>
                                    </time>

                                    <?php
                                    if ($can_join) {
                                    ?>
                                        <a class="d-inline-flex align-items-center" href="<?= $appointment->online_meeting_id; ?>" target="_blank">
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <rect width="16" height="16" transform="translate(0 0.5)" fill="url(#pattern0_302_972)" />
                                                <defs>
                                                    <pattern id="pattern0_302_972" patternContentUnits="objectBoundingBox" width="1" height="1">
                                                        <use xlink:href="#image0_302_972" transform="scale(0.00520833)" />
                                                    </pattern>
                                                    <image id="image0_302_972" width="192" height="192" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMAAAADACAMAAABlApw1AAAB/lBMVEUAAADqQzX7vAT8vAP7vAT6vAX8vAP/vwDqQzX7vAT7vQT/vwD7vAT6uwX8uwNAv0A0p1I0qVQzqFM0qFM0qVMyqlU0qFM0qFM1qFMrqlU1p1Q0qFI0p1M0qFM0qFQ1qFI1qlU0qFM0qFM1qlU0qFM0qVI0qVQ4p1A0qFM0p1Q0qFM0qFMzplk0qFM0qFE0qFMzp1I0qFM0p1M3pFIyqFIzqVM0qFP7vAQzqVP3uwVgkifjtQk7iTAYgDhChfTCrREqhTSfoxkcgTf3uwVzmCPtuAhUjyrYswy7qxMjgzWYoRtwlyTltglRjyvUsg00iDK3qhSQoBz0ugZplSVKjS3RsQ4xhzKwqBWJnh7wuQdmlCZGjC7NsA4uhjOpphcfgjaFnR9fkijftApCiy7GrhCmpReCnCDxugZXkCnftQrOsA+8qxIol0grm0oZgTgtn00Zgzkwo08chjwxpVAeij8zp1MijkImlEUqmkkZgTkunkwbhDswo1AzplIgij8jkEMnlkYsnEsZgjkvoE4xpFAeiD4hjEElkkUpmEgsnUwwok8ZZ9IvoU4yplIeiD40qFIij0I1qFM0qFMzplM0p1I1p1IzqlUzqVI0qFQ0qFNAn2A0qFIzqFM1qFM0qVMZZ9I1qFMZZ9IYZtMbZdUaZ9EXaNEZZ9IZaNIZZ9Izp1Qytw8fAAAAqnRSTlMAb//fw6NXDP/3fxDjOFMEY7ffr1Mky/+zDEPra/tPmxi/1zDzf6sgz0DnkxS7LNtLe6McOF/3+4v33/b//////////////////////////////////////////////////////////v/679jS5//0//v//v////////////////////////////////jz/PXYh+8ow1c8c2fTCKefb+Pnx8t/MLMs+2ePd+iKBoYAAANYSURBVHgB7M7BAURAAATBuwGzIP9wpeDTXl0R1E+SJEmSJEl65R/ENC/r9sm/wYz94P9tQOfF/xvSuPF/gxoX/W9Y5wH/G9gO/xvY2Nh/Q1vZf0Nb2H9Dm9l/Q5vYf4Nj/w/1c2GEQADFQJQKcC64u7tb/03hbv8mIxm2gn16APH/RwD7Xw/g//UAnz8QDIbC/L8YEHFwKhrj/5WAeAKXAvy/EJBM4Vaa/1cBfBk8FOX/RYBsDo/l+X8NoBDEc/y/BBAu4iX+XwEo4S36XwCIlfEe+y8AVKr4EPkvANTq+BT3LwD4U/gY8y8A+Br4EvEvADRbMADGvxjQ7uBr9r8e0C3ie+a/HtBL4UfGvx4Q6+Nnxr8cMHDwO+NfDRjWYWT8iwGjMayMfylgMoWd8a8EzOaAnfEvBCyWKw6wdpuHzs3/Zrunfi4OGAQAAAYuRiq41d1dYf83c+Q2ONSBYYg5EMVgDiQp6kAWoA7kBepAGWIORBWYA/UIdWA8QR2YFqgDsxBzYL4Ac6Beog6s1qgDmwJ1YAvmwG6POlAfUAeOJ9SB8wV14ArmwO6GOnB/oA48T6gDrxBzIHqDOfD5og78AtSB/wB1oAlxB1o66ueCAIEAAGBgjJXEeXfDrTIRcNk1OHmA8UQeYDqTB5gv5AGWgTxAGMkDENsDJKk8QJbLAxQLeYCykgcIa3kAmok8QNvJA/S5PMCwkgco1/IA4UYegO1EHmA3kwfYL+QBhkAeIIzkAYjtAZJUHiDL5QGKhTxAWckDhLU8AM1EHqDtng4c7sVHHXN5gNNZHuBybb8ubBsIAiCK/grCzMzMXYYZzUmV4cS28HS4I83v4C3vSaCA6J2qA+gaFwcwciYO4HxTHMDUhTiAzh1xAMyrA7g8EAdwtSIOoH9GHMDwujiAzklxAFyPiwOYmBMHMLsiDmBqSRzAcK84gM6jwbYO1ABw03YY3eoBGJkbbLYjCOC8Z/C/WTlA+yfnFEkAd6vfG+HgGlEAbGzfPzzuogeIkAGp9USsnoMBvBCrUjCABWJVDgawT6wqoQDG14hXNRDAKjGr1YMAnE0Rt0YIgPEt4vcaAOCRJDXqBQPmJkhWrVoo4GGRxFXKpedCAE8Xb/0455xzzjnnnHPOORetDwnHcN1C+Y/JAAAAAElFTkSuQmCC" />
                                                </defs>
                                            </svg>
                                            <span class="title1 color-content-primary">Google meet</span>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="d-inline-flex color-action-ember">
                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.2036 1.83325H9.47025C7.33691 1.83325 6.00358 3.16659 6.00358 5.29992V7.99992H8.96358L7.58358 6.61992C7.48358 6.51992 7.43691 6.39325 7.43691 6.26658C7.43691 6.13992 7.48358 6.01325 7.58358 5.91325C7.77691 5.71992 8.09691 5.71992 8.29025 5.91325L10.5236 8.14658C10.7169 8.33992 10.7169 8.65992 10.5236 8.85325L8.29025 11.0866C8.09691 11.2799 7.77691 11.2799 7.58358 11.0866C7.39025 10.8933 7.39025 10.5733 7.58358 10.3799L8.96358 8.99992H6.00358V11.6999C6.00358 13.8333 7.33691 15.1666 9.47025 15.1666H11.1969C13.3302 15.1666 14.6636 13.8333 14.6636 11.6999V5.29992C14.6702 3.16659 13.3369 1.83325 11.2036 1.83325Z" fill="#F26131" />
                                        <path d="M1.83691 7.99992C1.56358 7.99992 1.33691 8.22658 1.33691 8.49992C1.33691 8.77325 1.56358 8.99992 1.83691 8.99992H6.00358V7.99992H1.83691Z" fill="#F26131" />
                                    </svg>
                                    <span class="title1">Session opens 30 minutes before start time.</span>
                                </div>
                            </div>

                            <div class="session-actions">
                                <div class="buttons-wrapper d-flex">
                                    <form class="grow-1 mr-1 d-flex"
                                        action="<?= $appointment->online_meeting_id; ?>">
                                        <button
                                            class="btn btn-style-brand btn-size-m grow-1"
                                            <?php echo (!$can_join) ? 'disabled' : ''; ?>>
                                            <span>Join sesson</span>
                                        </button>
                                    </form>
                                    <a href="mailto:f.ahmadyf94@gmail.com?subject=reschedule" class="btn btn-style-outline btn-size-m grow-1">
                                        <span>Reschedule</span>
                                    </a>
                                </div>
                                <p class="caption mt-1 w-100 text-center">
                                    Can’t make it? You can reschedule anytime before 24 hours.
                                </p>
                            </div>
                        </div>
                        <div class="overlay"></div>
                    </div>
                </article>
            </section>
        <?php
        }
        ?>

        <section id="old-sessions">
            <div>
                <p class="body2 color-content-white">
                    A record of all your past sessions — attended, missed, and canceled. Tap any session to see more details.
                </p>
            </div>

            <!--
                <div class="filters">
                    <h4 class="body2 color-content-white mb-2">Filters:</h4>
                    <div class="filters-wrapper d-flex justify-content-start">
                        <div class="filter-item btn btn-size-m btn-style-outline-white">
                        <span>Date</span>
                        </div>

                        <div class="filter-item btn btn-size-m btn-style-outline-white">
                        <span>Therapist</span>
                        </div>

                        <div class="filter-item btn btn-size-m btn-style-outline-white">
                        <span>State</span>
                        </div>
                    </div>
                </div>
            -->

            <div class="sessions" data-type="detailed">
                <div class="type-selector d-flex mb-1">
                    <h4 class="title2 color-content-white">Sessions</h4>
                    <div class="btn btn-size-l btn-style-white-subtle type-selector">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.9996 7.75998H6.9996C6.58626 7.75998 6.25293 7.41331 6.25293 7.01331C6.25293 6.61331 6.58626 6.26664 6.9996 6.26664H20.9996C21.4129 6.26664 21.7463 6.61331 21.7463 7.01331C21.7463 7.41331 21.4129 7.75998 20.9996 7.75998Z" fill="white" />
                            <path d="M20.9996 12.76H6.9996C6.58626 12.76 6.25293 12.4133 6.25293 12.0133C6.25293 11.6133 6.58626 11.2666 6.9996 11.2666H20.9996C21.4129 11.2666 21.7463 11.6133 21.7463 12.0133C21.7463 12.4133 21.4129 12.76 20.9996 12.76Z" fill="white" />
                            <path d="M20.9996 17.76H6.9996C6.58626 17.76 6.25293 17.4133 6.25293 17.0133C6.25293 16.6133 6.58626 16.2666 6.9996 16.2666H20.9996C21.4129 16.2666 21.7463 16.6133 21.7463 17.0133C21.7463 17.4133 21.4129 17.76 20.9996 17.76Z" fill="white" />
                            <path d="M3.30626 8.05332C3.888 8.05332 4.3596 7.58173 4.3596 6.99999C4.3596 6.41825 3.888 5.94666 3.30626 5.94666C2.72452 5.94666 2.25293 6.41825 2.25293 6.99999C2.25293 7.58173 2.72452 8.05332 3.30626 8.05332Z" fill="white" />
                            <path d="M3.30626 13.0533C3.888 13.0533 4.3596 12.5817 4.3596 12C4.3596 11.4182 3.888 10.9467 3.30626 10.9467C2.72452 10.9467 2.25293 11.4182 2.25293 12C2.25293 12.5817 2.72452 13.0533 3.30626 13.0533Z" fill="white" />
                            <path d="M3.30626 18.0533C3.888 18.0533 4.3596 17.5817 4.3596 17C4.3596 16.4182 3.888 15.9467 3.30626 15.9467C2.72452 15.9467 2.25293 16.4182 2.25293 17C2.25293 17.5817 2.72452 18.0533 3.30626 18.0533Z" fill="white" />
                        </svg>

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.899 23.39H4.09902C2.17902 23.39 1.24902 22.41 1.24902 20.41V16.37C1.24902 14.36 2.17902 13.39 4.09902 13.39H19.899C21.819 13.39 22.749 14.37 22.749 16.37V20.41C22.749 22.41 21.819 23.39 19.899 23.39ZM4.09902 14.89C3.08902 14.89 2.74902 15.1 2.74902 16.37V20.41C2.74902 21.68 3.08902 21.89 4.09902 21.89H19.899C20.909 21.89 21.249 21.68 21.249 20.41V16.37C21.249 15.1 20.909 14.89 19.899 14.89H4.09902Z" fill="white" />
                            <path d="M19.899 11.8896H4.09902C2.17902 11.8896 1.24902 10.9096 1.24902 8.90965V4.86965C1.24902 2.85965 2.17902 1.88965 4.09902 1.88965H19.899C21.819 1.88965 22.749 2.86965 22.749 4.86965V8.90965C22.749 10.9096 21.819 11.8896 19.899 11.8896ZM4.09902 3.38965C3.08902 3.38965 2.74902 3.59965 2.74902 4.86965V8.90965C2.74902 10.1796 3.08902 10.3896 4.09902 10.3896H19.899C20.909 10.3896 21.249 10.1796 21.249 8.90965V4.86965C21.249 3.59965 20.909 3.38965 19.899 3.38965H4.09902Z" fill="white" />
                        </svg>
                    </div>
                </div>

                <div class="session-items-wrapper">
                    <?php
                    if (sizeof($bookly_customer_appointments) == 0) {
                    ?>
                        <div class="d-flex w-100 justify-content-center color-content-white">
                            No seesion booked yet.
                        </div>
                        <?php
                    } else {
                        foreach ($bookly_customer_appointments as $bookly_customer_appointment) {
                            $date = formatDateWithOffset($bookly_customer_appointment->start_date, $bookly_customer_appointment->time_zone_offset);
                            $date_offset = formatTimezoneOffset($bookly_customer_appointment->time_zone_offset);
                            $date_str = $date . " (GMT" . $date_offset . ")";

                            $appointment_id = $bookly_customer_appointment->appointment_id;
                            $staff_id = $bookly_customer_appointment->staff_id;
                            $staff_image = $appointments_staff->$staff_id->image;
                            $staff_url = $appointments_staff->$staff_id->url;
                            $job_title = $appointments_staff->$staff_id->job_title;

                            $status_class = "status-" . $bookly_customer_appointment->appointment_status;
                            $staff_class = "staff-" . $staff_id;
                            $time_class = "time-" . strtotime($bookly_customer_appointment->start_date);

                            if ($status_class === 'status-cancelled') {
                                $invoice_download_link = "<button onclick='this.disabled = true; doSomething();'' class='daroon2-create-invoice btn btn-style-outline btn-size-m grow-1' disabled>
                                    <span>Request receipt</span>
                                    </button>";
                            } else if (!is_null($bookly_customer_appointment->invoice_name)) {
                                $invoice_link = get_site_url() . '/wp-content/uploads/jung_files/' . $bookly_customer_appointment->invoice_name . '.pdf';
                                $invoice_download_link = "<a href='$invoice_link' class='btn btn-style-outline btn-size-m grow-1'>
                                                <span>Download receipt</span>
                                            </a>";
                            } else {
                                $payment_id = $bookly_customer_appointment->payment_id;
                                $invoice_download_link = "<a data-payment-id='$payment_id' class='daroon2-create-invoice btn btn-style-outline btn-size-m grow-1'>
                                    <span>Request receipt</span>
                                    </a>";
                            }

                            $extra_classes = array(
                                $status_class,
                                $staff_class,
                                $time_class
                            );
                        ?>
                            <article id="session-<?= $bookly_customer_appointment->appointment_id; ?>"
                                class="session-items d-flex flex-wrap <?= implode(' ', $extra_classes); ?>">
                                <a href="<?= $staff_url; ?>" class="therapist-img" style="background-image: url('<?= $staff_image; ?>')"></a>
                                <div href="" class="thrapist-title grow-1 ml-2">
                                    <h3>
                                        <a href="<?= $staff_url; ?>" class="title1 color-content-primary">
                                            <?= $bookly_customer_appointment->staff_name; ?>
                                        </a>
                                    </h3>
                                    <p class="therapist-expercy caption color-action-aqua"><?= $job_title; ?></p>
                                </div>

                                <time class="w-100 mt-3 d-flex justify-content-start" datetime="2025-07-11">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.5625 2.95125V1.78125C12.5625 1.47375 12.3075 1.21875 12 1.21875C11.6925 1.21875 11.4375 1.47375 11.4375 1.78125V2.90625H6.56249V1.78125C6.56249 1.47375 6.30749 1.21875 5.99999 1.21875C5.69249 1.21875 5.43749 1.47375 5.43749 1.78125V2.95125C3.41249 3.13875 2.42999 4.34625 2.27999 6.13875C2.26499 6.35625 2.44499 6.53625 2.65499 6.53625H15.345C15.5625 6.53625 15.7425 6.34875 15.72 6.13875C15.57 4.34625 14.5875 3.13875 12.5625 2.95125Z" fill="black" />
                                        <path d="M15 7.66127H3C2.5875 7.66127 2.25 7.99877 2.25 8.41127V13.0313C2.25 15.2813 3.375 16.7813 6 16.7813H12C14.625 16.7813 15.75 15.2813 15.75 13.0313V8.41127C15.75 7.99877 15.4125 7.66127 15 7.66127ZM6.9075 13.9388C6.87 13.9688 6.8325 14.0063 6.795 14.0288C6.75 14.0588 6.705 14.0813 6.66 14.0963C6.615 14.1188 6.57 14.1338 6.525 14.1413C6.4725 14.1488 6.4275 14.1563 6.375 14.1563C6.2775 14.1563 6.18 14.1338 6.09 14.0963C5.9925 14.0588 5.9175 14.0063 5.8425 13.9388C5.7075 13.7963 5.625 13.6013 5.625 13.4063C5.625 13.2113 5.7075 13.0163 5.8425 12.8738C5.9175 12.8063 5.9925 12.7538 6.09 12.7163C6.225 12.6563 6.375 12.6413 6.525 12.6713C6.57 12.6788 6.615 12.6938 6.66 12.7163C6.705 12.7313 6.75 12.7538 6.795 12.7838C6.8325 12.8138 6.87 12.8438 6.9075 12.8738C7.0425 13.0163 7.125 13.2113 7.125 13.4063C7.125 13.6013 7.0425 13.7963 6.9075 13.9388ZM6.9075 11.3138C6.765 11.4488 6.57 11.5313 6.375 11.5313C6.18 11.5313 5.985 11.4488 5.8425 11.3138C5.7075 11.1713 5.625 10.9763 5.625 10.7813C5.625 10.5863 5.7075 10.3913 5.8425 10.2488C6.0525 10.0388 6.3825 9.97127 6.66 10.0913C6.7575 10.1288 6.84 10.1813 6.9075 10.2488C7.0425 10.3913 7.125 10.5863 7.125 10.7813C7.125 10.9763 7.0425 11.1713 6.9075 11.3138ZM9.5325 13.9388C9.39 14.0738 9.195 14.1563 9 14.1563C8.805 14.1563 8.61 14.0738 8.4675 13.9388C8.3325 13.7963 8.25 13.6013 8.25 13.4063C8.25 13.2113 8.3325 13.0163 8.4675 12.8738C8.745 12.5963 9.255 12.5963 9.5325 12.8738C9.6675 13.0163 9.75 13.2113 9.75 13.4063C9.75 13.6013 9.6675 13.7963 9.5325 13.9388ZM9.5325 11.3138C9.495 11.3438 9.4575 11.3738 9.42 11.4038C9.375 11.4338 9.33 11.4563 9.285 11.4713C9.24 11.4938 9.195 11.5088 9.15 11.5163C9.0975 11.5238 9.0525 11.5313 9 11.5313C8.805 11.5313 8.61 11.4488 8.4675 11.3138C8.3325 11.1713 8.25 10.9763 8.25 10.7813C8.25 10.5863 8.3325 10.3913 8.4675 10.2488C8.535 10.1813 8.6175 10.1288 8.715 10.0913C8.9925 9.97127 9.3225 10.0388 9.5325 10.2488C9.6675 10.3913 9.75 10.5863 9.75 10.7813C9.75 10.9763 9.6675 11.1713 9.5325 11.3138ZM12.1575 13.9388C12.015 14.0738 11.82 14.1563 11.625 14.1563C11.43 14.1563 11.235 14.0738 11.0925 13.9388C10.9575 13.7963 10.875 13.6013 10.875 13.4063C10.875 13.2113 10.9575 13.0163 11.0925 12.8738C11.37 12.5963 11.88 12.5963 12.1575 12.8738C12.2925 13.0163 12.375 13.2113 12.375 13.4063C12.375 13.6013 12.2925 13.7963 12.1575 13.9388ZM12.1575 11.3138C12.12 11.3438 12.0825 11.3738 12.045 11.4038C12 11.4338 11.955 11.4563 11.91 11.4713C11.865 11.4938 11.82 11.5088 11.775 11.5163C11.7225 11.5238 11.67 11.5313 11.625 11.5313C11.43 11.5313 11.235 11.4488 11.0925 11.3138C10.9575 11.1713 10.875 10.9763 10.875 10.7813C10.875 10.5863 10.9575 10.3913 11.0925 10.2488C11.1675 10.1813 11.2425 10.1288 11.34 10.0913C11.475 10.0313 11.625 10.0163 11.775 10.0463C11.82 10.0538 11.865 10.0688 11.91 10.0913C11.955 10.1063 12 10.1288 12.045 10.1588C12.0825 10.1888 12.12 10.2188 12.1575 10.2488C12.2925 10.3913 12.375 10.5863 12.375 10.7813C12.375 10.9763 12.2925 11.1713 12.1575 11.3138Z" fill="black" />
                                    </svg>
                                    <span class="title1" style="margin-left: calc(.5 * var(--bp))"><?= $date_str; ?></span>
                                </time>

                                <a href="#" class="link-angle btn btn-size-m btn-style-plain">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.9998 14.9238C11.8098 14.9238 11.6198 14.8538 11.4698 14.7038L7.11984 10.3538C6.82984 10.0638 6.82984 9.58379 7.11984 9.29379C7.40984 9.00379 7.88984 9.00379 8.17984 9.29379L11.9998 13.1138L15.8198 9.29379C16.1098 9.00379 16.5898 9.00379 16.8798 9.29379C17.1698 9.58379 17.1698 10.0638 16.8798 10.3538L12.5298 14.7038C12.3798 14.8538 12.1898 14.9238 11.9998 14.9238Z" fill="#686B6A" />
                                    </svg>
                                </a>

                                <div class="opened-session">
                                    <div class="content p-4">
                                        <div href="#" class="close btn btn-size-m btn-style-plain">
                                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M18.3759 17.4592L13.4166 12.4999L18.3759 7.54066C18.7632 7.15336 18.7632 6.51102 18.3759 6.12372C17.9886 5.73643 17.3462 5.73643 16.9589 6.12372L11.9997 11.083L7.04041 6.12372C6.65312 5.73643 6.01077 5.73643 5.62348 6.12372C5.23618 6.51102 5.23618 7.15336 5.62348 7.54066L10.5827 12.4999L5.62348 17.4592C5.23618 17.8465 5.23618 18.4888 5.62348 18.8761C6.01077 19.2634 6.65312 19.2634 7.04041 18.8761L11.9997 13.9169L16.9589 18.8761C17.3462 19.2634 17.9886 19.2634 18.3759 18.8761C18.7632 18.4888 18.7632 17.8465 18.3759 17.4592Z" fill="#686B6A" />
                                            </svg>
                                        </div>

                                        <div class="therapist-info d-flex align-items-strech justify-content-start">
                                            <a href="<?= $staff_url; ?>" class="therapist-img" style="background-image: url('<?= $staff_image; ?>')"></a>
                                            <div class="therapist-title d-flex grow-1 direction-column align-items-start justify-content-around ml-2">
                                                <h3>
                                                    <a href="<?= $staff_url; ?>" class="title1 color-content-primary">
                                                        <?= $bookly_customer_appointment->staff_name; ?>
                                                    </a>
                                                </h3>
                                                <p class="therapist-expercy caption color-action-aqua"><?= $job_title; ?></p>
                                            </div>
                                        </div>

                                        <div class="session-info">
                                            <div class="d-flex mb-1">
                                                <time class="d-inline-flex align-items-center" datetime="2025-07-11">
                                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12.5625 2.95125V1.78125C12.5625 1.47375 12.3075 1.21875 12 1.21875C11.6925 1.21875 11.4375 1.47375 11.4375 1.78125V2.90625H6.56249V1.78125C6.56249 1.47375 6.30749 1.21875 5.99999 1.21875C5.69249 1.21875 5.43749 1.47375 5.43749 1.78125V2.95125C3.41249 3.13875 2.42999 4.34625 2.27999 6.13875C2.26499 6.35625 2.44499 6.53625 2.65499 6.53625H15.345C15.5625 6.53625 15.7425 6.34875 15.72 6.13875C15.57 4.34625 14.5875 3.13875 12.5625 2.95125Z" fill="black" />
                                                        <path d="M15 7.66127H3C2.5875 7.66127 2.25 7.99877 2.25 8.41127V13.0313C2.25 15.2813 3.375 16.7813 6 16.7813H12C14.625 16.7813 15.75 15.2813 15.75 13.0313V8.41127C15.75 7.99877 15.4125 7.66127 15 7.66127ZM6.9075 13.9388C6.87 13.9688 6.8325 14.0063 6.795 14.0288C6.75 14.0588 6.705 14.0813 6.66 14.0963C6.615 14.1188 6.57 14.1338 6.525 14.1413C6.4725 14.1488 6.4275 14.1563 6.375 14.1563C6.2775 14.1563 6.18 14.1338 6.09 14.0963C5.9925 14.0588 5.9175 14.0063 5.8425 13.9388C5.7075 13.7963 5.625 13.6013 5.625 13.4063C5.625 13.2113 5.7075 13.0163 5.8425 12.8738C5.9175 12.8063 5.9925 12.7538 6.09 12.7163C6.225 12.6563 6.375 12.6413 6.525 12.6713C6.57 12.6788 6.615 12.6938 6.66 12.7163C6.705 12.7313 6.75 12.7538 6.795 12.7838C6.8325 12.8138 6.87 12.8438 6.9075 12.8738C7.0425 13.0163 7.125 13.2113 7.125 13.4063C7.125 13.6013 7.0425 13.7963 6.9075 13.9388ZM6.9075 11.3138C6.765 11.4488 6.57 11.5313 6.375 11.5313C6.18 11.5313 5.985 11.4488 5.8425 11.3138C5.7075 11.1713 5.625 10.9763 5.625 10.7813C5.625 10.5863 5.7075 10.3913 5.8425 10.2488C6.0525 10.0388 6.3825 9.97127 6.66 10.0913C6.7575 10.1288 6.84 10.1813 6.9075 10.2488C7.0425 10.3913 7.125 10.5863 7.125 10.7813C7.125 10.9763 7.0425 11.1713 6.9075 11.3138ZM9.5325 13.9388C9.39 14.0738 9.195 14.1563 9 14.1563C8.805 14.1563 8.61 14.0738 8.4675 13.9388C8.3325 13.7963 8.25 13.6013 8.25 13.4063C8.25 13.2113 8.3325 13.0163 8.4675 12.8738C8.745 12.5963 9.255 12.5963 9.5325 12.8738C9.6675 13.0163 9.75 13.2113 9.75 13.4063C9.75 13.6013 9.6675 13.7963 9.5325 13.9388ZM9.5325 11.3138C9.495 11.3438 9.4575 11.3738 9.42 11.4038C9.375 11.4338 9.33 11.4563 9.285 11.4713C9.24 11.4938 9.195 11.5088 9.15 11.5163C9.0975 11.5238 9.0525 11.5313 9 11.5313C8.805 11.5313 8.61 11.4488 8.4675 11.3138C8.3325 11.1713 8.25 10.9763 8.25 10.7813C8.25 10.5863 8.3325 10.3913 8.4675 10.2488C8.535 10.1813 8.6175 10.1288 8.715 10.0913C8.9925 9.97127 9.3225 10.0388 9.5325 10.2488C9.6675 10.3913 9.75 10.5863 9.75 10.7813C9.75 10.9763 9.6675 11.1713 9.5325 11.3138ZM12.1575 13.9388C12.015 14.0738 11.82 14.1563 11.625 14.1563C11.43 14.1563 11.235 14.0738 11.0925 13.9388C10.9575 13.7963 10.875 13.6013 10.875 13.4063C10.875 13.2113 10.9575 13.0163 11.0925 12.8738C11.37 12.5963 11.88 12.5963 12.1575 12.8738C12.2925 13.0163 12.375 13.2113 12.375 13.4063C12.375 13.6013 12.2925 13.7963 12.1575 13.9388ZM12.1575 11.3138C12.12 11.3438 12.0825 11.3738 12.045 11.4038C12 11.4338 11.955 11.4563 11.91 11.4713C11.865 11.4938 11.82 11.5088 11.775 11.5163C11.7225 11.5238 11.67 11.5313 11.625 11.5313C11.43 11.5313 11.235 11.4488 11.0925 11.3138C10.9575 11.1713 10.875 10.9763 10.875 10.7813C10.875 10.5863 10.9575 10.3913 11.0925 10.2488C11.1675 10.1813 11.2425 10.1288 11.34 10.0913C11.475 10.0313 11.625 10.0163 11.775 10.0463C11.82 10.0538 11.865 10.0688 11.91 10.0913C11.955 10.1063 12 10.1288 12.045 10.1588C12.0825 10.1888 12.12 10.2188 12.1575 10.2488C12.2925 10.3913 12.375 10.5863 12.375 10.7813C12.375 10.9763 12.2925 11.1713 12.1575 11.3138Z" fill="black" />
                                                    </svg>
                                                    <span class="title1" style="margin-left: calc(.5 * var(--bp))"><?= $date_str; ?></span>
                                                </time>
                                                <a class="d-inline-flex align-items-center" href="<?= (is_null($bookly_customer_appointment->online_meeting_id)) ? '#' : $bookly_customer_appointment->online_meeting_id; ?>" target="_blank">
                                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                        <rect width="16" height="16" transform="translate(0 0.5)" fill="url(#pattern0_302_972)" />
                                                        <defs>
                                                            <pattern id="pattern0_302_972" patternContentUnits="objectBoundingBox" width="1" height="1">
                                                                <use xlink:href="#image0_302_972" transform="scale(0.00520833)" />
                                                            </pattern>
                                                            <image id="image0_302_972" width="192" height="192" preserveAspectRatio="none" xlink:href="data:image/png;base64,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" />
                                                        </defs>
                                                    </svg>
                                                    <span class="title1 color-content-primary">Google meet</span>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="session-actions">
                                            <div class="buttons-wrapper d-flex">
                                                <a href="<?= $staff_url; ?>" class="repeat-session btn btn-style-brand btn-size-m grow-1 mr-1">
                                                    <span>Repeat sesson</span>
                                                </a>
                                                <?= $invoice_download_link; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="overlay"></div>
                                </div>
                            </article>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </section>

        <section id="book-session-only-mobile" class="pt-3 pb-3 pl-5 pr-5">
            <a href="#" class="btn btn-size-m btn-style-brand">
                <span>Book a session</span>
            </a>
        </section>
    </div>
    <script>
        // Make sure ajaxurl is available
        if (typeof ajaxurl === 'undefined') {
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        }

        jQuery("#dashboard-appointments a").addClass('active')
        jQuery(document).ready(function() {})

        jQuery(".daroon2-create-invoice").click(async function() {
            const paymentId = jQuery(this).attr("data-payment-id")
            const thisElem = jQuery(this);
            const thisPar = thisElem.parent();

            thisElem.addClass("loading");
            try {
                const formData = new FormData();
                formData.append('action', 'get_bookly_payment_info');
                formData.append('payment_id', paymentId);
                formData.append('nonce', '<?php echo wp_create_nonce('daroon2_payment_nonce'); ?>');

                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    let invoiceLink = '<?= get_site_url() . '/wp-content/uploads/jung_files/'; ?>';
                    invoiceLink += `${result.name}.pdf`;
                    const downloadReceipt = `<a href='${invoiceLink}' class='btn btn-style-outline btn-size-m grow-1'><span>Download receipt</span></a>`;
                    thisPar.append(downloadReceipt)

                    thisElem.css("display", "none")
                    thisElem.removeClass("loading")
                } else {
                    console.error('Error:', result.data);
                    // alert('Error: ' + result.data);
                }
            } catch (error) {
                console.error('AJAX error:', error);
                // alert('Failed to get payment information');
            }
        })
    </script>
<?php
    return '';
}

add_shortcode('daroon2_dashboard_appointments', 'daroon2_dashboard_appointments_func');
