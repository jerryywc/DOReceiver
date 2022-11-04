<?php require_once "_require/js.php"?>
<script type="text/javascript">


         /*code: 48-57 Numbers*/
         function restrictAlphabets(e) {
             var x = e.which || e.keycode;
             if ((x >= 48 && x <= 57))
                 return true;
             else
                 return false;
         }
      


//Get Coordinate
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
     //alert("Geolocation is not supported by this browser.");
    }
    setTimeout(retryGetLocation, 1000);
}

function retryGetLocation() {
   if (document.getElementById('gps').value == ""){
        setTimeout(getLocation, 1000);
   }else{
        //Success
   }
}

// display/assign coordination 
function showPosition(position) {
    document.getElementById("gps").value = position.coords.latitude + "," + position.coords.longitude;
    document.getElementById("gps_coor").innerHTML = position.coords.latitude + "," + position.coords.longitude;
}

// Search DO for invoiceid, coordinate. if invoiceid found, hide validate button, show image section. if coordinate not found, show check in button
function doSearch(){
    var donum = $('#donum').val();
    var auth_id = $('#auth_id').val();

    if(donum.length < 8){
        alert("Pleaes enter first 8 letters, eg: 80659171 OR KK001777");
        return;
    }

    $.ajax({
        url: "_api/do_search.php",
        timeout:30000,
        type: "POST",
        data: {
            donum:donum,
            auth_id:auth_id
        },
        success: function(response){

            var data = JSON.parse(response);
            console.log(data);

            if(data.status.startsWith("success")){
                $('#donum').val(data.invoiceid);
                $('#donum').prop("readonly", true);
                $('#validate_do_btn').hide();
                $('#imgscreen').show();

                $('#submit_btn').show(); // if record already has coordinate, then can submit with submit button
                //validate_data_gps(); ??
                if(data.coordinate == ""){
                    $('#checkin_save_btn').show();
                }
            } else if(data.status.startsWith("failed")){
                alert(data.msg);
                console.log(data.msg);
            }
                                
        },
        error: function(jqXHR, textStatus){
            console.log(textStatus.toString());
        }
    });
}

// submit form
function submit(){
    $('#form_update').submit();
}

// check in and submit
function checkInSubmit(){
    //getLocation();

    if (document.getElementById('gps').value == ""){
        alert("Cannot get your coordinate. Please allow GPS function control and retry again");
    } else {
        $('#form_update').submit();
    }
}

/*
Depreciated: get coordinate and submit
Replaced with: checkInSubmit()
*/
function getLocation_save(){
    if (document.getElementById('gps').value == ""){
        alert("Cannot get your coordinate. Please allow GPS function control");
   }else{
        //Success
        document.form_update.submit(); // action = api/api_submit.php
   }
    /*
    if (navigator.getLocation_save) {
        navigator.getLocation_save.getCurrentPosition(showPosition_save);
       //alert("Searching");
    } else { 
     //alert("Locating Failed");
    }
    setTimeout(doSomething_save, 1000);
    */
}

/* Not in use */
function doSomething_save() {
   if (document.getElementById('gps').value == ""){
        setTimeout(doSomething_save, 1000);
   }else{
        //Success
   }
}

/* Not in use */
function showPosition_save(position) {
    document.getElementById("gps").value = position.coords.latitude + "," + position.coords.longitude;
    //document.getElementById("gps_btn").style.display = "none";
    document.getElementById("gps_coor").innerHTML = position.coords.latitude + "," + position.coords.longitude;
    document.form_update.submit();
}








	 function showname(){
	        
	        var filename =  document.getElementById('f_2_up');
	        var newfilename;
	                    
	       // newfilename = filename.value.replace("C:\\fakepath\\", "");

	       // document.getElementById("txt_2_img").innerHTML = newfilename;
	    }

	     var loadfile1 = function(event) {
	    	var output = document.getElementById('p_image1');
	    	output.src = URL.createObjectURL(event.target.files[0]);
	    	document.form_update.img1.value = '1';
            document.getElementById('content_div_2').style.display = "inline";
	    };

        var loadfile2 = function(event) {
            var output = document.getElementById('p_image2');
            output.src = URL.createObjectURL(event.target.files[0]);
            document.form_update.img2.value = '1';
             document.getElementById('content_div_3').style.display = "inline";
        };

        var loadfile3 = function(event) {
            var output = document.getElementById('p_image3');
            output.src = URL.createObjectURL(event.target.files[0]);
            document.form_update.img3.value = '1';
             document.getElementById('content_div_4').style.display = "inline";
        };

        var loadfile4 = function(event) {
            var output = document.getElementById('p_image4');
            output.src = URL.createObjectURL(event.target.files[0]);
            document.form_update.img4.value = '1';
        };

        /* Not in used */
	    function submit_form(){
	    	document.form_update.submit();
	    }

        /* 
        Depreciated: Re-search for DO, if found, submit form
        Replaced with: submit()
        */
	    function validate(){
	    	var donum = document.getElementById('donum').value;
	    	var auth_id = document.getElementById('auth_id').value;
			
			if( document.getElementById("f_1_up").files.length == 0 ){
				//console.log("Please upload at least 1 images.");
				}

	    	 if (donum.length == 0 ) {
                //document.getElementById("txtHint").innerHTML = "";
                alert("Please fill DO Number");
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                    	//if(this.responseText.trim() == "Success"){
                    	if(this.responseText.trim() != ""){
                            var strf = this.responseText.trim();
                            var res = strf.substring(0, 3);
                            if(res == "err"){
                                alert(res);
                                return;
                            }else{
                    		  document.getElementById('donum').value = this.responseText.trim();
                    		  document.form_update.submit(); // action = api/api_submit.php
                            }
                    	}else{
                    		alert('invalid!' + this.responseText.trim());
                            return;
                    	}
                    	
                    }
                };
                xmlhttp.open("GET", "api/api_validation.php?donum=" + donum + "&auth=" + auth_id , true);
                xmlhttp.send();

            }
	    }

        /* 
        Depreciated: Retrieve DO number based on user input, if found, hide validate button, then show image upload section
        Replaced with: doSearch()
        */
        function validate_data(){
            var donum = document.getElementById('donum').value;
            var auth_id = document.getElementById('auth_id').value;
            

             if (donum.length == 0 ) {
                //document.getElementById("txtHint").innerHTML = "";
                alert("Please fill DO Number");
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //if(this.responseText.trim() == "Success"){
                        if(this.responseText.trim() != ""){
                            var strf = this.responseText.trim();
                            var res = strf.substring(0, 3);
                            if(res == "err"){
                                alert("1: " + strf);
                                return;
                            }else{
                              document.getElementById('donum').value = this.responseText.trim();
                              //document.form_update.submit();
                              document.getElementById('validate_btn').style.display = "none";
                              document.getElementById('imgscreen').style.display = "inline";
                              
                              validate_data_gps();
                            }
                        }else{
                            alert('invalid!' + this.responseText.trim());
                            return;
                        }
                        
                    }
                };
                xmlhttp.open("GET", "api/api_validation.php?donum=" + donum + "&auth=" + auth_id , true);
                xmlhttp.send();

            }
        }


        /*
        Depreciated: This function get the coordinate of current DO, if not exists, show checkin_save_btn
        Replaced with: doSearch()
        */
        function validate_data_gps(){
            var donum = document.getElementById('donum').value;
            var auth_id = document.getElementById('auth_id').value;
            
             if (donum.length == 0 ) {
                //document.getElementById("txtHint").innerHTML = "";
                alert("Please fill DO Number");
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //if(this.responseText.trim() == "Success"){
                        if(this.responseText.trim() == ""){
                            document.getElementById('checkin_save_btn').style.display = "inline";
                        }else{
                            //alert('invalid!' + this.responseText.trim());
                           // return;
                        }

                        validate_data_img();
                        
                    }
                };
                xmlhttp.open("GET", "_api/api_validation_gps.php?donum=" + donum + "&auth=" + auth_id , true);
                xmlhttp.send();

            }
        }

        /* 
        Depreciated: This function check if img_name_<n> columns are all empty, then show sumit_btn
        Replaced with: doSearch()
        */
        function validate_data_img(){            

            var donum = document.getElementById('donum').value;
            var auth_id = document.getElementById('auth_id').value;
            
             if (donum.length == 0 ) {
                //document.getElementById("txtHint").innerHTML = "";
                alert("Please fill DO Number");
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //if(this.responseText.trim() == "Success"){
                        if(this.responseText.trim() == ""){
                            document.getElementById('submit_btn').style.display = "inline";
                            //alert(this.responseText.trim());
                        }else{
                            //alert('invalid!' + this.responseText.trim());
                           // alert(this.responseText.trim());
                            return;
                        }

                        //validate_data_img();
                        
                    }
                };
                xmlhttp.open("GET", "api/api_validation_img.php?donum=" + donum + "&auth=" + auth_id , true);
                xmlhttp.send();

            }
        }
</script>

<?php
    $bulk_upload_btn = "";
    //reminder: $do_upload value is retrieved in index.php
    if(isset($do_upload) && $do_upload == '1'){
        $bulk_upload_btn = 
            "<a href='bulk_upload.php?NID=$FullName' 
                style='padding:10px;border:outset; margin:5px;text-decoration:none; color:black'>
                Bulk Upload
            </a>";

        $single_pod_upload_btn = 
            "<a href='single_scanned_file_upload.php?NID=$FullName' 
                style='padding:10px;border:outset; margin:5px;text-decoration:none; color:black'>
                Single Scanned File Upload
            </a>";
    }
?>

<div style="text-align:right; margin-top:15px;margin-bottom:35px;">
    <?=$bulk_upload_btn?><?=$single_pod_upload_btn?>
</div>



<p>
<form id="form_update" name="form_update" action="_api/api_submit.php" method="post" enctype="multipart/form-data">
DO Number <!--<input type="text" value="" id="donum" name="donum" class="input_" placeholder="- DO Number -" onkeypress='return restrictAlphabets(event)'>-->
<input type="text" value="" id="donum" name="donum" class="input_" placeholder="- DO Number -">
 &nbsp; 
<input type="button" name="checkin_save_btn" id="checkin_save_btn" class="btn_" value=" Check In " onclick="checkInSubmit()" style="display:none" > <br> (First 8 letters, eg: 80659171 OR KK001777)

<div id="imgscreen" style="display:none">
<!-- Upload Image 1 -->
<br><br><center><img id='p_image1' alt='No preview available...' src='#' style='font-size: 12px; color: #595959; height: 100px;'></center>
<!--
	<center><input type="button" name="btn_1_up" id="btn_1_up" class="input_" value=" Upload DO Page 1 " class="btn_" onclick="document.getElementById('f_1_up').click(); return false; showname();" style="cursor: pointer;"></center>
	<input type="file" class="f_1_up" id="f_1_up" name="f_1_up"  onchange="loadfile1(event); showname();" style="visibility: hidden;">
-->    
    <input type="file" class="f_1_up" id="f_1_up" name="f_1_up"  onchange="loadfile1(event); showname();" >
	<input type="hidden" id="img1" name="img1" value="">


<!-- Upload Image 2 -->
<div id="content_div_2" style="display: none">
<br><br><center><img id='p_image2' alt='No preview available...' src='#' style='font-size: 12px; color: #595959; height: 100px;'></center>
<!--
    <center><input type="button" name="btn_2_up" id="btn_2_up" class="input_" value=" Upload DO Page 2 " class="btn_" onclick="document.getElementById('f_2_up').click(); return false; showname();" style="cursor: pointer;"></center>
    <input type="file" class="f_2_up" id="f_2_up" name="f_2_up"  onchange="loadfile2(event); showname();" style="visibility: hidden;">
-->
    <input type="file" class="f_2_up" id="f_2_up" name="f_2_up"  onchange="loadfile2(event); showname();">
    <input type="hidden" id="img2" name="img2" value="">
</div>

<!-- Upload Image 3 -->
<div id="content_div_3" style="display: none">
<br><br><center><img id='p_image3' alt='No preview available...' src='#' style='font-size: 12px; color: #595959; height: 100px;'></center>
<!--
    <center><input type="button" name="btn_3_up" id="btn_3_up" class="input_" value=" Upload DO Page 3 " class="btn_" onclick="document.getElementById('f_3_up').click(); return false; showname();" style="cursor: pointer;"></center>
    <input type="file" class="f_3_up" id="f_3_up" name="f_3_up"  onchange="loadfile3(event); showname();" style="visibility: hidden;">
-->
    <input type="file" class="f_3_up" id="f_3_up" name="f_3_up"  onchange="loadfile3(event); showname();" >
    <input type="hidden" id="img3" name="img3" value="">
</div>
<!-- Upload Image 4 -->
<div id="content_div_4" style="display: none">
<br><br><center><img id='p_image4' alt='No preview available...' src='#' style='font-size: 12px; color: #595959; height: 100px;'></center>
<!--
    <center><input type="button" name="btn_4_up" id="btn_4_up" class="input_" value=" Upload DO Page 4 " class="btn_" onclick="document.getElementById('f_4_up').click(); return false; showname();" style="cursor: pointer;"></center>
    <input type="file" class="f_4_up" id="f_4_up" name="f_4_up"  onchange="loadfile4(event); showname();" style="visibility: hidden;">
-->
    <input type="file" class="f_4_up" id="f_4_up" name="f_4_up"  onchange="loadfile4(event); showname();" >
    <input type="hidden" id="img4" name="img4" value="">
</div>

</div>
	<p>
		<input type="button" name="submit_btn" id="submit_btn" class="btn_" value=" SAVE " onclick="submit()"  style="display:none">
        <!--
        <input type="button" name="validate_btn" id="validate_btn" class="btn_" value=" VALIDATE DO " onclick="validate_data()"  >
        -->
        <input type="button" name="validate_do_btn" id="validate_do_btn" class="btn_" value=" VALIDATE DO " onclick="doSearch()"  >
	</p>
<!--
    <p>
        <input type="button" name="gps_btn" id="gps_btn" class="btn_" value=" Check In " onclick="getLocation()">
    </p>
-->
<p><center>Coordinate : <span id='gps_coor'></span></center></p>
<input type="hidden" id="gps" name="gps" value="">
<input type="hidden" id="auth_id" name="auth_id" value="<?= $FullName ?>">

</form>
</p>