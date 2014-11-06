<?php 
	require_once 'dbop.php';
	require_once 'parameter.config.php';
	session_start();
	$mydb = new mydb();
	$username = $_POST['staffID'];
	$password = $_POST['password'];
	$sql = "select user_pwd, staffID from tt_user where user_alias='{$username}'";
	$temp = $mydb->query($sql);
	if(!$temp||count($temp)==0){
		$_SESSION['ERROR_NUM'] = USER_NOT_EXIST_ERROR; 
		header("Location: ./login.php");
	}
	else{
		$pwd = $temp[0][0];
		if($password!=$pwd){
			$_SESSION['ERROR_NUM'] = PASSWORD_ERROR; 
			echo $pwd."<br>";
			echo $password;
			header("Location: ./login.php");
		}
		else{
			
			$_SESSION['STAFFID'] = $temp[0][1];

			header("Location: ./index.php");
			
		}
	}

?>