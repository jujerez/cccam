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
    <link rel="stylesheet" href="css/main.css">

    <title>Inicio instaladores</title>
  </head>
  <body>
    <?php
        require __DIR__ . '/../auxiliar.php';
        mostrarMenu();

        $pdo = conectar();
        $id = trim($_GET['id']);
        const PAR_URL = [
            'servidor' => ''
          , 'puerto' => '' 
          , 'usuario' => '' 
          , 'password' => '' 
          , 'fecha_alta' => '' 
          , 'cliente' => ''
          ,
        ];
        $errores = [];
        $parametros = comprobarParametrosInsertar(PAR_URL, $errores);
        comprobarValoresInsertar($parametros,$errores,$pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errores)) {
        $sent = $pdo->prepare('UPDATE clines
                                 SET servidor = :servidor
                                    , puerto = :puerto
                                    , usuario = :usuario
                                    , password = :password
                                    , fecha_alta = :fecha_alta
                                    , cliente_id = :cliente
                                 WHERE id = :id');
        $parametros['id']=$id;
        $sent->execute($parametros);
        alert('La fila se ha modificado correctamente.');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $sent = $pdo->prepare('SELECT *
                                     FROM clines
                                    WHERE id = :id');
            $sent->execute(['id' => $id]);
            //$parametros = $sent->fetch(PDO::FETCH_ASSOC);
            if (($parametros = $sent->fetch(PDO::FETCH_ASSOC)) === false) {
                //aviso('Error al modificar fila.', 'danger');
                header('Location: index.php');
                return;
            }
        }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-6 offset-3 mt-5 p-3" style="box-shadow: 2px 2px 10px #666;">
            <h2>Modificar cccam</h2><hr>
            <form  action="" method="post">
                <div class="form-group">
                    <label>Servidor</label>
                    <input 
                        type="text" 
                        class="form-control <?=esValido('servidor',$errores)?>"                        
                        name="servidor" 
                        value="<?=$parametros['servidor']?>"    
                    > 
                   <?=mensajeError('servidor',$errores)?> 
                </div>

                <div class="form-group">
                    <label>Puerto</label>
                    <input type="number" 
                           class="form-control <?=esValido('puerto',$errores)?>" 
                           name="puerto" 
                           value="<?=$parametros['puerto']?>"
                    > 
                    <?=mensajeError('puerto',$errores)?>    
                </div>

                <div class="form-group">
                    <label>Usuario</label>
                    <input type="text" 
                           class="form-control <?=esValido('usuario',$errores)?>" 
                           name="usuario" 
                           value="<?=$parametros['usuario']?>"
                    >   
                    <?=mensajeError('usuario',$errores)?> 
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="text" 
                           class="form-control <?=esValido('password',$errores)?>" 
                           name="password" 
                           value="<?=$parametros['password']?>"
                    >   
                    <?=mensajeError('password',$errores)?> 
                </div>
                
                <div class="form-group">
                    <label>Fecha de alta</label>
                    <input type="date" 
                           class="form-control <?=esValido('fecha_alta',$errores)?>" 
                           name="fecha_alta" 
                           value="<?=$parametros['fecha_alta']?>"
                    >
                    <?=mensajeError('fecha_alta',$errores)?>    
                </div>

                <?php
                    $sent = $pdo->prepare('SELECT *
                                            FROM clientes
                                            WHERE usuario_id = :usuario_id;
                                                ');
                    $sent->execute(['usuario_id'=>$_SESSION['id']]);
                ?>



                <div class="input-group mb-3">
    
                    <select class="custom-select" name="cliente">
                        <?php foreach ($sent as $fila => $v): ?>
                            <option value="<?=$v['id']?>"><?=$v['nombre']?></option>
                            
                        <?php endforeach ?> 
                    </select>
                 </div>
                
                
                <button type="submit" class="btn btn-dark active">Modificar </button>
            </form>
        </div>
        
    </div>
        

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