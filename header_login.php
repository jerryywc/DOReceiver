<?php
	$promo_code = htmlspecialchars($_GET["c"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="IMG/icon32x32.png" sizes="32x32">
<title>HI-REV</title>
    <link rel="stylesheet" type="text/css" href="IMG/engine1/style.css" />
    <link rel="stylesheet" type="text/css" href="styletab.css" />
	<script type="text/javascript" src="IMG/engine1/jquery.js"></script>
</head>
<body class="bodyClass" style="text-align:center;" ><!-- <body class="bodyClass"> --> 

<div class="divclass">
<center>
<div class="res_header_desk">
<?php require_once "menu_header_2.php";?>
</div>
<div class="res_header_mob">
<?php require_once "menu_header_mob.php";?>
</div>
<script type="text/javascript">
	<?php
		if($UserID == ""){
			//echo "openNav();";
			echo "location.assign('https://www.hi-rev.com.my/1001/api/page.php?c=$promo_code');";
		}else{
			echo "location.assign('index.php');";
		}
	?>
</script>
<div class="res_sub_menu_desk" style=" background-image: url(IMG/world_map.png); font-family:Arial, Helvetica, sans-serif;">
<font size="0.1px"> &nbsp; </font><br><br>
 <center> Please login to continue. <br> Please click <a href="#" onclick="openNav()" style="text-decoration:none; color:#000000; text-decoration:underline">here</a> to logon</center>

<br><br>
</div>
<div class="res_sub_menu_mob" style="background-image: url(IMG/world_map.png)">
<table border="0" width="100%" bgcolor="#FFFFFF"><tr><td style="font-family:Arial, Helvetica, sans-serif">
 <br><center> Sorry, your last session has expired. <br> Please click <a href="#" onclick="openNav()" style="text-decoration:none; color:#000000; text-decoration:underline">here</a> to logon</center>
<br><br>
<img src="IMG/adjuster.jpg" width="100%">
</td></tr></table>
</div>
<font size="0.1px" <?php if($UserID == ''){echo "onload='openNav()'"; } ?>> &nbsp; </font>
<div class="res_footer_desk">
<?php require_once "footer_web.php";?>
</div>
<div class="res_footer_mob">
<?php require_once "footer_mob.php";?>
</div>
</center>
</div>

</body>
</html>
