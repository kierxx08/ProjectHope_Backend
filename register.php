<?php

require "assets/db_conn.php";
require "assets/functions.php";
header('Content-Type: application/json');

if(isset($_POST["username"]) && isset($_POST["fname"]) && isset($_POST["lname"]) && isset($_POST["password"]) && isset($_POST["device_id"])){
    $device_id = $_POST["device_id"];
    $username = $_POST["username"];
    $fname = ucwords($_POST["fname"]);
    $lname = ucwords($_POST["lname"]);
    $password = $_POST["password"];
    $error = 0;

    if(check_input("alphanumeric",$device_id) && deviceid_exist(htmlspecialchars($device_id))){

        if (strlen(trim($username)) <= 0) {
            $json["username"] = "Username should not be empty";
            $error += 1;
        } else if (!check_input("alphanumeric", $username)) {
            $json["username"] = "Aplhanumeric only";
            $error += 1;
        }else{
            $username_query = mysqli_query($conn,"SELECT * FROM `login_info` WHERE user_name='$username'");
            if(mysqli_num_rows($username_query)>0){
                $json["username"] = "Username Already Registered";
                $error += 1;
            }
                
        }
        if (strlen(trim($fname)) <= 0) {
            $json["fname"] = "First Name should not be empty";
            $error += 1;
        } else if (!check_input("alphanumeric", $fname)) {
            $json["fname"] = "Aplhanumeric only";
            $error += 1;
        }
        if (strlen(trim($lname)) <= 0) {
            $json["lname"] = "Last Name should not be empty";
            $error += 1;
        } else if (!check_input("alphanumeric", $lname)) {
            $json["lname"] = "Aplhanumeric only";
            $error += 1;
        }
        if (strlen(trim($password)) <= 0) {
            $json["password"] = "Password should not be empty";
            $error += 1;
        }else if(strlen($password) < 8) {
            $json["password"] = "Password is to short";
            $error += 1;
        }else if(!check_input("password", $password)) {
            $json["password"] = "Contain Invalid Character";
            $error += 1;
        }

        if($error==0){
            $user_id = gen_userid($conn);
            $password = password_encrypt($_POST["password"]);
            $date = date("Y-m-d H:i:s");

            $query = "INSERT INTO `login_info`(`user_id`, `user_name`, `user_pass`, `login_type`, `login_status`, `last_active`) VALUES ('$user_id','$username','$password','app_user01','verified','$date')";
            $query2 = "INSERT INTO `user_info`(`user_id`, `fname`, `lname`, `since`) VALUES ('$user_id','$fname','$lname','$date')";
            
            if(mysqli_query($conn,$query) && mysqli_query($conn,$query2)){
                $json["success"] = true;
            }else{
                $json["success"] = false;
                $json["error_desc"] = "DB Error";
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