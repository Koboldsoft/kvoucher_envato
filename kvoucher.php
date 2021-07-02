<?php

/*
 * Plugin Name: KVoucher
 * Plugin URI: http://www.koboldsoft.com
 * Description: Voucher plugin for Wordpress Websites
 * Version: 1.0
 * Author: KoboldSoft
 * Text Domain: kvoucherpro
 * Domain Path: /languages
 * 
 * * @fs_premium_only /class/KVoucherCustomers_List.php
 */
if (! function_exists('kvo_fs')) {

    // Create a helper function for easy SDK access.
    function kvo_fs()
    {
        global $kvo_fs;

        if (! isset($kvo_fs)) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $kvo_fs = fs_dynamic_init(array(
                'id' => '8433',
                'slug' => 'kvoucher',
                'type' => 'plugin',
                'public_key' => 'pk_7e454d0c83f91e766649447851394',
                'is_premium' => true,
                'premium_suffix' => 'Premium',
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons' => false,
                'has_paid_plans' => true,
                'menu' => array(
                    'slug' => 'kvoucher_options'
                ),
                // Set the SDK to work in a sandbox mode (for development & testing).
                // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
                'secret_key' => 'sk_o)$?Y>^^7$)^7ngavDq74mv]^q5cf'
            ));
        }

        return $kvo_fs;
    }

    // Init Freemius.
    kvo_fs();
    // Signal that SDK was initiated.
    do_action('kvo_fs_loaded');
}

defined('ABSPATH') or die('Are you ok?');

// load FrontendStuff class
define('PLUGIN_ROOT_DIR', plugin_dir_path(__FILE__));
include (PLUGIN_ROOT_DIR . 'class/KVoucherFrontStuff.php');


if ( kvo_fs()->can_use_premium_code__premium_only() ) {
    // load KVoucherCustomers_List class
    include (PLUGIN_ROOT_DIR . 'class/KVoucherCustomers_List.php');
}


// load KoboldcouponCustomers class
include (PLUGIN_ROOT_DIR . 'class/KVoucherCustomers.php');

// load KVoucherSaveUsrData class
include (PLUGIN_ROOT_DIR . 'class/KVoucherSaveUsrData.php');

// load KVoucherSendData class
include (PLUGIN_ROOT_DIR . 'class/KVoucherSendData.php');

// ####################################################################################################################

use FrontendStuff\KVoucherForm;

use KVoucherSaveUsrData\KVoucherSaveData;

use KVoucherSendUsrData\KVoucherSendData;

register_activation_hook(__FILE__, 'kvoucher_install');

function kvoucher_install()
{
    global $wpdb;

    $table_name_usr = $wpdb->prefix . "usr_kvoucher";

    $charset_collate = $wpdb->get_charset_collate();
    // create table for template

    // create table usr data
    $sql_usr_kvoucher = "CREATE TABLE $table_name_usr (
        id int(9) NOT NULL AUTO_INCREMENT,
        price int(20) NOT NULL,
        shipping varchar(6) NULL,
        shipping_costs varchar(6) NULL,
        kind_of_adress varchar(6) NOT NULL,
        occasion varchar(100) NULL,
        title varchar(4) NOT NULL,
        fname varchar(30) NOT NULL,
        nname varchar(30) NOT NULL,
        company varchar(40) NULL,
        for_title varchar(4) NOT NULL,
        for_fname varchar(30) NOT NULL,
        for_nname varchar(30) NOT NULL,
        streetname varchar(50) NOT NULL,
        plz varchar(8) NOT NULL,
        city varchar(50) NOT NULL,
        country varchar(50) NOT NULL,
        phone varchar(20) NOT NULL,
        email varchar(50) NOT NULL,
        dif_title varchar(4) NULL,
        dif_fname varchar(30) NULL,
        dif_nname varchar(30) NULL,
        dif_streetname varchar(50) NULL,
        dif_plz varchar(8) NULL,
        dif_city varchar(50) NULL,
        dif_country varchar(50) NULL,
        dif_email varchar(50) NULL,
        key_kvoucher char(32) NOT NULL,
        date datetime NOT NULL,
        validity int(1) DEFAULT 3,
        vat decimal(2, 1) DEFAULT 0,
        currency varchar(20) DEFAULT 'euro',
        action int(1) DEFAULT 0,
        del int(1) DEFAULT 0,
        PRIMARY KEY  (id)
        ) $charset_collate;";

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql_usr_kvoucher);

    if (false == get_option('kvoucher_coupon_id')) {

        add_option('kvoucher_coupon_id', '0');
    }
}

function kvoucherpro_load_plugin_textdomain()
{
    load_plugin_textdomain('kvoucherpro', FALSE, basename(dirname(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'kvoucherpro_load_plugin_textdomain');

function kvencryptData($dataToEncrypt)
{
    $cipher = 'aes-256-ofb';

    if (in_array($cipher, openssl_get_cipher_methods())) {
        
        $ivlen = openssl_cipher_iv_length($cipher);

        $iv = openssl_random_pseudo_bytes($ivlen);

        $data['data'] = openssl_encrypt($dataToEncrypt, 'aes-256-ofb', '~B-$mAf5~jm<Fz!p', $options = 0, $iv);

        $data['iv'] = $iv;

        return $data;
    }
}

function kvencrytURLVarables($array)
{
    $data = kvencryptData(http_build_query($array));

    return $data;
}

function kvencryptURL($url)
{
    $data = kvencryptData($url);

    return $data;
}

// functions company settings here ##############################################################
function kvinsertSettings()
{
    require_once 'php/ScCompanySettings.php';

    require_once 'php/ScPayPalSettings.php';

    require_once 'php/ScStyleSettings.php';

    require_once 'php/ScTermsOfServiceSettings.php';
}

kvinsertSettings();

// ##############################################################################################

// load scripts #######################################
function kvload_frontend_scripts()
{
    wp_enqueue_media();
    // load js admin-script
    wp_register_script('frontend_js_script', plugins_url('/js/frontend-script.js', __FILE__), array(
        'jquery'
    ));

    wp_enqueue_script('frontend_js_script');

   wp_localize_script('frontend_js_script', 'usr_data_obj', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce')
    ));
    // load css admin-script
    wp_register_style('frontend_css_script', plugins_url('/css/frontend-style.css', __FILE__));

    wp_enqueue_style('frontend_css_script');
}

function kvusr_data_request()
{
    $nonce = $_POST['nonce'];

    if (! wp_verify_nonce($nonce, 'ajax-nonce')) {
        
        die('Nonce value cannot be verified.');
    }

    if (isset($_POST)) {
        
        $coupon_data = KVoucherSaveData::kvsaveData( $_POST['data'] );
        
        $send = new KVoucherSendData($coupon_data);
        
        $send->kvsendDataCurl(); // send data
        
        }

    die();
}

add_action( 'wp_ajax_kvusr_data_request', 'kvusr_data_request' );

add_action('wp_ajax_nopriv_kvusr_data_request', 'kvusr_data_request');

// create a session

function kvload_backend_scripts()
{
    wp_enqueue_media();
    // load js admin-script
    wp_register_script('backend_js_script', plugins_url('/js/backend-script.js', __FILE__));

    wp_enqueue_script('backend_js_script');
}
add_action('admin_enqueue_scripts', 'kvload_backend_scripts');

add_action('wp_enqueue_scripts', 'kvload_frontend_scripts');

// add fronpage site #############################################
function kvoucher_add_frontpage()
{
    add_shortcode('kvoucher', 'KVoucherFrontendStuff\KVoucherForm::kvBillingAdress');
}

add_action('init', 'kvoucher_add_frontpage');


// ################################################################

add_action('admin_menu', 'kvoucher_settings_menu');

function kvoucher_settings_menu()
{
    add_menu_page('KVoucher', // The title to be displayed in the browser window for this page.
    'KVoucher', // The text to be displayed for this menu item
    'administrator', // Which type of users can see this menu item
    'kvoucher_options', // The unique ID - that is, the slug - for this menu item
    'kvplugin_display', // The name of the function to call when rendering this menu's page
    plugin_dir_url(__FILE__) . 'img/kvoucherpro.png');
}

function kvoucher_edit_coupons_init()
{
    include 'edit_coupons.php';
}

function kvcheckCompanyData()
{
    $company_data = array();

    $company_data = get_option('kvoucher_plugin_company_textfiels');

    $required_data_company = array(
        __('Company name', 'kvoucherpro') => 'company',
        __('First Name', 'kvoucherpro') => 'first_name',
        __('Last Name', 'kvoucherpro') => 'last_name',
        __('Streetname', 'kvoucherpro') => 'street_name',
        __('Postal-Code', 'kvoucherpro') => 'postal_code',
        __('City', 'kvoucherpro') => 'city',
        __('Country', 'kvoucherpro') => 'country',
        __('Phonenumber', 'kvoucherpro') => 'phone_number',
        __('Company Domain', 'kvoucherpro') => 'company_url',
        __('Company E-mail', 'kvoucherpro') => 'company_email'
    );

    // output all errors
    foreach ($required_data_company as $required => $value) {

        if (empty($company_data[$value]) || $company_data[$value] == null || $company_data[$value] == '') {
            echo '<i style="color:red">' . $required . ' ' . __('is required!', 'kvoucherpro') . '</i><br>';
        }
    }
}

function kvcheckTermsOfServiceData()
{
    $terms_of_service_data = array();

    $terms_of_service_data = get_option('kvoucher_plugin_terms_of_service_textfields');

    $required_data_terms_of_service = array(
        'AGB`s' => 'terms_of_service'
    );

    // output all errors
    foreach ($required_data_terms_of_service as $required => $value) {

        if (empty($terms_of_service_data[$value]) || $terms_of_service_data[$value] == null || $terms_of_service_data[$value] == '') {
            echo '<i style="color:orange">' . __('The creation of the general terms and conditions is recommended!', 'kvoucherpro') . '</i><br>';
        }
    }
}

function kvcheck_paypal_data()
{
    $paypal_data = array();

    $paypal_data = get_option('kvoucher_plugin_paypal_textfiels');

    $required_data = array(
        'Paypal Client-ID' => 'paypal_client_id'
    );

    // output all errors
    foreach ($required_data as $required => $value) {

        if (empty($paypal_data[$value]) || $paypal_data[$value] == null || $paypal_data[$value] == '') {
            echo '<i style="color:red">' . $required . ' ' . __('is required!', 'kvoucherpro') . '</i><br>';
        }
    }
}

function kvplugin_display()
{
    ?>
<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">

	<div id="icon-themes" class="icon32"></div>

	<h2><?php _e('KVoucher Options','kvoucherpro')?></h2>
	
		<?php settings_errors(); ?>
        
        <?php kvcheckCompanyData(); ?>
        
        <?php kvcheck_paypal_data(); ?>
        
        <?php kvcheckTermsOfServiceData()?>
        
        <?php $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'company_options'; ?>
         
    <h2 class="nav-tab-wrapper">
		<a href="?page=kvoucher_options&tab=company_options"
			class="nav-tab <?php echo $active_tab == 'company_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Company Settings', 'kvoucherpro' );?></a>

		<a href="?page=kvoucher_options&tab=paypal_options"
			class="nav-tab <?php echo $active_tab == 'paypal_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'PayPal Settings', 'kvoucherpro' );?></a>

		<a href="?page=kvoucher_options&tab=terms_of_service_options"
			class="nav-tab <?php echo $active_tab == 'terms_of_service_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Terms of Service', 'kvoucherpro' );?></a>

		<a href="?page=kvoucher_options&tab=style_options"
			class="nav-tab <?php echo $active_tab == 'style_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Style Settings', 'kvoucherpro' );?></a>

		<a href="https://koboldsoft.com/kvoucher/" target="_blank"><img
			alt="<?php _e( 'Handbook', 'kvoucherpro' );?>"
			title="<?php _e( 'Handbook', 'kvoucherpro' );?>"
			src="<?php echo esc_url( plugins_url( 'img/help_kcoupon.png', __FILE__ ) )?>"
			width="35" height="35"></a>
	</h2>

	<form method="post" action="options.php">
 
            <?php
    switch ($active_tab) {
        case "company_options":
            settings_fields('kvoucher_plugin_company_textfiels');
            do_settings_sections('kvoucher_plugin_company_textfiels');
            break;
        case "paypal_options":
            settings_fields('kvoucher_plugin_paypal_textfiels');
            do_settings_sections('kvoucher_plugin_paypal_textfiels');
            break;
        case "style_options":
            settings_fields('kvoucher_plugin_style_textfiels');
            do_settings_sections('kvoucher_plugin_style_textfiels');
            break;
        case "terms_of_service_options":
            settings_fields('kvoucher_plugin_terms_of_service_textfields');
            do_settings_sections('kvoucher_plugin_terms_of_service_textfields');
            break;
    }

    submit_button();

    ?>
             
        </form>

</div>
<!-- /.wrap -->
<?php
}
//Edit_coupons

