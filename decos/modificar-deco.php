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
   // mostrarMenu();
    $pdo = conectar();
    const PAR_URL = [
          'marca' => ''
        , 'modelo' => '' 
        , 'serial' => '' 
        , 'fecha_compra' => '' 
        , 'lugar_compra' => ''
            
    ];
    $errores = [];
    $parametros = comprobarParametrosInsertar(PAR_URL, $errores);
    comprobarValoresDeco($parametros,$errores,$pdo); 
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errores)) {
    $sent = $pdo->prepare('UPDATE descodificadores
                                SET marca = :marca
                                , modelo = :modelo
                                , serial = :serial
                                , fecha_compra = :fecha_compra
                                , lugar_compra = :lugar_compra
                                , usuario_id = :usuario_id
                                WHERE id = :id');
        
        $res=$sent->execute([
            'marca'        => $parametros['marca'],
            'modelo'       => $parametros['modelo'],
            'serial'       => $parametros['serial'],
            'fecha_compra' => $parametros['fecha_compra'],
            'lugar_compra' => $parametros['lugar_compra'],
            'usuario_id'   => $_SESSION['id'],
            'id'           => trim($_GET['id'])

        ]);
        
        $res ? alert('El deco se ha modificado correctamente.'): alert('Error');
    
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sent = $pdo->prepare('SELECT *
                                    FROM descodificadores
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
            <h2>Modificar deco</h2><hr>
            <form  action="" method="post">
                <div class="form-group">
                    <label>Marca</label>
                    <input 
                        type="text" 
                        class="form-control <?=esValido('marca',$errores)?>"                        
                        name="marca" 
                        value="<?=$parametros['marca']?>"    
                    > 
                   <?=mensajeError('marca',$errores)?> 
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" 
                           class="form-control <?=esValido('modelo',$errores)?>" 
                           name="modelo" 
                           value="<?=$parametros['modelo']?>"
                    > 
                    <?=mensajeError('modelo',$errores)?>    
                </div>

                <div class="form-group">
                    <label>Serial</label>
                    <input type="text" 
                           class="form-control <?=esValido('serial',$errores)?>" 
                           name="serial" 
                           value="<?=$parametros['serial']?>"
                    >   
                    <?=mensajeError('serial',$errores)?> 
                </div>

                <div class="form-group">
                    <label>Fecha compra</label>
                    <input type="date" 
                           class="form-control <?=esValido('fecha_compra',$errores)?>" 
                           name="fecha_compra" 
                           value="<?=$parametros['fecha_compra']?>"
                    >   
                    <?=mensajeError('fecha_compra',$errores)?> 
                </div>

                <div class="form-group">
                    <label>Lugar de compra</label>
                    <input type="text" 
                           class="form-control <?=esValido('lugar_compra',$errores)?>" 
                           name="lugar_compra" 
                           value="<?=$parametros['lugar_compra']?>"
                    >   
                    <?=mensajeError('lugar_compra',$errores)?> 
                </div>
        
                
                <button type="submit" class="btn btn-dark active">Modificar</button>
                <a href="mostrar-decos.php" class="btn btn-info active" role="button">Volver</a>

            </form>
        </div>
        
    </div>
        

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
    <script>$(document).ready(function() {$('body').bootstrapMaterialDesign();});</script>
    
  </body>
</html>