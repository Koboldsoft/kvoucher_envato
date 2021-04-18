<?php

function koboldcoupon_initialize_terms_of_service_options()
{
    if (false == get_option('koboldcoupon_plugin_terms_of_service_textfields')) {
        add_option('koboldcoupon_plugin_terms_of_service_textfields');
    } // end if
    add_settings_section('terms_of_service_settings_section', // ID used to identify this section and with which to register options
        __('Terms of Service Options','koboldcouponpro'), // Title to be displayed on the administration page
        'sandbox_terms_of_service_callback', // Callback used to render the description of the section
        'koboldcoupon_plugin_terms_of_service_textfields' // Page on which to add this section of options
        );
    
    add_settings_field('terms_of_service', __('Terms of Service','koboldcouponpro'), 'koboldcoupon_textfield_terms_of_service_callback', 'koboldcoupon_plugin_terms_of_service_textfields', 'terms_of_service_settings_section');
    
    register_setting('koboldcoupon_plugin_terms_of_service_textfields', 'koboldcoupon_plugin_terms_of_service_textfields', 'koboldcoupon_plugin_sanitize_terms_of_service_options');
} // end koboldcoupon_initialize_terms_of_service_options
add_action('admin_init', 'koboldcoupon_initialize_terms_of_service_options');

// callback section
function sandbox_terms_of_service_callback()
{
    echo '<p>'.__('Provide your Terms of Service','koboldcouponpro').'</p><i>('.__('You can use HTML Code','koboldcouponpro').')</i>';
}

function koboldcoupon_textfield_terms_of_service_callback()
{
    if (empty(get_option('koboldcoupon_plugin_terms_of_service_textfields')['terms_of_service'])) {
        $terms_of_service = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $terms_of_service = get_option('koboldcoupon_plugin_terms_of_service_textfields')['terms_of_service'];
        $msg = '';
    }
    ;
    
    // Render the output
    echo '<textarea cols="90" rows="20" id="terms_of_service" name="koboldcoupon_plugin_terms_of_service_textfields[terms_of_service]" placeholder="your terms of service here">'.$terms_of_service.'</textarea>';
}

function koboldcoupon_plugin_sanitize_terms_of_service_options($input)
{
    if( isset( $input['terms_of_service'] ) )
        $new_input['terms_of_service'] = htmlentities ($input['terms_of_service']);
        
        return $new_input;
} 