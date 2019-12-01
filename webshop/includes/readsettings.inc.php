<?php
    $version = "2.2.3"; // the version of this webshop
    // ------------------------------------------------------
    // read the settings from the database
    // ------------------------------------------------------
    $query = "SELECT * FROM `settings`";
    $sql = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_row($sql)) {

	    // what color theme to use for the entire site?
	    $theme = $row[0];
	    
	    // shipping & currency
	    $send_default_country = $row[1]; 
	    $sendcosts_default_country = $row[2];
	    $sendcosts_other_country = $row[3]; 
	    $rembours_costs = $row[4];
	    $currency = $row[5]; 
	    $currency_symbol = $row[6]; 
	    $paymentdays = $row[7]; 
	    $vat = $row[8]; 
	    $show_vat = $row[9]; 
	    $db_prices_including_vat = $row[10]; 
	    
	    // shop settings
	    $sales_mail = $row[11];     
	    $shopname = $row[12];
	    $shopurl = $row[13]; 
	    $default_lang = $row[14];   
	    $order_prefix = $row[15]; 
	    $order_suffix = $row[16]; 
	    $stock_enabled = $row[17];
	    $ordering_enabled = $row[18];
	    $shop_disabled = $row[19]; 
	    $shop_disabled_title = $row[20];
	    $shop_disabled_reason = $row[21];
	    
	    // contact info
	    $webmaster_mail = $row[22];
	    $shoptel = $row[23];
	    $shopfax = $row[24];
	
	    // shop details & bank data
	    $bankaccount = $row[25]; // your bankaccount number
	    $bankaccountowner = $row[26];
	    $bankcity = $row[27];
	    $bankcountry = $row[28];
	    $bankname = $row[29];
	    $bankiban = $row[30];
	    $bankbic = $row[31];
	    $start_year = $row[32];   // the year you started your webshop, used in the copyright line in the footer
	    
	    // some pictures we need to specify
	    $shop_logo = $row[33];
//	    $background = $row[34]; unused
	    
	    // some strings you might want to change
	    $slogan = $row[35];
	    $page_title = $row[36];
	    $page_footer = $row[37];
	    
	    // the shipping methods you support
	    $shipping_postal = $row[38];
	    $shipping_atstore = $row[39];
	    $shipping_unused = $row[40]; // for future use maybe
	    
	    $number_format = $row[41];
	    $max_description = $row[42];
	    $no_vat = $row[43];
	    $pricelist_format = $row[44];
	    $date_format = $row[45];
	    $search_prodgfx = $row[46];
	    $use_prodgfx = $row[47];
	    
	    // payment methods you support
	    $pay_bank = $row[48];
	    $pay_atstore = $row[49];
	    $pay_paypal = $row[50];
	    $pay_onreceive = $row[51];
	    $pay_unused = $row[52]; // for future use maybe
	    $paypal_email = $row[53]; // paypal email
	    $paypal_currency = $row[54]; // paypal currency
	    
	    // new in 2.1
	    $thumbs_in_pricelist = $row[55];
	    $keywords = $row[56];
	    $charset = $row[57];
	    $conditions_page = $row[58];
	    $guarantee_page = $row[59];
	    $shipping_page = $row[60];
	    
	    // new in 2.2
	    $pictureid = $row[61];
	    $aboutus_page = $row[62];
	    $live_news = $row[63];
	    $pricelist_thumb_width = $row[64];
	    $pricelist_thumb_height = $row[65];
	    $category_thumb_width = $row[66];
	    $category_thumb_height = $row[67];
	    $product_max_width = $row[68];
	    $product_max_height = $row[69];
   }
?>