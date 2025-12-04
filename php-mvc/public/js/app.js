/**
 * Funciones generales de la aplicación
 */

// Función para mostrar mensajes
function mostrarMensaje(texto, tipo = 'success') {
    const mensajeDiv = document.createElement('div');
    mensajeDiv.className = `mensaje ${tipo}`;
    mensajeDiv.textContent = texto;

    const container = document.querySelector('.container');
    container.insertBefore(mensajeDiv, container.firstChild);

    setTimeout(() => {
        mensajeDiv.remove();
    }, 5000);

    // Scroll al top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Confirmar eliminación
function confirmarEliminacion(mensaje) {
    return confirm(mensaje);
}

// Validar formulario
function validarCamposObligatorios(formId) {
    const form = document.getElementById(formId);
    const campos = form.querySelectorAll('[required]');
    let valido = true;

    campos.forEach(campo => {
        if (!campo.value.trim()) {
            campo.style.borderColor = '#ef4444';
            valido = false;
        } else {
            campo.style.borderColor = '#d1d5db';
        }
    });

    return valido;
}

// Format number
function formatNumber(num, decimals = 2) {
    return parseFloat(num).toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Evento al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Remover mensajes después de 5 segundos
    const mensajes = document.querySelectorAll('.mensaje');
    mensajes.forEach(mensaje => {
        setTimeout(() => {
            mensaje.style.opacity = '0';
            setTimeout(() => mensaje.remove(), 300);
        }, 5000);
    });
});
