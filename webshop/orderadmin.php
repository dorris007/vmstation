
<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php include ("checklogin.php"); ?>
<?php
    // admin check
    if (IsAdmin() == false) {
	  PutWindow ($txt['general12'], $txt['general2'], "warning.gif", "50");
	  exit(include("includes/exit.inc.php"));
    }
    
    if (!empty($_POST['status'])) {
	    $status=$_POST['status'];
    }
    if (!empty($_POST['newstatus'])) {
	    $newstatus=$_POST['newstatus'];
    }
    if (!empty($_POST['notify'])) {
	    $notify=$_POST['notify'];
    }
    if (!empty($_POST['orderid'])) {
	    $orderid=$_POST['orderid'];
    }
      
    //改变状态时，读取状态
    if ($action == "showstatus") {
       if (!empty($_GET['orderid'])) {
	      $orderid=$_GET['orderid'];
       }
       if (!empty($_GET['oldstatus'])) {
	      $oldstatus=$_GET['oldstatus'];
       }
       // read old status text
       $query = "SELECT * FROM `order` WHERE `ID` = ".$orderid;
       $sql = mysql_query($query) or die(mysql_error());
       while ($row = mysql_fetch_row($sql)) {
               $status_id = $row[0]; // status of this order
		       		   
			   echo "<table width=\"70%\" class=\"datatable\">";
		       echo "<caption>".$txt['orderadmin14']." ".$row[7]."</caption>";
		       echo "<tr><td>";
		       
		       // determin the status and show a colored picture accordingly
		       if ($row[2] == "1") { $status_color = "blue"; $status_text = $txt['db_status1']; }
		       if ($row[2] == "2") { $status_color = "red"; $status_text = $txt['db_status2']; }
		       if ($row[2] == "3") { $status_color = "red"; $status_text = $txt['db_status3']; }
		       if ($row[2] == "4") { $status_color = "orange"; $status_text = $txt['db_status4']; }
		       if ($row[2] == "5") { $status_color = "green"; $status_text = $txt['db_status5']; }
		       if ($row[2] == "6") { $status_color = "green"; $status_text = $txt['db_status6']; }
		       if ($row[2] == "7") { $status_color = "green"; $status_text = $txt['db_status7']; }
		       echo "<img src=\"".$gfx_dir."/bullit_".$status_color.".gif\" alt=\"".$status_text."\" />&nbsp;".$status_text."<br />";
			   echo "<br />"; 
		       echo "<form method=\"post\" action=\"index.php?page=orderadmin&action=changestatus\">";
		       echo "<input type=\"hidden\" name=\"orderid\" value=\"" . $orderid . "\">";
			   echo "<SELECT NAME=\"newstatus\">";
		       echo "    <OPTION SELECTED VALUE=\"\">";
		       echo "    <OPTION VALUE=\"2\">" . $txt['db_status2'];
		       echo "    <OPTION VALUE=\"3\">" . $txt['db_status3'];
		       echo "    <OPTION VALUE=\"4\">" . $txt['db_status4'];
		       echo "    <OPTION VALUE=\"5\">" . $txt['db_status5'];
		       echo "    <OPTION VALUE=\"6\">" . $txt['db_status6'];
		       echo "    <OPTION VALUE=\"7\">" . $txt['db_status7'];
		       echo "    <OPTION VALUE=\"delete\">Delete";
		       echo "</SELECT><br />";
		       echo "<input type=\"checkbox\" name=\"notify\" value=\"yes\" checked>".$txt['orderadmin7']."<br />";
		       echo "<h4><input type=\"submit\" value=\"".$txt['orderadmin8']."\"></h4>";
		       echo "</form></td></tr></table>";
		       exit(include("includes/exit.inc.php"));
	   }
    }
    
    //改变订单状态
    if ($action == "changestatus" && $newstatus != "") {
	    // you shouldnt remove orders unless they are test orders
	    if ($newstatus == "delete") {
	       // first get the customerid from the order
	       $query = "SELECT * FROM `order` WHERE `ID` = ".$orderid;
           $sql = mysql_query($query) or die(mysql_error());
               
           while ($row = mysql_fetch_row($sql)) {
           $webid = $row[7]; //webid of this order, so we can derive the filename from it
                    }		   
           $query = "DELETE FROM `order` WHERE ID = " . $orderid; // delete the record
           $sql = mysql_query($query) or die(mysql_error());
           unlink($orders_dir."/".strval($webid)); // delete the file
           PutWindow ($txt['general13'], $txt['orderadmin3'], "notify.gif", "50");
        }
	    else {
           $query = "UPDATE `order` SET `STATUS` = '" . $newstatus . "' WHERE `ID` = " . $orderid;
           $sql = mysql_query($query) or die(mysql_error());
           $message = $txt['orderadmin15'];
           // send notification to customer??
           
               if ($notify == "yes") {

	               // first get the customerid from the order
	               $query = "SELECT `CUSTOMERID`, `WEBID` FROM `order` WHERE `ID` = '".$orderid."'";
                   $sql = mysql_query($query) or die(mysql_error());
               
                    while ($row = mysql_fetch_row($sql)) {
                          $custid = $row[0]; //customer id of current order
                          $webid = $row[1]; //web id of current order
                    }
	               $query = "SELECT `EMAIL` FROM `customer` WHERE `ID` = '".$custid."'";
                   $sql = mysql_query($query) or die(mysql_error());
               
                    while ($row = mysql_fetch_row($sql)) {
                          $to = $row[0]; //email address of that customer
                    }
                    
                    // prepare the email and send it
                    $subject = $txt['orderadmin1'] . $webid. $txt['orderadmin2'];
                    $body = $txt['orderadmin1'] . $webid. $txt['orderadmin2'].$txt['orderadmin4'].$custid. $txt['orderadmin5'];
				    $email_header = EmailHeader($sales_mail, $charset);
		            mymail($webmaster_mail, $to, $subject, $body, $email_header, $smtp_server, $smtp_port, $smtp_user, $smtp_pass, $charset);
                    $message = $message."<br />".$txt['orderadmin6']." ".$to;
               }
               PutWindow ($txt['general13'], $message, "notify.gif", "50");
           }
    }

        //通过状态查询订单
    if (!$status == NULL) {
	    // if there are no search criterea, then show all
	    if ($status == "%") {  $where = ""; }
	    else { $where = "WHERE STATUS = '" . $status . "'"; }

        $query = "SELECT * FROM `order` " . $where . " ORDER BY ID DESC";
        }
    else {
        $query = "SELECT * FROM `order` ORDER BY ID DESC";
    }
    $sql = mysql_query($query) or die(mysql_error());
    ?>

    <FORM METHOD="post" ACTION="index.php?page=orderadmin">
     <SELECT NAME="status">
           <OPTION VALUE="%"><?php echo $txt['orderadmin9']; ?>
           <OPTION VALUE="1"><?php echo $txt['db_status1'] ?>
           <OPTION VALUE="2"><?php echo $txt['db_status2'] ?>
           <OPTION VALUE="3"><?php echo $txt['db_status3'] ?>
           <OPTION VALUE="4"><?php echo $txt['db_status4'] ?>
           <OPTION VALUE="5"><?php echo $txt['db_status5'] ?>
           <OPTION VALUE="6"><?php echo $txt['db_status6'] ?>
           <OPTION VALUE="7"><?php echo $txt['db_status7'] ?>
     </SELECT>
     <INPUT TYPE="submit" VALUE="<?php echo $txt['orderadmin11']; ?>">
    </FORM>

    <table width="100%" class="datatable">
      <caption><?php echo $txt['orderadmin13']; ?></caption>
     <tr> 
      <th><?php echo $txt['orders4']; ?></th>
      <th><?php echo $txt['orders5']; ?></th>
      <th><?php echo $txt['orders6']." (".$currency.")"; ?></th>
      <th><?php echo $txt['orders7']; ?></th>
      <th><?php echo $txt['orders8']; ?></th>
     </tr>

    <?php
    $color = $tb_pricelist_color1;
    if (mysql_num_rows($sql) == 0) {
        echo "<tr><td colspan=\"5\">" . $txt['orderadmin10'] ."</td></tr>";
    }
    else {
	    
     while ($row = mysql_fetch_row($sql)) {
	    
	   // lets first check if the order still has a local file in the Orders folder
	   if (file_exists($orders_dir ."/". $row[7])) {
		   $sub_query = "SELECT * FROM customer WHERE ID = " . $row[5];
           $sub_sql = mysql_query($sub_query) or die(mysql_error());

           while ($sub_row = mysql_fetch_row($sub_sql)) {
	           echo "<tr><td>";
	           echo "<a href=\"index.php?page=customer&action=show&customerid=" . $row[5] . "\" target=\"_top\">".$sub_row[5]." ".$sub_row[4]." ".$sub_row[3]."</a><br />";
	           echo "<a href=\"?page=readorder&orderid=" . $row[0] . "\">" . $row[7] . "</a>";
	           
	           // if customer added notes to the order, then lets bring this to the admins attention by adding a note icon
	           if ($row[8] != "" && !is_null($row[8])) { 
		           $note = str_replace ("<br />", " \\n ", nl2br($row[8])); 
		           $note = str_replace ("<br>", " \\n ", nl2br($row[8])); 
		           echo "<br /><a href=\"javascript:alert('".stripslashes($txt['shipping3']).": \\n".br2nl(stripslashes($note))."')\"><img src=\"".$gfx_dir."/admin_notes.gif\" alt=\"".$txt['orderadmin12']."\"></a>"; 
		       }
	           echo "</td><td>";
	           // find out shipping method
			   $ship_query = "SELECT * FROM `shipping` WHERE `id` = ".$row[3];
	           $ship_sql = mysql_query($ship_query) or die(mysql_error());
               while ($ship_row = mysql_fetch_row($ship_sql)) { echo $ship_row[1]; }
               echo "<br />";
	           // find out shipping method
			   $pay_query = "SELECT * FROM `payment` WHERE `id` = ".$row[4];
	           $pay_sql = mysql_query($pay_query) or die(mysql_error());
               while ($pay_row = mysql_fetch_row($pay_sql)) { echo $pay_row[1]; }
	           echo "</td>";
	           echo "<td><div style=\"text-align:right;\">".myNumberFormat($row[6], $number_format)."</div></td>";
	           echo "<td>".$row[1]."</td>";
               echo "<td>";
               // determin the status and show a colored picture accordingly
	           if ($row[2] == "1") { $status_color = "blue"; $status_text = $txt['db_status1']; }
	           if ($row[2] == "2") { $status_color = "red"; $status_text = $txt['db_status2']; }
	           if ($row[2] == "3") { $status_color = "red"; $status_text = $txt['db_status3']; }
	           if ($row[2] == "4") { $status_color = "orange"; $status_text = $txt['db_status4']; }
	           if ($row[2] == "5") { $status_color = "green"; $status_text = $txt['db_status5']; }
	           if ($row[2] == "6") { $status_color = "green"; $status_text = $txt['db_status6']; }
	           if ($row[2] == "7") { $status_color = "green"; $status_text = $txt['db_status7']; }
	           echo "<img src=\"".$gfx_dir."/bullit_".$status_color.".gif\" alt=\"".$status_text."\" />&nbsp;".$status_text."<br />";
	           echo "<a href=\"index.php?page=orderadmin&action=showstatus&orderid=".$row[0]."&oldstatus=".$row[2]."\">".$txt['orderadmin14']."</a>";
               echo "</td></tr>";

           }
       }    
     }
    } 
?>
    </table>