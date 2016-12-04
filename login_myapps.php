<?php
//login
if($_GET['i'] && $_GET['k'])
{
	//username & password from url
	$username = $_GET['i'];
	$password = $_GET['k'];

	//check username & password for login
	$error = checklogin($myQuery,$mySQL,$mySession,cleanData($username),md5($password));
}//eof if

if($_SESSION['userID'])
	header('Location: index.php?'.$_SERVER['QUERY_STRING']);	
else
	//redirect to no access page
	echo "You don't have permission to view this page";
	//include(SYSTEM_LOGIN_PAGE);	
?>