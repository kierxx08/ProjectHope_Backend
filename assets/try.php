<?php
/*
$jsonString = file_get_contents('app_settings.json');
$data = json_decode($jsonString, true);

$data['latest_version'] = '1.0';

$newJsonString = json_encode($data);
file_put_contents('app_settings.json', $newJsonString);
*/

$jsonString = file_get_contents('app_settings.json');
$data = json_decode($jsonString);

echo $data->latest_description;
?>