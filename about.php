<!DOCTYPE html>
<html>
<head>
<!-- Latest compiled and minified CSS -->
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<title>Computer Dummies</title>
<link rel="stylesheet" href="./css/all.css">
<script src="./js/all.js"></script>
<!-- Latest compiled and minified JavaScript -->
<?php 
		session_start();
	include 'requirement.php';
	?>
</head>
<body>
<nav class="navbar home navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="./index.php">Computer Dummies</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li><a href="./index.php">Home</a></li>
		<li class="active">
			<a href="./about.php">About</a>
		</li>
	  </ul>
      <ul class="nav navbar-nav navbar-right">
	  <?php
	
	
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
		
		?>
		</ul>
    </div>
  </div>
</nav>
<!--ABOUT US-->
<div class="container text-center noback">
<div class="panel panel-body">
<h3>COMPUTER DUMMIES</h3>
<p><em>We Provide a learing expericence</em></p>
<p>Hope you enjoy</p>
  <br>
  <div class="row">
  <div class="col-sm-4"></div>
    <div class="col-sm-4">
	<p class="text-center"><strong>Shubham</strong></p><br>
	<a href="#demo" data-toggle="collapse">
      <img src="./images/shubham.jpg" class="img-circle person" alt="Random Name" width="50" height="50">
	 </a>
	  <div id="demo" class="collapse">
        <p><b>Founder</b></p>
        <p></p>
        <p></p>
      </div>
    </div>
  </div>
</div>
</div>
<!--ABOUT END-->
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
<!-- LOGIN -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Login</h4>
      </div><!--modal header -->
	  <div class="modal-body">
				<form class="form"  action="login.php" method="post">
					<div class="form-group">
					 <label for="usrname"><span class="glyphicon glyphicon-user"></span>USERNAME:</label><input id="user" type="text" class="form-control" name="user">
					</div><!-- form group"-->
					<div class="form-group">
					<label for="pass">PASSWORD:</label><input id="pass" type="password" class="form-control" name="pass">
					</div><!-- form group"-->
				<div class="modal-footer">
				<button class="btn btn-link" data-dismiss="modal" data-toggle="modal" data-target="#forgotpass" type="button"><span ></span>Forgot Password</button>
				<input type="submit" class="btn btn-block dark" value="LOGIN">
				</div><!-- modal footer"-->
		  </form><!-- form "-->
	  </div><!-- modal body"-->
	</div><!-- modal content "-->
</div><!-- modal dialog"-->
</div><!-- modal fade"-->
<!-- FORGOT PASSWORD -->
<div class="modal fade" id="forgotpass" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Forgot Password</h4>
      </div><!--modal header -->
	  <div class="modal-body">
				<form class="form"  action="forgot.php" method="post">
					<div class="form-group">
					<label for="user">EMAIL:</label><input id="user" type="text" class="form-control" name="email">
					</div><!-- form group"-->
				<div class="modal-footer">
				<input type="submit" class="btn btn-block dark" value="RESET">
				</div><!-- modal footer"-->
		  </form><!-- form "-->
	  </div><!-- modal body"-->
	</div><!-- modal content "-->
</div><!-- modal dialog"-->
</div><!-- modal fade"-->
<!--CHANGE Password -->
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
<!-- REGISTER -->
<div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Register</h4>
      </div><!--modal header -->
	  <div class="modal-body">
				<form class="form"  action="register.php" method="post">
				<div class="form-group">
				<label for="name">NAME:</label></td><td><input id="name" type="text" class="form-control" name="name"  pattern="[A-Z][A-Za-z ]+" title="Please Enter  only letters with first character to be in Uppercase">
				</div><!-- NAME form group -->
				<div class="form-group">
				<label for="user">USERNAME:</label></td><td><input id="user" type="text" class="form-control" name="user" pattern="[A-Za-z_][A-Za-z0-9_.]+" title="Please Enter a Valid Username">
				</div><!-- USERNAME form group -->
				<div class="form-group">
				<label for="user">EMAIL:</label></td><td><input id="email" type="text" class="form-control" name="email" pattern="[a-zA-Z0-9._]+@[a-zA-Z]+.[a-zA-Z]+" title="Enter a Valid Email">
				</div><!-- EMAIL form group -->
				<div class="form-group">
				<label for="ccemail">CONFIRM EMAIL:</label></td><td><input id="ccemail" type="text" class="form-control" name="cmail" pattern="[a-zA-Z0-9._]+@[a-zA-Z]+.[a-zA-Z]+" title="Enter a Valid Email">
				</div><!-- CONFIRM EMAIL form group -->
				<div class="modal-footer">
				<p class="help-block"> BY REGISTERING YOU ACCEPT OUR TERM AND CONDITIONS </p>
				<input type="submit" class="btn btn-block dark" value="REGISTER">
				</div><!-- modal footer"-->
		  </form><!-- form "-->
	  </div><!-- modal body"-->
	</div><!-- modal content "-->
</div><!-- modal dialog"-->
</div><!-- modal fade"-->
</html>