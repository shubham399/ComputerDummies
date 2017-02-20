<?php
include 'requirement.php';
session_start();
$uid=$_SESSION["uid"];
rest_questions($uid);
session_unset(); 
session_destroy(); 
echo "Please wait while we log you out... We will redirect you to your home page after you are loged out!!!";
$message=" Logged Out :SYSTEM";
if($uid>0)
 updatenotification($uid,$message);
header('Refresh: 2; URL=./index.php');
?>