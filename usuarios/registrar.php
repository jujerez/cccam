<?php
session_start();

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Material Design for Bootstrap fonts and icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons">

    <!-- Material Design for Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css" integrity="sha384-wXznGJNEXNG1NFsbm0ugrLFMQPWswR3lds2VeinahP8N0zJw9VWSopbjv2x7WCvX" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">

    <title>Registrar instalador</title>
  </head>
  <body>
    <?php
        require __DIR__ . '/../auxiliar.php';
        mostrarMenu();
        $pdo = conectar();
        const PAR_URL = [
                            'nick' => ''
                          , 'password' => '' 
                          , 'password_confirm' => '' 
                          , 'email' => '' 
            
                        ];
        $errores = [];
        $parametros = comprobarParametrosInsertar(PAR_URL, $errores);
        comprobarValoresRegistrar($parametros,$pdo,$errores);
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errores)) {
            $sent = $pdo->prepare('INSERT
                                     INTO usuarios (nick, password, email)
                                   VALUES (:nick, :password, :email)');

            $sent->execute([ 'nick' => $parametros['nick']
                           , 'password' => password_hash($parametros['password'], PASSWORD_DEFAULT)
                           , 'email' => $parametros['email']
                           
                       
            ]);
            alert('Usuario registrado correctamente.');

            
        }
        
        formRegistrar($parametros,$errores);
        
    ?>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
    <script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>
    <script>

        var eliminar = document.getElementsByClassName('eliminar');
        for (let i = 0; i < eliminar.length; i++) {
             
            eliminar[i].onclick = function() {
                if(!confirm('¿Seguro que quieres eliminar?')) {
                    return false;
                };
                return true;
                
            }
        }
    </script>
  </body>
</html>
