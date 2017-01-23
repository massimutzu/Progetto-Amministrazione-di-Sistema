<?php

$json = array();
foreach($lista as $item){
    $json[]= $item;    
}
echo json_encode($json);
?>
