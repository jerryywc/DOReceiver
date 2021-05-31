<style type="text/css">
	.menu_top{
		display: inline-block; 
		padding: 5px; 
		color: #FFF; 
		cursor: pointer;
		border-style: solid;
		border-color: grey;
		border-width: 1px;
	}
	.tbl_content{

	}
	.tbl_content td{
		padding: 5px;
		border-left-color: #e6e6e6;
		border-left-style: solid;
		border-left-width: 1px;
		border-bottom-style: solid;
		border-bottom-width: 1px;
		border-bottom-color: grey;
	}
</style>
<style>
.dropbtn {
  background-color: #222222;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
  background-color: #e2aa02;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  overflow: auto;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown a:hover {background-color: #ddd;}

.show {display: block;}
</style>
<div class="header">
	<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td width="50%">
<img src="../0/IMG/hirev_logo.png" style="width: 150px; padding: 5px;">
</td>
<td align="right">
<?php //require_once "session.php";

	//if($nameid == "Muhammad Aizam Alif B Ahmad Mashudi"){
		?>

<!--<div class="menu_top" onclick="location.assign('index.php?NID=<?php //echo $nameid;?>&QT=');"> Home </div>
<div class="menu_top" onclick="location.assign('index.php?NID=<?php //echo $nameid;?>&QT=1');"> DO History </div>  -->
<!-- <div class="div_class_menu" onclick="location.assign('index.php?NID=<?php //echo $nameid;?>&QT=');"> Home </div> -->
<!--<div class="div_class_menu" id="desk_menu_profile" > DO History </div>
        <div style="display:none; height: 0px;position: fixed;" id="float_menu_hidden">
		    <div class="div_class_menu_sub" onclick="location.assign('index.php?NID=<?php// echo $nameid;?>&QT=1')" > All </div><br>
        	<div class="div_class_menu_sub" onclick="location.assign('index.php?NID=<?php //echo $nameid;?>&QT=2')" > Within 3 Days </div><br>
        	<div class="div_class_menu_sub" onclick="location.assign('index.php?NID=<?php //echo $nameid;?>&QT=3')" > Within 7 Days </div>
    	</div>-->

<div class="dropdown">
  <button class="dropbtn" onclick="location.assign('index.php?NID=<?php echo $NID;?>');">Home</button>
  <!-- <button onclick="getLocation()" class="dropbtn">Check In</button> -->
  <button onclick="myFunction()" class="dropbtn">DO History</button>
  <div id="myDropdown" class="dropdown-content">    
    <!--
    <a href="index.php?NID=<?php echo $NID;?>&QT=2">Within 3 Days</a>
    <a href="index.php?NID=<?php echo $NID;?>&QT=3">Within 7 Days</a>
  -->
    <a href="report_3.php?NID=<?php echo $NID;?>">Within 3 Days</a>
    <a href="report_7.php?NID=<?php echo $NID;?>">Within 7 Days</a>
    <a href="report_month.php?NID=<?php echo $NID;?>">Monthly</a>
  </div>
</div>

		<?php
		//echo "<div style='display: inline-block; padding-right: 5px; color: #FFF; cursor: pointer;'> Home </div> | ";
		//echo "<div style='display: inline-block; padding-right: 5px; color: #FFF; cursor: pointer;'> Last 5 Days Records </div>";
	//}

 ?>
</td></tr></table>
</div>

<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
