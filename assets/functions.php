<?php

//$base_url = "https://www.kierasis.me/store";
$base_url = "http://192.168.254.2/projecthope";
function db()
{
    require "assets/db_conn.php";
    return $conn;
}

function check_input($type, $string)
{

    if ($type == "text01") {
        if (!preg_match("#^[a-zA-Z0-9=_-]+$#", $string)) {
            return false;
        } else {
            return true;
        }
    } else if ($type == "text02") {
        if (!preg_match("#^[a-zA-Z0-9_-]+$#", $string)) {
            return false;
        } else {
            return true;
        }
    } else  if ($type == "alphanumeric") {
        if (!preg_match("#^[a-zA-Z0-9]+$#", $string)) {
            return false;
        } else {
            return true;
        }
    } else if ($type == "alphanumeric02") {
        if (!preg_match("#^[a-zA-Z0-9 ]+$#", $string)) {
            return false;
        } else {
            return true;
        }
    } else if ($type == "password") {
        if (!preg_match("#^[a-zA-Z0-9\&!+$=]+$#", $string)) {
            return false;
        } else {
            return true;
        }
    } else if ($type == "integer") {
        if (!preg_match("#^[0-9]+$#", $string)) {
            return false;
        } else {
            return true;
        }
    } else if ($type == "date") {
        if (!preg_match("#^[0-9-: ]+$#", $string)) {
            return false;
        } else {
            return true;
        }
    } else if ($type == "email") {
        if (!preg_match("/^([a-zA-Z0-9\.]+@+[a-zA-Z]+(\.)+[a-zA-Z]{2,3})$/", $string)) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function gen_userid()
{
    $conn = db();
    $i = 1;
    while ($i == 1) {
        $id = random_str(20);
        $query = mysqli_query($conn, "SELECT * FROM `user_info` WHERE BINARY user_id='$id'");
        if (mysqli_num_rows($query) == 1) {
            $i = 1;
        } else {
            $i = 0;
        }
    }
    return $id;
}

function deviceid_exist($deviceid)
{
    $conn = db();
    $query = mysqli_query($conn, "SELECT * FROM `device_info` WHERE BINARY device_id='$deviceid'");
    if (mysqli_num_rows($query) == 1) {
        return true;
    } else {
        return false;
    }
}

function up_LastActive($user_id)
{
    $conn = db();
    $date = date("Y-m-d H:i:s");
    $query = mysqli_query($conn, "UPDATE `login_info` SET `last_active`='$date' WHERE user_id='$user_id'");

}

function password_encrypt($password)
{

    $ciphering = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($ciphering);
    $options = 0;

    $encryption_iv = '1234567891011121';
    $encryption_key = "S3XR3TP@$$";

    $encryption = openssl_encrypt(
        $password,
        $ciphering,
        $encryption_key,
        $options,
        $encryption_iv
    );

    return $encryption;
}

function random_str(
    int $length,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {

    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }

    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;

    for ($i = 0; $i < $length; ++$i) {
        $pieces[] = $keyspace[random_int(0, $max)];
    }

    return implode('', $pieces);
}
