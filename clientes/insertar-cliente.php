<?php
session_start();
if (!isset($_SESSION['login'])){
    header('Location: /usuarios/login.php');
    return;
}
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
    <link rel="stylesheet" href="/css/main.css">
    <title>Inicio instaladores</title>
  </head>
  <body>
    <?php
        require __DIR__ . '/../auxiliar.php';
        mostrarMenu();
        $pdo = conectar();
        const PAR_URL = [
                            'nombre' => ''
                          , 'telefono' => '' 
                          , 'direccion' => '' 
                          , 'nota' => '' 
                          ,
                        ];
        $errores = [];

        $_csrf = (isset($_POST['_csrf'])) ? $_POST['_csrf'] : null;
        unset($_POST['_csrf']);

        

        $parametros = comprobarParametrosInsertar(PAR_URL, $errores);
        comprobarValoresClientes($parametros,$errores);
   
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errores)) {
            
            if (!tokenValido($_csrf)) {
               // alert('El token de CSRF no es válido.', 'alert-danger');
                alert('Error interno del servidor.', 'alert-danger');
                
            } else {

                $sent = $pdo->prepare('INSERT
                                            INTO clientes (nombre, telefono, direccion, nota, usuario_id)
                                        VALUES (:nombre, :telefono, :direccion, :nota, :usuario_id)');
    
                $sent->execute([ 'nombre' => $parametros['nombre']
                                , 'telefono' => $parametros['telefono']
                                , 'direccion' => $parametros['direccion'] ?: null
                                , 'nota' => $parametros['nota'] ?: null
                                , 'usuario_id' => $_SESSION['id']
                            
                ]);
                
                alert('La fila se ha insertado correctamente.');
                $parametros = PAR_URL;
                
               
            }
            
        }
    

    
        
    ?>

    <div class="container">
        <div class="row">
            <div class="col-6 offset-3 mt-5 p-3 " style="box-shadow: 2px 2px 10px #666;">
            <h2>Insertar cliente</h2>
            <hr>
            <form  id="formu" action="" method="post">
                <div class="form-group">
                    <label>Nombre</label>
                    <input 
                        type="text" 
                        class="form-control <?=esValido('nombre',$errores)?>"                        
                        name="nombre" 
                        value="<?=h($parametros['nombre'])?>"    
                    > 
                   <?=mensajeError('nombre',$errores)?> 
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="number" 
                           class="form-control <?=esValido('telefono',$errores)?>" 
                           name="telefono" 
                           value="<?=h($parametros['telefono'])?>"
                    > 
                    <?=mensajeError('telefono',$errores)?>    
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <input type="text" 
                           class="form-control <?=esValido('direccion',$errores)?>" 
                           name="direccion" 
                           value="<?=h($parametros['direccion'])?>"
                    >   
                    <?=mensajeError('direccion',$errores)?> 
                </div>

                <div class="form-group">
                    <label>nota</label>
                    <input type="text" 
                           class="form-control <?=esValido('nota',$errores)?>" 
                           name="nota" 
                           value="<?=h($parametros['nota'])?>"
                           >   
                           <?=mensajeError('nota',$errores)?> 
                </div>

                <?=token_csrf()?>
  
                <button type="submit" class="btn btn-dark active">Insertar</button>
                <button type="reset"  class="btn btn-dark active">Limpiar</button>
            </form>
        
    </div>

        
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
    <script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>
    
  </body>
</html>