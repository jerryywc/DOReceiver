<?php require_once "_require/dbconn.php"?>

<?php //session_start(); 
	//$nameid = $_REQUEST['NID'];
	$NID = htmlspecialchars($_GET['NID']);

    $month = date("n");
    $year = date("Y");

    
    if(isset($_GET['month'])){
        $month = $_GET['month'];
    }
    if(isset($_GET['year'])){
        $year = $_GET['year'];
    }
    


	$FullName;
	$transporterid;

	try{
               
        $sql ="SELECT * FROM auth_id where FullName=? AND Status!='2' AND (transporterid!='' OR do_upload='1')";

        if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"s",$NID);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        if($row = mysqli_fetch_array($result)) {
        	$FullName = $row['FullName'];
        	$transporterid = $row['transporterid'];

        }
	} catch (mysqli_sql_exception $e){
        echo $e->getMessage();    
    }    

    /*
	$qt = 0;
	if(isset($_GET['QT'])){
		$qt = htmlspecialchars($_GET['QT']); // ( 0 or null = Home ) ( 1 = My Record )
	}*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>DO Check</title>
    <link rel="icon" type="image/png" href="http://www.hi-rev.com.my/hirev_web/IMG/icon32x32.png" sizes="32x32">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css" />


    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css">
	
    <style>
        #report_table{
            width: 100%;
            font-size: 0.85em;
        }
        .table{
            border-collapse: collapse;
            width: 100%;
            font-family: sans-serif;
            margin-top: 25px;
            font-size: 0.8em;
        }
        .table tr td{
            border: 1px solid;
            padding:  2px 4px;
        }

        .table tr th{
            border: 1px solid;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>

</head>
<body >
	<center>
<div class="">	

<?php 




$x = 0;
try{
	//if(!empty($transporterid)){
		$sql = "SELECT * FROM lf_gatepass where month(gps_date) = ? AND year(gps_date) = ? AND img_datetime != '' AND (staff_id in (SELECT FullName FROM auth_id WHERE do_upload = 1 or do_verify = 1) or status_by_fullname in (SELECT ID from auth_id WHERE do_verify = 1 or do_upload = 1)) ORDER BY staff_id, invoicedate, invoiceid";
		if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"ii",$month, $year);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        while($row = mysqli_fetch_array($result)) {
        	$x = $x + 1;

            if($x == 1){
                echo "<table id='report_table'>";
                echo "<thead>";
                echo "<tr>";                
                echo "<th> No. </th>";
                echo "<th> DO Date </th>";
                echo "<th> DO No </th>";
                echo "<th> Customer </th>";
                echo "<th> Transporter/Driver</th>";
                echo "<th> Gatepass</th>";
                echo "<th> Received Date/Time</th>";
                echo "<th> Uploaded By</th>";
                echo "<th> Upload Date/Time</th>";                
                echo "<th> Verified By</th>";
                echo "<th> Verified Date/Time</th>";
                //echo "<th> Uploaded Image</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
            }

            echo "<tr>";
            echo "<td> $x </td>";
            echo "<td>" . $row['invoicedate'] . "</td>";
            echo "<td>" . $row['invoiceid'] . "</td>";
            echo "<td>" . $row['accountname'] . "</td>";
            echo "<td>" . $row['transportercode'] . "</td>";
            echo "<td>" . $row['gatepass'] . "</td>";
            echo "<td>" . $row['received_datetime'] . "</td>";
            echo "<td>" . $row['staff_id'] . "</td>";
            echo "<td>" . $row['img_datetime'] . "</td>";
            echo "<td>" . $row['status_by'] . "</td>";
            echo "<td>" . $row['status_date'] . "</td>";

            $img = "";
            if($row['img_name_1'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_1']."'  style='display:inline-block; width:60px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_1']."\");'>";
            }

            if($row['img_name_2'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_2']."'  style='display:inline-block; width:60px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_2']."\");'>";
            }

            if($row['img_name_3'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_3']."'  style='display:inline-block; width:60px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_3']."\");'>";
            }

            if($row['img_name_4'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_4']."'  style='display:inline-block; width:60px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_4']."\");'>";
            }


            //echo "<td>" . $img . "</td>";
            echo "</tr>"; 
        }
    /*
	} else {
		$sql = "SELECT * FROM lf_gatepass where coordinate!='' AND month(gps_date) = ? AND year(gps_date) = ?";
		if($stmt = mysqli_prepare($conn, $sql)){       
            mysqli_stmt_bind_param($stmt,"ii", $month, $year);
            $result = mysqli_stmt_execute($stmt);
        } 

        $result = $stmt -> get_Result();                
        
        while($row = mysqli_fetch_array($result)) {
        	$x = $x + 1;

            if($x == 1){
                echo "<table>";
                echo "<tr>";
                echo "<th> No. </th>";
                echo "<th> DO Date </th>";
                echo "<th> DO No </th>";
                echo "<th> Customer </th>";
                echo "<th> Transporter/Driver</th>";
                echo "<th> Gatepass</th>";
                echo "<th> Received Date/Time</th>";
                echo "<th> Uploaded By</th>";
                echo "<th> Upload Date/Time</th>";
                echo "<th> Verified By</th>";
                echo "<th> Verified Date/Time</th>";
                //echo "<th> Uploaded Image</th>";
                echo "</tr>";
            }

            echo "<tr>";
            echo "<td> $x </td>";
            echo "<td>" . $row['invoicedate'] . "</td>";
            echo "<td>" . $row['invoiceid'] . "</td>";
            echo "<td>" . $row['accountname'] . "</td>";
            echo "<td>" . $row['transportercode'] . "</td>";
            echo "<td>" . $row['gatepass'] . "</td>";
            echo "<td>" . $row['received_datetime'] . "</td>";            
            echo "<td>" . $row['staff_id'] . "</td>";
            echo "<td>" . $row['img_datetime'] . "</td>";
            echo "<td>" . $row['status_by'] . "</td>";
            echo "<td>" . $row['status_date'] . "</td>";

            $img = "";
            if($row['img_name_1'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_1']."'  style='display:inline-block; width:60px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_1']."\");'>";
            }

            if($row['img_name_2'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_2']."'  style='display:inline-block; width:60px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_2']."\");'>";
            }

            if($row['img_name_3'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_3']."'  style='display:inline-block; width:60px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_3']."\");'>";
            }

            if($row['img_name_4'] != ''){
                $img = $img . "<img src='api/do/".$row['img_name_4']."'  style='display:inline-block; width:60px; margin: 2px;' onclick='window.open(\"api/do/".$row['img_name_4']."\");'>";
            }


            //echo "<td>" . $img . "</td>";
            echo "</tr>"; 

        }
	}*/

	if($x != 0){
        echo "</tbody>";
        echo "</table>";
    }

} catch (mysqli_sql_exception $e){
    echo $e->getMessage();    
}    

    
?>
</div>
</center>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!--
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.2/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
-->
<link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
 
<script type="text/javascript" src="DataTables/datatables.min.js"></script>
<script>
    $( document ).ready(function() {
        initTable();
      });


    function initTable(){
        $.extend( $.fn.dataTable.defaults, {
          responsive: true
        });
        
        var table = $('#report_table').DataTable({
          select: true,
          fixedHeader:true,
          "lengthMenu": [[50, 100, -1], [50, 100, 'All']],                    
          dom: 'Blfrtip',
          buttons: [
            'copy', 
            {
              extend: 'csv',
              title: 'Report ' + $( "#report_table option:selected" ).text(),
              exportOptions: {
                columns: ':visible'
              }
            },
            {
              extend: 'excel',
              title: 'Report ' + $( "#report_table option:selected" ).text(),
              exportOptions: {
                columns: ':visible'
              }
            },
            {
              extend: 'pdf',
              title: 'Report ' + $( "#report_table option:selected" ).text(),
              exportOptions: {
                columns: ':visible'
              }
            }, 
            'print'
          ]
        });

       
      } // end of initTable

</script>


</body>
</html>