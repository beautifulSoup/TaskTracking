
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
?>
<?php
	require_once 'dbop.php';
	header("Content-type: text/html; charset=utf-8");
	require_once 'parameter.config.php';
	$mydb = new mydb();

	if($_GET['type']==0){  //表示插入日报
		$userID = $_SESSION['STAFFID'];
		$sql = "select user_id from tt_user where staffID = '{$userID}'";
		$results = $mydb->query($sql);
		if(!$results){
			echo 'There is no user having this name!';
		}
		else{
			$authorID = $results[0][0];
		}
		$task = $_POST['task'];
		$project = $_POST['project'];
		$role = $_POST['role'];
		$date = $_POST['date'];
		$sql = "select * from tt_daily_report where author_id={$authorID} and daily_date ='{$date}' and daily_status <> 2";
		$results = $mydb->query($sql);
		if(!$results){
			$hours = $_POST['hours'];
			$description = $_POST['description'];
			$status = STATUS_PENDING;
			$sql = "insert into tt_daily_report(author_id, daily_date, daily_project, daily_roles, daily_hours, daily_task, daily_description, daily_status) values({$authorID}, '{$date}', '{$project}', '{$role}', {$hours}, '{$task}', '{$description}', {$status})"; 
			$ret = $mydb->insert($sql);
			if(!$ret){
				echo "Insert daily report failed!";
			}
			else{
				echo "Insert daily report succeed!";
			}
		}
		else{
			echo "You should not try to insert the daily report again!";
		}
	}
	else{
		$userID = $_SESSION['STAFFID'];
		$sql = "select user_id from tt_user where staffID = '{$userID}'";
		$results = $mydb->query($sql);
		if(!$results){
			echo 'There is no user having this name!';
		}
		else{
			$authorID = $results[0][0];
		}

		$task = $_POST['task'];
		$month = $_POST['month'].'-1';
		$sql = "select * from tt_month_report where author_id = {$authorID} and month_date = '{$month}' and month_status <>2";
		$isDup = $mydb->query($sql);
		if(!$isDup){
			$description = $_POST['description'];
		//首先取得该用户该月所有日报时常之和
			$tempMonth = date("m", strToTime($month));
			$tempYear = date('Y',strToTime($month));
			$sql = "select sum(daily_hours) from tt_daily_report where author_id = {$authorID} and extract(year from daily_date) = '{$tempYear}' and extract(month from daily_date) = '{$tempMonth}'";
		//echo $sql ."<br>";
			$results=$mydb->query($sql);
			if(isset($results[0][0])){
				$hours = $results[0][0];
			}
			else{
				$hours = 0;
			}
			$sql = "select sum(daily_hours) from tt_daily_report where author_id = {$authorID} and daily_status = 1 and extract(year from daily_date) = '{$tempYear}' and extract(month from daily_date) = '{$tempMonth}'";
			$results=$mydb->query($sql);
			if(isset($results[0][0])){
				$verifedHours = $results[0][0];
			}
			else{
				$verifedHours = 0;
			}
			$date = $month;
			$sql = "insert into tt_month_report(author_id, month_date, month_task, month_description, month_hours, verifed_hours) values({$authorID}, '{$date}', '{$task}', '{$description}', {$hours}, {$verifedHours})";
			$ret = $mydb->insert($sql);
			if(!$ret){
				echo "Insert monthly report failed!";
			
			}  
			else{
				echo "Insert monthly report succeed!";
			}
		}
		else{
			echo "You should not try to insert the monthly report again!";
		}
	}

	
	
	