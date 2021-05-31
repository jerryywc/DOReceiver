<?php 
//session_start();

//$promo_code = htmlspecialchars($_GET["c"]);

if(isset($_SESSION['login_user']))
{ 

	require_once "dbconn.php";
	$conn=connect();

	$UserID = $_SESSION['login_user'];
	//$ID_Type = $_SESSION['login_type'];
		/*
			0 User
			1 Workshop
			2 Admin
		*/
	$Time = $_SESSION['last_login'];
	//$Status = $_SESSION['login_status'];
	//$Group_Dealer = $_SESSION['group_dealer'];
	$now = time();
	

/*
	if (($now - $Time) > 7200){ //original 3600
		echo "<script> alert('Your last session has expired. Please login again.');</script>";
		//echo $now." <> ".$Time." > 7200";
		session_destroy();
		echo "<script type='text/javascript'>window.open('header_login.php','_self');</script>";
		//echo "<script type='text/javascript'>window.open('expired.php','_self');</script>";
	}
*/	
	if($Status == '0'){
	
		//echo "<script> location.assign('change_password.php'); </script>";
	}

}else{
	$UserID = "";

	echo "<script type='text/javascript'>location.assign('page.php','_self');</script>";
	
}


//error handler function
function customError($errno, $errstr) {
  if ($_SESSION['login_type'] == "2"){
  	//echo "<b><font style='color:black; opacity: 0'>Error:</b> [$errno] $errstr</font>";
  }
  //echo "Hijau daun";
}

//set error handler
set_error_handler("customError");

//trigger error
//echo($test);


function curPageURL() {
  if(isset($_SERVER["HTTPS"]) && !empty($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] != 'on' )) {
        $url = 'https://'.$_SERVER["SERVER_NAME"];//https url
  }  else {
    $url =  'http://'.$_SERVER["SERVER_NAME"];//http url
  }
  if(( $_SERVER["SERVER_PORT"] != 80 )) {
     $url .= $_SERVER["SERVER_PORT"];
  }
  $url .= $_SERVER["REQUEST_URI"];
  return $url;
}

//echo curPageURL();


?>

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
