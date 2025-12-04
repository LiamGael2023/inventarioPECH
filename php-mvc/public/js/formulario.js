/**
 * JavaScript para formulario de infraestructuras
 */

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formInfraestructura');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validar campos obligatorios
        if (!validarFormulario()) {
            mostrarMensaje('Por favor complete todos los campos obligatorios', 'error');
            return;
        }

        // Obtener datos del formulario
        const formData = obtenerDatosFormulario();

        // Determinar si es creación o actualización
        const infraId = document.getElementById('infraId').value;
        const url = infraId
            ? `${getBaseUrl()}/api/infraestructuras/${infraId}`
            : `${getBaseUrl()}/api/infraestructuras`;

        const method = infraId ? 'PUT' : 'POST';

        // Enviar datos
        enviarFormulario(url, method, formData);
    });
});

function validarFormulario() {
    const camposRequeridos = ['codigo', 'nombre', 'tipo', 'region', 'provincia', 'distrito'];
    let valido = true;

    camposRequeridos.forEach(campo => {
        const input = document.getElementById(campo);
        if (!input.value.trim()) {
            input.style.borderColor = '#ef4444';
            valido = false;
        } else {
            input.style.borderColor = '#d1d5db';
        }
    });

    return valido;
}

function obtenerDatosFormulario() {
    const form = document.getElementById('formInfraestructura');
    const formData = {};

    // Campos de texto y select
    const campos = [
        'codigo', 'nombre', 'tipo', 'region', 'provincia', 'distrito',
        'coordenada_este', 'coordenada_norte', 'zona_utm', 'altitud',
        'cuenca_hidrografica', 'subcuenca', 'cuerpo_agua',
        'capacidad', 'unidad_capacidad', 'longitud', 'ancho', 'altura',
        'area_influencia', 'material_construccion', 'anio_construccion',
        'titular_propietario', 'operador_actual', 'uso_principal',
        'licencia_agua', 'estado_conservacion', 'estado_operativo',
        'fecha_ultima_inspeccion', 'observaciones'
    ];

    campos.forEach(campo => {
        const elemento = document.getElementById(campo);
        if (elemento && elemento.value) {
            formData[campo] = elemento.value;
        }
    });

    return formData;
}

function enviarFormulario(url, method, data) {
    // Mostrar loading
    const submitBtn = document.querySelector('button[type="submit"]');
    const textoOriginal = submitBtn.textContent;
    submitBtn.textContent = 'Guardando...';
    submitBtn.disabled = true;

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        submitBtn.textContent = textoOriginal;
        submitBtn.disabled = false;

        if (result.success) {
            mostrarMensaje(result.message, 'success');
            setTimeout(() => {
                window.location.href = getBaseUrl() + '/infraestructura';
            }, 1500);
        } else {
            mostrarMensaje(result.error || 'Error al guardar', 'error');
            if (result.errores) {
                console.error('Errores de validación:', result.errores);
            }
        }
    })
    .catch(error => {
        submitBtn.textContent = textoOriginal;
        submitBtn.disabled = false;
        mostrarMensaje('Error al enviar el formulario: ' + error, 'error');
        console.error('Error:', error);
    });
}

function mostrarMensaje(texto, tipo) {
    const mensajeDiv = document.getElementById('mensaje');
    mensajeDiv.textContent = texto;
    mensajeDiv.className = `mensaje ${tipo}`;
    mensajeDiv.style.display = 'block';

    window.scrollTo({ top: 0, behavior: 'smooth' });

    setTimeout(() => {
        mensajeDiv.style.display = 'none';
    }, 5000);
}

function getBaseUrl() {
    // Obtener la URL base desde la configuración global o del DOM
    const link = document.querySelector('link[rel="stylesheet"]');
    if (link) {
        const href = link.getAttribute('href');
        const match = href.match(/^(https?:\/\/[^\/]+\/[^\/]+)/);
        if (match) {
            return match[1];
        }
    }
    // Fallback
    return window.location.origin;
}
