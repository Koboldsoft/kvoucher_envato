<?php

namespace KoboldcouponFrontendStuff;

if (! class_exists('KoboldcouponForm')) {

    class KoboldcouponForm

    {
        private static function kvoucherPostToSession(){
            
            foreach ($_POST as $content => $value) {
                
                $_SESSION[$content] = $value;
                
            }
            
        }

        private function kvoucherButton($value, $label)
        {
            $output = '<button style="margin:5px;" type="submit" value="' . $value . '" name="action">' . $label . '</button>';

            return $output;
        }

        // end kvoucherButton($value, $label)
        private function kvoucherSubStr($string, $length)
        {
            return substr($string, 0, $length);
        }

        // end substr($string, 0, $length);
        private static function kvoucherCheckBillingAdressDeliveryShipping()
        {
            $output = '<label for="check">'.__('Specify a different delivery address','kvoucherpro').'</label>

                       <input type="hidden" name="checkbox_del_shipping_adress" value="0" />

                       <input type="checkbox" onchange="toggleDisableDelShippingAdress(this)" name="checkbox_del_shipping_adress" value="1" ' . (! empty($_SESSION['checkbox_del_shipping_adress']) || $_SESSION['checkbox_del_shipping_adress'] == '1' ? 'checked' : '') . ' id="checkbox_del_shipping_adress">';

            return $output;
        }
        private static function kvoucherCheckTermsOfServiceData(){
            
            $output = true;
            
            $terms_of_service_data = get_option('kvoucher_plugin_terms_of_service_textfields');
            
            if (!is_array($terms_of_service_data) && !isset($terms_of_service_data['terms_of_service'])) {
                
                $output = false;
            }
            
            return $output;
        }
        // end kvoucherCheckBillingAdressDeliveryShipping()
        private static function kvoucherCheckAllCompanyData()

        {
            $output = true;

            $company_data = get_option('kvoucher_plugin_company_textfiels');
            
            $paypal_data = get_option('kvoucher_plugin_paypal_textfiels');
            
            $required_data_company = array(
                'company',
                'first_name',
                'last_name',
                'street_name',
                'postal_code',
                'city',
                'phone_number',
                'company_url',
                'value_added_tax',
                'company_email'
            );

            $required_data_paypal = array(
                'paypal_client_id'
            );

            foreach ($required_data_company as $required) {

                if (@$company_data[$required] == '' || @$company_data[$required] == null) {

                    $output = false;
                }
            }

            foreach ($required_data_paypal as $required) {

                if (@$paypal_data[$required] == '' || @$paypal_data[$required] == null) {

                    $output = false;
                }
            }
            
            if  (!in_array  ('curl', get_loaded_extensions())) {
                
                $output = false;
                
            }

            return $output;
        }
        private static function kvoucherGetCurrencyPaypalHTMLoutput(){
            
            $currency = get_option('kvoucher_plugin_company_textfiels')['currency'];
            
            switch ($currency) {
                case (empty($currency) || $currency == null || $currency == ''):
                    return "€";
                    break;
                    
                case 'euro':
                    return "€";
                    break;
                    
                case 'dollar':
                    return "$";
                    break;
                    
                case 'british_pound':
                    return "£";
                    break;
                    
            }
        }

        // end kvoucherCheckAllCompanyData()
        private static function kvoucherBillingAdressPrice()
        {
            $currency = self::kvoucherGetCurrencyPaypalHTMLoutput();
            
            $output = '<style>
                        .radio-toolbar input[type="radio"] { opacity: 0;position: fixed;width: 0; }
                        
                        .radio-toolbar label {display: inline-block; background-color: #d3d7cf; width: fit-content;  width: -moz-fit-content; padding: 2px 5px; margin: 2px; 100px; border-radius: 4px; border: 1px solid #fff; color: #000; }

                        .radio-toolbar label:hover  {background-color: #babdb6; border: 1px solid #fff; }

                        .radio-toolbar input[type="radio"]:checked + label { background-color: #2e3436; padding: 3px; border: 1px solid #fff; color: #fff; }

                        input[type=text], select, textarea{ width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; resize: vertical; }
                       
                      </style>';
            
            $output .= '<fieldset class="radio-toolbar">

                       <legend>'.__('Value','kvoucherpro').'</legend>

                       <input class="price" type="radio" id="twenty" name="price" value="20" ' . (empty($_SESSION['price']) || $_SESSION['price'] == '20' ? 'checked' : '') . '>
                
    	               <label for="twenty">20,00 '.$currency.'</label>
                
    	               <input class="price" type="radio" id="thirty" name="price" value="30" ' . ($_SESSION['price'] == '30' ? 'checked' : '') . '>
                
    	               <label for="thirty">30,00 '.$currency.'</label>
                
    	               <input class="price" type="radio" id="fifty" name="price" value="50" ' . ($_SESSION['price'] == '50' ? 'checked' : '') . '>
                
    	               <label for="fifty">50,00 '.$currency.'</label>
                
    	               <input class="price" type="radio" id="seventyfive" name="price" value="75" ' . ($_SESSION['price'] == '75' ? 'checked' : '') . '>
                
    	               <label for="seventyfive">75,00 '.$currency.'</label>
                
    	               <input class="price" type="radio" id="onehundred" name="price" value="100" ' . ($_SESSION['price'] == '100' ? 'checked' : '') . '>
                
    	               <label for="onehundred">100,00 '.$currency.'</label>
                
    	               <input class="price" type="radio" id="onehundredfifty" name="price" value="150" ' . ($_SESSION['price'] == '150' ? 'checked' : '') . '>
                
    	               <label for="onehundredfifty">150,00 '.$currency.'</label>
                
    	               <input class="price" type="radio" id="twohundred" name="price" value="200" ' . ($_SESSION['price'] == '200' ? 'checked' : '') . '>
                
    	               <label for="twohundred">200,00 '.$currency.'</label>

                       <input type="hidden" id="post" name="shipping" value="E-mail">
                
	                   </fieldset>';

            return $output;
        }

        // end kvoucherBillingAdressPrice()
        private static function kvoucherBillingAdressAdress()
        {
            $output = '<fieldset>
                
                        <legend>'.__('Billing address','kvoucherpro').'</legend>
                
                        <label for="privat">'.__('Privat','kvoucherpro').':</label><input type="radio" onchange="toggleDisableDelCompany(this)" id="radiobox_company_dis" id="privat" name="kind_of_adress" value="Privat" ' . (empty($_SESSION['kind_of_adress']) || $_SESSION['kind_of_adress'] == 'Privat' ? 'checked' : '') . '>
                
                        <label for="firma">'.__('Company','kvoucherpro').':</label><input class="kind_of_adress" type="radio" onchange="toggleEnableDelCompany(this)" id="radiobox_company_en" name="kind_of_adress" value="Firma" ' . ($_SESSION['kind_of_adress'] == 'Firma' ? 'checked' : '') . '><br>
                
		                <label for="herr">'.__('Mr','kvoucherpro').':</label><input type="radio" id="herr" name="title" value="Herr" ' . (empty($_SESSION['title']) || $_SESSION['title'] == 'Herr' ? 'checked' : '') . '>
                
                        <label for="frau">'.__('Mrs','kvoucherpro').':</label><input type="radio" id="frau" name="title" value="Frau" ' . ($_SESSION['title'] == 'Frau' ? 'checked' : '') . '>

                        <p ' . ($_SESSION['kind_of_adress'] == 'Firma' ? 'style="display:block"' : 'disabled style="display:none"') . ' id="company_input_field"><label style="width: 140px;" for="company">'.__('Company','kvoucherpro').': </label><input type="text" maxlength="40" name="company" id="company" value="' . (! empty($_SESSION['company']) ? $_SESSION['company'] : '') . '"  placeholder="'.__('Company','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="fname">'.__('First Name','kvoucherpro').'*: </label><input type="text" maxlength="30" name="fname" id="fname" required="required" value="' . (! empty($_SESSION['fname']) ? $_SESSION['fname'] : '') . '"  placeholder="'.__('First Name','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="nname">'.__('Last Name','kvoucherpro').'*: </label><input type="text" maxlength="30" name="nname" id="nname" required="required" value="' . (! empty($_SESSION['nname']) ? $_SESSION['nname'] : '') . '" placeholder="'.__('Last Name','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="streetname">'.__('Street + No.','kvoucherpro').'*:</label><input type="text" maxlength="50"  name="streetname" id="streetname" value="' . (! empty($_SESSION['streetname']) ? $_SESSION['streetname'] : '') . '" required="required" placeholder="'.__('Street + No.','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="plz">'.__('Postal-Code','kvoucherpro').'*:</label><input type="text" name="plz" id="plz" value="' . (! empty($_SESSION['plz']) ? $_SESSION['plz'] : '') . '" required="required" maxlength="6" placeholder="'.__('Postal-Code','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="city">'.__('City','kvoucherpro').'*:</label><input type="text" maxlength="50" name="city"  id="city" value="' . (! empty($_SESSION['city']) ? $_SESSION['city'] : '') . '" required="required" placeholder="'.__('City','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="country">'.__('Country','kvoucherpro').'*:</label><input type="text" maxlength="50" name="country" id="country" value="' . (! empty($_SESSION['country']) ? $_SESSION['country'] : 'Deutschland') . '" required="required" value="Deutschland" placeholder="'.__('Country','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="phone">'.__('Phone','kvoucherpro').':</label><input type="tel" maxlength="20" name="phone" id="phone" value="' . (! empty($_SESSION['phone']) ? $_SESSION['phone'] : '') . '" placeholder="'.__('Phone','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="email">'.__('E-mail','kvoucherpro').'*:</label><input type="email" maxlength="50" name="email" id="email" value="' . (! empty($_SESSION['email']) ? $_SESSION['email'] : '') . '" required="required" placeholder="'.__('E-mail','kvoucherpro').'" style="max-width: 300px;"></p>

                        </fieldset>';

            return $output;
        }

        // end kvoucherBillingAdressAdress()
        private static function kvoucherBillingAdressDeliveryShipping()

        {
            $output = '<fieldset id="fieldset_del_shipping_adress"' . (! empty($_SESSION['checkbox_del_shipping_adress']) || $_SESSION['checkbox_del_shipping_adress'] == '1' ? 'style="display:block' : 'disabled style="display:none') . '">

                      <legend>'.__('Differing Shipping Address','kvoucherpro').'</legend>

                      <label for="dif_herr">'.__('Mr','kvoucherpro').':</label><input type="radio" id="dif_herr" name="dif_title" value="Herr" ' . (empty($_SESSION['dif_title']) || $_SESSION['dif_title'] == 'Herr' ? 'checked' : '') . '>
                
                      <label for="dif_frau">'.__('Mrs','kvoucherpro').':</label><input type="radio" id="dif_frau" name="dif_title" value="Frau" ' . ($_SESSION['dif_title'] == 'Frau' ? 'checked' : '') . '>

                      <p><label style="width: 140px;" for="dif_fname">'.__('First Name','kvoucherpro').'*: </label><input type="text" name="dif_fname" id="dif_fname" value ="' . (! empty($_SESSION['dif_fname']) ? $_SESSION['dif_fname'] : '') . '" required="required"  placeholder="'.__('First Name','kvoucherpro').'" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_nname">'.__('Last Name','kvoucherpro').'*: </label><input type="text" name="dif_nname" id="dif_nname" value ="' . (! empty($_SESSION['dif_nname']) ? $_SESSION['dif_nname'] : '') . '" required="required"  placeholder="'.__('Last Name','kvoucherpro').'" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_streetname">'.__('Street + No.','kvoucherpro').'*:</label><input type="text"  name="dif_streetname" id="dif_streetname" value ="' . (! empty($_SESSION['dif_nname']) ? $_SESSION['dif_nname'] : '') . '" placeholder="'.__('Street + No.','kvoucherpro').'" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_plz">'.__('Postal-Code','kvoucherpro').'*:</label><input type="text" name="dif_plz" id="dif_plz" value ="' . (! empty($_SESSION['dif_plz']) ? $_SESSION['dif_plz'] : '') . '" required="required" maxlength="6" placeholder="'.__('Postal-Code','kvoucherpro').'" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_city">'.__('City','kvoucherpro').'*:</label><input type="text" name="dif_city"  id="dif_city" value ="' . (! empty($_SESSION['dif_city']) ? $_SESSION['dif_city'] : '') . '" required="required" placeholder="'.__('City','kvoucherpro').'" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_country">'.__('Country','kvoucherpro').'*:</label><input type="text" name="dif_country" id="dif_country" value ="' . (! empty($_SESSION['dif_country']) ? $_SESSION['dif_country'] : 'Deutschland') . '" required="required" value="Deutschland" placeholder="'.__('Country','kvoucherpro').'" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_email">'.__('E-mail','kvoucherpro').'*:</label><input type="email" name="dif_email" id="dif_email" value ="' . (! empty($_SESSION['dif_email']) ? $_SESSION['dif_email'] : '') . '" required="required" placeholder="'.__('E-mail','kvoucherpro').'" style="max-width: 300px;"></p>

                      </fieldset>';

            return $output;
        }

        // end kvoucherBillingAdressDeliveryShipping()
        private static function kvoucherPayCoupon(){
            
            $currency = self::kvoucherGetCurrencyPaypalHTMLoutput();
            
            $wert = floatval($_SESSION['price']);
            
            if($_SESSION['shipping'] == 'Post'){$shipping_costs = floatval(str_replace(',', '.', $_SESSION['shipping_costs']));};
            
            if($_SESSION['shipping'] == 'E-mail'){$_SESSION['shipping_costs'] = 0.00;};
            
            if($_SESSION['kind_of_adress'] == 'Privat'){ unset($_SESSION['company']);};
            
            $summe = $wert + $shipping_costs;
            
            $output = '<fieldset>

                        <legend>'.__('Voucher for','kvoucherpro').'</legend>

                       <p><i>'.__('Dates appear on the voucher','kvoucherpro').'</i></p>

                        '.__('First Name','kvoucherpro').': ' . $_SESSION['for_fname'] . '<br>

                        '.__('Last Name','kvoucherpro').': ' . $_SESSION['for_nname'] . '<br>
            
                        '.__('Occasion','kvoucherpro').': ' . $_SESSION['occasion'] . '<br>
            
                        '.__('Value','kvoucherpro').': ' . number_format($_SESSION['price'], 2, ',', '.') . ' '.$currency.'</fieldset>';

            $output .= '<fieldset>

                     <legend>'.__('Billing Adress','kvoucherpro').'</legend>

                        '.( !empty( $_SESSION['company'] ) ? __( 'Company','kvoucherpro').': '.$_SESSION['company'].'<br>'  :  '' ).'

                        '.__('First Name','kvoucherpro').': ' . $_SESSION['fname'] . '<br>

                        '.__('Last Name','kvoucherpro').': ' . $_SESSION['nname'] . '<br>

                        '.__('Street','kvoucherpro').': ' . $_SESSION['streetname'] . '<br>

                        '.__('City','kvoucherpro').': ' . $_SESSION['plz'] . ' ' . $_SESSION['city'] . '<br>

                        '.__('Country','kvoucherpro').': ' . $_SESSION['country'] . '<br>' . (! empty($_SESSION['phone']) ? __('Phone','kvoucherpro').': ' . $_SESSION['phone'] . '<br>' : '') . '

                        '.__('E-mail','kvoucherpro').': ' . $_SESSION['email'] . '<br>
            
                        '.__('Shipping costs','kvoucherpro').': ' . number_format($_SESSION['shipping_costs'], 2, ',', '.') . ' '.$currency.'<br>
            
                        '.__('Total','kvoucherpro').': ' . number_format($summe, 2, ',', '.') . ' '.$currency.'<br>
            
                        '.__('Shipping','kvoucherpro').': ' . $_SESSION['shipping'] . '<br></fieldset>';

            $output .= '<fieldset>
                        
                        <legend>'.__('Shipping Adress','kvoucherpro').'</legend>' . 
                        
                        ($_SESSION['checkbox_del_shipping_adress'] == '1' ? __('First name','kvoucherpro').': ' . $_SESSION['dif_fname'] . '<br>' : __('First name','kvoucherpro').': ' . $_SESSION['fname'] . '<br>') . 
                        
                        ($_SESSION['checkbox_del_shipping_adress'] == '1' ? __('Last name','kvoucherpro').': ' . $_SESSION['dif_nname'] . '<br>' : __('Last name','kvoucherpro').': ' . $_SESSION['nname'] . '<br>') .
                        
                        ($_SESSION['checkbox_del_shipping_adress'] == '1' ? __('Street','kvoucherpro').': ' . $_SESSION['dif_streetname'] . '<br>' : __('Street','kvoucherpro').': ' . $_SESSION['streetname'] . '<br>') .
                        
                        ($_SESSION['checkbox_del_shipping_adress'] == '1' ? __('City','kvoucherpro').': ' . $_SESSION['dif_plz'] . ' ' . $_SESSION['dif_city'] . '<br>' : __('City','kvoucherpro').': ' . $_SESSION['plz'] . ' ' . $_SESSION['city'] . '<br>') .
                        
                        ($_SESSION['checkbox_del_shipping_adress'] == '1' ? __('Country','kvoucherpro').': ' . $_SESSION['dif_country'] . '<br>' : __('Country','kvoucherpro').': ' . $_SESSION['country'] . '<br>') .
                        
                        ($_SESSION['checkbox_del_shipping_adress'] == '1' ? __('E-mail','kvoucherpro').': ' . $_SESSION['dif_email'] . '<br>' : __('E-mail','kvoucherpro').': ' . $_SESSION['email'] . '<br>') . __('Shipping','kvoucherpro').': ' . $_SESSION['shipping'] . 
                        
                        '</fieldset>';

            return $output;
        }

        // end kvoucherPayCoupon()
        private static function kvoucherTermsOfService()
        {
            $terms_of_service_data = get_option('kvoucher_plugin_terms_of_service_textfields');
            
            if (is_array($terms_of_service_data) && isset($terms_of_service_data['terms_of_service'])) {
                
                $terms = html_entity_decode(nl2br(get_option('kvoucher_plugin_terms_of_service_textfields')['terms_of_service']));
                
            }else{
                
                $terms = '';
            }
            
            $output .= '<p><label for="checkbox_terms_of_sevice">'.__('Please confirm our','kvoucherpro').' '.(self::kvoucherCheckTermsOfServiceData() == true ? '<a onclick="openToc();">'.__('terms and conditions','kvoucherpro').'</a>' : 'AGB´s').'</label>
                       <input type="checkbox" required="required" name="checkbox_terms_of_sevice" value="1" ' . (! empty($_SESSION['checkbox_terms_of_sevice']) || $_SESSION['checkbox_terms_of_sevice'] == '1' ? 'checked' : '') . ' id="checkbox_terms_of_sevice"></p>';

            $output .= '<div id="toc">

                            <div style="background-color: #ffffff; color: #000000;" id="toc_content">' . $terms . '</div>

                            <div style="background-color: #ffffff" id="toc_close"><a style="color: DodgerBlue;" onclick="closeToc();">'.__('close','kvoucherpro').'</a></div>
            
                        </div>';

            return $output;
        }
        
        private static function kvoucherThxForBuy(){
            
            $output = '<div id="thanks_message">

                               <p style="text-align: center; background-color: #ffffff; color: #000000;">'.__('Thank you for shopping at','kvoucherpro').' '. get_bloginfo( 'name' ) .'</p>

	                           <p style="text-align: center">

		                      <a style="color: DodgerBlue;" href="'.($_SERVER['REQUEST_URI']).'">'.__('Back to selection','kvoucherpro').'</a>

                      </div>';
                        
            return $output;         
            
        }

        // end kvoucherTermsOfService()
        
        private function kvoucherCouponFor(){
            
            $output = '<fieldset>
                        
                       <legend>'.__('Voucher for','kvoucherpro').'</legend>

                       <p><i>'.__('Dates appear on the voucher','kvoucherpro').'</i></p>

                       <label for="for_herr">'.__('Mr','kvoucherpro').':</label><input type="radio" id="for_herr" name="for_title" value="Herr" ' . (empty($_SESSION['for_title']) || $_SESSION['for_title'] == 'Herr' ? 'checked' : '') . '>
                
                        <label for="for_frau">'.__('Mrs','kvoucherpro').':</label><input type="radio" id="for_frau" name="for_title" value="Frau" ' . ($_SESSION['for_title'] == 'Frau' ? 'checked' : '') . '>
                
                        <p><label style="width: 140px;" for="for_fname">'.__('First Name','kvoucherpro').'*: </label><input type="text" maxlength="30" name="for_fname" id="for_fname" required="required" value="' . (! empty($_SESSION['for_fname']) ? $_SESSION['for_fname'] : '') . '"  placeholder="'.__('First Name','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="for_nname">'.__('Last Name','kvoucherpro').'*: </label><input type="text" maxlength="30" name="for_nname" id="for_nname" required="required" value="' . (! empty($_SESSION['for_nname']) ? $_SESSION['for_nname'] : '') . '" placeholder="'.__('Last Name','kvoucherpro').'" style="max-width: 300px;"></p>

                        <p><label style="width: 140px;" for="occasion">'.__('Occasion','kvoucherpro').': </label><input type="text" maxlength="30" name="occasion" id="occasion" value="' . (! empty($_SESSION['occasion']) ? $_SESSION['occasion'] : '') . '" placeholder="'.__('Occasion','kvoucherpro').'" style="max-width: 300px;"></p>
                
                        </fieldset>';
            
            return $output;
        }
        
        private static function kvoucherkindOfShipping()
        {
            $currency = self::kvoucherGetCurrencyPaypalHTMLoutput(); // load currency
            
            $show_shipping_costs = get_option('kvoucher_plugin_company_textfiels')['shipping_costs'];
            
            ( $show_shipping_costs == null || empty($show_shipping_costs) ? $show_shipping_costs = '0,00' : $show_shipping_costs = $show_shipping_costs);
            
            // if shipping selectected in the admin area post+email
            if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'post+email') {
                
                $output = '<fieldset>
                
                       <legend>'.__('Shipping','kvoucherpro').'</legend>
                
                            <input type="radio" id="post" name="shipping" value="Post" ' . (empty($_SESSION['shipping']) || $_SESSION['shipping'] == 'Post' ? 'checked' : '') . '>
                           
    	                    <label for="post">'.__('via Post','kvoucherpro').' <i>( + '.$show_shipping_costs.' '.$currency.' '.__('Shipping','kvoucherpro').')</i></label>
                           
    	                    <input type="radio" id="email" name="shipping" value="E-mail" ' . ($_SESSION['shipping'] == 'E-mail' ? 'checked' : '') . '>
    	                   
    	                    <label for="email">'.__('via E-mail (PDF)','kvoucherpro').'</label>
            
                       </fieldset>';
            }
            
            // if shipping selectected in the admin area email
            if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'email') {
                
                $output = '<fieldset>
                    
                       <legend>'.__('Shipping','kvoucherpro').'</legend>
                           
                           <p>'.__('The dispatch takes place via E-mail (PDF)','kvoucherpro').'</p>
                               
                       </fieldset>';

                $output .= '<input type="hidden" id="email" name ="shipping" value="E-mail">';

                $output .= '<input type="hidden" id="shipping_costs" name ="shipping_costs" value="0.00">';
            }

            // if shipping selectected in the admin area post
            if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'post') {
                
                $output = '<fieldset>
                    
                       <legend>'.__('Shipping','kvoucherpro').'</legend>
                           
                           <p>'.__('The dispatch takes place by Post','kvoucherpro').'</p>
                               
                       </fieldset>';

                $output .= '<input type="hidden" id="post" name ="shipping" value="Post">';

                $output .= '<input type="hidden" id="shipping_costs" name ="shipping_costs" value="'.get_option('kvoucher_plugin_company_textfiels')['shipping_costs'].'">';
            }

            return $output;
        }
        
        private static function kvoucherGetCurrencyforPaypalApi(){
            
            $currency = get_option('kvoucher_plugin_company_textfiels')['currency'];
            
            switch ($currency) {
                case (empty($currency) || $currency == null || $currency == ''):
                    return "EUR";
                    break;
                
                case 'euro':
                    return "EUR";
                    break;
                    
                case 'dollar':
                    return "USD";
                    break;
                    
                case 'british_pound':
                    return "GBP";
                    break;
                    
            }
            
        }
        
        private static function getSessionData(){
            
            $data = array(
                
                'price' => $_SESSION['price'],
                
                'shipping' => $_SESSION['shipping'],
                
                'shipping_costs' => str_replace(',', '.', $_SESSION['shipping_costs']),
                
                'kind_of_adress' => $_SESSION['kind_of_adress'],
                
                'for_title' => $_SESSION['for_title'],
                
                'for_fname' => self::kvoucherSubStr($_SESSION['for_fname'], 30),
                
                'for_nname' => self::kvoucherSubStr($_SESSION['for_nname'], 30),
                
                'occasion' => self::kvoucherSubStr($_SESSION['occasion'], 30),
                
                'title' => $_SESSION['title'],
                
                'fname' => self::kvoucherSubStr($_SESSION['fname'], 30),
                
                'nname' => self::kvoucherSubStr($_SESSION['nname'], 30),
                
                'company' => self::kvoucherSubStr($_SESSION['company'], 40),
                
                'streetname' => self::kvoucherSubStr($_SESSION['streetname'], 50),
                
                'plz' => self::kvoucherSubStr($_SESSION['plz'], 8),
                
                'city' => self::kvoucherSubStr($_SESSION['city'], 50),
                
                'country' => self::kvoucherSubStr($_SESSION['country'], 50),
                
                'phone' => self::kvoucherSubStr($_SESSION['phone'], 20),
                
                'email' => self::kvoucherSubStr($_SESSION['email'], 50),
                
                'dif_title' => $_SESSION['dif_title'],
                
                'dif_fname'=> self::kvoucherSubStr($_SESSION['dif_fname'], 30),
                
                'dif_nname' => self::kvoucherSubStr($_SESSION['dif_nname'], 30),
                
                'dif_streetname' => self::kvoucherSubStr($_SESSION['dif_streetname'], 50),
                
                'dif_plz' => self::kvoucherSubStr($_SESSION['dif_plz'], 8),
                
                'dif_city' => self::kvoucherSubStr($_SESSION['dif_city'], 50),
                
                'dif_country' => self::kvoucherSubStr($_SESSION['dif_country'], 50),
                
                'dif_email' => self::kvoucherSubStr($_SESSION['dif_email'], 50),
                
                'checkbox_del_shipping_adress' => $_SESSION['checkbox_del_shipping_adress'],
                
                'action' => $_SESSION['action'],
                
                );
                
            return base64_encode( http_build_query( $data ) );
                
         }

        // end kvoucherkindOfShipping()
        private static function paypalButtons_old()
        {
            
            $currency = self::kvoucherGetCurrencyforPaypalApi();
            
            $shipping_costs = floatval(str_replace(',', '.', $_SESSION['shipping_costs']));

            $value = floatval($_SESSION['price']);

            $summe = $shipping_costs + $value;

            $output .= sprintf('<script src="https://www.paypal.com/sdk/js?client-id=%s&currency='.$currency.'"></script>',get_option('kvoucher_plugin_paypal_textfiels')['paypal_client_id']);

            $output .= '<fieldset id="paypal-button-container"></fieldset>';

            $output .= '<script>
                     paypal.Buttons({
                         createOrder: function(data, actions) {
                             // This function sets up the details of the transaction, including the amount and line item details.
                             return actions.order.create({
                                 purchase_units: [{
                                     amount: {
                                         value: "' . $summe . '"
                                     }
                                 }]
                             });
                         },
                         onApprove: function(data, actions) {
                             // This function captures the funds from the transaction.
                             return actions.order.capture().then(function(details) {
                                 // This function shows a transaction success message to your buyer.
                                             
                                 var price = "' . $_SESSION['price'] . '";
                                     
                                 var shipping = "' . $_SESSION['shipping'] . '";

                                 var shipping_costs = "' . str_replace(',', '.', $_SESSION['shipping_costs']) . '";
                                     
			                     var kind_of_adress = "' . $_SESSION['kind_of_adress'] . '";

                                 var for_title = "' . $_SESSION['for_title'] . '";

                                 var for_fname = "' . self::kvoucherSubStr($_SESSION['for_fname'], 30) . '";
			                         
			                     var for_nname = "' . self::kvoucherSubStr($_SESSION['for_nname'], 30) . '";
			                         
			                     var occasion ="' . self::kvoucherSubStr($_SESSION['occasion'], 30) . '";
			                         
			                     var title = "' . $_SESSION['title'] . '";
			                         
			                     var fname = "' . self::kvoucherSubStr($_SESSION['fname'], 30) . '";
			                         
			                     var nname = "' . self::kvoucherSubStr($_SESSION['nname'], 30) . '";

                                 var company = "' . self::kvoucherSubStr($_SESSION['company'], 40) . '";
			                         
			                     var streetname = "' . self::kvoucherSubStr($_SESSION['streetname'], 50) . '";
			                         
			                     var plz = "' . self::kvoucherSubStr($_SESSION['plz'], 8) . '";
			                         
			                     var city = "' . self::kvoucherSubStr($_SESSION['city'], 50) . '";
			                         
			                     var country = "' . self::kvoucherSubStr($_SESSION['country'], 50) . '";
			                         
			                     var phone = "' . self::kvoucherSubStr($_SESSION['phone'], 20) . '";
			                         
			                     var email = "' . self::kvoucherSubStr($_SESSION['email'], 50) . '";
			                         
			                     var dif_title = "' . $_SESSION['dif_title'] . '";
			                         
			                     var dif_fname = "' . self::kvoucherSubStr($_SESSION['dif_fname'], 30) . '";
			                         
			                     var dif_nname = "' . self::kvoucherSubStr($_SESSION['dif_nname'], 30) . '";
			                         
			                     var dif_streetname = "' . self::kvoucherSubStr($_SESSION['dif_streetname'], 50) . '";
			                         
			                     var dif_plz = "' . self::kvoucherSubStr($_SESSION['dif_plz'], 8) . '";
			                         
			                     var dif_city = "' . self::kvoucherSubStr($_SESSION['dif_city'], 50) . '";
			                         
			                     var dif_country = "' . self::kvoucherSubStr($_SESSION['dif_country'], 50) . '";
			                         
			                     var dif_email = "' . self::kvoucherSubStr($_SESSION['dif_email'], 50) . '";

                                 var checkbox_del_shipping_adress = "' . $_SESSION['checkbox_del_shipping_adress'] . '";
			                         
			                     var action = "' . $_SESSION['action'] . '";
			                         
                                 var saveusrdataurl = "' . plugins_url() . '/kvoucher/saveUsrData.php";
                                     
                                 saveUserData(price,shipping,shipping_costs,kind_of_adress,for_title,for_fname,for_nname,occasion,title,fname,nname,company,streetname,plz,city,country,phone,email,dif_title,dif_fname,dif_nname,dif_streetname,dif_plz,dif_city,dif_country,dif_email,action,checkbox_del_shipping_adress,saveusrdataurl);
                                     
                                 openThxMsg();

                             });
                         }
                     }).render("#paypal-button-container");
                     //This function displays Smart Payment Buttons on your web page.
                     </script>';

            return $output;
        }
        
        private static function paypalButtons()
        {
            $data = self::getSessionData();
            
            $currency = self::kvoucherGetCurrencyforPaypalApi();
            
            $shipping_costs = floatval(str_replace(',', '.', $_SESSION['shipping_costs']));
            
            $value = floatval($_SESSION['price']);
            
            $summe = $shipping_costs + $value;
            
            $output .= sprintf('<script src="https://www.paypal.com/sdk/js?client-id=%s&currency='.$currency.'"></script>',get_option('kvoucher_plugin_paypal_textfiels')['paypal_client_id']);
            
            $output .= '<fieldset id="paypal-button-container"></fieldset>';
            
            $output .= '<script>
                     paypal.Buttons({
                         createOrder: function(data, actions) {
                             // This function sets up the details of the transaction, including the amount and line item details.
                             return actions.order.create({
                                 purchase_units: [{
                                     amount: {
                                         value: "' . $summe . '"
                                     }
                                 }]
                             });
                         },
                         onApprove: function(data, actions) {
                             // This function captures the funds from the transaction.
                             return actions.order.capture().then(function(details) {
                                 // This function shows a transaction success message to your buyer.
                                             
                                 var data = "' . $data . '";
                                     
                                 var saveusrdataurl = "' . plugins_url() . '/kvoucherPro/saveUsrData.php";
                                     
                                 saveUserData(data,saveusrdataurl);
                                     
                                 openThxMsg();
                                     
                             });
                         }
                     }).render("#paypal-button-container");
                     //This function displays Smart Payment Buttons on your web page.
                     </script>';
            
            return $output;
        }

        // end paypalButtons()
        public static function kvoucherBillingAdress()

        {
            $output = '<div id="kvoucherBillingAdress">';
            
            self::kvoucherPostToSession();
            
            $check_required_data = self::kvoucherCheckAllCompanyData();// check all required company data

            if ($check_required_data == true) {

                if ($_POST['action'] == 'save1' || $_POST['action'] == 'back1' || empty($_POST['action']) || ! isset($_POST['action'])) {

                    $output .= '<form action="" method="post">';

                    $output .= self::kvoucherBillingAdressPrice();

                    $output .= self::kvoucherkindOfShipping();
                    
                    $output .= self::kvoucherCouponFor();

                    $output .= '<fieldset>' . self::kvoucherButton('save2', __('Next','kvoucherpro')) . '</fieldset>';

                    $output .= '</form>';
                }

                if ($_POST['action'] == 'save2' || $_POST['action'] == 'back2') {

                    if ($_POST['action'] == 'save2') {

                        if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'post' || $_SESSION['shipping'] == 'Post') {

                            $shipping_costs = get_option('kvoucher_plugin_company_textfiels')['shipping_costs'];

                            ($shipping_costs == null || empty($shipping_costs) ? $_SESSION['shipping_costs'] = '0.00' : $_SESSION['shipping_costs'] = $shipping_costs);
                        }

                        if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'email') {

                            $_SESSION['shipping_costs'] = '0.00';
                        }
                    }

                    $output .= '<form action="" method="post">';

                    $output .= self::kvoucherBillingAdressAdress();

                    $output .= self::kvoucherCheckBillingAdressDeliveryShipping();

                    $output .= self::kvoucherBillingAdressDeliveryShipping();

                    $output .= self::kvoucherTermsOfService();

                    $output .= '<fieldset><button onclick="submitForms(1)">'.__('Back','kvoucherpro').'</button> ' . self::kvoucherButton('save3', __('Next','kvoucherpro')) . '</fieldset>';

                    $output .= '</form>';

                    $output .= '<form action="" method="post" id="1">';

                    $output .= '<input type="hidden" name="action" value="back1">';

                    $output .= '</form>';
                }

                if ($_POST['action'] == 'save3' || $_POST['action'] == 'back3') {

                    if ($_POST['action'] == 'save3') {

                        
                    }

                    $output .= self::kvoucherPayCoupon(); // show all date

                    $output .= '<form action="" method="post">';

                    $output .= '<fieldset>' . self::kvoucherButton('back2', __('Back','kvoucherpro')) . '</fieldset>'; // show button back

                    $output .= '</form>';
                    
                    $output .= self::kvoucherThxForBuy();

                    $output .= '<fieldset>' . self::paypalButtons() . '</fieldset>'; // show paypal buttons
                }
            }else {
                
                $output = '<p>'.__('Unfortunately, our online voucher service is currently not available. Please accept our apologies','kvoucherpro').'.</p>';
                
            }
            
            $output .= '</div>';

            return $output;
        } // end
    } // end class KoboldcouponForm
} // end if class_exists('KoboldcouponForm')

