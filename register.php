<?php
include 'requirement.php';
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
$name=test_input($_POST['name']);
$user=test_input($_POST['user']);
$email=test_input($_POST['email']);
$cmail=test_input($_POST['cmail']);
$comp=strcmp($pass,$cpass);
$cm=strcmp($email,$cmail);
$pass=generateRandomString(10);

if(!$comp && !$cm)
{
	add_user($name,$user,$pass,$email);
	addnotification("A NEW USER Just Register :SYSTEM");
	$message=" register :SYSTEM";
	$c=connect();
	$res=mysqli_query($c,"SELECT `uid` from `users` where `user`='$user'");
	$u=mysqli_fetch_array($res,MYSQLI_ASSOC);
	$uid=$row["uid"];
	mysqli_close($c);
	if($uid>0)
	updatenotification($uid,$message);
}
else
{echo "Password's or Email's Don't match";}
  header('Refresh: 5; URL=./index.php');
?>