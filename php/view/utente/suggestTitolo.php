<?php

$json = array();
foreach($titoli as $titolo){
    $json[]= $titolo;    
}
echo json_encode($json);
?>
