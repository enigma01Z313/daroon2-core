<?php

use Spipu\Html2Pdf\Html2Pdf;

// Add AJAX handler for getting Bookly payment information
add_action('wp_ajax_nopriv_get_bookly_payment_info', 'daroon2_get_bookly_payment_info');
add_action('wp_ajax_get_bookly_payment_info', 'daroon2_get_bookly_payment_info');

function daroon2_get_new_jung_id () {
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "jung_invoices ORDER BY id DESC LIMIT 1;");
    $no = 13319;
    foreach ($rows as $row) {
        $no += $row->id;
    }

    return $no;
}

function daroon2_get_bookly_payment_info() {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
        return;
    }

    // Get payment ID from POST data
    $payment_id = intval($_POST['payment_id']);
    
    if (!$payment_id) {
        wp_send_json_error('Invalid payment ID');
        return;
    }

    global $wpdb;
    $payment_table = $wpdb->prefix . 'bookly_payments';
    $appointments_table = $wpdb->prefix . 'bookly_appointments';
    $customer_appointments_table = $wpdb->prefix .'bookly_customer_appointments';
    $customers_table = $wpdb->prefix . 'bookly_customers';
    $jung_invoices_table = $wpdb->prefix . 'jung_invoices';
    $jung_invoice_users_table = $wpdb->prefix . 'jung_invoice_users';
    
    $sql = "SELECT 
            P.id as payment_id,
            P.created_at,
            P.paid,
            P.total,
            P.status,
            P.type,
            P.details,
            CA.id as customer_appointment_id,
            CA.appointment_id,
            CA.status as appointment_status,
            CA.time_zone,
            CA.customer_id,
            A.start_date,
            A.end_date,
            C.full_name AS customer_full_name,
            C.email,
            C.phone
        FROM $payment_table P
        LEFT JOIN $customer_appointments_table CA ON P.id = CA.payment_id
        LEFT JOIN $appointments_table A ON CA.appointment_id = A.id
        LEFT JOIN $customers_table C ON C.id = CA.customer_id
        LEFT JOIN $jung_invoice_users_table JIU ON JIU.customer_id = CA.customer_id
        WHERE P.id = %d";
    $query = $wpdb->prepare($sql, $payment_id);
    $payment_info = $wpdb->get_results($query);
            
    // $response = array(
    //     'success'       => true,
    //     'payment_id'    => $payment_id,
    //     'payment_info'  => $payment_info,
    //     'payment_created_at' => $payment_created_at,
    //     'sql'  =>  $sql
    // );
    // wp_send_json($response);

    if (empty($payment_info)) {
        wp_send_json_error('Payment not found');
        return;
    }
    $payment_info = $payment_info[0];

    $full_name = $payment_info->customer_full_name;
    $customer_id = $payment_info->customer_id;
    $time_zone = $payment_info->time_zone;
    $created_at = $payment_info->created_at;
    $end_date = $payment_info->end_date;
    $paid = $payment_info->paid;
    $details = json_decode($payment_info->details);

    $payment_created_at = new DateTime($created_at, new DateTimeZone('Asia/Tehran'));
    $payment_created_at->setTimezone(new DateTimeZone($time_zone));
    $payment_created_at = $payment_created_at->format("Y-m-d");

    $submitted_on = new DateTime($end_date, new DateTimeZone('Asia/Tehran'));
    $submitted_on->setTimezone(new DateTimeZone($time_zone));
    $submitted_on = $submitted_on->format("d/m/Y");
    $no = daroon2_get_new_jung_id();


    if ($currency != 0) {
        $access_key = "3537ee5cfb09ac6d1a3d2d874de56cd9";
        $url = "http://api.exchangeratesapi.io/v1/" . $payment_created_at . "?access_key=" . $access_key . "&base=USD&symbols=EUR,CAD";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $wpdb->insert($wpdb->prefix . "jung_rate_logs", [
            "payment_id" => $payment_id,
            "response" => $response,
            "created_at" => date("Y-m-d H:i:s")
        ], ["%d", "%s", "%s"]);
        $response = json_decode($response);
    }

    $rate = 0;
    $sign = "";
    $currency_acronyms = "";
    switch ($currency) {
        case 0:
            $currency_acronyms = "USD";
            $sign = "$";
            $rate = 1;
            break;
        case 1:
            $currency_acronyms = "CAD";
            $sign = "C$";
            if ($response->success) {
                $rate = $response->rates->CAD;
            }
            break;
        case 2:
            $currency_acronyms = "EUR";
            $sign = "€";
            if ($response->success) {
                $rate = $response->rates->EUR;
            }
            break;
    }


    $pdf = '<page backtop="14mm" backbottom="14mm" backleft="10mm" backright="10mm">
                <table style="color:#49496b;width:100%;font-size:10px;">
                    <tr>
                        <td style="font-size:10px;font-weight:bold;">Daroon Wellness Inc.</td>
                    </tr>
                    <tr>
                        <td>329 Howe St, Unit #740</td>
                    </tr>
                    <tr>
                        <td>Vancouver, BC, Canada V6C 3N2</td>
                    </tr>
                    <tr>
                        <td>(604) 245-7244</td>
                    </tr>
                </table>
                <br/>
                <table style="color:#49496b;width:100%;">
                    <tr>
                        <td style="width:50%;font-size:34px;font-weight:bold;">Invoice</td>
                        <td style="width:50%;font-size:26px;font-weight:bold;">PAID</td>
                    </tr>
                    <tr>
                        <td style="width:50%;font-size:12px;font-weight:bold;">Submitted on ' . $submitted_on . '</td>
                        <td style="width:50%;"></td>
                    </tr>
                </table>
                <br/>
                <table style="color:#49496b;width:100%;">
                    <tr style="font-size:12px;font-weight:bold;">
                        <td style="width:50%;">Invoice for</td>
                        <td style="width:50%;">Invoice #</td>
                    </tr>
                    <tr style="font-size:10px;">
                        <td style="width:50%;">' . $full_name . '</td>
                        <td style="width:50%;">DRN' . $no . '</td>
                    </tr>
                </table>
                <br/>
                <table style="color:#49496b;width:100%;">
                    <tr style="font-size:10px;">
                        <td class="width:50%;">' . $email . '</td>
                        <td class="width:50%;"></td>
                    </tr>
                    <tr style="font-size:10px;">
                        <td class="width:50%;">' . $phone . '</td>
                        <td class="width:50%;"></td>
                    </tr>
                </table>
                <br/>
                <br/>
                <table style="color:#49496b;width:100%;">
                    <thead>
                        <tr style="font-size:12px;">
                            <th style="width:35%;height:35px;line-height:15px;">Description</th>
                            <th style="width:35%;height:35px;line-height:15px;">Therapist</th>
                            <th style="width:15%;height:35px;line-height:15px;">Session Date</th>
                            <th style="width:15%;height:35px;line-height:15px;">Price ' . $currency_acronyms . '</th>
                        </tr>
                    </thead>
                    <tbody>';

    
    $docx = '<html>
                        <head>
                            <meta charset="UTF-8"/>
                        </head>
                        <body style="color:#49496b;">
                            <table style="width:100%;border:none;">
                                <tr>
                                    <td style="font-size:10px;font-weight:bold;">Daroon Wellness Inc.</td>
                                </tr>
                                <tr>
                                    <td style="font-size:10px;">329 Howe St, Unit #740</td>
                                </tr>
                                <tr>
                                    <td style="font-size:10px;">Vancouver, BC, Canada V6C 3N2</td>
                                </tr>
                                <tr>
                                    <td style="font-size:10px;">(604) 245-7244</td>
                                </tr>
                            </table>
                            <br/>
                            <table style="width:100%;border:none;">
                                <tr>
                                    <td style="font-size:34px;font-weight:bold;width:50%;">Invoice</td>
                                    <td style="font-size:26px;font-weight:bold;width:50%;">PAID</td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;width:50%;">Submitted on ' . $submitted_on . '</td>
                                    <td style="width: 50%;"></td>
                                </tr>
                            </table>
                            <br/>
                            <table style="width:100%;border:none;">
                                <tr style="font-size:12px;">
                                    <td style="font-weight:bold;width:50%;">Invoice for</td>
                                    <td style="font-weight:bold;width:50%;">Invoice #</td>
                                </tr>
                                <tr style="font-size:10px;">
                                    <td style="width:50%;">' . $full_name . '</td>
                                    <td style="width:50%;">DRN' . $no . '</td>
                                </tr>
                            </table>
                            <br/>
                            <table style="width:100%;border:none;">
                                <tr>
                                    <td style="font-size:10px;width:50%;">' . $email . '</td>
                                    <td style="width:50%;"></td>
                                </tr>
                                <tr>
                                    <td style="font-size:10px;width:50%;">' . $phone . '</td>
                                    <td style="width:50%;"></td>
                                </tr>
                            </table>
                            <br/>
                            <table style="width: 100%;border:none;">
                                <thead>
                                    <tr style="font-size:12px;font-weight:bold;">
                                        <th style="width:30%;">Description</th>
                                        <th style="width:35%;">Therapist</th>
                                        <th style="width:20%;">Session Date</th>
                                        <th style="width:15%;">Price ' . $currency_acronyms . '</th>
                                    </tr>
                                </thead>
                                <tbody>';
    $c = 0;
    $total = 0;
    

    // Extract all customer appointment IDs from $details->items
    foreach ($details->items as $item) {
        $query = "
            SELECT 
                jsi.var1, jsi.var2, jsi.var3, jsi.var4,
                ba.staff_id
            FROM {$wpdb->prefix}bookly_customer_appointments bca
            JOIN {$wpdb->prefix}bookly_appointments ba ON bca.appointment_id = ba.id
            JOIN {$wpdb->prefix}jung_staff_info jsi ON ba.staff_id = jsi.staff_id
            WHERE bca.id = %d
        ";

        $result = $wpdb->get_row($wpdb->prepare($query, $item->ca_id));

        $info = '';
        if ($result) {
            foreach (['var1', 'var2', 'var3', 'var4'] as $var) {
                if (!empty($result->$var)) {
                    $info .= esc_html($result->$var) . "<br/>";
                }
            }
        }

        // Format date and generate rows (same as before)
        $session_date = new DateTime($item->appointment_date, new DateTimeZone('Asia/Tehran'));
        $session_date->setTimezone(new DateTimeZone($time_zone));
        $formatted_date = $session_date->format("d/m/Y");

        $service_price = round($item->service_price * $rate, 2);
        $total += $service_price;

        $row_style = ($c % 2 == 0) ? 'background-color:#f3f3f3;' : '';
        $c++;

        $pdf .= '<tr style="font-size:10px;' . $row_style . '">
                    <td style="width:35%;height:35px;line-height:15px;">Psychotherapy Session - 45 minutes</td>
                    <td style="width:35%;height:35px;line-height:15px;">' . $info . '</td>
                    <td style="width:15%;height:35px;line-height:15px;">' . $formatted_date . '</td>
                    <td style="width:15%;height:35px;line-height:15px;">' . $sign . number_format($service_price, 2) . '</td>
                </tr>';

        $docx .= '<tr style="font-size:10px;' . $row_style . '">
                    <td style="width:35%;">Psychotherapy Session - 45 minutes</td>
                    <td style="width:35%;">' . $info . '</td>
                    <td style="width:15%;">' . $formatted_date . '</td>
                    <td style="width:15%;">' . $sign . number_format($service_price, 2) . '</td>
                </tr>';
    }



    $total = floor(($total * 100)) / 100;
    $paid = number_format($payment_info->paid, "6");
    $paid *= $rate;
    $paid = floor(($paid * 100)) / 100;
    $discount = $total - $paid;



    $pdf .= '</tbody>
        </table>
        <br/>
        <br/>
        <table style="color:#49496b;width:100%;text-align:right;">
            <tr style="font-size:10px;">
                <td style="width:35%;"></td>
                <td style="width:35%;"></td>
                <td style="width:15%;">Subtotal:</td>
                <td style="width:15%;font-weight:bold;">' . $sign . $total . '</td>
            </tr>
            <tr style="font-size:10px;">
                <td style="width:35%;"></td>
                <td style="width:35%;"></td>
                <td style="width:15%;">Adjustments:</td>
                <td style="width:15%;font-weight:bold;">';
    if ($discount != 0) {
        $pdf .= "- " . $sign . $discount;
    }
    $pdf .= '</td>
            </tr>
            <tr style="font-size:10px;">
                <td style="width:35%;"></td>
                <td style="width:35%;"></td>
                <td style="width:15%;">Total:</td>
                <td style="width:15%;font-weight:bold;">' . $sign . $paid . '</td>
            </tr>
        </table>
        <page_footer>
            <table style="color:#49496b;width:100%;margin:25px;font-size:10px;">
                <tr>
                    <td>Daroon wellness Inc.</td>
                </tr>
                <tr>
                    <td>Business #: 797154804</td>
                </tr>
            </table>
        </page_footer></page>';


    $docx .= '</tbody>
                                </table>
                                <br/>
                                <table style="width:100%;font-size:10px;">
                                    <tr>
                                        <td style="width:35%;"></td>
                                        <td style="width:35%;"></td>
                                        <td style="width:15%;text-align:right;">Subtotal:</td>
                                        <td style="width:15%;font-weight:bold;text-align:right;">' . $sign . $total . '</td>
                                    </tr>
                                    <tr>
                                        <td style="width:35%;"></td>
                                        <td style="width:35%;"></td>
                                        <td style="width:15%;text-align:right;">Adjustments:</td>
                                        <td style="width:15%;font-weight:bold;text-align:right;">';
    if ($discount != 0) {
        $docx .= "- " . $sign . $discount;
    }
    $docx .= '</td>
                    </tr>
                    <tr>
                        <td style="width:35%;"></td>
                        <td style="width:35%;"></td>
                        <td style="width:15%;text-align:right;">Total:</td>
                        <td style="width:15%;font-size:14px;font-weight:bold;text-align:right;">' . $sign . $paid . '</td>
                    </tr>
                </table>
            </body>
        </html>';


    



    
    
    
    
    
    // echo "cccccccc"




    $path = WP_CONTENT_DIR . "/uploads/jung_files/";
    $file_name = str_replace(" ", "", $full_name) . '-' . $customer_id . $payment_id . "-" . date("His");
    $html2pdf = new Html2Pdf('P', 'A4', 'en', true, 'UTF-8', array(0, 0, 0, 0));
    $html2pdf->setDefaultFont('dejavusans');
    $html2pdf->writeHTML($pdf);
    $html2pdf->output($path . $file_name . ".pdf", "F");


    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $phpWord->setDefaultFontName("Aria");
    $section = $phpWord->addSection();
    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $docx, false, false);
    $footer = $section->addFooter();
    $textrun = $footer->addTextRun();
    $style['name'] = "Aria";
    $style['size'] = 7.5;
    $style['color'] = "49496b";
    $textrun->addText("Daroon wellness Inc.", $style);
    $textrun->addTextBreak();
    $textrun->addText("Business #: 797154804", $style);
    $phpWord->save($path . $file_name . ".docx", "Word2007");
    
    
    $wpdb->insert($wpdb->prefix . "jung_invoices", [
        "customer_id" => $customer_id,
        "payment_id" => $payment_id,
        "name" => $file_name,
        "created_at" => date("Y-m-d H:i:s")
    ], ["%d", "%d", "%s", "%s"]);


    $response = array(
        'success'       => true,
        'payment_id'    => $payment_id,
        'payment_info'  => $payment_info,
        'name'          => $file_name,
        'path'          => $path,
        'files' => [
            $pdf,
            $docx
        ]
    );
    wp_send_json($response);
}
