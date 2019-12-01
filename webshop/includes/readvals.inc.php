<?php
    // some values we read through get or post 
    $page="main";
	
	if (!empty($_GET['page'])) {
	    $page=strip_slashes($_GET['page']);
	}
	if (!empty($_GET['action'])) {
	    $action=strip_slashes($_GET['action']);
	}
	if (!empty($_POST['action'])) {
	    $action=strip_slashes($_POST['action']);
	}
	if (!empty($_GET['cat'])) {
	    $cat=intval(strip_slashes($_GET['cat']));
	}
	if (!empty($_GET['prod'])) {
	    $prod=intval(strip_slashes($_GET['prod']));
	}
	if (!empty($_GET['group'])) {
	    $group=intval(strip_slashes($_GET['group']));
	}
?>