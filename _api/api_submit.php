<?php require_once "../_require/dbconn.php"?>

<?php

$id = $_REQUEST['donum'];
$gps = $_REQUEST['gps'];
$auth_id = $_REQUEST['auth_id'];

$img1 = $_REQUEST['img1'];
$img2 = $_REQUEST['img2'];
$img3 = $_REQUEST['img3'];
$img4 = $_REQUEST['img4'];

$newname = substr($id,0,8);

$image_1 = "";
$image_2 = "";
$image_3 = "";
$image_4 = "";

$gps = trim($gps);

if($img1 == "" && $img2 == "" && $img3 == "" && $img4 == ""){ // check in gps only, no images
//----------- Part 1 - No document upload

    if($gps != ""){ //no image, but has gps
        $today = date('Y-m-d');
        $now = date('H:i:s');

        date_default_timezone_set("Asia/Kuala_Lumpur");
        $datetime = date('Y-m-d H:i:s');
        $date_only = date('Y-m-d');
        $time_only = date('H:i:s');

        // Check if DO exists in lf_gatepass
        //$sql = "SELECT * FROM lf_gatepass WHERE invoiceid=? AND coordinate='' AND gps_date = ''  AND gps_time = ''";
        $sql = "SELECT * FROM lf_gatepass WHERE invoiceid=? ";

        if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"s",$id);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        // if record found in lf_gatepass
        if($row = mysqli_fetch_array($result)) {

            // check if coordinate already been set
            $coordinate = $row['coordinate'];

            if(!empty($coordinate)){
                //echo "<script>alert('Coordinate already exist.');</script>";
                
            } else {

                // Update record            
                $sql = "UPDATE lf_gatepass SET coordinate = ?, gps_date='$date_only', gps_time='$time_only', sync_out = '1', staff_id = ?
                        WHERE invoiceid = ?";

                if($stmt = mysqli_prepare($conn, $sql)){       
                    mysqli_stmt_bind_param($stmt,"sss",$gps, $auth_id, $id);
                    mysqli_stmt_execute($stmt);
                } 

                if(mysqli_affected_rows($conn) > 0){
                    echo "<script>alert('Successful!');</script>";
                    
                } else {
                    echo "<script>alert('Update failed, error code: NOIMG001. Please contact administrator for help.');</script>";
                    
                }
            }

        }else{
            // record not found in lf_gatepass, try search in lf_gatepass_temp
            $sql = "SELECT * FROM lf_gatepass_temp WHERE invoiceid=? ";

            if($stmt = mysqli_prepare($conn, $sql)){       
                mysqli_stmt_bind_param($stmt,"s",$id);
                $result = mysqli_stmt_execute($stmt);
            } 

            $result = $stmt -> get_Result();                
            
            // if record found in lf_gatepass
            if($row = mysqli_fetch_array($result)) {
                // check if coordinate already been set
                $coordinate = $row['coordinate'];

                if(!empty($coordinate)){
                    //echo "<script>alert('Coordinate already exist.');</script>";
                    
                } else {

                    // Update record            
                    $sql = "UPDATE lf_gatepass_temp SET coordinate = ?, gps_date='$date_only', gps_time='$time_only', sync_out = '1', staff_id = ?
                            WHERE invoiceid = ?";

                    if($stmt = mysqli_prepare($conn, $sql)){       
                        mysqli_stmt_bind_param($stmt,"sss",$gps, $auth_id, $id);
                        mysqli_stmt_execute($stmt);
                    } 

                    if(mysqli_affected_rows($conn) > 0){
                        echo "<script>alert('Successful!');</script>";
                        
                    } else {
                        echo "<script>alert('Update failed, error code: NOIMG002. Please contact administrator for help.');</script>";
                        
                    }
                }
            }
           
        }
    }else{ // no img no gps
         echo "<script>alert('" . $id . " There nothing to update. Please contact administrator for help.');</script>";
    }


}
else
{ // check in gps and upload images

     //"h:i:sa
    $today = date('Y-m-d');
    $now = date('H:i:s');
    $curr = date('Y-m-d H:i:s');
    //BEGIN check Image AVAILLABLE?

    $target_dir = "../api/do/";

    $lf_gatepass_has_record = 0;
    $lf_gatepass_temp_has_record = 0;

    /*
    $check_sql = "SELECT * FROM lf_gatepass WHERE invoiceid='$id' ";
    $query_sql = mysql_query($check_sql);
    if($res_sql = mysql_fetch_assoc($query_sql)){*/

    // Check if DO exists
    //$sql = "SELECT * FROM lf_gatepass WHERE invoiceid=? AND coordinate=''";

    // check if DO in lf_gatepass
    $sql = "SELECT * FROM lf_gatepass WHERE invoiceid=?";

    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt,"s",$id);
        $result = mysqli_stmt_execute($stmt);
    } 

    $result = $stmt -> get_Result();                
        
    if($row = mysqli_fetch_array($result)) {
        $lf_gatepass_has_record = 1;

        // check in gps
        if($row['coordinate'] == "" and $gps != ""){
            date_default_timezone_set("Asia/Kuala_Lumpur");
            $datetime = date('Y-m-d H:i:s');
            $date_only = date('Y-m-d');
            $time_only = date('H:i:s');

            $sql = "UPDATE lf_gatepass 
                    SET coordinate = ?, gps_date='$date_only', gps_time='$time_only', sync_out = '1', 
                        staff_id = ?
                    WHERE invoiceid = ? AND coordinate='' AND gps_date = ''  AND gps_time = ''";

            if($stmt = mysqli_prepare($conn, $sql)){       
                mysqli_stmt_bind_param($stmt,"sss", $gps, $auth_id, $id);
                mysqli_stmt_execute($stmt);
            } 
        } else {
            date_default_timezone_set("Asia/Kuala_Lumpur");
            $datetime = date('Y-m-d H:i:s');
            $date_only = date('Y-m-d');
            $time_only = date('H:i:s');

            $sql = "UPDATE lf_gatepass 
                    SET gps_date='$date_only', gps_time='$time_only', sync_out = '1', 
                        staff_id = ?
                    WHERE invoiceid = ? AND gps_date = ''  AND gps_time = ''";

            if($stmt = mysqli_prepare($conn, $sql)){       
                mysqli_stmt_bind_param($stmt,"ss", $auth_id, $id);
                mysqli_stmt_execute($stmt);
            } 
        }
    } 

    // if DO not in lf_gatepass
    if($lf_gatepass_has_record == 0){

        // check if DO in lf_gatepass_temp
        $sql = "SELECT * FROM lf_gatepass_temp WHERE invoiceid=?";

        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt,"s",$id);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();          

        if($row = mysqli_fetch_array($result)) {
            $lf_gatepass_temp_has_record = 1;

            // check in gps
            if($row['coordinate'] == "" and $gps != ""){
                date_default_timezone_set("Asia/Kuala_Lumpur");
                $datetime = date('Y-m-d H:i:s');
                $date_only = date('Y-m-d');
                $time_only = date('H:i:s');

                $sql = "UPDATE lf_gatepass_temp
                        SET coordinate = ?, gps_date='$date_only', gps_time='$time_only', sync_out = '1', 
                            staff_id = ?
                        WHERE invoiceid = ? AND coordinate='' AND gps_date = ''  AND gps_time = ''";

                if($stmt = mysqli_prepare($conn, $sql)){       
                    mysqli_stmt_bind_param($stmt,"sss", $gps, $auth_id, $id);
                    mysqli_stmt_execute($stmt);
                } 
            }
        }
    }

    // if DO not in both lf_gatepass & lf_gatepass_temp
    if($lf_gatepass_has_record == 0 && $lf_gatepass_temp_has_record == 0){
        echo "<script>alert('DO $id not found. Please make sure the QR code has been scanned. If problem persists, contact IT administrator.');</script>";
        
    }
        
       
    $error = "";

    // upload image 1
    if($img1 == "1"){
        //if($row['img_name_1'] == "AAA"){
        //    echo "<script>alert('Failed to update. Cannot overwrite image');</script>";
        //}else{
            if (isset($_FILES["f_1_up"]["name"])) {
                $image_1 = $_FILES["f_1_up"]["name"];
            }     

            if ($image_1 != '') { // Image 1 detected

                $target_file = $target_dir . basename($_FILES['f_1_up']['name']);
                $error = $error . "Target file: " . $target_file;
                $temp = explode(".", $_FILES['f_1_up']['name']);
                $newfilename = $newname.'_1.'.end($temp);
                $target_file = $target_dir.$newfilename;
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

                //img end -------------------------------


                //BEGIN Check if it an image or fake image
                if(isset($_POST["submit"])){
                    $check = getimagesize($_FILES['f_1_up']['tmp_name']);
                    if($check != false){
                        $uploadOk = 1;
                    }else{
                        $uploadOk = 0;
                        $error = $error . "Check image failed.";
                    }
                }
                //END Check if it an image or fake image

                /* 20210521 Jerry - skip checking for exists, allow overwrite if not yet verified (status = 1)
                if(file_exists($target_file)){
                    $uploadOk = 0;
                    $error = $error . "File already exists: " . $target_file;
                }*/

                //BEGIN check file size
                if($_FILES['f_1_up']['size'] > 20000000){
                    $uploadOk = 0;
                    $error = $error . "File size exceed 20MB: " . $_FILES['f_1_up']['size'];
                }
                //END check file size

                //BEGIN Allow only certain format of image
                /*
                if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg')
                */
                if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' 
                    && $imageFileType != 'pdf')
                {
                    $uploadOk = 0;
                    $error = $error . "Invalid image filetype: " . $imageFileType;
                }
                //END Allow only certain format of image

                //BEGIN check NO above error ?
                if($uploadOk == 0){
                    echo "<script type='text/javascript'>alert(\"Fail upload image 1: " . $error . "\");window.history.go(-1);</script>";
                
                }else{
                    $upload_results = "";

                    // SERVER A - UPLOAD FILE VIA CURL POST
                    // (A) SETTINGS
                    $url = "http://edms.posim.com.my/do_uploads/receiver.php"; // Where to upload file to
                    //$file = __DIR__ . DIRECTORY_SEPARATOR . "README.txt"; // File to upload
                    $file = $_FILES['f_1_up']['tmp_name'];
                    //$upname = "uploaded.txt"; // File name to be uploaded as

                    // (B) NEW CURL FILE
                    $cf = new CURLFile($file, mime_content_type_X($file), $target_file);

                    // (C) CURL INIT
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, [
                        // ATTACH FILE UPLOAD
                        "upload" => $cf,
                        // OPTIONAL - APPEND MORE POST DATA
                        "KEY" => "VALUE"
                    ]);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // (D) CURL RUN
                    // (D1) GO!
                    $result = curl_exec($ch);

                    // (D2) CURL ERROR
                    if (curl_errno($ch)) {
                        echo "CURL ERROR - " . curl_error($ch);
                        echo "<script type='text/javascript'>alert(\"Image 1 CURL ERROR -  " . curl_error($ch) . "\");window.history.go(-1);</script>";
                    }

                    // (D3) CURL OK - DO YOUR "POST UPLOAD" HERE
                    else {
                        // $info = curl_getinfo($ch);
                        // print_r($info);
                        $upload_result = $result;
                    }

                    // (D4) DONE
                    curl_close($ch);

                    // no error, upload file
                    //if (move_uploaded_file($_FILES['f_1_up']['tmp_name'], $target_file)) {
                    if($upload_result == "OK"){
                        $today = date('Y-m-d');

                        // Update record
                        

                        if($lf_gatepass_has_record == 1) {
                            $sql = "UPDATE lf_gatepass SET img_name_1=?, sync_out='1', img_datetime=now(), staff_id = ?
                                WHERE invoiceid = ?";
                        } else if($lf_gatepass_temp_has_record == 1){
                            $sql = "UPDATE lf_gatepass_temp SET img_name_1=?, sync_out='1', img_datetime=now(), staff_id = ?
                                WHERE invoiceid = ?";
                        }


                        if($stmt = mysqli_prepare($conn, $sql)){       
                            mysqli_stmt_bind_param($stmt,"sss",$newfilename, $auth_id, $id);
                            mysqli_stmt_execute($stmt);
                        } 

                        if(mysqli_affected_rows($conn) > 0){
                            echo "<script>alert('Successful!');</script>";
                        } else {
                            echo "<script>alert('Failed to Update image 1. Please contact administrator for help.');</script>";
                        }
                            
                           
                    }else{ // unable to upload file
                        echo "<script>alert('Cannot upload to $target_file ');</script>";
                    }
                }

            }else{ // Image 1 not detected
                echo "<script> alert('Error : Please upload at least 1 image'); </script>";
                            
            }           
        //}
    } // end of if($img1 == "1")

    // upload image 2
    if($img2 == "1"){
        //if($row['img_name_2'] == "AAA"){
        //    echo "<script>alert('Failed to update. Cannot overwrite image');</script>";
        //}else{
            if (isset($_FILES["f_2_up"]["name"])) {
                $image_2 = $_FILES["f_2_up"]["name"];
            }     

            if ($image_2 != '') { // Image 1 detected

                $target_file = $target_dir . basename($_FILES['f_2_up']['name']);
                $error = $error . "Target file: " . $target_file;
                $temp = explode(".", $_FILES['f_2_up']['name']);
                $newfilename = $newname.'_2.'.end($temp);
                $target_file = $target_dir.$newfilename;
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

                //img end -------------------------------


                //BEGIN Check if it an image or fake image
                if(isset($_POST["submit"])){
                    $check = getimagesize($_FILES['f_2_up']['tmp_name']);
                    if($check != false){
                        $uploadOk = 1;
                    }else{
                        $uploadOk = 0;
                        $error = $error . "Check image failed.";
                    }
                }
                //END Check if it an image or fake image

                /* 20210521 Jerry - skip checking for exists, allow overwrite if not yet verified (status = 1)
                if(file_exists($target_file)){
                    $uploadOk = 0;
                    $error = $error . "File already exists: " . $target_file;
                }*/

                //BEGIN check file size
                if($_FILES['f_2_up']['size'] > 20000000){
                    $uploadOk = 0;
                    $error = $error . "File size exceed 20MB: " . $_FILES['f_2_up']['size'];
                }
                //END check file size

                //BEGIN Allow only certain format of image
                /*
                if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg')
                */
                if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' 
                    && $imageFileType != 'pdf')
                {
                    $uploadOk = 0;
                    $error = $error . "Invalid image filetype: " . $imageFileType;
                }
                //END Allow only certain format of image

                //BEGIN check NO above error ?
                if($uploadOk == 0){
                    echo "<script type='text/javascript'>alert(\"Fail upload image 2: " . $error . "\");window.history.go(-1);</script>";
                
                }else{
                    $upload_results = "";

                    // SERVER A - UPLOAD FILE VIA CURL POST
                    // (A) SETTINGS
                    $url = "http://edms.posim.com.my/do_uploads/receiver.php"; // Where to upload file to
                    //$file = __DIR__ . DIRECTORY_SEPARATOR . "README.txt"; // File to upload
                    $file = $_FILES['f_2_up']['tmp_name'];
                    //$upname = "uploaded.txt"; // File name to be uploaded as

                    // (B) NEW CURL FILE
                    $cf = new CURLFile($file, mime_content_type_X($file), $target_file);

                    // (C) CURL INIT
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, [
                        // ATTACH FILE UPLOAD
                        "upload" => $cf,
                        // OPTIONAL - APPEND MORE POST DATA
                        "KEY" => "VALUE"
                    ]);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // (D) CURL RUN
                    // (D1) GO!
                    $result = curl_exec($ch);

                    // (D2) CURL ERROR
                    if (curl_errno($ch)) {
                        echo "CURL ERROR - " . curl_error($ch);
                        echo "<script type='text/javascript'>alert(\"Image 2 CURL ERROR -  " . curl_error($ch) . "\");window.history.go(-1);</script>";
                    }

                    // (D3) CURL OK - DO YOUR "POST UPLOAD" HERE
                    else {
                        // $info = curl_getinfo($ch);
                        // print_r($info);
                        $upload_result = $result;
                    }

                    // (D4) DONE
                    curl_close($ch);

                    // no error, upload file
                    //if (move_uploaded_file($_FILES['f_1_up']['tmp_name'], $target_file)) {
                    if($upload_result == "OK"){
                    // no error, upload file
                    //if (move_uploaded_file($_FILES['f_2_up']['tmp_name'], $target_file)) {
                        $today = date('Y-m-d');

                        // Update record
                        

                        if($lf_gatepass_has_record == 1) {
                            $sql = "UPDATE lf_gatepass SET img_name_2=?, sync_out='1', img_datetime=now(), staff_id = ?
                                WHERE invoiceid = ?";
                        } else if($lf_gatepass_temp_has_record == 1){
                            $sql = "UPDATE lf_gatepass_temp SET img_name_2=?, sync_out='1', img_datetime=now(), staff_id = ?
                                WHERE invoiceid = ?";
                        }


                        if($stmt = mysqli_prepare($conn, $sql)){       
                            mysqli_stmt_bind_param($stmt,"sss",$newfilename, $auth_id, $id);
                            mysqli_stmt_execute($stmt);
                        } 

                        if(mysqli_affected_rows($conn) > 0){
                            echo "<script>alert('Successful!');</script>";
                        } else {
                            echo "<script>alert('Failed to Update image 2. Please contact administrator for help.');</script>";
                        }
                            
                           
                    }else{ // unable to upload file
                        echo "<script>alert('Cannot upload to $target_file ');</script>";
                    }
                }

            }    
        //}
    } // end of if($img2 == "1")

    // upload image 3
    if($img3 == "1"){
        //if($row['img_name_3'] == "AAA"){
        //    echo "<script>alert('Failed to update. Cannot overwrite image');</script>";
        //}else{
            if (isset($_FILES["f_3_up"]["name"])) {
                $image_3 = $_FILES["f_3_up"]["name"];
            }     

            if ($image_3 != '') { // Image 1 detected

                $target_file = $target_dir . basename($_FILES['f_3_up']['name']);
                $error = $error . "Target file: " . $target_file;
                $temp = explode(".", $_FILES['f_3_up']['name']);
                $newfilename = $newname.'_3.'.end($temp);
                $target_file = $target_dir.$newfilename;
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

                //img end -------------------------------


                //BEGIN Check if it an image or fake image
                if(isset($_POST["submit"])){
                    $check = getimagesize($_FILES['f_3_up']['tmp_name']);
                    if($check != false){
                        $uploadOk = 1;
                    }else{
                        $uploadOk = 0;
                        $error = $error . "Check image failed.";
                    }
                }
                //END Check if it an image or fake image

                /* 20210521 Jerry - skip checking for exists, allow overwrite if not yet verified (status = 1)
                if(file_exists($target_file)){
                    $uploadOk = 0;
                    $error = $error . "File already exists: " . $target_file;
                }*/

                //BEGIN check file size
                if($_FILES['f_3_up']['size'] > 20000000){
                    $uploadOk = 0;
                    $error = $error . "File size exceed 20MB: " . $_FILES['f_3_up']['size'];
                }
                //END check file size

                //BEGIN Allow only certain format of image
                /*
                if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg')
                */
                if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' 
                    && $imageFileType != 'pdf')
                {
                    $uploadOk = 0;
                    $error = $error . "Invalid image filetype: " . $imageFileType;
                }
                //END Allow only certain format of image

                //BEGIN check NO above error ?
                if($uploadOk == 0){
                    echo "<script type='text/javascript'>alert(\"Fail upload image 3: " . $error . "\");window.history.go(-1);</script>";
                
                }else{
                    $upload_results = "";

                    // SERVER A - UPLOAD FILE VIA CURL POST
                    // (A) SETTINGS
                    $url = "http://edms.posim.com.my/do_uploads/receiver.php"; // Where to upload file to
                    //$file = __DIR__ . DIRECTORY_SEPARATOR . "README.txt"; // File to upload
                    $file = $_FILES['f_3_up']['tmp_name'];
                    //$upname = "uploaded.txt"; // File name to be uploaded as

                    // (B) NEW CURL FILE
                    $cf = new CURLFile($file, mime_content_type_X($file), $target_file);

                    // (C) CURL INIT
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, [
                        // ATTACH FILE UPLOAD
                        "upload" => $cf,
                        // OPTIONAL - APPEND MORE POST DATA
                        "KEY" => "VALUE"
                    ]);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // (D) CURL RUN
                    // (D1) GO!
                    $result = curl_exec($ch);

                    // (D2) CURL ERROR
                    if (curl_errno($ch)) {
                        echo "CURL ERROR - " . curl_error($ch);
                        echo "<script type='text/javascript'>alert(\"Image 3 CURL ERROR -  " . curl_error($ch) . "\");window.history.go(-1);</script>";
                    }

                    // (D3) CURL OK - DO YOUR "POST UPLOAD" HERE
                    else {
                        // $info = curl_getinfo($ch);
                        // print_r($info);
                        $upload_result = $result;
                    }

                    // (D4) DONE
                    curl_close($ch);

                    // no error, upload file
                    //if (move_uploaded_file($_FILES['f_1_up']['tmp_name'], $target_file)) {
                    if($upload_result == "OK"){
                    // no error, upload file
                    //if (move_uploaded_file($_FILES['f_3_up']['tmp_name'], $target_file)) {
                        $today = date('Y-m-d');

                        // Update record
                        

                        if($lf_gatepass_has_record == 1) {
                            $sql = "UPDATE lf_gatepass SET img_name_3=?, sync_out='1', img_datetime=now(), staff_id = ?
                                WHERE invoiceid = ?";
                        } else if($lf_gatepass_temp_has_record == 1){
                            $sql = "UPDATE lf_gatepass_temp SET img_name_3=?, sync_out='1', img_datetime=now(), staff_id = ?
                                WHERE invoiceid = ?";
                        }


                        if($stmt = mysqli_prepare($conn, $sql)){       
                            mysqli_stmt_bind_param($stmt,"sss",$newfilename, $auth_id, $id);
                            mysqli_stmt_execute($stmt);
                        } 

                        if(mysqli_affected_rows($conn) > 0){
                            echo "<script>alert('Successful!');</script>";
                        } else {
                            echo "<script>alert('Failed to Update image 3. Please contact administrator for help.');</script>";
                        }
                            
                           
                    } else{ // unable to upload file
                        echo "<script>alert('Cannot upload to $target_file ');</script>";
                    }
                }

            }    
        //}
    } // end of if($img3 == "1")

    // upload image 4
    if($img4 == "1"){
        //if($row['img_name_4'] == "AAA"){
        //    echo "<script>alert('Failed to update. Cannot overwrite image');</script>";
        //}else{
            if (isset($_FILES["f_4_up"]["name"])) {
                $image_4 = $_FILES["f_4_up"]["name"];
            }     

            if ($image_4 != '') { // Image 4 detected

                $target_file = $target_dir . basename($_FILES['f_4_up']['name']);
                $error = $error . "Target file: " . $target_file;
                $temp = explode(".", $_FILES['f_4_up']['name']);
                $newfilename = $newname.'_4.'.end($temp);
                $target_file = $target_dir.$newfilename;
                $uploadOk = 1;
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

                //img end -------------------------------


                //BEGIN Check if it an image or fake image
                if(isset($_POST["submit"])){
                    $check = getimagesize($_FILES['f_4_up']['tmp_name']);
                    if($check != false){
                        $uploadOk = 1;
                    }else{
                        $uploadOk = 0;
                        $error = $error . "Check image failed.";
                    }
                }
                //END Check if it an image or fake image

                /* 20210521 Jerry - skip checking for exists, allow overwrite if not yet verified (status = 1)
                if(file_exists($target_file)){
                    $uploadOk = 0;
                    $error = $error . "File already exists: " . $target_file;
                }*/

                //BEGIN check file size
                if($_FILES['f_4_up']['size'] > 20000000){
                    $uploadOk = 0;
                    $error = $error . "File size exceed 20MB: " . $_FILES['f_4_up']['size'];
                }
                //END check file size

                //BEGIN Allow only certain format of image
                /*
                if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg')
                */
                if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' 
                    && $imageFileType != 'pdf')
                {
                    $uploadOk = 0;
                    $error = $error . "Invalid image filetype: " . $imageFileType;
                }
                //END Allow only certain format of image

                //BEGIN check NO above error ?
                if($uploadOk == 0){
                    echo "<script type='text/javascript'>alert(\"Fail upload image 4: " . $error . "\");window.history.go(-1);</script>";
                
                }else{
                    $upload_results = "";

                    // SERVER A - UPLOAD FILE VIA CURL POST
                    // (A) SETTINGS
                    $url = "http://edms.posim.com.my/do_uploads/receiver.php"; // Where to upload file to
                    //$file = __DIR__ . DIRECTORY_SEPARATOR . "README.txt"; // File to upload
                    $file = $_FILES['f_4_up']['tmp_name'];
                    //$upname = "uploaded.txt"; // File name to be uploaded as

                    // (B) NEW CURL FILE
                    $cf = new CURLFile($file, mime_content_type_X($file), $target_file);

                    // (C) CURL INIT
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, [
                        // ATTACH FILE UPLOAD
                        "upload" => $cf,
                        // OPTIONAL - APPEND MORE POST DATA
                        "KEY" => "VALUE"
                    ]);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // (D) CURL RUN
                    // (D1) GO!
                    $result = curl_exec($ch);

                    // (D2) CURL ERROR
                    if (curl_errno($ch)) {
                        echo "CURL ERROR - " . curl_error($ch);
                        echo "<script type='text/javascript'>alert(\"Image 4 CURL ERROR -  " . curl_error($ch) . "\");window.history.go(-1);</script>";
                    }

                    // (D3) CURL OK - DO YOUR "POST UPLOAD" HERE
                    else {
                        // $info = curl_getinfo($ch);
                        // print_r($info);
                        $upload_result = $result;
                    }

                    // (D4) DONE
                    curl_close($ch);

                    // no error, upload file
                    //if (move_uploaded_file($_FILES['f_1_up']['tmp_name'], $target_file)) {
                    if($upload_result == "OK"){
                    // no error, upload file
                    //if (move_uploaded_file($_FILES['f_4_up']['tmp_name'], $target_file)) {
                        $today = date('Y-m-d');

                        // Update record
                        

                        if($lf_gatepass_has_record == 1) {
                            $sql = "UPDATE lf_gatepass SET img_name_4=?, sync_out='1', img_datetime=now(), staff_id = ?
                                WHERE invoiceid = ?";
                        } else if($lf_gatepass_temp_has_record == 1){
                            $sql = "UPDATE lf_gatepass_temp SET img_name_4=?, sync_out='1', img_datetime=now(), staff_id = ?
                                WHERE invoiceid = ?";
                        }


                        if($stmt = mysqli_prepare($conn, $sql)){       
                            mysqli_stmt_bind_param($stmt,"sss",$newfilename, $auth_id, $id);
                            mysqli_stmt_execute($stmt);
                        } 

                        if(mysqli_affected_rows($conn) > 0){
                            echo "<script>alert('Successful!');</script>";
                        } else {
                            echo "<script>alert('Failed to Update image 4. Please contact administrator for help.');</script>";
                        }
                            
                           
                    } else{ // unable to upload file
                        echo "<script>alert('Cannot upload to $target_file ');</script>";
                    }
                }

            }    
        //}
    } // end of if($img4 == "1")



    




} // end of else (have img)
    
echo "<script>location.assign('/DOReceiver/index.php?NID=$auth_id');</script>";



?>

<?php



    function mime_content_type_X($f) {
      $filename = $f;

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $tmp_explode = explode('.',$filename);
        $ext = strtolower(array_pop($tmp_explode));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }


?>