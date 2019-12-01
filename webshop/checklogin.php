<?php
/*  checklogin.php
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
    $lostlogin = 0;
	if (!empty($_GET['lostlogin'])) {
	    $lostlogin=$_GET['lostlogin'];
	}
	$email = "";
	if (!empty($_POST['email'])) {
	    $email=$_POST['email'];
	}
	if ($email == "") { $email = "--"; }
    
//Check if cookie is set
if (!isset($_COOKIE['cookie_info']))

{
    if ($lostlogin == 0) {
	$pagetoload = $_SERVER['QUERY_STRING'];
?>
  <table width="60%" class="datatable">
    <caption><?php echo $txt['checklogin1'] ?></caption>
    <tr><td>
        <form method="POST" action="login.php">
              <input type="hidden" value="<?php echo $pagetoload; ?>" name="pagetoload">
	          <table class="borderless" width="100%">
	                 <tr><td class="borderless"><?php echo $txt['checklogin2'] ?></td>
	                     <td class="borderless"><input type="text" name="name" size="20"></td>
	                 </tr>
	                 <tr><td class="borderless"><?php echo $txt['checklogin3'] ?></td>
	                     <td class="borderless"><input type="password" name="pass" size="20"></td>
	                 </tr>
	          </table>
	          <br />
	          <div style="text-align:center;"><input type="submit" value="<?php echo $txt['checklogin4'] ?>" name="sub"></div>
	          <br />
	          <div style="text-align:right;"><a href="?page=checklogin&lostlogin=1"><?php echo $txt['checklogin11'] ?></a></div>
  	    </form>
  	    </td>
  	</tr>
  </table>
  <br />
  <div style="text-align:center;"><a href="index.php?page=customer&action=new"><?php echo $txt['checklogin5'] ?></a></div>
  <br />
  <br />
  <br />
  <?php
       PutWindow($txt['checklogin6'], $txt['checklogin7'], "personal.jpg", "90");
   }
   if ($lostlogin == 1) {
	   ?>
  <table width="60%" class="datatable">
    <caption><?php echo $txt['checklogin8'] ?></caption>
    <tr><td>
        <?php echo $txt['checklogin9'] ?><br /><br />
        <form method="POST" action="?page=checklogin&lostlogin=2">
	     <div style="text-align:center;">
	       <input type="text" name="email" size="30">
           <input type="submit" value="<?php echo $txt['checklogin10'] ?>" name="sub">
	     </div>
  	    </form>
  	</tr>
   </table>
   <?php
   }
   if ($lostlogin == 2) {
	   // lets find the correct data in the database
      $query = sprintf("SELECT * FROM `customer` WHERE `EMAIL` = %s", quote_smart($email));
      $sql = mysql_query($query) or die(mysql_error());
      if (mysql_num_rows($sql) == 0) {
         PutWindow ($txt['general12'], $txt['checklogin15'], "warning.gif", "50");
         echo "<h4><a href=\"javascript:history.go(-1)\">" . $txt['checklogin18'] . "</a></h4>";
         exit(include("includes/exit.inc.php"));
      }
      while ($row = mysql_fetch_row($sql)) {
		      $login = $row[1];
		      $pass = $row[2];
	  }
      mymail($webmaster_mail, $email, $txt['checklogin13'], $txt['checklogin14']."\n\n".$txt['checklogin2'].": ".$login."\n".$txt['checklogin3'].": ".$pass, $email_header, $smtp_server, $smtp_port, $smtp_user, $smtp_pass, $charset);
      PutWindow ($txt['checklogin13'], $txt['checklogin12']. " " . $email, "notify.gif", "50");
   }
	exit(include("includes/exit.inc.php"));
 }
?>