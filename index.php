
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>

    <!--Import Google Icon Font-->
    <!--<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">-->
    <!--Import materialize.css-->
    <!--<link rel="stylesheet" href="css/materm.css" media="screen,projection">-->
    
      <?php
          $min=1;
          $max=1000000000; 
          $var= rand ( $min , $max );

            //echo"<img src='img/logo.jpg?dummy=$var' class='menu'>";    
            echo"<link rel='stylesheet' type='text/css' href='style-login.css?version=$var'>";         
            echo"<link rel='shortcut icon' type='image/gif' href='3.gif?version=$var'>";
            echo"<link rel='stylesheet' type='text/css' href='icons/material.css?version=$var'>";
            echo"<link rel='stylesheet' media='screen,projection' href='css/materialize.min.css?version=$var'>";
           ?>

    
    <!--<link rel="stylesheet" type="text/css" href="style1-login.css?version=3">-->

    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0px;
            overflow-x: auto;
        }

        .bgLogin {
            background-image: url("bgLogin1.jpg");
            background-position: center center;
            background-size: cover;
            background-repeat: no-repeat;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: -1;
        }

        .container {
            height: 100%;
            width: 100%;
            margin: 0px;

            display: flex;
            justify-content: center;
            align-items: center;

        }

        .box-login {
            background-color: blue;
            width: 700px;
            height: 70%;
            display: flex;
        }

        .div-form {
            height: 100%;
            width: 50%;
            background-color: yellow;
        }

        .formulario {
            background: tomato;
        }
        .font-welcome {
            font-size: 45px;
            font-weight: bolder;
        }
    </style>
</head>

<body>
   
    <div class="principal" style="display: flex;justify-content: space-around;height: 100%;align-items: center;">
        <div class="corpo-fundo card-1">
            
            <div class="pai-form">
                <div class="corpo-form">
                    <h2 style="    font-weight: bolder; font-size: 35px;margin-top: 0px;">ADMIN</h2>

                    <span style="display: block;font-size: 12px;font-weight: bold;padding-bottom: 15px;padding-top: 0px;">
                        Senha : 6 Digitos Numericos</span>
                    <form action="index.php"  method="POST">
                        <!--<div class="input-field" style="margin-bottom: 25px;">
                            input id="ctEmail" type="email" class="validate" required>-->
                           <!-- <input id="ctEmail" type="email" class="validate" >
                            <label for="ctEmail">E-mail</label>
                        </div>-->
                        <div class="input-field">
                            <!--<input id="password" type="password" class="validate" required>-->
                            <input id="password" type="password" name="senha" class="validate" >
                            <label for="password">Senha</label>
                        </div>
                        <br><br>
                        <div style="display: flex;flex-direction: row-reverse;margin-bottom: 15px;">
                            <button class="btn waves-effect waves-light teal" type="submit">Login</button>
                        </div>
                    </form>

                    <div class="alert alert-dismissible">
                        <span style="width: 100%;" id="mens">Favor Insira a sua Senha</span>                       <!--<a href="#" id="btn-close" class="close"data-dismiss="alert" aria-label="close" style="font-size: 1.6em;color: fff;">&times</a>-->
                    </div>
                    <br>
                <h6 style="    font-weight: bolder; font-size: 12px;margin-top: 0px;">© Ing. Pietro Valente zap: 85-99133-9186</h6>
                </div>
            </div>
        </div>
   
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/materialize.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#modal1').modal();
            //$('#mens').html('Validade Sistema Vencida!');
            //class="close" 
            //$("#btn-close").click(function(){
              //  $(".alert.alert-dismissible").hide();
            //});
        });
    </script>


<?php

error_reporting(0);


function x()
{
include "vari.php";

$senha_op=$_POST['senha'];

$conn=new mysqli($host,$user,$pass,$banco);  

  if ($conn->connect_error){
    echo "<b>Erro:</b> ".$conn->connect_error; 
    return;
    }




$result=$conn->query("select * from validade");

$row=$result->fetch_array();



if($row['flag']!=1){
echo("<script>$('#mens').html('Validade Sistema Vencida!');</script>");
$conn->close();
return;
}


$result=$conn->query("select * from senhas where senha = '$senha_op' and nivel>='3'");
$nr = mysqli_num_rows($result);
  
  if($nr==0){
    $conn->close();
    echo("<script>$('#mens').html('Senha Invalida!');
         setTimeout(function(){ $('#mens').html('Favor Insira a sua Senha'); }, 1000);
        </script>");
    return;
   }

$row=$result->fetch_array();
$nome_op=$row['nome'];
$id_op=$row['id'];
//session_start(); // Inicia a sessão
//$_SESSION['ope_senha'] =$senha_op;
//$_SESSION['ope_no'] = $nome_op;
//$_SESSION['ope_id'] = $id_op;

$conn->close();

//script.php?nome1=valor1&nome2=valor2
//echo $_SESSION['ope_no']."Sono qui";
echo "<script>window.location='adm.html'</script>";
}



if (isset($_POST['senha'])){ //If it is the first time, it does nothing   
  //session_start(); // Inicia a sessão
  x();
  }

?>

</body>

</html>