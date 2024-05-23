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
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Usuarios de la Plataforma</h2>
                        <a href="agregar_usuario.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Añadir Nuevo Usuario</a>
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
                            echo '<div class="dropdown">';
                            echo '<button class="btn btn-primary dropdown-toggle" onclick="toggleDropdown(event)">Detalles</button>';
                            echo '<div class="dropdown-menu">';
                            echo '<a href="editar_usuario.php?id=' . $usuario['id'] . '" class="dropdown-item">Editar</a>';
                            echo '<a href="eliminar_usuario.php?id=' . $usuario['id'] . '" class="dropdown-item">Eliminar</a>';
                            echo '</div>';
                            echo '</div>';
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