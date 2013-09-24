<?php
/* ========================================= WP AJAX ========================================= */
add_action('init', 'wp2sl_ajax_script_enqueuer');

add_action("wp_ajax_do_pwd_reset", "do_pwd_reset");
add_action("wp_ajax_nopriv_do_pwd_reset", "do_pwd_reset");

function wp2sl_ajax_script_enqueuer() {
   wp_register_script('wp2sl_actions_script', plugins_url('js/gl-ajax-actions.js', __FILE__), array('jquery'));
   wp_localize_script('wp2sl_actions_script', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

   wp_enqueue_script('jquery');
   wp_enqueue_script('wp2sl_actions_script');
}

function do_pwd_reset() {
    $f_pwd_email = sanitize_text_field($_POST['reset_email']);
    $email_user = get_user_by('email', $f_pwd_email);
    $status = 's';
    
    if (empty($f_pwd_email) || !is_email($f_pwd_email)) {
        $status = 'nv';
    } else {
        if ($email_user !== false) {
            if (wp2sl_do_pwd_reset($f_pwd_email)) {
                $status = 's';
            } else {
                $status = 'f';
            }
        } else {
            $status = 'nr';
        }
    }

    echo json_encode(array($status));
    die;
}

