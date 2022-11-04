<?php require_once "../_require/dbconn.php"?>
<?php
    error_reporting(E_ALL ^ E_WARNING);

    $mysqli_conn = $conn;
    $mysqli_conn -> autocommit(FALSE);

    $response;
    if (!isset($response)) 
        $response = new stdClass();

    $staff_name = "";
    if(isset($_POST['full_name']) && !empty($_POST['full_name'])){
        $staff_name = $_POST['full_name'];
    } else {
        $response->status = "failed";
        $response->msg = "No staff name provided.";

        $json_response = json_encode($response);

        echo $json_response;
        exit;
    }

    $coordinate = "";
    if(isset($_POST['coordinate']) && !empty($_POST['coordinate'])){
        $coordinate = $_POST['coordinate'];
    } else {
        $response->status = "failed";
        $response->msg = "No coordinate provided.";

        $json_response = json_encode($response);

        echo $json_response;
        exit;
    }




    $target_dir = "../api/do/";
    $error = "";
    $newname = $_POST['verified_do_number'][0] . date('Ymd_His');
    $newname = str_replace("/","",$newname);

    if (isset($_FILES["scanned_file"]["name"])) {
        $image_1 = $_FILES["scanned_file"]["name"];
        //echo "Filename" . basename($_FILES['scanned_file']['name']) . "\n";
        //echo "Filename: " . $newname . "\n";
    }     

    if ($image_1 != '') { // Image 1 detected

        $target_file = $target_dir . basename($_FILES['scanned_file']['name']);
        $error = $error . "Target file: " . $target_file;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        
        //$temp = explode(".", $_FILES['scanned_file']['name']);
        //$newfilename = $newname.'_1.'.end($temp);

        $newfilename = $newname . '_1.' . $imageFileType;

        $target_file = $target_dir.$newfilename;
        $uploadOk = 1;
        //$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        //img end -------------------------------


        //BEGIN Check if it an image or fake image
        if(isset($_POST["submit"])){
            $check = getimagesize($_FILES['scanned_file']['tmp_name']);
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
        if($_FILES['scanned_file']['size'] > 20000000){
            $uploadOk = 0;
            $error = $error . "File size exceed 20MB: " . $_FILES['scanned_file']['size'];
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
            $response->status = "failed";
            $response->msg = "Invalid image: " . $error;
                
            $json_response = json_encode($response);
                
            echo $json_response;
            exit;
            //echo "<script type='text/javascript'>alert(\"Fail upload image 1: " . $error . "\");window.history.go(-1);</script>";
        
        }else{
            $upload_results = "";

            

            // SERVER A - UPLOAD FILE VIA CURL POST
            // (A) SETTINGS
            $url = "http://edms.posim.com.my/do_uploads/receiver.php"; // Where to upload file to
            //$file = __DIR__ . DIRECTORY_SEPARATOR . "README.txt"; // File to upload
            $file = $_FILES['scanned_file']['tmp_name'];
            //$upname = "uploaded.txt"; // File name to be uploaded as

            // (B) NEW CURL FILE
            $cf = new CURLFile($file, mime_content_type($file), $target_file);

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
                //echo "CURL ERROR - " . curl_error($ch);
                //echo "<script type='text/javascript'>alert(\"Image 1 CURL ERROR -  " . curl_error($ch) . "\");window.history.go(-1);</script>";

                $response->status = "failed";
                $response->msg = "CURL ERROR - " . curl_error($ch);
                
                $json_response = json_encode($response);
                
                echo $json_response;
                exit;
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

                $response_msg = "";
                $invoice_list = "";
                foreach($_POST['verified_do_number'] as $key=>$value){
                    $new_key = $key + 1;
                    if(!empty($value)) {
                        $invoice_list = $invoice_list . "'" . $value . "',";
                        $response_msg = $response_msg . "$new_key = $value</br>";
                    }
                }

                $invoice_list = $invoice_list . "''";
                

                //if($lf_gatepass_has_record == 1) {
                    $sql = "UPDATE lf_gatepass SET img_name_1=?, sync_out='1', coordinate = ?, img_datetime=now(), staff_id = ?
                        WHERE invoiceid IN (" . $invoice_list . ")";
                //} else if($lf_gatepass_temp_has_record == 1){
                //    $sql = "UPDATE lf_gatepass_temp SET img_name_1=?, sync_out='1', img_datetime=now(), staff_id = ?
                //        WHERE invoiceid IN (" . $invoice_list . ")";
                //}


                if($stmt = mysqli_prepare($conn, $sql)){       
                    mysqli_stmt_bind_param($stmt,"sss",$newfilename, $coordinate, $staff_name);
                    mysqli_stmt_execute($stmt);
                } 

                if(mysqli_affected_rows($conn) > 0){
                    $response->status = "success";
                    $response->msg = "Image upload for: </br>" . $response_msg;

                    $json_response = json_encode($response);

                    echo $json_response;
                    exit;
                } else {
                    $response->status = "failed";
                    $response->msg = "Failed to update records for: </br>" . $response_msg;
                
                    $json_response = json_encode($response);
                
                    echo $json_response;
                    exit;
                }
                    
                   
            }else{ // unable to upload file
                $response->status = "failed";
                $response->msg = "Failed to upload Image upload for: </br>" . $response_msg;
            
                $json_response = json_encode($response);
            
                echo $json_response;
                exit;
            }
        }

    }















    


    



?>