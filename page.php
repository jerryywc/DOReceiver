<?php 
session_start();

$value = htmlspecialchars($_GET['c']);

if(isset($_SESSION['login_user']))
{
  //echo "WELCOME : " . $_SESSION['login_user'];
  //echo "<script>alert('WELCOME ". $_SESSION['login_user'] ."');</script>";
    echo "<script>location.assign('http://www.hi-rev.com.my/DOReceiver/');</script>";
}else{
  //echo "No USER";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title> LOGON </title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">



<script type="text/javascript">
    function Auth(){

        document.getElementById('loading_on').style.display = "inline";
        document.getElementById('loading_off').style.display = "none";
        document.getElementById('reg_').style.display = "none";
        document.getElementById('forgot_').style.display = "none";


        var id = document.getElementById('id').value;
        var pa = document.getElementById('pa').value;
        var cp = document.getElementById('cp').value;

        if (id.length == 0 || pa.length == 0) {
          alert("ID / Password Required");
         // return;
      } else {
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                if(this.responseText.trim() == "Success"){
                  setTimeout(location.assign('page.php'), 3000);
                }else{
                    alert("Invalid ID/Password");
                }
              }
          };
          xmlhttp.open("GET", "api/api_password.php?id=" + id + "&pa=" + pa + "&web=1", true);
          xmlhttp.send();
      }

       document.getElementById('loading_on').style.display = "none";
        document.getElementById('loading_off').style.display = "inline";

    }


    function Reg(){

        document.getElementById('loading_on').style.display = "none";
        document.getElementById('loading_off').style.display = "none";
        document.getElementById('reg_').style.display = "inline";
        document.getElementById('forgot_').style.display = "none";

    }

    function Forgot(){
        document.getElementById('loading_on').style.display = "none";
        document.getElementById('loading_off').style.display = "none";
        document.getElementById('reg_').style.display = "none";
        document.getElementById('forgot_').style.display = "inline";
    }

    function Reg_Submit(){

         var firstname = document.getElementById('firstname').value;
         var lastname = document.getElementById('lastname').value;
         var email = document.getElementById('email').value;
         var password = document.getElementById('password').value;
         var phone = document.getElementById('phone').value;

         var cp = document.getElementById('cp').value;

        if (email.length == 0 || password.length == 0) {
          alert("Field cannot be left blank");
          // return;
        } else {
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                if(this.responseText.trim() == "Success"){
                alert("Thank you for your registration.");
                 if(cp != ""){
                    //location.assign('page.php?c=' + cp);
                     setTimeout(location.assign('page.php?c=' + cp), 3000);
                 }else{
                    //location.assign('page.php');
                    setTimeout(location.assign('page.php'), 3000);
                 }
                 
                }else{
                    alert("Registration Failed.");
                }
              }
          };
          xmlhttp.open("GET", "api_register.php?firstname=" + firstname + "&lastname=" + lastname + "&email=" + email + "&password=" + password + "&phone=" + phone, true);
          xmlhttp.send();
      }
    }


    function Forgot_Submit(){
    	
        var email = document.getElementById('id_email').value;
		var cp = document.getElementById('cp').value;

        if (email.length == 0 || password.length == 0) {
          alert("Field cannot be left blank");
          // return;
        } else {
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                if(this.responseText.trim() == "Success"){
                alert("Please check your Email for new password");
                 if(cp != ""){
                    //location.assign('page.php?c=' + cp);
                     setTimeout(location.assign('page.php?c=' + cp), 3000);
                 }else{
                    //location.assign('page.php');
                    setTimeout(location.assign('page.php'), 3000);
                 }
                 
                }else{
                    alert("Email not valid");
                }
              }
          };
          xmlhttp.open("GET", "api_forgotpassword.php?email=" + email , true);
          xmlhttp.send();
      }
    }
</script>

<style type="text/css">
.img_banner{
  width: 200px;
}
.input_class{
  padding-left: 5px;
  padding-right: 5px;
  padding-top: 8px;
  padding-bottom: 8px;
  margin-top: 12px;
  width: 300px;
  font-family: Open Sans;
  font-weight: 400;
}
.button_class{
  padding: 5px;
  border-radius: 0.5em;
  min-width: 100px;
}
.button_class:hover{
  color: #FFDD33;
  background-color: #000;
}
.span_class:hover{
  color: #FFDD33;
  cursor: pointer;
}
  
@media screen and (max-width:680px) 
{
  .img_banner{
    width: 200px;
  }
  .input_class{
    width: 80%;
  }
}

</style>

</head>
<body style="background-color: black; color: #FFF; font-size: 16px;">

<input type="hidden" id="cp" name="cp" value="<?php echo $value; ?>">


<div id="loading_on" style="display:none">
<p>&nbsp;</p><center><img src="../0/IMG/loading.gif" ></center>
</div>

<div id="loading_off" style="display: inline; font-family: Open Sans; font-weight: 300; margin-top: 10%;">
 <p>&nbsp;</p><p>&nbsp;</p> 
 <center><img src="../0/IMG/hirev_logo.png" class="img_banner"><br><p>&nbsp;</p>
 <input type="text" name="id" id="id" value="" placeholder="ID / Email" class="input_class"><br>
 <input type="password" name="pa" id="pa" value="" placeholder="Password" class="input_class">
 <p>
   <input type="button" id="logon" value=" LOGIN " onclick="Auth();" class="button_class">
 </p>
 <!--
 <p>
    <span class="span_class" onclick="Reg()"> REGISTER </span><br>
  </p><p>
    <span class="span_class" onclick="Forgot()"> FORGOT PASSWORD </span>
 </p>
-->
 </center>
</div>



<div id="reg_" style="display: none; font-family: Open Sans; font-weight: 300; margin-top: 10%;">
 <p>&nbsp;</p><p>&nbsp;</p> 
 <center><img src="../IMG/hirev_logo.png" class="img_banner"><br><p>&nbsp;</p>
 <input type="text" name="firstname" id="firstname" value="" placeholder="First Name" class="input_class"><br>
 <input type="text" name="lastname" id="lastname" value="" placeholder="Last Name" class="input_class"><br>
 <input type="text" name="email" id="email" value="" placeholder="Email Address" class="input_class"><br>
 <input type="password" name="password" id="password" value="" placeholder="Password" class="input_class"><br>
 <input type="text" name="phone" id="phone" value="" placeholder="Contact Number" class="input_class">

 <p>
   <input type="button" id="register" value=" REGISTER " onclick="Reg_Submit();" class="button_class">
 </p>
 </center>
</div>
 



 <div id="forgot_" style="display: none; font-family: Open Sans; font-weight: 300; margin-top: 10%;">
 <p>&nbsp;</p><p>&nbsp;</p> 
 <center><img src="../IMG/hirev_logo.png" class="img_banner"><br><p>&nbsp;</p>
 <input type="text" name="id_email" id="id_email" value="" placeholder="ID / Email" class="input_class">
 <p>
   <input type="button"  value=" REQUEST NEW PASSWORD " onclick="Forgot_Submit();" class="button_class">
 </p>
 </center>
</div>



</body>
</html>