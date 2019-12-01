<?php
/*  info.php
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
  // this check is not pretty, but nescesarry
  if ($action != "guarantee" && $action != "shipping" && $action != "aboutus") { exit(include("includes/exit.inc.php")); } // hacking atempt

  // the files and strings we use
  $info_file = $lang_dir."/".$lang."/".$action.".txt";
  $info_pic  = $action.".gif";
  if ($action == "guarantee") { $header = $txt['guarantee1']; }
  if ($action == "shipping") { $header = $txt['send1']; }
  if ($action == "aboutus") { $header = $txt['menu18']; }
  
  
  // open the text file
  $fp = fopen($info_file, "rb") or die("Sorry. This page encountered an error opening the ".$action." page.");
  $info = fread($fp, filesize($info_file));
  fclose($fp);

  PutWindow($header, nl2br($info), $info_pic, "100");
  
  // make an edit link
  if (IsAdmin() == true) { echo "<h4><a href=\"?page=adminedit&filename=".$action.".txt&root=0\">".$txt['browse7']."</a></h4>"; }  
?>