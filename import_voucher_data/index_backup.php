<?php

  require 'vendor/autoload.php';

  use PhpOffice\PhpSpreadsheet\Spreadsheet;

  $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

?>

<?php

  /*
    Read the excel file using the load() function.
  */
  $spreadsheet = $reader->load("a.xlsx");


  /*
    Get the first sheet in the Excel file and convert it to an array using the toArray() function. And Get the Number of rows in the sheet using the count() function.
  */
  $d=$spreadsheet->getSheet(0)->toArray();

  echo "Row count: " . count($d);

  /*
    If you want to iterate all the rows in the excel file, then first convert it to an array and iterate using for or foreach.
  */
  $sheetData = $spreadsheet->getActiveSheet()->toArray();

  $i=1;

  echo "<br/>No | " . $sheetData[0][0] . " | " . $sheetData[0][1];

  unset($sheetData[0]); // remove first row

  foreach ($sheetData as $t) {
  // process element here;
  // access column by index
    echo "<br/>" . $i. " | ".$t[0]." | ".$t[1];
    $i++;
  }

  echo "<br/>Row count: " . count($sheetData);

?>