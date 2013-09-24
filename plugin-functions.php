<?php

/*
 * Custom functions
 */


/* ======================================================================
 * Get page ID by slug
 * ====================================================================== */
function wp2sl_get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}


/* ======================================================================
 * Get plugin base url
 * ====================================================================== */
function wp2sl_url() {
    return plugins_url() . '/2stage-login';
}


/* ======================================================================
 * Checks whether the image link is broken
 * ====================================================================== */
function wp2sl_url_exists($url) {
    $hdrs = @get_headers($url);
    return is_array($hdrs) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$hdrs[0]) : false;
}


/* ======================================================================
 * Generates random string (Alphanumeric, numeric etc)
 * ====================================================================== */
function wp2sl_str_rand($length = 32, $seeds = 'alphanum') {
    // Possible seeds
    $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
    $seedings['numeric'] = '0123456789';
    $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';
    $seedings['hexidec'] = '0123456789abcdef';

    // Choose seed
    if (isset($seedings[$seeds])) {
        $seeds = $seedings[$seeds];
    }

    // Seed generator
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);

    // Generate
    $str = '';
    $seeds_count = strlen($seeds);

    for ($i = 0; $length > $i; $i++) {
        $str .= $seeds{mt_rand(0, $seeds_count - 1)};
    }

    return $str;
}


/* ======================================================================
 * Generates hashed password for generic login
 * ====================================================================== */
function wp2sl_generate_gl_password($pwd) {
    if (defined('CRYPT_SHA512') && CRYPT_SHA512) {
        $salt = wp2sl_str_rand();
        $algo = '6'; // Algo for CRYPT_SHA512
        $rounds = '5000';

        $crypt_salt = '$' . $algo . '$rounds=' . $rounds . '$' . $salt;

        $hashed_pwd = crypt($pwd, $crypt_salt);
    } else {
        $hashed_pwd = crypt($pwd);
    }
    
    return $hashed_pwd;
}


/* ======================================================================
 * Validates generic login password
 * ====================================================================== */
function wp2sl_check_password($pwd, $hashed_pwd) {
    if (crypt($pwd, $hashed_pwd) == $hashed_pwd) {
        return TRUE;
    } else {
        return FALSE;
    }
}


/* ======================================================================
 * Adds Generic Login feature in wp-admin user profiles
 * ====================================================================== */
add_action( 'show_user_profile', 'wp2sl_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'wp2sl_extra_user_profile_fields' );
function wp2sl_extra_user_profile_fields( $user ) {
?>
    <?php if (!empty($_SESSION['gl_update_error'])) { ?>
        <div class="error gl-update-error">
            <?php foreach ($_SESSION['gl_update_error'] as $e) { ?>
            <p><strong>Generic Login ERROR</strong>: <?php _e($e); ?></p>
            <?php } ?>
            <h4><a href='#gl-head'>Take me there</a></h4>
        </div>
    <?php } unset($_SESSION['gl_update_error']); ?>
    <h3 id="gl-head"><img src="<?php echo wp2sl_url(); ?>/images/icon-lock.png" style="margin-right:10px;"><?php _e("Generic Login Information", "blank"); ?></h3>
    <table class="form-table gl-admin-table">
        <tr>
        <th><label for="gl_admin_username"><?php _e("Generic Username"); ?></label></th>
            <td>
                <input type="text" name="gl_admin_username" id="gl_admin_username" class="regular-text" 
                    value="<?php echo esc_attr( get_the_author_meta( '_wp2sl_gl_username', $user->ID ) ); ?>" />
                <span class="description"><?php _e("Enter your login username for 2-stage login."); ?></span>
            </td>
        </tr>
        <tr>
        <th><label for="gl_admin_pwd"><?php _e("Generic Password"); ?></label></th>
            <td>
                <input type="password" name="gl_admin_pwd" id="gl_admin_pwd" class="password" size="20" />
                <span class="description"><?php _e("Enter new login password for 2-stage login."); ?></span>
                <br />
                <input type="password" name="gl_admin_pwd_2" id="gl_admin_pwd_2" class="password" size="20" />
                <span class="description"><?php _e("<strong>Re-enter password to match.</strong>"); ?></span>
                <br />
            </td>
        </tr>
    </table>
<?php
}


/* ======================================================================
 * Saves Generic Login data in wp-admin user profiles
 * ====================================================================== */
add_action( 'personal_options_update', 'wp2sl_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'wp2sl_save_extra_user_profile_fields' );
function wp2sl_save_extra_user_profile_fields( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user', $user_id ) ) {
        $gl_new_username = sanitize_text_field($_POST['gl_admin_username']);
        $gl_new_pwd = sanitize_text_field($_POST['gl_admin_pwd']);
        $gl_new_pwd_2 = sanitize_text_field($_POST['gl_admin_pwd_2']);
        $_SESSION['gl_update_error'] = '';
        
        if (!empty($gl_new_username)) {
            update_user_meta($user_id, '_wp2sl_gl_username', $gl_new_username);
            if (!empty($gl_new_pwd)) {
                if ($gl_new_pwd != $gl_new_pwd_2) {
                    $_SESSION['gl_update_error'][] = "Passwords doesn't match.";
                } else {
                    update_user_meta($user_id, '_wp2sl_gl_pwd', wp2sl_generate_gl_password($gl_new_pwd));
                }
            }
            
        }
        
        $saved = true;
  }
  return true;
}


/* ======================================================================
 * Create generic login data for users when the plugin is activated
 * ====================================================================== */
add_action ('user_register', 'wp2sl_active_gl_user_create');
function wp2sl_active_gl_user_create() {
    $new_gl_username = 'gl-'.wp2sl_str_rand(4);
    $new_gl_pwd = wp2sl_str_rand(8);
    
    $gl_pwd_hash = wp2sl_generate_gl_password($new_gl_pwd);
    
    $usr_email = sanitize_email($_POST['email']);
    $usr_login = sanitize_text_field($_POST['user_login']);
    $created_user_data = get_user_by('login', $usr_login);
    
    update_user_meta($created_user_data->ID, '_wp2sl_gl_username', $new_gl_username);
    update_user_meta($created_user_data->ID, '_wp2sl_gl_pwd', $gl_pwd_hash);
    
    if (get_option('admin_email')) {
        $header_email = " <".get_option('admin_email').">";
    } else {
        $header_email = "";
    }
    
    $headers = "From: Site Admin - ".get_bloginfo('name').$header_email;
    
    $subject = "New user registration for Generic Login - ".get_bloginfo('name');

    $msg  = "Hello User,\r\n\r\nThanks for registering. This email is generated by 2-Stage Login for a generic login which has been activated for your account: {$usr_login}. ";
    $msg .= "Please note the username and password to login through Generic Login into our website. ";
    $msg .= "If you can not login with the given credentials, then please contact admin of the website.";
    $msg .= "\r\n\r\nUsername: {$new_gl_username}";
    $msg .= "\r\nPassword: {$new_gl_pwd}";
    $msg .= "\r\n\r\n\r\nThanks!";

    @wp_mail($usr_email, $subject, $msg, $headers);
}


/* ======================================================================
 * Resets password for generic login
 * ====================================================================== */
function wp2sl_do_pwd_reset($email) {
    $new_gl_pwd = wp2sl_str_rand(8);
    $gl_pwd_hash = wp2sl_generate_gl_password($new_gl_pwd);
    $created_user_data = get_user_by('email', $email);

    update_user_meta($created_user_data->ID, '_wp2sl_gl_pwd', $gl_pwd_hash);
    
    $headers = "From: Site Admin - ".get_bloginfo('name')." <info@example.com>";
    
    $subject = "Password reset for Generic Login - ".get_bloginfo('name');

    $msg  = "Hello User,\r\n\r\nYour generic login password has been reset for your account";
    $msg .= "Please note the new password to login through Generic Login into our website. ";
    $msg .= "If you can not login with the given credentials, then please contact admin of the website.";
    $msg .= "\r\n\r\nNew password: {$new_gl_pwd}";
    $msg .= "\r\n\r\n\r\nThanks!";

    if (wp_mail($email, $subject, $msg, $headers)) {
        return true;
    } else {
        return false;
    }
}

// #FF8877
// #25CEFF - OLD