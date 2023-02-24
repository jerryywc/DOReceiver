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

      echo "Temp File Path: " . $tmpFilePath;

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

        for($i = 0; $i < count($sheetData[0]); $i++) {
          echo "<td>" . $sheetData[0][$i];
        }

        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";
        



        $i=1;

        unset($sheetData[0]); // remove first row

        // process element here;
        foreach ($sheetData as $t) {
        
        // access column by index
          echo "<tr>";
          echo "<td>" . $i. "</td>";

          for($j = 0; $j < count($t); $j++){
            echo "<td>" . $t[$j] . "</td>";
          }

          echo "</tr>";
          $i++;
        }

        echo "</tbody>";
        echo "</table>";

        echo "<br/><br/>Row count: " . count($sheetData);

        
        // end of uploading file
      
    }





?>
