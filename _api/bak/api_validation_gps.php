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

	if($transid != ""){
		$select = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE '%$id%' AND transportercode='$transid' order by invoiceid desc";
		$query = mysql_query($select);
		if($row = mysql_fetch_assoc($query)){
			echo $row['coordinate'];
		}else{
			//echo "err : invoice not found";
		}
	} else if($do_upload == 1){
		$select = "SELECT * FROM lf_gatepass WHERE invoiceid LIKE '%$id%' AND img_name_1 = '' AND img_name_2 = '' AND img_name_3 = '' and img_name_4 = '' order by invoiceid desc";
		$query = mysql_query($select);
		if($row = mysql_fetch_assoc($query)){
			echo $row['coordinate'];
		}else{
			//echo "err : invoice not found";
		}
	}else{
		//echo "err : transporterid not found";
	}

}else{
	//echo "err : Either Your ID not found or You're not authorized.";
}



?>
