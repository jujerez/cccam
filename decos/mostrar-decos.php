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
        $sent = $pdo->prepare('SELECT id, marca, modelo, serial, fecha_compra, lugar_compra, nombre
                                FROM descodificadores d
                                    JOIN (SELECT id AS idcliente, nombre
                                            FROM clientes) c
                                            ON d.cliente_id = c.idcliente
                                    WHERE usuario_id = :id');
        $sent->execute([
            'id' => $_SESSION['id'],
        ])
        
    ?>

    <div class="container">
        <div class="row">
            <div class="col mt-5">
            <table class="table table-bordered">
            <thead class="thead-oscuro">
                <tr>
                    <th scope="col">Marca</th>
                    <th scope="col">Modelo</th>
                    <th scope="col">Serial</th>
                    <th scope="col">Fecha compra</th>
                    <th scope="col">Lugar de compra</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Accion</th>
                </tr>
            </thead>
            
            <?php foreach ($sent as $fila => $v): ?>
                <tr>
                    <tbody>
                        <td><?=$v['marca']?></td>
                        <td><?=$v['modelo']?></td>
                        <td><?=$v['serial']?></td>
                        <td><?=$v['fecha_compra']?></td>
                        <td><?=$v['lugar_compra']?></td>
                        <td><?=$v['nombre']?></td>
                        <td class="p-1">  
                            <form action="eliminar-deco.php" method="post" class="mb-0">
                                <input type="hidden" name="id" value="<?=$v['id']?>">
                                <button type="submit"  class="btn btn-danger btn-sm active eliminar">Eliminar</button>
                            </form> 
                            <a href="modificar-deco.php?id=<?=$v['id']?>"><button class="btn btn-success btn-sm active mb-0 mt-0">Modificar</button></a>
                                
                        </td>
                </tbody>
                </tr>            
            <?php endforeach ?> 
        </table>
        
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
