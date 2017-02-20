<?php
	session_start();
	include 'requirement.php';
	function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
	
	$uid=-1;
	if(!isset($_SESSION['uid']))
	{
		echo "Login to change the password";
		header('Refresh: 1; URL=./index.php');
	}
	else
	{
		$uid=$_SESSION['uid'];
		$op=test_input($_POST["op"]);
		$np=test_input($_POST["np"]);
		$cnp=test_input($_POST["cnp"]);
		$comp=strcmp($np,$cnp);
		if(!$comp)
		{
			passwordrest($uid,$op,$np);
		}
	}
	header('Refresh: 2; URL=./index.php');
?>