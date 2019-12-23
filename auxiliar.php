<?php
    const FPP = 3;
    function alert($mensaje, $tipo = 'alert-success')
    { 
    ?>
        <div class="container">
            <div class="row">
                <div class="col-6 offset-3 mt-5">
                    
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
                        $_SESSION['id'] = $fila['id'];
                        $_SESSION['token']= md5(uniqid(mt_rand(), true)); 
                          header('Location: /index.php');
                          return;
                      } else {
                  
                  // "Contraseña incorrecta";
                          alert('Usuario o contraseña invalida', 'alert-danger');
                      }
                  } else {
                      alert('La contraseña no puede estar vacía', 'alert-danger');
                  }
              } else {
                  // El usuario no existe
                  alert('Usuario o contraseña invalida', 'alert-danger');
              }
            }
    }


    function mostrarMenu() 
    {
        ?>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">

                <?php if(isset($_SESSION['login'])): ?>
                    Bienvenido <?=$_SESSION['login']?>
                <?php else: ?>
                    Logueate
                <?php endif ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/index.php">Inicio <span class="sr-only">(current)</span></a>
                    </li>
                
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Clientes
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/clientes/mostrar-clientes.php">Mostrar clientes</a>
                            <a class="dropdown-item" href="/clientes/insertar-cliente.php">Añadir cliente</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Clines
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/clines/mostrar-clines.php">Mostrar clines</a>
                            <a class="dropdown-item" href="/clines/insertar-cline.php">Añadir clines</a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Descodificador
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/decos/mostrar-decos.php">Mostrar decos</a>
                            <a class="dropdown-item" href="/decos/insertar-decodificador.php">Añadir deco</a>
                        </div>
                    </li>                      
                </ul>
                
            </div>

            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                  
                <ul class="navbar-nav ml-auto">
                    
                    <li class="nav-item">
                      <?php if (isset($_SESSION['login'])):?>
                        <a class="nav-link text-white" href="/usuarios/logout.php">Logout<span class="sr-only">(current)</span></a>
                      <?php else:?>

                        <a class="nav-link text-white" href="/usuarios/login.php">Login<span class="sr-only">(current)</span></a>
                      <?php endif?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/usuarios/registrar.php">Registrarse</a>
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
                <div class="col-6 offset-3 p-5" style="box-shadow: 2px 2px 10px #666;">
                
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

    function borrarFila($pdo, $tabla, $id, $anterior)
    {
        $sent = $pdo->prepare("DELETE
                                FROM $tabla
                                WHERE id = :id");
        $sent->execute(['id' => $id]);

        if ($sent->rowCount() === 1) {
            echo 'Fila borrada correctamente';
            
            //header("Location:".$_SERVER['HTTP_REFERER']); 
            header("Location: $anterior"); 
        } else {
            alert('No es posible borrar el cliente, porque existe una cline asociada a el ', 'alert-danger');
            
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

    

    function comprobarValoresInsertar($args, &$errores, $pdo)
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
                $errores['puerto'] = 'El puerto debe ser un número entero positivo.';
            } elseif (mb_strlen($puerto) > 5) {
                $errores['puerto'] = 'El puerto no puede tener más de 4 dígitos.';
            } elseif ($puerto > 65536 || $puerto <= 1024) {
                $errores['puerto'] = 'El puerto debe estar comprendido entre 1024 y 65536';
            }
        } else {
            $errores['puerto'] = 'El número de puerto es obligatorio.';

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

        if (isset($args['cliente'])) {
            if ($cliente === '') {
                $errores['cliente'] = 'El cliente es obligatorio.';
            } elseif (!ctype_digit($cliente)) {
                $errores['cliente'] = 'El cliente no tiene el formato correcto.';
            } else {
                $sent = $pdo->prepare('SELECT COUNT(*)
                                         FROM clientes
                                        WHERE id = :id');
                $sent->execute(['id' => $cliente]);
                if ($sent->fetchColumn() === 0) {
                    $errores['cliente'] = 'El cliente no existe.';
                }
            }
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

    function comprobarValoresDeco($args, &$errores, $pdo)
    {
        if (!empty($errores) || empty($_POST)) {
            return;
        }

        extract($args);    // convierte las claves del array en variables

        if ($marca !== '') {
            if (mb_strlen($marca) > 255) {
                $errores['marca'] = 'El marca no puede tener más de 255 caracteres.';
            }
        } else {
            $errores['marca'] = 'El nombre de la marca  es obligatorio.';
        }

        if ($modelo !== '') {
            if (mb_strlen($modelo) > 255) {
                $errores['modelo'] = 'El modelo no puede tener más de 255 caracteres.';
            }
        } else {
            $errores['modelo'] = 'El nombre del modelo  es obligatorio.';
        }

        if ($serial !== '') {
            if (mb_strlen($serial) > 255) {
                $errores['serial'] = 'El serial no puede tener más de 255 caracteres.';
            }
        } else {
            $errores['serial'] = 'El serial es obligatorio.';
        }

       
        if ($fecha_compra !== '') {
            if (!(validarFecha($fecha_compra))) {
                $errores['fecha_compra'] = 'La fecha proporcionada no es valida';
            }
        } else {
            $errores['fecha_pcompra'] = 'La fecha es obligatoria';
        }

        if ($lugar_compra !== '') {
            if (mb_strlen($lugar_compra) > 255) {
                $errores['lugar_compra'] = 'El lugar de compra no puede tener más de 255 caracteres.';
            }
        } else {
            $errores['lugar_compra'] = 'El lugar de la compra es obligatorio.';
        }

        if (isset($args['cliente'])) {
            if ($cliente === '') {
                $errores['cliente'] = 'El cliente es obligatorio.';
            } elseif (!ctype_digit($cliente)) {
                $errores['cliente'] = 'El cliente no tiene el formato correcto.';
            } else {
                $sent = $pdo->prepare('SELECT COUNT(*)
                                         FROM clientes
                                        WHERE id = :id');
                $sent->execute(['id' => $cliente]);
                if ($sent->fetchColumn() === 0) {
                    $errores['cliente'] = 'El cliente no existe.';
                }
            }
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

    function mostrarPaginador($pag,$npags) {
        ?>
        <div class="container">
            <div class="row">
                <div class="col-6 offset-3" >
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= ($pag <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?pag=<?= $pag - 1 ?>">Anterior</a>
                            </li>
                            <?php for ($i = 1; $i <= $npags; $i++): ?>
                                <li class="page-item <?= ($i == $pag) ? 'active' : '' ?>">
                                    <a class="page-link" href="?pag=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor ?>
                                <li class="page-item <?= ($pag >= $npags) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?pag=<?= $pag + 1 ?>">Siguiente</a>
                                </li>
                        </li>
                        </ul>
                    </nav>
                </div>
                
            </div>
        </div>
        <?php

    }

    function recogerNumPag()
    {
        if (isset($_GET['pag']) && ctype_digit($_GET['pag'])) {
            $pag = trim($_GET['pag']);
            unset($_GET['pag']);
        } else {
            $pag = 1;
        }
        
        return $pag;
    }

    function contarFilas($pdo)
    {
        $sent = $pdo->prepare('SELECT count(*)
                                FROM clines l
                                JOIN (SELECT id AS idcliente, nombre
                                        FROM clientes) c
                                        ON l.cliente_id = c.idcliente');
        $sent->execute();
        $count = $sent->fetchColumn();
        return $count;
    }

    function formRegistrar($parametros, $errores) 
    {
        ?>
          <div class="container">
              <div class="row">
                  <div class="col-6 offset-3 borde-sombreado p-5 mt-5 " style="box-shadow: 2px 2px 10px #666;">
                  <h2>Formulario de registro</h2><hr>
                  <form action="" method="post">


                        <div class="form-group">
                            <label>Usuario</label>
                            <input type="text" 
                                   class="form-control <?=esValido('nick', $errores)?>"  
                                   name="nick" 
                                   value="<?=$parametros['nick']?>"
                            >
                            <?=mensajeError('nick',$errores)?> 
                        </div>


                        <div class="form-group">
                            <label>Contraseña</label>
                            <div class="input-group-append">
                                <input 
                                    type="password" 
                                    class="form-control <?=esValido('password',$errores)?> " 
                                    name="password" 
                                    value="<?=$parametros['password']?>"
                                >
                                <?=mensajeError('password',$errores)?> 
                                    <button id="showpassword" class="btn btn-primary" type="button">
                                    <i class="material-icons">remove_red_eye</i>
                                    </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label> Repetir contraseña</label>
                            <div class="input-group-append">
                            <input 
                                type="password" 
                                class="form-control <?=esValido('password_confirm',$errores)?>" 
                                name="password_confirm" 
                            >
                            <?=mensajeError('password_confirm',$errores)?> 
                                <button id="showpassword" class="btn btn-primary" type="button">
                                <i class="material-icons">remove_red_eye</i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input 
                                type="email" 
                                class="form-control <?=esValido('email',$errores)?>" 
                                name="email" 
                                aria-describedby="emailHelp"
                           >
                           <?=mensajeError('email',$errores)?> 
                            
                        </div>
                        
                        <button type="submit" class="btn btn-primary active">Registrar</button>
                    </form> 
                  </div>
                  
              </div>
          </div>  
        <?php
    }

    function comprobarValoresRegistrar(&$args, $pdo, &$errores)
        {
            if (!empty($errores) || empty($_POST)) {
                return;
            }
            extract($args);
            if (isset($args['nick'])) {
                if ($nick === '') {
                    $errores['nick'] = 'El nick de usuario es obligatorio.';
                } elseif (mb_strlen($nick) > 255) {
                    $errores['nick'] = 'El nick de usuario no puede tener más de 255 caracteres.';
                }  else {
                    // Comprobar si el usuario existe
                    $sent = $pdo->prepare('SELECT *
                                             FROM usuarios
                                            WHERE nick = :nick');
                    $sent->execute(['nick' => $nick]);
                    if (($fila = $sent->fetch()) !== false) {
                        $errores['nick'] = 'Ese usuario ya existe.';
                    }
                }
            }
            
            if (isset($args['password'])) {
                if ($password === '') {
                    $errores['password'] = 'La contraseña es obligatoria.';
                }
            }
            if (isset($args['password_confirm'])) {
                if ($password_confirm === '') {
                    $errores['password_confirm'] = 'La confirmación de contraseña es obligatoria.';
                } elseif ($password !== $password_confirm) {
                    $errores['password_confirm'] = 'Las contraseñas no coinciden.';
                }
            }

            if (isset($args['email'])) {
                if ($email !== '' && !filter_var($args['email'], FILTER_VALIDATE_EMAIL)) {
                    $errores['email'] = 'La dirección de e-mail no es válida.';
                }
            }
           
        }

        function h($cadena)
        {
            return htmlspecialchars($cadena, ENT_QUOTES | ENT_SUBSTITUTE);
        }

        function tokenValido($_csrf)
        {
            if ($_csrf !== null) {
                return $_csrf === $_SESSION['token'];
            }
            return false;
        }

        function token_csrf()
        {
            if (isset($_SESSION['token'])) {
                $token = $_SESSION['token'];
                return <<<EOT
                    <input type="hidden" name="_csrf" value="$token">
                EOT;
            }
        }

        function getMediaPuerto() {
            $pdo = conectar();

            $sent = $pdo->prepare('SELECT  round(avg(puerto),0) as media_puerto
                                      FROM clines');
            $sent->execute();
            $resultado = $sent->fetch(PDO::FETCH_ASSOC);
            return $resultado['media_puerto'];

        }



?>
