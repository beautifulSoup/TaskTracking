<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">

    <!-- Loading Flat UI -->

<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">
<script src="./static/dist/js/vendor/jquery.min.js"></script>
<script src="./static/dist/js/flat-ui.min.js"></script>


<style type="text/css">
.login-form {
  position: relative;
  padding: 24px 23px 20px;
  background-color: #edeff1;
  border-radius: 6px;
  }
  
</style>
<title>Task Tracking</title>

<script type="text/javascript" src="./static/js/md5.js"></script>
<script type="text/javascript">
	window.onload = function(){
		var day = new Date();
		day = day.getDay();
		var img = ['monday.jpg', 'tuesday.jpg', 'wednesday.jpg', 'thursday.jpg', 'friday.jpg', 'saturday.jpg', 'sunday.jpg'];
		var path = './static/img/'+img[day-1];
		document.getElementById('imgContainer').src = path;
		
	}
	function login(){
		var userText = document.getElementById('username');
		var passwordText = document.getElementById('password');
		var username = userText.value;
		var password = passwordText.value;
		if(username==""){
			alert("Please input the staff id!");
			return false;
		}
		else if(password==""){
			alert("Please input the password!");
			return false;
		}
		passwordText.value = faultylabs.MD5(password);
		return true;
		
	}
</script>
</head>
<body style="width: 100%">
<div class ="container" >
<div style="margin-left:50px" class="col-xs-14">
<div style="height:200px" class="row">
<img style="float:left" src="./static/img/title.jpg">

</div>
<div class="row">
<div class="col-xs-4">
<FORM class="login-form" method="post" action="./loginResult.php"  onsubmit="return login();">
	
	<img style="margin-left:100px" src="./static/img/elephant.png"></img>
	<hr>
	<?php 
	require_once 'parameter.config.php';
	session_start();
	if(isset($_SESSION['ERROR_NUM'])){
		$errorNum = $_SESSION['ERROR_NUM'];
		if($errorNum == USER_NOT_EXIST_ERROR){
			echo "<span style='color:#EE0000'>Account not exists!</span><br>";
			$_SESSION['ERROR_NUM']=NO_ERROR;
		}
		else if($errorNum == PASSWORD_ERROR){
			echo "<span style='font-size:14px;color:#EE0000'>Password not correct</span><br>";
			$_SESSION['ERROR_NUM']=NO_ERROR;
		}
	}
			
	?>
	<input style="margin: 10px 0" type="text" id = "username" name="staffID" placeholder="Username" class="form-control"></input><br>
	<input  type="password" id = "password" name="password" placeholder="Password" class="form-control"></input>
	<input  style="margin: 20px 0" type="submit" value="Log in" class="btn btn-lg btn-primary btn-block"></input>
</FORM>
</div>
<div class="col-xs-5">
 <img  id="imgContainer" src="#"></img>
</div>
</div>
</div>
</div>
<div class="row" style="height:220px"></div>
<footer>
<div style="height: 100px;" class="container">
<div class="row">
<div style="text-align:center;" class="col-xs-12 center">
<span>Copyright  2014 XiangTaoHuiSoft</span>
</div>
</div>
</div>
</footer>
</body>
</html>