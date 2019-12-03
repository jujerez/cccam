<?php
    function alert($mensaje, $tipo = 'alert-success')
    { 
    ?>
        <div class="container">
            <div class="row">
                <div class="col-6 offset-3">
                    
                    <div class="alert <?=$tipo?>" role="alert">
                        <?=$mensaje?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    <?php
    }

    function conectar()
    {
        return new PDO('pgsql:host=localhost;dbname=cccam', 'usuario', 'usuario');
    }

    function compruebaLogin() 
    {
        if (isset($_POST['login'])) {
            $pdo = conectar();
            $sent = $pdo->prepare('SELECT * FROM usuarios WHERE nick = :nick');
            $nick = $_POST['login'];
            $sent->execute(['nick' => $nick]);
          
            // Comprobar si el usuario existe
              if (($fila = $sent->fetch()) !== false) {
                  if ($_POST['password']!=='') {
                      if (password_verify($_POST['password'], $fila['password'])) {
                        $_SESSION['login']= $nick;
                          header('Location: index.php');
                          return;
                      } else {
                  
                  // "Contraseña incorrecta";
                          alert('Usuario o contraseña invalida', 'danger');
                      }
                  } else {
                      alert('La contraseña no puede estar vacía', 'danger');
                  }
              } else {
                  // El usuario no existe
                  alert('Usuario o contraseña invalida', 'danger');
              }
            }
    }


    function mostrarMenu() 
    {
        ?>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Bienvenido <?=$_SESSION['login']?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Inicio <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insertar-cliente.php">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insertar-cline.php">Clines</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insertar-decodificador.php">Descodficadores</a>
                    </li>
                      
                </ul>
                
            </div>

            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                  
                <ul class="navbar-nav ml-auto">
                    
                    <li class="nav-item">
                      <?php if (isset($_SESSION['login'])):?>
                        <a class="nav-link text-white" href="logout.php">Logout<span class="sr-only">(current)</span></a>
                      <?php else:?>

                        <a class="nav-link text-white" href="login.php">Login<span class="sr-only">(current)</span></a>
                      <?php endif?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Registrarse</a>
                    </li> 
                </ul>

                  
            </div>
        </nav>
        <?php
    }

    function mostrarFormularioLogin() 
    {
        ?>
        <div class="container ">
            <div class="row mt-5 p-5">
                <div class="col-6 offset-3 p-5 borde-sombreado">
                
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="mail">Usuario</label>
                            <input type="text" class="form-control" id="mail" name="login" aria-describedby="emailHelp" placeholder="Usuario o correo electronico">
                            
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <div class="input-group-append">
                            <input id="pass" type="password" class="form-control " name="password" placeholder="Contraseña">
                                <button id="showpassword" class="btn btn-primary" type="button">
                                <i class="material-icons">remove_red_eye</i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </form>
                    
                </div>
                
            </div>
        </div>
        
        <?php
    }

    function borrarFila($pdo, $tabla, $id)
    {
        $sent = $pdo->prepare("DELETE
                                FROM $tabla
                                WHERE id = :id");
        $sent->execute(['id' => $id]);

        if ($sent->rowCount() === 1) {
            echo 'Fila borrada correctamente';
            header('Location: index.php');
        } else {
            //alert('Ha ocurrido un error inesperado.', 'danger');
            echo('Ha ocurrio un error inesperado');
        }
    }

    function comprobarParametrosInsertar($par, &$errores)
    {
            $resultado = $par;
            if (!empty($_POST)) {
                if (empty(array_diff_key($par, $_POST)) &&
                    empty(array_diff_key($_POST, $par))) {
                    $resultado = array_map('trim', $_POST);
                } else {
                    $errores[] = 'Los parámetros recibidos no son los correctos.';
                }
            }
            return $resultado;
    }

    

    function comprobarValoresInsertar($args, &$errores)
    {
        if (!empty($errores) || empty($_POST)) {
            return;
        }

        extract($args);    // convierte las claves del array en variables

        if ($servidor !== '') {
            if (mb_strlen($servidor) > 255) {
                $errores['servidor'] = 'El servidor no puede tener más de 255 caracteres.';
            }
        } else {
            $errores['servidor'] = 'El nombre del servidor  es obligatirio.';
        }

        if ($puerto !== '') {
            if (!ctype_digit($puerto)) {
                $errores['puerto'] = 'El número de puerto debe ser un número entero positivo.';
            } elseif (mb_strlen($puerto) > 5) {
                $errores['puerto'] = 'El número no puede tener más de 4 dígitos.';
            } elseif ($puerto > 65536 || $puerto <= 1024) {
                $errores['puerto'] = 'El puerto debe estar comprendido entre 1024 y 65536';
            }
        } else {
            $errores['paginas'] = 'El número paginas es obligatorio.';

        }

        if ($usuario !== '') {
            if (mb_strlen($usuario) > 255) {
                $errores['usuario'] = 'El usuario no puede tener más de 255 caracteres.';
            }
        } else {
            $errores['usuario'] = 'El nombre del usuario  es obligatirio.';
        }

        if ($password !== '') {
            if (mb_strlen($password) > 255) {
                $errores['password'] = 'El password no puede tener más de 255 caracteres.';
            }
        } else {
            $errores['password'] = 'El nombre del password  es obligatirio.';
        }

        if ($fecha_alta !== '') {
            if (!(validarFecha($fecha_alta))) {
                $errores['fecha_alta'] = 'La fecha proporcionada no es valida';
            }
        } else {
            $errores['fecha_publi'] = 'La fecha es obligatoria';
        }


    }

    function comprobarValoresClientes($args, &$errores)
    {
        if (!empty($errores) || empty($_POST)) {
            return;
        }

        extract($args);    // convierte las claves del array en variables

        if ($nombre !== '') {
            if (mb_strlen($nombre) > 255) {
                $errores['nombre'] = 'El nombre no puede tener más de 255 caracteres.';
            }
        } else {
            $errores['nombre'] = 'El nombre del cliente es obligatirio.';
        }

        if ($telefono !== '') {
            if (!ctype_digit($telefono)) {
                $errores['telefono'] = 'El número de telefono debe ser un número entero positivo.';
            } 
            if(mb_strlen($telefono) > 9 || mb_strlen($telefono)<9 ) {
                $errores['telefono'] = 'El número de teléfono tiene que ser de 9 digitos.';
            } 
        } else {
            $errores['paginas'] = 'El teléfono es obligatorio.';

        }

        
        if (mb_strlen($direccion) > 255) {
            $errores['direccion'] = 'La direccion no puede tener más de 255 caracteres.';
        }

        if (mb_strlen($nota) > 255) {
            $errores['nota'] = 'La nota no puede tener más de 255 caracteres.';
        }
       

        


    }

    function validarFecha($fecha){

        $valores = explode('-', $fecha);
        if(count($valores) == 3 && checkdate($valores[1], $valores[2], $valores[0])){
            return true;
        }
        return false;
    }

    function mensajeError($campo, $errores)
    {
        if (isset($errores[$campo])) {
            return <<<EOT
            <div class="invalid-feedback">
                {$errores[$campo]}
            </div>
            EOT;
        } else {
            return '';
        }
    }

    function esValido($campo, $errores)
    {
        
        if (isset($errores[$campo])) {
            return 'is-invalid';
        } elseif (!empty($_POST)) {
            return 'is-valid';
        } else {
            return '';
        }
    }



?>
