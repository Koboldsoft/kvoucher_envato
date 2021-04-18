<?php
function koboldcoupon_initialize_style_options()
{
    
    // If the social options don't exist, create them.
    if (false == get_option('koboldcoupon_plugin_style_textfiels')) {
        add_option('koboldcoupon_plugin_style_textfiels');
    } // end if
    add_settings_section('style_settings_section', // ID used to identify this section and with which to register options
        __('Style Options','koboldcouponpro'), // Title to be displayed on the administration page
        'sandbox_style_options_callback', // Callback used to render the description of the section
        'koboldcoupon_plugin_style_textfiels' // Page on which to add this section of options
        );
    
    add_settings_section('style_preview_button_section',__('Previews','koboldcouponpro'),'sandbox_style_button_preview_callback','koboldcoupon_plugin_style_textfiels');
    
    add_settings_field('font_color', __('Font-Color','koboldcouponpro' ), 'koboldcoupon_textfield_style_font_color_callback', 'koboldcoupon_plugin_style_textfiels', 'style_settings_section');
    
    add_settings_field('background_color', __('Background-Color','koboldcouponpro' ), 'koboldcoupon_textfield_style_background_color_callback', 'koboldcoupon_plugin_style_textfiels', 'style_settings_section');
    
    add_settings_field('logo', 'Logo', 'koboldcoupon_textfield_style_logo_callback', 'koboldcoupon_plugin_style_textfiels', 'style_settings_section');
    
    register_setting('koboldcoupon_plugin_style_textfiels', 'koboldcoupon_plugin_style_textfiels', 'koboldcoupon_plugin_sanitize_style_options');
} // end koboldcoupon_initialize_style_options
add_action('admin_init', 'koboldcoupon_initialize_style_options');

// callback section
function sandbox_style_options_callback()
{
    echo '<p>'.__('Provide your Custom-Style','koboldcouponpro' ).'</p>';
}

function sandbox_style_button_preview_callback(){
    
    $data['coupon_data']['lang'] = get_locale();
    
    $data['company_data']['company'] = @get_option('koboldcoupon_plugin_company_textfiels')['company'];
    
    $data['company_data']['currency'] = @get_option('koboldcoupon_plugin_company_textfiels')['currency'];
    
    $data['company_data']['street_name'] = @get_option('koboldcoupon_plugin_company_textfiels')['street_name'];
    
    $data['company_data']['postal_code'] = @get_option('koboldcoupon_plugin_company_textfiels')['postal_code'];
    
    $data['company_data']['city'] = @get_option('koboldcoupon_plugin_company_textfiels')['city'];
    
    $data['company_data']['phone_number'] = @get_option('koboldcoupon_plugin_company_textfiels')['phone_number'];
    
    $data['company_data']['company_url'] = @get_option('koboldcoupon_plugin_company_textfiels')['company_url'];
    
    $data['company_data']['company_email'] = @get_option('koboldcoupon_plugin_company_textfiels')['company_email'];
    
    $data['company_data']['tax_number'] = @get_option('koboldcoupon_plugin_company_textfiels')['tax_number'];
    
    $data['company_data']['value_added_tax'] = @get_option('koboldcoupon_plugin_company_textfiels')['value_added_tax'];
    
    $data['style_data'] = get_option('koboldcoupon_plugin_style_textfiels');
    
    $arrquery = @encrytURLVarables( $data );
    
    echo '<a style="margin: 0px 5px 0px 0px;" href="https://couponsystem.koboldsoft.com/preview.php?preview=coupon&'.http_build_query( $arrquery ).'" target="_blank" title="'.__('Preview Coupon','koboldcouponpro').'">'.__('Preview Coupon','koboldcouponpro').'</a>';

    echo '<a style="margin: 0px 5px 0px 5px;" href="https://couponsystem.koboldsoft.com/preview.php?preview=bill&'.http_build_query(  $arrquery ).'" target="_blank" title="'.__('Preview Bill','koboldcouponpro').'">'.__('Preview Bill','koboldcouponpro').'</a>';
    
}

function koboldcoupon_textfield_style_background_color_callback()
{
    if(empty(get_option('koboldcoupon_plugin_style_textfiels')['background_color'])){
        $background_color ='#ffffff';
    }else{
        $background_color = get_option('koboldcoupon_plugin_style_textfiels')['background_color'];
    };
    
    // Render the output
    echo '<input type="color" id="style_background_color" name="koboldcoupon_plugin_style_textfiels[background_color]" value="' . $background_color . '"/>';
}

function koboldcoupon_textfield_style_font_color_callback()
{
    if(empty(get_option('koboldcoupon_plugin_style_textfiels')['font_color'])){
        $font_color ='#000000';
    }else{
        $font_color = get_option('koboldcoupon_plugin_style_textfiels')['font_color'];
    };
    
    // Render the output
    echo '<input type="color" id="style_font_color" name="koboldcoupon_plugin_style_textfiels[font_color]" value="' . $font_color . '"/>';
}

function koboldcoupon_textfield_style_logo_callback()
{
    if(empty(get_option('koboldcoupon_plugin_style_textfiels')['logo'])){
        $logo ='';
    }else{
        $logo = get_option('koboldcoupon_plugin_style_textfiels')['logo'];
    };
    
    echo '<input id="style_logo" type="text" size="36" name="koboldcoupon_plugin_style_textfiels[logo]" value="' . $logo . '" />';
    echo '<input id="upload_image_button" class="button button-primary" class="button" type="button" value="'.__('Upload Image','koboldcouponpro').'" />';
}

function koboldcoupon_plugin_sanitize_style_options($input)
{
    if( isset( $input['background_color'] ) )
        $new_input['background_color'] = sanitize_text_field($input['background_color']);
    if( isset( $input['font_color'] ) )
        $new_input['font_color'] = sanitize_text_field($input['font_color']);
    if( isset( $input['logo'] ) )
        $new_input['logo'] = esc_url_raw(strip_tags(stripslashes($input['logo'])));;
        
        return $new_input;
} 