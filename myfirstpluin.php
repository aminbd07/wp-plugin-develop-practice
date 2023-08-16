<?php
/*
* Plugin Name: My First Plugin
* Plugin URI: https://example.com/plugins/the-basics/
* Description: Handle the basics with this plugin.
* Version: 1.0.0
* Author: Mr. X
* Text Domain : mfp
*
*/


/**
 * 
 */



function callOnPluginActivation()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'student_list'; // Replace with your table name

    // Check if the table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        // Table doesn't exist, create it
        $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            class VARCHAR(255) NOT NULL,
            age INTEGER(3),
            PRIMARY KEY (id)
        ) $wpdb->charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

register_activation_hook(
    __FILE__,
    'callOnPluginActivation'
);



add_action('admin_init', 'loadDefineData');
add_action('admin_init', 'loadAdminSetings');


function loadAdminSetings()
{
    register_setting('general', 'Site_Country');
    add_settings_field('field_id-site-country', "Site Country", 'showSettingFileds', 'general', 'default', array(
        'id' => 'field_id-site-country_',
        'option_name' => 'Site_Country'
    ));
    wp_enqueue_script('mfp-frontend-ajax', JS_DIR_URI . 'script.js', array('jquery'), null, false);
    wp_localize_script(
        'mfp-frontend-ajax',
        'mfpAjaxVar',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'author' => 'Nurul Amin',
            'serverTime' => date('Y-m-d h:i:s'),
        )
    );


    add_action('wp_ajax_mfp-save-my-data', 'handleMyAjaxData');
}

function loadDefineData()
{
    define('JS_DIR_URI', __DIR__ . "/js/");
}


/**
 * Update Post title
 * Author @nurul
 * Date : 07/07/2023
 * Arg : string $title, int id;
 * return string
 */
function updatePageTitle($title, $id)
{
    if (is_admin()) return $title;
    $color = 'RED';
    if ($id == 7) {
        $color = 'BLUE';
    }
    $update = "<p style='color:{$color}'> {$title} - {$id} </p>";
    return $update;
}

add_filter('the_title', 'updatePageTitle',  10, 2);



/**
 * Update Post title 
 * Author @nurul
 * Date : 07/07/2023
 * Arg : ;
 */
function addToCartAction($post_id, $new_post, $previous_post)
{
    var_dump($previous_post);
    var_dump($new_post);
}

// woocommerce_simple_add_to_cart
add_action('post_updated', 'addToCartAction', 10, 3);



// Add Setting Fileds 


function showSettingFileds($val)
{
    $id = $val['id'];
    $option_name = $val['option_name'];
?>
    <input type="text" name="<?php echo $option_name;  ?>" id="<?php echo $id; ?>" value="<?php echo esc_attr(get_option($option_name)) ?>" />
<?php

}




function mfp_register_meu()
{
    add_menu_page(
        __('My First Plugin', 'mfp'),
        __('Plugin Home', 'mfp'),
        'manage_options',
        'myfirstplugin.php',
        'loadMyPluginHome',
        // plugins_url('myplugin/images/icon.png'),
        'dashicons-admin-site-alt2',
        6
    );

    add_submenu_page(
        'myfirstplugin.php',
        __('Sub Menu', 'mfp'),
        __('My Sub Menu', 'mfp'),
        'manage_options',
        'my-sub-menu',
        'loadSubmenu'
    );
}
add_action('admin_menu', 'mfp_register_meu');


function loadMyPluginHome()
{
    include('include/welcome.php');
}


function loadSubmenu()
{
?>
    Sub Menu page 
<?php
}


function handleMyAjaxData()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'student_list';
    $success =  $wpdb->insert(
        $table_name,
        array(
            'name' => $_POST['name'],
            'class' => $_POST['class'],
            'age' => $_POST['age'],
        ),
        array(
            '%s',
            '%s',
            '%d',
        )
    );
    wp_send_json(['success' => true, 'id' => $wpdb->insert_id]);
    wp_die();
}
