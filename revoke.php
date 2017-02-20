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
	$person="invalid";
$invalid=strcmp($person,"admin");
if(!$invalid && $ruid>1)
{
	$player="player";
	mysqli_query($c,"UPDATE `users` set `type`='$player' where `uid`='$ruid'");
	echo "Admin Privilege Revoked ";
	$name=mysqli_query($c,"SELECT `name` from `users` where `uid`='$ruid'");
	$row=mysqli_fetch_array($name,MYSQLI_ASSOC);
	$rname=$row["name"];
	$message= "Revoked admin Privilege from ".$rname;
	updatenotification($auid,$message);
}
else
{
	echo "You Can't Revoke admin Privilege from this user or you don't have Privilege to revoke them";
}
header('Refresh: 2; URL=./admin_index.php?page=user');
?>