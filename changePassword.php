<head>
    <!-- Loading Flat UI -->
<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">
<script src="./static/dist/js/flat-ui.min.js"></script>
	<script type="text/javascript" src="./static/js/md5.js"></script>
	<script type="text/javascript">
		function checkForm(){
			var oldPassword = document.getElementById('oldPassword').value;
			var newPassword = document.getElementById('newPassword').value;
			var confirm = document.getElementById('confirm').value;
			if(oldPassword==""){
				alert("The old password can't be empty!");
				return false;
			}
			else if(newPassword==""){
				alert("The new password can't be empty!");
				return false;
			}
			else if(confirm==""){
				alert("The confirm can't be empty!");
				return false;
			}
			else if(confirm != newPassword){
				alert("Your confirm password is not the same as the new password!");
				return false;
			}
			document.getElementById('oldPassword').value = faultylabs.MD5(oldPassword);
			document.getElementById('newPassword').value = faultylabs.MD5(newPassword);
			document.getElementById('confirm').value = faultylabs.MD5(newPassword);
			return true;
		}

		function submitForm(url){
			if(!checkForm()){
				return;
			}
			var data="";
			var oldPassword = document.getElementById('oldPassword').value;
			var newPassword = document.getElementById('newPassword').value;
			var confirm = document.getElementById('confirm').value;
			data = data+"oldPassword="+oldPassword+"&newPassword="+newPassword+"&confirm="+confirm;
			
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
					window.location.href = "./changePassword.php";
					
				}
			};
			url = "./userOp.php?type=0";
			xmlHttp.open("POST", url, true);
			xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xmlHttp.send(data);
		}

	</script>

</head>


<form style="font-family:Calibri" action="javascript:submitForm()">
<table>
<tr>
<td>Old Password: </td><td><input id="oldPassword" style="height:30" class="form-control" name="oldPassword" type="password"></input><br></td>
</tr>
<tr>
<td>New Password: </td><td><input id="newPassword" style="height:30"  class="form-control" name="newPassword" type="password"></input><br></td>
</tr>
<tr>

<td>Confirm: </td><td><input class="form-control" style="height:30" id="confirm" name="confirm" type="password"></input></td>
</tr>
<tr>
<td>
</td>
<td>
<input type="submit" class="btn btn-primary" value="submit"></input>
</td>
</tr>
</table>
</form>