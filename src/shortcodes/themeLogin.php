<?php

function daroon2_theme_login_shortcode() {
    ob_start();

    // Check if user is already logged in
    if (is_user_logged_in()) {
        return 'You are already logged in.';
    }

    $error = '';
    $success = '';

    // Handle form submission
    if (isset($_POST['login_submit'])) {
        // Verify reCAPTCHA
        $recaptcha_secret = '6LdDTCwpAAAAAPvDtgLs88mqcSLbaTS-tjzhfQJz';
        $recaptcha_response = $_POST['g-recaptcha-response'];
        
        $verify_response = wp_remote_get("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
        
        if (!is_wp_error($verify_response)) {
            $response_body = wp_remote_retrieve_body($verify_response);
            $response_data = json_decode($response_body);
            
            if ($response_data->success) {
                $email = sanitize_email($_POST['user_email']);
                
                if (email_exists($email)) {
                    // Generate OTP
                    $otp = wp_rand(100000, 999999);
                    set_transient('login_otp_' . $email, $otp, 5 * MINUTE_IN_SECONDS);
                    
                    // Send OTP email
                    $to = $email;
                    $subject = 'Login OTP';
                    $message = 'Your OTP for login is: ' . $otp;
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    
                    if (wp_mail($to, $subject, $message, $headers)) {
                        $success = 'OTP has been sent to your email.';
                    } else {
                        $error = 'Failed to send OTP. Please try again.';
                    }
                } else {
                    // Create new user account
                    $username = $email;
                    $random_password = wp_generate_password();
                    $user_id = wp_create_user($username, $random_password, $email);
                    
                    if (!is_wp_error($user_id)) {
                        // Set user role to subscriber
                        $user = new WP_User($user_id);
                        $user->set_role('subscriber');
                        
                        // Generate OTP for new user
                        $otp = wp_rand(100000, 999999);
                        set_transient('login_otp_' . $email, $otp, 5 * MINUTE_IN_SECONDS);
                        
                        // Send OTP email
                        $to = $email;
                        $subject = 'Login OTP';
                        $message = 'Your OTP for login is: ' . $otp;
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        
                        if (wp_mail($to, $subject, $message, $headers)) {
                            $success = 'Account created and OTP has been sent to your email.';
                        } else {
                            $error = 'Account created but failed to send OTP. Please try again.';
                        }
                    } else {
                        $error = 'Failed to create account. Please try again.';
                    }
                }
            } else {
                $error = 'reCAPTCHA verification failed.';
            }
        }
    }

    // Handle OTP verification
    if (isset($_POST['verify_otp'])) {
        $email = sanitize_email($_POST['user_email']);
        $submitted_otp = sanitize_text_field($_POST['otp']);
        $stored_otp = get_transient('login_otp_' . $email);
        
        if ($stored_otp && $submitted_otp == $stored_otp) {
            $user = get_user_by('email', $email);
            if ($user) {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $user->user_login, $user);
                
                wp_redirect(home_url());
                exit;
            }
        } else {
            $error = 'Invalid OTP. Please try again.';
        }
    }
    ?>

    <div class="login-form-container">
        <?php if ($error) : ?>
            <div class="error-message"><?php echo esc_html($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success) : ?>
            <div class="success-message"><?php echo esc_html($success); ?></div>
        <?php endif; ?>

        <?php if (!$success) : ?>
            <form method="post" id="login-form">
                <div class="form-group">
                    <label for="user_email">Email Address</label>
                    <input type="email" name="user_email" id="user_email" required>
                </div>
                
                <input type="hidden" id="recaptcha_response" name="g-recaptcha-response">
                <button type="submit" name="login_submit" class="btn btn-style-black btn-size-l">Send OTP</button>
            </form>
        <?php else : ?>
            <form method="post" id="otp-form">
                <div class="form-group">
                    <label for="otp">Enter OTP</label>
                    <input type="text" name="otp" id="otp" required>
                </div>
                
                <input type="hidden" name="user_email" value="<?php echo esc_attr($_POST['user_email']); ?>">
                <button type="submit" name="verify_otp" class="btn">Verify OTP</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?render=6LdDTCwpAAAAAKer8tgz1NyKfNZrnAKE46khyGtB"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LdDTCwpAAAAAKer8tgz1NyKfNZrnAKE46khyGtB', {action: 'login'})
                .then(function(token) {
                    document.getElementById('recaptcha_response').value = token;
                });
        });
    </script>

    <style>
        .login-form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }
        .success-message {
            color: #28a745;
            margin-bottom: 15px;
        }
    </style>

    <?php
    return ob_get_clean();
}
add_shortcode('daroon2_theme_login', 'daroon2_theme_login_shortcode');

function daroon2_redirect_after_logout() {
    wp_safe_redirect(home_url('/signin'));
    exit();
}
add_action('wp_logout', 'daroon2_redirect_after_logout');
