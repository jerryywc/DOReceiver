<?php require_once "../_require/dbconn.php"?>
<?php

	$response;
  if (!isset($response)) 
    $response = new stdClass();

	$donum = $_REQUEST['donum'];
	$donum_wildcard = '%' . $donum . '%';
	$FullName = $_REQUEST['auth_id'];

	$transporterid;
	$do_upload;
	$curr_date = date('Y-m-d');
	$next_date = date('Y-m-d', strtotime("-10 days"));


	$invoiceid;
	$coordinate;
	$img_name_1;
	$img_name_2;
	$img_name_3;
	$img_name_4;

	try{

		$sql = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE ? AND dms_status = 2";

		if($stmt = mysqli_prepare($conn, $sql)){       
			mysqli_stmt_bind_param($stmt,"s",$donum_wildcard);
			$result = mysqli_stmt_execute($stmt);
		} 

		$result = $stmt -> get_Result();                
				
		if($row = mysqli_fetch_array($result)) { 
			$response->status = "failed";
		  $response->msg = "Invoice already been approved";
		  $json_response = json_encode($response);
		  echo $json_response;
		  exit;
		}

        
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
     	$response->status = "failed";
		  $response->msg = "Either Your ID not found or You're not authorized. Name: " . $FullName;
		  $json_response = json_encode($response);
		  echo $json_response;
		  exit;
    }


    if(!empty($transporterid)){ // If user is transporter

     	//$sql = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE ? AND transportercode = ? AND gatepassdate >= ? AND dms_status != 2 ORDER BY invoiceid DESC";
			$sql = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE ? AND transportercode = ? AND dms_status != 2 ORDER BY invoiceid DESC";

     	if($stmt = mysqli_prepare($conn, $sql)){       
	      mysqli_stmt_bind_param($stmt,"ss",$donum_wildcard, $transporterid);
	      $result = mysqli_stmt_execute($stmt);
	    } 

	    $result = $stmt -> get_Result();                
	        
	    if($row = mysqli_fetch_array($result)) { 
	    	$invoiceid = $row['invoiceid'];
	    	$coordinate = $row['coordinate'];
	    	$img_name_1 = $row['img_name_1'];
	    	$img_name_2 = $row['img_name_2'];
	    	$img_name_3 = $row['img_name_3'];
	    	$img_name_4 = $row['img_name_4'];
	    }

    } else if($do_upload == 1){ // If user is admin

     	/*
			$sql = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE ? AND img_name_1 = '' AND img_name_2 = '' AND img_name_3 = '' AND img_name_4 = ''  
			order by invoiceid desc";
			*/
			// select non-verified invoice. 
			$sql = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE ? AND status != 1 AND dms_status != 2 
				order by invoiceid desc";

			if($stmt = mysqli_prepare($conn, $sql)){       
				mysqli_stmt_bind_param($stmt,"s",$donum_wildcard);
				$result = mysqli_stmt_execute($stmt);
			} 

			$result = $stmt -> get_Result();

			if($row = mysqli_fetch_array($result)) { 
				$invoiceid = $row['invoiceid'];
				$coordinate = $row['coordinate'];
				$img_name_1 = $row['img_name_1'];
				$img_name_2 = $row['img_name_2'];
				$img_name_3 = $row['img_name_3'];
				$img_name_4 = $row['img_name_4'];
			} 
		}

		// If $invoiceid not found in lf_gatepass
		if(empty($invoiceid)){
			$sql = "";
			if(!empty($transporterid)) {
				$sql = "SELECT * FROM lf_gatepass_temp WHERE invoiceid LIKE ? AND transportercode = '" . $transporterid . "' ORDER BY invoiceid DESC";
			} else {
				$sql = "SELECT * FROM lf_gatepass_temp WHERE invoiceid LIKE ? order by invoiceid desc";
			}

			if($stmt = mysqli_prepare($conn, $sql)){       
	       mysqli_stmt_bind_param($stmt,"s",$donum_wildcard);
	       $result = mysqli_stmt_execute($stmt);
	    } 

	    $result = $stmt -> get_Result();

	    if($row = mysqli_fetch_array($result)) { 
		    $invoiceid = $row['invoiceid'];
		    $coordinate = $row['coordinate'];
	    	$img_name_1 = $row['img_name_1'];
	    	$img_name_2 = $row['img_name_2'];
	     	$img_name_3 = $row['img_name_3'];
	     	$img_name_4 = $row['img_name_4'];
	    } 
	  }

	  if(!empty($invoiceid)){
	   	$response->status = "success";
		  $response->msg = "success";
		  $response->invoiceid = $invoiceid;
		  $response->coordinate = $coordinate;
		  $response->img_name_1 = $img_name_1;
		  $response->img_name_2 = $img_name_2;
		  $response->img_name_3 = $img_name_3;
		  $response->img_name_4 = $img_name_4;
		  $json_response = json_encode($response);
		  echo $json_response;
		  exit;
	  } else { 
	  	$response->status = "failed";
		  $response->msg = "Invoice not found, please contact IT with this info (Doc Num: " . $donum . " | Transporter Id: " . $transporterid . " | do_upload: " . $do_upload . ")";
		  $json_response = json_encode($response);
		  echo $json_response;
		  exit;
	  }

	} catch (mysqli_sql_exception $e){
    echo $e->getMessage();    
  }    




?>
