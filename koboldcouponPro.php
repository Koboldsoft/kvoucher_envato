<?php
/*
 * Plugin Name: KoboldCouponPro
 * Plugin URI: http://www.koboldsoft.com
 * Description: Couponplugin for Wordpress Websites
 * Version: 1.0
 * Author: KoboldSoft
 * Text Domain: koboldcouponpro
 * Domain Path: /languages
 * Author URI: http://www.koboldsoft.com
 */

// this function please remove after debug-session ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// Permalink
/*
 add_action ( 'init', 'stop_heartbeat', 1 );
 function stop_heartbeat() {
 wp_deregister_script ( 'heartbeat' );
 }
*/
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//
defined('ABSPATH') or die('Are you ok?');

// load FrontendStuff class
define( 'PLUGIN_ROOT_DIR', plugin_dir_path( __FILE__ ) );
include( PLUGIN_ROOT_DIR . 'class/KoboldcouponFrontStuff.php');

// load Customer_List class
include( PLUGIN_ROOT_DIR . 'class/Customers_List.php');

//load KoboldcouponCustomers class
include( PLUGIN_ROOT_DIR . 'class/KoboldcouponCustomers.php');

// ####################################################################################################################

use FrontendStuff\KoboldcouponForm;


register_activation_hook(__FILE__, 'koboldcoupon_install');

function koboldcoupon_install()
{
    global $wpdb;

    
    $table_name_usr = $wpdb->prefix . "usr_koboldcoupon";
    
    $charset_collate = $wpdb->get_charset_collate();
    // create table for template

    // create table usr data
    $sql_usr_koboldcoupon = "CREATE TABLE $table_name_usr (
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
    key_koboldcoupon char(32) NOT NULL,
    date datetime NOT NULL,
    validity int(1) DEFAULT 3,
    vat decimal(2, 1) DEFAULT 0,
    currency varchar(20) DEFAULT 'euro',
    action int(1) DEFAULT 0,
    del int(1) DEFAULT 0,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    
    dbDelta($sql_usr_koboldcoupon);
    
}

function koboldcouponpro_load_plugin_textdomain() {
    load_plugin_textdomain( 'koboldcouponpro', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'koboldcouponpro_load_plugin_textdomain' );


function encryptData($dataToEncrypt){
    
    $cipher = 'aes-256-ofb';
    
    if (in_array( $cipher , openssl_get_cipher_methods()))
    {
        $ivlen = openssl_cipher_iv_length($cipher);
        
        $iv = openssl_random_pseudo_bytes($ivlen);
        
        $data['data'] = openssl_encrypt($dataToEncrypt, 'aes-256-ofb', '~B-$mAf5~jm<Fz!p', $options=0, $iv);
        
        $data['iv'] = $iv;
        
        return $data;
        
    }
    
}

function encrytURLVarables( $array ){
    
    $data = encryptData( http_build_query( $array ) );
    
    return $data;
    
}

function encryptURL( $url ){
    
    $data = encryptData(  $url ) ;
    
    return $data;
    
}

// functions company settings here ##############################################################
function insertSettings(){
    require_once 'php/ScCompanySettings.php';
    
    require_once 'php/ScPayPalSettings.php';
    
    require_once 'php/ScStyleSettings.php';
    
    require_once 'php/ScTermsOfServiceSettings.php';
    
}

insertSettings();


// ##############################################################################################

// load scripts #######################################
function load_frontend_scripts()
{
    wp_enqueue_media();
    // load js admin-script
    wp_register_script('frontend_js_script', plugins_url('/js/frontend-script.js', __FILE__));
    wp_enqueue_script('frontend_js_script');
    // load css admin-script
    wp_register_style('frontend_css_script', plugins_url('/css/frontend-style.css', __FILE__));
    wp_enqueue_style('frontend_css_script');
    
   
}
// create a session
function register_session() {
    if (!session_id())
        session_start();
}

add_action('init', 'register_session');


function load_backend_scripts()
{
    wp_enqueue_media();
    // load js admin-script
    wp_register_script('backend_js_script', plugins_url('/js/backend-script.js', __FILE__));
    wp_enqueue_script('backend_js_script');
}
add_action('admin_enqueue_scripts', 'load_backend_scripts');

add_action( 'wp_enqueue_scripts', 'load_frontend_scripts' );

// add fronpage site #############################################

function koboldcoupon_add_frontpage() {
    
    add_shortcode('koboldcouponpro', 'KoboldcouponFrontendStuff\KoboldcouponForm::koboldcouponBillingAdress');
}

add_action( 'init', 'koboldcoupon_add_frontpage' );

// ################################################################

add_action('admin_menu', 'koboldcoupon_settings_menu');

function koboldcoupon_settings_menu()
{
    add_menu_page(
        'KCouponPro', // The title to be displayed in the browser window for this page.
        'KCouponPro', // The text to be displayed for this menu item
        'administrator', // Which type of users can see this menu item
        'koboldcoupon_options', // The unique ID - that is, the slug - for this menu item
        'koboldcoupon_plugin_display', // The name of the function to call when rendering this menu's page
        plugin_dir_url( __FILE__ ) . 'img/koboldcouponpro.png'
        );
    
    //add_submenu_page('koboldcoupon_options', 'Customers', 'Coupons', 'manage_options', 'coupons', 'koboldcoupon_edit_coupons_init');
}

function koboldcoupon_edit_coupons_init()
{
    include 'edit_coupons.php';
}

function checkCompanyData(){
    
    $company_data = array();
    
    $company_data = get_option('koboldcoupon_plugin_company_textfiels');
    
    $required_data_company = array('Firmenname'=>'company',
        __( 'First Name', 'koboldcouponpro' )=>'first_name',
        __( 'Last Name', 'koboldcouponpro' )=>'last_name',
        __( 'Streetname', 'koboldcouponpro' )=>'street_name',
        __( 'Postal-Code', 'koboldcouponpro' )=>'postal_code',
        __( 'City', 'koboldcouponpro' )=>'city',
        __( 'Country', 'koboldcouponpro' )=>'country',
        __( 'Phonenumber', 'koboldcouponpro')=>'phone_number',
        __( 'Company Domain', 'koboldcouponpro' )=>'company_url',
        __( 'Company E-mail', 'koboldcouponpro' )=>'company_email'
                           );
    
    // output all errors
    foreach( $required_data_company as $required => $value ){
        
        if(empty($company_data[$value]) || $company_data[$value] == null || $company_data[$value] == '' ) { echo '<i style="color:red">'.$required.' '.__('is required!','koboldcouponpro').'</i><br>';}
        
     }
    
}

function checkcUrlinstalled() {
    if  (!in_array  ('curl', get_loaded_extensions())) {
        
        echo '<i style="color:red">'.__('cUrl is not installed on the web server but is required','koboldcouponpro').'!</i><br>';
        
    }
    
}

function checkTermsOfServiceData(){
    
    $terms_of_service_data = array();
    
    $terms_of_service_data = get_option('koboldcoupon_plugin_terms_of_service_textfields');
    
    $required_data_terms_of_service = array('AGB`s'=>'terms_of_service');
    
    // output all errors
    foreach( $required_data_terms_of_service as $required => $value ){
        
        if(empty($terms_of_service_data[$value]) || $terms_of_service_data[$value] == null || $terms_of_service_data[$value] == '' ) { echo '<i style="color:orange">'.__('The creation of the general terms and conditions is recommended!','koboldcouponpro').'</i><br>';}
        
    }
    
}
function infoKoboldCouponPro(){
    
    $sendurl = 'https://couponsystem.koboldsoft.com/koboldcouponProInfo.php';
    
    if(@file_get_contents($sendurl)){
        
        $data = file_get_contents($sendurl);
        
        $decodedData = json_decode($data);
        
        echo $decodedData->info;
            
        }
}


function checkisUrlValide(){
    
    $encryptedurl = encryptURL( $_SERVER['SERVER_NAME'] );
    
    $sendurl = 'https://couponsystem.koboldsoft.com/validationCustomers.php?'.http_build_query($encryptedurl);
    
    if(@file_get_contents($sendurl)){
        
        $data = file_get_contents($sendurl);
    
        $decodedData = json_decode($data);
    
        if ($decodedData->status != true){
        
            die ($decodedData->message);
            
        }else if( $decodedData->status == true ){
            
            echo $decodedData->message;
            
        }
    }
    
}

function check_paypal_data(){
    
    $paypal_data = array();
    
    $paypal_data = get_option('koboldcoupon_plugin_paypal_textfiels');
    
    $required_data = array('Paypal Client-ID'=>'paypal_client_id');
    
    // output all errors
    foreach( $required_data as $required => $value ){
        
        if(empty($paypal_data[$value]) || $paypal_data[$value] == null || $paypal_data[$value] == '' ) { echo '<i style="color:red">'.$required.' ist erforderlich!</i><br>';}
        
    }
}

function koboldcoupon_plugin_display()
{
    ?>
<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">

	<div id="icon-themes" class="icon32"></div>
	
	<a href="https://koboldsoft.com" target="_blank"><img alt="Koboldsoft.com" src="<?php echo esc_url( plugins_url( 'img/koboldsoft_black_solutions.png', __FILE__ ) )?>" height="25"></a>
	
	<h2><?php _e('KCouponPro Options','koboldcouponpro')?></h2>
	
		<?php // infoKoboldCouponPro() ?>
	
		<?php // checkisUrlValide()?>
	
        <?php settings_errors(); ?>
        
        <?php checkcUrlinstalled();?>
        
        <?php checkCompanyData(); ?>
        
        <?php check_paypal_data(); ?>
        
        <?php checkTermsOfServiceData()?>
        
        <?php
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'company_options';

    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    } // end if
    ?>
         
    <h2 class="nav-tab-wrapper">
		<a href="?page=koboldcoupon_options&tab=company_options" class="nav-tab <?php echo $active_tab == 'company_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Company Settings', 'koboldcouponpro' );?></a>
			
		<a href="?page=koboldcoupon_options&tab=paypal_options" class="nav-tab <?php echo $active_tab == 'paypal_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'PayPal Settings', 'koboldcouponpro' );?></a>
		
		<a href="?page=koboldcoupon_options&tab=terms_of_service_options" class="nav-tab <?php echo $active_tab == 'terms_of_service_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Terms of Service', 'koboldcouponpro' );?></a>
		
		<a href="?page=koboldcoupon_options&tab=style_options" class="nav-tab <?php echo $active_tab == 'style_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Style Settings', 'koboldcouponpro' );?></a>
		
		<a href="https://koboldsoft.com/kcouponpro/" target="_blank"><img alt="<?php _e( 'Handbook', 'koboldcouponpro' );?>" src="<?php echo esc_url( plugins_url( 'img/help_kcoupon.png', __FILE__ ) )?>" width="35" height="35"></a>
	</h2>

	<form method="post" action="options.php">
 
            <?php
    switch ($active_tab) {
        case "company_options":
            settings_fields('koboldcoupon_plugin_company_textfiels');
            do_settings_sections('koboldcoupon_plugin_company_textfiels');
            break;
        case "paypal_options":
            settings_fields('koboldcoupon_plugin_paypal_textfiels');
            do_settings_sections('koboldcoupon_plugin_paypal_textfiels');
            break;
        case "style_options":
            settings_fields('koboldcoupon_plugin_style_textfiels');
            do_settings_sections('koboldcoupon_plugin_style_textfiels');
            break;
        case "terms_of_service_options":
            settings_fields('koboldcoupon_plugin_terms_of_service_textfields');
            do_settings_sections('koboldcoupon_plugin_terms_of_service_textfields');
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

