<?php
	error_reporting(0);
	// PHPExcel 
	require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

	// 生成新的excel对象
	$objPHPExcel = new PHPExcel();
	// 设置excel文档的属性
	$objPHPExcel->getProperties()->setCreator("Sam.c")
             ->setLastModifiedBy("Sam.c Test")
             ->setTitle("Microsoft Office Excel Document")
             ->setSubject("Test")
             ->setDescription("Test")
             ->setKeywords("Test")
             ->setCategory("Test result file");
	// 开始操作excel表
	// 操作第一个工作表
	$objPHPExcel->setActiveSheetIndex(0);
	// 设置工作薄名称
	$objPHPExcel->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', 'Member report export'));
	// 设置默认字体和大小
	$objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', ''));
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	$filename = "export.xls";

	$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No.');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', 'StaffID');
	$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Author');
	$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Date');
	$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Task');
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Verifed Hours');
	$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Description');

	require_once 'dbop.php';
	require_once 'parameter.config.php';
	ob_clean();
	$mydb = new mydb();
	session_start();
	if(!isset($_SESSION['STAFFID'])){
		header("Location: login.php");
	}
	$leaderName = $_SESSION['STAFFID'];
	$sql = "select * from tt_user where staffID = '{$leaderName}'";
	$results = $mydb->query($sql);
	//验证是否为leader账号
	
	if(!$results){
		echo "alert('Leader account query failed!')";
		header("Location: login.php");
	}
	else{
		$isLeader = $results[0][5];
		if(!$isLeader){
			echo "alert('Your are not a leader, sorry!')";
			header("Location: login.php");
				
		}
	}
	$leaderID = $results[0][0];
	$month = date("Y-m", time());
	$month = str_replace('-', '', $month);
	$sql ="select * from tt_month_report where extract(YEAR_MONTH from month_date) = '{$month}' and month_status = 1 and author_id in (select user_id from tt_user where leader_id = {$leaderID})";
	$results = $mydb->query($sql);
	if(!$results){
		//echo "Datebase query failed!";
		
	}
	else{
		//
		for($i=0;$i<count($results);$i++){
			$row = $i+2;
			$no = $i+1;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row,"".$no);
			$temp = $results[$i];
			$author_id = $temp[AUTHOR_ID];
			$sql = "select name from staff_inf where id = {$author_id}";
			$staffRet = $mydb->query($sql);
			if(!$staffRet){
				echo "No staff name!";
				exit();
			}
			$sql = "select staffID from tt_user where user_id = {$author_id}";
			$userRet = $mydb->query($sql);
			if(!$userRet){
				echo "No user inf";
				exit();
			}
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row,"".$userRet[0][0]);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row,"".$staffRet[0][0]);
			$tempDate = date("Y-m", strtotime($temp[MONTH_DATE]));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"".$tempDate);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row,"".$temp[MONTH_TASK]);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row,"".$temp[VERIFIED_HOURS]);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,"".$temp[MONTH_DESCRIPTION]);
		}
		
	}
	
	// 从浏览器直接输出$filename


	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
	header("Content-Type:application/force-download");
	header("Content-Type: application/vnd.ms-excel;");
	header("Content-Type:application/octet-stream");
	header("Content-Type:application/download");
	header("Content-Disposition:attachment;filename=".$filename);
	header("Content-Transfer-Encoding:binary");


	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save("php://output");

