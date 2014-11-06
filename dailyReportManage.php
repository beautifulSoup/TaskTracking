
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
?>
<script type="text/javascript" src="./static/dist/js/vendor/jquery.min.js"></script>

<!--<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>  -->

<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">

    <!-- Loading Flat UI -->
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">


<!--

-->
<!-- <script type="text/javascript" src="./static/js/jquery-ui-datepicker.js"></script> -->
<link href="./static/css/datepicker.css" rel="stylesheet">
<script src="./static/js/bootstrap-datepicker.js" type="text/javascript"></script>

<script type="text/javascript" src="./static/dist/js/flat-ui.min.js"></script>
<style type="text/css">

<style type="text/css">


img{ 
border-style:none; 
} 

</style>

<script type="text/javascript">
	$(function () {

    	//$('#date').datepicker({
        //dateFormat: "yy-mm-dd"});
		$('#date').datepicker();
	});

	var dailyID = "";
	function checkForm(){
		var task = document.getElementById('task');
		if(task.value==""){
			alert("please input the task!");
			return false;
		}
		var project = document.getElementById('project');
		if(project.value==""){
			alert("please input the project");
			return false;
		}
		var role = document.getElementById('role');
		if(role.value==''){
			alert("please input your role");
			return false;
		}
		var date = document.getElementById('date');
		if(date.value==''){
			alert("please input the date");
			return false;
		}
		var description = document.getElementById('description');
		if(description.value==''){
			alert("please input the description");
			return false;
		}
		var hours = document.getElementById('hours');
		if(isNaN(hours.value)){
			alert('please input the hours as a number');
			return false;
		}
		var hourNum = parseInt(hours.value);
		if(hourNum>24 || hourNum<0){
			alert('please input the hour between 0 and 24');
			return false;
		}
		return true;

	}
	
	function modify(id){
		dailyID = id;
		url = "./dailyReportModify.php?id="+dailyID;
		var xmlHttp;
		if(window.XMLHttpRequest){
			xmlHttp = new XMLHttpRequest();

		}
		else{
			xmlHttp = new  ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlHttp.onreadystatechange=function(){
			if(xmlHttp.readyState==4 && xmlHttp.status==200){
				var container = document.getElementById('formContiner');
				container.innerHTML=xmlHttp.responseText;
		    	$('#date').datepicker({
		            dateFormat: "yy-mm-dd"});
				container.style.display="";

			}
		};


		xmlHttp.open("GET", url, true);
		xmlHttp.send();
		
	}

	function cancle(){
		document.getElementById('formContiner').style.display = "none";
		document.getElementById('date').value = "";
		document.getElementById('project').value = "";
		document.getElementById('role').value = "";
		document.getElementById('hours').value = "";
		document.getElementById('task').value = "";
		document.getElementById('description').value = ""; 
		document.getElementById('dailyForm').action = "javascript:submitForm('./newReport.php?type=0')";
		document.getElementById('cancleButton').style.display="none";
	}

	function submitForm(url){
		if(!checkForm()){
			return;
		}
		var data="";
		var project = document.getElementById('project').value;
		var date = document.getElementById('date').value;
		var  role = document.getElementById('role').value;
		var hours = document.getElementById('hours').value;
		var task = document.getElementById('task').value;
		var description = document.getElementById('description').value;
		data = data+"project="+project+"&date="+date+"&role="+role+"&hours="+hours+"&task="+task+"&description="+description;
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

		xmlHttp.open("POST", url, true);
		xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlHttp.send(data);
	}

	function deleteReport(url){

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

	function view(id){
		url = "./reportView.php?type=0&id="+id;
		var w = window.open(url, "", "screenX=300 , screenY=300, height=500, width=600");
	}


	
	

</script>

<table style="width:100%" >
<tr><td>
<div>
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
	$tempDate = date('Y-m-d', time());
	$sql = "select daily_hours from tt_daily_report where daily_date = '{$tempDate}' and author_id = {$userID}";
	$results = $mydb->query($sql);
	if($results==false){
		echo "There is no today's daily report yet!&nbsp";
	}
	else{
		echo "Today: {$results[0][0]} hours";
	}
	$tempMonth = date('m', time());
	$tempYear = date('Y', time());

	$sql = "select sum(daily_hours) from tt_daily_report where author_id = {$userID} and extract(year from daily_date) = '{$tempYear}' and extract(month from daily_date) = '{$tempMonth}'";
	$results = $mydb->query($sql);
	if(!$results){
		echo "Error: Database query failed!&nbsp";
	}
	else{
		echo "(Totally {$results[0][0]} hours this month!)<br>";

	}
?>

<span style="font-weight:bold;">Daily Reports in this Month</span><br>
<table style="width:85%" class="table table-bordered" >
	<tr>
		<th>Operation</th>
		<th>Report Date</th>
		<th>Project</th>
		<th>Hours</th>
		<th >Description</th>
		<th>Status</th>
	</tr>
<?php

	if(isset($_GET['total'])){
		$total = $_GET['total'];
	}
	else{
		$sql = "select count(*) from tt_daily_report  where author_id = {$userID}";
		$results = $mydb->query($sql);
		if(!$results){
			echo "Database query failed!";
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
	$sql = "select * from (select * from  tt_daily_report where author_id = {$userID} and extract(year from daily_date) = '{$tempYear}' and extract(month from daily_date) = '{$tempMonth}') as tempTable limit {$temp}, {$tempLimit}";
	$results = $mydb->query($sql);
	if($results==false){
		echo "No daily report yet<br>";
	}
	else{
		for($i=0;$i<count($results);$i++){
			$temp = $results[$i];
			$tempDate = date("Y-m-d",strtotime($temp[DAILY_DATE]));
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
			if($temp[DAILY_STATUS]==0){
				echo <<<tableRaw
				<tr>
					<td>
						<a href="javascript:void(0)" onclick="view('{$temp[DAILY_ID]}')"><image src="./static/img/view.png"></a>&nbsp
						<a href="javascript:void(0)" onclick="modify('{$temp[DAILY_ID]}')"><image src="./static/img/modify.png"></a>&nbsp
						<a href="javascript:void(0)" onclick="deleteReport('./reportOp.php?type=3&id={$temp[DAILY_ID]}')"><image src="./static/img/delete.png"></a>
					</td>
					<td>{$tempDate}</td>
					<td>{$temp[DAILY_PROJECT]}</td>
					<td>{$temp[DAILY_HOURS]}</td>
					<td>{$temp[DAILY_DESCRIPTION]}</td>
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
					<td>{$temp[DAILY_PROJECT]}</td>
					<td>{$temp[DAILY_HOURS]}</td>
					<td>{$temp[DAILY_DESCRIPTION]}</td>
					<td>{$status}</td>
				</tr>
tableRaw;
			}

		}
		echo "<tr><td  colspan=6>Page:";
		for($i=0;$i<=($total-1)/PAGE_NUM;$i++){
			$page = $i+1;
			echo "<a href='./dailyReportManage.php?total={$total}&n={$i}'>{$page}</a>&nbsp";
		}
		echo "</td></tr>";
	}
?>
</table>
</div>
</td></tr>



<tr><td>
<script type="text/javascript">
	function showPanel(){
		if(document.getElementById('formContiner').style.display=="none"){
			document.getElementById('formContiner').style.display="";
		}
		else{
			document.getElementById('formContiner').style.display="none";
		}
	}

</script>
<button id = "showButton" onclick="showPanel();" class="btn btn-primary" style="background-color: #EE9A49;color:#FFFFFF"">New daily Report</button>
</td></tr>
<tr><td>
<div  id="formContiner" class="jumbotron" style="width:850px;display:none">


<form style="width: 100%" id="dailyForm" action="javascript:submitForm('./newReport.php?type=0')">
	<table>
		<tr style="height:60px">
			<td>Task</td>
			<td><input  id ="task" type="text" name="task" class="form-control" ></td>
			<td>Project</td>
			<td> <input id="project" type="text" name="project" class="form-control" ></td>
			<td>Role</td>
			<td><input  id ="role" type="text" name="role" class="form-control"></td>
		</tr>

		<tr  style="height:60px">
			<td>Date</td>
			<td>
			<input   type="text" id="date" name="date" class="form-control"   data-date-format="yyyy-mm-dd"  data-date-viewMode="0">
			</td>

			<td>Hours</td>
			<td><input id="hours" type="text" name="hours" class="form-control" ></td>
		</tr>
		<tr >
			<td style="top:10px 0"></td>
			<td colspan=5><textarea id="description" name="description" class="form-control"  rows="10" cols="90" placeholder="Please Input Your Description"></textarea></td>
		</tr>
		<tr style="height:60px">
			<td  align="middle" colspan=6><div ><input id="submitButton" class="btn btn-primary"  type="submit" value="submit">&nbsp&nbsp<input  type="reset" class="btn btn-default" value="reset">&nbsp&nbsp<input onclick="cancle()"   id="cancleButton"  style="display:none;width:80px" type="button" value="cancel"></div></td>
		</tr>
	</table>
		
</form>
</div>
</td></tr>
</table>
