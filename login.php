<?php
include 'requirement.php';
$nameErr="";
$passErr="";
$user="";
$pass="";
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
$user = test_input($_POST["user"]);
$pass = test_input($_POST["pass"]);
if(login($user,$pass))
{
	$c=connect();
	$r1=mysqli_query($c,"SELECT `uid` from `users` where `username`='$user'");
	$row=mysqli_fetch_array($r1,MYSQLI_ASSOC);
	$uid=$row["uid"];
	mysqli_query($c,"UPDATE  `users` set `plogincount`=`plogincount`+'1' where `uid`='$uid'");
	mysqli_close($c);
	session_start();
	$_SESSION["uid"]=$uid;
	$_SESSION["type"]="player";
	session_write_close();
echo "Please Wait While We Log you in!! We Will Redirect You after Verication is done";
$message=" Logged in to player portal :SYSTEM";
if($uid>0)
 updatenotification($uid,$message);
	}
else
{
	unset($_POST['submit']);
	echo "Invalid Username/Password or Your account has been Revoked";

}
header('Refresh: 2; URL=./index.php');
?>	
