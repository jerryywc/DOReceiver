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
			<h3 style="text-align:center; background-color:orange; color:white; padding: 10px;">Important: Read this before proceeding</h3>
			<div style="padding:10px;">
				<b style="color:orange">This is for uploading single scanned document that consists of multiple DO/Invoices. 1 scanned document for multiple DO/Invoices.</b>
			</div>
			<div style="padding:10px;">
				For <b>staffs in Wisma Posim or Seksyen 15 office</b>:
				<ol>
					<li>Select the file by clicking <b>Choose File</b> button.</li>
					<li>Enter DO/Invoice number and click validate. <b>Do not click Run</b> before validate your DO/Invoice as it won't do anything.</li>
					<li>Once validated, another input field will appear for you to enter next DO/Invoice number. <b>Repeat step above</b>.</li>
					<li>Click on <b>Run</b> button</li>
				</ol>
			</div>

			<div style="padding:10px;">
				For <b>outstation staffs</b>, kindly connect to Sophos VPN (traffic light), then follow the above steps.
			</div>
		</div>

		<!-- content -->
		<form id="inputform">
		<div style="width:60%; margin:0 auto;">
			<table width="100%" id="input_table">
				
				<tr>
					<td width="25%">Staff Name:</td>
					<td colspan="2"><input type="text" id="full_name" name="full_name" style="width:100%; background-color:#D9D9D9" value="<?=$FullName?>" readonly/></td>
				</tr>
				<tr>
					<td width="25%">Coordinate:</td>
					<td colspan="2"><input type="text" id="coordinate" name="coordinate" style="width:100%; background-color:#D9D9D9" readonly/></td>
				</tr>
				<tr>
					<td width="25%"><b>Scanned POD File</b>:</td>
					<td colspan="2"><input type="file" id="scanned_file" name="scanned_file" accept=".pdf, .jpg, .png" style="width:100%;"/></td>
				</tr>
				<tbody>
				<tr>
					<td width="25%"><b>DO No.</b>:</td>
					<td>
							<input type="text" name="do_number[]" style="width:100%" placeholder="DO No."/>
							<input type="hidden" name="verified_do_number[]"/>
					</td>
					<td>
							<input type="button" onclick="searchDO(this, 0)" value="Validate" name="validate_button[]"/>
					</td>
				</tr>
				</tbody>
				
			</table>
			</form>

			<table width="100%" id="button_table">
				<tr>
					<td></td>
					<td><input type="button" id="run_button" value="Run" onclick="move_files()" style="padding:10px 30px"/>
					<input type="button" id="reset_button" value="Clear" onclick="location.reload()" style="padding:10px 30px"/></td>
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

		function searchDO(ele, index){
			console.log("index:" + index);			
			
			var input = $('input[name="do_number[]"]').eq(index).val();
			console.log("value: " + input);

			if(input.length < 8) {
				alert("Please enter first 8 letters, eg: 80659171 OR KK001777");
				return;
			}

			var auth_id = '<?=$FullName?>';

			$.ajax({
						url: "_api/do_search.php",
						timeout:30000,
						type: "POST",
						data: {
								donum:input,
								auth_id:auth_id
						},
						success: function(response){

								var data = JSON.parse(response);
								console.log(data);

								if(data.status.startsWith("success")){
										//$('#donum').val(data.invoiceid);
										$('input[name="do_number[]"]').eq(index).val(data.invoiceid);
										$('input[name="do_number[]"]').eq(index).prop('readonly', true);
										$('input[name="do_number[]"]').eq(index).css('background-color', '#D4FECE');
										$('input[name="do_number[]"]').eq(index).css('border', '2px solid #26A414');

										$('input[name="verified_do_number[]"]').eq(index).val(data.invoiceid);

										

										var next_index = index + 1;

										$('#input_table > tbody:last-child').append('<tr><td width="25%"></td><td><input type="text" name="do_number[]" style="width:100%" placeholder="DO No."/><input type="hidden" name="verified_do_number[]"/></td><td><input type="button" onclick="searchDO(this,' + next_index + ')" value="Validate"  name="validate_button[]"/></td></tr>');
										$(ele).hide();

										/*
										$('#donum').prop("readonly", true);
										$('#validate_do_btn').hide();
										$('#imgscreen').show();

										$('#submit_btn').show(); // if record already has coordinate, then can submit with submit button
										//validate_data_gps(); ??
										if(data.coordinate == ""){
												$('#checkin_save_btn').show();
										}*/
								} else if(data.status.startsWith("failed")){
										alert(data.msg);
										console.log(data.msg);
								}
																		
						},
						error: function(jqXHR, textStatus){
								console.log(textStatus.toString());
						}
				});

			// TO DO: ajax call to validate DO, then set the returned value to 'verified_do_number' field, 
			// then append new row
			/*

			*/
		}

		
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
			var form = $('#inputform')[0];
      var data = new FormData(form);
				
			$.ajax({
				url: "_api/single_scanned_file_submit_gettable.php",
				timeout:30000,
				type: "POST",
				enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        data: data, 		
				success: function(response){
					//console.log(response);

					var data = JSON.parse(response);
					console.log("data:" + data.status);

					if(data.status.startsWith("success")){
						$("#table").html(data.msg);
						alert("POD upload successful. Pleaese double check the file should you encounter 'exists' or 'failed");

						$('input:button').each(function(){
							if ($(this).val() == 'Validate')  {
								$(this).hide();
							}
						});

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
		    	console.log("error:" + textStatus.toString());
		    	alert('Error encoutered, kindly check the console log');
		  	}
			});
		} // end of move_files()

		
	</script>
</html>