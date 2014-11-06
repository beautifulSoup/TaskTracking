


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
	$mydb = new mydb();
	$id = $_GET['id'];
	$sql = "select * from tt_daily_report where daily_id = {$id}";
	$results = $mydb->query($sql);
	if($results){
		$temp = $results[0];
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
		echo <<<form
		
		<form id="dailyForm" action="javascript:submitForm('./reportOp.php?type=2&id={$temp[DAILY_ID]}')">
	<table>
		<tr style="height:60px">
			<td>Task: </td>
			<td><input id ="task" class="form-control" type="text" name="task" value='{$temp[DAILY_TASKS]}'></td>
			<td>Project:</td>
			<td> <input  id="project"  class="form-control" type="text" name="project" value ='{$temp[DAILY_PROJECT]}'></td>
			<td>Role: </td>
			<td><input  id ="role"  class="form-control" type="text" name="role" value ='{$temp[DAILY_ROLES]}'></td>
		</tr>

		<tr style="height:60px">
			<td>Date: </td>
			<td>
			<input  type="text" id="date"  name="date" class="form-control"   data-date-format="yyyy-mm-dd"  data-date-viewMode="0" value = '{$tempDate}'>
			<td>Hours: </td>
			<td><input   id="hours"  class="form-control" type="text" name="hours" value = '{$temp[DAILY_HOURS]}'></td>
		</tr>
		<tr>
			<td valign="top"></td>
			<td colspan=5><textarea id="description"  class="form-control" placeholder="Please Input Your Description" name="description" rows="10" cols="90" value= "{$temp[DAILY_DESCRIPTION]}">{$temp[DAILY_DESCRIPTION]}</textarea></td>
		</tr>
		<tr style="height:60px">
			<td  align="middle" colspan=6><div ><input id="submitButton" class="btn btn-primary" style="width:80px" type="submit" value="submit">&nbsp&nbsp<input style="width:80px" class="btn btn-default" type="reset" value="reset">&nbsp&nbsp<input onclick="cancle()"   id="cancleButton" class="btn btn-default" style="width:80px" type="button" value="cancel"></div></td>
		</tr>
	</table>
		
</form>
form;
	}
	
	
