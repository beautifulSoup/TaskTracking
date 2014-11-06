<?php 
include("db.config.php");
class mydb{
	var $dbname;
	var $dbuser;
	var $dbpassword;
	var $dbhost;
	var $dbh;
	var $result;
	
	function __construct(){
		$this->dbname = DB;
		$this->dbuser = USER;
		$this->dbpassword = PASSWORD;
		$this->dbhost= HOST;
		//$this->init_charset();
		$this->connect();
		mysql_set_charset('utf8', $this->dbh);
	}
	
	function connect(){
		$this->dbh = mysql_connect( $this->dbhost, $this->dbuser, $this->dbpassword);
		
	}
	
	
	function query($sql){
		if(!$this->dbh){
			return false;
		}
		mysql_select_db($this->dbname, $this->dbh);
		$query= mysql_query($sql, $this->dbh);
		if(!$query){
			return FALSE;
		}
		$return_array= array();
		while($new = mysql_fetch_array($query)){
			$return_array[]=$new;
		}
		return $return_array;
	}
	
	
	function insert($sql){
		if(!$this->dbh){
			return false;
		}
		mysql_select_db($this->dbname, $this->dbh);
		$query= mysql_query($sql, $this->dbh);
		return $query;
	}
	

	function update($sql){
		if(!$this->dbh){
			return false;
		}
		mysql_select_db($this->dbname, $this->dbh);
		$query= mysql_query($sql, $this->dbh);
		return $query;
	}
	
	function delete($sql){
		if(!$this->dbh){
			return false;
		}
		mysql_select_db($this->dbname, $this->dbh);
		$query= mysql_query($sql, $this->dbh);
		return $query;
	}
	
	function count($sql){
		if(!$this->dbh){
			return false;
		}
		mysql_select_db($this->dbname, $this->dbh);
		$query= mysql_query($sql, $this->dbh);
		return $query;
	}
	
}

?>