function kvsubmitForms(id) {

	document.getElementById(id).submit();

}

// open terms of conditions (toc)
function kvopenToc() {

	document.getElementById("toc").style.display = "block";

	var browser_height = window.innerHeight * 0.90;

	var width = document.getElementById("kvoucherBillingAdress").offsetWidth;

	document.getElementById("toc").style.height = browser_height + "px";

	document.getElementById("toc").style.width = width + "px";

	document.getElementById("toc_content").style.height = browser_height - 40
			+ "px";

}

function kvopenThxMsg() {

	var browser_height = window.innerHeight * 0.90;

	var width = document.getElementById("kvoucherBillingAdress").offsetWidth;

	document.getElementById("thanks_message").style.height = browser_height
			+ "px";

	document.getElementById("thanks_message").style.width = width + "px";

	document.getElementById("thanks_message").style.display = "block";

}

function kvcloseToc() {
	document.getElementById("toc").style.display = "none";
}

function kvtoggleDisableDelShippingAdress(checkbox) {

	var fieldset = document.getElementById("fieldset_del_shipping_adress");

	var checkbox = document.getElementById("checkbox_del_shipping_adress");

	if (fieldset.style.display === "none" && checkbox.checked === true) {
		fieldset.style.display = "block";
		fieldset.disabled = false;
	} else {
		fieldset.style.display = "none";
		fieldset.disabled = true;
	}
}

function kvtoggleEnableDelCompany(radiobox) {

	var fieldset = document.getElementById("company_input_field");

	var radiobox = document.getElementById("radiobox_company_en");

	fieldset.style.display = "block";

	fieldset.disabled = false;

}

function kvtoggleDisableDelCompany(radiobox) {

	var fieldset = document.getElementById("company_input_field");

	var radiobox = document.getElementById("radiobox_company_dis");

	fieldset.style.display = "none";

	fieldset.disabled = true;

}

function kvdif_del_adress() {
	var checkbox = document
			.getElementById('checkbox_different_delivery_shipping_adress');
	console.log(checkbox);
	// if ckeckbox not true
	if (checkbox.checked != true) {
		document.getElementById('delivery_shipping_adress').style.display = "none";// close
		// div
		document.getElementById("dif_fname").required = false;
		document.getElementById("dif_nname").required = false;
		document.getElementById("dif_email").required = false;
		document.getElementById("dif_plz").required = false;
		document.getElementById("dif_city").required = false;
		document.getElementById("dif_country").required = false;
	} else {
		document.getElementById('delivery_shipping_adress').style.display = "block";// open
		// diffrent
		// shipping
		// adress
		// div
		document.getElementById("dif_email").required = true;
		document.getElementById("dif_fname").required = true;
		document.getElementById("dif_nname").required = true;
	}
}

var kvsaveUserData;

//this is jQuery function
jQuery(function(){
	kvsaveUserData = function( data )
  {
  	 // We'll pass this variable to the PHP function kvusr_data_request
      
       
      // This does the ajax request
      jQuery.ajax({
      	type: 'POST',
          url: usr_data_obj.ajaxurl,
          data: {
              'action': 'kvusr_data_request',
              'data' : data,
              'nonce' : usr_data_obj.nonce
          },
          success:function(data) {
              // This outputs the result of the ajax request
              console.log(data);
          },
          error: function(errorThrown){
              console.log(errorThrown);
          }
      });  
      
  }
})

