<?php require_once "../_includes/dbconn.php"?>

<?php

  require '../vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\Spreadsheet;

  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

?>


<?php



    error_reporting(E_ALL ^ E_WARNING);

    $response;
    if (!isset($response)) 
        $response = new stdClass();




    $total = count($_FILES['excel_file']['name']);

    //echo "\n\nCount: " . $total;
    //echo "\n\nName: " . $_FILES['excel_file']['name'];
    //echo "\n\nTemp Name: " . $_FILES['excel_file']['tmp_name'];

   
      //Get the temp file path
      $tmpFilePath = $_FILES['excel_file']['tmp_name'];

      //echo "Temp File Path: " . $tmpFilePath;

      //Make sure we have a file path
      if ($tmpFilePath != ""){
        
        /*
          Read the excel file using the load() function.
        */
        $spreadsheet = $reader->load($tmpFilePath);


        /*
          Get the first sheet in the Excel file and convert it to an array using the toArray() function. And Get the Number of rows in the sheet using the count() function.
        */
        $d=$spreadsheet->getSheet(0)->toArray();

        //echo "<br/>Row count: " . count($d) . "<br/>";

        /*
          If you want to iterate all the rows in the excel file, then first convert it to an array and iterate using for or foreach.
        */
        $sheetData = $spreadsheet->getActiveSheet()->toArray();



        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr>";
        echo "<td>No</td>";
        /*
        for($i = 0; $i < count($sheetData[0]); $i++) {
          echo "<td>" . $sheetData[0][$i];
        }
        */
        echo "<td>invoiceid</td>";
        echo "<td>invoicedate</td>";
        echo "<td>gatepass</td>";
        echo "<td>gatepassdate</td>";
        echo "<td>accountnum</td>";
        echo "<td>accountname</td>";
        echo "<td>transportercode</td>";

        echo "<td>Status</td>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        



        $i=1;

        unset($sheetData[0]); // remove first row

        // process element here;
        foreach ($sheetData as $t) {
          $status = "";
        
        // access column by index
          echo "<tr>";
          echo "<td>" . $i. "</td>";

          /*
          for($j = 0; $j < count($t); $j++){
            echo "<td>" . $t[$j] . "</td>";
          }
          */
          $invoiceid = $t[0];
          $invoicedate = $t[1];
          $gatepass = $t[2];
          $gatepassdate = $t[3];
          $accountnum = $t[4];
          $accountname = $t[5];
          $transportercode = $t[6];

          $status = insert_row($mysqli_conn, $invoiceid, $invoicedate, $gatepass, $gatepassdate, $accountnum, $accountname, $transportercode);

          echo "<td>" . $invoiceid . "</td>";
          echo "<td>" . $invoicedate . "</td>";
          echo "<td>" . $gatepass . "</td>";
          echo "<td>" . $gatepassdate . "</td>";
          echo "<td>" . $accountnum . "</td>";
          echo "<td>" . $accountname . "</td>";
          echo "<td>" . $transportercode . "</td>";
          echo "<td>" . $status . "</td>";
          echo "</tr>";
          $i++;
        }

        echo "</tbody>";
        echo "</table>";

        echo "<br/><br/>Row count: " . count($sheetData);

        
        // end of uploading file
      
    }


    function insert_row($mysqli_conn, $invoiceid, $invoicedate, $gatepass, $gatepassdate, $accountnum, $accountname, $transportercode){
      echo $invoiceid;
      try{

        $sql = "SELECT * FROM lf_gatepass WHERE invoiceid = ?";

        if($stmt = mysqli_prepare($mysqli_conn, $sql)){       
          mysqli_stmt_bind_param($stmt,"s",$invoiceid);
          $result = mysqli_stmt_execute($stmt);
          //mysqli_stmt_execute($stmt) or die( mysqli_error($mysqli_conn));
        } 
       
        $result = $stmt -> get_Result();				               
    
        if($row = mysqli_fetch_array($result)) {
          return "Exists";
        }

        $sql = "INSERT INTO lf_gatepass (invoiceid, invoicedate, gatepass, gatepassdate, accountnum, accountname, transportercode) VALUES (?,?,?,?,?,?,?)";
    
        if($stmt = mysqli_prepare($mysqli_conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"sssssss",$invoiceid, $invoicedate, $gatepass, $gatepassdate, $accountnum, $accountname, $transportercode);
            mysqli_stmt_execute($stmt);
            //mysqli_stmt_execute($stmt) or die( mysqli_error($mysqli_conn));
        } 

        $sql = "UPDATE lf_gatepass
                SET invoicedate = STR_TO_DATE(invoicedate,  '%m/%d/%Y')
                WHERE invoiceid = ? 
                AND STR_TO_DATE(invoicedate,  '%m/%d/%Y') IS NOT NULL";

        if($stmt = mysqli_prepare($mysqli_conn, $sql)){       
          mysqli_stmt_bind_param($stmt,"s",$invoiceid);
          mysqli_stmt_execute($stmt);
          //mysqli_stmt_execute($stmt) or die( mysqli_error($mysqli_conn));
        } 

        $sql = "UPDATE lf_gatepass
                SET gatepassdate = STR_TO_DATE(gatepassdate,  '%m/%d/%Y')
                WHERE invoiceid = ? 
                AND STR_TO_DATE(gatepassdate,  '%m/%d/%Y') IS NOT NULL";

        if($stmt = mysqli_prepare($mysqli_conn, $sql)){       
          mysqli_stmt_bind_param($stmt,"s",$invoiceid);
          mysqli_stmt_execute($stmt);
          //mysqli_stmt_execute($stmt) or die( mysqli_error($mysqli_conn));
        } 

        return "OK";
    
        //print_r($product_images);
      } catch (mysqli_sql_exception $e){
          //echo $e->getMessage();    
          return "Error: " . $e->getMessage();
      }  
    }




?>
