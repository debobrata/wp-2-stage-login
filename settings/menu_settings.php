<?php

// Add Supplier menu
add_action('admin_menu', 'wp2sl_create_menu');

function wp2sl_create_menu() {
    $gl_list_page = add_menu_page(
            $page_title = 'Generic Login - WP 2Stage Login',
            $menu_title = 'Generic Login',
            $capability = 'administrator',
            $menu_slug  = 'manage-generic-login',
            $function   = 'manage_generic_login',
            $icon_url   = plugins_url('2stage-login/images/icon-lock.png'),
            $position   = 99
        );
    
    // Submenus
    //add_submenu_page($parent_slug = 'manage-generic-login', $page_title = __('Settings'), $menu_title = __('Settings'), $capability = 'administrator', $menu_slug = 'generic-login-settings', $function = 'manage_generic_login');
    //add_submenu_page($parent_slug = 'manage-generic-login', $page_title = __('Import Suppliers', 'ss'), $menu_title = __('Import Suppliers', 'ss'), $capability = 'administrator', $menu_slug = 'import-suppliers', $function = 'import_suppliers');
}

function manage_generic_login() {
    // blank
    include_once 'gl-settings.php';
}

function add_suppliers() {
    include 'add_suppliers.php';
}

function import_suppliers() {
    include_once 'import_suppliers.php';
}

