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

<div class="header">
	<table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td width="50%">
<img src="../0/IMG/hirev_logo.png" style="width: 150px; padding: 5px;">
</td>
<td align="right">
<?php //require_once "session.php";

	//if($nameid == "Muhammad Aizam Alif B Ahmad Mashudi"){
		?>

<div class="menu_top" onclick="location.assign('index.php?NID=<?php echo $nameid;?>&QT=');"> Home </div>
<div class="menu_top" onclick="location.assign('index.php?NID=<?php echo $nameid;?>&QT=1');"> DO History </div>  

		<?php
		//echo "<div style='display: inline-block; padding-right: 5px; color: #FFF; cursor: pointer;'> Home </div> | ";
		//echo "<div style='display: inline-block; padding-right: 5px; color: #FFF; cursor: pointer;'> Last 5 Days Records </div>";
	//}

 ?>
</td></tr></table>
</div>
