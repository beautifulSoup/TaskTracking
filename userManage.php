

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
<head>
    <!-- Loading Flat UI -->
<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./static/js/jquery-ui.min.js"></script>
<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">
<script src="./static/dist/js/flat-ui.min.js"></script>
<script type="text/javascript" src="./static/js/md5.js"></script>

<style type="text/css">

img{ 
border-style:none; 
} 

</style>

<script type="text/javascript">

	function view(id){
		url = "./staffInfView.php?id="+id;
		var w = window.open(url, "");
	}

	
	function showPanel(){
		if(document.getElementById('formContiner').style.display=="none"){
			document.getElementById('formContiner').style.display="";
		}
		else{
			document.getElementById('formContiner').style.display="none";
		}
	}


	function checkForm(){
		if(document.getElementById('alias').value==''){
			alert("Please input the user alias!");
			return false;
		}
		else if(document.getElementById('staffID').value==""){
			alert("Please input the staff ID!");
			return false;
		}
		else if(document.getElementById('department').value==''){
			alert("Please input the password!");
			return false;
		}
		else if(document.getElementById('password').value==''){
			alert("please input the password!");
			return false;
		}
		var password = document.getElementById('password').value;
		document.getElementById('password').value =faultylabs.MD5(password);
		
		return true;
	}

	function modify(id, name, leader, department, isLeader, staffID){
		document.getElementById('formContiner').style.display = "";
		document.getElementById('staffID').value = staffID;
		document.getElementById('alias').value = name;
		document.getElementById('department').value = department;
		document.getElementById('leader').value = leader;
		document.getElementById('isLeader').value = isLeader;
		url = "./userOp.php?type=3&id="+id;
		document.getElementById('userForm').action = "javascript:submitForm('"+url+"')";
		document.getElementById('cancelButton').style.display = "";
	}


	function cancel(){
		document.getElementById('formContiner').style.display = "none";
		document.getElementById('alias').value = "";
		document.getElementById('department').value = "";
		document.getElementById('leader').value = "";
		document.getElementById('isLeader').value = "";
		document.getElementById('userForm').action = "javascript:submitForm('./userOp.php?type=2')";
		document.getElementById('cancelButton').style.display = "none";
	}
	function deleteAccount(id){
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
		url = "./userOp.php?type=4&id="+id;
		xmlHttp.open("GET", url, true);
		xmlHttp.send();
	}

	function submitForm(url){
		if(!checkForm()){
			return;
		}
		var data="";
		var alias = document.getElementById('alias').value;
		var department = document.getElementById('department').value;
		var  leader = document.getElementById('leader').value;
		var isLeader = document.getElementById('isLeader').value;
		var password = document.getElementById('password').value;
		var staffID = document.getElementById('staffID').value;
		data = data+"alias="+alias+"&department="+department+"&leader="+leader+"&isLeader="+isLeader+"&password="+password+"&staffID="+staffID;
		
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
</script>

</head>


<table style="width:100%">
<tr><td>
<table  style="width:85%" class="table table-bordered" >
	<tr>
		<th>Operation</th>
		<th>StaffID</th>
		<th>User Name</th>
		<th>Leader Name</th>
		<th>Department</th>
		<th>Is Leader</th>
	</tr>
	
	
	

<?php

	require_once 'dbop.php';
	require_once 'parameter.config.php';
	$mydb = new mydb();
	$userID = $_SESSION['STAFFID'];
	$sql = "select * from tt_user where staffID = '{$userID}'";
	$results = $mydb->query($sql);
	if(!$results){
		echo 'There is no user having this name!';
		exit();
	}
	else{
		$userID = $results[0][0];
		if($results[0][6]==0){
			echo 'You are not a administor';
			header("Location: ./login.php");
		}
	}
	
	if(isset($_GET['total'])){
		$total = $_GET['total'];
	}
	else{
		$sql = "select count(*) from tt_user";
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
	$sql = "select * from (select * from tt_user where is_admin<>1) as tempTable limit {$temp}, {$tempLimit} ";
	

	$results = $mydb->query($sql);
	if(!$results){
		echo "There are no user account yet<br>";
	}
	else{

		for($i=0;$i<count($results);$i++){
			$temp = $results[$i];
			$leaderID = $temp[LEADER_ID];
			$sql = "select user_alias from tt_user where user_id = {$leaderID} ";
			$ret = $mydb->query($sql);
			if(!$ret){
				echo "Leader name query failed !";
				exit();
			}
			else{
				$leaderName = $ret[0][0];
			}
			if($temp[IS_LEADER]==1){
				$isLeader = "YES";
			}
			else{
				$isLeader = "NO";
			}
			echo <<<tableRaw
				<tr>
					<td>
						<a href="javascript:void(0)" onclick ="view('{$temp[USER_ID]}')"><image src="./static/img/view.png"></a>&nbsp
						<a href="javascript:void(0)" onclick="modify('{$temp[USER_ID]}','{$temp[USER_ALIAS]}', '{$temp[LEADER_ID]}', '{$temp[USER_DEPARTMENT]}','{$temp[IS_LEADER]}', '{$temp[StaffID]}')"><image src="./static/img/modify.png"></a>&nbsp
						<a href="javascript:void(0)" onclick="deleteAccount('{$temp[USER_ID]}')"><image src="./static/img/delete.png"></a>
					</td>
					<td>{$temp[StaffID]}</td>
					<td>{$temp[USER_ALIAS]}</td>
					<td>{$leaderName}</td>
					<td>{$temp[USER_DEPARTMENT]}</td>
					<td>{$isLeader}</td>
				</tr>
tableRaw;

		}
		
		echo "<tr><td  colspan=6>Page:";
		for($i=0;$i<=($total-1)/PAGE_NUM;$i++){
			$page = $i+1;
			echo "<a href='./userManage.php?total={$total}&n={$i}'>{$page}</a>&nbsp";
		}
		echo "</td></tr>";
	}
	?>
</table>
</td></tr>

<tr><td>
<button id = "showButton" onclick="showPanel();" class="btn btn-primary" style="width:160px;background-color: #EE9A49;color:#FFFFFF">New User Account</button>
</td></tr>
<tr><td>
<div id="formContiner" style="display: none;background-color: #FAFAD2">
<form id="userForm" action="javascript:submitForm('./userOp.php?type=2')">
	<table>
		<tr style="height:80px">
			<td>Name </td>
			<td><input id ="alias" class="form-control" type="text" name="alias"></td>
			<td>StaffID</td>
			<td><input id='staffID' class="form-control" type="text" name="staffID"></td>
			<td>Pass</td>
			<td><input id="password" class="form-control" type="password" name = "password"></td>
			</tr>
			<tr style="height:80px;">
			<td>Dept </td>
			<td><input id = "department" class="form-control" type="text" name = "department"></td>
			<td>Leader</td>
			<td><select class="form-control select" style="height:40px" id="leader" name="leader">
				<?php 
					
					$sql = "select * from tt_user where is_leader = 1";
					$results = $mydb->query($sql);
					if(!$results){
						echo "Database query failed";
					}
					else{
						for($i=0;$i<count($results);$i++){
							$temp=$results[$i];
							echo "<option value='{$temp[0]}'>{$temp[2]}</option>";
						}
					}
				
				
				?>
			
			
			</select></td>
			<td>IsLeader: </td>

			<td><select class="form-control select" style="height:40px" id="isLeader" name= "isLeader">
				<option value='0'>No</option>
				<option value='1' selected="selected">Yes</option>

			</select>
			</td>
			</tr>
		<tr style="height:80px">
			<td  align="middle" colspan=6><div ><input id="submitButton" class="btn btn-primary" type="submit" value="submit">&nbsp&nbsp<input class="btn btn-default" type="reset" value="reset">&nbsp&nbsp<input onclick="cancel()"   class="btn btn-default" id="cancelButton"  style="display:none;width:80px" type="button" value="cancel"></div></td>
		</tr>
	</table>
		
</form>
</div>
</td></tr>

</table>


