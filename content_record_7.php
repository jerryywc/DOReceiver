<script type="text/javascript">

//Get Coordinate
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
       //alert("Searching");
    } else { 
     //alert("Locating Failed");
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

function showPosition(position) {
    document.getElementById("gps").value = position.coords.latitude + "," + position.coords.longitude;
   // document.getElementById("mygps").innerHTML = position.coords.latitude + "," + position.coords.longitude;
    //document.getElementById("lat").value = position.coords.latitude;
    //document.getElementById("lon").value = position.coords.longitude;
    //alert(document.getElementById("gps").value);
   // get_coords_name();
}




	    function validate(){
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
                            if(this.responseText.trim() == "err"){
                                alert("Either Your ID not found or You're not authorized.");
                                return;
                            }else{
                    		  document.getElementById('donum').value = this.responseText.trim();
                    		  document.form_update.submit();
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
</script>

<p>


<?php 
    $select_id = "SELECT * FROM auth_id where FullName='$nameid'";
    $query_id = mysql_query($select_id);
    if($row_id = mysql_fetch_assoc($query_id)){
        $transid = $row_id['transporterid'];

        $x = 0;
        if($transid != ''){
            $select_q = "SELECT * FROM lf_gatepass where transportercode='$transid' AND coordinate!='' AND gps_date >= ( CURDATE() - INTERVAL 7 DAY )";
            $query_q = mysql_query($select_q);
            while($row_q = mysql_fetch_assoc($query_q)):

                $x = $x + 1;

                if($x == 1){
                    echo "<table border='0' cellpadding='0' cellspacing='0' class='tbl_content'>";
                    echo "<tr><td> No. </td><td> Invoice </td><td> Inv. Date </td><td> Customer </td><td> &nbsp; </td></tr>";
                }

                echo "<tr><td> $x </td><td>" . $row_q['invoiceid'] . "</td><td>" . date('d-m-Y',strtotime($row_q['invoicedate'])) . "</td><td>". $row_q['accountname'] . "</td><td>" ;

                $img = "";
                if($row_q['img_name_1'] != ''){
                    $img = $img . "<img src='http://www.hi-rev.com.my/DOReceiver/api/do/".$row_q['img_name_1']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"http://www.hi-rev.com.my/DOReceiver/api/do/".$row_q['img_name_1']."\");'>";
                }

                if($row_q['img_name_2'] != ''){
                    $img = $img . "<img src='http://www.hi-rev.com.my/DOReceiver/api/do/".$row_q['img_name_2']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"http://www.hi-rev.com.my/DOReceiver/api/do/".$row_q['img_name_2']."\");'>";
                }

                if($row_q['img_name_3'] != ''){
                    $img = $img . "<img src='http://www.hi-rev.com.my/DOReceiver/api/do/".$row_q['img_name_3']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"http://www.hi-rev.com.my/DOReceiver/api/do/".$row_q['img_name_3']."\");'>";
                }

                if($row_q['img_name_4'] != ''){
                    $img = $img . "<img src='http://www.hi-rev.com.my/DOReceiver/api/do/".$row_q['img_name_4']."'  style='display:inline-block; width:100px; margin: 2px;' onclick='window.open(\"http://www.hi-rev.com.my/DOReceiver/api/do/".$row_q['img_name_4']."\");'>";
                }


                echo $img."</td></tr>";

            endwhile;

            if($x != 0){
                echo "</table>";
            }
        }
    }
?>

<input type="hidden" id="gps" name="gps" value="">
<input type="hidden" id="auth_id" name="auth_id" value="<?php echo $nameid; ?>">

</p>