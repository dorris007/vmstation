<?php
/*  conditions.php
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
<?php if ($action == "checkout") { include ("checklogin.php"); } ?>
<?php
  $count = CountCart($customerid);
  if ($count == 0 && $action == "checkout") {
	      PutWindow ($txt['cart1'], $txt['cart2'], "carticon.gif", "50");
  }
  else {
	  if ($action == "checkout") { 
		  
		  if ($ordering_enabled == 1) {
		  	  echo "<h4><img src=\"".$gfx_dir."/1.gif\" alt=\"step 1\">&nbsp;<img src=\"".$gfx_dir."/2_.gif\" alt=\"step 2\">&nbsp;<img src=\"".$gfx_dir."/3_.gif\" alt=\"step 3\"></h4><br /><br />"; 
		  	  }
		  else {
			  PutWindow ($txt['conditions3'], $txt['conditions4'], "carticon.gif", "50");
		  }
      }  
  	  
	  // read the conditions file
	  $conditions_file = $lang_dir."/".$lang."/conditions.txt";
	  $fp = fopen($conditions_file, "rb") or die("Couldn't open ".$conditions_file.". Make sure it exists and is readable.");
	  $conditions = fread($fp, filesize($conditions_file));
	  fclose($fp);
?>
	 <form method="post" action="index.php?page=shipping">
	   <textarea rows="30" cols="65" readonly><?php echo $conditions ?></textarea><br />
	  <?php
  }
  
  if ($count != 0 && $action == "checkout" && $ordering_enabled == 1) {
	  echo "<input type=\"submit\" value=\"" . $txt['conditions1'] . "\"><br />";
      }
      ?>
 </form>
 
<?php
  if (IsAdmin() == true && $action == "show") { echo "<h4><a href=\"?page=adminedit&filename=conditions.txt&root=0\">".$txt['browse7']."</a></h4>"; }
?> 