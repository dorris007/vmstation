<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php include ("checklogin.php"); ?>
<?php include ("addons/tinymce/tinymce.inc"); ?>

<?php
	// admin check
    if (IsAdmin() == false) {
	  PutWindow ($txt['general12'], $txt['general2'], "warning.gif", "50");
	  exit(include("includes/exit.inc.php"));
    }
          
      if ($action == "edit_product" || $action == "delete_product") {
          if (!empty($_GET['prodid'])) {
	          $prodid=$_GET['prodid'];
          }
          if (!empty($_GET['pgroud'])) {
	          $pgroup=$_GET['pgroup'];
          }
          if (!empty($_GET['pcat'])) {
	          $pcat=$_GET['pcat'];
          }
      }
      if ($action == "add_product") {
	      $pnew=0;
	      $pgroup=0; 
	      $pcat=0; 
	      $pfrontpage=0;
	      $price=0;
	      $pstock=1; // let's presume that when you add a product it's in stock
      }
      if ($action == "save_new_product" || $action == "update_product") {      
          if (!empty($_POST['pid'])) {
	      $pid=$_POST['pid'];
          }
          if (!empty($_POST['pcat'])) {
	      $pcat=$_POST['pcat'];
          }
          if (!empty($_POST['text2edit'])) {
	      $pdescription=$_POST['text2edit'];
          }
          if (!empty($_POST['pprice'])) {
	      $pprice=$_POST['pprice'];
          }
          if (!empty($_POST['pstock'])) {
	      $pstock=$_POST['pstock'];
          }
          $pfrontpage=CheckBox($_POST['pfrontpage']);
	      $pnew=CheckBox($_POST['pnew']);
      }
      if ($action == "update_product") {
          if (!empty($_POST['prodid'])) {
	      $prodid=$_POST['prodid'];
          }
      }
?>	      
<?php
    if ($action == "del_image") {
	   if (!empty($_GET['del_name'])) {
		   $del_name=$_GET['del_name'];
		   
		   if (file_exists($del_name)) { 
			   unlink($del_name); 
			   PutWindow ($txt['general13'] , $txt['productadmin25'], "notify.gif", "50");
		   }
       }
    }
    
    // upload the screenshot to the correct folder
    if ($action == "upload_screenshot") {
          if (!empty($_POST['picid'])) {
	          $picid=$_POST['picid'];
          }
          if (!empty($_GET['picid'])) {
	          $picid=$_GET['picid'];
          }
          
          $file = $_FILES['uploadedfile']['name'];
          $ext = explode(".", $file);
          $ext = strtolower(array_pop($ext));

          if ($ext == "jpg" || $ext == "gif" || $ext == "png") {        
             $target_path = $product_dir."/";
             $target_path = $target_path.$picid;
             
             // delete old gif or jpg if it is found
             if (file_exists($target_path.".jpg")) { unlink($target_path.".jpg"); }
             if (file_exists($target_path.".gif")) { unlink($target_path.".gif"); }
             if (file_exists($target_path.".png")) { unlink($target_path.".png"); }

             $target_path = $target_path.".".$ext; 
            
             if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
	            chmod($target_path,0644); 
                PutWindow ($txt['general13'], basename( $_FILES['uploadedfile']['name']).$txt['productadmin1'].$target_path, "notify.gif", "50");
                echo "<h4><a href=?page=productadmin&action=add_product>".$txt['productadmin4']."</a></h4>";
             } 
             else{
                PutWindow ($txt['general12'], $txt['productadmin2'], "warning.gif", "50");
             }
         }
         else { PutWindow ($txt['general12'], $txt['productadmin3'], "warning.gif", "50"); }
    }
    
    // save new product in database
    if ($action == "save_new_product") {
	    $query="INSERT INTO `product` (`PRODUCTID`,`CATID`,`DESCRIPTION`,`PRICE`,`STOCK`,`FRONTPAGE`,`NEW`) VALUES ('".$pid."','".$pcat."','".$pdescription."','".$pprice."','".$pstock."','".$pfrontpage."','".$pnew."')";
        $sql = mysql_query($query) or die(mysql_error());
        
        // what the picture should be named like depends on settings
        if ($pictureid == 2) { 
	        $picid = $pid; 
	    }
        else { $picid = mysql_insert_id(); }
        
        echo "<h4><a href=?page=productadmin&action=add_product>".$txt['productadmin4']."</a></h4>";
        $action = "picture_upload_form";
    }
    
    // update product with new values in database
    if ($action == "update_product") {
	    // first lets see if the product id has changed. if it has, then the screenshot should be renamed too (if a screenshot is found)
	    $query="SELECT * FROM `product` WHERE ID=".$prodid;
	    $sql = mysql_query($query) or die(mysql_error());
	    while ($row = mysql_fetch_row($sql)) {
		       // if the product id has changed and $pictureid (which holds the setting what to use for the picture name) = 2, then rename it to the new id
		       if ($row[1] != $pid && $pictureid == 2) {
			       if (file_exists($product_dir."/".$row[1].".jpg")) { rename($product_dir."/".$row[1].".jpg", $product_dir."/".$pid.".jpg"); }
			       if (file_exists($product_dir."/".$row[1].".gif")) { rename($product_dir."/".$row[1].".gif", $product_dir."/".$pid.".gif"); }
			       if (file_exists($product_dir."/".$row[1].".png")) { rename($product_dir."/".$row[1].".png", $product_dir."/".$pid.".png"); }
	           }
	           // determine how to name the picture
	           if ($pictureid == 1) {
		           $picid = $row[0];         // pic id is database id
	           }
	           else { $picid = $row[1]; }    // pic id is product id

        }
	    // now save new data
        $query="UPDATE `product` SET `PRODUCTID`='".$pid."',`CATID`='".$pcat."',`DESCRIPTION`='".$pdescription."',`PRICE`='".$pprice."',`STOCK`='".$pstock."',`FRONTPAGE`='".$pfrontpage."',`NEW`='".$pnew."' WHERE ID=".$prodid;
	    $sql = mysql_query($query) or die(mysql_error());
        echo "<h4><a href=?page=browse&action=list&group=".$pgroup."&cat=".$pcat."&orderby=DESCRIPTION>".$txt['productadmin5']."</a></h4>";
        $action = "picture_upload_form";
    }
    // optionally upload a screenshot
    if ($action == "picture_upload_form") {
	    echo "<br /><br />";
	    if ($picid == "" || is_null($picid)) {
		    PutWindow ($txt['general12'], $txt['productadmin23'], "warning.gif", "50");
	    }
	    else {
		        $thumb = "";
		        if (file_exists($product_dir."/".$picid.".jpg")) { $thumb = $product_dir."/".$picid.".jpg"; }
		        if (file_exists($product_dir."/".$picid.".gif")) { $thumb = $product_dir."/".$picid.".gif"; }
		        if (file_exists($product_dir."/".$picid.".png")) { $thumb = $product_dir."/".$picid.".png"; }
		        if ($thumb != "") {       
		            $size = getimagesize("$thumb");
		            $height = $size[1];
		            $width = $size[0];
		            if ($height > 350)
		               {
		                 $height = 350;
		                 $percent = ($size[1] / $height);
		                 $width = round($size[0] / $percent);
		               }
		            if ($width > 450)
		               {
		                 $width = 450;
		                 $percent = ($size[0] / $width);
		                 $height = round($size[1] / $percent);
		               }
			        echo "<h4><img src=\"".$thumb."\" class=\"borderimg\" height=".$height." width=".$width."><br />";
			        echo "<a href=\"index.php?page=productadmin&action=del_image&del_name=".$thumb."\">".$txt['productadmin24']."</a></h4>";
		   	    }
		        echo "<br /><br />";
                echo "<table width=\"80%\" class=\"datatable\">";
                echo "<caption>".$txt['productadmin21']."</caption>";
		        echo "<tr><td>";	    
		        echo "<form enctype=\"multipart/form-data\" action=\"index.php?page=productadmin\" method=\"POST\">";
		        echo "<input type=\"hidden\" name=\"action\" value=\"upload_screenshot\">";
		        echo "<input type=\"hidden\" name=\"picid\" value=\"".$picid."\">";
			    echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"500000\">";
			    echo $txt['productadmin19']." <input name=\"uploadedfile\" type=\"file\"><br />";
			    echo "<input type=\"submit\" value=\"".$txt['productadmin20']."\">";
			    echo "</form>";
			    echo "</td></tr></table>";
		    }
	}
	
    // delete product
    if ($action == "delete_product") {
	    // find out the category, so we can beam you back
	    $query="SELECT * FROM `product` WHERE ID=".$prodid;
	    $sql = mysql_query($query) or die(mysql_error());
	    while ($row = mysql_fetch_row($sql)) {
               $pcat = $row[2];
        }
	    $query="DELETE FROM `product` WHERE ID=".$prodid;
	    $sql = mysql_query($query) or die(mysql_error());
	    PutWindow ($txt['general13'] , $txt['productadmin26'], "notify.gif", "50");
        //echo "<a href=?page=browse&action=list&cat=".$pcat."&orderby=DESCRIPTION>".$txt['productadmin5']."</a>";
    }
    
    // read values to show in form
    if ($action == "edit_product") {
	    $query = "SELECT * FROM `product` WHERE ID=".$prodid;
	    $sql = mysql_query($query) or die(mysql_error());
	       while ($row = mysql_fetch_row($sql)) {
                 $pid = $row[1];
                 $pcat = $row[2];
                 $pdescription = $row[3];
                 $pprice = $row[4];
                 $pstock = $row[5];
                 $pfrontpage = $row[6];
                 $pnew = $row[7];
           }
    }
    
    // show form with or without values
    if ($action == "add_product" || $action == "edit_product") {
	    
       echo "<table width=\"90%\" class=\"datatable\">";
       echo "<caption>";
       if ($action == "add_product") {
           echo $txt['productadmin6'];
       }
       else {
          echo $txt['productadmin7'];
       }
       echo "</caption>";
       echo "<tr><td>";
       echo "<form method=\"POST\" action=\"index.php?page=productadmin\">";
       echo $txt['productadmin18']." <select name=\"pcat\">";
    
         $error = 0;
         
         // pull down menu with all groups and their categories
         $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
	     $sql = mysql_query($query) or die(mysql_error());
         
	     $groupNum = 0;
         $catNum = 0;
         
         if (mysql_num_rows($sql) == 0) {
	        echo "</select><br /><br />".$txt['productadmin8'];
	        $groupNum = 0;
           }
	       else {
		         $groupNum = $groupNum +1;
                 while ($row = mysql_fetch_row($sql)) {
                   
                    $query_cat = "SELECT * FROM `category` WHERE `GROUPID` = " . $row[0] . " ORDER BY `DESC` ASC";
	                $sql_cat = mysql_query($query_cat) or die(mysql_error());

                    if (mysql_num_rows($sql_cat) != 0) {
	                      $catNum = $catNum +1;
                          while ($row_cat = mysql_fetch_row($sql_cat)) {
	                            $selected = "";
	                            if ($row_cat[0] == $pcat) { $selected = " SELECTED"; }
                                 echo "<option value=\"".$row_cat[0]."\"".$selected.">". $row[1] . "-->" . $row_cat[1] . "</option>\n";  
                          }      
                    }        
                 }    
           }
                
          
       mysql_free_result($sql);
       echo "</select><br />";
       //echo "group ". $groupNum .  " cat ". $catNum;
       
       if ($groupNum > 0 && $catNum > 0) {
	       
       		echo $txt['productadmin9']." <input type=\"text\" name=\"pid\" size=\"60\" maxlength=\"60\" value=\"".$pid."\"><br />";
       		echo $txt['productadmin10']."<br /><textarea name=\"text2edit\" rows=\"15\" cols=\"50\">".$pdescription."</textarea><br />";
       		echo $txt['productadmin11'];
       		if ($no_vat == 0 && $db_prices_including_vat == 0) { echo " (".$txt['general6']." ".$txt['general5'].")"; }
       		if ($no_vat == 0 && $db_prices_including_vat == 1) { echo " (".$txt['general7']." ".$txt['general5'].")"; }
            echo " <input type=\"text\" name=\"pprice\" size=\"10\" maxlength=\"10\" value=\"".$pprice."\"><br />";
            
       		if ($stock_enabled == 1) {
	       		echo $txt['productadmin12'];
       		}
       		else {
	       		echo $txt['productadmin13'];
       		}
       		echo " <input type=\"text\" name=\"pstock\" size=\"4\" maxlength=\"10\" value=\"".$pstock."\"><br />";
       		echo $txt['productadmin14']." <input type=\"checkbox\" name=\"pfrontpage\" "; if ($pfrontpage == 1) { echo "checked"; } echo "><br />";
       		echo $txt['productadmin15']." <input type=\"checkbox\" name=\"pnew\" "; if ($pnew == 1) { echo "checked"; } echo "><br />";
       		echo "<br /><div align=center>";
		       
       		if ($action == "add_product") {
          		echo "<input type=\"hidden\" name=\"action\" value=\"save_new_product\">";
          		echo "<input type=\"submit\" value=\"".$txt['productadmin16']."\">";
       		}
       		else {
          		echo "<input type=\"hidden\" name=\"prodid\" value=\"".$prodid."\">";
          		echo "<input type=\"hidden\" name=\"action\" value=\"update_product\">";
          		echo "<input type=\"submit\" value=\"".$txt['productadmin17']."\">";
       		}
       }
       else { 
	       if ($catNum ==0) { echo "</select><br /><br />".$txt['productadmin22']; } 
	   }

       echo "</div></form>"; 
    echo "</td></tr></table>";          
          }
    
?>