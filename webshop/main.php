<?php if ($index_refer <> 1) { exit(include("includes/exit.inc.php")); } ?>
<?PHP echo "<center><font size=5 color=red>欢迎来到开心果购物网站</font></center>"; ?>    
    <?php
         // Are there any special offers (frontpage=1 in product details)?
         $m_query = "SELECT * FROM `product` WHERE `FRONTPAGE` = '1'";
         $m_sql = mysql_query($m_query) or die(mysql_error());
         if (!mysql_num_rows($m_sql) == 0) {
            while ($m_row = mysql_fetch_row($m_sql)) {

	              // Read product details
	              $query_details = "SELECT * FROM `product` WHERE `ID`=" . $m_row[0];
	              $sql_details = mysql_query($query_details) or die(mysql_error());
                  while ($row_details = mysql_fetch_row($sql_details)) {
	                    $prod = $m_row[0];
	                    $refermain = 1;  // This is important, because it tells details.php that it was opened by main.php, not browse.php
	                    include ("details.php");
	                    echo "<br /><br />";
                   }
            }
         }
    ?>