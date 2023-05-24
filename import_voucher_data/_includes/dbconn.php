<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try{
$mysqli_conn = mysqli_connect("localhost", "hirevadm_dorec", "Dorec@Hirevadmin", "hirevadm_db");
	if(!$mysqli_conn){
	    echo "Failed to connect to database";
	    exit;
	}

} catch (mysqli_sql_exception $e){
        echo $e->getMessage();    
        exit;
    }     
?>