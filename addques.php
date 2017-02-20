<?php
include 'requirement.php';
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   $data = htmlspecialchars($data,ENT_QUOTES);
   return $data;
}
$ques=test_input($_POST['ques']);
$a=test_input($_POST['a']);
$b=test_input($_POST['b']);
$c=test_input($_POST['c']);
$d=test_input($_POST['d']);
$ca=test_input($_POST['ca']);
$df=test_input($_POST['dif']);
$cid=test_input($_POST['cid']);
$hasimage=0;
$target_dir = "./images/";
$name="temp.jpg";
$target_file = $target_dir . basename($name);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if($_FILES["fileToUpload"]["name"]!=NULL) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg") {
    echo "Sorry, only JPG files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		$hasimage=1;
		
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
}add_quest($ques,$a,$b,$c,$d,$ca,$df,$cid,$hasimage);
$uid=$_POST['uid'];
$message=" added a question";if($uid>0) updatenotification($uid,$message);
header('Refresh: 2; URL=./admin_index.php?page=addques');
?>