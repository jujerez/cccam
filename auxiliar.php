<?php
    function alert($mensaje, $tipo)
    { 
    ?>
        <div class="<?=$tipo?>">
            <span><?=$mensaje?></span>
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
                        <a class="nav-link" href="#">Inicio <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insertar-cliente.php">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insertar-cline">Clines</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insertar-decodificador">Descodficadores</a>
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
?>
