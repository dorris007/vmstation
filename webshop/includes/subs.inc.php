<?php
    function strip_slashes($value) {
	    $value = stripslashes($value);
	    $value = str_replace("/", "hackthematrix", $value);
	    $value = str_replace(".", "hackthematrix", $value);
	    $value = strip_tags($value);
	    return $value;
    }
    
	function quote_smart($value)
	{
	   if( is_array($value) ) { 
	       return array_map("quote_smart", $value);
	   } else {
	       if( get_magic_quotes_gpc() ) {
	           $value = stripslashes($value);
	       }
	       if( $value == '' ) {
	           $value = 'NULL';
	       } 
	       if( !is_numeric($value) || $value[0] == '0' ) {
	           $value = "'".mysql_real_escape_string($value)."'";
	       }
	       return $value;
	   }
	}

    function br2nl($text)
    {
        return  preg_replace('/<br\\\\s*?\\/??>/i', "\\n", $text);
    }
	function mymail($from,$to,$subject,$message,$headers,$smtp_server,$smtp_port,$smtp_user,$smtp_pass,$charset)
	{
	  // don't use smtp but sendmail
	  if ($smtp_server == "") {
		  if (mail($to, $subject, $message, $headers)) {
	          return true;     
          }
          else { return false; }
      }
      require_once 'addons/xpertmailer/XPertMailer.php';
      $mail = new XPertMailer(SMTP_RELAY_CLIENT, $smtp_server);
      $mail->auth($smtp_user, $smtp_pass, AUTH_DETECT);
      $mail->from($from, $from);
      $mail->port(25);
	  $header['Reply-To'] = $to;
	  //$header['X-Whatever'] = '';
	  $mail->headers($header);      // text version
      $text = 'This is a HTML mail.'.CRLF.'You need a mail client that can read HTML e-mail messages in order to read this message.';
      $send = $mail->send($to, $subject, $text, $message, $charset);
      //echo $send ? "Done." : "Error."; 
	  return $send;
	} 
	

    Function CheckBox($check) {
        // returns 1 if checkbox is checked or 0 if unchecked
	    if ($check == "on") { return 1; }
        else { return 0; }
    }
 	 // parameter security. not implemented yet.
 	Function escape_data($data){
             $pattern='-{2,}';
             $data=eregi_replace($pattern,'',$data);
             return $data;
    }
    // email in html header
    Function EmailHeader ($email, $charset) {
		     if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
       			$s_eol = "\r\n";
			 } elseif (strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) {
       			$s_eol = "\r";
			 } else {
       			$s_eol = "\n";
			 }
             $email_header = "From: ".$email.$s_eol.
			                 'X-Mailer: PHP/' . phpversion() .$s_eol.
			                 "MIME-Version: 1.0".$s_eol .
			                 "Content-Type: text/html; charset=".$charset.$s_eol .
			                 "Content-Transfer-Encoding: 8bit".$s_eol.$s_eol;
			 return $email_header;
    }
    // format numbers according to settings
    Function myNumberFormat ($aNumber,$number_format) {
	         if ($number_format == "1234,56") {
		          $aNumber = number_format($aNumber, 2, ',', '');
	         }
	         if ($number_format == "1.234,56") {
		          $aNumber = number_format($aNumber, 2, ',', '.');
	         }
	         if ($number_format == "1234.56") {
		          $aNumber = number_format($aNumber, 2, '.', '');
	         }
	         if ($number_format == "1,234.56") {
		          $aNumber = number_format($aNumber, 2, '.', ',');
	         }
	         return $aNumber;
    }
	// is the id of an admin?          
    Function IsAdmin() {
             if (!isset($_COOKIE['cookie_info'])) { return false; }
	         $cookie_info = explode("-", $_COOKIE['cookie_info']);
             $customerid = $cookie_info[1];
             if (is_null($customerid)) { return false; }
	         $f_query = "SELECT * FROM customer WHERE ID = " . $customerid;
             $f_sql = mysql_query($f_query) or die(mysql_error());
             while ($f_row = mysql_fetch_row($f_sql)) {
                   if ($f_row[12] == "ADMIN") 
				   { 
				   		if ($f_row[6] == $_SERVER['REMOTE_ADDR']) 
						{ return true; } 
						else { 
							  //setcookie ("cookie_info","", time() - 3600)==TRUE; // kill the cookie
							  return false; 
							  }
                   } else 
					{ 
				   		return false; 
					}
             }
             return false;
    }
    // read the language folder and show the flags
    Function ShowFlags() {
             include ("includes/settings.inc.php");
   			 if ($dir = @opendir($lang_dir)) {
                while (($file = readdir($dir)) !== false) {
                       if ($file != "." && $file != ".." && $file != "index.php") {
							// for redirection to current page after setlang.php
							$redir = $_SERVER['argv'][0];
							if (!empty($redir)){
								$redir = str_replace("=", "**", $redir);
								$redir = str_replace("&", "$$", $redir);
							}
							//added the $redir variable to the lang link
							echo "<a href=\"setlang.php?lang=".$file."&redirect_to=".$redir."\"><img src=\"".$gfx_dir."/flags/".$file.".png\" alt=\"".$file."\" /></a> ";
                       }
                }  
                closedir($dir);
             }
    }
    
	// is the visitor logged in?          
    Function LoggedIn() {
             if (!isset($_COOKIE['cookie_info'])) { return false; }
             return true;
    }
    // if we know the category but not the group, this is how we find out
    Function TheGroup($TheCat) {
	         $g_query = "SELECT * FROM `category` WHERE `ID` = ".$TheCat;
             $g_sql = mysql_query($g_query) or die(mysql_error());
             while ($g_row = mysql_fetch_row($g_sql)) {
	             $FoundIt =  $g_row[2];
             }
             return $FoundIt;
    }
    // how many items in the cart?
    Function CountCart() {
              // customer id from cookie
             if (!isset($_COOKIE['cookie_info'])) { return 0; }
             $cookie_info = explode("-", $_COOKIE['cookie_info']);
             $customerid = $cookie_info[1];
             if (is_null($customerid)) { return 0; }
             $f_query = "SELECT * FROM basket WHERE (CUSTOMERID=".$customerid." AND STATUS='BASKET')";
             $f_sql = mysql_query($f_query) or die(mysql_error());
             return mysql_num_rows($f_sql);
    }
    // general window to display misc. messages
    Function PutWindow($title,$message,$picture,$width) {
	         include ("includes/settings.inc.php");
	         echo "<table class=\"datatable\">";
	         echo "<caption>".$title."</caption>";
             echo "<tr><td><img src=\"".$gfx_dir."/".$picture."\" alt=\"".$picture."\"></td>";
             echo "<td>".$message."</td></tr></table>";
             echo "<br /><br />";
	}
  // is the customer living in the default send country?
   Function IsCustomerFromDefaultSendCountry($f_send_default_country) {
            // determine sendcosts depending on the country of origin
            $cookie_info = explode("-", $_COOKIE['cookie_info']);
            $customerid = $cookie_info[1];

            $f_query="SELECT * FROM `customer` WHERE `ID` = " . $customerid ;
            $f_sql = mysql_query($f_query) or die(mysql_error());
            while ($f_row = mysql_fetch_row($f_sql)) {
                   $country = $f_row[13];
            }
            if ($country == $f_send_default_country) { 
	            return 1;
            }
            else { return 0; }
   }
   // split up a string, used by max_description
   Function stringsplit($the_string, $the_number)  {
	        $startoff_nr = 0;
            $the_output_array = array();
            for($z = 1; $z < ceil(strlen($the_string)/$the_number)+1 ; $z++) {
	           $startoff_nr = ($the_number*$z)-$the_number;
               $the_output_array[] = substr($the_string, $startoff_nr, $the_number);
            }
            return($the_output_array);
   }	   
   // see if url exists (for picture on remote host as well)
   function url_exists($url) {
	       $a_url = parse_url($url);
	       if (!isset($a_url['port'])) $a_url['port'] = 80;
	       $errno = 0;
	       $errstr = '';
	       $timeout = 5;
	       if(isset($a_url['host']) && $a_url['host']!=gethostbyname($a_url['host'])){
	           $fid = @fsockopen($a_url['host'], $a_url['port'], $errno, $errstr, $timeout);
	           if (!$fid) return false;
	           $page = isset($a_url['path'])  ?$a_url['path']:'';
	           $page .= isset($a_url['query'])?'?'.$a_url['query']:'';
	           fputs($fid, 'HEAD '.$page.' HTTP/1.0'."\r\n".'Host: '.$a_url['host']."\r\n\r\n");
	           $head = fread($fid, 4096);
	           fclose($fid);
	           return preg_match('#^HTTP/.*\s+[200|302]+\s#i', $head);
	       } else {
	           return false;
	       }
	}
    // check if local or remote picture exists   
    function thumb_exists($thumbnail) {
	         $pos = strpos($thumbnail, "://");
	         if ($pos === false) { 
		         return file_exists($thumbnail);
	         }
	         else {
	             return url_exists($thumbnail);
	         }
    }
    // get user IP
    function GetUserIP() {
          if (isset($_SERVER)) { if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) 
                                    { $ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; } 
                                 elseif(isset($_SERVER["HTTP_CLIENT_IP"])) 
                                    { $ip = $_SERVER["HTTP_CLIENT_IP"]; } 
                                 else { $ip = $_SERVER["REMOTE_ADDR"]; }
                               }  
          else { if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) 
                      { $ip = getenv( 'HTTP_X_FORWARDED_FOR' ); } 
                 elseif ( getenv( 'HTTP_CLIENT_IP' ) ) 
                      { $ip = getenv( 'HTTP_CLIENT_IP' ); } 
                 else { $ip = getenv( 'REMOTE_ADDR' ); }
               }
          return $ip;     
    }  
    // trim a string        
    function file_trim(&$value, $key){ 
	    $value = trim($value); 
    }
    // check if current user is banned
    function IsBanned() {
             // check ip from database
	         if (!isset($_COOKIE['cookie_info'])) { return false; }
	         $cookie_info = explode("-", $_COOKIE['cookie_info']);
             $customerid = $cookie_info[1];
             if (is_null($customerid)) { return false; }
	         $f_query = "SELECT * FROM customer WHERE ID = " . $customerid;
             $f_sql = mysql_query($f_query) or die(mysql_error());
             while ($f_row = mysql_fetch_row($f_sql)) {
                    $userip = $f_row[6];
             }
             // get current computers ip
             $ip = GetUserIP(); 
             
             // now check both in the banlist
		     $file = file('banned.txt');
		     @array_walk($file, 'file_trim');
		     while (list($key, $val) = each($file)) {
		            if ($ip == $val) { return true; }
		            if ($userip == $val) { return true; }
		     }
		     return false;
    }

    
    

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
    
    
    
    
    
    
    
																																	$found = false;    
																																	$check ="Po"."were"."d b"."y "."Free"."Webs"."hop".".org";
																																	$file = file('footer'.'.php');
																																	@array_walk($file, 'file_trim');
																																	while (list($key, $val) = each($file)) {
																																	     $pos = strpos ($val, $check);
																																	     if (is_integer($pos)) { $found = true; }
																																	}    
																																	if (!$found) { echo "You rem"."oved the Pow"."ered by FreeWebshop.org li"."ne. That i"."s a vio"."lation of the G"."NU GENER"."AL PU"."BLIC LIC"."ENSE. Ple"."ase rest"."ore the ori"."ginal fo"."oter, or re"."move the soft"."ware."; exit; }
?>