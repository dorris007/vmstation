
<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php include ("checklogin.php"); ?>
<?php
     // admin check
    if (IsAdmin() == false) {
	  PutWindow ($txt['general12'], $txt['general2'], "warning.gif", "50");
	  exit(include("includes/exit.inc.php"));
    }
     // ok, let's do the updating/deleting/moving here
     
      // upload a screenshot for the category
      if ($action == "upload_screenshot") {
          if (!empty($_POST['cid'])) {
	          $cid=$_POST['cid'];
          }
          
         $file = $_FILES['uploadedfile']['name'];
         $ext = explode(".", $file);
         $ext = strtolower(array_pop($ext));

         if ($ext == "jpg" || $ext == "gif" || $ext == "png") {         
            $target_path = $brands_dir."/";
            $target_path = $target_path.$cid;
            
             // delete old gif or jpg if it is found
             if (file_exists($target_path.".jpg")) { unlink($target_path.".jpg"); }
             if (file_exists($target_path.".gif")) { unlink($target_path.".gif"); }
             if (file_exists($target_path.".png")) { unlink($target_path.".png"); }
            
             $target_path = $target_path.".".$ext; 

             if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
	            chmod($target_path,0644); 
                PutWindow ($txt['general13'], basename( $_FILES['uploadedfile']['name']).$txt['groupadmin1'].$target_path, "notify.gif", "50");
             } 
             else{
                PutWindow ($txt['general12'], $txt['groupadmin2'], "warning.gif", "50");
             }   
         }
         else { PutWindow ($txt['general12'], $txt['groupadmin3'], "warning.gif", "50"); }
      }          
      
     // add a group to db
     if ($action == "add_group") {
	     if (!empty($_POST['gname'])) {
	          $gname=$_POST['gname'];
          }
          $query="INSERT INTO `group` (`NAME`) VALUES ('".$gname."')";
          $sql = mysql_query($query) or die(mysql_error());
          PutWindow ($txt['general13'], $txt['groupadmin4'], "notify.gif", "50");
     }
     
     // edit group in db 
     if ($action == "update_group") {
	     if (!empty($_POST['gid'])) {
	          $gid=$_POST['gid'];
          }
	     if (!empty($_POST['gname'])) {
	          $gname=$_POST['gname'];
          }
          if ($gname != "") {
	          $query="UPDATE `group` SET `NAME`='".$gname."' WHERE ID=".$gid;
	          $sql = mysql_query($query) or die(mysql_error());
	          PutWindow ($txt['general13'], $txt['groupadmin5'], "notify.gif", "50");
          }
      }      

     // delete group from db 
     if ($action == "delete_group") {
	     if (!empty($_POST['gid'])) {
	          $gid=$_POST['gid'];
          }
          // delete the group
          $query="DELETE FROM `group` WHERE ID=".$gid;
          $sql = mysql_query($query) or die(mysql_error());
          
          // delete all products found in the categories in that group
          $query="SELECT * FROM `category` WHERE GROUPID=".$gid;
          $sql = mysql_query($query) or die(mysql_error());

          while ($row = mysql_fetch_row($sql)) {
                 // delete all products in the categories of this group
                 $query_prod="DELETE FROM `product` WHERE CATID=".$row[0];
                 $sql_prod = mysql_query($query_prod) or die(mysql_error());
          }
          // delete the categories
          $query="DELETE FROM `category` WHERE GROUPID=".$gid;
          $sql = mysql_query($query) or die(mysql_error());
          PutWindow ($txt['general13'], $txt['groupadmin6'], "notify.gif", "50");
      }      

     // delete a categorie picture
     if ($action == "del_image") {
	   if (!empty($_GET['del_name'])) {
		   $del_name=$_GET['del_name'];
		   
		   if (file_exists($del_name)) { 
			   unlink($del_name); 
			   PutWindow ($txt['general13'] , $txt['productadmin25'], "notify.gif", "50");
		   }
       }
     }   
        
     // add a category to db
     if ($action == "add_category") {
	     if (!empty($_POST['gid'])) {
	          $gid=$_POST['gid'];
          }
	     if (!empty($_POST['cname'])) {
	          $cname=$_POST['cname'];
          }          $query="INSERT INTO `category` (`DESC`,`GROUPID`) VALUES ('".$cname."','".$gid."')";
          $sql = mysql_query($query) or die(mysql_error());
          $cid = mysql_insert_id(); // for the screenshot form
          PutWindow ($txt['general13'], $txt['groupadmin7'], "notify.gif", "50");
     }      

     // edit category in db 
     if ($action == "update_category") {
	     if (!empty($_POST['cid'])) {
	          $cid=$_POST['cid'];
          }
	     if (!empty($_POST['cname'])) {
	          $cname=$_POST['cname'];
          }
          if ($cname != "") { 
	          $query="UPDATE `category` SET `DESC`='".$cname."' WHERE ID=".$cid;
	          $sql = mysql_query($query) or die(mysql_error());
	          PutWindow ($txt['general13'], $txt['groupadmin8'], "notify.gif", "50");
          }
      }           
     
     // offer opertunity to upload a brand logo (screenshot form)
     if ($action == "add_category" || $action == "update_category") {
	        $thumb = "";
	        if (file_exists($brands_dir."/".$cid.".jpg")) { $thumb = $brands_dir."/".$cid.".jpg"; }
	        if (file_exists($brands_dir."/".$cid.".gif")) { $thumb = $brands_dir."/".$cid.".gif"; }
	        if (file_exists($brands_dir."/".$cid.".png")) { $thumb = $brands_dir."/".$cid.".png"; }
	        
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
		        echo "<a href=\"index.php?page=groupadmin&action=del_image&del_name=".$thumb."\">".$txt['productadmin24']."</a></h4>";
	        }	     
	     ?>
        <table width="80%" class="datatable">
          <caption><?php echo $txt['groupadmin29']; ?></caption>
          <tr><td>
              <form enctype="multipart/form-data" action="index.php?page=groupadmin" method="POST">
                <input type="hidden" name="action" value="upload_screenshot">
                <input type="hidden" name="cid" value="<?php echo $cid; ?>">
	            <input type="hidden" name="MAX_FILE_SIZE" value="500000">
	            <?php echo $txt['groupadmin27']; ?><input name="uploadedfile" type="file"><br />
	            <input type="submit" value="<?php echo $txt['groupadmin28']; ?>">
	          </form>
	      </td></tr>
	    </table>
	    <br />
	    <?php
     } 
     
     // move category to new group in db 
     if ($action == "move_category") {
	     if (!empty($_POST['cid'])) {
	          $cid=$_POST['cid'];
          }
	     if (!empty($_POST['gid'])) {
	          $gid=$_POST['gid'];
          }
          $query="UPDATE `category` SET `GROUPID`='".$gid."' WHERE ID=".$cid;
          $sql = mysql_query($query) or die(mysql_error());
          PutWindow ($txt['general13'], $txt['groupadmin9'], "notify.gif", "50");
      }           
      
     // delete category from db 
     if ($action == "delete_category") {
	     if (!empty($_POST['cid'])) {
	          $cid=$_POST['cid'];
          }
          // delete all products in this category
          $query_prod="DELETE FROM `product` WHERE CATID=".$cid;
          $sql_prod = mysql_query($query_prod) or die(mysql_error());
          
          // delete the category
          $query="DELETE FROM `category` WHERE ID=".$cid;
          $sql = mysql_query($query) or die(mysql_error());
          PutWindow ($txt['general13'], $txt['groupadmin10'], "notify.gif", "50");
      }      
      
      if ($action == "delete_empty") {
          // deletion counters
	      $num_cat = 0;
          $num_group = 0;
          
          // first track down the empty categories
	      $query="SELECT * FROM `category`";
          $sql = mysql_query($query) or die(mysql_error());
          while ($row = mysql_fetch_row($sql)) {	      
                $sub_query="SELECT * FROM `product` WHERE CATID=".$row[0];
                $sub_sql = mysql_query($sub_query) or die(mysql_error());	             
                if (mysql_num_rows($sub_sql) == 0) {
	                // no products found in this category, so let's remove it
                    $del_query="DELETE FROM `category` WHERE ID=".$row[0];
                    $del_sql = mysql_query($del_query) or die(mysql_error());
                    $num_cat = $num_cat +1;
                }
          }
          // now track down the empty groups
	      $query="SELECT * FROM `group`";
          $sql = mysql_query($query) or die(mysql_error());
          while ($row = mysql_fetch_row($sql)) {	      
                $sub_query="SELECT * FROM `category` WHERE GROUPID=".$row[0];
                $sub_sql = mysql_query($sub_query) or die(mysql_error());	             
                if (mysql_num_rows($sub_sql) == 0) {
	                // no categories found in this group, so let's remove it
                    $del_query="DELETE FROM `group` WHERE ID=".$row[0];
                    $del_sql = mysql_query($del_query) or die(mysql_error());
                    $num_group = $num_group +1;
                }
          }
          PutWindow ($txt['general13'], $txt['groupadmin32'].": ".$num_cat."<br />".$txt['groupadmin33'].": ".$num_group, "notify.gif", "50");
      }
      
    // SHOW ALL FORMS ---------------------------------->
	     
	     // general options
	     echo "<h6>".$txt['groupadmin30']."</h6><br />";
	     echo "<ul>";
         echo "<li><a href=\"?page=groupadmin&action=delete_empty\">".$txt['groupadmin31']."</a></li>";	     
         echo "</ul><br /><br />";

	     //大类选项
	     echo "<h6>".$txt['groupadmin18']."</h6><br />";
	     
	     // 添加大类
         echo "<table width=\"90%\" class=\"datatable\">";
         echo "  <caption>".$txt['groupadmin12']."</caption>";
         
         echo "<tr><td>";
         echo "    <form method=\"POST\" action=\"index.php?page=groupadmin&action=add_group\">";
	     echo "     ".$txt['groupadmin11']." <input type=\"text\" name=\"gname\" size=\"15\" maxlength=\"30\" value=\"\"><br />";
	     echo "     <input type=\"submit\" value=\"".$txt['groupadmin12']."\">";
	     echo "     </form>";
	     echo "</td></tr></table>";
	     
	     echo "<br />";
	     
	     //编辑大类
         echo "<table width=\"90%\" class=\"datatable\">";
         echo "  <caption>".$txt['groupadmin16']."</caption>";
         
         echo "<tr><td>";
         
         //读取大类信息，然后绑定到下拉列表框中
         $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
         $sql = mysql_query($query) or die(mysql_error());

         if (mysql_num_rows($sql) == 0) {
            echo $txt['groupadmin13'];
         }
         else {
             
               echo "<form method=\"POST\" action=\"index.php?page=groupadmin&action=update_group\">";
               echo $txt['groupadmin14']." <select name=\"gid\">";
               
               // all groups in pulldown
               while ($row = mysql_fetch_row($sql)) {
                      echo "<option value=\"".$row[0]."\">".$row[1]."</option>\n";
               }
	           
               echo "</select><br />";
               echo $txt['groupadmin15']." <input type=\"text\" name=\"gname\" size=\"15\" maxlength=\"30\" value=\"\"><br />";
	           echo "<input type=\"submit\" value=\"".$txt['groupadmin16']."\">";
	           echo "</form>";
         }
         echo "</td></tr></table>";	     
         echo "<br />";
         
	     // 删除大类
         echo "<table width=\"90%\" class=\"datatable\">";
         echo "  <caption>".$txt['groupadmin17']."</caption>";

         echo "<tr><td>";
         
         // read all groups
         $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
         $sql = mysql_query($query) or die(mysql_error());

         if (mysql_num_rows($sql) == 0) {
            echo $txt['groupadmin13'];
         }
         else {
             
               echo "    <form method=\"POST\" action=\"index.php?page=groupadmin&action=delete_group\">";
               echo "   ".$txt['groupadmin14']." <select name=\"gid\">";
               
               // all groups in pulldown
               while ($row = mysql_fetch_row($sql)) {
                      echo "<option value=\"".$row[0]."\">".$row[1]."</option>\n";
               }
	           
               echo "     </select><br />";
	           echo "     <input type=\"submit\" value=\"".$txt['groupadmin17']."\">";
	           echo "     </form>";
	     
         }         
         echo "</td></tr></table>";	     
         echo "<br />";
	     
         // 小类选项
         echo "<br />";
         echo "<h6>".$txt['groupadmin19']."</h6><br />";
	     
	     // 增加小类
         echo "<table width=\"90%\" class=\"datatable\">";
         echo "  <caption>".$txt['groupadmin21']."</caption>";
         
         echo "<tr><td>";
         
         //读取大类
         $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
         $sql = mysql_query($query) or die(mysql_error());

         if (mysql_num_rows($sql) == 0) {
            echo $txt['groupadmin13'];
         }
         else {
             
               echo "    <form method=\"POST\" action=\"index.php?page=groupadmin&action=add_category\">";
               echo "    ".$txt['groupadmin14']." <select name=\"gid\">";
               
               // all groups in pulldown
               while ($row = mysql_fetch_row($sql)) {
                      echo "<option value=\"".$row[0]."\">".$row[1]."</option>\n";
               }
	           
               echo "     </select><br />";
               echo "     ".$txt['groupadmin20']." <input type=\"text\" name=\"cname\" size=\"15\" maxlength=\"40\" value=\"\"><br />";
	           echo "     <input type=\"submit\" value=\"".$txt['groupadmin21']."\">";
	           echo "     </form>";
         }
         echo "</td></tr></table>";	     
         echo "<br />";
         
	     // 编辑小类
         echo "<table width=\"90%\" class=\"datatable\">";
         echo "  <caption>".$txt['groupadmin24']."</caption>";
         
         echo "<tr><td>";
         
         // read all groups
         $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
         $sql = mysql_query($query) or die(mysql_error());

         if (mysql_num_rows($sql) == 0) {
            echo $txt['groupadmin13'];
         }
         else {
               echo "<form method=\"POST\" action=\"index.php?page=groupadmin&action=update_category\">";
               echo " ".$txt['groupadmin22']." <select name=\"cid\">";
               while ($row = mysql_fetch_row($sql)) {
                      $query_cat = "SELECT * FROM `category` WHERE `GROUPID` = " . $row[0] . " ORDER BY `DESC` ASC";
	                  $sql_cat = mysql_query($query_cat) or die(mysql_error());

                      while ($row_cat = mysql_fetch_row($sql_cat)) {             
               
                      // all categories and their groups in pulldown
                      echo "<option value=\"".$row_cat[0]."\">". $row[1] . "-->" . $row_cat[1] . "</option>\n";
                      }
              }
               echo "     </select><br />";
               echo "     ".$txt['groupadmin23']." <input type=\"text\" name=\"cname\" size=\"15\" maxlength=\"40\" value=\"\"><br />";
	           echo "     <input type=\"submit\" value=\"".$txt['groupadmin24']."\">";
	           echo "     </form>";
         } 
         echo "</td></tr></table>";	     
         echo "<br />";
         
	     //移动小类
         echo "<table width=\"90%\" class=\"datatable\">";
         echo "  <caption>".$txt['groupadmin25']."</caption>";

         echo "<tr><td>";
         
         // read all groups
         $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
         $sql = mysql_query($query) or die(mysql_error());

         if (mysql_num_rows($sql) == 0) {
            echo $txt['groupadmin13'];
         }
         else {
               echo "<form method=\"POST\" action=\"index.php?page=groupadmin&action=move_category\">";
               echo " ".$txt['groupadmin22']." <select name=\"cid\">";
               while ($row = mysql_fetch_row($sql)) {
                      $query_cat = "SELECT * FROM `category` WHERE `GROUPID` = " . $row[0] . " ORDER BY `DESC` ASC";
	                  $sql_cat = mysql_query($query_cat) or die(mysql_error());

                      while ($row_cat = mysql_fetch_row($sql_cat)) {             
               
                      // all categories and their groups in pulldown
                      echo "<option value=\"".$row_cat[0]."\">". $row[1] . "-->" . $row_cat[1] . "</option>\n";
                      }
              }
               echo "     </select><br />";
               // read all groups
               $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
               $sql = mysql_query($query) or die(mysql_error());

               if (mysql_num_rows($sql) == 0) {
                   echo $txt['groupadmin13'];
               }
               else {
                     echo "    ".$txt['groupadmin14']." <select name=\"gid\">";
               
                     // all groups in pulldown
                     while ($row = mysql_fetch_row($sql)) {
                            echo "<option value=\"".$row[0]."\">".$row[1]."</option>\n";
                     }
                     echo "     </select><br />";
	                 echo "     <input type=\"submit\" value=\"".$txt['groupadmin25']."\">";
               }
	           echo "     </form>";
         }
         echo "</td></tr></table>";	     
         echo "<br />";

	     //删除小类
         echo "<table width=\"90%\" class=\"datatable\">";
         echo "  <caption>".$txt['groupadmin26']."</caption>";
         
         echo "<tr><td>";
         
         // read all groups
         $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
         $sql = mysql_query($query) or die(mysql_error());

         if (mysql_num_rows($sql) == 0) {
            echo $txt['groupadmin13'];
         }
         else {
               echo "<form method=\"POST\" action=\"index.php?page=groupadmin&action=delete_category\">";
               echo " ".$txt['groupadmin22']." <select name=\"cid\">";
               while ($row = mysql_fetch_row($sql)) {
                      $query_cat = "SELECT * FROM `category` WHERE `GROUPID` = " . $row[0] . " ORDER BY `DESC` ASC";
	                  $sql_cat = mysql_query($query_cat) or die(mysql_error());

                      while ($row_cat = mysql_fetch_row($sql_cat)) {             
               
                      // all categories and their groups in pulldown
                      echo "<option value=\"".$row_cat[0]."\">". $row[1] . "-->" . $row_cat[1] . "</option>\n";
                      }
              }
               echo "     </select><br />";
	           echo "     <input type=\"submit\" value=\"".$txt['groupadmin26']."\">";
	           echo "     </form>";
         }                                  
         echo "</td></tr></table>";
?>