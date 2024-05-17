<?php
require_once 'conexion.php';

$usuarios = obtenerUsuarios();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Usuarios Registrados</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Usuarios de la plataforma</h2>
                    </div>
                    <?php
                    if (count($usuarios) > 0) {
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>#</th>";
                        echo "<th>Usuario</th>";
                        echo "<th>Nombre</th>";
                        echo "<th>Correo</th>";
                        echo "<th>Tipo de Perfil</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        foreach ($usuarios as $usuario) {
                            echo "<tr>";
                            echo "<td>" . $usuario['id'] . "</td>";
                            echo "<td>" . $usuario['usuario'] . "</td>";
                            echo "<td>" . $usuario['nombre'] . "</td>";
                            echo "<td>" . $usuario['correo'] . "</td>";
                            echo "<td>" . $usuario['tipoPerfil'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                    } else {
                        echo '<div class="alert alert-danger"><em>No se encontraron usuarios.</em></div>';
                    }
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
