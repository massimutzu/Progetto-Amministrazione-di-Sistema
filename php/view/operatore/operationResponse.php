<?php

$json = array();
$json['status']= $status;
$json['msg']= $msg;
$json['newValue']= $newValue;
echo json_encode($json);
?>
