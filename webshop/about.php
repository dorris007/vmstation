<?php
/*  about.php
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
<?php include ("checklogin.php"); ?>
<?php include ("includes/httpclass.inc.php"); ?>

     <table width="100%" class="datatable">
       <caption>About FreeWebshop.org</caption>
      <tr><td> 
            <h6>Version</h6>
            <br />
            <img src=gfx/logo_about.gif style="float: right;">
            You are using <em>FreeWebshop.org</em> version <strong><?php echo $version ?></strong><br />
            <?php
            if ($live_news == 1) {
				$newest_version = new HTTPRequest('http://www.chaozz.nl/?printlastversion=12');
				echo "The most recent version is <strong>".$newest_version->DownloadToString()."</strong><br />";
			}
			?>
            <br />
            For recent fixes and changes <a href="docs/changelog.txt">read the changelog</a><br />
            Inform yourself about the copyright <a href="docs/copyright.txt">read the copyright text</a><br />
            <br />
            <h6>About</h6>
            <br />
            Thank you for using FreeWebshop.org. FreeWebshop.org is a free shopping cart script. 
            It's designed to simplify e-commerce for everyone. The project is an initiative by Elmar Wenners of chaozz@work software.
            The script is written in PHP and uses a MySQL database. The script is released under the GNU General Public License as 
            published by the Free Software Foundation.<br />
            <br />
            The project could use your contribution. <a href="http://www.freewebshop.org">See what you can do for FreeWebshop.org</a><br />
            <br />
            <h6>Support</h6>
            <br />
            Support site  : <a href="http://www.freewebshop.org">www.freewebshop.org</a><br />
            Support email : <a href="mailto:support@freewebshop.org">support@freewebshop.org</a><br />
            <br />
            Because this product is free software there is limited support. I will however support this
            product as much as I can be giving FREE support via my website. There you can post questions
            or suggestions. I hope a community will form to help eachother out and to share knowledge.<br />
            <br />
            For any other questions, read the documentation in the "/doc" folder of this installation.<br />
            <br />
            <br />
            Greetz,<br />
            Elmar Wenners (aka chaozz)
          </td>
      </tr>
     </table>