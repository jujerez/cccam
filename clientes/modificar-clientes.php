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

    <title>Modificar Clientes</title>
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
          
        ];

        $id = trim($_GET['id']);
        $errores = [];
        $parametros = comprobarParametrosInsertar(PAR_URL, $errores);
        comprobarValoresClientes($parametros,$errores,$pdo);
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errores)) {
        $sent = $pdo->prepare('UPDATE clientes
                                 SET nombre = :nombre
                                    , telefono = :telefono
                                    , direccion = :direccion
                                    , nota = :nota
                                    , usuario_id = :usuario_id
                                 WHERE id = :id');


        $res=$sent->execute([
            'nombre'     => $parametros['nombre'],
            'telefono'   => $parametros['telefono'],
            'direccion'  => $parametros['direccion']?: NULL,
            'nota'       => $parametros['nota'] ?: NULL,
            'usuario_id' => $_SESSION['id'],
            'id'         => $id,
            
        ]);
        
        $res ? alert('El cliente se ha modificado correctamente.')
             : alert('Error al modificar cliente', 'alert-danger');
        
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sent = $pdo->prepare('SELECT *
                                     FROM clientes
                                    WHERE id = :id AND usuario_id = :usuario_id');
            $sent->execute([
                              'id' => trim($_GET['id']) 
                             , 'usuario_id' => $_SESSION['id']
                             ]);
            
            if (($parametros = $sent->fetch(PDO::FETCH_ASSOC)) === false) {
                alert('Error interno del servidor.', 'alert-danger');
            }
        }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-6 offset-3 mt-5 p-3" style="box-shadow: 2px 2px 10px #666;">
            <h2>Modificar cliente</h2><hr>
            <form  action="" method="post">
                <div class="form-group">
                    <label>Nombre</label>
                    <input 
                        type="text" 
                        class="form-control <?=esValido('nombre',$errores)?>"                        
                        name="nombre" 
                        value="<?=$parametros['nombre']?>"    
                    > 
                   <?=mensajeError('nombre',$errores)?> 
                </div>

                <div class="form-group">
                    <label>telefono</label>
                    <input type="number" 
                           class="form-control <?=esValido('telefono',$errores)?>" 
                           name="telefono" 
                           value="<?=$parametros['telefono']?>"
                    > 
                    <?=mensajeError('telefono',$errores)?>    
                </div>

                <div class="form-group">
                    <label>direccion</label>
                    <input type="text" 
                           class="form-control <?=esValido('direccion',$errores)?>" 
                           name="direccion" 
                           value="<?=$parametros['direccion']?>"
                    >   
                    <?=mensajeError('direccion',$errores)?> 
                </div>

                <div class="form-group">
                    <label>nota</label>
                    <input type="text" 
                           class="form-control <?=esValido('nota',$errores)?>" 
                           name="nota" 
                           value="<?=$parametros['nota']?>"
                    >   
                    <?=mensajeError('nota',$errores)?> 
                </div>
        
                
                <button type="submit" class="btn btn-dark active">Modificar</button>
                <a href="mostrar-clientes.php" class="btn btn-info active" role="button">Volver</a>
            </form>
        </div>
        
    </div>
        

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
    <script>$(document).ready(function() {$('body').bootstrapMaterialDesign();});</script>
    <script>

    </script>
  </body>
</html>