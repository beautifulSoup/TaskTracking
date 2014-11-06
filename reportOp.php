	<?php
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
	require_once 'parameter.config.php';
	$mydb = new mydb();
	$type = $_GET['type'];
	if($type==0){    // daily report verify
		$id = $_GET['id'];
		$status = $_GET['status'];
		$sql = "update tt_daily_report set daily_status = {$status} where daily_id = {$id}";
		$ret = $mydb->update($sql);
		if(!$ret){
			if($status=='1'){
				echo "Verify the report failed!";
			}
			else if($status=='2'){
				echo "Deny the report failed!";
			}
		}
		else{
			if($status=='1'){
				echo "verify the report succeed!";
			}
			else if($status=='2'){
				echo "Deny the report succeed!";
			}
		}
	}
	else if($type==1){ //monthly report verify
		$id = $_GET['id'];
		$status = $_GET['status'];
		$sql = "update tt_month_report set month_status = {$status} where month_id = {$id}";
		$ret = $mydb->update($sql);

		if(!$ret){
			if($status=='1'){
				echo "Verify the report failed!";
			}
			else if($status=='2'){
				echo "Deny the report failed!";
			}
		}
		else{
			if($status=='1'){
				echo "verify the report succeed!";
			}
			else if($status=='2'){
				echo "Deny the report succeed!";
			}
		}
	}
	else if($type==2){   //modify the daily report
		$id = $_GET['id'];
		$task = $_POST['task'];
		$project = $_POST['project'];
		$role = $_POST['role'];
		$date = $_POST['date'];
		$hours = $_POST['hours'];
		$description = $_POST['description'];
		$status = STATUS_PENDING;
		
		$sql = "update tt_daily_report set daily_date='{$date}', daily_project='{$project}', daily_roles='{$role}', daily_hours={$hours}, daily_task='{$task}',daily_description='{$description}', daily_status={$status} where daily_id = {$id} ";
		$ret = $mydb->update($sql);
		if($ret){
			echo "Modify daily report succeed!";
		}
		else{
			echo "Modify daily report failed!";
		}
	}
	else if($type==3){		//delete the daily report
		$id = $_GET['id'];
		$sql = "delete from tt_daily_report where daily_id = {$id}";
		$ret = $mydb->delete($sql);
		if($ret){
			echo "Delete daily report succeed!";
		}
		else{
			echo "Delete daily report failed!";
		}
	}
	else if($type==4){   //modify the monthly report
		$id = $_GET['id'];
		$task = $_POST['task'];
		$month = $_POST['month']."-1";
		$description = $_POST['description'];
		$sql = "update tt_month_report set month_date='{$month}', month_task = '{$task}', month_description = '{$description}' where month_id = {$id}";
		$ret = $mydb->delete($sql);
		if($ret){
			echo "Modify monthly report succeed!";
		}
		else{
			echo "Modify monthly report failed!";
		}
		


	}
	else if($type==5){  //delete the monthly report
		$id = $_GET['id'];
		$sql = "delete from tt_month_report where month_id = {$id}";
		$ret = $mydb->delete($sql);
		if($ret){
			echo "Delete monthly report succeed!";
		}
		else{
			echo "Delete monthly report failed!";
		}
	}
			

?>