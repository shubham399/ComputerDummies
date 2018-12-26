<?php
require("./sendgrid-php/sendgrid-php.php");
// require 'vendor/autoload.php';


function connect()
{
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

	$con=mysqli_connect($server, $username, $password, $db) or die("Failed to connect to MySQL: " . mysql_error());
	return $con;
}
function get_qid($uid,$cid)
{
	$c=connect();
	$result = mysqli_query($c,"SELECT `qid` FROM `question` WHERE `qid` not in (select `qid` from `user_question` where `uid`='$uid') and `cid`='$cid' ");
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	mysqli_close($c);
	return $row["qid"];
}
function get_score($rt,$qid)
{
	$c=connect();
	$result = mysqli_query($c,"SELECT `dificulty` FROM `question` WHERE `qid`='$qid'");
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	mysqli_close($c);
	$d=$row["dificulty"];
	if($rt>=0&&$rt<=60)
	$score=100+($rt*$d);
	else
		$score=0;
	return $score;
}
function check_ans($qid,$ans)
{
	$c=connect();
	$result = mysqli_query($c,"SELECT `correct_ans` FROM `question` WHERE `qid`='$qid'");
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	mysqli_close($c);
	$ca=$row["correct_ans"];
	$comp=strcmp($ans,$ca);
	if($comp)
		return false;
	else
		return true;
}
function add_quest($ques,$a,$b,$c,$d,$ca,$df,$cid,$hasimage)
{
	$ques=htmlspecialchars($ques);
	$a=htmlspecialchars($a);
	$b=htmlspecialchars($b);
	$c=htmlspecialchars($c);
	$d=htmlspecialchars($d);
	$ca=htmlspecialchars($ca);
	$df=htmlspecialchars($df);
	$ca=strtoupper($ca);
	$cid=htmlspecialchars($cid);
	if($hasimage>0)
		$hasimage=1;
	else
		$hasimage=0;
if(!(!strcmp('A',$ca)||!strcmp('B',$ca)||!strcmp('C',$ca)||!strcmp('D',$ca)))
{echo "Invalid Correct ans must be A,B,C or D only";exit(0);}
if($df<1||$df>5)
{echo "Invalid difficulty level only 1 to 5";exit(0);}
$con=connect();
mysqli_query($con,"Insert into `question`(`ques`,`A`,`B`,`C`,`D`,`correct_ans`,`dificulty`,`cid`,`hasimage`) values('$ques','$a','$b','$c','$d','$ca','$df','$cid','$hasimage')");
mysqli_close($con);
if($hasimage)
{
$c=connect();
$qid=0;
$result=mysqli_query($c,"SELECT `qid` FROM `question`");
while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC))!=NULL)
$qid=$row["qid"];
mysqli_close($c);
$target_dir = "./images/";
$name=$qid.".jpg";
$name2="temp.jpg";
$target_file = $target_dir . basename($name);
$target_file2 = $target_dir . basename($name2);
rename($target_file2,$target_file);
}
echo "Question added";
}
function add_user($name,$users,$pass,$email)
{
		$name=htmlspecialchars($name);
	$users=htmlspecialchars($users);
	$p=encrypt($pass,$users);
	$email=htmlspecialchars($email);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {echo "Invalid Email";exit(0);}
	$c=connect();
	$query = "SELECT `username` FROM `users` WHERE `username`='$users'";
	$r1 = mysqli_query($c,$query);
	$r2=mysqli_query($c,"SELECT `email` FROM `users` WHERE `email` = '$email'");
	echo "<pre>Debug: $query</pre>\n";
	if ( false===$r1 ) {
  printf("error: %s\n", mysqli_error($c));
}
	if(mysqli_num_rows($r1)==0 && mysqli_num_rows($r2)==0)
	{
	 $result = mysqli_query($c,"insert into `users` (`name`,`username`,`password`,`email`) values ('$name','$users','$p','$email')");echo "<p>User Registered a mail with a new Password hasbeen sent to your Register Mail. Check Your <b>Spam </b>Folder too.</p>";
	echo "<pre>Debug: Create user:</pre>\n";
	if ( false===$result ) {
  printf("error: %s\n", mysqli_error($c));
}
		$from="donotreply@computerdummies.cf";
	$subject="Password for your COMPUTER DUMMIES";
	$message="Welcome, ".$name."\n Thank you for registering with us hope you love solving the Questions. Your new password is \"".$pass."\"(without Quotes) please change your password after you login";
	// echo $message;
	sendMail($email,$subject,$message,"From: $from\n");
	}
else
	echo "Username or email  Exist";
	mysqli_close($c);

}
function login($users,$pass)
{
	$users=htmlspecialchars($users);
	$pass=encrypt($pass,$users);
	$c=connect();
	$r1=mysqli_query($c,"SELECT `password`, `acountactive` from `users` where `username`='$users'");
	$row=mysqli_fetch_array($r1,MYSQLI_ASSOC);
	$active=$row["acountactive"];
	$comp=strcmp($pass,$row["password"]);
	if(!$comp && $active)
		return true;
	else
		return false;
}
function alogin($users,$pass)
{
	$users=htmlspecialchars($users);
	$pass=encrypt($pass,$users);
	$c=connect();
	$r1=mysqli_query($c,"SELECT `password`,`acountactive` from `users` where `username`='$users' and `type`='admin'");
	$row=mysqli_fetch_array($r1,MYSQLI_ASSOC);
	$active=$row["acountactive"];
	$comp=strcmp($pass,$row["password"]);
	if(!$comp && $active)
		return true;
	else
		return false;
}
function update_score($uid,$qid,$score)
{
	$c=connect();
	mysqli_query($c,"UPDATE `users` set `score`=`score`+'$score' where `uid`='$uid'");
	mysqli_query($c,"UPDATE `user_question` set `qscore`='$score' where `uid`='$uid'	 and `qid`='$qid'");
	mysqli_close($c);
}
function questionloaded($uid,$qid)
{
	$c=connect();
	mysqli_query($c,"INSERT into `user_question` (`uid`,`qid`) values ('$uid','$qid')");
	mysqli_close($c);
}
function get_qleader($qid)
{
	$c=connect();
	$result = mysqli_query($c,"SELECT `name`,`qscore` FROM `users`,`user_question` where `users`.`uid`=`user_question`.`uid` and `qid`='$qid' and `acountactive`='1' order by `qscore` DESC");
	$i=0;
	echo "<table class=\"table table-hover\" border=1	 style= width:100%>";
	echo "<tr><th>RANK</th><th>NAME</th><th>SCORE</th></tr>";
	while($i < 5 && ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)))
	{
		$name=$row["name"];
		$qscore=$row["qscore"];
		$i++;

		echo "<tr><td>".$i."</td><td>".$name."</td><td>".$qscore."</td></tr>";
	}
	echo "</table>";
}
function get_leaderboard()
{
	$c=connect();
	$result = mysqli_query($c,"SELECT `name`,`score` FROM `users` where `acountactive`='1' order by `score` DESC");
	$i=0;
	echo "
 <table class=\"table table-bordered table-responsive table-hover\" style= width:100%>";
	echo "<tr><th>RANK</th><th>NAME</th><th>SCORE</th></tr>";
	while($i < 10 && ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)))
	{

		$name=$row["name"];
		$score=$row["score"];
		$i++;
		echo "<tr><td>".$i."</tc><td>".$name."</tc><td>".$score."</td></tr>";
	}
	echo "</table>";

}
function display_question($qid,$cid)
{
	$c=connect();
	$result = mysqli_query($c,"SELECT `ques`,`A`,`B`,`C`,`D`,`dificulty`,`hasimage` FROM `question` where `qid`='$qid'");
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	mysqli_close($c);
	$q=htmlspecialchars_decode(htmlspecialchars_decode($row["ques"]));
	$a=htmlspecialchars_decode(htmlspecialchars_decode($row["A"]));
	$b=htmlspecialchars_decode(htmlspecialchars_decode($row["B"]));
	$c=htmlspecialchars_decode(htmlspecialchars_decode($row["C"]));
	$d=htmlspecialchars_decode(htmlspecialchars_decode($row["D"]));
	$hasimage=$row["hasimage"];
	$dif=$row["dificulty"];
	?>
	<div class="panel panel-default">
	<div class="panel-body">
	<nav class="nav navbar-nav navbar-right">
	<?php echo "<b>Point Earned :".$dif."x</b>" ?>
	</nav>
	<?php
	echo "<h3>".$q."</h3>";
	if($hasimage)
		echo "<div class=\"panel-default\"><div class=\"panel-body\"><img src=\"./images/".$qid.".jpg\" class=\"img-rounded\"  width=\"500\" height=\"400\"></div></div>";
	echo "<form class=\"form-horizontal\" action=\"validate.php\" method=\"post\" id=\"questions\"name=\"ques\">";
	echo "
	<b>Timer</b><input type=\"text\" name=\"time\" value=\"60\">
	<table class=\"table table-bordered\" style= width:100%>
	<tr>
	<td><input type=\"hidden\" name=\"qid\" value=\"".$qid."\"><input type=\"hidden\" name=\"cid\" value=\"".$cid."\">
	<input type=\"radio\" name=\"ans\" value=\"A\" ><em>".$a."</em>
	<input type=\"radio\" name=\"ans\" value=\"E\" checked=\"checked\"style=\"display:none\"></td>
	<td><input type=\"radio\" name=\"ans\" value=\"B\"><em>".$b."</em></td>
	</tr>
	<tr>
	<td><input type=\"radio\" name=\"ans\" value=\"C\"><em>".$c."</em></td>
	<td><input type=\"radio\" name=\"ans\" value=\"D\"><em>".$d."</em></td></tr>
	</table>
	<input type=\"submit\"  class=\"btn btn-info\" value=\"Check Answer\">";
	echo "</form></div></div>";
	echo "<script>

var targetURL=\"./index.php\"
//change the second to start counting down from
var countdownfrom=60
var currentsecond=document.ques.time.value=countdownfrom+1
function countredirect(){
if (currentsecond!=1){
currentsecond-=1
width=0
document.ques.time.value=currentsecond
}
else{
document.getElementById(\"questions\").submit();
return
}
setTimeout(\"countredirect()\",1000)
}

countredirect()
</script>";
	//echo "</div>";

	}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function forgot($email)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT `username` from `users` where `email`='$email'");

	if(mysqli_num_rows($result)==0)
	{
		echo "User Does not Exist";
	}
	else{
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$users=$row["username"];
	$pass=generateRandomString(10);
	$from="donotreply@computerdummies.cf";
	$subject="New password for your COMPUTER DUMMIES";
	$message="Your new password is ".$pass." please change your password after you login";
	sendMail($email,$subject,$message,"From: $from\n");
	$users=htmlspecialchars($users);
	$pass=encrypt($pass,$users);
	mysqli_query($c,"UPDATE `users` set `password`='$pass' where `email`='$email'");
	echo "An Email with a new password is sent to your email ";
	}
	mysqli_close($c);

}
function passwordrest($uid,$op,$np)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT `password`,`username` from `users` where `uid`='$uid'");
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$users=$row['username'];
	$np=encrypt($np,$users);
	$op=encrypt($op,$users);
	$comp=strcmp($op,$row["password"]);
	if($comp)
	{
		echo "Invalid Old Password";
	}
	else
	{
	mysqli_query($c,"UPDATE `users` set `password`='$np' where `uid`='$uid'");
	echo "Password Changed Sucessfully!!";

	}
	echo "PAssssss";
	mysqli_close($c);
}
function encrypt($pass,$users)
{
	if (CRYPT_SHA256 == 1)
	{$salt='$5$rounds=5000'.md5($users);
 $pass=crypt(htmlspecialchars($pass),$salt);
	}
	else
	{
	echo "SHA-256 not supported.";
	exit(0);
	}
	$pass=strrchr($pass,"\$");
	$pass=substr($pass,1);
	return $pass;
}
function rest_questions($uid)
{
$c=connect();
mysqli_query($c,"DELETE FROM `user_question` WHERE `uid`='$uid' and `qscore`=-1");
mysqli_close($c);
}
function diplayans($qid)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT `A`,`B`,`C`,`D`,`correct_ans` from `question` where `qid`='$qid'");
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$ca=$row["correct_ans"];
	$ans=htmlspecialchars_decode(htmlspecialchars_decode($row[$ca]));
	echo "<p>".$ans."</p>";
	mysqli_close($c);
}
function display_score_rank($uid)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT `score` from `users` where `uid`='$uid'");
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$score=$row["score"];
	echo "<table class=\"table\" border=0	 style= width:100%><th><td>Your Score: <b>".$score."</b></td>";
	$result=mysqli_query($c,"SELECT `uid`,`score` from `users` order by `score` DESC");
	$i=1;
	$flag=1;
	while($flag)
	{
		$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$comp=strcmp($row["uid"],$uid);

		if(!$comp)
		{	$flag=0;
	break;
		}
		else
			$i++;
	}
	echo "<td>Your Rank: <b>".$i."</td></th></table>";


}
function addquestionform($uid)
{
	$c=connect();
		echo "<form class=\"form-horizontal\" action=\"addques.php\" method=\"post\" id=\"questions\"name=\"ques\" enctype=\"multipart/form-data\">";
	echo "
	<b>Question</b><input type=\"text\" class=\"form-control\" name=\"ques\" placeholder=\"Enter Your Question Here?\">
	<b>Has Image:</b><input class=\"btn btn-default\" name=\"fileToUpload\" type=\"file\"  id=\"fileToUpload\" />
	<table class=\"table table-bordered\" style= width:100%>
	<tr>
	<td>A: <input type=\"text\" class=\"form-control\" name=\"a\" placeholder=\"option A\" ><input type=\"hidden\" name=\"uid\" value=\"".$uid."\"></td>
	<td>B: <input type=\"text\"  class=\"form-control\" name=\"b\" placeholder=\"option B\"></td>
	</tr>
	<tr>
	<td>C: <input type=\"text\" class=\"form-control\" name=\"c\" placeholder=\"option C\"></td>
	<td>D: <input type=\"text\"  class=\"form-control\" name=\"d\" placeholder=\"option D\"></td></tr>
	<tr>
	<td>Correct Answer: <input type=\"text\" class=\"form-control\" name=\"ca\" placeholder=\"A,B,C or D\" pattern=\"[ABCD]\" title=\"Enter a Option\"></td>
	<td>Dificulty: <input type=\"text\"  class=\"form-control\" name=\"dif\" placeholder=\"1-5\"pattern=\"[1-5]\" title=\"Enter a Valid Dificulty\"></td></tr>
	<tr><td> CATEGORY : <select name=\"cid\" class=\"form-control\">";
	$result=mysqli_query($c,"SELECT * FROM `category`");
	while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC))){
	echo "<option value=\"".$row["cid"]."\">".$row["category"]."</option>";}
     mysqli_close($c);
	echo "</select></td></tr>
	</table><input type=\"submit\"  class=\"btn btn-info\" value=\"Add Question\">";
	echo "</form>";
}
function displayallquestion($cid,$page)
{
	$c=connect();
	if($cid>0)
	$result=mysqli_query($c,"SELECT * FROM `question` where `cid`='$cid' ORDER BY `qid`");
else
	$result=mysqli_query($c,"SELECT * FROM `question`  ORDER BY `qid`");
	echo "
	<table class=\"table table-bordered table-responsive\" style= width:100%>";
	echo "<tr><th>Question ID</th><th>Question</th><th>A</th><th>B</th><th>C</th><th>D</th><th>Correct Answer</th><th>Dificulty</th><th>CATEGORY</th><th>Percentage of Recieving Correct answer</th><th>No of attempts</th><th>Has Image</th></tr>";
	$offset=($page-1)*10;
	$i=0;
	while($i< $offset)
	{$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
$i++;}
$i=0;
	while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC))&&$i<10)
	{
		$i++;
		$cid=$row["cid"];
		$qid=$row["qid"];
		$r2=mysqli_query($c,"SELECT `category` from `category` where `cid`='$cid'");
		$r=mysqli_fetch_array($r2,MYSQLI_ASSOC);
		$totalans=mysqli_query($c,"SELECT `uid`FROM `user_question` where `qid`='$qid'");
		$correctans=mysqli_query($c,"SELECT `uid`FROM `user_question` where `qid`='$qid' and `qscore`>0");
		$tans=mysqli_num_rows($totalans);
		$cans=mysqli_num_rows($correctans);
		$hasimage=$row["hasimage"];
		if($hasimage)
			$hasimage="Yes";
		else
			$hasimage="No";
		if($tans==0)
			$per="N/A";
		else
			$per=round(($cans*100)/$tans,2);
		echo "<tr><td>".$row["qid"]."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["ques"]))."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["A"]))."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["B"]))."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["C"]))."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["D"]))."</td><td>".$row["correct_ans"]."</td><td>".$row["dificulty"]."</td><td>".$r["category"]."</td><td>".$per."</td><td>".$tans."</td><td>".$hasimage."</td></tr>";
	}
		echo "</table>";
}
function addcategoryform($uid)
{
	echo "<form class=\"form-horizontal\" action=\"addcat.php\" method=\"post\" id=\"questions\"name=\"ques\"><input type=\"hidden\" name=\"uid\" value=\"".$uid."\"><input type=\"text\" class=\"form-control\" name=\"cat\" placeholder=\"CATEGORY\" ><input type=\"submit\"  class=\"btn btn-info\" value=\"Add Category\"></form>";


}
function deletequestion($uid)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT * FROM `question` where `qid` not in (select `qid` from `user_question`)");
	echo "<div class=\"panel panel-default\">
	<div class=\"panel-body\">
	<table class=\"table table-bordered\" style= width:100%>";
	echo "<tr><th>Question ID</th><th>Question</th><th>A</th><th>B</th><th>C</th><th>D</th><th>Correct Answer</th><th>Dificulty</th><th>CATEGORY</th></tr>";
	while($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{$qid=$row["qid"];
		$link="<a href=\"./delete.php?qid=".$qid."&uid=".$uid."\">DELETE</a>";
		$cid=$row["cid"];
		$r2=mysqli_query($c,"SELECT `category` from `category` where `cid`='$cid'");
		$r=mysqli_fetch_array($r2,MYSQLI_ASSOC);
		echo "<tr><td>".$row["qid"]."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["ques"]))."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["A"]))."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["B"]))."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["C"]))."</td><td>".htmlspecialchars_decode(htmlspecialchars_decode($row["D"]))."</td><td>".$row["correct_ans"]."</td><td>".$row["dificulty"]."</td><td>".$r["category"]."</td><td>".$link."</td></tr>";
	}
		echo "</table></div></div>";
}
function diplaycat($uid)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT * FROM `category`");
	$i=0;
	echo "<p>Choose a Category to Begin</p>";
	if(mysqli_num_rows($result)!=0)
	{
	echo "<div class=\"panel panel-default\">
	<div class=\"panel-body\">";
	echo "<table class=\"table table-bordered\" style= width:100%><tr>";
	while($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{

		$cid=$row["cid"];
		$cat=$row["category"];
		$flag=0;
		$r2=mysqli_query($c,"SELECT * FROM `category` where `cid` in (SELECT `cid` FROM `question` WHERE `qid` not in (select `qid` from `user_question` where `uid`='$uid'))");
		while($r=mysqli_fetch_array($r2,MYSQLI_ASSOC))
		{
			$comp=strcmp($r["cid"],$cid);
			if(!$comp)
				$flag=1;
		}
		$link="";
		if($flag)
		$link="<a href=\"./start.php?cid=".$cid."\" class=\"btn btn-info btn-block \" role=\"button\">".$cat."</a>";
		else
		$link="<a href=\"./start.php?cid=".$cid."\" class=\"btn btn-primary btn-block disabled\" role=\"button\">".$cat."</a>";

		echo "<td>".$link."</td>";
		$i++;
		if($i%2==0)
			echo "</tr><tr>";

	}
	echo "</tr>";
	echo "</table></div></div>";
	}
?>
<div class="alert alert-info">
  <strong>Info!</strong>Please Provide your Valuable Suggestion to help Us create a better Platform
</div>

<?php
}
function modifyquestionform($uid,$qid)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT * FROM `question` where `qid`='$qid'");
	$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$ques=htmlspecialchars_decode(htmlspecialchars_decode($row["ques"]));
	$A=htmlspecialchars_decode(htmlspecialchars_decode($row["A"]));
	$B=htmlspecialchars_decode(htmlspecialchars_decode($row["B"]));
	$C=htmlspecialchars_decode(htmlspecialchars_decode($row["C"]));
	$D=htmlspecialchars_decode(htmlspecialchars_decode($row["D"]));
	$ca=$row["correct_ans"];
	$dif=$row["dificulty"];
		echo "<form class=\"form-horizontal\" action=\"upques.php\" method=\"post\" id=\"questions\"name=\"ques\">";
	echo "
	<b>Question</b><input type=\"text\" class=\"form-control\" name=\"ques\" value=\"".$ques."\">
	<input type=\"hidden\" name=\"qid\" value=\"".$qid."\">
	<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">
	<table class=\"table table-bordered\" style= width:100%>
	<tr>
	<td>A: <input type=\"text\" class=\"form-control\" name=\"a\" value=\"".$A."\" ></td>
	<td>B: <input type=\"text\" class=\"form-control\" name=\"b\" value=\"".$B."\"></td>
	</tr>
	<tr>
	<td>C: <input type=\"text\"  class=\"form-control\" name=\"c\" value=\"".$C."\"></td>
	<td>D: <input type=\"text\"  class=\"form-control\" name=\"d\" value=\"".$D."\"></td></tr>
	<tr>
	<td>Correct Answer: <input type=\"text\" class=\"form-control\" name=\"ca\" value=\"".$ca."\" disabled=\"disabled\"></td>
	<td>Dificulty: <input type=\"text\" class=\"form-control\" name=\"dif\" value=\"".$dif."\" pattern=\"[1-5]\" title=\"Enter a Valid Dificulty\"></td></tr>
	<tr><td> CATEGORY : <select name=\"cid\" class=\"form-control\">";
	$result=mysqli_query($c,"SELECT * FROM `category`");
	while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC))){
	echo "<option value=\"".$row["cid"]."\">".$row["category"]."</option>";}
     mysqli_close($c);
	echo "</select></td></tr>
	</table><input type=\"submit\"  class=\"btn btn-info\" value=\"Update Question\">";
	echo "</form>";
}
function getmodifyqid()
{
	echo "<form class=\"form-horizontal\" action=\"admin_index.php\" method=\"GET\" id=\"questions\"name=\"ques\">";
	echo "<input type=\"hidden\" name=\"page\" value=\"modify\"><label for=\"qqid\">Enter The Question number to be Edited:</label><input type=\"text\" name=\"qqid\" class=\"form-control\" value=\"0\"><input type=\"submit\"  class=\"btn btn-info\" value=\"Update Question\"></form>";
}
function disprightpercentage($qid)
{
	$c=connect();
	$totalans=mysqli_query($c,"SELECT `uid`FROM `user_question` where `qid`='$qid'");
		$correctans=mysqli_query($c,"SELECT `uid`FROM `user_question` where `qid`='$qid' and `qscore`>0");
		$tans=mysqli_num_rows($totalans);
		$cans=mysqli_num_rows($correctans);
		if($tans==0)
			$per="N/A";
		else
			$per=round(($cans*100)/$tans,2);
		echo "<p>People getting this answer right : <b>".$per." %</b></p>";
		mysqli_close($c);
}
function getadminnotification()
{
	$c=connect();
	$result=mysqli_query($c,"SELECT * From `admin_notification` order by `adnid` DESC");
	$count=mysqli_num_rows($result);
	echo "<div  class=\"col-sm-4\"><div class=\"panel panel-default\"><div class=\"panel-heading\">Notification</div>
	<div class=\"panel-body\">";
	if($count==0)
		echo "No new notification yet";
	else{

		echo "<table>";$i=0;
		while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC))&& $i<5)
		{
			$i++;
			$noti=$row["notification"];
			echo "<tr><td>".$noti."</td><tr>";
		}
	}echo "</table></div></div></div>";
	mysqli_close($c);
}
function updatenotification($uid,$message)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT  `name` FROM `users` WHERE `uid`='$uid'");
	$u=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$user=$u["name"];
	$not=$user." 	 ".$message;
	mysqli_query($c,"INSERT into `admin_notification`(`notification`) values ('$not') ");
	mysqli_close($c);

}
function viewusers($page)
{
	$c=connect();
	$r=mysqli_query($c,"SELECT `uid` ,`name`,`plogincount`,`score`,`type`,`acountactive`,`timestamp` FROM `users` order by `score` DESC");
	$offset=($page-1)*10;
	$i=0;
	$serial=0;
	while($i< $offset)
	{$row=mysqli_fetch_array($r,MYSQLI_ASSOC);
$serial++;
$i++;}
$i=0;
	echo "<div class=\"panel panel-body\"><table class=\"table table-bordered\" style= width:100%><tr><th>Serial No.</th><th>ID</th><th>Name</th><th>No of Question attempted</th><th>Score</th><th>RANK</th><th>Login Count</th><th>Grant/Revoke Privilege</th><th>BLOCK/UNBLOCK USER</th><th>REGISTRATION TIME</th></tr>";

	while(($row=mysqli_fetch_array($r,MYSQLI_ASSOC))&&$i<10)
	{
		$serial++;
		$uid=$row["uid"];
		$rankquery=mysqli_query($c,"SELECT `uid`,`score` from `users` where `acountactive`='1' order by `score` DESC");
		$rank=1;
		$flag=1;
		while(($rankf=mysqli_fetch_array($rankquery,MYSQLI_ASSOC))!=NULL)
		{

			$comp=strcmp($rankf["uid"],$uid);
			if(!$comp)
			{
				$flag=0;
				break;
			}
			else
			$rank++;
		}
		if($flag)
		$rank="N/A";
		$i++;
		$name=$row["name"];
		$lc=$row["plogincount"];
		$score=$row["score"];
		$account=$row["acountactive"];
		$ques=mysqli_query($c,"SELECT `qid` from `user_question` where `uid`='$uid'");
		$nq=mysqli_num_rows($ques);
		if($account)
		$alink="<a href=\"userrevoke.php?uid=".$uid."\">BLOCK USER</a>";
		else
		$alink="<a href=\"retrive.php?uid=".$uid."\">UNBLOCK USER</a>";
		$link="<a href=\"grant.php?uid=".$uid."\">Grant Admin Privilege</a>";
		$type=$row["type"];
		$comp=strcmp($type,"admin");
		$time=$row["timestamp"];
		$t=strtotime($time);
if($t==-62169962400)
$time="Not Avialable";
		if(!$comp && $uid>1)
		{
			$link="<a href=\"revoke.php?uid=".$uid."\">Revoke Admin Privilege</a>";

		echo "<tr><td>".$serial."</td><td>".$uid."</td><td>".$name."</td><td>".$nq."</td><td>".$score."</td><td>".$rank."</td><td>".$lc."</td><td>".$link."</td><td>".$alink."</td><td>".$time."</td></tr>";
		}else
		{
			if($uid>1)
			echo "<tr><td>".$serial."</td><td>".$uid."</td><td>".$name."</td><td>".$nq."</td><td>".$score."</td><td>".$rank."</td><td>".$lc."</td><td>".$link."</td><td>".$alink."</td><td>".$time."</td></tr>";
			else
			echo "<tr><td>".$serial."</td><td>".$uid."</td><td>".$name."</td><td>".$nq."</td><td>".$score."</td><td>".$rank."</td><td>".$lc."</td><td>Can't Change Admin Privilege Here</td><td>Can't BLOCK</td><td>".$time."</td></tr>";
		}
	}

	echo "</table></div>";
	mysqli_close($c);

}
function displayplayernotification()
{
	$c=connect();
	$i=0;
	$result=mysqli_query($c,"SELECT * From `pnotification` order by `pnid` DESC");
	$count=mysqli_num_rows($result);
	echo "<div class=\"panel panel-default\"> <div class=\"panel-body\"><table class=\"table-responsive	\"><tr><th>Notification</th></tr>";
	if($count==0)
		echo "No Notification yet";
	else{

	while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC))&& $i<10)
		{
			$i++;
			$noti=$row["notification"];
			echo "<tr><td>".$noti."</td></tr>";
		}
	}
	echo "</table></div></div>";

}
function notificationupdate($uid,$message)
{
$c=connect();
	$result=mysqli_query($c,"SELECT  `name` FROM `users` WHERE `uid`='$uid'");
	$u=mysqli_fetch_array($result,MYSQLI_ASSOC);
	$user=$u["name"];
	$not=$user." 	 ".$message;
	mysqli_query($c,"INSERT into `pnotification`(`notification`) values ('$not') ");
	mysqli_close($c);

}
function addnotification($message)
{
	$c=connect();
	mysqli_query($c,"INSERT into `pnotification`(`notification`) values ('$message') ");
	mysqli_close($c);
}
function addnotidorm($uid)
{
	echo "<div class=\"panel panel-default\"><div class=\"panel-heading\">Send Notification:</div><div class=\"panel-body\">";
	echo "<form class=\"form-horizontal\" action=\"addnoti.php\" method=\"POST\" id=\"questions\"name=\"ques\">";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$uid."\">";
	echo "<input type=\"text\" class=\"form-control\" name=\"m\">";
	echo "<input type=\"submit\"  class=\"btn btn-info\" value=\"Send Notification\">";
	echo "</form>";
	echo "</div></div>";
}
function num_questions($cid)
{
	$c=connect();
	if($cid>0)
	$r=mysqli_query($c,"SELECT * FROM `question` WHERE `cid`='$cid'");
else
	$r=mysqli_query($c,"SELECT * FROM `question`");
	$n=mysqli_num_rows($r);
	return $n;
}
function canrate($uid)
{
	$c=connect();
	$result=mysqli_query($c,"SELECT * From `tbl_rating` where `user_id`='$uid'");
	$count=mysqli_num_rows($result);
	mysqli_close($c);
	if($count==0)
		return true;
	else
	return false;
}
function viewcategory()
{
	$c=connect();
	$result=mysqli_query($c,"SELECT * FROM `category`");
	echo "<table class=\"table table-bordered\" style= width:100%>
	<tr><th>ID</th><th>NAME</th><th>Number of attempts</th><th>Questions Avilable</th></tr>
	";
	$sumnq=0;
	$sumnqa=0;
	while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC)))
	{
	$cid=$row["cid"];
	$name=$row["category"];
	$questionatempted=mysqli_query($c,"select `uid` from `user_question` where `qid` in (select `qid` from `question` where `cid`='$cid')");
	$nqa=mysqli_num_rows($questionatempted);
	$sumnqa+=$nqa;
	$numq=mysqli_query($c,"SELECT `qid` from `question` where `cid`='$cid'");
	$nq=mysqli_num_rows($numq);
	$sumnq+=$nq;
	echo "<tr><td>".$cid."</td><td>".$name."</td><td>".$nqa."</td><td>".$nq."</td></tr>";
	}
	echo "<tr><td>-</td><td><b>Total:</></td><td><b>".$sumnqa."</b></td><td><b>".$sumnq."</b></td></tr>";
	echo "</table>";
	mysqli_close($c);
}
function getcid()
{
	$c=connect();
	$result=mysqli_query($c,"SELECT * FROM `category` order by `category`");
	echo "<div class=\"panel panel-default\"><div class=\"panel-heading\"><h3>Choose Category</h3></div>
	<div class=\"panel-body\"><form class=\"form-horizontal\" action=\"admin_index.php\" method=\"GET\" id=\"questions\"name=\"cid\"><select name=\"cid\" class=\"form-control\">";
	echo "<option value=\"0\">ALL</option>";
	while(($row=mysqli_fetch_array($result,MYSQLI_ASSOC)))
	{
		$cid=$row["cid"];
		$name=$row["category"];
		echo "<option value=\"".$cid."\">".$name."</option>";
	}
	echo "</select><input type=\"hidden\" name=\"page\" value=\"viewques\"><input type=\"submit\"  class=\"btn btn-info\" value=\"Display Questions\"></form></div></div>";
}
function displayrating()
{
	$c=connect();
	$res=mysqli_query($c,"SELECT `rate` from `tbl_rating`");
	$n=mysqli_num_rows($res);
	$s=0;
	while(($row=mysqli_fetch_array($res,MYSQLI_ASSOC)))
	{
		$r=$row["rate"];
		$s+=$r;
	}
	if($n>0)
	$s/=$n;
	$s=round($s,2);
	echo "<p>We got an average of ".$s." star(s) rating from ".$n." users </p>";
	mysqli_close($c);
}
function num_users()
{
	$c=connect();
	$r=mysqli_query($c,"SELECT * FROM `users`");
	$n=mysqli_num_rows($r);
	return $n;
}

function sendMail($f,$t,$subject,$msg)
{
	$email = new \SendGrid\Mail\Mail(); 
$email->setFrom($f);
$email->setSubject($subject);
$email->addTo($t);
$email->addContent(
    "text/html", $msg
);
$sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}
}

?>
