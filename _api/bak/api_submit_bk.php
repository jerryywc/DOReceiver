<?php

$id = $_REQUEST['donum'];

$newname = substr($id,0,8);

require_once "dbconn.php";
$conn=connect();


if (isset($_FILES["f_2_up"]["name"])) {
    $image_n = $_FILES["f_2_up"]["name"];
}


//BEGIN check Image AVAILLABLE?

if ($image_n != '') { //New Image detected

    //img 1 ---------------------------------
    //$target_dir = "http://www.hi-rev.com.my/0/IMG/car/".strtolower($car_brand)."/";
    //$target_dir = "do/".strtolower($car_brand)."/";
    $target_file = "do/" . $target_dir . basename($_FILES['f_2_up']['name']);

    $temp = explode(".", $_FILES['f_2_up']['name']);
    $newfilename = $newname.'.'.end($temp);

    $target_file2 = "do/".$target_dir.$newfilename;

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
    if(file_exists($target_file2)){
        $uploadOk = 0;
    }

    //END Check if file already exist

    //BEGIN check file size
    if($_FILES['f_2_up']['size'] > 20000000){
        $uploadOk = 0;
    }
    //END check file size

    //BEGIN Allow only certain format of image
    if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != 'jpeg' && $imageFileType02 != "png" && $imageFileType02 != "jpg" && $imageFileType02 != 'jpeg'){
        $uploadOk = 0;
    }
    //END Allow only certain format of image

    //BEGIN check NO above error ?
    if($uploadOk == 0){
        echo "<script type='text/javascript'>alert(\"Fail upload image\");window.history.go(-1);</script>";
    }else{
        /*echo "$newfilename";
        echo "<br>";
        echo "success";*/ 
        //$newfilename = $newfilename . "." . $imageFileType;

        if (move_uploaded_file($_FILES['f_2_up']['tmp_name'], $target_file2)) {
            $today = date('Y-m-d');

           
                $q_update = "UPDATE lf_gatepass SET img_name='$newfilename' WHERE invoiceid='$id'";
                if($r_update = mysql_query($q_update)){
                    echo "<script>alert('Successful!');</script>";
                }else{
                    echo "<script>alert('Failed to Update. Please contact administrator for help.');</script>";
                }
            
           
        }else{
            echo "<script>alert('Cannot upload to $target_file2 ');</script>";
        }
    }
}else{ //Only update info : existing
           
    echo "<script> alert('Error : Please upload image'); </script>";
            
}


//END check Image AVAILLABLE?
    echo "<script>location.assign('http://www.hi-rev.com.my/DOReceiver');</script>";



?>
