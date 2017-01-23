<?php

$json = array();
foreach($autori as $autore){
    $json[]= $autore;    
}
echo json_encode($json);
?>
