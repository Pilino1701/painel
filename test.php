<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); 
header('Content-Type: application/json; charset=utf-8');  

$a=$_GET['par'];

$a=intval($a);


$b="R$ 17,000.00";

$arr = array('a' => $a, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
$ar = array('fatt' => $b);

echo json_encode($ar);
?>