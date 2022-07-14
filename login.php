<?php

require "assets/db_conn.php";
require "assets/functions.php";
header('Content-Type: application/json');

if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["device_id"])){
    $device_id = $_POST["device_id"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $error = 0;

    if(check_input("alphanumeric",$device_id) && deviceid_exist(htmlspecialchars($device_id))){

        if (strlen(trim($username)) <= 0) {
            $json["username"] = "Username should not be empty";
            $error += 1;
        } else if (!check_input("alphanumeric", $username)) {
            $json["username"] = "Aplhanumeric only";
            $error += 1;
        }
        if (strlen(trim($password)) <= 0) {
            $json["password"] = "Password should not be empty";
            $error += 1;
        }else if(strlen($password) < 8) {
            $json["password"] = "Password is to short";
            $error += 1;
        }

        if($error==0){
            $password = password_encrypt($_POST["password"]);
            $query = mysqli_query($conn,"SELECT * FROM `login_info` li,`user_info` ui WHERE li.user_id=ui.user_id AND BINARY li.user_name='$username' AND BINARY li.user_pass='$password'");
            $fetch = mysqli_fetch_array($query);

            if(mysqli_num_rows($query)==1){
                if($fetch['login_status']=='verified'){
                    $gen_token = random_str(64);
                    $user_id = $fetch['user_id'];
                    $date = addslashes(date("Y-m-d H:i:s"));
                    $sql = "INSERT INTO `login_session`(`login_token`, `user_id`, `device_id`, `platform`, `session_status`, `created_date`) VALUES ('$gen_token','$user_id','$device_id','android_app','active','$date')";
                    if(mysqli_query($conn,$sql)){
                        $json["success"] = true;
                        $json["user_id"] = $user_id;
                        $json["login_token"] = $gen_token;
                        $json["fname"] = $fetch['fname'];
                        $json["lname"] = $fetch['lname'];
                    }else{
                        $json["success"] = false;
                        $json["error_desc"] = "Creating Login Session Error";
                    }
                }else if($fetch['login_status']=='verify'){
                    $json["success"] = false;
                    $json["error_desc"] = "Account need to verify.\nPlease Contact the Admin.";
                }else if($fetch['login_status']=='blocked'){
                    $json["success"] = false;
                    $json["error_desc"] = "Account Blocked.\nPlease Contact the Admin.";
                }else{
                    $json["success"] = false;
                    $json["error_desc"] = "Login Status Error";
                }
                
            }else{
                $json["success"] = false;
                $json["error_desc"] = "Username and/or Password is Incorrect";
            }
        }else{
            $json["success"] = false;
        }



    }else{
        $json["success"] = false;
        $json["error_desc"] = "Invalid Device ID";
    }

}else{
    $json["success"] = false;
    $json["error_desc"] = "Missing Parameter";
}
echo json_encode($json);
?>