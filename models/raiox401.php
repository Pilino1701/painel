<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); 
header('Content-Type: application/json; charset=utf-8');  

date_default_timezone_set("America/Sao_Paulo");


//$a=$_GET['par'];



include"vari.php";


$gg=date('d');
//echo $gg."  ";



if($gg>1):
  $gf=date('Y/m/d', strtotime('-1 days'));
else:
  $gf=0;  
endif;	



$gi=date("Y-m-01"); //primeiro dia do mes


$dia=date('Y-m-d');
$tst = strtotime($dia);
$dt_qu=trim(date('d/m/Y', $tst));


$conn=new mysqli($host,$user,$pass,$banco);

if ($conn->connect_error){
    echo "<b>Erro:</b> ".$conn->connect_error; 
    return;
    }
$conn->set_charset("utf8");

$ndesh=array();


for($i=0;$i<93;$i++){
  $j=$i+1;
  
    
  $dtc=date("Y-m-d",strtotime("- $j days"));
  $dtc2=date("Y-m-d",strtotime("- $j days"));

  //echo $dtc." ".$dtc2."\n";

 
  $st="select * from usuario where data_ac>='$dtc' and data_ac<='$dtc2' and flag_us='1'and substr(modalidade,1,5)<>'PERSO'";
  $sql=$conn->query($st);
  $ndesh[$i] = $sql->num_rows;

  }

$ndesh=array_reverse($ndesh);


//CAIXA PRED

$tiret=0;
$timat=0;

$arrope=array(array());
$numope=0;
$nomeop="";

$totdia=0.0;
$totdin=0.0;
$totcar=0.0;
$totche=0.0;
$totcarcr=0.0;
$totcarde=0.0;
$totcarpi=0.0;
$totcartr=0.0;
$totliq=0.0;
$npag=0;
$dianpag=array();
$pernpag=array();
$pernfat=array();
$numdia1=0;
$numdia2=0;
$npagpar=0;
$nfatpar=0;

$datac1=date("1900-01-01");//data per vedere quando cambia il giorno
$datac2=date("1900-01-01");//data per vedere quando cambia il giorno
$dtc=date("Y-m-01",strtotime('-3 months'));
//$dtc2=date("Y-m-d",strtotime('-36 days'));
$dtc2=date("Y-m-d");




$st="select * from caixa where data_pag>='$dtc' and data_pag<='$dtc2'order by data_pag,id";
$sql=$conn->query($st);
while($escrever=$sql->fetch_array()){

$tot=0.0;
$linhapag1="";
$linhapag2="";
$linhapag3="";
$linhapag4="";
$linhapag5="";

$stringa1="";
$stringa2="";
$stringa3="";
$stringa4="";
$stringa5="";

$timestamp = strtotime($escrever["data_cat"]); // Gera o timestamp de $data_mysql
$dt_cat=trim(date('d/m/Y', $timestamp));
$timestamp = strtotime($escrever["data_pag"]); // Gera o timestamp de $data_mysql
$dt_pag=trim(date('d/m/Y', $timestamp));


//Dinheiro
if($escrever["val_din"]!=0.0){
$tot=$tot+$escrever["val_din"];
$totdin=$totdin+$escrever["val_din"];
$totliq=$totliq+$escrever["val_din"];
}

//Cartao
if($escrever["val_cartao"]!=0.0){
$tot=$tot+$escrever["val_cartao"];
$totcar=$totcar+$escrever["val_cartao"];

if(substr($escrever["ticar"],0,6)=='DEBITO'){
$totcarde=$totcarde+$escrever["val_cartao"];
$totliq=$totliq+$escrever["val_cartao"];
}
elseif(substr($escrever["ticar"],0,6)=='PIX'){
$totcar=$totcar-$escrever["val_cartao"];  
$totcarpi=$totcarpi+$escrever["val_cartao"];
$totliq=$totliq+$escrever["val_cartao"];
}
elseif(substr($escrever["ticar"],0,6)=='TRANSF'){
$totcar=$totcar-$escrever["val_cartao"];
$totcartr=$totcartr+$escrever["val_cartao"];
$totliq=$totliq+$escrever["val_cartao"];
}
else{
$totcarcr=$totcarcr+$escrever["val_cartao"];
$totliq=$totliq+$escrever["val_cartao"]/$escrever["n_vez"];
}
} //cartao


//Cheque 1
if($escrever["val_ch1"]!=0.0){
$tot=$tot+$escrever["val_ch1"];
$totche=$totche+$escrever["val_ch1"];

if($escrever["dt_ch1"]>=$dtc and $escrever["dt_ch1"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch1"];
}

$a=strtotime($escrever["dt_ch1"]);
$b=trim(date('d/m/Y',$a));
$linhapag2=$linhapag2."<B>Valor Ch1: </B>".$escrever["val_ch1"]."<B>  Data Ch1: </B>".$b."  ";
$stringa2=$stringa2."Valor Ch1: ".$escrever["val_ch1"]."  Data Ch1: ".$b."  ";
}

//Cheque 2
if($escrever["val_ch2"]!=0.0){
$tot=$tot+$escrever["val_ch2"];
$totche=$totche+$escrever["val_ch2"];

if($escrever["dt_ch2"]>=$dtc and $escrever["dt_ch2"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch2"];
}

$a=strtotime($escrever["dt_ch2"]);
$b=trim(date('d/m/Y',$a));
$linhapag2=$linhapag2."<B>Valor Ch2: </B>".$escrever["val_ch2"]."<B>  Data Ch2: </B>".$b."  ";
$stringa2=$stringa2."Valor Ch2: ".$escrever["val_ch2"]."  Data Ch2: ".$b."  ";
}

//Cheque 3
if($escrever["val_ch3"]!=0.0){
$tot=$tot+$escrever["val_ch3"];
$totche=$totche+$escrever["val_ch3"];

if($escrever["dt_ch3"]>=$dtc and $escrever["dt_ch3"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch3"];
}

$a=strtotime($escrever["dt_ch3"]);
$b=trim(date('d/m/Y',$a));
$linhapag2=$linhapag2."<B>Valor Ch3: </B>".$escrever["val_ch3"]."<B>  Data Ch3: </B>".$b."  ";
$stringa2=$stringa2."Valor Ch3: ".$escrever["val_ch3"]."  Data Ch3: ".$b."  ";
}


//Cheque 4
if($escrever["val_ch4"]!=0.0){
$tot=$tot+$escrever["val_ch4"];
$totche=$totche+$escrever["val_ch4"];

if($escrever["dt_ch4"]>=$dtc and $escrever["dt_ch4"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch4"];
}

$a=strtotime($escrever["dt_ch4"]);
$b=trim(date('d/m/Y',$a));
$linhapag3=$linhapag3."<B>Valor Ch4: </B>".$escrever["val_ch4"]."<B>  Data Ch4: </B>".$b."  ";
$stringa3=$stringa3."Valor Ch4: ".$escrever["val_ch4"]."  Data Ch4: ".$b."  ";
}


//Cheque 5
if($escrever["val_ch5"]!=0.0){
$tot=$tot+$escrever["val_ch5"];
$totche=$totche+$escrever["val_ch5"];

if($escrever["dt_ch5"]>=$dtc and $escrever["dt_ch5"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch5"];
}

$a=strtotime($escrever["dt_ch5"]);
$b=trim(date('d/m/Y',$a));
$linhapag3=$linhapag3."<B>Valor Ch5: </B>".$escrever["val_ch5"]."<B>  Data Ch5: </B>".$b."  ";
$stringa3=$stringa3."Valor Ch5: ".$escrever["val_ch5"]."  Data Ch5: ".$b."  ";
} 

//Cheque 6
if($escrever["val_ch6"]!=0.0){
$tot=$tot+$escrever["val_ch6"];
$totche=$totche+$escrever["val_ch6"];

if($escrever["dt_ch6"]>=$dtc and $escrever["dt_ch6"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch6"];
}

$a=strtotime($escrever["dt_ch6"]);
$b=trim(date('d/m/Y',$a));
$linhapag3=$linhapag3."<B>Valor Ch6: </B>".$escrever["val_ch6"]."<B>  Data Ch6: </B>".$b."  ";
$stringa3=$stringa3."Valor Ch6: ".$escrever["val_ch6"]."  Data Ch6: ".$b."  ";
}

//Cheque 7
if($escrever["val_ch7"]!=0.0){
$tot=$tot+$escrever["val_ch7"];
$totche=$totche+$escrever["val_ch7"];

if($escrever["dt_ch7"]>=$dtc and $escrever["dt_ch7"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch7"];
}

$a=strtotime($escrever["dt_ch7"]);
$b=trim(date('d/m/Y',$a));
$linhapag4=$linhapag4."<B>Valor Ch7: </B>".$escrever["val_ch7"]."<B>  Data Ch7: </B>".$b."  ";
$stringa4=$stringa4."Valor Ch7: ".$escrever["val_ch7"]."  Data Ch7: ".$b."  ";
}

//Cheque 8
if($escrever["val_ch8"]!=0.0){
$tot=$tot+$escrever["val_ch8"];
$totche=$totche+$escrever["val_ch8"];

if($escrever["dt_ch8"]>=$dtc and $escrever["dt_ch8"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch8"];
}

$a=strtotime($escrever["dt_ch8"]);
$b=trim(date('d/m/Y',$a));
$linhapag4=$linhapag4."<B>Valor Ch8: </B>".$escrever["val_ch8"]."<B>  DataCh8: </B>".$b."  ";
$stringa4=$stringa4."Valor Ch8: ".$escrever["val_ch8"]."  Data Ch8: ".$b."  ";
}

//Cheque 9
if($escrever["val_ch9"]!=0.0){
$tot=$tot+$escrever["val_ch9"];
$totche=$totche+$escrever["val_ch9"];

if($escrever["dt_ch9"]>=$dtc and $escrever["dt_ch9"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch9"];
}

$a=strtotime($escrever["dt_ch9"]);
$b=trim(date('d/m/Y',$a));
$linhapag4=$linhapag4."<B>Valor Ch9: </B>".$escrever["val_ch9"]."<B>  DataCh9: </B>".$b."  ";
$stringa4=$stringa4."Valor Ch9: ".$escrever["val_ch9"]."  Data Ch9: ".$b."  ";
}

//Cheque 10
if($escrever["val_ch10"]!=0.0){
$tot=$tot+$escrever["val_ch10"];
$totche=$totche+$escrever["val_ch10"];

if($escrever["dt_ch10"]>=$dtc and $escrever["dt_ch10"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch10"];
}

$a=strtotime($escrever["dt_ch10"]);
$b=trim(date('d/m/Y',$a));
$linhapag5=$linhapag5."<B>Valor Ch10: </B>".$escrever["val_ch10"]."<B>  Data Ch10: </B>".$b."  ";
$stringa5=$stringa5."Valor Ch10: ".$escrever["val_ch10"]."  Data Ch10: ".$b."  ";
}

//Cheque 11
if($escrever["val_ch11"]!=0.0){
$tot=$tot+$escrever["val_ch11"];
$totche=$totche+$escrever["val_ch11"];

if($escrever["dt_ch11"]>=$dtc and $escrever["dt_ch11"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch11"];
}

$a=strtotime($escrever["dt_ch11"]);
$b=trim(date('d/m/Y',$a));
$linhapag5=$linhapag5."<B>Valor Ch11: </B>".$escrever["val_ch11"]."<B>  Data Ch11: </B>".$b."  ";
$stringa5=$stringa5."Valor Ch11: ".$escrever["val_ch11"]."  Data Ch11: ".$b."  ";
}

//Cheque 12
if($escrever["val_ch12"]!=0.0){
$tot=$tot+$escrever["val_ch12"];
$totche=$totche+$escrever["val_ch12"];

if($escrever["dt_ch12"]>=$dtc and $escrever["dt_ch12"]<=$dtc2){
$totliq=$totliq+$escrever["val_ch12"];
}

$a=strtotime($escrever["dt_ch12"]);
$b=trim(date('d/m/Y',$a));
$linhapag5=$linhapag5."<B>Valor Ch12: </B>".$escrever["val_ch12"]."<B>  Data Ch12: </B>".$b."  ";
$stringa5=$stringa5."Valor Ch12: ".$escrever["val_ch12"]."  Data Ch12: ".$b."  ";
}


if($tot!=0.0){
if($escrever["tilan"]=="MATRICULA") $timat++;
if($escrever["tilan"]=="RETORNO") $tiret++;
}

if($tot!=0.0) $npag=$npag+1;


if($escrever['data_pag']!=$datac2&&$datac2=="1900-01-01"):
 $datac2=$escrever['data_pag'];
endif;

if($escrever['data_pag']==$datac2):
  if($tot!=0.0) $npagpar=$npagpar+1;
  if($tot!=0.0) $nfatpar=$nfatpar+$tot;
endif;   

if($escrever['data_pag']!=$datac2&&$datac2!="1900-01-01"):
 $pernpag[$numdia2]=$npagpar;
 $pernfat[$numdia2]=$nfatpar;
 $numdia2++;
 $npagpar=0;
 $nfatpar=0;
 //$dianpag[$numdia1]=$numdia1+1;
 $timestamp = strtotime($datac2); // Gera o timestamp de $data_mysql
 $dt_pag=trim(date('d', $timestamp));
 $dianpag[$numdia1]=$dt_pag;
 $numdia1++;
 if($tot!=0.0) $npagpar=$npagpar+1;
 if($tot!=0.0) $nfatpar=$nfatpar+$tot;
 $datac2=$escrever['data_pag'];
endif;  


$nomeop=$escrever["operador"];

if ($numope==0&&$tot!=0.0):
   $arrope[0][0]=$nomeop;
   $arrope[0][1]=$tot;
   $numope++;
elseif($tot!=0.0):
   $flag_tr=0;
   for($tr=0;$tr<$numope;$tr++){
     if($arrope[$tr][0]==$escrever["operador"]):
         $arrope[$tr][1]=$arrope[$tr][1]+$tot;
         $flag_tr=1;
     endif;
    }//for
     
     if($flag_tr==0):
      $arrope[$numope][0]=$nomeop;
      $arrope[$numope][1]=$tot;
      $numope++;
     endif; 

endif;

}/*Fim do while*/

//manca l´ultimo
 $timestamp = strtotime($datac2); // Gera o timestamp de $data_mysql
 $dt_pag=trim(date('d', $timestamp));
 $dianpag[$numdia1]=$dt_pag;
 $pernpag[$numdia2]=$npagpar;
 $pernfat[$numdia2]=$nfatpar;
 



$tot2=$totdin+$totcar+$totche+$totcarpi+$totcartr;


$conn->close();



function regressione($X,$Y,$p)
{
  if (!is_array($X) && !is_array($Y)) return false;
  if (count($X) <> count($Y)) return false;
  if (empty($X) || empty($Y)) return false;
 
  $regres = array();
  //$reg=array();
  $n = count($X);
  $mx = array_sum($X)/$n; // media delle x
  $my = array_sum($Y)/$n; // media delle y
  $sxy = 0;
  $sxsqr = 0;
 
  for ($i=0;$i<$n;$i++){
    $sxy += ($X[$i] - $mx) * ($Y[$i] - $my);
    $sxsqr += pow(($X[$i] - $mx),2); // somma degli scarti quadratici medi
  }
 
  $m = $sxy / $sxsqr; // coefficiente angolare
  $q = $my - $m * $mx; // termine noto
 
  for ($i=0;$i<$n;$i++){
    $regres[$i] = $m * $X[$i] + $q;
  }
 
 //$reg[0] = $m * ($p+1) + $q;
 //$reg[1] = $m * ($p+2) + $q;
 //$reg[2] = $m * ($p+3) + $q;
 //$reg[3] = $m * ($p+4) + $q;

 $reg = $m * ($p+1) + $q;

return number_format( $reg, 1);
}

function regre($X,$Y,$p)
{
  if (!is_array($X) && !is_array($Y)) return false;
  if (count($X) <> count($Y)) return false;
  if (empty($X) || empty($Y)) return false;
 
  $regres = array();
  //$reg=array();
  $n = count($X);
  $mx = array_sum($X)/$n; // media delle x
  $my = array_sum($Y)/$n; // media delle y
  $sxy = 0;
  $sxsqr = 0;
 
  for ($i=0;$i<$n;$i++){
    $sxy += ($X[$i] - $mx) * ($Y[$i] - $my);
    $sxsqr += pow(($X[$i] - $mx),2); // somma degli scarti quadratici medi
  }
 
  $m = $sxy / $sxsqr; // coefficiente angolare
  $q = $my - $m * $mx; // termine noto
 
  for ($i=0;$i<$n;$i++){
    $regres[$i] = $m * $X[$i] + $q;
  }
 
 //$reg[0] = $m * ($p+1) + $q;
 //$reg[1] = $m * ($p+2) + $q;
 //$reg[2] = $m * ($p+3) + $q;
 //$reg[3] = $m * ($p+4) + $q;

 $reg = $m * ($p+1) + $q;

return number_format( $reg, 1);
}

$diapred=array();
$pernpagf=array();
$pred=array();
$art=array();



$i=0;
$media=0;

foreach($pernpag as $value){
  $media=$media+$value;
  $i++;
}
$media=$media/$i;

//echo $media."\n";

$i=0;
foreach($pernpag as $value){
  if($value/$media>=0.2) {
    $pernpagf[$i]=$value;
    $diapred[$i]=$i+1;
    $i++;
    } 
}

$ctn=$i;

$pred[0]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[0];

$pernpagf=$art;

$pred[1]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[1];
$pernpagf=$art;

$pred[2]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[2];
$pernpagf=$art;

$pred[3]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[3];
$pernpagf=$art;

$pred[4]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[4];
$pernpagf=$art;


$pred[5]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[5];
$pernpagf=$art;

$pred[6]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[6];
$pernpagf=$art;

$pred[7]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[7];
$pernpagf=$art;

$pred[8]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[8];
$pernpagf=$art;

$pred[9]=regressione($diapred,$pernpagf,$ctn);

$j=0;
for($i=0;$i<$ctn;$i++){
  if($i==0) continue;
  $art[$j]=$pernpagf[$i];
  $j++;
}

$art[$j]=$pred[9];
$pernpagf=$art;


//print_r($art);
//print_r($pernpagf);
//print_r($pred);

$i=0;
$media=0;
foreach($pred as $value){
  $media=$media+$value;
  $i++;
}

$media=$media/$i;
$media=number_format( $media, 1);
$totale=(int)($media*10);

$npred=array(1,2,3,4,5,6,7,8,9,10);

//qui comincio con predizione desistenze
$diades=array();
//$perdes=array();
$predes=array();
$artdes=array();

//print_r($ndesh);

$i=0;
foreach($ndesh as $value){
  $diades[$i]=$i+1;
  $i++;
  }


$ctn2=$i;

$predes[0]=regre($diades,$ndesh,$ctn);

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[0];

$ndesh=$artdes;//finisce qui

$predes[1]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[1];

$ndesh=$artdes;//finisce qui

$predes[2]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[2];

$ndesh=$artdes;//finisce qui


$predes[3]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[3];

$ndesh=$artdes;//finisce qui


$predes[4]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[4];

$ndesh=$artdes;//finisce qui

$predes[5]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[5];

$ndesh=$artdes;//finisce qui

$predes[6]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[6];

$ndesh=$artdes;//finisce qui

$predes[7]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[7];

$ndesh=$artdes;//finisce qui

$predes[8]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[8];

$ndesh=$artdes;//finisce qui

$predes[9]=regre($diades,$ndesh,$ctn);//comincia qui

$j=0;
for($i=0;$i<$ctn2;$i++){
  if($i==0) continue;
  $artdes[$j]=$ndesh[$i];
  $j++;
}

$artdes[$j]=$predes[9];

$ndesh=$artdes;//finisce qui

$i=0;
$media2=0;
foreach($predes as $value){
  $media2=$media2+$value;
  $i++;
}
$media2=$media2/$i;
$media2=number_format( $media2, 1);
$totale2=(int)($media2*10);

//print_r($predes);

//print_r($ndesh);



$ar = array('diapred'=>$diapred,'pernpagf'=>$pernpagf,'pred'=>$pred,'npred'=>$npred,
  'media'=>$media,'totale'=>$totale,'media2'=>$media2,'totale2'=>$totale2,'predes'=>$predes);

echo json_encode($ar);


//print_r($arrope);
//print_r($dianpag);


?>
       
