<?php

header('Content-Type: application/json');


if (isset($_POST["app_info"])) {

    $SettingFile = file_get_contents('assets/app_settings.json');
    $data = json_decode($SettingFile);

    $maintenance = $data->maintenance;

    $json["maintenance"] = $maintenance;
    $json["app_latest_version"] =  $data->latest_version;

    $latest_description = $data->latest_description[0];
    for ($i = 1; $i < count($data->latest_description); $i++) {
        $latest_description .= "\n".$data->latest_description[$i];
    }

    $json["app_latest_description"] =  $latest_description;
    $json["app_link"] =  $data->app_link;

    if ($maintenance == true) {
        $json["maintenance_link"] = $data->maintenance_link;
        $json["maintenance_desc"] = $data->maintenance_desc;
    }


    echo json_encode($json);
} else {
    header("HTTP/1.1 404 Not Found");
}
