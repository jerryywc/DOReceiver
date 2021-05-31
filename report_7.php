<?php require_once "_require/dbconn.php"?>

<?php //session_start(); 
	//$nameid = $_REQUEST['NID'];
	$NID = htmlspecialchars($_GET['NID']);
	$FullName;
	$transporterid;

	try{
               
        $sql ="SELECT * FROM auth_id where FullName=? AND Status!='2' AND (transporterid!='' OR do_upload='1')";

        if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"s",$NID);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        if($row = mysqli_fetch_array($result)) {
        	$FullName = $row['FullName'];
        	$transporterid = $row['transporterid'];

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
<body class="body">
	<center>
<div class="content_">	
<?php 
require "menu.php"; 

$x = 0;
try{
	if(!empty($transporterid)){
		$sql = "SELECT * FROM lf_gatepass where transportercode=? AND coordinate!='' AND gps_date >= ( CURDATE() - INTERVAL 7 DAY )";
		if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"s",$transporterid);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        while($row = mysqli_fetch_array($result)) {
        	$x = $x + 1;

            if($x == 1){
                echo "<table border='0' cellpadding='0' cellspacing='0' class='tbl_content'>";
                echo "<tr><td> No. </td><td> Invoice </td><td> Inv. Date </td><td> Customer </td><td> &nbsp; </td></tr>";
            }

            echo "<tr><td> $x </td><td>" . $row['invoiceid'] . "</td><td>" . date('d-m-Y',strtotime($row['invoicedate'])) . "</td><td>". $row['accountname'] . "</td><td>" ;

            $img = "";
            if($row['img_name_1'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_1']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_1']."\");'>";
            }

            if($row['img_name_2'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_2']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_2']."\");'>";
            }

            if($row['img_name_3'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_3']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_3']."\");'>";
            }

            if($row['img_name_4'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_4']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_4']."\");'>";
            }

            echo $img."</td></tr>";

        }
	} else {
		$sql = "SELECT * FROM lf_gatepass where staff_id=? AND coordinate!='' AND gps_date >= ( CURDATE() - INTERVAL 7 DAY )";
		if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"s",$NID);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        while($row = mysqli_fetch_array($result)) {
        	$x = $x + 1;

            if($x == 1){
                echo "<table border='0' cellpadding='0' cellspacing='0' class='tbl_content'>";
                echo "<tr><td style='width:30px'> No. </td><td style='width:150px'> Invoice </td><td style='width:150px'> Inv. Date </td><td> Customer </td><td> &nbsp; </td></tr>";
            }

            echo "<tr><td> $x </td><td>" . $row['invoiceid'] . "</td><td>" . date('d-m-Y',strtotime($row['invoicedate'])) . "</td><td>". $row['accountname'] . "</td><td>" ;

            $img = "";
            if($row['img_name_1'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_1']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_1']."\");'>";
            }

            if($row['img_name_2'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_2']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_2']."\");'>";
            }

            if($row['img_name_3'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_3']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_3']."\");'>";
            }

            if($row['img_name_4'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_4']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_4']."\");'>";
            }

            echo $img."</td></tr>";

        }
	}

	if($x != 0){
        echo "</table>";
    }

} catch (mysqli_sql_exception $e){
    echo $e->getMessage();    
}    

    
?>
</div>
</center>

</body>
</html>