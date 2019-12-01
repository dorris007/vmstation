
  <div id="navcontainer2">
       <ul>

<?php
           // <li id="active"><a href="#" id="current">Item</a></li>
           echo "<li><a href=\"index.php?page=main\">" . $txt['menu1'] . "</a></li>";
           echo "<li><a href=\"index.php?page=cart&action=show\">" . $txt['menu2'] . " (".CountCart().")</a></li>";
           // if the general conditions page is disabled, then skip it when checking out
           if ($conditions_page == 1) { echo "<li><a href=\"index.php?page=conditions&action=checkout\">" . $txt['menu3'] . "</a></li>"; }
           else { echo "<li><a href=\"index.php?page=shipping\">" . $txt['menu3'] . "</a></li>"; }
           
           if (IsAdmin() == true) {
 	            echo "<li><a href=\"index.php?page=admin&version=$version\">" . $txt['menu9'] . "</a></li>";
           }
           if (LoggedIn() == true) {    
	            echo "<li><a href=\"index.php?page=my&id=". $customerid . "\">" . $txt['menu10'] . "</a></li>";
	            echo "<li><a href=\"logout.php\">" . $txt['menu11'] . "</a></li>";
           }
           else {
	            echo "<li><a href=\"index.php?page=my\">" . $txt['menu12'] . "</a></li>";
	            echo "<li><a href=\"index.php?page=customer&action=new\">" . $txt['menu13'] . "</a></li>";
           }
           echo "<li>";
           ShowFlags();
           echo "</li>";
?>

       </ul>
  </div>
