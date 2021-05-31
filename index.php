<?php require_once "_require/dbconn.php"?>

<?php //session_start(); 
	//$nameid = $_REQUEST['NID'];
	$NID = htmlspecialchars($_GET['NID']);
	$FullName;

	try{
               
        $sql ="SELECT * FROM auth_id where FullName=? AND Status!='2' AND (transporterid!='' OR do_upload='1')";

        if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"s",$NID);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        if($row = mysqli_fetch_array($result)) {
        	$FullName = $row['FullName'];        	
        }
	} catch (mysqli_sql_exception $e){
        echo $e->getMessage();    
    }    

    /*
	$qt = 0;
	if(isset($_GET['QT'])){
		$qt = htmlspecialchars($_GET['QT']); // ( 0 or null = Home ) ( 1 = My Record )
	}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>DO Check</title>
    <link rel="icon" type="image/png" href="http://www.hi-rev.com.my/hirev_web/IMG/icon32x32.png" sizes="32x32">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script> 
		$(document).ready(function(){
				  // $("#MTW").animate({width: 'toggle'});
				   // $('#MTW').fadeToggle('fast');
					  // $('#MTW').width(50);	 
		});

		$(document).ready(function(){

			$("#desk_menu_profile").click(function(){
		     $("#float_menu_hidden").animate({height: 'toggle'});
		  });


		});
	</script>

</head>
<body class="body" onload="getLocation();">
	<center>
<div class="content_">	
<?php 

	require "menu.php"; 

	if(isset($FullName)){
		require_once "content.php";
	} else {
		echo "<p>You are not Authorize to use this System. Please contact System administrator for validation.</p>";
	}

	//require_once "dbconn.php";
	//$conn=connect();

	//Validate USer existance
	//echo $nameid;
	/*
	$select_auth = "SELECT * FROM auth_id where FullName='$nameid' AND Status!='2' AND (transporterid!='' OR do_upload='1')";
	$query_auth = mysql_query($select_auth);
	if($row_auth = mysql_fetch_assoc($query_auth)){
		if($qt == "1"){
			require "content_record.php";
		} elseif($qt == "2"){
			require "content_record_3.php";
		} elseif($qt == "3"){
			require "content_record_7.php";
		}else{
			require "content.php";	
		}
	}else{
		echo "<p><br>You are not Authorize to use this System. Please contact System administrator for validation.<br><br></p>";
	}*/

?>
<!--
<img src="../0/IMG/adjuster.jpg" style="width:100%" />
-->

</div>
</center>

</body>
</html>