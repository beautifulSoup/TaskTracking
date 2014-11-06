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
	$sql = "select * from tt_month_report where month_id = {$id}";
	$results = $mydb->query($sql);
	if($results){
		$temp = $results[0];
		$tempDate = date("Y-m",strtotime($temp[DAILY_DATE]));
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
		echo <<<form

<form id = "monthForm" action="javascript:submitForm('./reportOp.php?type=4&id={$temp[MONTH_ID]}')">
	<table>
		<tr style="height:80px">
			<td>Task: </td>
			<td><input style="height:50px" class="form-control" id ="task" type="text" name="task" value="{$temp[MONTH_TASK]}"></td>
			<td>Month: </td>
			<td><input style="height:50px" class="form-control" id="month" type="text" name="month" value = "{$tempDate}" data-date-format="yyyy-mm" data-date-viewMode="1" data-date-minViewMode="1"></td>
		</tr>

		<tr>
			<td></td>
			<td colspan=4><textarea id="description" class="form-control" placeholder= "Please Input Your Description Here!" name="description" rows="10" cols="90">{$temp[MONTH_DESCRIPTION]}</textarea></td>
		</tr>
		<tr style="height:80px">
			<td  align="middle" colspan=6><div ><input style="width:80px" class="btn btn-primary" type="submit" value="submit">&nbsp&nbsp&nbsp<input  class="btn btn-default" style="width:80px" type="reset" value="reset">&nbsp&nbsp&nbsp<input onclick="cancle()"   class="btn btn-default" id="cancleButton"  style="width:80px" type="button" value="cancle"></div></td>
		</tr>
	</table>
		
</form>
form;
	}
	
	
