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

<link href="./static/css/bootstrap.css" rel="stylesheet">
<script src="./static/js/jquery.js" type="text/javascript"></script>


<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">
<script src="./static/dist/js/vendor/jquery.min.js"></script>
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<script src="./static/dist/js/flat-ui.min.js"></script>


<link href="./static/css/datepicker.css" rel="stylesheet">
<script src="./static/js/bootstrap-datepicker.js" type="text/javascript"></script>

<script type="text/javascript">

	$(function () {

		$('#month').datepicker();
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
				var continer = document.getElementById('monthReportList');
				continer.innerHTML=xmlHttp.responseText;
		
			}
		};

		if(condition!=""){
			condition = "?random="+Math.random()+"&"+condition;
		}
		else{
			condition = "?random="+Math.random();
		}
		var url = "./monthReportList.php" + condition;
		<?php 
			if(isset($_GET['total'])){
				echo "url = url +'&total={$_GET['total']}';";
			}
			if(isset($_GET['n'])){
				echo "url = url+'&n={$_GET['n']}';";
			}
			
		?>
		xmlHttp.open("GET", url, true);
		xmlHttp.send();
		
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
		var task = document.getElementById('task');
		if(task.value==""){
			alert("please input the task!");
			return false;
		}

		var month = document.getElementById('month');
		if(month.value==''){
			alert("please input the month");
			return false;
		}
		var description = document.getElementById('description');
		if(description.value==''){
			alert("please input the description");
			return false;
		}

		return true;

	}

	var monthID;

	function modify(id){
		monthID = id;
		url = "./monthReportModify.php?id="+monthID;

		var xmlHttp;
		if(window.XMLHttpRequest){
			xmlHttp = new XMLHttpRequest();

		}
		else{
			xmlHttp = new  ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlHttp.onreadystatechange=function(){
			if(xmlHttp.readyState==4 && xmlHttp.status==200){
				var continer = document.getElementById('formContiner');
				continer.innerHTML=xmlHttp.responseText;
				$('#month').datepicker();
				continer.style.display="";
				
			}
		};

		xmlHttp.open("GET", url, true);
		xmlHttp.send();
		

	}

	function cancle(){
		document.getElementById('formContiner').style.display = "none";
		document.getElementById('month').value = "";
		document.getElementById('task').value = "";
		document.getElementById('description').value = ""; 
		document.getElementById('monthForm').action = "javascript:submitForm('./newReport.php?type=1')";
		document.getElementById('cancleButton').style.display="none";
	}

	function submitForm(url){
		if(!checkForm()){
			return;
		}
		var data="";
		var month = document.getElementById('month').value;
		var task = document.getElementById('task').value;
		var description = document.getElementById('description').value;
		data = data+"month="+month+"&task="+task+"&description="+description;
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
		var url = "./reportView.php?type=1&id="+id;
		
		var w = window.open(url, "", "screenX=300 , screenY=300, height=500, width=600");
	}

</script>
	<style type="text/css">
    


	img{ 
	border-style:none; 
	} 



</style>


<!-- the search form -->
<table width ="87%" >
<tr ><td>
<div  class="jumbotron" style="width:100%;height:150px">
<form action="javascript: search()">
	<table>
		<tr>
			<td>From:&nbsp&nbsp</td>
			<td><input  type="text" style="height:40px" id="from" name="from" class="form-control"   data-date-format="yyyy-mm"  data-date-viewMode="1" data-date-minViewMode="1" />
			</td>
			<td>To:&nbsp&nbsp</td>
			<td><input type="text" style="height:40px" id="to" name="to" class="form-control"   data-date-format="yyyy-mm"  data-date-viewMode="1" data-date-minViewMode="1" />
			</td>
			<td>Status:&nbsp&nbsp</td>
			<td><select id="status" class=" select form-control">
					<option value="-1">All</option>
					<option value="0">Pending</option>
					<option value="1">Submitted</option>
					<option value="2">Deny</option>
				</select>
			</td>
		</tr>
	</table>
	<div style="margin:10px 0;float:right;width:250px"><input type="submit" class="btn btn-primary"  value="Search"></div>
</form>
</div>
</td></tr>
<tr><td>
<div><span style="font-weight:bold">Monthly Report</span><br></div>
<div id ='monthReportList'>	
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
</table>
</div>
</td></tr>
<tr><td>

<button id = "showButton" class="btn btn-primary" onclick="showPanel();" style="width:160px;background-color: #EE9A49;color:#FFFFFF" >New Monthly Report</button>
</td></tr>
<tr><td>
<div class="jumbotron" id="formContiner" style="width:100%;display: none;">


<form  id = "monthForm" action="javascript:submitForm('./newReport.php?type=1')">
	<table>
		<tr style="height:80px" >
			<td>Task: </td>
			<td>
				<input style="height:40px" type="text" id="task" name="task" class="form-control"  />
			
			</td>
			<td>Month: </td>
			<td>			
				<input  style="height:40px" type="text" id="month" name="month" class="form-control"   data-date-format="yyyy-mm"  data-date-viewMode="1" data-date-minViewMode="1"  data-date-weekStart="1"/>
			</td>
		</tr>

		<tr>
			<td></td>
			<td colspan=4><textarea placeholder="Please Input Your Description here!" class="form-control" id="description" name="description" rows="10" cols="90"></textarea></td>
		</tr>
		<tr style="height:80px">
			<td  align="middle" colspan=6><div ><input class="btn btn-primary" style="width:80px" type="submit" value="submit">&nbsp&nbsp&nbsp&nbsp<input  class="btn btn-default" style="width:80px" type="reset" value="reset">&nbsp&nbsp<input onclick="cancle()"   id="cancleButton"  style="display:none;width:80px" type="button" value="cancle"></div></td>
		</tr>
	</table>
		
</form>
</div>
</td></tr>
</table>
</div>
