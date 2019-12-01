<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php
        function InStr($String,$Find,$CaseSensitive = false) {
    	         $i=0;
    	         while (strlen($String)>=$i) {
    		           unset($substring);
    		           if ($CaseSensitive) {
    			          $Find=strtolower($Find);
    			          $String=strtolower($String);
    		           }
    		     $substring=substr($String,$i,strlen($Find));
    		     if ($substring==$Find) return true;
    		     $i++;
    	         }
    	return false;
        }


         $eror = 0;
         // read post values if needed
         if (!empty($_POST['login'])) {
   	         $login=$_POST['login'];
         }
         else { $error =1 ; }
         if (!empty($_POST['pass1'])) {
	         $pass1=$_POST['pass1'];
         }
         else { $error =1 ; }
         if (!empty($_POST['pass2'])) {
   	         $pass2=$_POST['pass2'];
         }
         else { $error =1 ; }
         if (!empty($_POST['name'])) {
	         $name=$_POST['name'];
         }
         else { $error =1 ; }
         if (!empty($_POST['voorletters'])) {
	         $voorletters=$_POST['voorletters'];
         }
         else { $error =1 ; }
         if (!empty($_POST['adres'])) {
   	         $adres=$_POST['adres'];
         }
         else { $error =1 ; }
         if (!empty($_POST['postcode'])) {
	         $postcode=$_POST['postcode'];
         }
         else { $error =1 ; }
         if (!empty($_POST['plaats'])) {
   	         $plaats=$_POST['plaats'];
         }
         else { $error =1 ; }
         if (!empty($_POST['email'])) {
   	         $email=$_POST['email'];
         }
         else { $error =1 ; }
         if (!empty($_POST['land'])) {
   	         $land=$_POST['land'];
         }
         else { $error =1 ; }
         
         // optional, so don't raise error if null
         if (!empty($_POST['company'])) {
   	         $company=$_POST['company'];
         }
         if (!empty($_POST['telefoon'])) {
	         $telefoon=$_POST['telefoon'];
         }
         if (!empty($_POST['tussenvoegsels'])) {
   	         $tussenvoegsels=$_POST['tussenvoegsels'];
         }

         // customer id from cookie
         $cookie_info = explode("-", $_COOKIE['cookie_info']);
         $customerid = $cookie_info[1];
         if (!isset($customerid)) { $customerid=0; }

         // is it an admin?
         if (IsAdmin() == true) {
	        // customerid can be send from different forms in different ways so we need to check POST and GET
            if (!empty($_POST['customerid'])) {
   	           $customerid=intval($_POST['customerid']);
   	        }
            if (!empty($_GET['customerid'])) {
   	           $customerid=intval($_GET['customerid']);
   	        }
         }

      if ($action=="delete" && IsAdmin() == true) {
	      // are you removing a customer or accidently an admin?
          $query = "SELECT * FROM `customer` WHERE ID = " . $customerid ;
          $sql = mysql_query($query) or die(mysql_error());          
          while ($row = mysql_fetch_row($sql)) {
	             if ($row[12] != "ADMIN") {
                     $del_query="DELETE FROM `customer` WHERE ID = " . $customerid ;
                     $del_sql = mysql_query($del_query) or die(mysql_error());
                     PutWindow ($txt['general13'] , $txt['customer2'], "dustbin.gif", "50");
                 }
                 else { 
	                   PutWindow ($txt['general12'] , $txt['customer32'], "warning.gif", "50"); 
	             }
         }
         echo "<h4><a href=\"javascript:history.go(-1)\">" . $txt['customer3'] . "</a></h4>";
         exit(include("includes/exit.inc.php"));
      }
      
      if ($action=="save") {
	      // are all values send?
	      if ($error == 1) {
		      PutWindow ($txt['general12'], $txt['customer6'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
              exit(include("includes/exit.inc.php"));
	      }

	      // are there no empty spaces in the username?
	      if (instr($login, " ", false) == TRUE && $error == 0) {
		      PutWindow ($txt['general12'], $txt['customer7'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
		      $error =1;
	      }

	      // did the customer type the password twice the same?
	      if ($pass1<>$pass2 && $error == 0) {
		      PutWindow ($txt['general12'], $txt['customer8'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
		      $error =1;
	      }
          // ok, is it long enough (is it ever?)
	      if (strlen($pass1)< 5 && $error == 0) {
		      PutWindow ($txt['general12'], $txt['customer9'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
		      $error =1;
	      }

	      // is the email address somewhat ok? does it have a @ and a . in it?
	      if (instr($email, "@", false) == FALSE || instr($email, ".", false) == FALSE && $error == 0) {
		      PutWindow ($txt['general12'], $txt['customer10'] . "<br /><br />" . $txt['customer5'], "warning.gif", "50");
		      $error =1;
	      }

          if ($error == 0) {
		      if (!isset($_COOKIE['cookie_info'])) {
			      // new customer
	
		                  // if you would want to check for reserved usernames, THIS would be the place!!!
		                  // here you could check for loginnames like: ADMIN, WEBMASTER and bad language.
		                  // [.. code not here yet ..]
	 
		                  
			      	      // check if the loginname is unique
		                  $query = sprintf("SELECT * FROM `customer` WHERE `LOGINNAME` = %s", quote_smart($login));
	                      $sql = mysql_query($query) or die(mysql_error());
		                  if (!mysql_num_rows($sql) == 0) {
			                 PutWindow ($txt['general12'], $txt['customer29'], "warning.gif", "50");
  		                     $error =1;
		                  }
			      	      // check if the email address is unique
		                  $query = sprintf("SELECT * FROM `customer` WHERE `EMAIL` = %s", quote_smart($email));
	                      $sql = mysql_query($query) or die(mysql_error());
		                  if (!mysql_num_rows($sql) == 0) {
			                 PutWindow ($txt['general12'], $txt['customer34'], "warning.gif", "50");
		                     $error =1;
		                  }
	              if ($error == 0) {
			          include ($lang_file);
		              $query = sprintf("INSERT INTO `customer` ( `LOGINNAME`, `PASSWORD`, `LASTNAME`, `MIDDLENAME`, `INITIALS`, `IP`, `ADDRESS`, `ZIP`, `CITY`, `PHONE`, `EMAIL`, `GROUP`, `COUNTRY`,`COMPANY`,`JOINDATE`) VALUES (%s, %s, %s, %s, %s, '', %s, %s, %s, %s, %s, 'CUSTOMER', %s, %s, '".Date($date_format)."')", quote_smart($login), quote_smart($pass1), quote_smart($name), quote_smart($tussenvoegsels), quote_smart($voorletters), quote_smart($adres), quote_smart($postcode), quote_smart($plaats), quote_smart($telefoon), quote_smart($email), quote_smart($land), quote_smart($company));
		              mymail($webmaster_mail, $webmaster_mail, "New ".$shopname." user", $name." has registered itself to ".$shopname, $email_header, $smtp_server, $smtp_port, $smtp_user, $smtp_pass, $charset);
		              mymail($webmaster_mail, $email, $txt['customer11'], $txt['customer12'], $email_header, $smtp_server, $smtp_port, $smtp_user, $smtp_pass, $charset);
	                  $sql = mysql_query($query) or die(mysql_error());
	                  PutWindow($txt['general13'], $txt['customer13'], "notify.gif", "50"); // succesfully saved
			          echo "<h4><a href=\"index.php?page=orders\">".$txt['customer35']."</a></h4>";  // click here to login
			          exit(include("includes/exit.inc.php"));
	              }
	          }
	          else {
	              // update existing customer
		          $query = sprintf("UPDATE `customer` SET `LOGINNAME` =%s, `PASSWORD` = %s, `LASTNAME` = %s, `MIDDLENAME` = %s, `INITIALS` = %s, `IP` = '', `ADDRESS` = %s, `ZIP` = %s, `CITY` = %s, `PHONE` = %s, `EMAIL` = %s, `COUNTRY` = %s, `COMPANY` = %s WHERE ID = %s", quote_smart($login), quote_smart($pass1), quote_smart($name), quote_smart($tussenvoegsels), quote_smart($voorletters), quote_smart($adres), quote_smart($postcode), quote_smart($plaats), quote_smart($telefoon), quote_smart($email), quote_smart($land), quote_smart($company), quote_smart($customerid));
	              $sql = mysql_query($query) or die(mysql_error());
	              PutWindow($txt['general13'], $txt['customer13'], "notify.gif", "50"); // succesfully saved
	          }
          }
      }

      if ($action == show) {
          $query = sprintf("SELECT * FROM `customer` WHERE `ID` = %s", quote_smart($customerid));
          $sql = mysql_query($query) or die(mysql_error());
          while ($row = mysql_fetch_row($sql)) {
                 $login = $row[1];
                 $pass1 = $row[2];
                 $pass2 = $row[2];
                 $name = $row[3];
                 $tussenvoegsels = $row[4];
                 $voorletters = $row[5];
                 $adres = $row[7];
                 $postcode = $row[8];
                 $plaats = $row[9];
                 $telefoon = $row[10];
                 $email = $row[11];
                 $land = $row[13];
                 $company = $row[14];
                 }
      }
         ?>
		    <table width="80%" class="datatable">
		      <caption><?php echo $txt['customer14']; ?></caption>
		       <tr><td>
                 <table width="100%" class="borderless">

                  <form method="POST" action="index.php?page=customer&action=save">
	              <tr><td><?php echo $txt['customer15'] ?> </td>
	              <?php
	                    if ($action == show) { echo "<td>" . $login . "<input type=hidden name=login value='" . $login . "'></td>"; }
	                    else {
		                    ?>
		                    <td><input type="text" name="login" size="15" maxlength="15" value="<?php echo $login ?>"></td>
		          <?php } ?>
     	          </tr>
	              <tr><td><?php echo $txt['customer16'] ?> </td>
	                  <td><input type="password" name="pass1" size="10" maxlength="10" value="<?php echo $pass1 ?>"> <?php echo $txt['customer33']; ?></td>
        	      </tr>
	              <tr><td><?php echo $txt['customer17'] ?> </td>
	                  <td><input type="password" name="pass2" size="10" maxlength="10" value="<?php echo $pass2 ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer18'] ?></td>
	                  <td><input type="text" name="name" size="30" maxlength="30" value="<?php echo $name ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer19'] ?></td>
	                  <td><input type="text" name="tussenvoegsels" size="10" maxlength="10" value="<?php echo $tussenvoegsels ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer20'] ?></td>
	                  <td><input type="text" name="voorletters" size="10" maxlength="10" value="<?php echo $voorletters ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer30'] ?></td>
	                  <td><input type="text" name="company" size="30" maxlength="70" value="<?php echo $company ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer21'] ?></td>
	                  <td><input type="text" name="adres" size="30" maxlength="75" value="<?php echo $adres ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer22'] ?> </td>
	                  <td><input type="text" name="postcode" size="15" maxlength="15" value="<?php echo $postcode ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer23'] ?> </td>
	                  <td><input type="text" name="plaats" size="30" maxlength="50" value="<?php echo $plaats ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer24'] ?> </td>
	                  <td>
                         <SELECT NAME="land">
                          <OPTION VALUE="<?php echo $land ?>" SELECTED><?php echo $land ?>
                            <?php 
                             // read countries
                             $file = file('countries.txt');
                             @array_walk($file, 'file_trim');
                             while (list($key, $val) = each($file)) {
                                     echo "<OPTION VALUE=\"".$val."\">".$val;
                             }
                          ?>
                         </SELECT>
                        </td>
                  </tr>
	              <tr><td><?php echo $txt['customer25'] ?></td>
	                  <td><input type="text" name="telefoon" size="20" maxlength="20" value="<?php echo $telefoon ?>"></td>
	              </tr>
	              <tr><td><?php echo $txt['customer26'] ?></td>
	                  <td><input type="text" name="email" size="30" maxlength="50" value="<?php echo $email ?>"></td>
	              </tr>
                  <tr><td class="borderless" colspan=2><div style="text-align:center;"><br /><input type="submit" value="<?php echo $txt['customer28'] ?>" name="sub"><br />
                      (<?php echo $txt['customer27'] ?>)</div></td>
	              </tr>
	              <input type="hidden" name="customerid" value="<?php echo $customerid ?>">
                 </form>
               </td></td>
              </table>   
            </table>