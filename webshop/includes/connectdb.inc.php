<?php
    // connect to db
    $db = mysql_connect($dblocation,$dbuser,$dbpass) or die(mysql_error());
 	mysql_select_db($dbname,$db) or die(mysql_error());
?>