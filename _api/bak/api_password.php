<?php
    
    require_once "dbconn.php";
    $conn=connect();
    
    $id = $_REQUEST["id"];
    $pa = $_REQUEST['pa'];
    $web = $_REQUEST['web'];   

    $len = strlen($pa);

    $select_data = "SELECT * FROM auth_id WHERE ID='$id' or Email='$id'";
    $query_data = mysql_query($select_data, $conn);
    if($row_data = mysql_fetch_assoc($query_data)){
        $Auth = $row_data['AUTH'];
        $Email = $row_data['Email'];
        $Plen = $row_data['plen'];
        $npa = decryptp($Auth);

        if($Plen == $len){
           if($pa == substr($npa,0,$len) && $pa != ''){
                if($web == "1"){

                    $now = time();
        
                    $User_Email = $Email;
                    $last_login = date("d-m-y h:i:sa");
                    $query = "UPDATE auth_id SET last_login='$last_login' WHERE Email='$Email'";
                    $result = mysql_query($query);
                    
                    session_start();
                    $_SESSION['login_user'] = $row_data["Email"];
                    $_SESSION['login_type'] = $row_data["ID_Type"];
                    $_SESSION['last_login'] = $now;
                    $_SESSION['login_status'] = $row_data['Status'];
                    $_SESSION['group_dealer'] = $row_data['group_dealer'];

                    echo "Success";
                }else{
                    header('Content-Type: Application/json');
                    $token = array('success' => 1, 'id' => $id, 'email' => $Email);
                    echo json_encode($token);
                }
                
            }else{
                if($web == "1"){
                    echo "Failed";
                }else{
                    header('Content-Type: Application/json');
                    $token = array('success' => 0, 'id' => $id, 'email' => $Email, 'msg' => 'Wrong ID / Password');
                    echo json_encode($token);
                }
            } 
        }else{
             if($web == "1"){
                    echo "Failed";
                }else{
                    header('Content-Type: Application/json');
                    $token = array('success' => 0, 'id' => $id, 'email' => $Email, 'msg' => 'Wrong ID / Password');
                    echo json_encode($token);
                }
        }

        

    }else{
        if($web == "1"){
                echo "Failed";
            }else{
                header('Content-Type: Application/json');
                $token = array('success' => 0, 'id' => $id, 'email' => $Email, 'msg' => 'Wrong ID / Password');
                echo json_encode($token);
            }
    }


     function decryptp($str){
        $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");

        $key_size =  strlen($key);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $ciphertext_base64 = $str;

        $ciphertext_dec = base64_decode($ciphertext_base64);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

        return $plaintext_dec;
    }

?>
