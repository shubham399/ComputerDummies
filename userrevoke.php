<?php
include 'requirement.php';
session_start();
if(isset($_SESSION["uid"]))
$auid=$_SESSION["uid"];
else
$auid=0;
$ruid=$_GET["uid"];
$c=connect();
if(isset($_SESSION["type"]))
	$person=$_SESSION["type"];
else
	$person="player";
$invalid=strcmp($person,"admin");
if(!$invalid && $ruid>1)
{
	$player="admin";
	mysqli_query($c,"UPDATE `users` set `acountactive`='0' where `uid`='$ruid'");
	echo "USER BLOCKED";
	$name=mysqli_query($c,"SELECT `name` from `users` where `uid`='$ruid'");
	$row=mysqli_fetch_array($name,MYSQLI_ASSOC);
	$rname=$row["name"];
	$message= "BLOCKED : ".$rname;
	updatenotification($auid,$message);
}
else
{
	echo "You Can't BLOCK this USER";
}
header('Refresh: 2; URL=./admin_index.php?page=user');
?>