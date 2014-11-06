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
	require_once 'dbop.php';
	$mydb = new mydb();
	$staffid = $_SESSION['STAFFID'];
	$sql = "select * from tt_user where staffID = '{$staffid}'";
	$results = $mydb->query($sql);
	if(!$results){
		echo "Error: Database query failed!<br>";
		exit();	
	}
	$isLeader = $results[0][5];
	$isAdmin = $results[0][6];
	$_SESSION['USERNAME'] = $results[0][2];
	
?>

<!DOCTYPE html>
<html xmlns = "http://www.w3.org/1992/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>tasktracking</title>


<link href="./static/dist/css/vendor/bootstrap.min.css" rel="stylesheet">

    <!-- Loading Flat UI -->
<link href="./static/dist/css/flat-ui.css" rel="stylesheet">
<link href="./static/css/main.css" rel="stylesheet">
<script src="./static/dist/js/vendor/jquery.min.js"></script>
<script src="./static/dist/js/flat-ui.min.js"></script>
<style type="text/css">

img{ 
border-style:none; 
} 

h6{
	back-ground: #ecf0f1;
}

</style>

<script type="text/javascript">


	function flush(url){
		var innerPage = document.getElementById('maincontent');
		innerPage.innerHTML="<iframe src='"+url+"' frameborder='no' width=900 height=1000></iframe>";
	
	}

	function edit(){

		url = "./informationEdit.php?id="+<?php echo $results[0][0]; ?>;
		var w = window.open(url, "");
	}
	function reload(){
		window.location.href = "./index.php";
	}

</script>


</head>
<body style="width:100%">
	<div class="container">
		<div class="row">
			<div class="col-xs-10">
				<img style="float:left" src="./static/img/title.jpg">
			</div>	
		</div>
		
		
		<div class="row">
			<div class="col-xs-12">
 <nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-01">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <a class="navbar-brand" href="javascript:void(0)" onclick="reload()">				
                <?php 
					$name = $_SESSION['USERNAME'];
					echo "{$name}";
					
				?></a>
            </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-01">
            <ul class="nav navbar-nav">           
                <li class="active"><a href="./logout.php">logout</a></li>
            </ul>           
            <form class="navbar-form navbar-right" action="#" role="search">
                <div class="form-group">
                    <div class="input-group">
                    <input class="form-control" id="navbarInput-01" type="search" placeholder="Search">
                        <span class="input-group-btn">
                            <button type="submit" class="btn"><span class="fui-search"></span></button>
                        </span>            
                    </div>
                </div>               
            </form>
        </div><!-- /.navbar-collapse -->
        </nav><!-- /navbar -->
			
			</div>
		</div>

		<div class ="row">
			<div class="col-xs-3">
				<div style="padding:3px" class="jumbotron">
					<div class="list-group">
   <a href="#" class="list-group-item active">
      <h6 class="list-group-item-heading">
         Report Manage
      </h6>
   </a>
   <a href="javascript:void(0)" onclick="flush('./dailyReportManage.php')" class="list-group-item">
      <span class="list-group-item-heading">
         Daily Report
      </span>

   </a>
   <a href="javascript:void(0)" onclick="flush('./monthReportManage.php')" class="list-group-item">
      <span class="list-group-item-heading">
		Month Report
      </span>

   </a>
   <a href="javascript:void(0)" onclick="flush('./dailyReportHistory.php')" class="list-group-item">
      <span class="list-group-item-heading">
		Daily History
      </span>

   </a>
	</div>
	
	<?php 
		if($isLeader){
		echo <<<leaderList
<div class="list-group">
   <a href="#" class="list-group-item active">
      <h6 class="list-group-item-heading">
         Leader Operation
      </h6>
   </a>
   <a href="javascript:void(0)" onclick="flush('./dailyReportVerify.php')" class="list-group-item">
      <span class="list-group-item-heading">
		Daily Verify        
      </span>

   </a>
   <a href="javascript:void(0)" onclick="flush('./monthReportVerify.php')" class="list-group-item">
      <span class="list-group-item-heading">
		Month Verify	
		</span>
   </a>
	<a href="javascript:void(0)" onclick="flush('./dailyReportExport.php')" class="list-group-item">
      <span class="list-group-item-heading">
		Daily Export	
		</span>
   </a>
   <a href="javascript:void(0)" onclick="flush('./monthlyReportExport.php')" class="list-group-item">
      <span class="list-group-item-heading">
		Monthly Export	
		</span>
   </a>

</div>
leaderList;
		}
		?>
<div class="list-group">
   <a href="#" class="list-group-item active">
      <h6 class="list-group-item-heading">
         User Manage
      </h6>
   </a>
   <a href="javascript:void(0)" onclick="flush('./changePassword.php')" class="list-group-item">
      <span class="list-group-item-heading">
         Change Password
      </span>

   </a>
     <a href="javascript:void(0)" onclick="flush('./informationEdit.php?id=<?php echo $results[0][0]?>')" class="list-group-item">
      <span class="list-group-item-heading">
         Information Edit
      </span>

   </a>
   
   <?php 
   if($isAdmin){
		echo <<<adminList

   <a href="javascript:void(0)" onclick="flush('./userManage.php')" class="list-group-item">
      <span class="list-group-item-heading">
		User Manage
		</span>
   </a>

adminList;
}
   
   ?>

</div>
				</div>
			</div>

			

				<div style="padding-left:0px;padding-top:0px" id="maincontent" class="col-xs-9">
					<img src="./static/img/welcome.jpg">
				
				</div>



	</div>

	</div>
	<footer>
<div style="height: 100px;" class="container">
<div class="row">
<div style="text-align:center;" class="col-xs-12 center">
<span>Copyright  2014 XiangTaoHuiSoft</span>
</div>
</div>
</div>
</footer>
</body>
</html>