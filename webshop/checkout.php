<?php
/*  checkout.php
    Copyright 2006 Elmar Wenners
    Support site: http://www.chaozz.nl

    This file is part of FreeWebshop.org.

    FreeWebshop.org is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    FreeWebshop.org is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with FreeWebshop.org; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
?>
<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php include ("checklogin.php"); ?>
<?php
      if (!empty($_POST['shipping'])) { $shipping=intval($_POST['shipping']); }
      if (!empty($_POST['payment'])) { $payment=intval($_POST['payment']); }
      if (!empty($_POST['notes']))    { $notes=$_POST['notes']; } else { $notes = ""; }
?>
<?php
    // if the cart is empty, then you shouldn't be here
   if (CountCart() == 0) {
       PutWindow ($txt['general12'], $txt['checkout2'], "warning.gif", "50");
	   exit(include("includes/exit.inc.php"));
   }

     // lets find out some customer details
     $query = sprintf("SELECT * FROM customer WHERE ID = %s", quote_smart($customerid));
     $sql = mysql_query($query) or die(mysql_error());
     
     // we can not find you, so please leave
     if (mysql_num_rows($sql) == 0) {
        PutWindow ($txt['general12'], $txt['checkout2'], "warning.gif", "50");
	    exit(include("includes/exit.inc.php"));
    }
     // read the details
     while ($row = mysql_fetch_row($sql)) {
            $lastname = $row[3];
	        $surname = $row[4];
	        $to = $row[11];
            $address = $row[7];
            $zipcode = $row[8];
            $city = $row[9];
            $country = $row[13];
     }

     // process the order. NOTE: the price is calculated and added later on in this process!!! so $total is still empty at this point
     $query = sprintf("INSERT INTO `order` (`DATE`,`STATUS`,`SHIPPING`,`PAYMENT`,`CUSTOMERID`,`TOPAY`,`WEBID`,`NOTES`) VALUES ('".Date($date_format)."','1',%s,%s,%s,'1','n/a',%s)", quote_smart($shipping), quote_smart($payment), quote_smart($customerid), quote_smart($notes));
     $sql = mysql_query($query) or die(mysql_error());

     // get the last id
     $lastid = mysql_insert_id();

     // make webID
     $date_array = GetDate();
     $this_year = $date_array['year'];
     $webid = $order_prefix . $this_year. $lastid . $order_suffix;
     $query = "UPDATE `order` SET `WEBID` = '" .  $webid . "' WHERE `ID` = " . $lastid;
     $sql = mysql_query($query) or die(mysql_error());

     include ($lang_file);
     $message = $txt['checkout3'];
      // now go through all all products from basket with status 'basket'

     $query = "SELECT * FROM basket WHERE ( CUSTOMERID = " . $customerid . " AND STATUS = 'BASKET' )";
     $sql = mysql_query($query) or die(mysql_error());
     $total = 0;

     while ($row = mysql_fetch_row($sql)) {
	       $query_details = "SELECT * FROM product WHERE ID = '" . $row[2] . "'";
	       $sql_details = mysql_query($query_details) or die(mysql_error());

	       while ($row_details = mysql_fetch_row($sql_details)) {
		         $product_price = $row_details[4];
		         if ($no_vat == 0 && $db_prices_including_vat == 0) { $product_price = $product_price * $vat; }
		         $subtotal = $product_price * $row[6];
		         
		         // make up the description to print according to the pricelist_format and max_description
         		 if ($pricelist_format == 0) { $print_description = $row_details[1]; }
         		 if ($pricelist_format == 1) { $print_description = $row_details[3]; }
         		 if ($pricelist_format == 2) { $print_description = $row_details[1]." - ".$row_details[3]; }
         		 if ($max_description != 0) {
		             $description = stringsplit($print_description, $max_description); // so lets only show the first xx characters
		             if (strlen($print_description) != strlen($description[0])) { $description[0] = $description[0] . ".."; }
		             $print_description = $description[0];
		             $print_description = strip_tags($print_description); // strip html because half html can mess up the layout
		         }
		         $print_description = strip_tags($print_description); //remove html because of danger of broken tags
		         
	             $message = $message.$row[6].$txt['checkout4'].$print_description." (".$currency_symbol."".myNumberFormat($row_details[4],$number_format).$txt['checkout5'].") <br />";
	             $total = $total + $subtotal;

	             // update stock amount if needed
	             if ($stock_enabled == 1) {
		             if ($row[6] > $row_details[5] || $row_details[5] == 0) {
                         include ($lang_file);
			             PutWindow ($txt['general12'], $txt['checkout15']." ".$print_description."<br />".$txt['checkout7']." ".$row[6]."<br />".$txt['checkout8']." ".$row_details[5], "warning.gif", "50");
			             exit(include("includes/exit.inc.php"));
		             }
		             $new_stock = $row_details[5] - $row[6];
                     $update_query = "UPDATE `product` SET `STOCK` = ".$new_stock." WHERE `ID` = '".$row_details[0]."'";
                     $update_sql = mysql_query($update_query) or die(mysql_error());
	            }
           }
     }

     // first the shipping
     $query = sprintf("SELECT * FROM `shipping` WHERE `id` = %s", quote_smart($shipping));
     $sql = mysql_query($query) or die(mysql_error());
     
     // read the shipping method
     while ($row = mysql_fetch_row($sql)) {
            $shipping_descr = $row[1];
	        $sendcosts = $row[2];
     }
     include ($lang_file); // update sendcost in language file
     $message = $message . $txt['checkout6'].$txt['checkout6']; // white line
     $message = $message . $txt['checkout16'].$shipping_descr; // shipping method
     $message = $message . $txt['checkout6'].$txt['checkout6']; // white line
     
     // shippingmethod 2 is pick up at store. if you don't support this option, there is no need to remove this
     if ($shipping == "2") {
	     $message = $message . $txt['checkout18']; // appointment line
     }
     else {
	     $message = $message . $txt['checkout17']; // shipping address
     }
     $message = $message . $txt['checkout6'].$txt['checkout6']; // white line
     
     // now lets calculate the invoice total now we know the final addition, the shipping costs     
     $total = $total + $sendcosts;
     $totalprint = myNumberFormat($total,$number_format);
     $print_sendcosts = myNumberFormat($sendcosts,$number_format);
     include ($lang_file);
         
     // now the payment
     $query = sprintf("SELECT * FROM `payment` WHERE `id` = %s", quote_smart($payment));
     $sql = mysql_query($query) or die(mysql_error());
     
     // read the payment method
     while ($row = mysql_fetch_row($sql)) {
            $payment_descr = $row[1];
	        $payment_code = $row[2];
	        // there could be some variables in the code, like %total%, %webid% and %shopurl% so lets update them with the correct values
	        $payment_code = str_replace("%total%", $total, $payment_code);
	        $payment_code = str_replace("%webid%", $webid, $payment_code);
	        $payment_code = str_replace("%shopurl%", $shopurl, $payment_code);
     }
     $message = $message . $txt['checkout19'].$payment_descr; // Payment method:
     $message = $message . $txt['checkout6']; // line break
     
     // paypal and ideal use extra code to checkout. if there is extra code, then we paste it here
     if ($payment_code <> "") {
	     $message = $message . $payment_code;
         $message = $message . $txt['checkout6']; // line break
     }
     
     // the two standard build in payment methods
     if ($payment == "1") {
	     $message = $message . $txt['checkout20']; // bank info
     }
     if ($payment == "2") {
	     $message = $message . $txt['checkout21']; // pick up at store info
     }
     
     $message = $message . $txt['checkout6'].$txt['checkout6']; // white line
     $message = $message . $txt['checkout24']; // total invoice amount
     if ($no_vat == 0) { $message = $message ." ". $txt['checkout25']; } // including VAT line if needed
     
     // if pay at the store, you don't need to pay within 14 days
     if ($payment <> "2") { 
	     $message = $message . $txt['checkout6'].$txt['checkout6']; // new line
	     $message = $message . $txt['checkout26'];  // pay within xx days 
     }
     
     $message = $message . $txt['checkout6'].$txt['checkout6']; // white line
     $message = $message . $txt['checkout9']; // direct link to customer order for online status checking

     // order update
     $query = "UPDATE `order` SET `TOPAY` = '" .  $total . "' WHERE `ID` = " . $lastid;
     $sql = mysql_query($query) or die(mysql_error());

     //basket update
     $query = sprintf("DELETE FROM `basket` WHERE (`CUSTOMERID` = %s)", quote_smart($customerid));
     $sql = mysql_query($query) or die(mysql_error());
     
     
     // now lets show the customer some details
	 echo "<h4><img src=\"".$gfx_dir."/1_.gif\" alt=\"step 1\">&nbsp;<img src=\"".$gfx_dir."/2_.gif\" alt=\"step 2\">&nbsp;<img src=\"".$gfx_dir."/3.gif\" alt=\"step 3\"></h4><br /><br />"; 
     
     // email and save the order in HTML
     $html_header = "<html><head><title>".$webid."</title>";
     $html_header = $html_header."<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=".$charset."\"></head><body>";
     $html_footer = "</body></html>";
     $full_message = $html_header.$message.$html_footer;
     $subject = $txt['checkout10'];

     // email header
     $email_header = EmailHeader($sales_mail, $charset);
	                 
     if (mymail($webmaster_mail, $to, $subject, $full_message, $email_header, $smtp_server, $smtp_port, $smtp_user, $smtp_pass, $charset)) {
        echo "<p>" . $txt['checkout11'] ."</p>";
     }
     else { echo "<p>" . $txt['checkout12'] . "</p>"; }
     
     mymail($webmaster_mail, $sales_mail, $subject, $full_message, $email_header, $smtp_server, $smtp_port, $smtp_user, $smtp_pass, $charset); // no error checking here, because there is no use to report this to the customer

     // save the order in order folder for administration
     $handle = fopen ($orders_dir."/".strval($webid), "w+");
     if (!fwrite($handle, $message))
        {
         $retVal = false;
     }
     else {
           fclose($handle);
     }
     // now print the confirmation on the screen
?>
     <table width="100%" class="datatable">
       <caption><?php echo $txt['checkout13']; ?></caption>
       <tr><td>
           <?php echo $message ?>
       </td></tr>
     </table>
     <h4><a href="printorder.php?orderid=<?php echo $lastid ?>"><?php echo $txt['readorder1'] ?></a></h4>