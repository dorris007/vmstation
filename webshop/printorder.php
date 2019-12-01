<?php
/*  printorder.php
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
<?php
    if (!empty($_GET['orderid'])) {
	   $orderid=intval($_GET['orderid']);
    }
    
    include("includes/readcookie.inc.php");
    include("includes/settings.inc.php");
    include("includes/connectdb.inc.php");
 	    
    // lets check if the order you are trying to read is REALLY your own order
    $query = sprintf("SELECT * FROM `order` WHERE ID = %s", quote_smart($orderid));
    $sql = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_row($sql)) { 
	       $webid = $row[7];
	       $ownerid = $row[5];
	}
    
    if ($ownerid != $customerid && IsAdmin() == false) {
       PutWindow ($txt['general12'], $txt['general1'], "warning.gif", "50"); // access denied
	   exit(include("includes/exit.inc.php"));
    }
    $fp = fopen($orders_dir."/".$webid, "rb") or die("Couldn't open order");
    $order = fread($fp, filesize($orders_dir."/".$webid));
    fclose($fp);
    
    // in version 2.1 we switched to html orders, but included the html header. that is wrong
    // from version 2.2 the orders are saved in html, but without the header. the part below 
    // is to be compatible with 2.1
    if (substr ($order, 0, 6) == "<html>") { 
	    $order = str_replace("<body>", "<body onLoad=\"javascript:window.print()\">", $order);
	    echo $order; }
    else {
	    // if there are linebreaks, then we have a new order. if not, then it's an old one that needs nl2br
		$pos = strpos ($order, "<br />");
		if ($pos === false) { $order = nl2br($order); }
	    
?>
			<html>
			 <head>
			  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset ?>">
			  <title><?php echo $webid ?></title>
			 </head>
			 <body onLoad="javascript:window.print()"> 
			   <?php echo $order; ?>
			 </body>
			</html> 
			
<?php 
    }
?>