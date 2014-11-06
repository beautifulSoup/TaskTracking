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
<link type="text/css" href="./static/css/jquery-ui.css" rel="stylesheet" />
<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">

    <!-- Loading Flat UI -->
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<script type="text/javascript" src="./static/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="./static/dist/js/flat-ui.min.js"></script>
<script type="text/javascript" src="./static/js/json.js"></script>

<style type="text/css">
	table input{
		width: 100%;
		height: 25px;
	}
	table td{
		height:20px;
		width:20px;
	}
	
	


</style>
<?php 

	require_once 'dbop.php';
	require_once 'parameter.config.php';
	$mydb = new mydb();
	if(isset($_GET['id'])){
		$userID = $_GET['id'];
	}
	else{
		header("Location: login.php");
	}
	$sql = "select * from staff_inf where id={$userID}";
	$results = $mydb->query($sql);
	if(!$results){
		echo "Database connect failed! Please check your network condition!";
		exit();
	}
	$staff = $results[0];
	$family = json_decode($staff[FAMILYMEMBER], true);
	$emergency = json_decode($staff[EMERGENCYCONTACT], true);
	$education = json_decode($staff[EDUCATIONEXPERIENCE], true);
	$work = json_decode($staff[WORKEXPERIENCE], true);
	
	echo <<<table
<table  border="1" bordercolor="#a0c6e5" style="width: 70%">
  <tr>
    <td style="width:20px">Name</td>
    <td>
     {$staff[NAME]}</td>
    <td>Gender</td>
    <td>
      {$staff[GENDER]}
    </td>
    <td>Birthday</td>
    <td>
     {$staff[BIRTHDAY]}
    </td>
    <td rowspan="5" style="width:50px">photo</td>
  </tr>
  <tr>
    <td>Used Name</td>
    <td>{$staff[USEDNAME]}</td>
    <td>Weight</td>
    <td>{$staff[WEIGHT]}</td>
    <td>Height </td> 
    <td>{$staff[HEIGHT]}</td>
  </tr>
  <tr>
    <td>Nationality</td>
    <td>{$staff[NATION]}</td>
    <td>Native place</td>
    <td>{$staff[NATIVEPLACE]}</td>
    <td>Marital status</td>
    <td>{$staff[MARITAL]}
	</td>
  </tr>
  <tr>
    <td>Political affiliation</td>
    <td>{$staff[POLITICS]}</td>
    <td>Health</td>
    <td>{$staff[HEALTH]}</td>
    <td>Blood type</td>
    <td>{$staff[BLOODTYPE]}</td>
  </tr>
  <tr>
    <td>ID number</td>
    <td colspan="5">{$staff[IDCard]}</td>
  </tr>
  <tr>
    <td>Account type</td>
    <td>{$staff[ACCOUNTTYPE]}
    </td>
    <td>Account Address</td>
    <td colspan="4">{$staff[ACCOUNTADDRESS]}</td>
  </tr>
  <tr>
    <td>Education experience</td>
    <td>{$staff[EDUCATIONBACKGROUND]}</td>
    <td>Degree</td>
    <td>{$staff[DEGREE]}</td>
    <td>Second degree</td>
    <td colspan="2">{$staff[SECONDDEGREE]}</td>
  </tr>
  <tr>
    <td>Major</td>
    <td colspan="3">{$staff[MAJOR]}</td>
    <td>Second Major</td>
    <td colspan="2">{$staff[SECONDMAJOR]}</td>
  </tr>
  <tr>
    <td>Graduate School</td>
    <td colspan="3">{$staff[GRADUATESCHOOL]}</td>
    <td>Graduation time</td>
    <td colspan="2">{$staff[GRADUATETIME]}</td>
  </tr>
  <tr>
    <td>Foreign Language</td> 
	<td colspan="7">{$staff[FOREIGNLANGUAGE]}</td>
  </tr>
  <tr>
    <td>Computer ability</td>
    <td colspan="6">{$staff[COMPUTERABILITY]}</td>
  </tr>
  <tr>
    <td>Religious belief</td>
    <td colspan="3">{$staff[RELIGION]}</td>
    <td>E-mail</td>
    <td colspan="2">{$staff[EMAIL]}</td>
  </tr>
  <tr>
    <td>Home address</td>
    <td colspan="6">{$staff[FAMILYADDRESS]}</td>
  </tr>
  <tr>
    <td>Telephone</td>
    <td colspan="3">{$staff[TELPHONE]}</td>
    <td>Cellphone</td>
    <td colspan="2">{$staff[CELLPHONE]}</td>
  </tr>
  <tr>
    <td rowspan="5">Family Members</td>
    <td>Appellation</td>
    <td>Name</td>
    <td>Age</td>
    <td colspan="3">Job</td>
  </tr>
  <tr>
    <td>{$family["familyMembers"][0]["appellation"]}</td>
    <td>{$family["familyMembers"][0]["name"]}</td>
    <td>{$family["familyMembers"][0]["age"]}</td>
    <td colspan="3">{$family["familyMembers"][0]["job"]}</td>
  </tr>
  <tr>
    <td>{$family["familyMembers"][1]["appellation"]}</td>
    <td>{$family["familyMembers"][1]["name"]}</td>
    <td>{$family["familyMembers"][1]["age"]}</td>
    <td colspan="3">{$family["familyMembers"][1]["job"]}</td>
  </tr>
  <tr>
    <td>{$family["familyMembers"][2]["appellation"]}</td>
    <td>{$family["familyMembers"][2]["name"]}</td>
    <td>{$family["familyMembers"][2]["age"]}</td>
    <td colspan="3">{$family["familyMembers"][2]["job"]}</td>
  </tr>
  <tr>
    <td>{$family["familyMembers"][3]["appellation"]}</td>
    <td>{$family["familyMembers"][3]["name"]}</td>
    <td>{$family["familyMembers"][3]["age"]}</td>
    <td colspan="3">{$family["familyMembers"][3]["job"]}</td>
  </tr>
  <tr>
    <td>Emergency Contact</td>
    <td colspan="3">{$emergency["name"]}</td>
    <td>Telephone</td>
    <td colspan="2">{$emergency["phone"]}</td>
  </tr>
  <tr>
    <td rowspan="6">Education Experience</td>
    <td colspan="2">Start and Stop Date</td>
    <td colspan="2">School and Major</td>
    <td colspan="2">Is graduated</td>
  </tr>
  <tr>
    <td colspan="2">{$education["educationexperiences"][0]["date"]}</td>
    <td colspan="2">{$education["educationexperiences"][0]["school"]}</td>
    <td colspan="2">{$education["educationexperiences"][0]["graduate"]}</td>
  </tr>
  <tr>
    <td colspan="2">{$education["educationexperiences"][1]["date"]}</td>
    <td colspan="2">{$education["educationexperiences"][1]["school"]}</td>
    <td colspan="2">{$education["educationexperiences"][1]["graduate"]}</td>
  </tr>
  <tr>
    <td colspan="2">{$education["educationexperiences"][2]["date"]}</td>
    <td colspan="2">{$education["educationexperiences"][2]["school"]}</td>
    <td colspan="2">{$education["educationexperiences"][2]["graduate"]}</td>
  </tr>
  <tr>
    <td colspan="2">{$education["educationexperiences"][3]["date"]}</td>
    <td colspan="2">{$education["educationexperiences"][3]["school"]}</td>
    <td colspan="2">{$education["educationexperiences"][3]["graduate"]}</td>
  </tr>
  <tr>
    <td colspan="2">{$education["educationexperiences"][4]["date"]}</td>
    <td colspan="2">{$education["educationexperiences"][4]["school"]}</td>
    <td colspan="2">{$education["educationexperiences"][4]["graduate"]}</td>
  </tr>
  <tr>
    <td rowspan="5">Work Experience</td>
    <td colspan="2">Start and Stop date</td>
    <td colspan="4">Job</td>
  </tr>
  <tr>
    <td colspan="2">{$work["workexperiences"][0]["date"]}</td>
    <td colspan="4">{$work["workexperiences"][0]["job"]}</td>
  </tr>
  <tr>
    <td colspan="2">{$work["workexperiences"][1]["date"]}</td>
    <td colspan="4">{$work["workexperiences"][1]["job"]}</td>
  </tr>
  <tr>
    <td colspan="2">{$work["workexperiences"][2]["date"]}</td>
    <td colspan="4">{$work["workexperiences"][2]["job"]}</td>
  </tr>
  <tr>
    <td colspan="2">{$work["workexperiences"][3]["date"]}</td>
    <td colspan="4">{$work["workexperiences"][3]["job"]}</td>
  </tr>
  <tr>
    <td colspan="1">Awards during school</td>
	<td style="width:200px" colspan="6">{$staff[AWARDS]}</td>
  </tr>
  <tr>
    <td colspan="1">Specialty</td>
  	<td valign="top" style="height:200px" colspan="6">{$staff[SPECIALITY]}</td>
  </tr>
  <tr>
    <td colspan="1">Training Experience</td>
    <td  valign="top" style="height:200px" colspan="6" >{$staff[TRAINEXPERIENCE]}</td>
  </tr>
  <tr>
    <td colspan="1">Self Assessment</td>
 	<td valign="top" style="height:200px" colspan="6">{$staff[SELFASSESSMENT]}</td> 
  </tr>
  <tr>
    <td colspan="1">Remark</td>
 	<td  valign="top" style="height:200px" colspan="6">{$staff[REMARK]}</td> 
  </tr>
</table>

table;
	
	
	





?>