<?php
if (function_exists("uploadprogress_get_info")) {

    $info = uploadprogress_get_info($_GET['ID']);
} else {
    $info = false;
}

$progress = ($info['bytes_uploaded']*100)/$info['bytes_total'];

echo $progress . var_dump($_FILES);