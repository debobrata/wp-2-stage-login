<?php
/**
 * Login form main body
 */
include_once 'form_header.php';
?>

    <section class="main">
        
        <div class="notification-container">
            <?php if ($_SESSION['wp2sl_login_error'] === true) { ?>
            <div class="error_pan">
                <!--<div class="transparency"></div>-->
                <img src="<?php echo wp2sl_url().'/templates/forms/login/images/error_icon_64px.png' ; ?>">
                <div class="error_content"><?php echo $_SESSION['wp2sl_login_error_msg']; ?></div>
            </div>
            <?php } ?>
        </div>
        
        <form class="form-1" name="frm_generic_login" id="frm_generic_login" method="POST" action="" autocomplete="off">
            <p class="field">
                <input type="text" name="g_wp_username" id="g_wp_username" placeholder="Wordpress Username" value="<?php echo esc_attr($g_wp_username); ?>">
                <i class="icon-user icon-large"></i>
            </p>
            <p class="field">
                <input type="text" name="g_username" id="g_username" placeholder="Username" value="<?php echo esc_attr($g_username); ?>">
                <i class="icon-user icon-large"></i>
            </p>
            <p class="field">
                <input type="password" name="g_pwd" id="g_pwd" placeholder="Password" value="">
                <i class="icon-lock icon-large"></i>
            </p>
            <p class="field">
                <a id="gl-forgot-pwd" style="text-align:center;" href="javascript:void(0);">Forgot password?</a>
            </p>
            <p class="field" id="forgot-pwd-container" style="display:none;">
                <input type="text" name="g_frgt_pwd" id="g_frgt_pwd" placeholder="Enter registered email id" value="">
                <i class="icon-pencil icon-large"></i>
                <input type="button" value="Submit" id="sub_g_frgt_pwd" name="sub_g_frgt_pwd">
            </p>
            <p class="submit">
                <button type="submit" name="sub_generic_login" value="Login"><i class="icon-arrow-right icon-large"></i></button>
            </p>
        </form>
    </section>

<?php include_once 'form_footer.php'; ?>

