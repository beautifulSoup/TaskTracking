<?php
	header("Content-type: text/html; charset=utf-8");
	session_start();
	session_destroy();
	setcookie(session_name(),'', time()-3600);
	$_SESSION=array();
?>

<script type="text/javascript">
	var url = "./login.php";
	alert("Logout succeed");
	window.location.href = url;

</script>
	