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
	if(isset($_GET['id'])){
		$id = $_GET['id'];
		
	}
	else{
		header("Location: ./login.php");
	}
	$sql = "update staff_inf set 

name='{$_POST['name']}', 

gender={$_POST['gender']}, 

birthday = '{$_POST['birthday']}', 

usedname = '{$_POST['usedname']}', 

weight={$_POST['weight']}, 

height = {$_POST['weight']}, 

nation = '{$_POST['nation']}',

nativeplace = '{$_POST['nativeplace']}',

marital = {$_POST['marital']},

politics = '{$_POST['politics']}',

health = '{$_POST['health']}',

bloodtype = '{$_POST['bloodtype']}',

IDCard = '{$_POST['IDCard']}',

accounttype = {$_POST['accounttype']},

accountaddress = '{$_POST['accountaddress']}',

educationBackground = '{$_POST['educationBackground']}',

degree = '{$_POST['degree']}',

seconddegree = '{$_POST['seconddegree']}',

major = '{$_POST['major']}',

secondmajor = '{$_POST['secondmajor']}',

graduateschool = '{$_POST['graduateschool']}',

graduatetime = '{$_POST['graduatetime']}',

foreignlanguage = '{$_POST['foreignlanguage']}',

computerability = '{$_POST['computerability']}',

religion = '{$_POST['religion']}',

email = '{$_POST['email']}',

telphone = '{$_POST['telphone']}',

cellphone = '{$_POST['cellphone']}',

familymember = '{$_POST['familymember']}',

familyaddress = '{$_POST['familyaddress']}',

emergencycontact = '{$_POST['emergency']}',

educationexperience = '{$_POST['educationexperience']}',

workexperience = '{$_POST['workexperience']}',

awards = '{$_POST['awards']}',

speciality = '{$_POST['speciality']}',

trainexperience = '{$_POST['trainexperience']}',

selfassessment = '{$_POST['selfassessment']}',

remark = '{$_POST['remark']}'

where id = {$id}";
	require_once 'dbop.php';
	$mydb = new mydb();
	$ret = $mydb->update($sql);
	if($ret){
		$inf ="Information update success!";
	}
	else{
		$inf = "Information update failed!";
	}
	echo <<<script
	<script type="text/javascript">
	
	alert('{$inf}');
	window.location.href = "./informationEdit.php?id={$id}";
	</script>
script;


?>
