<?php

function connect()
	{
	error_reporting(0);
	
	/*
		$host_user = "localhost";
		$user_user = "lubeapps";
		$password_user = "posim@2015";
		$database = "lubeapps_hirev";
		
		//$mysqli = new mysqli("localhost", "root", "");
		$query_conn = mysql_connect("localhost", "root", "");
		*/
		$host_user = "localhost";
		$user_user = "hirevadm_dorec";
		$password_user = "Dorec@Hirevadmin";
		$database = "hirevadm_db_dev";
		
		//$mysqli = new mysqli("localhost", "root", "");
		$query_conn = mysql_connect("localhost", $user_user, $password_user);
		
		/*
		if($query_conn){
			echo "<script>alert(\"Awesome!\");</script>";
		}else{
			echo "<script>alert(\"Access Denied!\");</script>";
		}*/
		
		if(!$query_conn)
		{
		echo "<script>alert(\"Error!\");</script>";
			return false;
		}
		if(!mysql_select_db($database))
		{
		echo "<script>alert(\"Connection Lost!\");</script>";
			return false;
		}
		return $query_conn;
	}
	
?>	
