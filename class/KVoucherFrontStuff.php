<?php
namespace KVoucherFrontendStuff;

if (! class_exists('KVoucherForm')) {

    class KVoucherForm

    {

        private static function kvoucherPostToHiddenField()
        
        {
            foreach ($_POST as $content => $value) {

                if ( $content != 'action'){

                    $output .= ' <input type="hidden" name="' . sanitize_text_field( $content ) . '" value="' . sanitize_text_field ($value ) . '">';
                    
                }
                
            }

            return $output;
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
            $output = '<label for="check">' . __('Specify a different delivery address', 'kvoucherpro') . '</label>

                       <input type="hidden" name="checkbox_del_shipping_adress" value="0" />

                       <input type="checkbox" onchange="toggleDisableDelShippingAdress(this)" name="checkbox_del_shipping_adress" value="1" ' . (! empty($_POST['checkbox_del_shipping_adress']) || $_POST['checkbox_del_shipping_adress'] == '1' ? 'checked' : '') . ' id="checkbox_del_shipping_adress">';

            return $output;
        }

        private static function kvoucherCheckTermsOfServiceData()
        {
            $output = true;

            $terms_of_service_data = get_option('kvoucher_plugin_terms_of_service_textfields');

            if (! is_array($terms_of_service_data) && ! isset($terms_of_service_data['terms_of_service'])) {

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

            if (! in_array('curl', get_loaded_extensions())) {

                $output = false;
            }

            return $output;
        }

        private static function kvoucherGetCurrencyPaypalHTMLoutput()
        {
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

            // This IF block will be auto removed from the Free Version and will only get executed if the user on a trial or have a valid license.
            if (kvo_fs()->can_use_premium_code__premium_only()) {

                $output .= '<fieldset class="radio-toolbar">

                       <legend>' . __('Value', 'kvoucherpro') . '</legend>

                       <input class="price" type="radio" id="twenty" name="price" value="20" ' . (empty($_POST['price']) || $_POST['price'] == '20' ? 'checked' : '') . '>
                
    	               <label for="twenty">20,00 ' . $currency . '</label>
                
    	               <input class="price" type="radio" id="thirty" name="price" value="30" ' . ($_POST['price'] == '30' ? 'checked' : '') . '>
                
    	               <label for="thirty">30,00 ' . $currency . '</label>
                
    	               <input class="price" type="radio" id="fifty" name="price" value="50" ' . ($_POST['price'] == '50' ? 'checked' : '') . '>
                
    	               <label for="fifty">50,00 ' . $currency . '</label>
                
    	               <input class="price" type="radio" id="seventyfive" name="price" value="75" ' . ($_POST['price'] == '75' ? 'checked' : '') . '>
                
    	               <label for="seventyfive">75,00 ' . $currency . '</label>
                
    	               <input class="price" type="radio" id="onehundred" name="price" value="100" ' . ($_POST['price'] == '100' ? 'checked' : '') . '>
                
    	               <label for="onehundred">100,00 ' . $currency . '</label>
                
    	               <input class="price" type="radio" id="onehundredfifty" name="price" value="150" ' . ($_POST['price'] == '150' ? 'checked' : '') . '>
                
    	               <label for="onehundredfifty">150,00 ' . $currency . '</label>
                
    	               <input class="price" type="radio" id="twohundred" name="price" value="200" ' . ($_POST['price'] == '200' ? 'checked' : '') . '>
                
    	               <label for="twohundred">200,00 ' . $currency . '</label>

                       <input type="hidden" id="post" name="shipping" value="E-mail">
                
	                   </fieldset>';
            } // end if ( kvo_fs()->can_use_premium_code__premium_only() )

            if (kvo_fs()->is_not_paying()) {

                $output .= '<fieldset class="radio-toolbar">
                    
                       <legend>' . __('Value', 'kvoucherpro') . '</legend>
                           
                       <input class="price" type="radio" id="twenty" name="price" value="20" ' . (empty($_POST['price']) || $_POST['price'] == '20' ? 'checked' : '') . '>
                           
    	               <label for="twenty">20,00 ' . $currency . '</label>
    	                   
    	               <input class="price" type="radio" id="fifty" name="price" value="50" ' . ($_POST['price'] == '50' ? 'checked' : '') . '>
    	                   
    	               <label for="fifty">50,00 ' . $currency . '</label>
    	                   
    	               <input class="price" type="radio" id="onehundred" name="price" value="100" ' . ($_POST['price'] == '100' ? 'checked' : '') . '>
    	                   
    	               <label for="onehundred">100,00 ' . $currency . '</label>
    	                   
    	               <input type="hidden" id="post" name="shipping" value="E-mail">
    	                   
	                   </fieldset>';
            }

            return $output;
        }

        // end kvoucherBillingAdressPrice()
        private static function kvoucherBillingAdressAdress()
        {
            $output = '<fieldset>
                
                        <legend>' . __('Billing address', 'kvoucherpro') . '</legend>
                
                        <label for="privat">' . __('Privat', 'kvoucherpro') . ':</label><input type="radio" onchange="toggleDisableDelCompany(this)" id="radiobox_company_dis" id="privat" name="kind_of_adress" value="Privat" ' . (empty($_POST['kind_of_adress']) || $_POST['kind_of_adress'] == 'Privat' ? 'checked' : '') . '>
                
                        <label for="firma">' . __('Company', 'kvoucherpro') . ':</label><input class="kind_of_adress" type="radio" onchange="toggleEnableDelCompany(this)" id="radiobox_company_en" name="kind_of_adress" value="Firma" ' . ($_POST['kind_of_adress'] == 'Firma' ? 'checked' : '') . '><br>
                
		                <label for="herr">' . __('Mr', 'kvoucherpro') . ':</label><input type="radio" id="herr" name="title" value="Herr" ' . (empty($_POST['title']) || $_POST['title'] == 'Herr' ? 'checked' : '') . '>
                
                        <label for="frau">' . __('Mrs', 'kvoucherpro') . ':</label><input type="radio" id="frau" name="title" value="Frau" ' . ($_POST['title'] == 'Frau' ? 'checked' : '') . '>

                        <p ' . ($_POST['kind_of_adress'] == 'Firma' ? 'style="display:block"' : 'disabled style="display:none"') . ' id="company_input_field"><label style="width: 140px;" for="company">' . __('Company', 'kvoucherpro') . ': </label><input type="text" maxlength="40" name="company" id="company" value="' . (! empty($_POST['company']) ? $_POST['company'] : '') . '"  placeholder="' . __('Company', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="fname">' . __('First Name', 'kvoucherpro') . '*: </label><input type="text" maxlength="30" name="fname" id="fname" required="required" value="' . (! empty($_POST['fname']) ? $_POST['fname'] : '') . '"  placeholder="' . __('First Name', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="nname">' . __('Last Name', 'kvoucherpro') . '*: </label><input type="text" maxlength="30" name="nname" id="nname" required="required" value="' . (! empty($_POST['nname']) ? $_POST['nname'] : '') . '" placeholder="' . __('Last Name', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="streetname">' . __('Street + No.', 'kvoucherpro') . '*:</label><input type="text" maxlength="50"  name="streetname" id="streetname" value="' . (! empty($_POST['streetname']) ? $_POST['streetname'] : '') . '" required="required" placeholder="' . __('Street + No.', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="plz">' . __('Postal-Code', 'kvoucherpro') . '*:</label><input type="text" name="plz" id="plz" value="' . (! empty($_POST['plz']) ? $_POST['plz'] : '') . '" required="required" maxlength="6" placeholder="' . __('Postal-Code', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="city">' . __('City', 'kvoucherpro') . '*:</label><input type="text" maxlength="50" name="city"  id="city" value="' . (! empty($_POST['city']) ? $_POST['city'] : '') . '" required="required" placeholder="' . __('City', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="country">' . __('Country', 'kvoucherpro') . '*:</label><input type="text" maxlength="50" name="country" id="country" value="' . (! empty($_POST['country']) ? $_POST['country'] : 'Deutschland') . '" required="required" value="Deutschland" placeholder="' . __('Country', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="phone">' . __('Phone', 'kvoucherpro') . ':</label><input type="tel" maxlength="20" name="phone" id="phone" value="' . (! empty($_POST['phone']) ? $_POST['phone'] : '') . '" placeholder="' . __('Phone', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="email">' . __('E-mail', 'kvoucherpro') . '*:</label><input type="email" maxlength="50" name="email" id="email" value="' . (! empty($_POST['email']) ? $_POST['email'] : '') . '" required="required" placeholder="' . __('E-mail', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                        </fieldset>';

            return $output;
        }

        // end kvoucherBillingAdressAdress()
        private static function kvoucherBillingAdressDeliveryShipping()

        {
            $output = '<fieldset id="fieldset_del_shipping_adress"' . (! empty($_POST['checkbox_del_shipping_adress']) || $_POST['checkbox_del_shipping_adress'] == '1' ? 'style="display:block' : 'disabled style="display:none') . '">

                      <legend>' . __('Differing Shipping Address', 'kvoucherpro') . '</legend>

                      <label for="dif_herr">' . __('Mr', 'kvoucherpro') . ':</label><input type="radio" id="dif_herr" name="dif_title" value="Herr" ' . (empty($_POST['dif_title']) || $_POST['dif_title'] == 'Herr' ? 'checked' : '') . '>
                
                      <label for="dif_frau">' . __('Mrs', 'kvoucherpro') . ':</label><input type="radio" id="dif_frau" name="dif_title" value="Frau" ' . ($_POST['dif_title'] == 'Frau' ? 'checked' : '') . '>

                      <p><label style="width: 140px;" for="dif_fname">' . __('First Name', 'kvoucherpro') . '*: </label><input type="text" name="dif_fname" id="dif_fname" value ="' . (! empty($_POST['dif_fname']) ? $_POST['dif_fname'] : '') . '" required="required"  placeholder="' . __('First Name', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_nname">' . __('Last Name', 'kvoucherpro') . '*: </label><input type="text" name="dif_nname" id="dif_nname" value ="' . (! empty($_POST['dif_nname']) ? $_POST['dif_nname'] : '') . '" required="required"  placeholder="' . __('Last Name', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_streetname">' . __('Street + No.', 'kvoucherpro') . '*:</label><input type="text"  name="dif_streetname" id="dif_streetname" value ="' . (! empty($_POST['dif_nname']) ? $_POST['dif_nname'] : '') . '" placeholder="' . __('Street + No.', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_plz">' . __('Postal-Code', 'kvoucherpro') . '*:</label><input type="text" name="dif_plz" id="dif_plz" value ="' . (! empty($_POST['dif_plz']) ? $_POST['dif_plz'] : '') . '" required="required" maxlength="6" placeholder="' . __('Postal-Code', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_city">' . __('City', 'kvoucherpro') . '*:</label><input type="text" name="dif_city"  id="dif_city" value ="' . (! empty($_POST['dif_city']) ? $_POST['dif_city'] : '') . '" required="required" placeholder="' . __('City', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_country">' . __('Country', 'kvoucherpro') . '*:</label><input type="text" name="dif_country" id="dif_country" value ="' . (! empty($_POST['dif_country']) ? $_POST['dif_country'] : 'Deutschland') . '" required="required" value="Deutschland" placeholder="' . __('Country', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                      <p><label style="width: 140px;" for="dif_email">' . __('E-mail', 'kvoucherpro') . '*:</label><input type="email" name="dif_email" id="dif_email" value ="' . (! empty($_POST['dif_email']) ? $_POST['dif_email'] : '') . '" required="required" placeholder="' . __('E-mail', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                      </fieldset>';

            return $output;
        }

        // end kvoucherBillingAdressDeliveryShipping()
        private static function kvoucherPayCoupon()
        {
            $currency = self::kvoucherGetCurrencyPaypalHTMLoutput();

            $wert = floatval($_POST['price']);

            if ($_POST['shipping'] == 'Post') {
                $shipping_costs = floatval(str_replace(',', '.', $_POST['shipping_costs']));
            }
            ;

            if ($_POST['shipping'] == 'E-mail') {
                $_POST['shipping_costs'] = 0.00;
            }
            ;

            if ($_POST['kind_of_adress'] == 'Privat') {
                unset($_POST['company']);
            }
            ;

            $summe = $wert + $shipping_costs;

            $output = '<fieldset>

                        <legend>' . __('Voucher for', 'kvoucherpro') . '</legend>

                       <p><i>' . __('Dates appear on the voucher', 'kvoucherpro') . '</i></p>

                        ' . __('First Name', 'kvoucherpro') . ': ' . $_POST['for_fname'] . '<br>

                        ' . __('Last Name', 'kvoucherpro') . ': ' . $_POST['for_nname'] . '<br>
            
                        ' . __('Occasion', 'kvoucherpro') . ': ' . $_POST['occasion'] . '<br>
            
                        ' . __('Value', 'kvoucherpro') . ': ' . number_format($_POST['price'], 2, ',', '.') . ' ' . $currency . '</fieldset>';

            $output .= '<fieldset>

                     <legend>' . __('Billing Adress', 'kvoucherpro') . '</legend>

                        ' . (! empty($_POST['company']) ? __('Company', 'kvoucherpro') . ': ' . $_POST['company'] . '<br>' : '') . '

                        ' . __('First Name', 'kvoucherpro') . ': ' . $_POST['fname'] . '<br>

                        ' . __('Last Name', 'kvoucherpro') . ': ' . $_POST['nname'] . '<br>

                        ' . __('Street', 'kvoucherpro') . ': ' . $_POST['streetname'] . '<br>

                        ' . __('City', 'kvoucherpro') . ': ' . $_POST['plz'] . ' ' . $_POST['city'] . '<br>

                        ' . __('Country', 'kvoucherpro') . ': ' . $_POST['country'] . '<br>' . (! empty($_POST['phone']) ? __('Phone', 'kvoucherpro') . ': ' . $_POST['phone'] . '<br>' : '') . '

                        ' . __('E-mail', 'kvoucherpro') . ': ' . $_POST['email'] . '<br>
            
                        ' . __('Shipping costs', 'kvoucherpro') . ': ' . number_format($_POST['shipping_costs'], 2, ',', '.') . ' ' . $currency . '<br>
            
                        ' . __('Total', 'kvoucherpro') . ': ' . number_format($summe, 2, ',', '.') . ' ' . $currency . '<br>
            
                        ' . __('Shipping', 'kvoucherpro') . ': ' . $_POST['shipping'] . '<br></fieldset>';

            $output .= '<fieldset>
                        
                        <legend>' . __('Shipping Adress', 'kvoucherpro') . '</legend>' . 
            ($_POST['checkbox_del_shipping_adress'] == '1' ? __('First name', 'kvoucherpro') . ': ' . $_POST['dif_fname'] . '<br>' : __('First name', 'kvoucherpro') . ': ' . $_POST['fname'] . '<br>') . 
            ($_POST['checkbox_del_shipping_adress'] == '1' ? __('Last name', 'kvoucherpro') . ': ' . $_POST['dif_nname'] . '<br>' : __('Last name', 'kvoucherpro') . ': ' . $_POST['nname'] . '<br>') . 
            ($_POST['checkbox_del_shipping_adress'] == '1' ? __('Street', 'kvoucherpro') . ': ' . $$_POST['dif_streetname'] . '<br>' : __('Street', 'kvoucherpro') . ': ' . $_POST['streetname'] . '<br>') . 
            ($_POST['checkbox_del_shipping_adress'] == '1' ? __('City', 'kvoucherpro') . ': ' . $_POST['dif_plz'] . ' ' . $_POST['dif_city'] . '<br>' : __('City', 'kvoucherpro') . ': ' . $_POST['plz'] . ' ' . $_POST['city'] . '<br>') . 
            ($_POST['checkbox_del_shipping_adress'] == '1' ? __('Country', 'kvoucherpro') . ': ' . $_POST['dif_country'] . '<br>' : __('Country', 'kvoucherpro') . ': ' . $_POST['country'] . '<br>') . 
            ($_POST['checkbox_del_shipping_adress'] == '1' ? __('E-mail', 'kvoucherpro') . ': ' . $_POST['dif_email'] . '<br>' : __('E-mail', 'kvoucherpro') . ': ' . $_POST['email'] . '<br>') . __('Shipping', 'kvoucherpro') . ': ' . $_POST['shipping'] . 
            '</fieldset>';

            return $output;
        }

        // end kvoucherPayCoupon()
        private static function kvoucherTermsOfService()
        {
            $terms_of_service_data = get_option('kvoucher_plugin_terms_of_service_textfields');

            if (is_array($terms_of_service_data) && isset($terms_of_service_data['terms_of_service'])) {

                $terms = html_entity_decode(nl2br(get_option('kvoucher_plugin_terms_of_service_textfields')['terms_of_service']));
            } else {

                $terms = '';
            }

            $output .= '<p><label for="checkbox_terms_of_sevice">' . __('Please confirm our', 'kvoucherpro') . ' ' . (self::kvoucherCheckTermsOfServiceData() == true ? '<a onclick="openToc();">' . __('terms and conditions', 'kvoucherpro') . '</a>' : 'AGB´s') . '</label>
                       <input type="checkbox" required="required" name="checkbox_terms_of_sevice" value="1" ' . (! empty($_POST['checkbox_terms_of_sevice']) || $_POST['checkbox_terms_of_sevice'] == '1' ? 'checked' : '') . ' id="checkbox_terms_of_sevice"></p>';

            $output .= '<div id="toc">

                            <div style="background-color: #ffffff; color: #000000;" id="toc_content">' . $terms . '</div>

                            <div style="background-color: #ffffff" id="toc_close"><a style="color: DodgerBlue;" onclick="closeToc();">' . __('close', 'kvoucherpro') . '</a></div>
            
                        </div>';

            return $output;
        }

        private static function kvoucherThxForBuy()
        {
            $output = '<div id="thanks_message">

                               <p style="text-align: center; background-color: #ffffff; color: #000000;">' . __('Thank you for shopping at', 'kvoucherpro') . ' ' . get_bloginfo('name') . '</p>

	                           <p style="text-align: center">

		                      <a style="color: DodgerBlue;" href="' . ($_SERVER['REQUEST_URI']) . '">' . __('Back to selection', 'kvoucherpro') . '</a>

                      </div>';

            return $output;
        }

        // end kvoucherTermsOfService()
        private function kvoucherCouponFor()
        {
            $output = '<fieldset>
                        
                       <legend>' . __('Voucher for', 'kvoucherpro') . '</legend>

                       <p><i>' . __('Dates appear on the voucher', 'kvoucherpro') . '</i></p>

                       <label for="for_herr">' . __('Mr', 'kvoucherpro') . ':</label><input type="radio" id="for_herr" name="for_title" value="Herr" ' . (empty($_POST['for_title']) || $_POST['for_title'] == 'Herr' ? 'checked' : '') . '>
                
                        <label for="for_frau">' . __('Mrs', 'kvoucherpro') . ':</label><input type="radio" id="for_frau" name="for_title" value="Frau" ' . ($_POST['for_title'] == 'Frau' ? 'checked' : '') . '>
                
                        <p><label style="width: 140px;" for="for_fname">' . __('First Name', 'kvoucherpro') . '*: </label><input type="text" maxlength="30" name="for_fname" id="for_fname" required="required" value="' . (! empty($_POST['for_fname']) ? $_POST['for_fname'] : '') . '"  placeholder="' . __('First Name', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        <p><label style="width: 140px;" for="for_nname">' . __('Last Name', 'kvoucherpro') . '*: </label><input type="text" maxlength="30" name="for_nname" id="for_nname" required="required" value="' . (! empty($_POST['for_nname']) ? $_POST['for_nname'] : '') . '" placeholder="' . __('Last Name', 'kvoucherpro') . '" style="max-width: 300px;"></p>

                        <p><label style="width: 140px;" for="occasion">' . __('Occasion', 'kvoucherpro') . ': </label><input type="text" maxlength="30" name="occasion" id="occasion" value="' . (! empty($_POST['occasion']) ? $_POST['occasion'] : '') . '" placeholder="' . __('Occasion', 'kvoucherpro') . '" style="max-width: 300px;"></p>
                
                        </fieldset>';

            return $output;
        }

        private static function kvoucherkindOfShipping()
        {
            $currency = self::kvoucherGetCurrencyPaypalHTMLoutput(); // load currency

            $show_shipping_costs = get_option('kvoucher_plugin_company_textfiels')['shipping_costs'];

            ($show_shipping_costs == null || empty($show_shipping_costs) ? $show_shipping_costs = '0,00' : $show_shipping_costs = $show_shipping_costs);

            // if shipping selectected in the admin area post+email
            if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'post+email') {

                $output = '<fieldset>
                
                       <legend>' . __('Shipping', 'kvoucherpro') . '</legend>
                
                            <input type="radio" id="post" name="shipping" value="Post" ' . (empty($_POST['shipping']) || $_POST['shipping'] == 'Post' ? 'checked' : '') . '>
                           
    	                    <label for="post">' . __('via Post', 'kvoucherpro') . ' <i>( + ' . $show_shipping_costs . ' ' . $currency . ' ' . __('Shipping', 'kvoucherpro') . ')</i></label>
                           
    	                    <input type="radio" id="email" name="shipping" value="E-mail" ' . ($_POST['shipping'] == 'E-mail' ? 'checked' : '') . '>
    	                   
    	                    <label for="email">' . __('via E-mail (PDF)', 'kvoucherpro') . '</label>
            
                       </fieldset>';
            }

            // if shipping selectected in the admin area email
            if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'email') {

                $output = '<fieldset>
                    
                       <legend>' . __('Shipping', 'kvoucherpro') . '</legend>
                           
                           <p>' . __('The dispatch takes place via E-mail (PDF)', 'kvoucherpro') . '</p>
                               
                       </fieldset>';

                $output .= '<input type="hidden" id="email" name ="shipping" value="E-mail">';

                $output .= '<input type="hidden" id="shipping_costs" name ="shipping_costs" value="0.00">';
            }

            // if shipping selectected in the admin area post
            if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'post') {

                $output = '<fieldset>
                    
                       <legend>' . __('Shipping', 'kvoucherpro') . '</legend>
                           
                           <p>' . __('The dispatch takes place by Post', 'kvoucherpro') . '</p>
                               
                       </fieldset>';

                $output .= '<input type="hidden" id="post" name ="shipping" value="Post">';

                $output .= '<input type="hidden" id="shipping_costs" name ="shipping_costs" value="' . get_option('kvoucher_plugin_company_textfiels')['shipping_costs'] . '">';
            }

            return $output;
        }

        private static function kvoucherGetCurrencyforPaypalApi()
        {
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

        private static function getPostData()
        {
            $data = array(

                'price' => $_POST['price'],

                'shipping' => $_POST['shipping'],

                'shipping_costs' => str_replace(',', '.', $_POST['shipping_costs']),

                'kind_of_adress' => $_POST['kind_of_adress'],

                'for_title' => $_POST['for_title'],

                'for_fname' => self::kvoucherSubStr($_POST['for_fname'], 30),

                'for_nname' => self::kvoucherSubStr($_POST['for_nname'], 30),

                'occasion' => self::kvoucherSubStr($_POST['occasion'], 30),

                'title' => $_POST['title'],

                'fname' => self::kvoucherSubStr($_POST['fname'], 30),

                'nname' => self::kvoucherSubStr($_POST['nname'], 30),

                'company' => self::kvoucherSubStr($_POST['company'], 40),

                'streetname' => self::kvoucherSubStr($_POST['streetname'], 50),

                'plz' => self::kvoucherSubStr($_POST['plz'], 8),

                'city' => self::kvoucherSubStr($_POST['city'], 50),

                'country' => self::kvoucherSubStr($_POST['country'], 50),

                'phone' => self::kvoucherSubStr($_POST['phone'], 20),

                'email' => self::kvoucherSubStr($_POST['email'], 50),

                'dif_title' => $_POST['dif_title'],

                'dif_fname' => self::kvoucherSubStr($_POST['dif_fname'], 30),

                'dif_nname' => self::kvoucherSubStr($_POST['dif_nname'], 30),

                'dif_streetname' => self::kvoucherSubStr($_POST['dif_streetname'], 50),

                'dif_plz' => self::kvoucherSubStr($_POST['dif_plz'], 8),

                'dif_city' => self::kvoucherSubStr($_POST['dif_city'], 50),

                'dif_country' => self::kvoucherSubStr($_POST['dif_country'], 50),

                'dif_email' => self::kvoucherSubStr($_POST['dif_email'], 50),

                'checkbox_del_shipping_adress' => $_POST['checkbox_del_shipping_adress'],

                'action' => $_POST['action']
            );

            return base64_encode(http_build_query($data));
        }

        // end kvoucherkindOfShipping()
        private static function paypalButtons()
        {
            $data = self::getPostData();

            $currency = self::kvoucherGetCurrencyforPaypalApi();

            $shipping_costs = floatval(str_replace(',', '.', $_POST['shipping_costs']));

            $value = floatval($_POST['price']);

            $summe = $shipping_costs + $value;

            $output .= sprintf('<script src="https://www.paypal.com/sdk/js?client-id=%s&currency=' . $currency . '"></script>', get_option('kvoucher_plugin_paypal_textfiels')['paypal_client_id']);

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
                                     
                                 saveUserData(data);

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

            $output .= '<noscript style="color:red;">' . __('Please enable javascript in your browser. Otherwise it is not possible to buy a voucher.', 'kvoucherpro') . '</noscript>';

            $check_required_data = self::kvoucherCheckAllCompanyData(); // check all required company data

            if ($check_required_data == true) {

                if ($_POST['action'] == 'save1' || $_POST['action'] == 'back1' || empty($_POST['action']) || ! isset($_POST['action'])) {

                    $output .= '<form action="" method="post">';

                    $output .= self::kvoucherPostToHiddenField();

                    $output .= self::kvoucherBillingAdressPrice();

                    $output .= self::kvoucherkindOfShipping();

                    $output .= self::kvoucherCouponFor();

                    $output .= '<fieldset>' . self::kvoucherButton('save2', __('Next', 'kvoucherpro')) . '</fieldset>';

                    $output .= '</form>';
                }

                if ($_POST['action'] == 'save2' || $_POST['action'] == 'back2') {

                    if ($_POST['action'] == 'save2') {

                        if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'post' || $_POST['shipping'] == 'Post') {

                            $shipping_costs = get_option('kvoucher_plugin_company_textfiels')['shipping_costs'];

                            ($shipping_costs == null || empty($shipping_costs) ? $_POST['shipping_costs'] = '0.00' : $_POST['shipping_costs'] = $shipping_costs);
                        }

                        if (get_option('kvoucher_plugin_company_textfiels')['shipping'] == 'email') {

                            $_POST['shipping_costs'] = '0.00';
                        }
                    }

                    $output .= '<form action="" method="post">';

                    $output .= self::kvoucherPostToHiddenField();

                    $output .= self::kvoucherBillingAdressAdress();

                    $output .= self::kvoucherCheckBillingAdressDeliveryShipping();

                    $output .= self::kvoucherBillingAdressDeliveryShipping();

                    $output .= self::kvoucherTermsOfService();

                    $output .= '<fieldset><button onclick="submitForms(1)">' . __('Back', 'kvoucherpro') . '</button> ' . self::kvoucherButton('save3', __('Next', 'kvoucherpro')) . '</fieldset>';

                    $output .= '</form>';

                    $output .= '<form action="" method="post" id="1">';

                    $output .= self::kvoucherPostToHiddenField();

                    $output .= '<input type="hidden" name="action" value="back1">';

                    $output .= '</form>';
                }

                if ($_POST['action'] == 'save3' || $_POST['action'] == 'back3') {

                    if ($_POST['action'] == 'save3') {

                        $output .= self::kvoucherPayCoupon(); // show all date

                        $output .= '<form action="" method="post">';

                        $output .= self::kvoucherPostToHiddenField();

                        $output .= '<fieldset>' . self::kvoucherButton('back2', __('Back', 'kvoucherpro')) . '</fieldset>'; // show button back

                        $output .= '</form>';

                        $output .= self::kvoucherThxForBuy();

                        $output .= '<fieldset>' . self::paypalButtons() . '</fieldset>'; // show paypal buttons
                    }
                }
            } else {

                $output = '<p>' . __('Unfortunately, our online voucher service is currently not available. Please accept our apologies', 'kvoucherpro') . '.</p>';
            }

            $output .= '</div>';

            return $output;
        } // end
    } // end class KVoucherForm
} // end if class_exists('KVoucherForm')

