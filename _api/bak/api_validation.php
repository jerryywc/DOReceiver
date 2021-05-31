<?php

$id = $_REQUEST['donum'];
$auth = $_REQUEST['auth'];

require_once "dbconn.php";
$conn=connect();

$select_user = "SELECT * FROM auth_id where FullName='$auth' ";
$query_user = mysql_query($select_user);
if($row_user = mysql_fetch_assoc($query_user)){
	$transid = $row_user['transporterid'];
	$do_upload = $row_user['do_upload'];

	$now_date = date('Y-m-d');
	$next_date = date('Y-m-d', strtotime("-10 days"));

	if($auth == "Muhammad Aizam Alif B Ahmad Mashudi"){
		//$next_date = date('Y-m-d', strtotime("-600 days"));
	}

	if($transid != ""){ // Transporter doing DO upload
		$select = "SELECT * FROM lf_gatepass 
					WHERE invoiceid LIKE '%$id%' 
					AND transportercode='$transid' 
					AND gatepassdate>='$next_date' 
					order by invoiceid desc";
		$query = mysql_query($select);
		if($row = mysql_fetch_assoc($query)){
			echo $row['invoiceid'];
		}else{
			// 20210520 Jerry select from lf_gatepass_temp
			//echo "err : invoice not found. Please check with admin to verify invoice date."; 
			$select = "SELECT * FROM lf_gatepass_temp
						WHERE invoiceid LIKE '%id%' 
						AND transportercode='$transid' 
						AND gatepassdate>='$next_date' 
						order by invoiceid desc";
			$query = mysql_query($select);
			if($row = mysql_fetch_assoc($query)){
				echo $row['invoiceid'];
			}else{
				echo "err : invoice not found. Please check with admin to verify invoice date."; 
			}
			// end of 20210520 Jerry select from lf_gatepass_temp
		}
	} else if($do_upload == 1){ // Admin who has the rights doing DO upload
		$select = "SELECT * FROM lf_gatepass 
					WHERE invoiceid LIKE '%$id%' 
					AND img_name_1 = '' 
					AND img_name_2 = '' 
					AND img_name_3 = '' 
					and img_name_4 = ''  
					order by invoiceid desc";
		$query = mysql_query($select);
		if($row = mysql_fetch_assoc($query)){
			echo $row['invoiceid'];
		}else{
			// 20210520 Jerry select from lf_gatepass_temp
			//echo "err : invoice not found. Please check with admin to verify invoice date."; 
			$select = "SELECT * FROM lf_gatepass_temp
						WHERE invoiceid LIKE '%$id%' 
						AND img_name_1 = '' 
						AND img_name_2 = '' 
						AND img_name_3 = '' 
						and img_name_4 = ''  
						order by invoiceid desc";
			$query = mysql_query($select);
			if($row = mysql_fetch_assoc($query)){
				echo $row['invoiceid'];
			}else{
				echo "err : invoice not found. Please check with admin to verify invoice date."; 
			}
			// end of 20210520 Jerry select from lf_gatepass_temp
		}
	}else{
		echo "err : transporterid not found";
	}

}else{
	echo "err : Either Your ID not found or You're not authorized.";
}



?>
