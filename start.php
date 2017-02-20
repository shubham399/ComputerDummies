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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<title>Computer Dummies </title>
<style>

		body{background-image: url("back.jpg");
  background-repeat: no-repeat;}
    footer{background-image: url("back.jpg");
  background-repeat: no-repeat;}
  panel-body{background-image: url("back.jpg");
  background-repeat: no-repeat;}
</style>  
</head>
<body>
<nav class="navbar home navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="./index.php">COMPUTER DUMMIES</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="./index.php">Home</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php 
		session_start();
	include 'requirement.php';
$cid=$_GET["cid"];	
	
	$uid=-1;
	if(!isset($_SESSION['uid']))
	{
		?>
		<li><button class="btn btn-link" data-toggle="modal" data-target="#register" type="button"><span class="glyphicon glyphicon-user"></span>Sign Up</button></li>
        <li><button class="btn btn-link" data-toggle="modal" data-target="#login" type="button"><span class="glyphicon glyphicon-log-in"></span> Login</button></li>
		<?php
		$uid=-1;
	}
	else
	{
		$uid=$_SESSION['uid'];
		$c=connect();
		$result=mysqli_query($c,"SELECT `name` from `users` where `uid`='$uid'");
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$name=$row["name"];
		mysqli_close($c);
		?>
		<li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#">Welcome <?php echo $name; ?>
          <span class="caret"></span></a>
		 <ul class="dropdown-menu">
            <li><button class="btn btn-link" data-toggle="modal" data-target="#cpassword" type="button">Change Password</button></li>
            <!--<li><a href="#">Page 1-2</a></li>
            <li><a href="#">Page 1-3</a></li> -->
          </ul>
        </li>
		<?php
		echo "<li><a href=\"./logout.php\"><span class=\"glyphicon glyphicon-log-out\"></span> Logout</a></li>";
	}
		$qid=get_qid($uid,$cid);
	?>
		
      </ul>
    </div>
  </div>
</nav>
<div class="container">
<div class ="row">

<?php
if(!$qid)
	{
		echo "<div class=\"col-sm-4\">";
		echo "<h3>Hurray! You Attemted All The Question Avilable in this Category Try Other Categories</h3>";
		$c=connect();
		$result=mysqli_query($c,"SELECT * FROM `category` where `cid`='$cid'");
		$fly=mysqli_num_rows($result);
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$cname=$row["category"];
		$message=" Solved ".$cname." Questions :SYSTEM";if($uid>0 && $fly && !$qid && $cid>0)
		notificationupdate($uid,$message);
		mysqli_close($c);
		?><a href="./index.php">Click Here</a>
		<div class="alert alert-info">
  <strong>Info!</strong>Please Provide your Valuable Suggestion to help Us create a better Platform 
</div>
		<?php 
	}
	else if($uid==-1)
	{
		echo "<div class=\"col-sm-4\">";
		header('Refresh: 1; URL=./index.php');	
	}
	else if($uid>0){
		echo "<div class=\"col-sm-6\">";
	questionloaded($uid,$qid);
	display_question($qid,$cid);
	
	}
?>
</div>
<div class="col-sm-4"></div>
<nav class="navbar navbar-right">
<div class="panel panel-default">
<div class="panel panel-body">

<div class="col-sm-4">
<?php if($uid>0)
display_score_rank($uid,$cid);
?>
<h3>LEADERBOARD</h3>
<?php
get_leaderboard();
?>
</div></div></div>
</nav>
</div>
</div>
</body>
<footer>
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


<!--Change Password-->
<div class="modal fade" id="cpassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Change Password</h4>
      </div><!--modal header -->
	  <div class="modal-body">
				<form class="form"  action="cpass.php" method="post">
					<div class="form-group">
					<label for="user">Old Password:</label><input id="user" type="password" class="form-control" name="op">
					</div><!-- form group"-->
					<div class="form-group">
					<label for="user">New  Password:</label><input id="user" type="password" class="form-control" name="np">
					</div><!-- form group"-->
					<div class="form-group">
					<label for="user"> Confirm New Password:</label><input id="user" type="password" class="form-control" name="cnp">
					</div><!-- form group"-->
				<div class="modal-footer">
				<input type="submit" class="btn btn-block dark" value="Change">
				</div><!-- modal footer"-->
		  </form><!-- form "-->
	  </div><!-- modal body"-->
	</div><!-- modal content "-->
</div><!-- modal dialog"-->
</div><!-- modal fade"-->	
</html>