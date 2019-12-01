<?php
    // public error printer
    error_reporting(0);
    function PrintError ($message) {
	    echo "<br /><br />";
	    echo "<table border=\"5\" bordercolor=\"red\" cellpadding=\"4\" cellspacing=\"0\"><tr><td><strong>Error:</strong>&nbsp;";
	    echo $message;
	    echo "<br /><br /><a href=\"install.php\">Try again</a>";
	    echo "</td></tr></table>";
	    exit;
    }
    function parse_mysql_dump($url, $ignoreerrors = false) {
        $file_content = file($url);
        //print_r($file_content);
        $query = ""; 
        foreach($file_content as $sql_line) {
          $tsl = trim($sql_line);
          if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
            $query .= $sql_line;
            if(preg_match("/;\s*$/", $sql_line)) {
              $result = mysql_query($query);
              if (!$result && !$ignoreerrors) die(mysql_error());
              $query = "";
            }
          }
        }
    }
    // header printer
    function PrintPageHeader ($header) {
	    echo "<h1><img src=\"gfx/logo_about.gif\" align=\"right\" alt=\"\" />".$header."<hr size=\"5\" noshade color=\"#001894\" width=\"100%\"></h1>";
    }
	    

    // read post values
    $step = 1;
    if (!empty($_POST['step'])) { $step=$_POST['step']; }
    // database values
    if (!empty($_POST['dblocation'])) { $dblocation=$_POST['dblocation']; }
    else { $dblocation = ""; }	    
    if (!empty($_POST['dbname'])) { $dbname=$_POST['dbname']; }
    else { $dbname = ""; }	    
    if (!empty($_POST['dbuser'])) { $dbuser=$_POST['dbuser']; }
    else { $dbuser = ""; }	    
    if (!empty($_POST['dbpass'])) { $dbpass=$_POST['dbpass']; }
    else { $dbpass = ""; }	    
    // smtp values
    if (!empty($_POST['smtp_server'])) { $smtp_server=$_POST['smtp_server']; }
    else { $smtp_server = ""; }	    
    if (!empty($_POST['smtp_port'])) { $smtp_port=$_POST['smtp_port']; }
    else { $smtp_port = ""; }	    
    if (!empty($_POST['smtp_user'])) { $smtp_user=$_POST['smtp_user']; }
    else { $smtp_user = ""; }	    
    if (!empty($_POST['smtp_pass'])) { $smtp_pass=$_POST['smtp_pass']; }
    else { $smtp_pass = ""; }	    
    
    // header
    ?>
    <html>
     <head>
	  <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	  <META HTTP-EQUIV="Expires" CONTENT="-1">
      <title>FreeWebshop.org installation procedure</title>
     </head>
     <body bgcolor="white">
     <font face="verdana,arial">
    <?php   
    
    if ($step == 1) {
	    // display welcome
	    PrintPageHeader("欢迎使用电子购物平台");
	    ?>
	    我们将使用一个非常简单的方法创建数据表.<br />
	    <br />
	    <b>注意:</b> 请仔细阅读docs文件夹中的文件, 以获得更多的安装信息。<br />
	    <br />
	    在单击“下一步”之前，必须做好以下准备:
		<ul>
	     <li><font color=red>必须创建一个“webshop”数据库</font></li>
	     <li><font color=red>在数据库中添加一个用户</font> </li>
	     <li><font color=red>必须确信包含includes\settings.inc.php文件</font></li>
		 <li><font color=red>如果你打算使用SMTP服务器发送邮件，你还必须知道服务器地地址、端口号、用户名和密码等</font></li>
	    </ul> 
	    在做好以上准备之前，单击“下一步”继续..<br />
	    <br />
	    <form method="post" action="install.php">
	          <input type="hidden" name="step" value="2">
	          <input type="submit" value="Next step">
	    </form>      
	    <?php
    }

    if ($step == 2) {
	    // show form in which database values must be filled in
	    PrintPageHeader("步骤1 - 填写创建数据表及邮件服务器的基本信息");
	    ?>
	     首先，我们必须创建一个数据库. 如果还没创建好,现在也可以创建! 因为要把数据表中的数据保存到数据库中.<br />
	    <br />
	    <form method="post" action="install.php">
	          <strong>数据库配置</strong><br />
	          数据库服务器地址:<br />
	          <input type="text" name="dblocation" value="localhost"><br />
	          数据库的名称:<br />
	          <input type="text" name="dbname" value=""><br />
	          数据库的用户名:<br />
	          <input type="text" name="dbuser" value=""><br />
	          数据库用户的密码:<br />
	          <input type="password" name="dbpass" value="">
	          <br />
	          <br />
	          <strong>SMTP服务器的配置</strong><br/>
	          SMTP服务器的名称:<br />
	          <input type="text" name="smtp_server" value=""><br />
	          SMTP服务器的端口号:<br />
	          <input type="text" name="smtp_port" value="25"><br />
	          SMTP服务器的用户名:<br />
	          <input type="text" name="smtp_user" value=""><br />
	          SMTP服务器用户的密码:<br />
	          <input type="password" name="smtp_pass" value="">
	          <br />
	          <input type="hidden" name="step" value="3">
	          <input type="submit" value="Next step">
	    </form>      
	    <?php
    }    	    
 
    if ($step == 3) {
       PrintPageHeader("步骤2 -将设置保存到settings文件中");
	   // try to connect to database before writing them to the settings file
	   $db = mysql_connect($dblocation,$dbuser,$dbpass) or die(PrintError ("Could not connect to the database ".$dbname." with the data supplied. Please check the data!"));
 	   mysql_select_db($dbname,$db) or die(PrintError ("Could not connect to the database ".$dbname." with the data supplied. Please check the data!"));
	   echo "成功链接数据库！<br />";
	        // save values to settings.php
	        $nothing = "";
            @chmod("includes/settings.inc.php", 0777);
	        $f=@fopen("includes/settings.inc.php","a");
	        if ($f) {
		        fwrite($f,"\n");
		        fwrite($f,"    // database values\n");
		        fwrite($f,"    $".$nothing."dblocation = \"".$dblocation."\";\n");
		        fwrite($f,"    $".$nothing."dbname = \"".$dbname."\";\n");
		        fwrite($f,"    $".$nothing."dbuser = \"".$dbuser."\";\n");
		        fwrite($f,"    $".$nothing."dbpass = \"".$dbpass."\";\n");
		        fwrite($f,"    \n");
		        fwrite($f,"    // SMTP values (OPTIONAL, LEAVE SERVER EMPTY IF YOU WANT TO USE PHPs MAIL() )\n");
		        fwrite($f,"    $".$nothing."smtp_server = \"".$smtp_server."\";\n");
		        fwrite($f,"    $".$nothing."smtp_port = \"".$smtp_port."\";\n");
		        fwrite($f,"    $".$nothing."smtp_user = \"".$smtp_user."\";\n");
		        fwrite($f,"    $".$nothing."smtp_pass = \"".$smtp_pass."\";\n");
		        fwrite($f,"?>");
		        fclose($f);
		        echo "并用户输入的数据都被保存到settings.php文件中。";
		        ?>
	            <br />
	            <form method="post" action="install.php">
	                 <input type="hidden" name="dblocation" value="<?php echo $dblocation; ?>">
	                 <input type="hidden" name="dbname" value="<?php echo $dbname; ?>">
	                 <input type="hidden" name="dbuser" value="<?php echo $dbuser; ?>">
	                 <input type="hidden" name="dbpass" value="<?php echo $dbpass; ?>">
	                 <input type="hidden" name="step" value="4">
	                 <input type="submit" value="Next step">
	            </form>
	            <?php      
            }
            else {
		    // settings.php is not writable
		    PrintError ("includes/settings.inc.php is not writable. Change it's permissions to 777 and try again!");
	    }
    }
    
    if ($step == 4 ) {
	   PrintPageHeader("步骤3 -创建数据表成功 "); 
	   // connect to database
	   $db = mysql_connect($dblocation,$dbuser,$dbpass) or die(PrintError ("Could not connect to the database ".$dbname." with the data supplied. Please check the data!"));
 	   mysql_select_db($dbname,$db) or die(PrintError ("Could not connect to the database ".$dbname." with the data supplied. Please check the data!"));
 	   
 	   echo "Filling database <strong>".$dbname."</strong> with the correct tables and values..<br /><br />"; 
       // fill database with correct structure	    
       parse_mysql_dump('FreeWebshop.sql', FALSE);
       echo "<br><b>完成!</b><br><br>";
       echo "已经成功创建了eshop中的数据表.但是在运行之前还必须删除或修改<b>install.php</b>文件!<br><br>";
       echo "你现在可以登录<a href=index.php?page=my>电子购物系统</a>使用刚创建的用户名和密码！！！<br><br>";
       echo "<b>管理员用户名:</b> admin<br><b>管理员密码:</b> admin_1234<br><br>";
       echo "<font color=red><b>不要忘记修改admin用户的密码和EMAIL地址!!</b></font><br><br>";
	  
    }

   // footer
?>
   </font>
   </body>
  </html> 
