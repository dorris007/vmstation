<?php
/*  contact.php
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
         // read values if needed
         if (!empty($_POST['action'])) {
             $mail = 0;

	         if (!empty($_POST['name'])) {
   	            $name=strip_tags($_POST['name']);
   	            $mail = $mail +1;
	         }
	         if (!empty($_POST['email'])) {
   	            $email=$_POST['email'];
   	            $mail = $mail +1;
	         }
	         if (!empty($_POST['message'])) {
   	            $message=strip_tags($_POST['message']);
   	            $mail = $mail +1;
	         }

	         if ($mail == 3) {
			     // ip address, so you can block a notorious spammer
			     $message = "<strong>message from:</strong>&nbsp;<a href=\"mailto:".$email."\">".$name."</a> <em><".GetUserIP()."></em><br /><br />".nl2br($message);
			     // email header
			     $email_header = EmailHeader($sales_mail, $charset); 
	
			     mymail($webmaster_mail, $webmaster_mail, $txt['contact1'], $message, $email_header, $smtp_server, $smtp_port, $smtp_user, $smtp_pass, $charset);
			     PutWindow ($txt['general13'] , $txt['contact3'], "notify.gif", "90");
	             exit(include("includes/exit.inc.php"));
  	         }
  	         else {
                   PutWindow ($txt['general12'], $txt['contact5'], "warning.gif", "50");
                   exit(include("includes/exit.inc.php"));
  	         }

	     }
?>
    <table width="70%" class="datatable">
      <caption><?php echo $txt['contact6']; ?></caption>

     <tr><td>
          <?php echo $txt['contact7']." ".$shopname; ?><br />
         <br />
          <table class="borderless">
             <tr><td><img src="<?php echo $gfx_dir ?>/mail.gif" alt="" />&nbsp;&nbsp;<a href="mailto:<?php echo $webmaster_mail; ?>"><?php echo $webmaster_mail; ?></a></td></tr>
             <?php if (!$shoptel==NULL) { echo "<tr><td><img src=\"".$gfx_dir."/phone.gif\" alt=\"\" />&nbsp;&nbsp;".$shoptel."</td></tr>"; } ?>
             <?php if (!$shopfax==NULL) { echo "<tr><td><img src=\"".$gfx_dir."/fax.gif\" alt=\"\" />&nbsp;&nbsp;".$shopfax."</td></tr>"; } ?>
          </table>   
         <br />
         <br />
         <?php echo $txt['contact11']; ?><br />

         <form method="POST" action="index.php?page=contact">
	       <?php echo $txt['contact13']; ?><br />
	       <input type="text" name="name" size="25" maxlength="25" value=""><br />
	       <?php echo $txt['contact14']; ?><br />
	       <input type="text" name="email" size="45" maxlength="45" value=""><br />
	       <?php echo $txt['contact15']; ?><br />
	       <textarea name="message" rows=15 cols=55 value=""></textarea><br />
           <input type="hidden" name="action" value="mailform">
           <input type="submit" value="<?php echo $txt['contact12']." ".$shopname; ?>">
         </form>
	     <br />
     </td></tr>
    </table>