<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php include ("checklogin.php"); ?>

<?php
	  // the visitor is logged in, so proceed
	      
      if (!empty($_POST['numprod'])) {
	      $numprod=intval($_POST['numprod']);
      }
      if (!empty($_POST['prodid'])) {
	      $prodid=intval($_POST['prodid']);
      }
      if (!empty($_POST['basketid'])) {
	      $basketid=intval($_POST['basketid']);
      }

    // current date
    $today = getdate();

    if (IsAdmin() == true) {
	    if (!empty($_GET['id']))
	        { $customerid = intval($_GET['id']); }
       }

    if ($action=="add" && $numprod != 0) {
        // if we work with stock amounts, then lets check if there is enough in stock
        if ($stock_enabled == 1) {
            $query = sprintf("SELECT `STOCK` FROM `product` WHERE `ID` = %s", quote_smart($prodid));
            $sql = mysql_query($query) or die(mysql_error());
           
            while ($row = mysql_fetch_row($sql)) {
		            if ($numprod > $row[0] || $row[0] == 0) {
		                      include ($lang_file);
  		                      PutWindow ($txt['general12'], $txt['checkout15']."<br />".$txt['checkout7']." ".$numprod."<br />".$txt['checkout8']." ".$row[0], "warning.gif", "50");
				              exit(include("includes/exit.inc.php"));
			        }
		    }
        }
	    $query = "INSERT INTO `basket` ( `CUSTOMERID` , `PRODUCTID` , `STATUS` , `ORDERID` , `LINEADDDATE` , `QTY` ) VALUES ( '" . $customerid . "', '" . $prodid . "', 'BASKET', '0' , '" . Date("d-m-Y @ G:i") . "' , '" . $numprod . "')";
        $sql = mysql_query($query) or die(mysql_error());
    }
   if ($action=="update"){
	   if ($numprod==0){
		   $query = "DELETE FROM `basket` WHERE ID = " . $basketid;
           $sql = mysql_query($query) or die(mysql_error());
      }
      else {
	       // if we work with stock amounts, then lets check if there is enough in stock
           if ($stock_enabled == 1) {
               $query = "SELECT `STOCK` FROM `product` WHERE `ID` = '".$prodid."'";
               $sql = mysql_query($query) or die(mysql_error());
               
               while ($row = mysql_fetch_row($sql)) {
			           if ($numprod > $row[0] || $row[0] == 0) {
		                         include ($lang_file);
			                     PutWindow ($txt['general12'], $txt['checkout15']."<br />".$txt['checkout7']." ".$numprod."<br />".$txt['checkout8']." ".$row[0], "warning.gif", "50");
					             exit(include("includes/exit.inc.php"));
				       }
			   }
	       }
           $query = "UPDATE `basket` SET `CUSTOMERID` = " . $customerid . ", `PRODUCTID` = '" . $prodid . "', `STATUS` = 'BASKET', `ORDERID` = 0, `LINEADDDATE` = '" . Date("d-m-Y @ G:i") . "', `QTY` = " . $numprod . " WHERE `ID` = " . $basketid;
           $sql = mysql_query($query) or die(mysql_error());
      }
   }

   if ($action=="empty"){
		   $query = "DELETE FROM basket WHERE CUSTOMERID = " . $customerid;
           $sql = mysql_query($query) or die(mysql_error());
   }

   // read basket
   $query = "SELECT * FROM basket WHERE (CUSTOMERID = " . $customerid . " AND STATUS = 'BASKET') ORDER BY ID";
   $sql = mysql_query($query) or die(mysql_error());
   $count = mysql_num_rows($sql);

   if ($count == 0) {
	   PutWindow ($txt['cart1'], $txt['cart2'], "carticon.gif", "50");
   }
   else {
   ?>
   
   <table width="100%" class="datatable">
     <caption><?php echo $txt['cart11'] ?></caption>
     <tr>
       <th><?php echo $txt['cart3']; ?></th>
       <th><?php echo $txt['cart4']; ?></th>
       <th><?php echo $txt['cart5']; ?></th>
    </tr>

   <?php
   $optel = 0;

   while ($row = mysql_fetch_row($sql)) {
         $query = "SELECT * FROM `product` where `ID`='" . $row[2] . "'";
         $sql_details = mysql_query($query) or die(mysql_error());
         while ($row_details = mysql_fetch_row($sql_details)) {
   	     $optel = $optel +1;
	     if ($optel == 3) { $optel = 1; }
	     if ($optel == 1) { $kleur = ""; }
	     if ($optel == 2) { $kleur = " class=\"altrow\""; }

         // make up the description to print according to the pricelist_format and max_description
         if ($pricelist_format == 0) { $print_description = $row_details[1]; }
         if ($pricelist_format == 1) { $print_description = $row_details[3]; }
         if ($pricelist_format == 2) { $print_description = $row_details[1]." - ".$row_details[3]; }
         if ($max_description != 0) {
            $description = stringsplit($print_description, $max_description); // so lets only show the first xx characters
            if (strlen($print_description) != strlen($description[0])) { $description[0] = $description[0] . ".."; }
            $print_description = $description[0];
         }
         $print_description = strip_tags($print_description); //remove html because of danger of broken tags
?>
               <tr<?php echo $kleur; ?>>
                   <td><a href="index.php?page=details&prod=<?php echo $row_details[0]; ?>"><?php echo $print_description; ?></a></td>
                   <td><?php 
                         echo $currency_symbol;
                         $subtotaal = $row_details[4] * $row[6];
                         if ($no_vat == 0 && $db_prices_including_vat == 0) { $subtotaal = $subtotaal * $vat; }
                         $printprijs = myNumberFormat($subtotaal, $number_format);
                         echo $printprijs;
                       ?>
                   </td>
                   <td>
                   <form method="POST" action="index.php?page=cart&action=update">
                    <input type="hidden" name="prodid" value="<?php echo $row_details[0] ?>">
                    <input type="hidden" name="basketid" value="<?php echo $row[0] ?>">
                    <div style="text-align:right;"><input type="text" size="4" name="numprod" value="<?php echo $row[6] ?>">&nbsp;<input type="submit" value="<?php echo $txt['cart10'] ?>" name="sub">
                   </form>
                   <form method="POST" action="index.php?page=cart&action=update">
                    <input type="hidden" name="prodid" value="<?php echo $row_details[0] ?>">
                    <input type="hidden" name="basketid" value="<?php echo $row[0] ?>">
                    <input type="hidden" name="numprod" value="0">
                    <div style="text-align:right;"><input type="submit" value="<?php echo $txt['cart6']; ?>" name="sub">
                   </form>
                   </td>
               </tr>
               <?php

               $totaal = $totaal + $subtotaal;
         }
   }
   if ($no_vat == 0 ) {
      $totaal_ex = $totaal / $vat;
      $totaal_ex = myNumberFormat($totaal_ex,$number_format);
   }
   $totaal = myNumberFormat($totaal,$number_format);
   ?>
      <tr><td colspan="3"><div style="text-align:right;"><strong><?php echo $txt['cart7']; ?></strong> <?php echo $currency_symbol . $totaal ?><br />
      <?php if ($no_vat == 0) { echo "<small>(".$currency_symbol.$totaal_ex." ".$txt['general6']." ".$txt['general5'].")</small>"; } ?></div></td></tr>
   </table>
   <br />
   <br />
   <div style="text-align:center;">

    <table class="borderless" width="50%">
     <tr><td nowrap>
           <form method="post" action="index.php?page=cart&action=empty">
            <input type="submit" value="<?php echo $txt['cart8']; ?>">
           </form>
         </td>
         <td>
            <?php
               // if the conditions page is disabled, then we might as well skip it ;)
               if ($conditions_page == 1) { echo "<form method=\"post\" action=\"index.php?page=conditions&action=checkout\">"; }
               else { echo "<form method=\"post\" action=\"index.php?page=shipping\">"; }
            ?>  
            <input type="submit" value="<?php echo $txt['cart9']; ?>">
           </form>
         </td>
     </tr>
    </table>
   </div>
   <?php
   }
   ?>