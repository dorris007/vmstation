<?php
/*  footer.php
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
      //website start year and current year
      $date_arr = getdate();
      $current_year = $date_arr['year'];
      if ($start_year == $current_year) { 
	      $footer_year = $current_year; 
	  }
	  else { $footer_year = $start_year."-".$current_year; }
    
	  echo $shopname ?> | <?php if (!is_null($page_footer)) { echo $page_footer." | "; } ?> &copy;<?php echo $footer_year;
	  echo "<br />";
	  
   	  // PLEASE DO NOT REMOVE THE COPYRIGHT LINE. THIS IS THE ONLY THING WE ASK IN RETURN FOR THIS FREE SCRIPT!
      // YOU GET A FREE SCRIPT, WE GET SOME EXPOSURE. FAIR ENOUGH? -->
       echo "<a href=\"http://www.freewebshop.org\"><small>Powered by FreeWebshop.org</small></a>";
?>
       <!-- you can add a page counter below this line!! -->
