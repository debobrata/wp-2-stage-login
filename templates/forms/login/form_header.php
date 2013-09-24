<?php

/*
 * Login form header
 */

include_once ABSPATH . 'wp-load.php';
global $post;
$body_bg = "";
if (get_option('_wp2sl_gl_page_bg') && wp2sl_url_exists(get_option('_wp2sl_gl_page_bg'))) :
	$body_bg = ' style="background:url('.esc_attr(get_option('_wp2sl_gl_page_bg')).');"';
endif;
	
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        
        <title><?php echo $post->post_title . ' | ' .  get_bloginfo('name'); ?></title>
        
        <meta name="description" content="Custom Login Form Styling with CSS3" />
        <meta name="keywords" content="css3, login, form, custom, input, submit, button, html5, placeholder" />
        <meta name="author" content="Codrops" />
        
        <?php do_action('wp2sl_enqueue_styles'); ?>
        <?php do_action('wp2sl_enqueue_scripts'); ?>
        
        <!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
    </head>
    <body<?php echo $body_bg; ?>>
        <div class="container">
            <header>
                <?php if (get_option('_wp2sl_gl_page_logo')) { ?>
                <a href="<?php echo site_url(); ?>"><img src="<?php echo esc_attr(get_option('_wp2sl_gl_page_logo')); ?>" height="100" title="Generic Login" alt="Generic Login"></a>
                <?php } ?>
                
                <?php if (get_option('_wp2sl_gl_page_heading')) { ?>
                <a href="<?php echo site_url(); ?>"><h1><?php echo esc_attr(get_option('_wp2sl_gl_page_heading')); ?></h1></a>
                <?php } else { ?>
                <a href="<?php echo site_url(); ?>"><h1><?php echo get_bloginfo('name'); ?></h1></a>
                <?php } ?>
                
                <?php if (get_option('_wp2sl_gl_page_sub_heading')) { ?>
                <h2><?php echo esc_attr(get_option('_wp2sl_gl_page_sub_heading')); ?></h2>
                <?php } else { ?>
                <h2><?php echo get_bloginfo('description'); ?></h2>
                <?php } ?>
                
                <div class="support-note">
                    <span class="note-ie">Sorry, only modern browsers.</span>
                </div>
            </header>
            
                    