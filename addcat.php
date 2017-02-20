<?php
include 'requirement.php';
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
$cat=test_input($_POST['cat']);
$c=connect();
mysqli_query($c,"INSERT into `category` (`category`) values ('$cat')");
echo "Category Added";
mysqli_close($c);
$uid=$_POST['uid'];
$message=" added a category";if($uid>0)
 updatenotification($uid,$message);
header('Refresh: 1; URL=./admin_index.php?page=addcategory');
?>