<?php
include 'requirement.php';
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
$con=connect();
$uid=$_POST['uid'];
$qid=test_input($_POST['qid']);
$ques=test_input($_POST['ques']);
$a=test_input($_POST['a']);
$b=test_input($_POST['b']);
$c=test_input($_POST['c']);
$d=test_input($_POST['d']);
$df=test_input($_POST['dif']);
$cid=test_input($_POST['cid']);
mysqli_query($con,"UPDATE `question` SET `ques`='$ques',`A`='$a',`B`='$b',`C`='$c',`D`='$d',`dificulty`='$df',`cid`='$cid' where `qid`='$qid' ");
mysqli_close($con);
echo "Question UPDATED";
$message=" updated a question";
if($uid>0)
 updatenotification($uid,$message);
header('Refresh: 2; URL=./admin_index.php?page=modify&qqid=0');
?>