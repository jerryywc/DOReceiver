<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
     <a class="navbar-brand" href="index.php">
       <img align="middle" src="IMG/hirev_logo.png" width="120px"/>
     </a>
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
     </button>
     <div class="collapse navbar-collapse" id="navbarNavDropdown">
       <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="setup_page.php?s=">SETUP MENU</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="package.php?s=">PACKAGES</a>
        </li>
<?php
  $acl_do = "";
  if(isset($_SESSION['acl_do']) && !empty($_SESSION['acl_do'])){
    $acl_do = $_SESSION['acl_do'];
  }
  if($acl_do == 1){
?>         
         <li class="nav-item ">
           <a class="nav-link" href="setup_.php?type=DO">VERIFY DO</a>
         </li>
<?php
  }
?>         

         <li class="nav-item dropdown">
           <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">             
             <img src="IMG/icon/profile.png" width="28px">
           </a>
           <div class="dropdown-menu dropdown-menu-right text-right" aria-labelledby="navbarDropdownMenuLink">
             <a class="dropdown-item" href="profile.php">Profile</a>
             <a class="dropdown-item" href="logout.php">Logout</a>
           </div>
         </li>
<?php
  $ID_Type = "";
  if(isset($_SESSION['login_type']) && !empty($_SESSION['login_type'])){
    $ID_Type = $_SESSION['login_type'];
  }
  if($ID_Type == '2'){
?>
         <li class="nav-item">
           <a class="nav-link" href="setup_page.php"><img src="IMG/icon/setup.png" width="28px"></a>
         </li>
<?php
  }
?>         
       </ul>
     </div>
   </nav>
   <div style="height:70px"></div>