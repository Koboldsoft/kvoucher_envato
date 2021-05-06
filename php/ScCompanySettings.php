<?php

function koboldcoupon_initialize_company_options()
{
    if (false == get_option('koboldcoupon_plugin_company_textfiels')) {
        add_option('koboldcoupon_plugin_company_textfiels');
    } // end if

    // First, we register a section. This is necessary since all future options must belong to a
    add_settings_section('company_settings_section', __( 'Company Options', 'koboldcouponpro'),'koboldcoupon_general_company_callback','koboldcoupon_plugin_company_textfiels');

    add_settings_field('company', __( 'Company', 'koboldcouponpro').'*', 'koboldcoupon_textfield_company_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('first_name',__( 'First Name', 'koboldcouponpro').'*','koboldcoupon_textfield_first_name_callback','koboldcoupon_plugin_company_textfiels','company_settings_section');

    add_settings_field('last_name',__( 'Last Name', 'koboldcouponpro').'*', 'koboldcoupon_textfield_last_name_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('street_name',__( 'Streetname', 'koboldcouponpro').'*', 'koboldcoupon_textfield_street_name_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('postal_code',__( 'Postal-Code', 'koboldcouponpro').'*', 'koboldcoupon_textfield_postal_code_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('city', __( 'City', 'koboldcouponpro').'*', 'koboldcoupon_textfield_city_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('country', __( 'Country', 'koboldcouponpro').'*', 'koboldcoupon_textfield_country_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('tax_number', __( 'Tax number', 'koboldcouponpro'), 'koboldcoupon_textfield_tax_number_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('phone_number', __( 'Phonenumber', 'koboldcouponpro').'*', 'koboldcoupon_textfield_phone_number_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('company_url', __( 'Company Domain', 'koboldcouponpro').'*', 'koboldcoupon_textfield_company_url_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('company_email',__( 'Company E-mail', 'koboldcouponpro').'*', 'koboldcoupon_textfield_company_email_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('validity', __( 'Validity', 'koboldcouponpro'), 'koboldcoupon_textfield_validity_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('currency', __( 'Currency', 'koboldcouponpro'), 'koboldcoupon_textfield_currency_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('shipping', __( 'Shipping', 'koboldcouponpro').'*', 'koboldcoupon_textfield_company_shipping_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');
    
    add_settings_field('shipping_costs',__( 'Shipping costs by post', 'koboldcouponpro'), 'koboldcoupon_textfield_shipping_costs_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    add_settings_field('value_added_tax',__( 'Value added tax in percent', 'koboldcouponpro').'*', 'koboldcoupon_textfield_value_added_tax_callback', 'koboldcoupon_plugin_company_textfiels', 'company_settings_section');

    // Finally, we register the fields with WordPress

    register_setting('koboldcoupon_plugin_company_textfiels', 'koboldcoupon_plugin_company_textfiels', 'koboldcoupon_plugin_sanitize_company_options');
} // end koboldcoupon_initialize_company_options
add_action('admin_init', 'koboldcoupon_initialize_company_options');

/*
 * ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------
 */
function koboldcoupon_general_company_callback()
{
    _e( 'Register your company data.', 'koboldcouponpro');
    
}

// end koboldcoupon_general_company_callback

/*
 * ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------
 */
function koboldcoupon_textfield_company_callback()
{
    
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['company'])) {
        $company = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
        
    } else {
        $company = get_option('koboldcoupon_plugin_company_textfiels')['company'];
        $msg = '';
    }
    ;

    $html = '<input type="text" id="show_company" name="koboldcoupon_plugin_company_textfiels[company]" value="' . esc_attr($company) . '" required/>'.$msg;

    echo $html;
}

function koboldcoupon_textfield_shipping_costs_callback()
{
    
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['shipping_costs'])) {
        
        $shipping_costs = '';
        
    } else {
        
        $shipping_costs = get_option('koboldcoupon_plugin_company_textfiels')['shipping_costs'];
        
    }
    
    $html = '<input type="text" id="shipping_costs" maxlength="8" size="8" name="koboldcoupon_plugin_company_textfiels[shipping_costs]" value="' . esc_attr($shipping_costs) . '"/> <b id="curr_shipping">€</b>';
    
    echo $html;
}

// end koboldcoupon_textfield_company_callback
function koboldcoupon_textfield_first_name_callback($args)
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['first_name'])) {
        $first_name = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $first_name = get_option('koboldcoupon_plugin_company_textfiels')['first_name'];
    }
    ;

    $html = '<input type="text" id="show_firstname" name="koboldcoupon_plugin_company_textfiels[first_name]" value="' . esc_attr($first_name) . '" required/>'.$msg;

    echo $html;
}

// end koboldcoupon_textfield_first_name_callback
function koboldcoupon_textfield_last_name_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['last_name'])) {
        $last_name = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $last_name = get_option('koboldcoupon_plugin_company_textfiels')['last_name'];
        $msg = '';
    }
    ;

    $html = '<input type="text" id="last_name" name="koboldcoupon_plugin_company_textfiels[last_name]" value="' . esc_attr($last_name) . '" required/>'.$msg;

    echo $html;
}

// end koboldcoupon_textfield_first_name_callback
function koboldcoupon_textfield_street_name_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['street_name'])) {
        $street_name = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $street_name = get_option('koboldcoupon_plugin_company_textfiels')['street_name'];
        $msg = '';
    }
    ;

    $html = '<input type="text" id="street_name" name="koboldcoupon_plugin_company_textfiels[street_name]" value="' . esc_attr($street_name) . '" required/>'.$msg;

    echo $html;
}

// end koboldcoupon_textfield_street_name_callback

//
function koboldcoupon_textfield_postal_code_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['postal_code'])) {
        $postal_code = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $postal_code = get_option('koboldcoupon_plugin_company_textfiels')['postal_code'];
        $msg = '';
    }
    ;

    $html = '<input type="text" id="postal_code" name="koboldcoupon_plugin_company_textfiels[postal_code]" value="' . esc_attr($postal_code) . '" required/>'.$msg;

    echo $html;
}

// end koboldcoupon_textfield_post_code_callback
function koboldcoupon_textfield_city_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['city'])) {
        $city = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $city = get_option('koboldcoupon_plugin_company_textfiels')['city'];
        $msg = '';
    }
    ;

    $html = '<input type="text" id="postal_code" name="koboldcoupon_plugin_company_textfiels[city]" value="' . esc_attr($city) . '" required/>'.$msg;

echo $html;
}

function koboldcoupon_textfield_country_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['country'])) {
        $country = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $country = get_option('koboldcoupon_plugin_company_textfiels')['country'];
        $msg = '';
    }
    ;
    
    $html = '<input type="text" id="country" name="koboldcoupon_plugin_company_textfiels[country]" value="' . esc_attr($country) . '" required/>'.$msg;
    
    echo $html;
}

// end koboldcoupon_textfield_city_callback
function koboldcoupon_textfield_tax_number_callback($args)
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['tax_number'])) {
        $tax_number = '';
        
    } else {
        $tax_number = get_option('koboldcoupon_plugin_company_textfiels')['tax_number'];
        
    }

    $html = '<input type="text" id="postal_code" name="koboldcoupon_plugin_company_textfiels[tax_number]" value="' . esc_attr($tax_number) . '"/>';

    echo $html;
}

// end koboldcoupon_textfield_tax_number_callback
function koboldcoupon_textfield_phone_number_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['phone_number'])) {
        $phone_number = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $phone_number = get_option('koboldcoupon_plugin_company_textfiels')['phone_number'];
        $msg = '';
    }
    ;

    $html = '<input type="text" id="postal_code" name="koboldcoupon_plugin_company_textfiels[phone_number]" value="' . esc_attr($phone_number) . '" required/>'.$msg;

    echo $html;
}

// end koboldcoupon_textfield_phone_number_callback
function koboldcoupon_textfield_company_url_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['company_url'])) {
        $company_url = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $company_url = get_option('koboldcoupon_plugin_company_textfiels')['company_url'];
        $msg = '';
    }
    ;

    $html = '<input type="text" id="company_url" name="koboldcoupon_plugin_company_textfiels[company_url]" value="' . esc_attr($company_url) . '" required/>'.$msg;

    echo $html;
}

// end koboldcoupon_textfield_companx_url_callback
function koboldcoupon_textfield_company_email_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['company_email'])) {
        $company_email = '';
        $msg = ' <small style="color:red">'.__( 'required', 'koboldcouponpro').'!<small/>';
    } else {
        $company_email = get_option('koboldcoupon_plugin_company_textfiels')['company_email'];
        $msg = '';
    }
    ;

    $html = '<input type="email" id="company_email" name="koboldcoupon_plugin_company_textfiels[company_email]" value="' . esc_attr($company_email) . '" required/>'.$msg;

    echo $html;
}

// end koboldcoupon_textfield_company_email_callback

function koboldcoupon_textfield_company_shipping_callback(){
    
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['shipping'])) {
        
        $kind_of_shipping = 'post+email';
        
    } else {
        
        $kind_of_shipping = get_option('koboldcoupon_plugin_company_textfiels')['shipping'];
        
    }

    
    $html = '<p><input type="radio" id="post+email" name="koboldcoupon_plugin_company_textfiels[shipping]" value="post+email" '.($kind_of_shipping == 'post+email' ? "checked" : "").'><label for="post+email">Post + E-mail</label></p>
             <p><input type="radio" id="post" name="koboldcoupon_plugin_company_textfiels[shipping]" value="post" '.($kind_of_shipping == 'post' ? "checked" : "").'><label for="male">Post</label></p>
             <p><input type="radio" id="email" name="koboldcoupon_plugin_company_textfiels[shipping]" value="email" '.($kind_of_shipping == 'email' ? "checked" : "").'><label for="email">E-mail</label></p>';
    
    echo $html;
    
}

function koboldcoupon_textfield_currency_callback(){
    
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['currency'])) {
        
        $currency = 'euro';
        
    } else {
        
        $currency = get_option('koboldcoupon_plugin_company_textfiels')['currency'];
        
    }
    
    
    $html = '<select name="koboldcoupon_plugin_company_textfiels[currency]" id="currency" onchange="setCurrencyShippingCost()">
                <option value="euro" '.($currency == 'euro' ? "selected" : "").'>€</option>
                <option value="dollar" '.($currency == 'dollar' ? "selected" : "").'>$</option>
                <option value="british_pound" '.($currency == 'british_pound' ? "selected" : "").'>£</option>
            </select>';
    
    echo $html;
    
}

function koboldcoupon_textfield_validity_callback(){
    
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['validity'])) {
        
        $validity = '3';
        
    } else {
        
        $validity = get_option('koboldcoupon_plugin_company_textfiels')['validity'];
        
    }
    
    
    $html = '<select name="koboldcoupon_plugin_company_textfiels[validity]" id="validity">
                <option value="1" '.($validity == '1' ? "selected" : "").'>1</option>
                <option value="2" '.($validity == '2' ? "selected" : "").'>2</option>
                <option value="3" '.($validity == '3' ? "selected" : "").'>3</option>
                <option value="4" '.($validity == '4' ? "selected" : "").'>4</option>
                <option value="5" '.($validity == '5' ? "selected" : "").'>5</option>
            </select> '.__('Year(s)','koboldcouponpro');
    
    echo $html;
    
}


function koboldcoupon_textfield_value_added_tax_callback()
{
    if (empty(get_option('koboldcoupon_plugin_company_textfiels')['value_added_tax'])) {
        
        $value_added_tax = 00;
        
    } else {
        
        $value_added_tax = get_option('koboldcoupon_plugin_company_textfiels')['value_added_tax'];
    }

    $html = '<input type="number" step=0.1 id="value_added_tax" min="0" max="100" maxlength="2" size="2" name="koboldcoupon_plugin_company_textfiels[value_added_tax]" value="' . esc_attr($value_added_tax) . '" required/>%';

    echo $html;
    
}

function koboldcoupon_plugin_sanitize_company_options($input)
{
    $new_input = array();
    if (isset($input['company']))
        $new_input['company'] = sanitize_text_field($input['company']);

    if (isset($input['first_name']))
        $new_input['first_name'] = sanitize_text_field($input['first_name']);

    if (isset($input['last_name']))
        $new_input['last_name'] = sanitize_text_field($input['last_name']);

    if (isset($input['street_name']))
        $new_input['street_name'] = sanitize_text_field($input['street_name']);

    if (isset($input['postal_code']))
        $new_input['postal_code'] = sanitize_text_field($input['postal_code']);

    if (isset($input['city']))
        $new_input['city'] = sanitize_text_field($input['city']);
    
    if (isset($input['country']))
        $new_input['country'] = sanitize_text_field($input['country']);
    
    if (isset($input['tax_number']))
        $new_input['tax_number'] = sanitize_text_field($input['tax_number']);

    if (isset($input['phone_number']))
        $new_input['phone_number'] = sanitize_text_field($input['phone_number']);

    if (isset($input['company_url']))
        $new_input['company_url'] = strip_tags(stripslashes($input['company_url']));

    if (isset($input['company_email']))
        $new_input['company_email'] = sanitize_email(strip_tags(stripslashes($input['company_email'])));

    if (isset($input['value_added_tax']))
        
        $new_input['value_added_tax'] =  koboldcouponPregMatchValueaddedTax($input['value_added_tax']);
    
    if (isset($input['shipping']))
            
        $new_input['shipping'] =  sanitize_text_field($input['shipping']);
    
    if (isset($input['currency']))
            
        $new_input['currency'] =  sanitize_text_field($input['currency']);
    
        if (isset($input['validity']))
            
            $new_input['validity'] =  sanitize_text_field($input['validity']);
    
    if (isset($input['shipping_costs']))
            
        $new_input['shipping_costs'] =  koboldcouponPregMatchShippingCosts($input['shipping_costs']);

    return $new_input;
}

function koboldcouponPregMatchShippingCosts($shipping_costs){
    
    $shipping_costs = str_replace('.', ',', $shipping_costs);
    
    if(preg_match('/^[0-9]*\,[0-9]{0,2}$/', $shipping_costs)){
        
        return $shipping_costs;
        
    }
    
    if(preg_match('/^[0-9]*$/', $shipping_costs)){
        
        return $shipping_costs;
        
    }
    
}

function koboldcouponPregMatchValueaddedTax($valueaddedtax){
    
    $valueaddedTax = str_replace('.', ',', $valueaddedtax);
    
    if(preg_match('/^[0-9]*\.[0-9]{0,2}$/', $valueaddedtax)){
        
        return $valueaddedtax;
        
    }
    
    if(preg_match('/^[0-9]*$/', $valueaddedtax)){
        
        return $valueaddedtax;
        
    }
    
}
    
