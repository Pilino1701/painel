<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); 
header('Content-Type: application/json; charset=utf-8');  

$a=$_GET['par'];



include"vari.php";




$dia=date('Y-m-d');
$tst = strtotime($dia);
$dt_qu=trim(date('d/m/Y', $tst));


$conn=new mysqli($host,$user,$pass,$banco);

if ($conn->connect_error){
    echo "<b>Erro:</b> ".$conn->connect_error; 
    return;
    }
$conn->set_charset("utf8");



$st="select * from usuario where data_ac>='$dia' and data_ac<='2060-01-01' and flag_us='1'and substr(modalidade,1,5)<>'PERSO'";

$sql=$conn->query($st);
$alat = $sql->num_rows;


$conn->close();
$ar = array('alat' => $alat);
echo json_encode($ar);

?>
       
