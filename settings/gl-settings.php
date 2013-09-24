<?php
/**
 * 2-Stage Login Settings page | wp-admin
 */


// Settings form post action
$frm_gl_settings_submit_val = sanitize_text_field($_POST['sub_gl_settings']);
$gl_active_status = sanitize_text_field($_POST['gl_active_status']);
$gl_page_heading = sanitize_text_field($_POST['gl_page_heading']);
$gl_page_sub_heading = sanitize_text_field($_POST['gl_page_sub_heading']);
$gl_page_logo = sanitize_text_field($_POST['gl_page_logo']);
$gl_page_bg = sanitize_text_field($_POST['gl_page_bg']);
$gl_active_status = (!empty($gl_active_status)) ? "yes" : "no";

if (isset($frm_gl_settings_submit_val) && $frm_gl_settings_submit_val == "Save Settings") {
    global $default_gl_tpl_bg;
    global $default_gl_tpl_logo;
    var_dump(wp2sl_url_exists($gl_page_bg));
    $gl_page_logo = (empty($gl_page_logo) || !wp2sl_url_exists($gl_page_logo)) ? $default_gl_tpl_logo : $gl_page_logo;
    $gl_page_bg = (empty($gl_page_bg) || !wp2sl_url_exists($gl_page_bg)) ? $default_gl_tpl_bg : $gl_page_bg;
    
    update_option('_wp2sl_gl_active', $gl_active_status);
    update_option('_wp2sl_gl_page_heading', $gl_page_heading);
    update_option('_wp2sl_gl_page_sub_heading', $gl_page_sub_heading);
    update_option('_wp2sl_gl_page_logo', $gl_page_logo);
    update_option('_wp2sl_gl_page_bg', $gl_page_bg);
    
    echo '<div class="updated gl-notice" id="message">
            <p><strong>Settings saved.</strong></p>
            </div>';
}

add_action('admin_enqueue_scripts', 'my_admin_scripts');
 
function my_admin_scripts() {
    if (isset($_GET['page']) && $_GET['page'] == 'manage-generic-login') {
        wp_enqueue_media();
        wp_register_script('my-admin-js', WP_PLUGIN_URL.'/my-plugin/my-admin.js', array('jquery'));
        wp_enqueue_script('my-admin-js');
    }
}

?>

<div class="wrap">
    <div class="icon32 gl-icon-64">
        <img src="<?php echo wp2sl_url().'/images/banner-lock-icon-64.png'; ?>">
    </div>
    <h2 class="gl-settings-header">Settings</h2>
    <div class="clear"></div>
    
    <form id="frm_gl_settings" name="frm_gl_settings" method="POST" action="">
        <h3>General Information</h3>
        <table class="form-table">
            <tr>
                <th><label for="gl_active_status"><?php _e("Do you want to use generic login?"); ?></label></th>
                <td>
                    <input type="checkbox" name="gl_active_status" id="gl_active_status" class="checkbox"
                        value="yes"<?php if (get_option('_wp2sl_gl_active') == "yes") { echo ' checked="checked"'; } ?>/>
                </td>
            </tr>
            <tr>
                <th><label for="gl_page_heading"><?php _e("Login page heading (optional)"); ?></label></th>
                <td>
                    <input type="text" name="gl_page_heading" id="gl_page_heading" class="regular-text" value="<?php echo esc_attr(get_option('_wp2sl_gl_page_heading')); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="gl_page_sub_heading"><?php _e("Login page sub-heading (optional)"); ?></label></th>
                <td>
                    <input type="text" name="gl_page_sub_heading" id="gl_page_sub_heading" class="regular-text" value="<?php echo esc_attr(get_option('_wp2sl_gl_page_sub_heading')); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="gl_page_logo"><?php _e("Login page logo (optional)"); ?></label></th>
                <td>
                    <img class="gl-settings" src="<?php echo get_option('_wp2sl_gl_page_logo'); ?>" />
                    <input type="text" name="gl_page_logo" id="gl_page_logo" class="regular-text" value="<?php echo esc_attr(get_option('_wp2sl_gl_page_logo')); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="gl_page_bg"><?php _e("Login page background image (optional)"); ?></label></th>
                <td>
                    <img class="gl-settings" src="<?php echo get_option('_wp2sl_gl_page_bg'); ?>" />
                    <input type="text" name="gl_page_bg" id="gl_page_bg" class="regular-text" value="<?php echo esc_attr(get_option('_wp2sl_gl_page_bg')); ?>" /></td>
            </tr>
            <tr>
                <th></th>
                <td>
                    <input type="submit" name="sub_gl_settings" id="sub_gl_settings" class="button button-primary" value="Save Settings" />
                </td>
            </tr>
        </table>
    </form>
</div>

