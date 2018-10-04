<?php 
class dbaccess {

	var $error_msg;		// holds error messages
	var $host;			// Your MySQL host, may need to add port number as well
	var $user;			// The user name you will be using to connect. Should not be root
	var $pass;			// The password for $user
	var $db;			// The database you are connecting to
	var $conn;			// database link
    var $xx;
	var $port;
	var $ms;
	var $furl;

    function dbaccess(){
		$this->host = 'localhost';
		$this->user = 'root';
		$this->pass = '';
		$this->db 	= 'encryption_interface';
		$this->port = '3306';
		$this->connect(true);
		/*
		global $greyboxurl; $mailsender;
		$mailsender = 'noreply@zeesoftonline.com';
		$this->ms = $mailsender;
		$urlparts = explode('/',$_SERVER['REQUEST_URI']); 
		$urlhost = trim($_SERVER['HTTP_HOST']);
		$greyboxurl = 'http://'.$urlhost.'/'.trim($urlparts[1]).'/greybox/';
		$this->furl = 'http://'.$urlhost.'/'.trim($urlparts[1]);
		*/
	}

     function dbaccess2($host,$user,$pass,$db){
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db 	= $db;
        $xx = $host;
	}

	function connect($persist=true){
		if( $persist )
			$this->conn = mysql_pconnect($this->host, $this->user, $this->pass);
		else
			$this->conn = mysql_connect($this->host, $this->user, $this->pass);
		if (!$this->conn){
			$this->error_msg = mysql_error();
			return false;
		}
		if (!mysql_select_db($this->db)){
			$this->error_msg = mysql_error();
			return false;
		}
		return true;
	}

	function disconnect(){
		mysql_close($this->conn);
	}

	function get_all($sql){
		$results = dbaccess::query($sql);
		if (!$results){
			$this->error_msg = mysql_error();
			return false;
		}
		return $results;
	}

	function get_row($sql){
		$results = dbaccess::query($sql);
		if (!$results){
			$this->error_msg = mysql_error();
			return false;
		}
		if (dbaccess::num_rows($results) > 1){
			$this->error_msg = "Your query returned more than one result";
			return false;
		}
		return $results;
	}

	function query($sql){
		$return = 0;
		$results = mysql_query($sql);
		if (!$results){
			return $return;
		}
		return $results;
	}
	
	function query_nonselect($sql){
		$results = dbaccess::query($sql);
		$value = mysql_affected_rows();
		return $value;
	}
	
	function num_rows($dbquery){
		return mysql_num_rows($dbquery);
	}
	
	function get_array($dbquery){
		return mysql_fetch_array($dbquery);
	}
	
	function insert_id(){
		$sql = "SELECT LAST_INSERT_ID()";
		$a = dbaccess::query($sql);
		$b = dbaccess::get_array($a);
		return $b[0];
	}	
	
	function get_assoc($dbquery){
		return mysql_fetch_assoc($dbquery);
	}
	
	function data_seek($dbquery,$int){
		return mysql_data_seek($dbquery,$int);
	}
	
	function escape_string($string){
		return mysql_real_escape_string($string);
	}

	function add_slashes($string){
		if (get_magic_quotes_gpc()) {
			return $string;
		}else {
			return addslashes($string);
		}
	}

}
?>