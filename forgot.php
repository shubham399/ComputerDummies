<?php 
include 'requirement.php';
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
$email=test_input($_POST['email']);
forgot($email);
header('Refresh: 2; URL=./index.php');
?>