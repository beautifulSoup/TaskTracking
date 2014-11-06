<?php
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
	?>

<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">

    <!-- Loading Flat UI -->
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<!--
<script type="text/javascript" src="./static/dist/js/vendor/jquery.min.js"></script>
-->
<script type="text/javascript" src="./static/dist/js/flat-ui.min.js"></script>
<script type="text/javascript" src="./static/js/json.js"></script>

<style type="text/css">
	table input{
		width: 100%;
		height: 25px;
	}
	#table2{
		width: 70%;
	}
	



</style>

<script type="text/javascript">
	
	var photoPath="<?php echo $staff[PHOTO]?>";
	var isLoad = 1;
  var t;
	$(function(){
		flushPhoto();
		$("#gender").val("<?php echo $staff[GENDER]?>");
		$("#atype").val("<?php echo $staff[ACCOUNTTYPE]?>");
		$("#marital").val("<?php echo $staff[MARITAL]?>");
	});
	function checkForm(){

		var familyMember = {"familyMembers":[
	                                     {"appellation" : document.getElementsByName('appellation1')[0].value, "name": document.getElementsByName('name1')[0].value, "age":document.getElementsByName('age1')[0].value, "job": document.getElementsByName('job1')[0].value},
	                                     {"appellation" : document.getElementsByName('appellation2')[0].value, "name": document.getElementsByName('name2')[0].value, "age":document.getElementsByName('age2')[0].value, "job": document.getElementsByName('job2')[0].value},
	                                     {"appellation" : document.getElementsByName('appellation3')[0].value, "name": document.getElementsByName('name3')[0].value, "age":document.getElementsByName('age3')[0].value, "job": document.getElementsByName('job3')[0].value},
	                                     {"appellation" : document.getElementsByName('appellation4')[0].value, "name": document.getElementsByName('name4')[0].value, "age":document.getElementsByName('age4')[0].value, "job": document.getElementsByName('job4')[0].value}
										] 
		};
		
		var educationexperience = {"educationexperiences":[
	                                                   {"date":document.getElementsByName('date1')[0].value, "school": document.getElementsByName('school1')[0].value, "graduate": document.getElementsByName('graduate1')[0].value},
	                                                   {"date":document.getElementsByName('date2')[0].value, "school": document.getElementsByName('school2')[0].value, "graduate": document.getElementsByName('graduate2')[0].value},
	                                                   {"date":document.getElementsByName('date3')[0].value, "school": document.getElementsByName('school3')[0].value, "graduate": document.getElementsByName('graduate3')[0].value},
	                                                   {"date":document.getElementsByName('date4')[0].value, "school": document.getElementsByName('school4')[0].value, "graduate": document.getElementsByName('graduate4')[0].value},
	                                                   {"date":document.getElementsByName('date5')[0].value, "school": document.getElementsByName('school5')[0].value, "graduate": document.getElementsByName('graduate5')[0].value}
													]
		};

		var workexperience = {"workexperiences":[
	                                        {"date":document.getElementsByName('jobdate1')[0].value, "job": document.getElementsByName('job1')[0].value},
	                                        {"date":document.getElementsByName('jobdate2')[0].value, "job": document.getElementsByName('job2')[0].value},
	                                        {"date":document.getElementsByName('jobdate3')[0].value, "job": document.getElementsByName('job3')[0].value},
	                                        {"date":document.getElementsByName('jobdate4')[0].value, "job": document.getElementsByName('job4')[0].value}
	                                    	]
		};
		var emergency = {"name": document.getElementsByName('emergencycontact')[0].value, "phone": document.getElementsByName('emergencyphone')[0].value};
		
		document.getElementsByName('familymember')[0].value = familyMember.toJSONString();
		document.getElementsByName('emergency')[0].value = emergency.toJSONString();
		document.getElementsByName('educationexperience')[0].value = educationexperience.toJSONString();
		document.getElementsByName('workexperience')[0].value = workexperience.toJSONString();
		
		return true;
	}

	function photoUpload(){
		url = "./imgUpload.php?id=<?php echo $staff[ID];?>";
		var w = window.open(url, "", "screenX=300 , screenY=300, height=500, width=600");
		
	}

	function flushPhoto(){
		if(photoPath!=""){
			if(isLoad == 1){
        t = Math.random();
				var html = "<a style='height:200px;width:150px' href='javascript:void(0)' onclick='photoUpload()'><img style='height:200px;width:150px' src='"+photoPath+"?t="+t+"'></img>";
				document.getElementById('photo').innerHTML = html; 
				isLoad = 0;
			}
			else{
				var html = "<a   style='height:200px;width:150px' href='javascript:void(0)' onclick='photoUpload()'><img style='height:200px;width:150px' src='"+photoPath+"?t="+t+"'></img>";
				document.getElementById('photo').innerHTML = html; 
			}
		}
	}

	setInterval("flushPhoto();", 1000);

	

</script>

<?php 
	
//./inforSubmit.php?id={$userID} onsubmit="return checkForm()" 	<form method="post" action="./inforSubmit.php?id={$userID}" onsubmit="return checkForm()">
	echo <<<form
<div style="width: 100%; ">
<form method="post" action="./inforSubmit.php?id={$userID}" onsubmit="return checkForm()">


	
<table class="table" style="width: 90%">
  <tr>
    <td>Name</td>
    <td>
      <input type="text" name="name" value="{$staff[NAME]}"/></td>
    <td>Gender</td>
    <td>
      <select id="gender" name="gender" value = "{$staff[GENDER]}">
			<option value="0">Male</option>
			<option value="1">Female</option>
      </select>
    </td>
    <td>Birthday</td>
    <td>
      <input type="text" id="birthday" name="birthday" value = "{$staff[BIRTHDAY]}" />
    </td>
    <td  rowspan="5" id= "photo" style="width:180px"><a  class="btn btn-default" href="javascript:void(0)" onclick="photoUpload()">Photo Upload</a></td>
  </tr>
  <tr>
    <td>Used Name</td>
    <td><input type="text" name="usedname" value = "{$staff[USEDNAME]}"/></td>
    <td>Weight</td>
    <td><input type="text" name="weight" value="{$staff[WEIGHT]}"/></td>
    <td>Height </td> 
    <td><input type="text" name="height" value="{$staff[HEIGHT]}"/></td>
  </tr>
  <tr>
    <td>Nationality</td>
    <td><input name="nation" type="text" value="{$staff[NATION]}"/></td>
    <td>Native place</td>
    <td><input type="text" name="nativeplace" value="{$staff[NATIVEPLACE]}"/></td>
    <td>Marital status</td>
    <td><select id="marital" name="marital" value="{$staff[MARITAL]}">
			<option value="0">Married</option>
			<option value="1">Unmarried</option>
		</select>
	</td>
  </tr>
  <tr>
    <td>Political affiliation</td>
    <td><input type="text" name="politics" value="{$staff[POLITICS]}"/></td>
    <td>Health</td>
    <td><input type="text" name="health" value="{$staff[HEALTH]}"/></td>
    <td>Blood type</td>
    <td><input type="text" name="bloodtype" value="{$staff[BLOODTYPE]}"/></td>
  </tr>
  <tr>
    <td>ID number</td>
    <td colspan="5"><input type="text" name="IDCard" value="{$staff[IDCard]}"/></td>
  </tr>
  <tr>
    <td>Account type</td>
    <td><select id="atype" name="accounttype" value = "{$staff[ACCOUNTTYPE]}">
      <option value="0">Town</option>
      <option value="1">Country</option>
    </select>
    </td>
    <td>Account Address</td>
    <td colspan="4"><input type="text" name="accountaddress" value="{$staff[ACCOUNTADDRESS]}"/></td>
  </tr>
  <tr>
    <td>Education experience</td>
    <td><input type="text" name="educationBackground" value="{$staff[EDUCATIONBACKGROUND]}"/></td>
    <td>Degree</td>
    <td><input type="text" name="degree" value="{$staff[DEGREE]}"/></td>
    <td>Second degree</td>
    <td colspan="2"><input type="text" name="seconddegree" value="{$staff[SECONDDEGREE]}"/></td>
  </tr>
  <tr>
    <td>Major</td>
    <td colspan="3"><input type="text" name="major" value = "{$staff[MAJOR]}"/></td>
    <td>Second Major</td>
    <td colspan="2"><input type="text" name="secondmajor" value="{$staff[SECONDMAJOR]}"/></td>
  </tr>
  <tr>
    <td>Graduate School</td>
    <td colspan="3"><input type="text" name="graduateschool" value="{$staff[GRADUATESCHOOL]}"/></td>
    <td>Graduation time</td>
    <td colspan="2"><input type="text" name="graduatetime" value="{$staff[GRADUATETIME]}"/></td>
  </tr>
  <tr>
    <td>Foreign Language</td> 
	<td colspan="7"><input type="text" name="foreignlanguage" value="{$staff[FOREIGNLANGUAGE]}"/></td>
  </tr>
  <tr>
    <td>Computer ability</td>
    <td colspan="6"><input type="text" name="computerability" value="{$staff[COMPUTERABILITY]}" /></td>
  </tr>
  <tr>
    <td>Religious belief</td>
    <td colspan="3"><input type="text" name="religion" value="{$staff[RELIGION]}"/></td>
    <td>E-mail</td>
    <td colspan="2"><input type="text" name="email" value="{$staff[EMAIL]}"/></td>
  </tr>
  <tr>
    <td>Home address</td>
    <td colspan="6"><input type="text" name="familyaddress" value="{$staff[FAMILYADDRESS]}"/></td>
  </tr>
  <tr>
    <td>Telephone</td>
    <td colspan="3"><input type="text" name="telphone" value="{$staff[TELPHONE]}"/></td>
    <td>Cellphone</td>
    <td colspan="2"><input type="text" name="cellphone" value="{$staff[CELLPHONE]}"/></td>
  </tr>
</table>
<table class="table" id="table2" style="width:70%">
  <tr>
    <td rowspan="5">Family Members</td>
    <td>Appellation</td>
    <td>Name</td>
    <td>Age</td>
    <td colspan="3">Job</td>
  </tr>
  <tr>
    <td><input type="text" name="appellation1"  value="{$family["familyMembers"][0]["appellation"]}"/></td>
    <td><input type="text" name="name1"  value="{$family["familyMembers"][0]["name"]}"/></td>
    <td><input type="text" name="age1"  value="{$family["familyMembers"][0]["age"]}"/></td>
    <td colspan="3"><input type="text" name="job1"  value="{$family["familyMembers"][0]["job"]}"/></td>
  </tr>
  <tr>
    <td><input type="text" name="appellation2"  value="{$family["familyMembers"][1]["appellation"]}"/></td>
    <td><input type="text" name="name2"  value="{$family["familyMembers"][1]["name"]}"/></td>
    <td><input type="text" name="age2"  value="{$family["familyMembers"][1]["age"]}"/></td>
    <td colspan="3"><input type="text" name="job2"  value="{$family["familyMembers"][1]["job"]}"/></td>
  </tr>
  <tr>
    <td><input type="text" name="appellation3"  value="{$family["familyMembers"][2]["appellation"]}"/></td>
    <td><input type="text" name="name3"  value="{$family["familyMembers"][2]["name"]}"/></td>
    <td><input type="text" name="age3"  value="{$family["familyMembers"][2]["age"]}"/></td>
    <td colspan="3"><input type="text" name="job3"  value="{$family["familyMembers"][2]["job"]}"/></td>
  </tr>
  <tr>
    <td><input type="text" name="appellation4" value="{$family["familyMembers"][3]["appellation"]}" /></td>
    <td><input type="text" name="name4" value="{$family["familyMembers"][3]["name"]}" /></td>
    <td><input type="text" name="age4" value="{$family["familyMembers"][3]["age"]}" /></td>
    <td colspan="3"><input type="text" name="job4" value="{$family["familyMembers"][3]["job"]}" /></td>
  </tr>
  <tr>
    <td>Emergency Contact</td>
    <td colspan="3"><input type="text" name="emergencycontact" value="{$emergency["name"]}"/></td>
    <td>Telephone</td>
    <td colspan="2"><input type="text" name="emergencyphone" value="{$emergency["phone"]}" /></td>
  </tr>
  <tr>
    <td rowspan="6">Education Experience</td>
    <td colspan="2">Start and Stop Date</td>
    <td colspan="2">School and Major</td>
    <td colspan="2">Is graduated</td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="date1" value="{$education["educationexperiences"][0]["date"]}"/></td>
    <td colspan="2"><input type="text" name="school1" value="{$education["educationexperiences"][0]["school"]}"/></td>
    <td colspan="2"><input type="text" name="graduate1" value="{$education["educationexperiences"][0]["graduate"]}"/></td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="date2" value="{$education["educationexperiences"][1]["date"]}"/></td>
    <td colspan="2"><input type="text" name="school2" value="{$education["educationexperiences"][1]["school"]}"/></td>
    <td colspan="2"><input type="text" name="graduate2" value="{$education["educationexperiences"][1]["graduate"]}"/></td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="date3" value="{$education["educationexperiences"][2]["date"]}"/></td>
    <td colspan="2"><input type="text" name="school3" value="{$education["educationexperiences"][2]["school"]}"/></td>
    <td colspan="2"><input type="text" name="graduate3" value="{$education["educationexperiences"][2]["graduate"]}"/></td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="date4" value="{$education["educationexperiences"][3]["date"]}"/></td>
    <td colspan="2"><input type="text" name="school4" value="{$education["educationexperiences"][3]["school"]}"/></td>
    <td colspan="2"><input type="text" name="graduate4" value="{$education["educationexperiences"][3]["graduate"]}"/></td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="date5" value="{$education["educationexperiences"][4]["date"]}"/></td>
    <td colspan="2"><input type="text" name="school5" value="{$education["educationexperiences"][4]["school"]}"/></td>
    <td colspan="2"><input type="text" name="graduate5" value="{$education["educationexperiences"][4]["graduate"]}"/></td>
  </tr>
  <tr>
    <td rowspan="5">Work Experience</td>
    <td colspan="2">Start and Stop date</td>
    <td colspan="4">Job</td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="jobdate1" value="{$work["workexperiences"][0]["date"]}"/></td>
    <td colspan="4"><input type="text" name="job1" value="{$work["workexperiences"][0]["job"]}"/></td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="jobdate2" value="{$work["workexperiences"][1]["date"]}" /></td>
    <td colspan="4"><input type="text" name="job2" value="{$work["workexperiences"][1]["job"]}" /></td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="jobdate3" value="{$work["workexperiences"][2]["date"]}" /></td>
    <td colspan="4"><input type="text" name="job3" value="{$work["workexperiences"][2]["job"]}"/></td>
  </tr>
  <tr>
    <td colspan="2"><input type="text" name="jobdate4" value="{$work["workexperiences"][3]["date"]}"/></td>
    <td colspan="4"><input type="text" name="job4" value="{$work["workexperiences"][3]["job"]}" /></td>
  </tr>
  <tr>
    <td colspan="1">Awards during school</td>
	<td colspan="6"><textarea name="awards" rows="6" style="font-size:18px" cols="80" value="{$staff[AWARDS]}">{$staff[AWARDS]}</textarea></td>
  </tr>
  <tr>
    <td colspan="1">Specialty</td>
  	<td colspan="6"><textarea name="speciality" rows="6" style="font-size:18px" cols="80" value="{$staff[SPECIALITY]}">{$staff[SPECIALITY]}</textarea></td>
  </tr>
  <tr>
    <td colspan="1">Training Experience</td>
    <td colspan="6" ><textarea name="trainexperience" rows="6" style="font-size:18px" cols="80" value="{$staff[TRAINEXPERIENCE]}">{$staff[TRAINEXPERIENCE]}</textarea></td>
  </tr>
  <tr>
    <td colspan="1">Self Assessment</td>
 	<td colspan="6"><textarea name="selfassessment" rows="6" style="font-size:18px" cols="80" value="{$staff[SELFASSESSMENT]}">{$staff[SELFASSESSMENT]}</textarea>    </td> 
  </tr>
  <tr>
    <td colspan="1">Remark</td>
 	<td colspan="6"><textarea style="font-size:18px" name="remark" rows="6" cols="80" value="{$staff[REMARK]}">{$staff[REMARK]}</textarea></td> 
  </tr>
</table>
	<input type="hidden" name="familymember" value="" />
	<input type="hidden" name="emergency" value="" />
	<input type="hidden" name="educationexperience" value="" />
	<input type="hidden" name="workexperience" value="" />
	<input type="submit" style="margin-left:100px" class="btn btn-primary" value="submit"/>

	
	</form>
</div>

form;
?>