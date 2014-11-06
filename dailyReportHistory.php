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

<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
    <!-- Loading Flat UI -->
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">
<script src="./static/dist/js/flat-ui.min.js"></script>
<link href="http://www.see-source.com/bootstrap/js/datepicker/css/datepicker.css" rel="stylesheet">
<script src="http://www.see-source.com/bootstrap/js/datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<style type="text/css">

img{ 
border-style:none; 
} 

</style>
<script type="text/javascript">



	$(function () {

		$('#from').datepicker();
		$('#to').datepicker();
	});

	window.onload=function(){
		search();
	};

	
	function search(){
		var condition = "";
		var from = document.getElementById('from').value;
		var to = document.getElementById('to').value;
		var status = document.getElementById('status').value;
		if(from!=""){
			condition=condition+("from="+from);
		}
		if(to!=""){
			if(condition!=""){
				condition+="&";
			}
			condition=condition+("to="+to);
		}
		if(status!="-1"){
			if(condition!=""){
				condition+="&";
			}
			condition=condition+("status="+status);
		}
		var xmlHttp;
		if(window.XMLHttpRequest){
			xmlHttp = new XMLHttpRequest();

		}
		else{
			xmlHttp = new  ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlHttp.onreadystatechange=function(){
			if(xmlHttp.readyState==4 && xmlHttp.status==200){
				var continer = document.getElementById('dailyReportList');
				continer.innerHTML=xmlHttp.responseText;
				
			}
		};

		if(condition!=""){
			condition = "?random="+Math.random()+"&"+condition;
		}
		else{
			condition = "?random="+Math.random();
		}
		var url = "./dailyReportList.php" + condition;
		<?php 
			if(isset($_GET['total'])){
				echo "url = url+'&total={$_GET['total']}';";
			}
			if(isset($_GET['n'])){
				echo "url = url+'&n={$_GET['n']}';";
			}
		?>
		xmlHttp.open("GET", url, true);
		xmlHttp.send();
		
	}

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
		if(hourNum>8 || hourNum<0){
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
				$('#date').datepicker();
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
		var role = document.getElementById('role').value;
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
		w.document.write(content);
	}
	

</script>


<!-- the search form -->
<table style="width ="100%" >
<tr><td>
<div  class="jumbotron" style="width:800px;height:150px">
<form action="javascript: search()">
	<table>
		<tr>
			<td>From:&nbsp&nbsp</td>
			<td><input class="form-control" style="height:40px" type="text" id="from" name="from" data-date-format="yyyy-mm-dd"></td>
			<td>To:&nbsp&nbsp</td>
			<td><input class="form-control" style="height:40px" type="text" id="to" name="to" data-date-format="yyyy-mm-dd"></td>
			<td>Status:&nbsp&nbsp</td>
			<td><select class="select form-control" id="status">
					<option value="-1">All</option>
					<option value="0">Pending</option>
					<option value="1">Submitted</option>
					<option value="2">Deny</option>
				</select>
			</td>
		</tr>
	</table>
	<div style="margin:10px 0;float:right;width:250px"><input class="btn btn-primary" type="submit" value="Search"></div>
</form>
</div>
</td></tr>
<tr><td>
<div><span style="font-weight:bold">Daily Report</span><br></div>
<div id ='dailyReportList'>	
<table class="table table-bordered"  width="800px" border="1" cellspacing="0" cellPadding="2">
	<tr>
		<th>Operation</th>
		<th>Report Date</th>
		<th>Project</th>
		<th>Hours</th>
		<th>Description</th>
		<th>Status</th>
	</tr>
</table>
</div>
</td></tr>
<tr><td>
<div   id="formContiner" class="jumbotron" style="display: none;">


</div>
</td></tr>
</table>
