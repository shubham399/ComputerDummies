<?php
session_start();
include 'requirement.php';
$uid=-1;
	if(!isset($_SESSION['uid']))
	{
		header('Refresh: 1; URL=./index.php');
	}
	else
	$uid=$_SESSION['uid'];
$qid=$_POST["qid"];
$time=$_POST["time"];
$ans=$_POST["ans"];
$cid=$_POST["cid"];
$score=0;
$c=connect();
		$result=mysqli_query($c,"SELECT `name` from `users` where `uid`='$uid'");
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$name=$row["name"];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script><title>Computer Dumies </title>
</head>
<body>
<div class="container">
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">COMPUTER DUMMIES</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="./index.php">Home</a></li>
      </ul>
	  <ul class="nav navbar-nav navbar-right">
	  <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Welcome <?php echo $name; ?>
          <span class="caret"></span></a>
		 <ul class="dropdown-menu">
         </ul>
        </li>
		<li><a href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
	  </ul>
	  </div>
  </div>
</nav>
<?php
$r1 = mysqli_query($c,"SELECT `uid` FROM `user_question` WHERE `uid`='$uid' and `qid`='$qid' and `qscore`=-1");
mysqli_close($c);
$count=mysqli_num_rows($r1);
?>
<div class="row">
<div class="col-sm-4">
<?php
if(check_ans($qid,$ans))
{
	$score=get_score($time,$qid);
	echo "<h3>Hurray You Got It Right !!!!</h3>";
	echo "<p> You Earn: ".$score." points </p>";
}
else
{	echo "<h3>Ohh Sorry! Wrong Answer</h3>";
echo "<b>Correct Answer was:</b>";
diplayans($qid);
}
if($count==1)
{
update_score($uid,$qid,$score);
}
disprightpercentage($qid);
?>
<div class="alert alert-info">
  <strong>Info!</strong>Please Provide your Valuable Suggestion to help Us create a better Platform 
</div>
</div><!-- col sm 4 1-->
<div class="col-sm-4">
</div><!-- col sm 4 2-->
<nav class="navbar navbar-right">
<div class="panel panel-default">
<div class="panel panel-body">

<div class="col-sm-4">
<h3>QUESTION LEADER</h3>
<?php get_qleader($qid); ?>
</div><!-- col sm 4 3-->
</div>
</div>
</div>
</nav>
</div><!-- row -->
<?php $link="./start.php?cid=".$cid;
?>
<a href="<?php echo $link;?>" class="btn btn-info" role="button">Next Question</a>
</div>
</body>
<footer>
<div class="container">
<nav class="navbar">
<div class="container-fluid">
<p>Copyright 2016 by COMPUTER DUMMIES. All Rights Reserved.
</p> 
	<nav class="navbar navbar-right">
				<label>Contact US</label>
				<button class="btn btn-info btn-md" data-toggle="modal" data-target="#contatus" type="button"><span class="glyphicon glyphicon-envelope"></span></button>
		</nav><!--nav right -->
</div><!-- container fluid -->
</nav><!-- navbar -->
</div><!--container -->	
</footer><!--footer -->
<!--CONTACT US -->
<div class="modal fade" id="contatus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Contact Us</h4>
      </div><!--modal header -->
	  <div class="modal-body">
				<form action="postmail.php" method="post">
					<div class="form-group">
					<label for="recipient-name" class="control-label"> Your Email:</label>
					<input type="email" class="form-control" id="recipient-name" name="email">
					</div><!-- form group"-->
					<div class="form-group">
					<label for="message-text" class="control-label">Message:</label>
					<textarea class="form-control" id="message-text" name="message"></textarea>
				
				</div><!-- form group"-->
				<input type="submit" class="btn btn-block dark" value="Send message">
				</form><!-- form "-->
				</div><!-- modal body"-->
				<div class="modal-footer">
				<button type="button" class="btn pull-left dark" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
				
				</div><!-- modal footer"-->
		  
	  
	</div><!-- modal content "-->
</div><!-- modal dialog"-->
</div><!-- modal fade"-->
</html>