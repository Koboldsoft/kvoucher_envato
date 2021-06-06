function submitForms(id){
	
	document.getElementById(id).submit();

}


// open terms of conditions (toc)
function openToc() {
	
    document.getElementById("toc").style.display = "block";
    
    var browser_height = window.innerHeight*0.90;
    
    var width = document.getElementById("kvoucherBillingAdress").offsetWidth;
    
    document.getElementById("toc").style.height=browser_height +"px";
    
    document.getElementById("toc").style.width = width+"px"; 
    
    document.getElementById("toc_content").style.height=browser_height - 40+"px"; 
    
}

function openThxMsg() {
	
    var browser_height = window.innerHeight*0.90;
    
    var width = document.getElementById("kvoucherBillingAdress").offsetWidth;
    
    document.getElementById("thanks_message").style.height = browser_height +"px";
    
    document.getElementById("thanks_message").style.width = width+"px"; 
    
    document.getElementById("thanks_message").style.display = "block";

    
}

function closeToc() {
    document.getElementById("toc").style.display = "none"; 
}

function toggleDisableDelShippingAdress(checkbox) {
    
	var fieldset = document.getElementById("fieldset_del_shipping_adress");
    
    var checkbox = document.getElementById("checkbox_del_shipping_adress");
    
    if (fieldset.style.display === "none" && checkbox.checked === true ) {
    	fieldset.style.display = "block";
    	fieldset.disabled = false;
    } else {
    	fieldset.style.display = "none";
    	fieldset.disabled = true;
    }
  } 

function toggleEnableDelCompany(radiobox) {
    
	var fieldset = document.getElementById("company_input_field");
    
    var radiobox = document.getElementById("radiobox_company_en");
    
    fieldset.style.display = "block";
    
    fieldset.disabled = false;
    
  } 

function toggleDisableDelCompany(radiobox) {
    
	var fieldset = document.getElementById("company_input_field");
    
    var radiobox = document.getElementById("radiobox_company_dis");
    
    fieldset.style.display = "none";
    
    fieldset.disabled = true;
    
  } 

function dif_del_adress() {
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


function saveUserData(data,sendurl) {
	
	console.log(data);

	var ajaxRequest; // The variable that makes Ajax possible!

	try {
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e) {
		// Internet Explorer Browsers
		try {
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}

	}
	// Wenn Request-Objekt vorhanden, dann Anfrage senden:
	if (ajaxRequest != null) {
		ajaxRequest.open('POST', sendurl, true);
		ajaxRequest.setRequestHeader('Content-Type',
				'application/x-www-form-urlencoded');
		ajaxRequest.send('data=' + data);
		}
	
}

