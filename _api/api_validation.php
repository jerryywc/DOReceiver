<?php require_once "../_require/dbconn.php"?>
<?php

	$donum = $_REQUEST['donum'];
	$donum_wildcard = '%' . $donum . '%';
	$FullName = $_REQUEST['auth_id'];

	$transporterid;
	$do_upload;
	$curr_date = date('Y-m-d');
	$next_date = date('Y-m-d', strtotime("-10 days"));
	$invoiceid;

	try{
        
        // get the user info to determine if user is transporter or admin (do_upload = 1)       
        $sql = "SELECT * FROM auth_id where FullName = ? ";

        if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"s",$FullName);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        if($row = mysqli_fetch_array($result)) {
        	$transporterid = $row['transporterid'];
        	$do_upload = $row['do_upload'];
        } else {
        	echo "err : Either Your ID not found or You're not authorized. Name: " . $FullName;
        	exit;
        }


        if(!empty($transporterid)){ // If user is transporter

        	$sql = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE ? AND tranportercode = ? AND gatepassdate >= ? ORDER BY invoiceid DESC";

        	if($stmt = mysqli_prepare($conn, $sql)){       
	            mysqli_stmt_bind_param($stmt,"sss",$donum_wildcard, $transporterid, $next_date);
	            $result = mysqli_stmt_execute($stmt);
	        } 

	        $result = $stmt -> get_Result();                
	        
	        if($row = mysqli_fetch_array($result)) { 
	        	$invoiceid = $row['invoiceid'];
	        }

        } else if($do_upload == 1){ // If user is admin

			$sql = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE ? AND img_name_1 = '' AND img_name_2 = '' AND img_name_3 = '' AND img_name_4 = ''  
					order by invoiceid desc";

			if($stmt = mysqli_prepare($conn, $sql)){       
	            mysqli_stmt_bind_param($stmt,"s",$donum_wildcard);
	            $result = mysqli_stmt_execute($stmt);
	        } 

	        $result = $stmt -> get_Result();

	        if($row = mysqli_fetch_array($result)) { 
		        $invoiceid = $row['invoiceid'];
	        } 
	    }

	    // If $invoiceid not found in lf_gatepass
	    if(empty($invoiceid)){
	    	$sql = "SELECT * FROM lf_gatepass_temp WHERE invoiceid LIKE ? order by invoiceid desc";

			if($stmt = mysqli_prepare($conn, $sql)){       
	            mysqli_stmt_bind_param($stmt,"s",$donum_wildcard);
	            $result = mysqli_stmt_execute($stmt);
	        } 

	        $result = $stmt -> get_Result();

	        if($row = mysqli_fetch_array($result)) { 
		        $invoiceid = $row['invoiceid'];
	        } 
	    }

	    if(!empty($invoiceid)){
	    	echo $invoiceid;
	    	exit;
	    } else {
	    	echo "err : Invoice not found, please contact IT with this info (Doc Num: " . $donum . " | Transporter Id: " . $transporterid . " | do_upload: " . $do_upload . ")";
	    }

	} catch (mysqli_sql_exception $e){
        echo $e->getMessage();    
    }    




?>
