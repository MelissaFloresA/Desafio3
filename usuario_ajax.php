<?php
require_once 'conexion.php';
require_once 'usuario_model.php';

header('Content-Type: application/json');

$model = new UsuarioModel();
$response = ['success' => false, 'message' => 'Acción no válida'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'registrar':
                $errores = validarDatos($_POST);
                
                if (empty($errores)) {
                    $exito = $model->crearUsuario(
                        $_POST['nombres'],
                        $_POST['apellidos'],
                        $_POST['correo'],
                        $_POST['password'],
                        $_POST['fecha_nacimiento']
                    );
                    
                    if ($exito) {
                        $response = [
                            'success' => true, 
                            'message' => 'Usuario registrado exitosamente',
                            'resetForm' => true
                        ];
                    } else {
                        $response = [
                            'success' => false, 
                            'message' => 'Error al registrar el usuario'
                        ];
                    }
                } else {
                    $response = [
                        'success' => false, 
                        'message' => 'Errores de validación', 
                        'errors' => $errores
                    ];
                }
                break;
                
            case 'actualizar':
                $errores = validarDatos($_POST);
                
                if (empty($errores)) {
                    $exito = $model->actualizarUsuario(
                        $_POST['id'],
                        $_POST['nombres'],
                        $_POST['apellidos'],
                        $_POST['correo'],
                        $_POST['password'],
                        $_POST['fecha_nacimiento']
                    );
                    
                    if ($exito) {
                        $response = [
                            'success' => true, 
                            'message' => 'Usuario actualizado exitosamente'
                        ];
                    } else {
                        $response = [
                            'success' => false, 
                            'message' => 'Error al actualizar el usuario'
                        ];
                    }
                } else {
                    $response = [
                        'success' => false, 
                        'message' => 'Errores de validación', 
                        'errors' => $errores
                    ];
                }
                break;
                
            case 'eliminar':
                if (isset($_POST['id'])) {
                    $exito = $model->eliminarUsuario($_POST['id']);
                    $response = [
                        'success' => $exito, 
                        'message' => $exito ? 'Usuario eliminado' : 'Error al eliminar'
                    ];
                }
                break;
                
            case 'obtener':
                if (isset($_POST['id'])) {
                    $usuario = $model->obtenerUsuarioPorId($_POST['id']);
                    if ($usuario) {
                        $response = [
                            'success' => true, 
                            'usuario' => $usuario
                        ];
                    } else {
                        $response = [
                            'success' => false, 
                            'message' => 'Usuario no encontrado'
                        ];
                    }
                }
                break;
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'listar') {
    $usuarios = $model->obtenerUsuarios();
    $response = ['success' => true, 'usuarios' => $usuarios];
}

echo json_encode($response);

function validarDatos($data) {
    $errores = [];
    
    // Validación de campos obligatorios
    if (empty($data['nombres']) || empty($data['apellidos']) || empty($data['correo']) || 
        empty($data['password']) || empty($data['fecha_nacimiento'])) {
        $errores['general'] = 'Todos los campos son obligatorios.';
        return $errores;
    }

    // Validación de nombres (solo letras y espacios)
    if (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/', $data['nombres'])) {
        $errores['nombres'] = "El campo 'Nombres' solo puede contener letras.";
    }

    // Validación de apellidos (solo letras y espacios)
    if (!preg_match('/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/', $data['apellidos'])) {
        $errores['apellidos'] = "El campo 'Apellidos' solo puede contener letras.";
    }

    // Validación de correo electrónico
    if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "El formato del correo electrónico no es válido.";
    }

    // Validación de contraseña (mínimo 6 caracteres)
    if (strlen($data['password']) < 6) {
        $errores['password'] = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Validación de fecha de nacimiento
    $fechaTimestamp = strtotime($data['fecha_nacimiento']);
    if ($fechaTimestamp >= time()) {
        $errores['fecha_nacimiento'] = "La fecha de nacimiento no puede ser futura.";
    } else {
        $anioNacimiento = date('Y', $fechaTimestamp);
        if ($anioNacimiento < 1000) {
            $errores['fecha_nacimiento'] = "El año de nacimiento no es válido.";
        }
    }

    return $errores;
}
?>