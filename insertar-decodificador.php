<?php
session_start();
if (!isset($_SESSION['login'])){
    header('Location: login.php');
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
    <link rel="stylesheet" href="css/main.css">
    <title>Inicio instaladores</title>
  </head>
  <body>
    <?php
        require __DIR__ . '/auxiliar.php';
        mostrarMenu();
        $pdo = conectar();
        const PAR_URL = [
                            'marca' => ''
                          , 'modelo' => '' 
                          , 'serial' => '' 
                          , 'fecha_compra' => '' 
                          , 'lugar_compra' => '' 
                          , 'cliente' => ''
                          ,
                        ];
        $errores = [];
        $parametros = comprobarParametrosInsertar(PAR_URL, $errores);
        comprobarValoresDeco($parametros,$errores);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errores)) {
            $sent = $pdo->prepare('INSERT
                                     INTO descodificadores (marca, modelo, serial, fecha_compra, lugar_compra, cliente_id)
                                   VALUES (:marca, :modelo, :serial, :fecha_compra, :lugar_compra, :cliente)');
            $sent->execute($parametros);
            alert('La fila se ha insertado correctamente.');
            
        }
    
        
    ?>

    <div class="container">
        <div class="row">
            <div class="col-6 offset-3 mt-5 p-3" style="box-shadow: 2px 2px 10px #666;">
            <h2>Insertar descodificador</h2><hr>
            <form  action="" method="post">
                <div class="form-group">
                    <label>marca</label>
                    <input 
                        type="text" 
                        class="form-control <?=esValido('marca',$errores)?>"                        
                        name="marca" 
                        value="<?=$parametros['marca']?>"    
                    > 
                   <?=mensajeError('marca',$errores)?> 
                </div>

                <div class="form-group">
                    <label>modelo</label>
                    <input type="text" 
                           class="form-control <?=esValido('modelo',$errores)?>" 
                           name="modelo" 
                           value="<?=$parametros['modelo']?>"
                    > 
                    <?=mensajeError('modelo',$errores)?>    
                </div>

                <div class="form-group">
                    <label>serial</label>
                    <input type="text" 
                           class="form-control <?=esValido('serial',$errores)?>" 
                           name="serial" 
                           value="<?=$parametros['serial']?>"
                    >   
                    <?=mensajeError('serial',$errores)?> 
                </div>

                <div class="form-group">
                    <label>fecha_compra</label>
                    <input type="date" 
                           class="form-control <?=esValido('fecha_compra',$errores)?>" 
                           name="fecha_compra" 
                           value="<?=$parametros['fecha_compra']?>"
                    >   
                    <?=mensajeError('fecha_compra',$errores)?> 
                </div>
                
                <div class="form-group">
                    <label>lugar_compra</label>
                    <input type="text" 
                           class="form-control <?=esValido('lugar_compra',$errores)?>" 
                           name="lugar_compra" 
                           value="<?=$parametros['lugar_compra']?>"
                    >
                    <?=mensajeError('lugar_compra',$errores)?>    
                </div>

                <?php
                    $sent = $pdo->query('SELECT *
                                            FROM clientes
                                            WHERE true;
                                                ');
                ?>



                <div class="input-group mb-3">
    
                    <select class="custom-select" name="cliente">
                        <?php foreach ($sent as $fila => $v): ?>
                            <option value="<?=$v['id']?>"><?=$v['nombre']?></option>
                            
                        <?php endforeach ?> 
                    </select>
                 </div>
                
                
                <button type="submit" class="btn btn-dark active">Insertar</button>
            </form>
        
    </div>

            



    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/popper.js@1.12.6/dist/umd/popper.js" integrity="sha384-fA23ZRQ3G/J53mElWqVJEGJzU0sTs+SvzG8fXVWP+kJQ1lwFAOkcUOysnlKJC33U" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-material-design@4.1.1/dist/js/bootstrap-material-design.js" integrity="sha384-CauSuKpEqAFajSpkdjv3z9t8E7RlpJ1UP0lKM/+NdtSarroVKu069AlsRPKkFBz9" crossorigin="anonymous"></script>
    <script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>
    <script>
        eliminar.onclick = function() {
            if(!confirm('¿Seguro que quieres eliminar?')) {
                return false;
            };
            return true;
            
        }
    </script>
  </body>
</html>