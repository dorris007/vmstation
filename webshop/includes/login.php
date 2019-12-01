<?php
/*  login.php
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
    if (!empty($_POST['pagetoload'])) {
        $pagetoload=$_POST['pagetoload'];
    }
    else { $pagetoload="my"; }
	// colllect the details and validate
	$time = time();

    // db values and language setting
	include ("includes/settings.inc.php");
	include ("includes/subs.inc.php");
	include ("includes/connectdb.inc.php");
	include ("includes/readsettings.inc.php");
	include ("includes/initlang.inc.php");
    include ($lang_file);
    
    
    if ($name == NULL) {
     ?>
     <html><head><link href="freewebshop.css" rel="stylesheet" type="text/css" />
     <META HTTP-EQUIV="Refresh" CONTENT="5; URL=index.php?page=checklogin"></head>
     <body><br /><br /><br /><br /><br /><br /><br />
     <h4><?php echo $txt['login1'] ?> <a href="index.php?page=checklogin"><?php echo $txt['login2'] ?></a></h4></body></html>
	<?php
    exit;
	}

    $query = sprintf("SELECT * FROM `customer` WHERE loginname=%s AND password=%s", quote_smart($_POST['name']), quote_smart($_POST['pass']));	
    $sql = mysql_query($query) or die(mysql_error());
	$count = mysql_num_rows($sql);
    while ($row = mysql_fetch_row($sql)) {
    $id = $row[0];
    }

    if ($count == 1)
	{
		$cookie_data = $name.'-'.$id; //name userid
		// store IP
		$query = "UPDATE `customer` SET `IP` = '".GetUserIP()."' WHERE `ID`=".$id;
     	$sql = mysql_query($query) or die(mysql_error());


			if(setcookie ("cookie_info",$cookie_data, $time+30240000)==TRUE)
			{
				?>
                 <html><head><link href="freewebshop.css" rel="stylesheet" type="text/css" />
                 <META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php?<?php echo $pagetoload . "&id=" . $id; ?>"></head>
                 <body><br /><br /><br /><br /><br /><br /><br />
                 <h4><?php echo $txt['login3'] ?></h4></body></html>
				<?php

			}
	}
	else
	{
     ?>
	     <html><head><link href="freewebshop.css" rel="stylesheet" type="text/css" />
	     <META HTTP-EQUIV="Refresh" CONTENT="5; URL=index.php?page=checklogin"></head>
	     <body><br /><br /><br /><br /><br /><br /><br />
	     <h4><?php echo $txt['login1'] ?> <a href="index.php?page=checklogin"><?php echo $txt['login2'] ?></a></h4></body></html>
     <?php 
     	 exit;
     }
     ?>