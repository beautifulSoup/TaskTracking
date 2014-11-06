<?php
	$report = null;
	header("Content-type: text/html; charset=utf-8");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
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
	require_once 'parameter.config.php';
	$type = $_GET['type'];
	$mydb = new mydb();
	if($type==0){  //daily report view
		$id = $_GET['id'];
		$sql = "select * from tt_daily_report where daily_id = {$id}";
		$results = $mydb->query($sql);
		if($results){
			$temp = $results[0];
			$status = "";
			switch($temp[DAILY_STATUS]){
				case STATUS_PENDING:{
					$status = TEXT_PENDING;
					break;
				}
				case STATUS_SUBMITTED:{
					$status = TEXT_SUBMITTED;
					break;
				}
				case STATUS_DENY:{
					$status = TEXT_DENY;
					break;
				}
			}
			$content = "<table class='table table-bordered' border='1' cellspacing='0' cellpadding='2px'><tr><td>Date:</td><td>".$temp[DAILY_DATE]."</td><td>Project:</td><td>".$temp[DAILY_PROJECT]."</td><td>Roles:</td><td>".$temp[DAILY_ROLES]."</td></tr><tr><td>Tasks:</td><td>".$temp[DAILY_TASKS]."</td><td>Hours:</td><td>".$temp[DAILY_HOURS]."</td><td>Status:</td><td>".$status."</td></tr><tr height='100px'><td valign='top'>Description:</td><td colspan=5 valign='top'>".$temp[DAILY_DESCRIPTION]."</td></tr></table>";
			echo $content;
		}
	}
	else{  //monthly report view
	
		$id = $_GET['id'];
		$sql = "select * from tt_month_report where month_id = {$id}";
		$results = $mydb->query($sql);
		if($results){
			$temp = $results[0];
			$tempDate = date("Y-m",strtotime($temp[MONTH_DATE]));
			$status = "";
			switch($temp[MONTH_STATUS]){
				case STATUS_PENDING:{
					$status = TEXT_PENDING;
					break;
				}
				case STATUS_SUBMITTED:{
					$status = TEXT_SUBMITTED;
					break;
				}
				case STATUS_DENY:{
					$status = TEXT_DENY;
					break;
				}
			}
			$content = "<table class='table table-bordered' border='1' cellspacing='0' cellpadding='2px'><tr><td>Date:</td><td>".$tempDate."</td><td>Tasks:</td><td>".$temp[MONTH_TASK]."</td><td>Status:</td><td>".$status."</td></tr><tr><td>Hours:</td><td>".$temp[MONTH_HOURS]."</td><td>Verified　Hours</td><td colspan=3>".$temp[VERIFIED_HOURS]."</tr><tr height='100px'><td valign='top'>Description:</td><td colspan=5 valign='top'>".$temp[MONTH_DESCRIPTION]."</td></tr></table>";
			echo $content;
		}
	}
	