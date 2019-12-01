<?php
/*  shippingadmin.php
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
     // admin check
    if (IsAdmin() == false) {
	  PutWindow ($txt['general12'], $txt['general2'], "warning.gif", "50");
	  exit(include("includes/exit.inc.php"));
    }
     // ok, let's do the updating/deleting/moving here
     
     // add a shipping method
     if ($action == "add_shipping") {
	      if (!empty($_POST['description'])) {
	          $description=$_POST['description'];
          }
          $rate = 0;
	      if (!empty($_POST['rate'])) {
	          $rate=$_POST['rate'];
          }
	      $country=CheckBox($_POST['country']);
	      
          if ($description != "") {
	          $query="INSERT INTO `shipping` (`description`, `rate`, `country`) VALUES ('".$description."', '".$rate."', ".$country.")";
	          $sql = mysql_query($query) or die(mysql_error());
	          PutWindow ($txt['general13'], $txt['shippingadmin1'], "notify.gif", "50");
          }
     }
     
     // edit a shipping method
     if ($action == "update_shipping") {
	      if (!empty($_POST['sid'])) {
	          $sid=$_POST['sid'];
          }
	      if (!empty($_POST['description'])) {
	          $description=$_POST['description'];
          }
          $rate = 0;
  	      if (!empty($_POST['rate'])) {
	          $rate=$_POST['rate'];
          }
          $country=CheckBox($_POST['country']);
          
          if ($description != "") {
	          // shipping data
	          $query="UPDATE `shipping` SET `description`='".$description."', `rate`=".$rate.", `country`=".$country." WHERE `id`=".$sid;
	          $sql = mysql_query($query) or die(mysql_error());
	          
	          // payment data
              $query="SELECT * FROM `payment`";
			  $sql = mysql_query($query) or die(mysql_error());
	                 	          
              if (mysql_num_rows($sql) <> 0) { 
	              while ($row = mysql_fetch_row($sql)) {
		                 $selected=CheckBox($_POST[$row[0]]);
	  		             if ($selected == 1) {
		                 	$query_shippay="SELECT * FROM `shipping_payment` WHERE `shippingid`='".$sid."' AND paymentid='".$row[0]."'";
				         	$sql_shippay = mysql_query($query_shippay) or die(mysql_error());
				            // if it's not found then add it
				            if (mysql_num_rows($sql_shippay) == 0) { 
						        $query_add="INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES ('".$sid."', '".$row[0]."')";
						        $sql_add = mysql_query($query_add) or die(mysql_error());
				            }
		                 }
		                 else {
			                    // it's not selected, so lets remove the record that binds this shipping to this payment method
						        $query_del="DELETE FROM `shipping_payment` WHERE `shippingid`='".$sid."' AND `paymentid`='".$row[0]."'";
						        $sql_del = mysql_query($query_del) or die(mysql_error());
		                 }
	              }
              }
              
	          PutWindow ($txt['general13'], $txt['shippingadmin3'], "notify.gif", "50");
          }
      }      

     // delete a shipping method
     if ($action == "delete_shipping") {
	     if (!empty($_GET['sid'])) {
	          $sid=$_GET['sid'];
          }
          // remove shipping method
          $query="DELETE FROM `shipping` WHERE `id`=".$sid;
          $sql = mysql_query($query) or die(mysql_error());
          
          // remove links to payment methods
          $query_del="DELETE FROM `shipping_payment` WHERE `shippingid`='".$sid."'";
          $sql_del = mysql_query($query_del) or die(mysql_error());
          
          PutWindow ($txt['general13'], $txt['shippingadmin2'], "notify.gif", "50");
      } 
      // show a shipping method for editing
      if ($action == "show_shipping") {
	     if (!empty($_GET['sid'])) {
	          $sid=$_GET['sid'];
          }
         $query="SELECT * FROM `shipping` WHERE `id`=".$sid;
         $sql = mysql_query($query) or die(mysql_error());
         
         while ($row = mysql_fetch_row($sql)) {	   
	          if ($row[4] == 1) { PutWindow ($txt['general13'], $txt['shippingadmin15'], "warning.gif", "50"); }   // part of system!
	          echo "<table width=\"100%\" class=\"datatable\">";
	          echo "<caption>".$txt['shippingadmin14']."</caption>";
	          echo "<form method=\"POST\" action=\"index.php?page=shippingadmin&action=update_shipping\">";
	          echo "<input name=\"sid\" type=\"hidden\" value=\"".$row[0]."\">";
	          echo "<tr><td>";
	          echo $txt['shippingadmin5']."<br />";
	          echo "<input name=\"description\" type=\"text\" value=\"".$row[1]."\" size=\"30\" maxlength=\"150\"><br /><br />";
	          echo $txt['shippingadmin6']."<br />";
	          echo "<input name=\"rate\" type=\"text\" value=\"".$row[2]."\" size=\"5\" maxlength=\"7\"><br /><br />";
	          echo $txt['shippingadmin7']."<br />";
	          echo "<input name=\"country\" type=\"checkbox\" "; if ($row[3] == 1) echo "checked"; echo ">";
	          echo "</td><td>";
	          echo $txt['shippingadmin13']."<br />";
	          // for every payment method we add a checkbox
	          $query_pay="SELECT * FROM `payment`";
	          $sql_pay = mysql_query($query_pay) or die(mysql_error());
	         
	          while ($row_pay = mysql_fetch_row($sql_pay)) {	      
			          $query_shippay="SELECT * FROM `shipping_payment` WHERE `shippingid`='".$sid."' AND paymentid='".$row_pay[0]."'";
			          $sql_shippay = mysql_query($query_shippay) or die(mysql_error());
			         
			          if (mysql_num_rows($sql_shippay) <> 0) { 
				             $checked = "checked"; }
			          else { $checked = ""; }
    	              echo "<input name=\"".$row_pay[0]."\" type=\"checkbox\" ".$checked.">&nbsp;".$row_pay[1]."<br />";
		      }
		      echo "</td></tr>";    
		      echo "<tr class=\"altrow\"><td colspan=\"2\">";
	          echo "<h4><input type=\"submit\" value=\"".$txt['shippingadmin8']."\"></h4>";
	          echo "</td></tr>";
              echo "</form>";
              echo "</table>";
     	      exit(include("includes/exit.inc.php"));
         }
      }
     
         echo "<table width=\"100%\" class=\"datatable\">";
         echo "  <caption>".$txt['shippingadmin4']."</caption>";
         echo "  <tr><th>".$txt['shippingadmin5']."</th><th>".$txt['shippingadmin6']."</th><th>".$txt['shippingadmin7']."</th><th>".$txt['shippingadmin12']."</th></tr>";
         // add a shipping method
         echo "  <form method=\"POST\" action=\"index.php?page=shippingadmin&action=add_shipping\">";
         echo "  <tr class=\"altrow\">";
         echo "    <td><input name=\"description\" type=\"text\" value=\"\" size=\"30\" maxlength=\"150\"></td>";
         echo "    <td><input name=\"rate\" type=\"text\" value=\"\" size=\"5\" maxlength=\"7\"></td>";
         echo "    <td><input name=\"country\" type=\"checkbox\"></td>";
         echo "    <td><input type=\"submit\" value=\"".$txt['shippingadmin10']."\"></td>";
         echo "  </tr>";
         echo "  </form>";
         
         $query="SELECT * FROM `shipping`";
         $sql = mysql_query($query) or die(mysql_error());
         
         while ($row = mysql_fetch_row($sql)) {
	            echo "  <tr>";
	            echo "    <td>".$row[1]."</td>";
	            echo "    <td>".$row[2]."</td>";
	            echo "    <td>".$row[3]."</td>";
	            echo "    <td><a class=\"plain\" href=\"?page=shippingadmin&sid=".$row[0]."&action=show_shipping\">".$txt['shippingadmin8']."</a><br />";
                if ($row[4] <> 1) { echo "    <a class=\"plain\" href=\"?page=shippingadmin&sid=".$row[0]."&action=delete_shipping\">".$txt['shippingadmin9']."</a></td>"; }
	            echo "  </tr>";
         }    
         echo "</table>";
         echo "<br /><br />";
         echo "<h6>".$txt['shippingadmin17']."</h6>";
         echo "<ul>";
         echo "<li><a class=\"plain\" href=\"?page=adminedit&filename=countries.txt&root=1&wysiwyg=0\">".$txt['shippingadmin11']."</a></li>";
         echo "<li><a class=\"plain\" href=\"?page=editsettings&show=2\">" .$txt['shippingadmin16']."</a></li>";
         echo "</ul>";
?>