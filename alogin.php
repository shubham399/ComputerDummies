<?php
include 'requirement.php';
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
$user = test_input($_POST["user"]);
$pass = test_input($_POST["pass"]);
if(alogin($user,$pass))
{
	$c=connect();
	$r1=mysqli_query($c,"SELECT `uid` from `users` where `username`='$user'");
	$row=mysqli_fetch_array($r1,MYSQLI_ASSOC);
	$uid=$row["uid"];
mysqli_query($c,"UPDATE  `users` set `alogincount`=`alogincount`+'1' where `uid`='$uid'");
	mysqli_close($c);
	session_start();
	$_SESSION["uid"]=$uid;
	$_SESSION["type"]="admin";
	session_write_close();
	echo "Loading..... Please Wait";
	$message=" Logged in to admin portal :SYSTEM";
if($uid>0)
 updatenotification($uid,$message);
	header('Refresh: 3; URL=./admin_index.php');
}
else
{
	echo "Invalid Username/Password or Your account has been Revoked or You don't have administrator Privilege";
	header('Refresh: 1; URL=./admin_index.php');
}
?>