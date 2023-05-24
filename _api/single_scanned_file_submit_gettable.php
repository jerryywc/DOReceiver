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

    $image_1 = "";

    if (isset($_FILES["scanned_file"]["name"])) {
        $image_1 = $_FILES["scanned_file"]["name"];
        //echo "Filename" . basename($_FILES['scanned_file']['name']) . "\n";
        //echo "Filename: " . $newname . "\n";
    }

    if(empty($image_1)){
        $response->status = "failed";
        $response->msg = "Please select file to upload. ";
                
        $json_response = json_encode($response);
                
        echo $json_response;
        exit;
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


                $email = "";
                $status_by = "";

                $sql = "SELECT Email FROM auth_id WHERE FullName = ?";

                if($stmt = mysqli_prepare($conn, $sql)){       
                    mysqli_stmt_bind_param($stmt,"s",$staff_name);
                    $result = mysqli_stmt_execute($stmt);
                } 

                $result = $stmt -> get_Result();                
                
                // if record found in lf_gatepass
                if($row = mysqli_fetch_array($result)) {
                    $email = $row['Email'];
                    $status_by = substr($email, 0, strpos($email, "@"));
                }
                

                //if($lf_gatepass_has_record == 1) {

                $date_only = date('Y-m-d');
                $time_only = date('H:i:s');

                $found = false;

                $updated = 0;

                try{
                    $sql = "UPDATE lf_gatepass SET img_name_1=?, sync_out='1', coordinate = ?, img_datetime=now(), staff_id = ?, gps_date = ?, gps_time = ?,
                                status = '1', status_date = now(), status_by = ?, status_by_fullname = ?, dms_sync = '1'
                            WHERE invoiceid IN (" . $invoice_list . ")";

                    if($stmt = mysqli_prepare($conn, $sql)){       
                        mysqli_stmt_bind_param($stmt,"sssssss",$newfilename, $coordinate, $staff_name, $date_only, $time_only, $status_by, $email);
                        mysqli_stmt_execute($stmt);
                    } 

                    $updated = mysqli_affected_rows($conn);

                    $sql2 = "UPDATE lf_gatepass_temp SET img_name_1=?, sync_out='1', coordinate = ?, img_datetime=now(), staff_id = ?, gps_date = ?, gps_time = ?,
                                status = '1', status_date = now(), status_by = ?, status_by_fullname = ?, dms_sync = '1'
                            WHERE invoiceid IN (" . $invoice_list . ")";

                    if($stmt2 = mysqli_prepare($conn, $sql2)){       
                        mysqli_stmt_bind_param($stmt2,"sssssss",$newfilename, $coordinate, $staff_name, $date_only, $time_only, $status_by, $email);
                        mysqli_stmt_execute($stmt2);
                    } 

                    $updated = $updated + mysqli_affected_rows($conn);

                    //if(mysqli_affected_rows($conn) > 0){
                    if($updated > 0){
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
                } catch (mysqli_sql_exception $e){
                    //echo $e->getMessage();

                    $response->status = "failed";
                    $response->msg = "Failed to update records: </br>" . $e->getMessage();;
                    
                    $json_response = json_encode($response);
                    
                    echo $json_response;
                    exit;
                }     
                    
                   
            }else{ // unable to upload file
                $response->status = "failed";
                $response->msg = "Failed to upload Image upload for: </br>" . $upload_result;
            
                $json_response = json_encode($response);
            
                echo $json_response;
                exit;
            }
        }

    }
?>

<?php



    function mime_content_type($f) {
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