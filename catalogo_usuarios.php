<?php
session_start(); // Inicia la sesión al principio del archivo

// Verifica si no hay un usuario en la sesión o si no es admin
if(!isset($_SESSION['usuario']) || ($_SESSION['esAdmin'] != 1)) {
    header("Location: login.html");
    exit();
}

// Resto de tu código aquí
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usuarios de la Plataforma</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 800px;
            margin: 0 auto;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            z-index: 1000;
        }
        .show-dropdown .dropdown-menu {
            display: block;
        }
        .dropdown.show-dropdown {
            position: relative;
        }
        .dropdown.show-dropdown .dropdown-menu {
            left: 0;
            top: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Acciones</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <?php
                if($_SESSION['esAdmin'] && $_SESSION['esAdmin'] == 1){
                   echo '<a class="nav-link active" aria-current="page" href="catalogo_usuarios.php">CRUD</a>';
                   echo '</li> <li class="nav-item">';
                    echo '<a class="nav-link active" aria-current="page" href="subida.html">Subida de reticulas</a>';

                }
            ?>

        </li>
        <li class="nav-item">
          <a class="nav-link active" href="ver_reticulas.php">Ver reticulas</a>
        </li>
            <li>
                <a class="nav-link active" href="logout.php">Cerrar Sesion</a>
            </li>

          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
    <div class="wrapper">
        
        <div class="container-fluid">
            <div class="row">
                
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">

                        
                        <h2 class="pull-left">Usuarios de la Plataforma</h2>
                        <?php if ($_SESSION['esAdmin']): ?>
                            <a href="agregar_usuario.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Añadir Nuevo Usuario</a>
                        <?php endif; ?>
                    </div>
                    <?php
                    require_once 'conexion.php';
                    $usuarios = obtenerUsuarios();
                    if ($usuarios) {
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>#</th>";
                        echo "<th>Nombre</th>";
                        echo "<th>Correo</th>";
                        echo "<th>Perfil</th>";
                        echo "<th>Acciones</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        foreach ($usuarios as $usuario) {
                            echo "<tr>";
                            echo "<td>" . $usuario['id'] . "</td>";
                            echo "<td>" . $usuario['nombre'] . "</td>";
                            echo "<td>" . $usuario['correo'] . "</td>";
                            echo "<td>" . $usuario['tipoPerfil'] . "</td>";
                            echo "<td>";
                            if ($_SESSION['esAdmin']) {
                                echo '<div class="dropdown">';
                                echo '<button class="btn btn-primary dropdown-toggle" onclick="toggleDropdown(event)">Detalles</button>';
                                echo '<div class="dropdown-menu">';
                                echo '<a href="editar_usuario.php?id=' . $usuario['id'] . '" class="dropdown-item">Editar</a>';
                                echo '<a href="eliminar_usuario.php?id=' . $usuario['id'] . '" class="dropdown-item">Eliminar</a>';
                                echo '</div>';
                                echo '</div>';
                            } else {
                                echo 'No permitido';
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo '<div class="alert alert-danger"><em>No se encontraron registros.</em></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown(event) {
            var button = event.target;
            var dropdown = button.closest('.dropdown');
            var allDropdowns = document.querySelectorAll('.dropdown');
            
            allDropdowns.forEach(function (dd) {
                if (dd !== dropdown) {
                    dd.classList.remove('show-dropdown');
                }
            });

            dropdown.classList.toggle('show-dropdown');
            event.stopPropagation();
        }

        document.addEventListener('click', function () {
            document.querySelectorAll('.dropdown').forEach(function (dropdown) {
                dropdown.classList.remove('show-dropdown');
            });
        }, false);

        document.querySelectorAll('.dropdown').forEach(function (dropdown) {
            dropdown.addEventListener('click', function (event) {
                event.stopPropagation(); 
            }, false);
        });
    </script>
</body>
</html>
