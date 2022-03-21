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

        // Check if DO exists
        $sql = "SELECT * FROM lf_gatepass WHERE invoiceid=? AND coordinate='' AND gps_date = ''  AND gps_time = ''";

        if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"s",$id);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        if($row = mysqli_fetch_array($result)) {

            // Update record
            
            $sql = "UPDATE lf_gatepass SET coordinate = ?, gps_date='$date_only', gps_time='$time_only', sync_out = '1', staff_id = ?
                    WHERE invoiceid = ?";
            
            //$sql = "UPDATE lf_gatepass SET sync_out = '1', staff_id = ?
            //        WHERE invoiceid = ?";

            if($stmt = mysqli_prepare($conn, $sql)){       
                mysqli_stmt_bind_param($stmt,"sss",$gps, $auth_id, $id);
                mysqli_stmt_execute($stmt);
            } 

            if(mysqli_affected_rows($conn) > 0){
                echo "<script>alert('Successful!');</script>";
            } else {
                echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
            }

             //--- New GPS data.. update
            /*
            $q_update = "UPDATE lf_gatepass SET coordinate='$gps', gps_date='$today', gps_time='$now', sync_out='1', staff_id = '$auth_id' 
                        WHERE invoiceid='$id'";
            


            if($r_update = mysql_query($q_update)){
                echo "<script>alert('Successful!');</script>";
            }else{
                echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
            }*/
        }else{
           //--- GPS exist.. dont update
             echo "<script>alert('Failed to update. Coordinate already exist. Please contact administrator for help.');</script>";
        }
    }else{ // no img no gps
         echo "<script>alert('" . $id . " There nothing to update. Please contact administrator for help.');</script>";
    }


}else{ // check in gps and upload images

     //"h:i:sa
    $today = date('Y-m-d');
    $now = date('H:i:s');
    $curr = date('Y-m-d H:i:s');
    //BEGIN check Image AVAILLABLE?

    $target_dir = "../api/do/";

    /*
    $check_sql = "SELECT * FROM lf_gatepass WHERE invoiceid='$id' ";
    $query_sql = mysql_query($check_sql);
    if($res_sql = mysql_fetch_assoc($query_sql)){*/

    // Check if DO exists
    //$sql = "SELECT * FROM lf_gatepass WHERE invoiceid=? AND coordinate=''";
    $sql = "SELECT * FROM lf_gatepass WHERE invoiceid=?";

    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt,"s",$id);
        $result = mysqli_stmt_execute($stmt);
    } 

    $result = $stmt -> get_Result();                
        
    if($row = mysqli_fetch_array($result)) {

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
        }
        
       
        $error = "";

        //-------------------------------------------------------------------------------------- Image 1 Start
        if($img1 == "1"){
            if($row['img_name_1'] == "AAA"){
                 echo "<script>alert('Failed to update. Cannot overwrite image');</script>";
            }else{
                if (isset($_FILES["f_1_up"]["name"])) {
                    $image_1 = $_FILES["f_1_up"]["name"];
                }     
                ////////---------------------------------------------------------------- 1st Img 

                if ($image_1 != '') { //New Image detected

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

                    //BEGIN Check if file already exist
                    //if(file_exists($_SERVER['DOCUMENT_ROOT']."/".$target_file)){

                    /* 20210521 Jerry - skip checking for exists, allow overwrite if not yet verified (status = 1)
                    if(file_exists($target_file)){
                        $uploadOk = 0;
                        $error = $error . "File already exists: " . $target_file;
                    }*/

                    //END Check if file already exist

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
                        /*echo "$newfilename";
                        echo "<br>";
                        echo "success";*/ 
                        //$newfilename = $newfilename . "." . $imageFileType;

                        if (move_uploaded_file($_FILES['f_1_up']['tmp_name'], $target_file)) {
                            $today = date('Y-m-d');

                            /*
                                $q_update = "UPDATE lf_gatepass SET img_name_1='$newfilename', sync_out='1', img_datetime='$curr', staff_id = '$auth_id' WHERE invoiceid='$id'";
                                if($r_update = mysql_query($q_update)){
                                    echo "<script>alert('Successful!');</script>";
                                }else{
                                    echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                                }
                            */

                            // Update record
                            $sql = "UPDATE lf_gatepass SET img_name_1=?, sync_out='1', img_datetime=now(), staff_id = ?
                                    WHERE invoiceid = ?";

                            if($stmt = mysqli_prepare($conn, $sql)){       
                                mysqli_stmt_bind_param($stmt,"sss",$newfilename, $auth_id, $id);
                                mysqli_stmt_execute($stmt);
                            } 

                            if(mysqli_affected_rows($conn) > 0){
                                echo "<script>alert('Successful!');</script>";
                            } else {
                                echo "<script>alert('Failed to Update image 1. Please contact administrator for help.');</script>";
                            }
                            
                           
                        }else{
                            echo "<script>alert('Cannot upload to $target_file ');</script>";
                        }
                    }
                }else{ //Only update info : existing
                           
                    echo "<script> alert('Error : Please upload at least 1 image'); </script>";
                            
                }           
            }
        } // end of if($img1 == "1")



        //-------------------------------------------------------------------------------------- Image 2 Start
        if($img2 == "1"){
            if($row['img_name2'] == "AAA"){
                echo "<script>alert('Failed to update. Cannot overwrite image');</script>";
            }else{
                if (isset($_FILES["f_2_up"]["name"])) {
                    $image_2 = $_FILES["f_2_up"]["name"];
                }

                //////////----------------------------------------------------------------------- 2nd Img
                if ($image_2 != '') { //New Image detected

                    $target_file = $target_dir . basename($_FILES['f_2_up']['name']);
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
                        }
                    }
                    //END Check if it an image or fake image

                    /* 20210521 Jerry - skip checking for exists, allow overwrite if not yet verified (status = 1)
                    //BEGIN Check if file already exist
                    if(file_exists($target_file)){
                        $uploadOk = 0;
                    }
                    */

                    //END Check if file already exist

                    //BEGIN check file size
                    if($_FILES['f_2_up']['size'] > 20000000){
                        $uploadOk = 0;
                    }
                    //END check file size

                    //BEGIN Allow only certain format of image
                    /*
                    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg')
                    */
                    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType != 'pdf')
                    {
                        $uploadOk = 0;
                    }
                    //END Allow only certain format of image

                    //BEGIN check NO above error ?
                    if($uploadOk == 0){
                        echo "<script type='text/javascript'>alert(\"Fail upload image 2\");window.history.go(-1);</script>";
                    }else{
                        /*echo "$newfilename";
                        echo "<br>";
                        echo "success";*/ 
                        //$newfilename = $newfilename . "." . $imageFileType;

                        if (move_uploaded_file($_FILES['f_2_up']['tmp_name'], $target_file)) {
                            /*
                            $today = date('Y-m-d');

                        
                                $q_update = "UPDATE lf_gatepass SET img_name_2='$newfilename',  sync_out='1', img_datetime='$curr' , staff_id = '$auth_id' WHERE invoiceid='$id'";
                                if($r_update = mysql_query($q_update)){
                                    echo "<script>alert('Successful!');</script>";
                                }else{
                                    echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                                }
                            */
                            // Update record
                            $sql = "UPDATE lf_gatepass SET img_name_2=?, sync_out='1', img_datetime=now(), staff_id = ?
                                    WHERE invoiceid = ?";

                            if($stmt = mysqli_prepare($conn, $sql)){       
                                mysqli_stmt_bind_param($stmt,"sss",$newfilename, $auth_id, $id);
                                mysqli_stmt_execute($stmt);
                            } 

                            if(mysqli_affected_rows($conn) > 0){
                                echo "<script>alert('Successful!');</script>";
                            } else {
                                echo "<script>alert('Failed to Update image 2. Please contact administrator for help.');</script>";
                            }
                        
                        }else{
                            echo "<script>alert('Cannot upload to $target_file ');</script>";
                        }
                    }
                }
            }
        } // end of if($img2 == "1")




        //-------------------------------------------------------------------------------------- Image 3 Start
        if($img3 == "1"){
            if($row['img_name3'] != ""){
                echo "<script>alert('Failed to update. Cannot overwrite image');</script>";
            }else{

                 if (isset($_FILES["f_3_up"]["name"])) {
                    $image_3 = $_FILES["f_3_up"]["name"];
                }

                //////////----------------------------------------------------------------------- 3rd Img
                if ($image_3 != '') { //New Image detected

                    $target_file = $target_dir . basename($_FILES['f_3_up']['name']);
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
                        }
                    }
                    //END Check if it an image or fake image

                    /* 20210521 Jerry - skip checking for exists, allow overwrite if not yet verified (status = 1)
                    //BEGIN Check if file already exist
                    if(file_exists($target_file)){
                        $uploadOk = 0;
                    }
                    */

                    //END Check if file already exist

                    //BEGIN check file size
                    if($_FILES['f_3_up']['size'] > 20000000){
                        $uploadOk = 0;
                    }
                    //END check file size

                    //BEGIN Allow only certain format of image
                    /*
                    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg')
                    */
                    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType != 'pdf')
                    {
                        $uploadOk = 0;
                    }
                    //END Allow only certain format of image

                    //BEGIN check NO above error ?
                    if($uploadOk == 0){
                        echo "<script type='text/javascript'>alert(\"Fail upload image 3\");window.history.go(-1);</script>";
                    }else{
                        /*echo "$newfilename";
                        echo "<br>";
                        echo "success";*/ 
                        //$newfilename = $newfilename . "." . $imageFileType;

                        if (move_uploaded_file($_FILES['f_3_up']['tmp_name'], $target_file)) {
                            /*
                            $today = date('Y-m-d');

                        
                                $q_update = "UPDATE lf_gatepass SET img_name_3='$newfilename',  sync_out='1', img_datetime='$curr' , staff_id = '$auth_id' WHERE invoiceid='$id'";
                                if($r_update = mysql_query($q_update)){
                                    echo "<script>alert('Successful!');</script>";
                                }else{
                                    echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                                }
                            */
                            // Update record
                            $sql = "UPDATE lf_gatepass SET img_name_3=?, sync_out='1', img_datetime=now(), staff_id = ?
                                    WHERE invoiceid = ?";

                            if($stmt = mysqli_prepare($conn, $sql)){       
                                mysqli_stmt_bind_param($stmt,"sss",$newfilename, $auth_id, $id);
                                mysqli_stmt_execute($stmt);
                            } 

                            if(mysqli_affected_rows($conn) > 0){
                                echo "<script>alert('Successful!');</script>";
                            } else {
                                echo "<script>alert('Failed to Update image 3. Please contact administrator for help.');</script>";
                            }
                        
                        }else{
                            echo "<script>alert('Cannot upload to $target_file ');</script>";
                        }
                    }
                }

            }
        } // end of if($img3 == "1")



         //-------------------------------------------------------------------------------------- Image 3 Start
        if($img4 == "1"){
            if($row['img_name4'] != ""){
                echo "<script>alert('Failed to update. Cannot overwrite image');</script>";
            }else{

                if (isset($_FILES["f_4_up"]["name"])) {
                    $image_4 = $_FILES["f_4_up"]["name"];
                }

                            //////////----------------------------------------------------------------------- 4rd Img
                if ($image_4 != '') { //New Image detected

                    $target_file = $target_dir . basename($_FILES['f_4_up']['name']);
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
                        }
                    }
                    //END Check if it an image or fake image

                    /* 20210521 Jerry - skip checking for exists, allow overwrite if not yet verified (status = 1)
                    //BEGIN Check if file already exist
                    if(file_exists($target_file)){
                        $uploadOk = 0;
                    }
                    */

                    //END Check if file already exist

                    //BEGIN check file size
                    if($_FILES['f_4_up']['size'] > 20000000){
                        $uploadOk = 0;
                    }
                    //END check file size

                    //BEGIN Allow only certain format of image
                    /*
                    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg')
                    */
                    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType != 'pdf')
                    {
                        $uploadOk = 0;
                    }
                    //END Allow only certain format of image

                    //BEGIN check NO above error ?
                    if($uploadOk == 0){
                        echo "<script type='text/javascript'>alert(\"Fail upload image 4\");window.history.go(-1);</script>";
                    }else{
                        /*echo "$newfilename";
                        echo "<br>";
                        echo "success";*/ 
                        //$newfilename = $newfilename . "." . $imageFileType;

                        if (move_uploaded_file($_FILES['f_4_up']['tmp_name'], $target_file)) {
                            /*
                            $today = date('Y-m-d');

                        
                                $q_update = "UPDATE lf_gatepass SET img_name_4='$newfilename',  sync_out='1', img_datetime='$curr' , staff_id = '$auth_id'WHERE invoiceid='$id'";
                                if($r_update = mysql_query($q_update)){
                                    echo "<script>alert('Successful!');</script>";
                                }else{
                                    echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                                }
                            */
                            $sql = "UPDATE lf_gatepass SET img_name_4=?, sync_out='1', img_datetime=now(), staff_id = ?
                                    WHERE invoiceid = ?";

                            if($stmt = mysqli_prepare($conn, $sql)){       
                                mysqli_stmt_bind_param($stmt,"sss",$newfilename, $auth_id, $id);
                                mysqli_stmt_execute($stmt);
                            } 

                            if(mysqli_affected_rows($conn) > 0){
                                echo "<script>alert('Successful!');</script>";
                            } else {
                                echo "<script>alert('Failed to Update image 4. Please contact administrator for help.');</script>";
                            }
                        
                        }else{
                            echo "<script>alert('Cannot upload to $target_file ');</script>";
                        }
                    }
                }


            }
        } // end of if($img4 == "1")


//--------------------------------------------- end update

    } else {
        echo "<script>alert('Failed to update. Please contact administrator for help.');</script>";
    }
}
    
echo "<script>location.assign('/DOReceiver/index.php?NID=$auth_id');</script>";



?>
