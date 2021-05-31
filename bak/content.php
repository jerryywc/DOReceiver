<script type="text/javascript">

//Get Coordinate
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else { 
     //alert("Geolocation is not supported by this browser.");
    }
    setTimeout(doSomething, 1000);
}

function doSomething() {
   if (document.getElementById('gps').value == ""){
        setTimeout(doSomething, 1000);
   }else{
        //Success
   }
}

// display/assign coordination 
function showPosition(position) {
    document.getElementById("gps").value = position.coords.latitude + "," + position.coords.longitude;
    document.getElementById("gps_coor").innerHTML = position.coords.latitude + "," + position.coords.longitude;
}




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
function doSomething_save() {
   if (document.getElementById('gps').value == ""){
        setTimeout(doSomething_save, 1000);
   }else{
        //Success
   }
}
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

	    function submit_form(){
	    	document.form_update.submit();
	    }

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
                            document.getElementById('gps_btn').style.display = "inline";
                        }else{
                            //alert('invalid!' + this.responseText.trim());
                           // return;
                        }

                        validate_data_img();
                        
                    }
                };
                xmlhttp.open("GET", "api/api_validation_gps.php?donum=" + donum + "&auth=" + auth_id , true);
                xmlhttp.send();

            }
        }

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
<p>
<form id="form_update" name="form_update" action="api/api_submit.php" method="post" enctype="multipart/form-data">
DO Number <input type="text" value="" id="donum" name="donum" class="input_" placeholder="- DO Number -"> &nbsp; 
<input type="button" name="gps_btn" id="gps_btn" class="btn_" value=" Check In " onclick="getLocation_save()" style="display:none" > <br> (Last 5 - XXXXX)

<div id="imgscreen" style="display:none">
<!-- Upload Image 1 -->
<br><br><center><img id='p_image1' alt='no file selected...' src='#' style='font-size: 12px; color: #595959; height: 100px;'></center>
	<center><input type="button" name="btn_1_up" id="btn_1_up" class="input_" value=" Upload DO Page 1 " class="btn_" onclick="document.getElementById('f_1_up').click(); return false; showname();" style="cursor: pointer;"></center>
	<input type="file" class="f_1_up" id="f_1_up" name="f_1_up" accept="image/*" onchange="loadfile1(event); showname();" style="visibility: hidden;">
	<input type="hidden" id="img1" name="img1" value="">


<!-- Upload Image 2 -->
<div id="content_div_2" style="display: none">
<br><br><center><img id='p_image2' alt='no file selected...' src='#' style='font-size: 12px; color: #595959; height: 100px;'></center>
    <center><input type="button" name="btn_2_up" id="btn_2_up" class="input_" value=" Upload DO Page 2 " class="btn_" onclick="document.getElementById('f_2_up').click(); return false; showname();" style="cursor: pointer;"></center>
    <input type="file" class="f_2_up" id="f_2_up" name="f_2_up" accept="image/*" onchange="loadfile2(event); showname();" style="visibility: hidden;">
    <input type="hidden" id="img2" name="img2" value="">
</div>

<!-- Upload Image 3 -->
<div id="content_div_3" style="display: none">
<br><br><center><img id='p_image3' alt='no file selected...' src='#' style='font-size: 12px; color: #595959; height: 100px;'></center>
    <center><input type="button" name="btn_3_up" id="btn_3_up" class="input_" value=" Upload DO Page 3 " class="btn_" onclick="document.getElementById('f_3_up').click(); return false; showname();" style="cursor: pointer;"></center>
    <input type="file" class="f_3_up" id="f_3_up" name="f_3_up" accept="image/*" onchange="loadfile3(event); showname();" style="visibility: hidden;">
    <input type="hidden" id="img3" name="img3" value="">
</div>
<!-- Upload Image 4 -->
<div id="content_div_4" style="display: none">
<br><br><center><img id='p_image4' alt='no file selected...' src='#' style='font-size: 12px; color: #595959; height: 100px;'></center>
    <center><input type="button" name="btn_4_up" id="btn_4_up" class="input_" value=" Upload DO Page 4 " class="btn_" onclick="document.getElementById('f_4_up').click(); return false; showname();" style="cursor: pointer;"></center>
    <input type="file" class="f_4_up" id="f_4_up" name="f_4_up" accept="image/*" onchange="loadfile4(event); showname();" style="visibility: hidden;">
    <input type="hidden" id="img4" name="img4" value="">
</div>

</div>
	<p>
		<input type="button" name="submit_btn" id="submit_btn" class="btn_" value=" SAVE " onclick="validate()"  style="display:none">
        <input type="button" name="validate_btn" id="validate_btn" class="btn_" value=" VALIDATE DO " onclick="validate_data()"  >
	</p>
<!--
    <p>
        <input type="button" name="gps_btn" id="gps_btn" class="btn_" value=" Check In " onclick="getLocation()">
    </p>
-->
<p><center>Coordinate : <span id='gps_coor'></span></center></p>
<input type="hidden" id="gps" name="gps" value="">
<input type="hidden" id="auth_id" name="auth_id" value="<?php echo $nameid; ?>">

</form>
</p>