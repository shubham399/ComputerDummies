<?php
include 'requirement.php';
$c=connect();
$result=mysqli_query($c,"SELECT `email` from `users` where `type`='admin'");
$email = $_POST['email'];
$message = $_POST['message'];
$recipient = '';
$subject="Contact Form COMPUTER DUMMIES";
//creating message
$content = "New contact form submission \n From:,$email, \n ,$message,";
//sending message
//mail($email,$subject,$message,"From: $from\n");
while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC))){
	$recipient=$row["email"];
sendMail($recipient,"FEEDBACK COMPUTER DUMMIES",$content,"From: donotreply@computerdummies.cf\n");}mysqli_close($c);
echo 'Your message has been sent Click Back to go to home page';
header('Refresh: 5; URL=./index.php');
?>
