<?php

$id = $_REQUEST['donum'];
$gps = $_REQUEST['gps'];
$auth_id = $_REQUEST['auth_id'];

$img1 = $_REQUEST['img1'];
$img2 = $_REQUEST['img2'];
$img3 = $_REQUEST['img3'];
$img4 = $_REQUEST['img4'];

$newname = substr($id,0,8);

require_once "dbconn.php";
$conn=connect();

    $image_1 = "";
    $image_2 = "";
    $image_3 = "";
    $image_4 = "";

$gps = trim($gps);

if($img1 == "" && $img2 == "" && $img3 == "" && $img4 == ""){
//----------- Part 1 - No document upload

   // echo "<script>alert('" . $gps . "');</script>";
    if($gps != ""){
        $today = date('Y-m-d');
        $now = date('h:i:sa');

        $check_sql = "SELECT * FROM lf_gatepass WHERE invoiceid='$id' AND coordinate=''";
        $query_sql = mysql_query($check_sql);
        if($res_sql = mysql_fetch_assoc($query_sql)){
             //--- New GPS data.. update
            $q_update = "UPDATE lf_gatepass SET coordinate='$gps', gps_date='$today', gps_time='$now', sync_out='1', staff_id = '$auth_id' WHERE invoiceid='$id'";
            if($r_update = mysql_query($q_update)){
                echo "<script>alert('Successful!');</script>";
            }else{
                echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
            }
        }else{
           //--- GPS exist.. dont update
             echo "<script>alert('Failed to update. Coordinate already exist. Please contact administrator for help.');</script>";
        }
    }else{
         echo "<script>alert('" . $id . " There nothing to update. Please contact administrator for help.');</script>";
    }


}else{

     //"h:i:sa
    $today = date('Y-m-d');
    $now = date('h:i:sa');
    $curr = date('Y-m-d h:i:sa');
    //BEGIN check Image AVAILLABLE?

    $target_dir = "do/";

    $check_sql = "SELECT * FROM lf_gatepass WHERE invoiceid='$id' ";
    $query_sql = mysql_query($check_sql);
    if($res_sql = mysql_fetch_assoc($query_sql)){

        if($res_sql['coordinate'] == "" and $gps != ""){
            $q_update = "UPDATE lf_gatepass SET coordinate='$gps', gps_date='$today', gps_time='$now', sync_out='1', staff_id = '$auth_id' WHERE invoiceid='$id'";
            $r_update = mysql_query($q_update);
        }
        
       
        $error = "";
        //img_name_1
        //-------------------------------------------------------------------------------------- Image 1 Start
        if($img1 == "1"){
            if($res_sql['img_name1'] != ""){
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
                    if(file_exists($target_file)){
                        $uploadOk = 0;
                        $error = $error . "File already exists: " . $target_file;
                    }

                    //END Check if file already exist

                    //BEGIN check file size
                    if($_FILES['f_1_up']['size'] > 20000000){
                        $uploadOk = 0;
                        $error = $error . "File size exceed 2MB: " . $_FILES['f_1_up']['size'];
                    }
                    //END check file size

                    //BEGIN Allow only certain format of image
                    /*
                    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg')
                    */
                    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg')
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

                           
                                $q_update = "UPDATE lf_gatepass SET img_name_1='$newfilename', sync_out='1', img_datetime='$curr', staff_id = '$auth_id' WHERE invoiceid='$id'";
                                if($r_update = mysql_query($q_update)){
                                    echo "<script>alert('Successful!');</script>";
                                }else{
                                    echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                                }
                            
                           
                        }else{
                            echo "<script>alert('Cannot upload to $target_file ');</script>";
                        }
                    }
                }else{ //Only update info : existing
                           
                    echo "<script> alert('Error : Please upload at least 1 image'); </script>";
                            
                }           
            }
        }



        //-------------------------------------------------------------------------------------- Image 2 Start
        if($img2 == "1"){
            if($res_sql['img_name2'] != ""){
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

        //BEGIN Check if file already exist
        if(file_exists($target_file)){
            $uploadOk = 0;
        }

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
        if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg')
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
                $today = date('Y-m-d');

               
                    $q_update = "UPDATE lf_gatepass SET img_name_2='$newfilename',  sync_out='1', img_datetime='$curr' , staff_id = '$auth_id' WHERE invoiceid='$id'";
                    if($r_update = mysql_query($q_update)){
                        echo "<script>alert('Successful!');</script>";
                    }else{
                        echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                    }
                
               
            }else{
                echo "<script>alert('Cannot upload to $target_file ');</script>";
            }
        }
    }
            }
        }




        //-------------------------------------------------------------------------------------- Image 3 Start
        if($img3 == "1"){
            if($res_sql['img_name3'] != ""){
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

        //BEGIN Check if file already exist
        if(file_exists($target_file)){
            $uploadOk = 0;
        }

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
        if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg')
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
                $today = date('Y-m-d');

               
                    $q_update = "UPDATE lf_gatepass SET img_name_3='$newfilename',  sync_out='1', img_datetime='$curr' , staff_id = '$auth_id' WHERE invoiceid='$id'";
                    if($r_update = mysql_query($q_update)){
                        echo "<script>alert('Successful!');</script>";
                    }else{
                        echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                    }
                
               
            }else{
                echo "<script>alert('Cannot upload to $target_file ');</script>";
            }
        }
    }

            }
        }



         //-------------------------------------------------------------------------------------- Image 3 Start
        if($img4 == "1"){
            if($res_sql['img_name4'] != ""){
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

        //BEGIN Check if file already exist
        if(file_exists($target_file)){
            $uploadOk = 0;
        }

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
        if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg')
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
                $today = date('Y-m-d');

               
                    $q_update = "UPDATE lf_gatepass SET img_name_4='$newfilename',  sync_out='1', img_datetime='$curr' , staff_id = '$auth_id'WHERE invoiceid='$id'";
                    if($r_update = mysql_query($q_update)){
                        echo "<script>alert('Successful!');</script>";
                    }else{
                        echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                    }
                
               
            }else{
                echo "<script>alert('Cannot upload to $target_file ');</script>";
            }
        }
    }


            }
        }


//--------------------------------------------- end update

    }else{
        echo "<script>alert('Failed to update. Please contact administrator for help.');</script>";
    }


    //----------- Part 2 - with attachment
   

   


    

    //END check Image AVAILLABLE?

}


    echo "<script>location.assign('https://www.hi-rev.com.my/DOReceiver/index.php?NID=$auth_id');</script>";



?>
