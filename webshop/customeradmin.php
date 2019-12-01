
<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?php include ("checklogin.php"); ?>

<?php
	// admin check
    if (IsAdmin() == false) {
	  PutWindow ($txt['general12'], $txt['general2'], "warning.gif", "50");
	  exit(include("includes/exit.inc.php"));
    }

    $query = "SELECT * FROM `customer` ORDER BY ID ASC";
    $sql = mysql_query($query) or die(mysql_error());

    ?>
    
    <table width="100%" class="datatable">
      <caption><?php echo $txt['customeradmin6']; ?></caption>
     <tr> 
      <th><?php echo $txt['customeradmin1']; ?></th>
      <th><?php echo $txt['customeradmin2']; ?></th>
      <th><?php echo $txt['customeradmin3']; ?></th>
      <th><?php echo $txt['customeradmin4']; ?></th>
      <th><?php echo $txt['customeradmin5']; ?></th>
     </tr>

    <?php
    $color = $tb_pricelist_color2;
    while ($row = mysql_fetch_row($sql)) {
	   echo "<tr>";
	   echo "<td>".$row[3]."</td>";
	   echo "<td>".$row[15]."</td>";
	   echo "<td><a href=\"mailto:".$row[11]."\">".$row[11]."</a></td>";
	   echo "<td>".$row[10]."</td>";
	   echo "<td>";
	   echo "<a href=\"index.php?page=customer&action=show&customerid=".$row[0]."\"><img src=\"".$gfx_dir."/admin_edit.gif\" width=\"16\" height=\"16\" alt=\"编辑会员信息\" /></a>";
	   if ($row[12] != "ADMIN") {  
		   // you cannot remove the shop admin even if you wanted it
		   echo "<a href=\"index.php?page=customer&action=delete&customerid=".$row[0]."\"><img src=\"".$gfx_dir."/admin_delete.gif\" width=\"16\" height=\"16\" alt=\"删除会员\" /></a>";
	   }
	   echo "<a href=\"index.php?page=orders&id=".$row[0]."\"><img src=\"".$gfx_dir."/admin_orders.gif\" width=\"16\" height=\"16\" alt=\"查看用户订单\" /></a>";
	   echo "<a href=\"index.php?page=cart&action=show&id=".$row[0]."\"><img src=\"".$gfx_dir."/admin_cart.gif\" width=\"16\" height=\"16\" alt=\"查看用户购物车\"></a>";
	   echo "<a href=\"javascript:alert('用户名: ".stripslashes($row[1])." | 密码: ".stripslashes($row[2])."')\"><img src=\"".$gfx_dir."/admin_login.gif\" width=\"16\" height=\"16\" alt=\"查看用户名和密码\"></a>";
	   echo "</td></tr>";
    }
?>
    </table>