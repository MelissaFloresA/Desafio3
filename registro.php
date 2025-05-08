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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/styles.css">
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
                                <label for="password2" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="password2" name="password2" required>
                                <div class="invalid-feedback" id="errorPassword2"></div>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                    required>
                                <div class="invalid-feedback" id="errorFecha"></div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary" id="btn-registro">Registrar</button>

                                <!--para edicion si se quiere cancelar none para que no se mire si no esta editando-->
                                <button type="button" id="btnCancelar" class="btn d-none noselect">
                                <span class="text">Cancelar</span><span class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path></svg></span>
                                </button>
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
                                    <!-- Los usuarios se cargarán aquí con AJAX -->
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
</body>

</html>