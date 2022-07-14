<?php

require "assets/db_conn.php";
require "assets/functions.php";



if(isset($_POST["unique_id"]) && isset($_POST["device_brand"]) && isset($_POST["device_model"]) && isset($_POST["device_app_version"])){

    $unique_id = $_POST["unique_id"];
    $phone_brand=$_POST["device_brand"];
    $phone_model=$_POST["device_model"];
    $phone_app_version=$_POST["device_app_version"];
    $phone_name=$_POST["device_name"];

    //$date = time();
    $key = random_str(20);
    $date = addslashes(date("Y-m-d H:i:s"));

    $sql_register = "INSERT INTO `device_info` (`device_id`, `unique_id`, `brand`, `model`, `name`, `app_version`, `last_update`, `detected_date`) VALUES ('$key','$unique_id','$phone_brand','$phone_model','$phone_name','$phone_app_version','$date','$date')";

    $myObj = new \stdClass();

    if($conn){

        if(mysqli_query($conn,$sql_register)){
            //logs($conn,"system","null","Android App installed in new device with device ID# $key");
            $myObj->error = false;
            $myObj->device_id = $key;

            $unique_id_query = mysqli_query($conn,"SELECT * FROM `device_info` WHERE unique_id='$unique_id'");
            $ui_fetch = mysqli_fetch_array($unique_id_query);
            

        }else{

            $myObj->error = true;

            $myObj->error_desc = "Error 102: Server Error";

        }

    }else{

            $myObj->error = true;

            $myObj->error_desc = "Error 101: Server Error";

    }

    

    $myJSON = json_encode($myObj);

    echo $myJSON;

    

}else{

	    echo "Unauthorized Request";

}

?>