<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With'); 
header('Content-Type: application/json; charset=utf-8');  

date_default_timezone_set("America/Sao_Paulo");


$a=$_GET['par'];



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



$st="select * from usuario where data_ac>='$dia' and data_ac<='2060-01-01' and flag_us='1'and substr(modalidade,1,5)<>'PERSO'";

$sql=$conn->query($st);
$alat = $sql->num_rows;

if($gf!=0):
  $st="select * from usuario where data_ac>='$gi' and data_ac<='$gf' and flag_us='1'and substr(modalidade,1,5)<>'PERSO'";
  $sql=$conn->query($st);
  $ades = $sql->num_rows;
else:
 $ades = 0;	
endif;

if($gf!=0):
  $gi=date('Y/m/01', strtotime('-1 months'));
  $me=date('m');
 
  switch($me){
   case 1:
     $gf=date('Y/m/d', strtotime('-32 days'));
     break;
   case 2:
     $gf=date('Y/m/d', strtotime('-32 days'));
     break;
   case 3:
     $gf=date('Y/m/d', strtotime('-29 days'));
     break;
   case 4:
     $gf=date('Y/m/d', strtotime('-32 days'));
     break;  
   case 5:
     $gf=date('Y/m/d', strtotime('-31 days'));
     break;  
   case 6:
     $gf=date('Y/m/d', strtotime('-32 days'));
     break; 
   case 7:
     $gf=date('Y/m/d', strtotime('-31 days'));
     break;
   case 8:
     $gf=date('Y/m/d', strtotime('-32 days'));
     break;
   case 9:
     $gf=date('Y/m/d', strtotime('-32 days'));
     break;
   case 10:
     $gf=date('Y/m/d', strtotime('-31 days'));
     break;
   case 11:
     $gf=date('Y/m/d', strtotime('-32 days'));
     break; 
   case 12:
     $gf=date('Y/m/d', strtotime('-31 days'));
     break;  
   }//switch
  //$gf=date('d/m/Y', strtotime('-1 months', strtotime('2021/10/16')));

  $st="select * from usuario where data_ac>='$gi' and data_ac<='$gf' and flag_us='1'and substr(modalidade,1,5)<>'PERSO'";
  $sql=$conn->query($st);
  $adess = $sql->num_rows;
else:
 $adess = 0;	
endif;

if($ades==0):
  $pdes=0;
else:
  $pdes=(($ades-$adess)/$adess)*100;
  $pdes = number_format($pdes, 2);
endif;



//FREQUENCIA
$nacc=array();
$diacc=array();
$dtc=date("Y-m-01");
$dtc2=date("Y-m-d");
$st="select * from freque where data>='$dtc'&&data<='$dtc2'";
$sql=$conn->query($st);

$datac2="1900-01-01";
$accp=0;//acessos parciais
$acct=0;//acessos totais
$numdia1=0;
$numdia2=0;

while($escrever=$sql->fetch_array()){
$acct++;
if($escrever['data']!=$datac2&&$datac2=="1900-01-01"):
 $datac2=$escrever['data'];
endif;

if($escrever['data']==$datac2):
   $accp++;
endif;   

if($escrever['data']!=$datac2&&$datac2!="1900-01-01"):
 $nacc[$numdia2]=$accp;
 $numdia2++;
 $timestamp = strtotime($datac2); // Gera o timestamp de $data_mysql
 $dt_acc=trim(date('d/m', $timestamp));
 $diacc[$numdia1]=$dt_acc;
 $numdia1++;
 $datac2=$escrever['data'];
 $accp=1;
endif;  

}//while

//manca l´ultimo
 $timestamp = strtotime($datac2); // Gera o timestamp de $data_mysql
 $dt_acc=trim(date('d/m', $timestamp));
 $diacc[$numdia1]=$dt_acc;

 $nacc[$numdia2]=$accp;


//CAIXA
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
$dtc=date("Y-m-01");
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

}/*Fim do while*/

//manca l´ultimo
 $timestamp = strtotime($datac2); // Gera o timestamp de $data_mysql
 $dt_pag=trim(date('d', $timestamp));
 $dianpag[$numdia1]=$dt_pag;
 $pernpag[$numdia2]=$npagpar;
 $pernfat[$numdia2]=$nfatpar;
 



$tot2=$totdin+$totcar+$totche+$totcarpi+$totcartr;





//CAIXA2
$totdia=0.0;
$totdin=0.0;
$totcar=0.0;
$totche=0.0;
$totcarcr=0.0;
$totcarde=0.0;
$totcarpi=0.0;
$totcartr=0.0;
$totliq=0.0;
$npag2=0;




$dtc=date("Y-m-01",strtotime('-1 months'));
$dtc2=date("Y-m-d",strtotime('-1 months'));


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




if($tot!=0.0) $npag2=$npag2+1;

}/*Fim do while*/

$ppag=(($npag-$npag2)/$npag2)*100;
$ppag = number_format($ppag, 2);


$tot3=$totdin+$totcar+$totche+$totcarpi+$totcartr;



//Custo1
$totcu=0.0;

$dtc=date("Y-m-01");
$dtc2=date("Y-m-d");

$st="select * from custos where data_pag>='$dtc' and data_pag<='$dtc2'order by data_pag"; 
$sql=$conn->query($st);
while($escrever=$sql->fetch_array()){

$timestamp = strtotime($escrever["data_pag"]); // Gera o timestamp de $data_mysql
$dt_pag=trim(date('d/m/Y', $timestamp));


$totcu=$totcu+$escrever["valor"];

}/*Fim do while*/


//Custo2
$totcu2=0.0;

$dtc=date("Y-m-01",strtotime('-31 days'));
$dtc2=date("Y-m-d",strtotime('-31 days'));

$st="select * from custos where data_pag>='$dtc' and data_pag<='$dtc2'order by data_pag"; 
$sql=$conn->query($st);
while($escrever=$sql->fetch_array()){

$timestamp = strtotime($escrever["data_pag"]); // Gera o timestamp de $data_mysql
$dt_pag=trim(date('d/m/Y', $timestamp));


$totcu2=$totcu2+$escrever["valor"];

}/*Fim do while*/



$pcus=(($totcu-$totcu2)/$totcu2)*100;
$pcus = number_format($pcus, 2);


$totcu = number_format($totcu, 2, ',', '.');
//$totcu2 = number_format($totcu2, 2, ',', '.');

//echo $totcu." ".$totcu2." ";


$pfat=(($tot2-$tot3)/$tot3)*100;
$pfat = number_format($pfat, 2);
//echo $tot2." ".$tot3." ";
//echo $pfat." ";

$tmed=$tot2/$npag;
$tmed2=$tot3/$npag2;
$ptme=(($tmed-$tmed2)/$tmed2)*100;
$ptme = number_format($ptme, 2);

$tot2 = number_format($tot2, 2, ',', '.');
$tmed = number_format($tmed, 2, ',', '.');


$conn->close();


$ar = array('fatt'=>$tot2,'alat' => $alat,'ades' => $ades,'tmed'=>$tmed,'npag'=>$npag,'pfat'=>$pfat,
'pdes'=>$pdes,'ppag'=>$ppag,'ptme'=>$ptme,'totcu'=>$totcu,'pcus'=>$pcus,'pernpag'=>$pernpag,'dianpag'=>$dianpag,'pernfat'=>$pernfat,'diacc'=>$diacc,'nacc'=>$nacc);
//$ar=[1,2,3,4,5,6,7,8,9];

echo json_encode($ar);

//print_r($diacc);
//print_r($dianpag);


?>
       
