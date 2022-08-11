<?php require_once "../_require/dbconn.php"?>
<?php
    error_reporting(E_ALL ^ E_WARNING);

    $mysqli_conn = $conn;
    $mysqli_conn -> autocommit(FALSE);

    $response;
    if (!isset($response)) 
        $response = new stdClass();

    $staff_name = "";
    if(isset($_GET['staff_name']) && !empty($_GET['staff_name'])){
        $staff_name = $_GET['staff_name'];
    } else {
        $response->status = "failed";
        $response->msg = "No staff name provided.";

        $json_response = json_encode($response);

        echo $json_response;
    }

    $coordinate = "";
    if(isset($_GET['coordinate']) && !empty($_GET['coordinate'])){
        $coordinate = $_GET['coordinate'];
    } else {
        $response->status = "failed";
        $response->msg = "No coordinate provided.";

        $json_response = json_encode($response);

        echo $json_response;
    }

    $json_text = "";
    if(isset($_GET['json_text']) && !empty(trim($_GET['json_text']))){
        $json_text = $_GET["json_text"];
    } else {
        $response->status = "failed";
        $response->msg = "No value provided.";

        $json_response = json_encode($response);

        echo $json_response;
    }

    $do_list = json_decode($json_text);

    $table = "<table id='do_table' border=1 style='width:100%'>
                <tr>
                    <th>DO No.</th>
                    <th>Orignal File Name</th>
                    <th>New File Name</th>
                    <th>Upload Status</th>
                </tr>";

    foreach($do_list as $temp_do){
        $do = $temp_do->do;
        $original_file_name = $temp_do->original_file_name;
        $new_file_name = $temp_do->new_file_name;
        $status = $temp_do->status;
        $status_html = "";

        if($status == "success"){
            $status_html = "<span style='color:green'>" . $status . "</span>";
            update_table($mysqli_conn, $do, $new_file_name, $staff_name, $coordinate);
        } else {
            $status_html = "<span style='color:red; font-weight:bold'>" . $status . "</span>";
        }

        

        $table = $table . 
                "<tr>
                    <td>$do</td>
                    <td>$original_file_name</td>
                    <td>$new_file_name</td>
                    <td>$status_html</td>
                </tr>";
    }
    $table = $table . "</table>";
    

    try{
        // Commit transaction
        if (!$mysqli_conn -> commit()) {
            $response->status = "failed";
            $response->msg = "Failed to save record.";

            $json_response = json_encode($response);

            echo $json_response;

            $mysqli_conn -> rollback();
            exit;

        } 

        $mysqli_conn -> close();
    } catch (mysqli_sql_exception $e){
        echo $e->getMessage();    
    }

    $response->status = "success";
    $response->msg = $table;
    $json_response = json_encode($response);
    echo $json_response;
    exit;

            

    function update_table($mysqli_conn, $do, $new_file_name, $staff_name, $coordinate){
        $date_only = date('Y-m-d');
        $time_only = date('H:i:s');
        $do = $do . "%";

        $sql;
        try{
            $sql ="UPDATE lf_gatepass SET coordinate = ?, gps_date = ?, gps_time = ?, sync_out = '1', staff_id = ? 
                    WHERE invoiceid like ? AND coordinate = '' AND gps_date = '' AND gps_time = ''";

            if($stmt = mysqli_prepare($mysqli_conn, $sql)){       
                mysqli_stmt_bind_param($stmt,"sssss",$coordinate, $date_only, $time_only, $staff_name, $do);
                mysqli_stmt_execute($stmt);
            } 

            if (strpos($new_file_name, '_1.') !== false){
                $sql = "UPDATE lf_gatepass SET img_name_1 = ?, img_datetime=now(), staff_id = ? WHERE invoiceid like ?";
            } else if(strpos($new_file_name, '_2.') !== false) {
                $sql = "UPDATE lf_gatepass SET img_name_2 = ?, img_datetime=now(), staff_id = ? WHERE invoiceid like ?";
            } else if(strpos($new_file_name, '_3.') !== false) {
                $sql = "UPDATE lf_gatepass SET img_name_3 = ?, img_datetime=now(), staff_id = ? WHERE invoiceid like ?";
            } else if(strpos($new_file_name, '_4.') !== false) {
                $sql = "UPDATE lf_gatepass SET img_name_4 = ?, img_datetime=now(), staff_id = ? WHERE invoiceid like ?";
            }

            if($stmt = mysqli_prepare($mysqli_conn, $sql)){       
                mysqli_stmt_bind_param($stmt,"sss",$new_file_name, $staff_name, $do);
                mysqli_stmt_execute($stmt);
            } 
                    
        } catch (mysqli_sql_exception $e){
            echo $e->getMessage();    
        }      

    }


?>