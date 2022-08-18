<?php require_once "_require/dbconn.php"?>

<?php //session_start(); 
	//$nameid = $_REQUEST['NID'];
	$NID = htmlspecialchars($_GET['NID']);
	$FullName;
	$do_upload;
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
					$do_upload = $row['do_upload'];
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
<!doctype html>
<html lang="en">
	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Upload POD - Bulk Upload</title>
		<link rel="icon" type="image/png" href="https://www.hi-rev.com.my/hirev_web/IMG/icon32x32.png" sizes="32x32">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

		<style>
			body{
				font-size:0.9em;
			}
			#do_table tr td{
				text-align:center;
			}
		</style>

	</head>
	<body class="body" onload="getLocation();">

		
		<!-- Import notice -->
		<div style="width:60%; border:2px #FFA500 solid;margin:35px auto;">
			<h3 style="text-align:center; background-color:red; color:white">Important: Read this before proceeding</h3>
			<div style="padding:10px;">
				For <b>staffs in Wisma Posim or Seksyen 15 office</b>:
				<ol>
					<li>Rename the POD files by using first 8 digits of the DO document, eg: <i style="color:red"><b>80664110.jpg</b></i></li>
					<li>If there's more than 1 file per DO, then add _1, _2 after the DO number, eg: <i style="color:red"><b>80664110_1.jpg, 80664110_2.jpg</b></i></li>
					<li>Go to shared folder @ <i style="color:green"><b>10.1.1.145\admin_uploads\</b></i>, create a new folder with your own name, eg: <b>yongwc</b>, then copy DO into this folder.</li>
					<li>Enter the name of the folder (eg: <b>yongwc</b>) that you just created in the form below.
					<li>Click on Run button</li>
				</ol>
			</div>

			<div style="padding:10px;">
				For <b>outstation staffs</b>, kindly connect to Sophos VPN (traffic light), then follow the above steps.
			</div>
		</div>

		<!-- content -->
		<div style="width:60%; margin:0 auto;">
			<table width="100%">
				<tr>
					<td width="25%">Staff Name:</td>
					<td><input type="text" id="full_name" name="full_name" style="width:100%; background-color:#D9D9D9" value="<?=$FullName?>" readonly/></td>
				</tr>
				<tr>
					<td width="25%">Coordinate:</td>
					<td><input type="text" id="coordinate" name="coordinate" style="width:100%; background-color:#D9D9D9" readonly/></td>
				</tr>
				<tr>
					<td width="25%"><b>Folder Name</b>:</td>
					<td><input type="text" id="folder_name" name="folder_name" style="width:100%;" placeholder="eg: yongwc"/></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="button" id="run_button" value="Run" onclick="move_files()" style="padding:10px 30px"/><input type="button" id="reset_button" value="Clear" onclick="reset()" style="padding:10px 30px"/></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="hidden" id="json_respond" name="json_respond" style="width:100%;"/>
				</tr>
			</table>
			<div id="table" style="padding: 50px 0;">
			</div>
		</div>
	</body>
	<script>
		$( document ).ready(function() {

			$("#reset_button").hide();

		});

		// get coordinate
		function getLocation() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition);
			} else { 
				//alert("Geolocation is not supported by this browser.");
			}
			setTimeout(retryGetLocation, 1000);
		}

		// retry get location
		function retryGetLocation() {
			if (document.getElementById('coordinate').value == ""){
				setTimeout(getLocation, 1000);
			}
		}

		// display/assign coordination 
		function showPosition(position) {
			document.getElementById("coordinate").value = position.coords.latitude + "," + position.coords.longitude;
		}

		function move_files(){
			var folder = $("#folder_name").val();
				
			$.ajax({
				url: "https://edms.posim.com.my/do_uploads/_api/bulk_receiver.php", //live
				//url: "../do_uploads/_api/bulk_receiver.php", //test
				timeout:30000,
				type: "GET",
				data: {
					folder:folder
				},				
				success: function(response){
					console.log(response);
					var data = JSON.parse(response);

					if(data.status.startsWith("success")){
						$('#json_respond').val(data.msg);
						get_table();
						// update table
						// window.location.replace("rev4u_appointments.php");

					} else if(data.status.startsWith("failed")){
						alert(data.msg);
						//console.log(data.msg);
					}
				},
				error: function(jqXHR, textStatus){
		    	console.log(textStatus.toString());
		    	alert('Error encoutered, kindly check the console log');
		  	}
			});
		} // end of move_files()

		function get_table(){
			var json_text = $("#json_respond").val();
			var staff_name = $("#full_name").val();
			var coordinate = $("#coordinate").val();
				
			$.ajax({
				url: "_api/bulk_uploads_submit_gettable.php",
				timeout:30000,
				type: "POST",
				data: {
					json_text:json_text,
					staff_name:staff_name,
					coordinate:coordinate
				},				
				success: function(response){
					console.log(response);

					var data = JSON.parse(response);

					if(data.status.startsWith("success")){
						$("#table").html(data.msg);
						alert("POD upload successful. Pleaese double check the file should you encounter 'exists' or 'failed");
						$("#run_button").hide();
						$("#reset_button").show();
						// update table
						// window.location.replace("rev4u_appointments.php");

					} else if(data.status.startsWith("failed")){
						alert(data.msg);
						//console.log(data.msg);
					}
				},
				error: function(jqXHR, textStatus){
		    	console.log(textStatus.toString());
		    	alert('Error encoutered, kindly check the console log');
		  	}
			});
		} // end of move_files()

		function reset(){
			$("#folder_name").val("");
			$("#table").html("");
			$("#reset_button").hide();
			$("#run_button").show();
		}
	</script>
</html>