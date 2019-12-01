<?php
/*  index.php
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php 
    // files we need to include before the <head> tag
	include ("includes/settings.inc.php");
	include ("includes/subs.inc.php");
	include ("includes/readvals.inc.php");
	include ("includes/connectdb.inc.php");
	include ("includes/readsettings.inc.php");
	include ("includes/initlang.inc.php");
	include ("includes/readcookie.inc.php");
    include ($lang_file);
?>
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $charset ?>" />
<meta name="description" content="<?php echo $description ?>" />
<meta name="keywords" content="<?php echo $keywords ?>" />
<meta name="author" content="E. Wenners / FreeWebshop.org" />
<title><?php echo $page_title ?></title>
<link rel="stylesheet" type="text/css" href="freewebshop.css" />
</head>
<body>
<div id="container">
	<div id="header">
	     <?php 
	           include("header.php"); 
			   echo "<br />";
			   include("topmenu.php");
		 ?>
	</div>
	<div id="menu"> 
		 <?php 
		       include("menu.php"); 
		 ?>
	</div>
	<div id="content">
		 <?php 
		       include("loadmain.php"); 
		 ?>
	</div>
	<div id="footer">
		 <?php 
		       include("footer.php"); 
		 ?>
	</div>
</div>
</body>
</html>