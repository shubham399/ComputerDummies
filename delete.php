<?php
$qid=$_GET["qid"];
session_start();
	include 'requirement.php';
	
	
	$uid=-1;
	if(!isset($_SESSION['uid']) && !isset($_SESSION['type']))
	{
		echo "";
		}
	else
	{
	$comp=strcmp("admin",$_SESSION['type']);
	if($comp)
		echo "";
	else
	{
		$c=connect();
		$result=mysqli_query($c,"select `qid` from `user_question` where `qid`='$qid'");
		if(mysqli_num_rows($result)==0){
				$result=mysqli_query($c,"SELECT `hasimage` from `question` where `qid`='$qid'");
				$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
				$hasimage=$row["hasimage"];
				if($hasimage)
				{
					$file="./images/".$qid.".jpg";
					if (!unlink($file))
  {
  echo ("Error deleting $file");
	exit(0);
 }

}
		mysqli_query($c,"DELETE from `question` where `qid`='$qid'");echo "Question Deleted";}else{echo "Question Cannot be Deleted";}
		mysqli_close($c);
			$uid=$_SESSION['uid'];
		$message=" deleted a question";
		updatenotification($uid,$message);

	}
	}
	header('Refresh: 2; URL=./admin_index.php?page=delques');
?>