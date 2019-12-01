<?php
/*  admin.php
    Copyright 2006 Elmar Wenners
    Support site: http://www.chaozz.nl

    This file is part of UltraShop.

    UltraShop is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    UltraShop is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with UltraShop; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
?>
<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php include ("checklogin.php"); ?>

<?php
    if (IsAdmin() == false) {
	  PutWindow ($txt['general12'], $txt['general2'], "warning.gif", "50");
	  exit(include("includes/exit.inc.php"));
    }
?>
<?php include ("includes/httpclass.inc.php"); ?>
<?php
      if (!empty($_GET['adminaction'])) {
	      $adminaction=$_GET['adminaction'];
      }
	  if ($adminaction == "optimize_tables") {
		  echo "<strong>".$txt['admin10']."</strong><br /><br />";
		  //get all tables
          $alltables = mysql_query("SHOW TABLES");
          //go trough them, save as an array
          while($table = mysql_fetch_assoc($alltables)){
                //go through the array ( $db => $tablename )
                foreach ($table as $db => $tablename) {
                        //optimize every table
                        echo $txt['admin11']." ".$tablename.".. ";
                        mysql_query("OPTIMIZE TABLE `".$tablename."`") or die(mysql_error());
                        echo "<strong>".$txt['admin12']."</strong><br />";
                }
          }
	      exit(include("includes/exit.inc.php"));
      }
?>
	  <table width="80%" class="datatable">
	    <caption><?php echo $txt['admin1']; ?></caption>
	   <tr><td>

          <table class="borderless" width="100%">
              <tr><td colspan="3"><h6><?php echo $txt['admin23']; ?></h6></td></tr>
              <tr>
                 <td><div style="text-align:center;"><a class="plain" href="?page=orderadmin"><img src="<?php echo $gfx_dir; ?>/orders.gif" alt="" /><br /><?php echo $txt['admin2']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=customeradmin"><img src="<?php echo $gfx_dir; ?>/customers.gif" alt="" /><br /><?php echo $txt['admin3']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=productadmin&action=add_product"><img src="<?php echo $gfx_dir; ?>/products.gif" alt="" /><br /><?php echo $txt['admin5']; ?></a><br /><br /></div></td>
              </tr>
              <tr>   
                 <td><div style="text-align:center;"><a class="plain" href="?page=groupadmin"><img src="<?php echo $gfx_dir; ?>/groups.gif" alt="" /><br /><?php echo $txt['admin6']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=paymentadmin"><img src="<?php echo $gfx_dir; ?>/paymentadmin.gif" alt="" /><br /><?php echo $txt['admin21']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=shippingadmin"><img src="<?php echo $gfx_dir; ?>/shippingadmin.gif" alt="" /><br /><?php echo $txt['admin18']; ?></a><br /><br /></div></td>
              </tr>
              <tr>   
                 <td><div style="text-align:center;"><a class="plain" href="?page=uploadadmin"><img src="<?php echo $gfx_dir; ?>/uploadlist.gif" alt="" /><br /><?php echo $txt['admin9']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=editsettings"><img src="<?php echo $gfx_dir; ?>/settings.gif" alt="" /><br /><?php echo $txt['admin8']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=admin&adminaction=optimize_tables"><img src="<?php echo $gfx_dir; ?>/optimize.gif" alt="" /><br /><?php echo $txt['admin7']; ?></a><br /><br /></div></td>
              </tr>
              <tr><td colspan="3"><h6><?php echo $txt['admin24']; ?></h6></td></tr>
              <tr>   
                 <td><div style="text-align:center;"><a class="plain" href="?page=adminedit&filename=shipping.txt&root=0"><img src="<?php echo $gfx_dir; ?>/shipping.gif" alt="" /><br /><?php echo $txt['admin16']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=adminedit&filename=guarantee.txt&root=0"><img src="<?php echo $gfx_dir; ?>/guaranteeadmin.gif" alt="" /><br /><?php echo $txt['admin17']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=adminedit&filename=conditions.txt&root=0"><img src="<?php echo $gfx_dir; ?>/conditionsadmin.gif" alt="" /><br /><?php echo $txt['admin15']; ?></a><br /><br /></div></td>
              </tr>
              <tr>   
                 <td><div style="text-align:center;"><a class="plain" href="?page=adminedit&filename=banned.txt&root=1&wysiwyg=0"><img src="<?php echo $gfx_dir; ?>/banned.gif" alt="" /><br /><?php echo $txt['admin19']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=adminedit&filename=aboutus.txt&root=0"><img src="<?php echo $gfx_dir; ?>/aboutusicon.gif" alt="" /><br /><?php echo $txt['admin20']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="?page=adminedit&filename=main.txt&root=0"><img src="<?php echo $gfx_dir; ?>/mainadmin.gif" alt="" /><br /><?php echo $txt['admin22']; ?></a><br /><br /></div></td>
              </tr>
              <tr><td colspan="3"><h6><?php echo $txt['admin25']; ?></h6></td></tr>
              <tr>
                 <td><div style="text-align:center;"><a class="plain" href="?page=about"><img src="<?php echo $gfx_dir; ?>/about.gif" alt="" /><br /><?php echo $txt['admin13']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;"><a class="plain" href="http://www.chaozz.nl/?checkupdate=12&version=<?php echo $version ?>"><img src="<?php echo $gfx_dir; ?>/update.gif" alt="" /><br /><?php echo $txt['admin14']; ?></a><br /><br /></div></td>
                 <td><div style="text-align:center;">&nbsp;</div></td>
              </tr>   
          </table>       
       </tr>
      </table>
      <br />
      <br />
      
      <?php
          // the live news feed
          if ($live_news == 1) {
              $livenews = new HTTPRequest('http://www.freewebshop.org/?action=live_news');
    	      PutWindow ("Live from FreeWebshop.org", $livenews->DownloadToString(), "news.gif", "100");
          }
      ?>
     