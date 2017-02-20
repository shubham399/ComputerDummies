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

</head>
<body>


<nav class="navbar home navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">COMPUTER DUMMIES ADMINISTRATOR PANEL</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php 
		session_start();
	include 'requirement.php';
	
	
	$uid=-1;
	if(!isset($_SESSION['uid']) && !isset($_SESSION['type']))
	{
		?>
        <li><button class="btn btn-link" data-toggle="modal" data-target="#login" type="button"><span class="glyphicon glyphicon-log-in"></span> Login</button></li>
		<?php
		$uid=-1;
	}
	else
	{
		$uid=$_SESSION['uid'];
		if(isset($_SESSION['type']))
		$type=$_SESSION['type'];
	else
		$type="NULL";
		if(strcmp($type,"admin"))
		header('Refresh: 2; URL=./index.php');	
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
<div class="container">
<?php if($uid>0 && !strcmp($type,"admin"))
{
if(!isset($_GET['page']))
$page="home";
else
$page=$_GET['page'];	
$home="";
$addques=""	;
$addcat="";
$view="";
$del="";
$user="";
$in="in";
$modify="";
$c="";
$cat="";
$viewcat="";
$noti="";
$check=strcmp($page,"home");
if(!$check)
	$home="active";
$check=strcmp($page,"addques");
if(!$check)
{	$addques="active";
$c="active";

}$check=strcmp($page,"addcategory");
if(!$check)
{$addcat="active";$cat="active";}
$check=strcmp($page,"viewcategory");
if(!$check)
{$viewcat="active";$cat="active";}
$check=strcmp($page,"viewques");
if(!$check)
{	$view="active";
	$c="active";
}$check=strcmp($page,"delques");
if(!$check)
{$del="active";$c="active";}
$check=strcmp($page,"user");
if(!$check)
	$user="active";
$check=strcmp($page,"modify");
if(!$check)
{	$modify="active";$c="active";}
$check=strcmp($page,"notification");
if(!$check)
	$noti="active";

?>
  <ul class="nav nav-tabs">
    <li class="<?php echo $home;?>"><a href="./admin_index.php?page=home">Home</a></li>
<li class="dropdown <?php echo $cat;?>">
	  <a class="dropdown-toggle" data-toggle="dropdown" href="#">CATEGORY<span class="caret"></span></a>
	  <ul class="dropdown-menu">
	  <li class="<?php echo $addcat;?>"><a  href="./admin_index.php?page=addcategory">ADD</a></li>
	  <li class="<?php echo $viewcat;?>"><a  href="./admin_index.php?page=viewcategory">VIEW</a></li>
	  </ul>
	  </li>
	<li class="dropdown <?php echo $c;?>">
	  <a class="dropdown-toggle" data-toggle="dropdown" href="#">Question
    	<span class="caret"></span></a>
		<ul class="dropdown-menu">
		<li><a  href="./admin_index.php?page=addques">ADD</a></li>
        <li><a href="./admin_index.php?page=viewques">VIEW</a></li>
		<li><a  href="./admin_index.php?page=delques">DELETE</a></li>
		<li><a  href="./admin_index.php?page=modify">MODIFY</a></li>
		</ul>
	</li>		
		<li class="<?php echo $user;?>"><a  href="./admin_index.php?page=user">USERS</a></li>
		<li class="<?php echo $noti;?>"><a  href="./admin_index.php?page=notification">Add Notification</a></li>
  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade <?php echo $in." ".$home;?>">
      <h3>HOME</h3>
      <p>Welcome Admininstrator</p>
	  <?php getadminnotification(); ?>
    </div>
    <div id="menu1" class="tab-pane fade <?php echo $in." ".$addques;?>">
      <h3>Add Question</h3>
      <p><?php addquestionform($uid); ?></p>
    </div>
	 <div id="menucat" class="tab-pane fade <?php echo $in." ".$addcat;?>">
      <h3>Add CATEGORY</h3>
      <p><?php addcategoryform($uid); ?></p>
    </div>
    
    <div id="menu3" class="tab-pane fade <?php echo $in." ".$del;?>">
      <h3>Delete Questions</h3>
	  <p> NOTE: Question Which are not answered by any player can be deleted.</p>
      <p><?php deletequestion($uid); ?></p>
    </div>
	 <div id="menuviewcat" class="tab-pane fade <?php echo $in." ".$viewcat;?>">
	<div class="panel panel-default">
	  <div class="panel-heading">VIEW CATEGORY</div>
	  <div class="panel-body"><?php viewcategory();?></div>
	</div>      
	  </div>
	<div id="modify" class="tab-pane fade <?php echo $in." ".$modify;?>">
      <h3>UPDATE QUESTION</h3>
	  <?php if(!isset($_GET['qqid']))
		  $qqid=0;
	  else
		  $qqid=$_GET['qqid'];
	  echo "<p>Edit Question Here</p>";
	  if($qqid==0)
		  getmodifyqid();
	  else if($qqid>0)
			modifyquestionform($uid,$qqid);
?>
    </div>
	<div id="menu4" class="tab-pane fade <?php echo $in." ".$user;?>">
	  <div class="panel panel-default">
	  <div class="panel panel-heading darkl">USERS	</div>
      <?php $i=1;
			if(num_users()%10!=0)
			$page=( num_users()/10)+1;
			else
					$page=( num_users()/10);
	  $p=0;if(!isset($_GET['pno']))
		  $p=1;
				else $p=$_GET['pno'];
				if($p<1|| $p>$page)
					$p=1;
			
				viewusers($p); ?>
		
    
	<div class="panel-footer">
		  
		  <?php
			
			echo "<ul class=\"pagination\">";
			if($p>1)
		echo 	"<li class=\"previous\"><a href=\"./admin_index.php?page=user&pno=".($p-1)."\">Previous</a></li>";
			while($i<=$page)
			{
				if($i==$p)
				echo  "<li class=\"active\"><a href=\"./admin_index.php?page=user&pno=".$i."\">".$i."</a></li>";
			else
			echo "<li><a href=\"./admin_index.php?page=user&pno=".$i."\">".$i."</a></li>";
				
				$i++;
			}
if($p+1<$page || (num_users()%10==0 && $p <$page))
			echo "<li class=\"next\"><a href=\"./admin_index.php?page=user&pno=".($p+1)."\">Next</a></li>";
			echo "</ul>";
		  ?>
		  </div>
		  </div>
	</div>
	<div id="notification" class="tab-pane fade <?php echo $in." ".$noti;?>">
      <h3>Display Notification</h3>
      <?php addnotidorm($uid); ?>
    </div>
	<div id="menu2" class="tab-pane fade <?php echo $in." ".$view;?>">
	<?php if(!isset($_GET['cid']))
		getcid(); 
else{ 	$cid=$_GET['cid'];
?>

	  <div class="panel panel-default"><div class="panel-heading"><h3>View Question</h3></div>
	<div class="panel-body">
      <p><?php $i=1;
			if(num_questions($cid)%10!=0)
			$page=(num_questions($cid)/10)+1;
			else
					$page=(num_questions($cid)/10);
	  $p=0;if(!isset($_GET['pno']))
		  $p=1;
				else $p=$_GET['pno'];
				if($p<1|| $p>$page)
					$p=1;
				
		  displayallquestion($cid,$p); ?></p></div><div class="panel-footer">
		  
		  <?php
			
			echo "<ul class=\"pagination\">";
			if($p>1)
		echo 	"<li class=\"previous\"><a href=\"./admin_index.php?page=viewques&pno=".($p-1)."&cid=".$cid."\">Previous</a></li>";
			while($i<=$page)
			{
				if($i==$p)
				echo "<li class=\"active\"><a href=\"./admin_index.php?page=viewques&pno=".$i."&cid=".$cid."\">".$i."</a></li>";
			else
			echo "<li><a href=\"./admin_index.php?page=viewques&pno=".$i."&cid=".$cid."\">".$i."</a></li>";
				
				$i++;
			}
if($p+1<$page || (num_questions($cid)%10==0 && $p <$page))
			echo "<li class=\"next\"><a href=\"./admin_index.php?page=viewques&pno=".($p+1)."&cid=".$cid."\">Next</a></li>";
			echo "</ul>";
			echo "<nav class=\"navbar navbar-right\">";
			echo "<a class=\"btn btn-info\" href=\"./admin_index.php?page=viewques\">Change Category</a></nav>"
		  ?>
		  </div></div>
<?php } ?>
    </div>
  </div>
</div>
	<?php }
else{	?>
<p> Login to access Adminstrator Panel</p>
<?php } ?>
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
				<form class="form"  action="alogin.php" method="post">
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
