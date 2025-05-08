function validarFormulario(event) {
    let esValido = true;

    // Obtener los valores de los campos
    const nombres = document.getElementById("nombres").value.trim();
    const apellidos = document.getElementById("apellidos").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const password = document.getElementById("password").value.trim();
    const password2 = document.getElementById("password2").value.trim(); // confirmacion de contraseña
    const fecha = document.getElementById("fecha_nacimiento").value.trim();
    const fechaactual = new Date();

    // Limpiar errores previos
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    // Validación de nombres
    if (nombres === "") {
        document.getElementById("errorNombres").textContent = "Ingrese su nombre.";
        document.getElementById("nombres").classList.add('is-invalid');
        esValido = false;
    }

    // Validación de apellidos
    if (apellidos === "") {
        document.getElementById("errorApellidos").textContent = "Ingrese su apellido.";
        document.getElementById("apellidos").classList.add('is-invalid');
        esValido = false;
    }

    // Validación de correo
    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (correo === "") {
        document.getElementById("errorCorreo").textContent = "Ingrese un correo.";
        document.getElementById("correo").classList.add('is-invalid');
        esValido = false;
    } else if (!regexCorreo.test(correo)) {
        document.getElementById("errorCorreo").textContent = "Formato de correo incorrecto.";
        document.getElementById("correo").classList.add('is-invalid');
        esValido = false;
    }

    // Validación de contraseña1
    if (password === "") {
        document.getElementById("errorPassword").textContent = "Ingrese una contraseña.";
        document.getElementById("password").classList.add('is-invalid');
        esValido = false;
    } else if (password.length < 6) {
        document.getElementById("errorPassword").textContent = "La contraseña debe tener al menos 6 caracteres.";
        document.getElementById("password").classList.add('is-invalid');
        esValido = false;
    } 
        // Solo validar coincidencia de contraseñas
        if (password2 === "") {
            document.getElementById("errorPassword2").textContent = "Confirme su contraseña.";
            document.getElementById("password2").classList.add('is-invalid');
            esValido = false;
        } else if (password !== password2) {
            document.getElementById("errorPassword2").textContent = "Las contraseñas no coinciden.";
            document.getElementById("password2").classList.add('is-invalid');
            esValido = false;
        }
    

    // Validación de fecha de nacimiento
    if (fecha === "") {
        document.getElementById("errorFecha").textContent = "Ingrese una fecha.";
        document.getElementById("fecha_nacimiento").classList.add('is-invalid');
        esValido = false;
    } else {
        const fechaNacimiento = new Date(fecha);
        const anioNacimiento = fechaNacimiento.getFullYear();

        if (fechaNacimiento >= fechaactual) {
            document.getElementById("errorFecha").textContent = "La fecha de nacimiento no puede ser futura.";
            document.getElementById("fecha_nacimiento").classList.add('is-invalid');
            esValido = false;
        } else if (anioNacimiento < 1000) {
            document.getElementById("errorFecha").textContent = "El año de la fecha de nacimiento no puede ser menor a 1000.";
            document.getElementById("fecha_nacimiento").classList.add('is-invalid');
            esValido = false;
        }
    }

    // Si hay errores, no enviar el formulario
    if (!esValido) {
        event.preventDefault();
    }

    return esValido;
}

// Validación en tiempo real
document.addEventListener('DOMContentLoaded', function () {
    const campos = ['nombres', 'apellidos', 'correo', 'password', 'password2', 'fecha_nacimiento'];

    campos.forEach(campo => {
        document.getElementById(campo).addEventListener('blur', function () {
            const event = new Event('submit', { cancelable: true });
            validarFormulario(event);
        });
    });
});