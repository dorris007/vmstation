<?php
/*  menu.php
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
           echo "<h1>".$txt['menu15']."</h1>\n"; 
           
           echo "<div id=\"navcontainer\">";
                            
           // if the category is send, then use that to find out the group
           if (!is_null($cat) && $cat != 0) { $group = TheGroup($cat); }
           
           $query = "SELECT * FROM `group` ORDER BY `NAME` ASC";
	       $sql = mysql_query($query) or die(mysql_error());

           if (mysql_num_rows($sql) == 0) {
	          echo $txt['menu17']; // no groups found
           }
	       else {
                echo "<ul id=\"navlist\">\n";
                while ($row = mysql_fetch_row($sql)) {
	                  if ($group != $row[0]) { 
			            echo "<li><a href=\"index.php?group=" . $row[0] . "&page=categories\">" . $row[1] . "</a></li>\n"; 
			        }
	                else {
		                //select/highlight
			            echo "<li id=\"active\"><a id=\"current\" href=\"index.php?group=" . $row[0] . "&page=categories\">" . $row[1] . "</a></li>\n"; 
	                }
                }
                echo "</ul>\n";
           }
           echo "<br />\n";
          
           echo "<h1>".$txt['menu14']."</h1>\n"; 
           echo "<ul id=\"navlist\">\n";
           echo "<li"; if ($page == "search") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=search\">" . $txt['menu4'] . "</a></li>\n";
           echo "<li"; if ($page == "browse" && $action=="shownew") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=browse&action=shownew\">" . $txt['menu16'] . "</a></li>\n";
           if ($conditions_page == 1) { echo "<li"; if ($page == "conditions" && $action == "show") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=conditions&action=show\">" . $txt['menu5'] . "</a></li>\n"; }
           
           if ($shipping_page == 1) { echo "<li"; if ($page == "info" && $action== "shipping") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=info&action=shipping\">" . $txt['menu6'] . "</a></li>\n"; }
           if ($guarantee_page == 1) { echo "<li"; if ($page == "info" && $action== "guarantee") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=info&action=guarantee\">" . $txt['menu7'] . "</a></li>\n"; }
           if ($aboutus_page == 1) { echo "<li"; if ($page == "info" && $action== "aboutus") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=info&action=aboutus\">" . $txt['menu18'] . "</a></li>\n"; }
           
           echo "<li"; if ($page == "contact") { echo " id=\"active\""; }; echo "><a href=\"index.php?page=contact\">" . $txt['menu8'] . "</a></li>\n";
           echo "</ul>\n";
           
           echo "</div>";
?>