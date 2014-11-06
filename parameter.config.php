<?php
/* this is the status definition of reports
 * 
 */

	define('STATUS_PENDING', 0);
	define('STATUS_SUBMITTED', 1);
	define('STATUS_DENY', 2);
	define('TEXT_PENDING','pending');
	define('TEXT_SUBMITTED', 'submitted');
	define('TEXT_DENY', 'deny');
/* this is the index of each colum of the tt_DAILY_report table;
 * 
 */
	define('DAILY_ID', 0);
	define('DAILY_AUTHOR', 1);
	define('DAILY_DATE', 2);
	define('DAILY_PROJECT', 3);
	define('DAILY_ROLES', 4);
	define('DAILY_HOURS', 5);
	define('DAILY_TASKS', 6);
	define('DAILY_DESCRIPTION', 7);
	define('DAILY_STATUS', 8);


	define('MONTH_ID', 0);
	define('AUTHOR_ID', 1 );
	define('MONTH_DATE', 2);
	define('MONTH_TASK', 3);
	define('MONTH_DESCRIPTION', 4);
	define('MONTH_HOURS', 5);
	define('VERIFIED_HOURS', 6);
	define('MONTH_STATUS', 7);

	define('USER_ID', 0);
	define('USER_PWD', 1);
	define('USER_ALIAS', 2);
	define('LEADER_ID', 3);
	define('USER_DEPARTMENT', 4);
	define('IS_LEADER', 5);
	define('IS_ADMIN', 6);
	define('StaffID', 7);
	
	
	//now there is the staff_inf column
	define('ID', 0);
	define('NAME', 1);
	define('GENDER', 2);
	define('BIRTHDAY', 3);
	define('USEDNAME', 4);
	define('WEIGHT', 5);
	define('HEIGHT', 6);
	define('NATION', 7);
	define('NATIVEPLACE', 8);
	define('MARITAL', 9);
	define('POLITICS', 10);
	define('HEALTH', 11);
	define('BLOODTYPE', 12);
	define('IDCard', 13);
	define('ACCOUNTTYPE', 14);
	define('ACCOUNTADDRESS', 15);
	define('EDUCATIONBACKGROUND', 16);
	define('DEGREE', 17);
	define('SECONDDEGREE', 18);
	define('MAJOR', 19);
	define('SECONDMAJOR', 20);
	define('GRADUATESCHOOL', 21);
	define('GRADUATETIME', 22);
	define('FOREIGNLANGUAGE', 23);
	define('COMPUTERABILITY', 24);
	define('RELIGION', 25);
	define('EMAIL', 26);
	define('FAMILYADDRESS', 27);

	define('CELLPHONE', 28);
	define('FAMILYMEMBER', 29);
	define('EMERGENCYCONTACT', 30);
	define('EDUCATIONEXPERIENCE', 31);
	define('WORKEXPERIENCE', 32);
	define('AWARDS', 33);
	define('SPECIALITY', 34);
	define('TRAINEXPERIENCE', 35);
	define('SELFASSESSMENT', 36);
	define('REMARK', 37);	
	define('TELPHONE', 38);
	define('PHOTO', 39);
/* the error number
 * 
 */
	define("USER_NOT_EXIST_ERROR", 0);
	define("PASSWORD_ERROR", 1);
	define("NO_ERROR", -1); 
	
	
	define("PAGE_NUM", 10);