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

<!--ajax to get the data list -->
<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./static/js/jquery-ui.min.js"></script>
<link type="text/css" href="./static/css/jquery-ui.css" rel="stylesheet" />

    <!-- Loading Flat UI -->
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">
<script src="./static/dist/js/flat-ui.min.js"></script>


<table class="table table-bordered" width="800px" border="1" cellspacing="0" cellPadding="2">
	<tr>
		<th>Operation</th>
		<th>Month</th>
		<th>Task</th>
		<th>Hours</th>
		<th>Verified Hours</th>
		<th>Description</th>
		<th>Status</th>
	</tr>
<?php

	require_once 'dbop.php';
	require_once 'parameter.config.php';
	$mydb = new mydb();
	$staffid = $_SESSION['STAFFID'];
	$sql = "select user_id from tt_user where staffID = '{$staffid}'";
	$results = $mydb->query($sql);
	if(!$results){
		echo 'There is no user having this name!';
	}
	else{
		$userID = $results[0][0];
	}
	if(isset($_GET['total'])){
		$total = $_GET['total'];
	}
	else{
		$sql = "select count(*) from tt_month_report  where author_id = {$userID}";
		$results = $mydb->query($sql);
		if(!$results){
			echo "No monthly report yet!<br>";
		}
		$total = $results[0][0];
	}
	if(isset($_GET['n'])){
		$page = $_GET['n'];
	}
	else{
		$page = 0;
	}
	$temp = $page * PAGE_NUM;
	$tempLimit = PAGE_NUM;
	
	$condition="";
	if(isset($_GET['from'])){
		$from = $_GET['from'];
		$from = str_replace('-','',$from);
		$condition= $condition . " and extract(YEAR_MONTH from month_date) >= '{$from}' ";
	}
	if(isset($_GET['to'])){
		$to = $_GET['to'];
		$to = str_replace('-', '', $to);
		$condition=$condition . " and extract(YEAR_MONTH from month_date)<='{$to}' ";

	}
	if(isset($_GET['status'])){
		$status = $_GET['status'];

		$condition =$condition . " and month_status = {$status} ";

	}
	//echo $condition;
	$sql = "select * from (select * from tt_month_report where author_id = {$userID} {$condition} ) as tempTable limit {$temp}, {$tempLimit} ";


	$results = $mydb->query($sql);
	if(!$results){
		echo "No monthly report yet!<br>";
	}
	else{

		for($i=0;$i<count($results);$i++){
			$temp = $results[$i];
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
			
			if($temp[MONTH_STATUS]==0){
			echo <<<tableRaw
			<tr>
				<td>
					<a href="javascript:void(0)" onclick="view('{$temp[DAILY_ID]}')"><image src="./static/img/view.png"></a>&nbsp
					<a href="javascript:void(0)" onclick="modify('{$temp[DAILY_ID]}')"><image src="./static/img/modify.png"></a>&nbsp
					<a href="javascript:void(0)" onclick="deleteReport('./reportOp.php?type=5&id={$temp[MONTH_ID]}')"><image src="./static/img/delete.png"></a>
				</td>
				<td>{$tempDate}</td>
				<td>{$temp[MONTH_TASK]}</td>
				<td>{$temp[MONTH_HOURS]}</td>
				<td>{$temp[VERIFIED_HOURS]}</td>
				<td>{$temp[MONTH_DESCRIPTION]}</td>
				<td>{$status}</td>
			</tr>
			
tableRaw;
			}
			else{
			echo <<<tableRaw
			<tr>
				<td>
					<a href="javascript:void(0)" onclick="view('{$temp[DAILY_ID]}')"><image src="./static/img/view.png"></a>&nbsp
					<a href="javascript:void(0)"><image src="./static/img/no_modify.png"></a>&nbsp
					<a href="javascript:void(0)"><image src="./static/img/no_delete.png"></a>
				</td>
				<td>{$tempDate}</td>
				<td>{$temp[MONTH_TASK]}</td>
				<td>{$temp[MONTH_HOURS]}</td>
				<td>{$temp[VERIFIED_HOURS]}</td>
				<td>{$temp[MONTH_DESCRIPTION]}</td>
				<td>{$status}</td>
			</tr>
		
tableRaw;
			}
		}
		echo "<tr><td  colspan=7>Page:";
		for($i=0;$i<=($total-1)/PAGE_NUM;$i++){
			$page = $i+1;
			echo "<a href='./monthReportManage.php?total={$total}&n={$i}'>{$page}</a>&nbsp";
		}
		echo "</td></tr>";
	}
?>
</table>


