$(document).ready(function () {
    //modo edicion se habilita si el usuario le da click
    let modoEdicion = false;
    let usuarioEditandoId = null;

    // Cargar lista de usuarios al inicio
    cargarUsuarios();

    // Manejar envío del formulario
    $('#formRegistro').on('submit', function (e) {
        e.preventDefault();

        if (validarFormulario(e)) {
            const formData = {
                action: modoEdicion ? 'actualizar' : 'registrar',
                nombres: $('#nombres').val().trim(),
                apellidos: $('#apellidos').val().trim(),
                correo: $('#correo').val().trim(),
                password: $('#password').val(),
                fecha_nacimiento: $('#fecha_nacimiento').val()
            };

            if (modoEdicion) {
                formData.id = usuarioEditandoId;
            }

            $.ajax({
                url: 'usuario_ajax.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        $('#formRegistro')[0].reset();
                        cargarUsuarios();
                        cancelarEdicion();
                    } else {
                        if (response.errors) {
                            // Mostrar errores del servidor
                            for (const campo in response.errors) {
                                $(`#${campo}`).addClass('is-invalid');
                                $(`#error${campo.charAt(0).toUpperCase() + campo.slice(1)}`).text(response.errors[campo]);
                            }
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    alert('Error en la solicitud');
                }
            });
        }
    });

    // Manejar edición de usuario
    $(document).on('click', '.btn-editar', function () {
        const id = $(this).data('id');

        $.ajax({
            url: 'usuario_ajax.php',
            type: 'POST',
            data: { action: 'obtener', id: id },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    modoEdicion = true;
                    usuarioEditandoId = id;

                    // Llenar el formulario con los datos del usuario
                    $('#nombres').val(response.usuario.nombres);
                    $('#apellidos').val(response.usuario.apellidos);
                    $('#correo').val(response.usuario.correo);
                    // No llenar el campo de contraseña porque no se puede desencriptar sha256
                    $('#password').val('');
                    $('#fecha_nacimiento').val(response.usuario.fecha_nacimiento);

                    // Cambiar el texto del botón
                    $('#formRegistro button[type="submit"]').text('Actualizar Usuario');

                    // Mostrar botón de cancelar
                    $('#btnCancelar').removeClass('d-none');
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert('Error al obtener usuario');
            }
        });
    });

    // Manejar eliminación de usuario
    $(document).on('click', '.btn-eliminar', function () {
        if (confirm('¿Está seguro de eliminar este usuario?')) {
            const id = $(this).data('id');

            $.ajax({
                url: 'usuario_ajax.php',
                type: 'POST',
                data: { action: 'eliminar', id: id },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        cargarUsuarios();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    alert('Error al eliminar');
                }
            });
        }
    });

    // Función para cancelar edición
    function cancelarEdicion() {
        modoEdicion = false;
        usuarioEditandoId = null;
        $('#formRegistro')[0].reset();
        $('#formRegistro button[type="submit"]').text('Registrar');
        $('#btnCancelar').addClass('d-none');
    }

    // Botón cancelar edición
    $('#btnCancelar').on('click', function (e) {
        e.preventDefault();
        cancelarEdicion();
    });
});

function cargarUsuarios() {
    $.ajax({
        url: 'usuario_ajax.php?action=listar',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                const tbody = $('#tablaUsuarios tbody');
                tbody.empty();

                response.usuarios.forEach(usuario => {
                    tbody.append(`
                        <tr>
                            <td>${usuario.idusuarios}</td>
                            <td>${usuario.nombres}</td>
                            <td>${usuario.apellidos}</td>
                            <td>${usuario.correo}</td>
                            <td>${usuario.fecha_nacimiento}</td>
                            <td>
                                <button class="btn btn-primary btn-sm btn-editar me-2" data-id="${usuario.idusuarios}">Editar</button>
                                <button class="btn btn-danger btn-sm btn-eliminar" data-id="${usuario.idusuarios}">Eliminar</button>
                            </td>
                        </tr>
                    `);
                });
            }
        },
        error: function (xhr, status, error) {
            console.error(error);
            alert('Error al cargar usuarios');
        }
    });
}