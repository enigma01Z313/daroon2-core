<?php

// Include Google API Client
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/vendor/autoload.php';



function daroon2_google_login_init() {
    if (!session_id()) {
        session_start();
    }

    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    
    // Check if Google API Client is installed
    if (!class_exists('Google_Client')) {
        // Add admin notice if Google Client library is not installed
        add_action('admin_notices', function() {
            echo '<div class="error"><p>Google API Client Library is not installed. Please run: <code>composer require google/apiclient:^2.12.1</code> in the plugin directory.</p></div>';
        });
        return;
    }
}
add_action('init', 'daroon2_google_login_init');

// Register REST API endpoints
function daroon2_register_google_endpoints() {
    register_rest_route('daroon2/v1', '/google-login', array(
        'methods' => 'GET',
        'callback' => 'daroon2_handle_google_login',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('daroon2/v1', '/google-callback', array(
        'methods' => 'GET',
        'callback' => 'daroon2_handle_google_callback',
        'permission_callback' => '__return_true'
    ));
}
add_action('rest_api_init', 'daroon2_register_google_endpoints');

function daroon2_handle_google_login() {
    try {
        $client = new Google_Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);
        $client->addScope('email');
        $client->addScope('profile');

        // Store the referrer URL in session for redirect after login
        if (!session_id()) {
            session_start();
        }
        $_SESSION['google_auth_redirect'] = strtok(wp_get_referer(), '?');

        $auth_url = $client->createAuthUrl();
        wp_redirect($auth_url);
        exit;
    } catch (Exception $e) {
        error_log('Google Login Error: ' . $e->getMessage());
        wp_redirect(home_url('/login?error=google_auth_failed1'));
        exit;
    }
}

function daroon2_handle_google_callback(WP_REST_Request $request) {
    try {
        $client = new Google_Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(GOOGLE_REDIRECT_URI);

        if ($request->get_param('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($request->get_param('code'));
            $client->setAccessToken($token);

            // Get user info
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            $email = $google_account_info->email;
            $name = $google_account_info->name;

            // Check if user exists
            $user = get_user_by('email', $email);
      
            if (!$user) {
                // Create new user
                $username = $email;
                $random_password = wp_generate_password();

                $user_id = wp_create_user($username, $random_password, $email);

          
                // wp_redirect(home_url('?userId='.$user_id));
                // exit;

                if (!is_wp_error($user_id)) {
                    $user = new WP_User($user_id);
                    $user->set_role('subscriber');
                    
                    // Add user meta
                    update_user_meta($user_id, 'first_name', $google_account_info->givenName);
                    update_user_meta($user_id, 'last_name', $google_account_info->familyName);
                    update_user_meta($user_id, 'google_user_id', $google_account_info->id);
                }
            }

            if ($user && !is_wp_error($user)) {
                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                do_action('wp_login', $user->user_login, $user);
                
                // Get the stored redirect URL
                if (!session_id()) {
                    session_start();
                }

                $redirect_url = $_SESSION['google_auth_redirect'] ?? '';
                unset($_SESSION['google_auth_redirect']); // Clear the stored URL

                // Check if the redirect URL is a login/signup page
                $login_pages = array(
                    home_url('/signup/'),
                    home_url('/signin/'),
                );
                $has_full_name = get_user_meta($user->ID, 'full_name', true);

                if (empty($redirect_url)){
                    $redirect_url_goto = home_url('/');
                }else if( in_array($redirect_url, $login_pages)) {
                    if( $has_full_name == '' ) $redirect_url_goto = $redirect_url. '?complete_profile=true&user_id=' . $user->ID;
                    else $redirect_url_goto = home_url('/account/');
                }else if( strpos($redirect_url, 'team') != 'false' ){
                    $redirect_url_goto = $redirect_url. '?goto=therapist-bookly-form';

                    if( $has_full_name == '' ) {
                        $redirect_url_goto .= '&complete_profile=true&user_id=' . $user->ID;
                    }
                }
                
                wp_redirect($redirect_url_goto);
                exit;
            }
        }
    } catch (Exception $e) {
        error_log('Google Callback Error: ' . $e->getMessage());
    }

    wp_redirect(home_url('/login?error=google_auth_failed2'));
    exit;
}

// Add Google login button to the login form
function daroon2_add_google_login_button($text = "Sign in with Google") {
    ob_start();
    ?>
    <div class="google-login-container w-100 googleSignnintwith Googbwxt-center mb-5">
        <a href="<?php echo esc_url(rest_url('daroon2/v1/google-login')); ?>" class="btn btn-style-outline btn-size-l">
            <svg width="18" height="18" viewBox="0 0 18 18" style="margin-right: 8px;">
                <path fill="#4285f4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.875 2.684-6.615z"></path>
                <path fill="#34a853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z"></path>
                <path fill="#fbbc05" d="M3.964 10.71c-.18-.54-.282-1.117-.282-1.71s.102-1.17.282-1.71V4.958H.957C.347 6.173 0 7.548 0 9s.348 2.827.957 4.042l3.007-2.332z"></path>
                <path fill="#ea4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.958L3.964 7.29C4.672 5.163 6.656 3.58 9 3.58z"></path>
            </svg>
            <span style="--padding-x-count: 2"><?php echo esc_html($text); ?></span>
        </a>
    </div>
    <?php
    return ob_get_clean();
} 