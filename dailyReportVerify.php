
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

    <!-- Loading Flat UI -->
<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">
<script src="./static/dist/js/flat-ui.min.js"></script>

<script type="text/javascript">
	function verify(url){
		
		
		var xmlHttp;
		if(window.XMLHttpRequest){
			xmlHttp = new XMLHttpRequest();

		}
		else{
			xmlHttp = new  ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlHttp.onreadystatechange=function(){
			if(xmlHttp.readyState==4 && xmlHttp.status==200){
				alert(xmlHttp.responseText);
				location.reload(true);
			}
		};


		

		xmlHttp.open("GET", url, true);
		xmlHttp.send();
	}

</script>
<div style="width:87%">
<table class="table table-bordered"  border="1" cellspacing="0" cellPadding="2">
	<tr>

		<th>Report Date</th>
		<th>StaffID</th>
		<th>Author</th>
		<th>Project</th>
		<th>Hours</th>
		<th>Description</th>
		<th>Operation</th>
	</tr>
	
	


<?php

	require_once 'dbop.php';
	require_once 'parameter.config.php';
	header("Content-type: text/html; charset=utf-8");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	$mydb = new mydb();
	$leaderid = $_SESSION['STAFFID'];
	$sql = "select * from tt_user where staffID = '{$leaderid}'";
	$results = $mydb->query($sql);
	//验证是否为leader账号
	
	if(!$results){
		echo "alert('Leader account query failed!')";
		header("Location: login.php");
	}
	else{
		$isLeader = $results[0][5];
		if(!$isLeader){
			echo "alert('Your are not a leader, sorry!')";
			header("Location: login.php");
			
		}
	}
	$leaderID = $results[0][0];
	if(isset($_GET['total'])){
		$total = $_GET['total'];
	}
	else{
		$sql = "select count(*) from tt_daily_report where daily_status = 0 and author_id in (select user_id from tt_user where leader_id = {$leaderID})";
		
		$results = $mydb->query($sql);
		if(!$results){
			echo "Count query failed!<br>";
			
		}
		else{
			$total = $results[0][0];
		}
	}
	if(isset($_GET['n'])){
		$page = $_GET['n'];
	}
	else{
		$page = 0;
	}
	$temp = $page*PAGE_NUM;
	$tempLimit = PAGE_NUM;
	$sql = "select * from (select * from tt_daily_report where daily_status = 0 and author_id in (select user_id from tt_user where leader_id = {$leaderID})) as temptable limit {$temp}, {$tempLimit}";

	$results = $mydb->query($sql);
	if(!$results){
		echo "No pending daily report yet !<br>";
	}
	else{
		for($i=0;$i<count($results);$i++){
			$temp = $results[$i];
			$tempDate = date("Y-m-d",strtotime($temp[DAILY_DATE]));
			$sql = "select name from staff_inf where id = {$temp[DAILY_AUTHOR]}";
			$staffRet = $mydb->query($sql);
			if(!$staffRet){
				echo "No staff name!";
				exit();
			}
			$sql = "select staffID from tt_user where user_id = {$temp[DAILY_AUTHOR]}";
			$userRet = $mydb->query($sql);
			if(!$userRet){
				echo "No user inf";
				exit();
			}
			
				
			echo <<<tableRaw
			<tr>

				<td>{$tempDate}</td>
				<td>{$userRet[0][0]}</td>
				<td>{$staffRet[0][0]}</td>
				<td>{$temp[DAILY_PROJECT]}</td>
				<td>{$temp[DAILY_HOURS]}</td>
				<td>{$temp[DAILY_DESCRIPTION]}</td>
				<td style="width:150px">
				<a href="javascript:void(0)" onclick="verify('./reportOp.php?type=0&status=1&id={$temp[DAILY_ID]}')">Verify</a>&nbsp
				<a href="javascript:void(0)" onclick="verify('./reportOp.php?type=0&status=2&id={$temp[DAILY_ID]}')">Deny</a>&nbsp
				</td>
			</tr>
tableRaw;

		}
		echo "<tr><td  colspan=7>Page:";
		for($i=0;$i<=($total-1)/PAGE_NUM;$i++){
			$page = $i+1;
			echo "<a href='./dailyReportVerify.php?total={$total}&n={$i}'>{$page}</a>&nbsp";
		}
		echo "</td></tr>";

				
	}
	?>
</table>
</div>