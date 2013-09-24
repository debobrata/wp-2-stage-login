<?php
/*
 * Template Name: Generic Login Page
 */

// Check if generic login is already done
if (isset($_SESSION['general_login']) || get_option('_wp2sl_gl_active') != "yes") {
    if (is_user_logged_in()) {
        wp_safe_redirect(admin_url());
    } else {
        wp_safe_redirect(wp_login_url());
    }
}


$_SESSION['wp2sl_login_error'] = false;
$_SESSION['wp2sl_login_error_msg'] = '';
$_SESSION['wp2sl_login_success'] = false;
$_SESSION['wp2sl_login_error_msg'] = '';

$submit_val         = sanitize_text_field($_POST['sub_generic_login']);
$submit_f_pwd_val   = sanitize_text_field($_POST['sub_g_frgt_pwd']);
$g_wp_username      = sanitize_text_field($_POST['g_wp_username']);
$g_username         = sanitize_text_field($_POST['g_username']);
$g_pwd              = sanitize_text_field($_POST['g_pwd']);

// Form post action
if (isset($submit_val) && $submit_val == "Login") {
    global $wpdb;
    
    $login_user = get_user_by('login', $g_wp_username);
    
    if ($login_user->ID) {
        if ((get_user_meta($login_user->ID, '_wp2sl_gl_username', true) == $g_username)
                && (wp2sl_check_password($g_pwd, get_user_meta($login_user->ID, '_wp2sl_gl_pwd', true)) === TRUE)) {
            $_SESSION['general_login'] = 1;
            $_SESSION['general_login_id'] = $login_user->id;
            
            $_SESSION['wp2sl_login_error'] = false;
            $_SESSION['wp2sl_login_error_msg'] = '';
            
            wp_safe_redirect(wp_login_url());
        } else {
            $_SESSION['wp2sl_login_error'] = true;
            $_SESSION['wp2sl_login_error_msg'] = "Wrong username / password for user: <strong><em>{$login_user->user_login}</em></strong>";
        }
    } else {
        $_SESSION['wp2sl_login_error'] = true;
        $_SESSION['wp2sl_login_error_msg'] = "Username is not registered";
    }
}

include_once 'forms/login/formbody.php';

