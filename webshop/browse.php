<?php
/*  browse.php
    Copyright 2006 Elmar Wenners
    Support site: http://www.chaozz.nl

    This file is part of UltraShop.

    UltraShop is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    UltraShop is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with UltraShop; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
?>
<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php
      $searchmethod = " AND "; //default

      if (!empty($_POST['searchmethod'])) {
	      $searchmethod=$_POST['searchmethod'];
      }
      if (!empty($_POST['searchfor'])) {
	      $searchfor=$_POST['searchfor'];
      }
      if (!empty($_GET['search'])) {
	      $searchfor=$_GET['search'];
      }
      if (!empty($_GET['orderby'])) {
	      $orderby=$_GET['orderby'];
      }
      if ($orderby == NULL || $orderby == "") { $orderby = "DESCRIPTION"; }
?>

<?php
    if (!$cat == NULL){
    // find the product category
     $query = sprintf("SELECT * FROM `category` where `ID`=%s", quote_smart($cat));
     $sql = mysql_query($query) or die(mysql_error());

     while ($row = mysql_fetch_row($sql)) {
            $categorie = $row[1];
            }
     }
    else {
	     $categorie = $txt['browse1'] . " / " . $searchfor;
    }
?>

          <table width="100%" class="datatable">
            <caption><?php echo $txt['browse9']; ?></caption>
           <tr><th>
                    <?php 
                        echo $txt['browse2']." / ".$categorie;
                        echo "<br />";
                        if ($action == "list") { echo "<a href=\"index.php?page=browse&action=list&group=". $group . "&cat=". $cat . "&orderby=DESCRIPTION\"><small>" . $txt['browse4'] . "</small></a>";  }
                    ?>
               </th><th>
			        <?php 
			            echo "<div style=\"text-align:right;\">";
			            echo $txt['browse3']; 
			        	// if we use VAT, then display that the prices are including VAT in the list below
				        if ($no_vat == 0) { echo " (".$txt['general7']." ".$txt['general5'].")"; }
                        echo "<br />";
                        if ($action == "list") { echo "<a href=\"index.php?page=browse&action=list&group=". $group . "&cat=". $cat . "&orderby=PRICE\"><small>" . $txt['browse4'] . "</small></a>";  }
                        echo "</div>";
			        ?>
               </th>
           </tr>
  <?php


 	if ($action == "list") {
	 	if ($stock_enabled == 1 && IsAdmin() == false) { // filter out products with stock lower than 1
	 	    $query = sprintf("SELECT * FROM `product` where `STOCK` > 0 AND `CATID`=%s ORDER BY %s", quote_smart($cat), quote_smart($orderby));

	 	}
	 	else { $query = sprintf("SELECT * FROM `product` WHERE `STOCK` > 0 AND CATID=%s ORDER BY %s", quote_smart($cat), quote_smart($orderby)); }
    }
    elseif ($action == "shownew") {
	 	if ($stock_enabled == 1 && IsAdmin() == false) { // filter out products with stock lower than 1
            $query = sprintf("SELECT * FROM `product` WHERE `STOCK` > 0 AND `NEW` = '1' ORDER BY %s", quote_smart($orderby));
        }
        else { $query = sprintf("SELECT * FROM `product` WHERE `NEW` = '1' ORDER BY %s", quote_smart($orderby)); }
    }
	    
    else {
         //search on the given terms
         $searchitem = explode (" ", $searchfor);
         if ($stock_enabled == 1) {
       	     $searchquery = "WHERE `STOCK` > 0 AND ((DESCRIPTION LIKE '%" . $searchitem[0] . "%') OR (PRODUCTID = '" . $searchitem[0] . "'))";
   	     }
   	     else { $searchquery = "WHERE ((DESCRIPTION LIKE '%" . $searchitem[0] . "%') OR (PRODUCTID = '" . $searchitem[0] . "'))"; }
	     $counter = 1;

	     while (!$searchitem[$counter] == NULL){
	           $searchquery = $searchquery . $searchmethod . "(DESCRIPTION LIKE '%" . $searchitem[$counter] . "%')";
               $counter = $counter +1;
         }
         $query = "SELECT * FROM product $searchquery ORDER BY $orderby";
	}
	$sql = mysql_query($query) or die(mysql_error());
    
    if (mysql_num_rows($sql) == 0) {
	   echo "<tr><td>".$txt['browse5']."</td><td>&nbsp;</td></tr></table>";
    }
    else {
	    
      $optel = 0;

      while ($row = mysql_fetch_row($sql)) {
	          $optel = $optel +1;
	          if ($optel == 3) { $optel = 1; }
	          if ($optel == 1) { $kleur = ""; }
	          if ($optel == 2) { $kleur = " class=\"altrow\""; }
              
	          // the price gets calculated here
	          $printprijs = $row[4]; // from the database
	          if ($db_prices_including_vat == 0 && $no_vat == 0) { $printprijs = $row[4] * $vat; }
              $printprijs = myNumberFormat($printprijs,$number_format); // format to our settings

              // reset values
              $picturelink = "";
              $new = "";
              $thumb = "";
              
              // new product?
              if ($row[7] == 1) { $new = "<font color=\"red\"><strong>" . $txt['general3']. "</strong></font>"; }
              
              // is there a picture?
              if ($search_prodgfx == 1 && $use_prodgfx == 1) {
	              
	              if ($pictureid == 1) { $picture = $row[0]; }
	              else { $picture = $row[1]; }
	              
	              // determine resize of thumbs
                  $width = "";
                  $height = "";
                  if ($pricelist_thumb_width != 0) { $width = " width=\"".$pricelist_thumb_width."\""; }
                  if ($pricelist_thumb_height != 0) { $height = " height=\"".$pricelist_thumb_height."\""; }
                  
	              if (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/".$picture.".jpg\"".$width.$height." alt=\"\" />"; }
	              if (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/".$picture.".gif\"".$width.$height." alt=\"\" />"; }
	              if (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = "<img class=\"imgleft\" src=\"".$product_dir."/".$picture.".png\"".$width.$height." alt=\"\" />"; }
	              
	              if ($thumb != "" && $thumbs_in_pricelist == 0) {
		              // use a photo icon instead of a thumb
		              $picturelink = "<a href=\"".$product_dir."/".$picture.".jpg\"><img src=".$gfx_dir."/photo.gif></a>";
		              $thumb = "";
	              }
              }
              
              // see if you are an admin. if so, add a [EDIT] link to the line
              $admin_edit = "";
              if (IsAdmin() == true) {
		          // determine how to name the picture
		          if ($pictureid == 1) {
			          $picid = $row[0];         // pic id is database id
		          }
		          else { $picid = $row[1]; }    // pic id is product id
                  
                  $admin_edit = "<br />";
                  if ($stock_enabled == 1) { $admin_edit = $admin_edit.$txt['productadmin12']." ".$row[5]; }
                  $admin_edit = $admin_edit."&nbsp;|&nbsp;<a href=?page=productadmin&action=edit_product&pcat=".$cat."&prodid=".$row[0].">".$txt['browse7']."</a>";
                  $admin_edit = $admin_edit."&nbsp;|&nbsp;<a href=?page=productadmin&action=delete_product&pcat=".$cat."&prodid=".$row[0].">".$txt['browse8']."</a>";
                  $admin_edit = $admin_edit."&nbsp;|&nbsp;<a href=?page=productadmin&action=picture_upload_form&picid=".$picid.">".$txt['browse10']."</a>";
              }
              // make up the description to print according to the pricelist_format and max_description
              if ($pricelist_format == 0) { $print_description = $row[1]; }
              if ($pricelist_format == 1) { $print_description = $row[3]; }
              if ($pricelist_format == 2) { $print_description = $row[1]." - ".$row[3]; }
              if ($max_description != 0) {
                 $description = stringsplit($print_description, $max_description); // so lets only show the first xx characters
                 if (strlen($print_description) != strlen($description[0])) { $description[0] = $description[0] . ".."; }
                 $print_description = $description[0];
              }
              $print_description = strip_tags($print_description); //remove html because of danger of broken tags
              
              echo "<tr".$kleur.">";
              
              // see what the stock is
              if ($stock_enabled == 0) {
	              if ($row[5] == 1) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_stock1']."\" /> "; } // in stock
	              if ($row[5] == 0) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_stock2']."\" /> "; } // out of stock
	              if ($row[5] == 2) { $stockpic = "<img class=\"imgleft\" src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_stock3']."\" /> "; } // in backorder
              }
              else { $stockpic = ""; }
              
              echo "<td>".$stockpic."<a class=\"plain\" href=\"index.php?page=details&prod=".$row[0]."&cat=".$row[2]."&group=".$group."\">".$thumb.$print_description."</a> ".$picturelink." ".$new." ".$admin_edit."</td>";
              echo "<td><div style=\"text-align:right;\">".$currency_symbol."&nbsp;".$printprijs."</div></td>";
              
              echo "</tr>";
               } ?>
         </table>
        </tr></td>
       </table>
       <div style="text-align:right;"><img src="<?php echo $gfx_dir ?>/photo.gif" alt="" /> <em><small><?php echo $txt['browse6'] ?></small></em></div>
<?php      
  if ($stock_enabled == 0) {  
?>       
       <br />
       <br />
          <table width="50%" class="datatable">
            <caption><?php echo $txt['db_stock10'] ?></caption>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_green.gif\" alt=\"".$txt['db_stock1']."\" />"; ?></td>
                  <td><?php echo $txt['db_stock11']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_red.gif\" alt=\"".$txt['db_stock2']."\" />"; ?></td>
                  <td><?php echo $txt['db_stock12']; ?></td>
              </tr>
              <tr>
                  <td><?php echo "<img src=\"".$gfx_dir."/bullit_orange.gif\" alt=\"".$txt['db_stock3']."\" />"; ?></td>
                  <td><?php echo $txt['db_stock13']; ?></td>
              </tr>
       </table>
<?php       
     }
  }
?>  