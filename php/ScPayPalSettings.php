<?php

function koboldcoupon_initialize_paypal_options()
{
    
    // If the social options don't exist, create them.
    if (false == get_option('koboldcoupon_plugin_paypal_textfiels')) {
        add_option('koboldcoupon_plugin_paypal_textfiels');
    } // end if
    add_settings_section('paypal_settings_section', // ID used to identify this section and with which to register options
        __( 'PayPal Options','koboldcouponpro'), // Title to be displayed on the administration page
        'sandbox_paypal_options_callback', // Callback used to render the description of the section
        'koboldcoupon_plugin_paypal_textfiels' // Page on which to add this section of options
        );
    
    add_settings_field('paypal_client_id', __( 'PayPal Client-ID','koboldcouponpro'), 'koboldcoupon_textfield_paypal_client_id_callback', 'koboldcoupon_plugin_paypal_textfiels', 'paypal_settings_section');
    
    register_setting('koboldcoupon_plugin_paypal_textfiels', 'koboldcoupon_plugin_paypal_textfiels', 'koboldcoupon_plugin_sanitize_paypal_options');
} // end koboldcoupon_initialize_paypal_options
add_action('admin_init', 'koboldcoupon_initialize_paypal_options');

// callback section
function sandbox_paypal_options_callback()
{
    echo '<p>'.__( 'Provide your PayPal Client-ID', 'koboldcouponpro').'.</p>';
}

function koboldcoupon_textfield_paypal_client_id_callback()
{
    if(empty(get_option('koboldcoupon_plugin_paypal_textfiels')['paypal_client_id'])){
        $paypal_client_id ='';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    }else{
        $paypal_client_id = get_option('koboldcoupon_plugin_paypal_textfiels')['paypal_client_id'];
        $msg = '';
    };
    
    // Render the output
    echo '<input type="text" size="80" id="paypal_client_id" name="koboldcoupon_plugin_paypal_textfiels[paypal_client_id]" placeholder="' . $paypal_client_id . '" required />'.$msg;
}

function koboldcoupon_plugin_sanitize_paypal_options($input)
{
    if( isset( $input['paypal_client_id'] ) )
        $new_input['paypal_client_id'] = sanitize_text_field($input['paypal_client_id']);
        
        return $new_input;
} 