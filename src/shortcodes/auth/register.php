<?php

require_once __DIR__ . '/googleAuth.php';

function daroon2_theme_register_shortcode()
{
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
                    // Store remember me preference
                    set_transient('login_remember_' . $email, isset($_POST['remember_me']), 5 * MINUTE_IN_SECONDS);

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
                        // Store remember me preference
                        set_transient('login_remember_' . $email, isset($_POST['remember_me']), 5 * MINUTE_IN_SECONDS);

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
                // Get stored remember me preference
                $remember = get_transient('login_remember_' . $email);
                
                // Check if this is a new user who needs to provide additional info
                $has_full_name = get_user_meta($user->ID, 'full_name', true);
                
                if (!$has_full_name && isset($_POST['full_name']) && isset($_POST['birth_date'])) {
                    // Save the additional information
                    $full_name = sanitize_text_field($_POST['full_name']);
                    $birth_date = sanitize_text_field($_POST['birth_date']);
                    
                    update_user_meta($user->ID, 'full_name', $full_name);
                    update_user_meta($user->ID, 'birth_date', $birth_date);

                    // Log the user in
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID, $remember);
                    do_action('wp_login', $user->user_login, $user);

                    wp_redirect(home_url('/account'));
                    exit;
                } else if (!$has_full_name) {
                    // Show the additional information form
                    $success = 'verify_success';
                } else {
                    // Existing user - just log them in
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID, $remember);
                    do_action('wp_login', $user->user_login, $user);

                    wp_redirect(home_url());
                    exit;
                }
            }
        } else {
            $error = 'Invalid OTP. Please try again.';
        }
    }
    ?>

    <div class="login-form-container w-100 d-flex flex-wrap pt-4" style="margin-left: auto; margin-right: auto;">
        <?php if ($error) : ?>
            <div class="error-message title2 mb-5 w-100 text-center"><?php echo esc_html($error); ?></div>
        <?php endif; ?>

        <?php /*if ($success) : ?>
            <div class="success-message title2 mb-5 w-100 text-center"><?php echo esc_html($success); ?></div>
        <?php endif; */ ?>

        <?php if (!$success) : ?>
            <h1 class="title3 mb-5 w-100 text-center">Sign up</h1>

            <form method="post" id="login-form" class="mb-2 w-100 d-flex flex-wrap justify-content-center">
                <div class="mb-2 w-100">
                    <label for="user_email" class="title2 mb-1 w-100 pr-3 pl-3" style="display: block;">Email Address</label>
                    <input type="email" name="user_email" id="user_email" class="input w-100 p-2 body2" placeholder="Email Address" required>
                </div>

                <div class="mb-5 w-100">
                    <label class="checkbox-container ">
                        <input type="checkbox" name="remember_me" id="remember_me">
                        <span class="checkmark"></span>
                        <span class="title1 color-content-secondary">Remember me</span>
                    </label>
                </div>

                <input type="hidden" id="recaptcha_response" name="g-recaptcha-response">
                <button type="submit" name="login_submit" class="btn btn-style-black btn-size-l w-100">Sign up</button>
            </form>

            <p class="title1 color-content-secondary mb-2 w-100 text-center">Or</p>
            
            <?php echo daroon2_add_google_login_button("Sign up with Google"); ?>

            <p class="title1 color-content-secondary mb-6 w-100 text-center">
                Already have an account? <a class="color-action-ember" href="<?php echo home_url(); ?>/signin" style="text-decoration: none;">Sign in</a>
            </p>
        <?php elseif ($success === 'verify_success') : ?>
            <h1 class="title3 mb-3 w-100 text-center">Complete registration</h1>
            <p class="body1 color-content-secondary mb-5 w-100 text-center">Fill in a few details to finish setting up your account.</p>
            
            <form class="w-100 mb-5 d-flex flex-wrap justify-content-center" method="post" id="profile-form">
                <div class="mb-5 w-100">
                    <label class="title2 mb-1 pl-3 pr-3" for="full_name" style="display: block;">Full Name</label>
                    <input type="text" name="full_name" id="full_name" placeholder="Enter your full name" class="input w-100 p-2 body2" required>
                </div>

                <div class="mb-5 w-100">
                    <label class="title2 mb-1 pl-3 pr-3" for="birth_date" style="display: block;">Date of birth</label>
                    <input type="date" name="birth_date" id="birth_date" class="input w-100 p-2 body2" required>
                </div>

                <input type="hidden" name="otp" value="<?php echo esc_attr($submitted_otp); ?>">
                <input type="hidden" name="user_email" value="<?php echo esc_attr($email); ?>">
                <button type="submit" name="verify_otp" class="btn btn-style-black btn-size-l">Continue</button>
            </form>

            <p class="body1 color-content-secondary mb-3 w-100 text-center">We use this information to personalize your experience and keep your account secure.</p>
        <?php else : ?>
            <div class="w-100 d-flex mb-5">
                <a href="<?php echo home_url(); ?>/signup" class="btn btn-style-plain btn-size-l">
                    <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.7012 10.3114H5.77699L10.1678 5.92055C10.4337 5.65472 10.4337 5.21472 10.1678 4.94889C9.90199 4.68305 9.46199 4.68305 9.19616 4.94889L3.63199 10.5131C3.36616 10.7789 3.36616 11.2189 3.63199 11.4847L9.19616 17.0489C9.33366 17.1864 9.50783 17.2506 9.68199 17.2506C9.85616 17.2506 10.0395 17.1864 10.1678 17.0489C10.4337 16.7831 10.4337 16.3431 10.1678 16.0772L5.77699 11.6864H19.7012C20.077 11.6864 20.3887 11.3747 20.3887 10.9989C20.3887 10.6231 20.077 10.3114 19.7012 10.3114Z" fill="black"/>
                    </svg>
                    <span>Back</span>
                </a>
            </div>

            <h1 class="title3 mb-3 w-100 text-center">Enter the verification code</h1>
            <p class="body1 color-content-secondary mb-5 w-100 text-center">We've sent a 6-digit code to your email. Please enter it below to verify your identity.</p>
            <p class="title1 mb-5 w-100 text-center"><?php echo esc_html($_POST['user_email']); ?></p>
            <form class="w-100 mb-5 d-flex flex-wrap justify-content-center" method="post" id="otp-form">
                <div class="mb-5 w-100">
                    <label class="title2 mb-1 pl-3 pr-3" for="otp" style="display: block;">Verification code</label>
                    <input type="text" name="otp" id="otp" placeholder="e.g. 123456" class="input w-100 p-2 body2" pattern="[0-9]{6}" maxlength="6" inputmode="numeric" required>
                </div>

                <input type="hidden" name="user_email" value="<?php echo esc_attr($_POST['user_email']); ?>">
                <button type="submit" name="verify_otp" class="btn btn-style-black btn-size-l">Verify code</button>
            </form>

            <p class="title1 color-content-secondary mb-6 w-100 text-center color-content-secondary">
                Didn’t receive the code? <a class="color-content-primary">Resend code</a><a href="#" id="resend-link" class="color-content-primary" onclick="resendOTP(event, '<?php echo esc_attr($_POST['user_email']); ?>')">Resend code</a> <span id="timer" class="color-content-secondary"></span>
                <script>
                    let resendTimer;
                    let remainingTime = 0;

                    function updateTimer() {
                        const minutes = Math.floor(remainingTime / 60);
                        const seconds = remainingTime % 60;
                        document.getElementById('timer').textContent = `(${minutes}:${seconds < 10 ? '0' : ''}${seconds})`;
                        
                        if (remainingTime <= 0) {
                            clearInterval(resendTimer);
                            document.getElementById('timer').textContent = '';
                            document.getElementById('resend-link').style.pointerEvents = '';
                            document.getElementById('resend-link').style.opacity = '';
                        } else {
                            remainingTime--;
                        }
                    }

                    function startResendTimer() {
                        remainingTime = 120; // 2 minutes
                        document.getElementById('resend-link').style.pointerEvents = 'none';
                        document.getElementById('resend-link').style.opacity = '0.5';
                        clearInterval(resendTimer);
                        resendTimer = setInterval(updateTimer, 1000);
                        updateTimer();
                    }

                    function resendOTP(e, email) {
                        e.preventDefault();
                        grecaptcha.ready(function() {
                            grecaptcha.execute('6LdDTCwpAAAAAKer8tgz1NyKfNZrnAKE46khyGtB', {action: 'login'})
                            .then(function(token) {
                                var formData = new FormData();
                                formData.append('login_submit', '1');
                                formData.append('user_email', email);
                                formData.append('g-recaptcha-response', token);
                                
                                fetch(window.location.href, {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => {
                                    alert('A new verification code has been sent to your email.');
                                    startResendTimer();
                                })
                                .catch(error => {
                                    alert('Failed to resend code. Please try again.');
                                });
                            });
                        });
                    }
                </script>
            </p>
        <?php endif; ?>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?render=6LdDTCwpAAAAAKer8tgz1NyKfNZrnAKE46khyGtB"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LdDTCwpAAAAAKer8tgz1NyKfNZrnAKE46khyGtB', {
                    action: 'login'
                })
                .then(function(token) {
                    document.getElementById('recaptcha_response').value = token;
                });
        });
    </script>

    <style>
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
        }

        .success-message {
            color: #28a745;
            margin-bottom: 15px;
        }
        
        #login-form .btn,
        .google-login-container .btn,
        .btn[name="verify_otp"]{
            --height: 60px;
        }

        .google-login-container{
            display: flex;
            justify-content: center;
        }
        
        .google-login-container .btn{
            width: 100%;
            max-width: min(380px, 100%);
        }

        /* Checkbox styles */
        .checkbox-container {
            display: flex;
            align-items: center;
            position: relative;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            --size: 18px;
            --border-width: 2px;

            position: relative;
            height: var(--size);
            width: var(--size);
            background-color: #fff;
            border: var(--border-width) solid #bbb;
            border-radius: 4px;
            margin-right: 12px;
        }

        .checkbox-container:hover input ~ .checkmark {
            background-color: #f5f5f5;
        }

        .checkbox-container input:checked ~ .checkmark {
            background-color: #000;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 4px;
            top: 0;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
    </style>

<?php
    return ob_get_clean();
}
add_shortcode('daroon2_theme_register', 'daroon2_theme_register_shortcode');
