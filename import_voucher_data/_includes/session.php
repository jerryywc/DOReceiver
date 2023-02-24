<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
?>
<?php session_start();

    
    if(!isset($_SESSION['login_user'])){
        //if not login, redirect to index.php
        header("Location: index.php");
        exit;
    } 
    /*else {
        $user_name = $_SESSION['login_user'];

        //unset($_SESSION['PREV_PAGE']);
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
            //if last request was more than 30 minutes ago, destroy old session then start new session, redirect to index.php
            
            session_unset();     // unset $_SESSION variable for the run-time 
            session_destroy();   // destroy session data in storage

            session_start(); //start new session
            //$_SESSION['EXPIRED'] = "expired";
            header("Location: index.php");
            exit;
        }

        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    }*/
?>