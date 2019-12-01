<?php
/*  details.php
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
<?php
	// read product details
    $query = sprintf("SELECT * FROM `product` where `ID`=%s", quote_smart($prod));
	$sql = mysql_query($query) or die(mysql_error());
    
	if (mysql_num_rows($sql) == 0) {
		PutWindow($txt['general12'], $txt['general9'], "warning.gif", "50");
	}
	else {
	    while ($row = mysql_fetch_row($sql)) {
	          $screenshot = "";
	          
	          if ($use_prodgfx == 1) {
	              if ($pictureid == 1) { 
		              $picture = $row[0]; 
	              }
	              else { $picture = $row[1]; }
	              
	              $thumb = "";
	              
		          if (thumb_exists($product_dir ."/". $picture . ".jpg")) { $thumb = $product_dir ."/". $picture . ".jpg"; }
		          if (thumb_exists($product_dir ."/". $picture . ".gif")) { $thumb = $product_dir ."/". $picture . ".gif"; }
		          if (thumb_exists($product_dir ."/". $picture . ".png")) { $thumb = $product_dir ."/". $picture . ".png"; }
		          
			      if ($thumb == "") { $thumb = $gfx_dir."/nothumb.jpg"; }
			      
		          $size = getimagesize("$thumb");
		          $height = $size[1];
		          $width = $size[0];
		          $resized = 0;
		          if ($height > $product_max_height)
		             {
		               $height = $product_max_height;
		               $percent = ($size[1] / $height);
		               $width = round(($size[0] / $percent));
		               $resized = 1;
		             }
		          if ($width > $product_max_width)
		             {
		               $width = $product_max_width;
		               $percent = ($size[0] / $width);
		               $height = round(($size[1] / $percent));
		               $resized = 1;
		             }
		          if ($resized == 0) { $screenshot = "<img class=\"borderimg\" src=\"".$thumb."\" height=".$height." width=".$width." alt=\"\" />"; }
		          else { $screenshot = "<a href=\"".$thumb."\"><img class=\"borderimg\" src=\"".$thumb."\" height=".$height." width=".$width." alt=\"\"/><br />".$txt['details9']."</a>"; }
		      }
	
	          ?>
	          <table width="85%" class="datatable">
	            <caption><?php echo $txt['details1'] ?></caption>
	           <tr><td>
	               <h5><?php echo $txt['details2'] ?>: <?php echo $row[1] ?></h5><br />
	               <br />
	               <div style="text-align:center;"><?php echo $screenshot; ?>
	               <br />
	               <?php
		               // show extra admin options?
		               $admin_edit = "";
	                   if (IsAdmin() == true) {
				           // determine how to name the picture
				           if ($pictureid == 1) {
					           $picid = $row[0];         // pic id is database id
				           }
				           else { $picid = $row[1]; }    // pic id is product id
		                   
	                       $admin_edit = "<br /><br />";
	                       $admin_edit = $admin_edit."<a href=?page=productadmin&action=edit_product&pcat=".$cat."&prodid=".$row[0].">".$txt['browse7']."</a>";
	                       $admin_edit = $admin_edit."&nbsp;|&nbsp;<a href=?page=productadmin&action=delete_product&pcat=".$cat."&prodid=".$row[0].">".$txt['browse8']."</a>";
	                       $admin_edit = $admin_edit."&nbsp;|&nbsp;<a href=?page=productadmin&action=picture_upload_form&picid=".$picid.">".$txt['browse10']."</a>";
	                   } 
	               ?>
	                 <br />
	                 <br />
	                 <table class="borderless" width="90%">
	                  <tr><td class="borderless">
	                     <div style="text-align:left;">
		                   <em><strong><?php echo $txt['details4'] ?>:</strong></em>
		                   <ul><li><?php echo nl2br($row[3])." ".$admin_edit ?></li></ul>
		                 </div>
		              </td></tr>
		             </table>
		           </div>      
	               <br />
	               <form method="POST" action="index.php?page=cart&action=add">
	               <div style="text-align:right">
	                <input type="hidden" name="prodid" value="<?php echo $row[0] ?>">
	                                <?php
	                                       if (!$row[4] == 0) {
		                                       if ($no_vat == 1) {
	                                               $in_vat = myNumberFormat($row[4],$number_format);
	                                               echo "<big><strong>" . $txt['details5'] . ": ". $currency_symbol .  $in_vat . "</strong></big>";
			                                   }
			                                   else {    
		                                            if ($db_prices_including_vat == 1) {
		                                               $ex_vat = $row[4] / $vat;    
	                                                   $in_vat = myNumberFormat($row[4],$number_format);
	                                                   $ex_vat = myNumberFormat($ex_vat,$number_format);
	                                                }
	                                                else {
		                                               $in_vat = $row[4] * $vat;    
	                                                   $ex_vat = myNumberFormat($row[4],$number_format);
	                                                   $in_vat = myNumberFormat($in_vat,$number_format);
	                                                }
	                                                echo "<big><strong>" . $txt['details5'] . ": ". $currency_symbol .  $in_vat . "</strong></big>";
	                                                echo "<br /><small>(".$currency_symbol.$ex_vat." ".$txt['general6']." ".$txt['general5'].")</small>";
	                                           }
	
	                                 ?>
	                                   <br />
	                                   <br />
	                                   <?php echo $txt['details6'] ?>: <input type="text" size="4" name="numprod" value="1" maxlength="3">&nbsp;<input type="submit" value="<?php echo $txt['details7'] ?>" name="sub">
	                                       <?php } ?>
	               </form>
	               </div>
	               </td>
	           </tr>
	          </table>
	          <?php
	            if (!$refermain == 1) {
		      ?>
	          <br />
	          <h4><a href="javascript:history.go(-1)"><?php echo $txt['details8'] ?></a></h4>
	          <?php
	           }
	       }
    }       
?>