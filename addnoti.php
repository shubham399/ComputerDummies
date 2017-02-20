<?php
include 'requirement.php';
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
$uid=test_input($_POST['uid']);
$m=test_input($_POST['m']);
$m=$m." :ADMINISTRATOR";
$message=" sent a notification :SYSTEM";
if($uid>0)
 updatenotification($uid,$message);
 addnotification($m);
 echo "Your Notification is sent";
header('Refresh: 2; URL=./admin_index.php?page=notification'); 
?>