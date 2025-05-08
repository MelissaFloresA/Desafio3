<?php
require_once 'usuario_model.php';

// Controlador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $model = new UsuarioModel();
    $errores = [];

    // Validaciones básicas del servidor (las principales están en usuario_ajax.php)
    if (empty($_POST['nombres']))
        $errores['nombres'] = 'Nombre requerido';
    if (empty($_POST['apellidos']))
        $errores['apellidos'] = 'Apellido requerido';

    if (empty($errores)) {
        $exito = $model->crearUsuario(
            $_POST['nombres'],
            $_POST['apellidos'],
            $_POST['correo'],
            $_POST['password'],
            $_POST['fecha_nacimiento']
        );

        if ($exito) {
            $mensaje = 'Usuario registrado exitosamente';
        } else {
            $errores['general'] = 'Error al registrar usuario';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="text-center">Registro de Usuarios</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($mensaje)): ?>
                            <div class="alert alert-success"><?= $mensaje ?></div>
                        <?php endif; ?>

                        <form id="formRegistro" method="POST">
                            <div class="mb-3">
                                <label for="nombres" class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                                <div class="invalid-feedback" id="errorNombres"></div>
                            </div>

                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                                <div class="invalid-feedback" id="errorApellidos"></div>
                            </div>

                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo" required>
                                <div class="invalid-feedback" id="errorCorreo"></div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback" id="errorPassword"></div>
                            </div>

                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                    required>
                                <div class="invalid-feedback" id="errorFecha"></div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                                
                                <!--para edicion si se quiere cancelar none para que no se mire si no esta editando-->
                                <button type="button" id="btnCancelar" class="btn btn-secondary d-none">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h4 class="text-center">Usuarios Registrados</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tablaUsuarios">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombres</th>
                                        <th>Apellidos</th>
                                        <th>Correo</th>
                                        <th>Fecha Nacimiento</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los usuarios se cargarán aquí via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/validaciones.js"></script>
    <script src="js/registrousuario.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>