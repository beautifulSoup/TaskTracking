<?php
	header("Content-type: text/html; charset=utf-8");
	
	
	
	function sessionVerify(){
		if(!isset($_SESSION['user_agent'])){
			$_SESSION['user_agent'] = md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
		}
		else if($_SESSION['user_agent']!=md5($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'])){
			session_regenerate_id();
		}
	}
	
	session_start();
	sessionVerify();
	
	if(!isset($_SESSION['STAFFID'])){
		header("Location: login.php");
	}
	require_once 'dbop.php';
	$mydb = new mydb();
	
	function isAdmin(){
		global $mydb;
		$staffID = $_SESSION['STAFFID'];
		$sql = "select * from tt_user where staffID = '{$staffID}'";
		$ret = $mydb->query($sql);
		if(!$ret){
			return false;
		}
		else if($ret[0][6]!=1){
			return false;
		}
		else
			return true;
	}

	$userID = $_SESSION['STAFFID'];
	switch($_GET['type']){
		case 0:{  //修改密码
			$oldPassword = $_POST['oldPassword'];
			$sql = "select user_pwd from tt_user where	staffID = '{$userID}'";
			$results = $mydb->query($sql);
			if(!$results){
				echo "Database query failed!";
			}
			else{
				$password = $results[0][0];
				if($oldPassword != $password){
					echo "The old password is not correct!";
				}
				else{
					$newPassword = $_POST['newPassword'];
					$sql = "update tt_user set user_pwd = '{$newPassword}' where staffID = '{$userID}'";
					$ret = $mydb->update($sql);
					if($ret){
						echo "Change the password succeed!";
					}
					else{
						echo "Change the password failed!";
					}
				}
			}

			break;	
		}
		case 1:{  //修改用户名
			$newAlias = $_POST['newAlias'];
			$sql = "update tt_user set user_alias = '{$newAlias}' where user_alias = '{$userName}'";
			$ret = $mydb->update($sql);
			if(!$ret){
				echo "Change the alias failed! Someone else may already have the alias!";
			}
			else{
				echo "Change the alias succeed!";
				$_SESSION['USERNAME'] = $newAlias;
			}
			break;
		}
		
		case 2:{  //添加账号
			if(!isAdmin()){
				header('Location: ./login.php');
			}
			$userName = $_POST['alias'];
			$userPassword = $_POST['password'];
			$userDepartment = $_POST['department'];
			$userLeader = $_POST['leader'];
			$isLeader = $_POST['isLeader'];
			$staffID = $_POST['staffID'];
			$sql = "insert into tt_user(user_pwd, user_alias, leader_id, user_department, is_leader, staffID) values('{$userPassword}', '{$userName}', {$userLeader}, '{$userDepartment}', {$isLeader}, {$staffID})";
			$ret1 = $mydb->insert($sql);
			$sql = "select user_id from tt_user where user_alias = '{$userName}'";
			$results = $mydb->query($sql);
			$staffID = $results[0][0];
			$sql = "insert into staff_inf(id) values({$staffID})";
			$ret2 = $mydb->insert($sql);  //创建信息表中记录
			if($ret1&&$ret2){
				echo "New user account create succeed!";
			}
			else{
				echo "New user account create failed! You may change the user name!";
			}
			
			break;
		}
		
		case 3:{  //修改账号
			if(!isAdmin()){
				header('Location: ./login.php');
			}
			$userID = $_GET['id'];
			$userName = $_POST['alias'];


			$userPassword = $_POST['password'];
			$userDepartment = $_POST['department'];
			$userLeader = $_POST['leader'];
			$isLeader = $_POST['isLeader'];
			$staffID = $_POST['staffID'];
			$sql = "update tt_user set user_alias = '{$userName}',user_pwd = '{$userPassword}', user_department = '{$userDepartment}', leader_id = {$userLeader}, is_leader = {$isLeader}, staffID = {$staffID} where user_id = {$userID}"; 
			$ret = $mydb->update($sql);
			if($ret){
				echo "Account update succees!";
			}
			else{
				echo "Account update failed!";
			}
			break;
		}

		case 4:{
			if(!isAdmin()){
				header('Location: ./login.php');
			}
			$userID = $_GET['id'];
			$sql = "delete from tt_user where user_id = '{$userID}'";
			$ret = $mydb->delete($sql);
			if($ret){
				echo "New user account delete succeed!";
			}
			else{
				echo "New user account delete failed!";
			}
			break;
			
		}
	}