const mascotaSelectHistorial = document.getElementById('mascota-select');
const historialContenedor = document.getElementById('historial-contenedor');
const mascotaInfo = document.getElementById('mascota-info');
const buscarInput = document.getElementById('buscar-historial');
const filtroTipo = document.getElementById('filtro-tipo');

let mascotaActual = null;
let historialCompleto = [];

// Cargar mascotas
async function cargarMascotasHistorial() {
    try {
        const token = localStorage.getItem('authToken');
        const respuesta = await fetch("http://127.0.0.1:8000/api/mascotas", {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            const mascotas = await respuesta.json();
            mascotaSelectHistorial.innerHTML = '<option value="">Selecciona una mascota</option>';
            mascotas.forEach(mascota => {
                const option = document.createElement('option');
                option.value = mascota.id;
                option.textContent = mascota.nombre;
                mascotaSelectHistorial.appendChild(option);
            });

            // Si hay parámetro en URL, cargar esa mascota
            const params = new URLSearchParams(window.location.search);
            if (params.get('mascota')) {
                mascotaSelectHistorial.value = params.get('mascota');
                cargarHistorial();
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Cargar historial de la mascota seleccionada
async function cargarHistorial() {
    const mascotaId = mascotaSelectHistorial.value;
    
    if (!mascotaId) {
        historialContenedor.innerHTML = '<p class="text-slate-400 text-center py-8">Selecciona una mascota para ver su historial</p>';
        mascotaInfo.classList.add('hidden');
        return;
    }

    try {
        const token = localStorage.getItem('authToken');
        
        // Cargar info de mascota
        const respMascota = await fetch(`http://127.0.0.1:8000/api/mascotas/${mascotaId}`, {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (respMascota.ok) {
            mascotaActual = await respMascota.json();
            mostrarInfoMascota(mascotaActual);
        }

        // Cargar historial médico
        const respHistorial = await fetch(`http://127.0.0.1:8000/api/mascotas/${mascotaId}/historial`, {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (respHistorial.ok) {
            historialCompleto = await respHistorial.json();
            mostrarHistorial(historialCompleto);
        } else {
            historialContenedor.innerHTML = '<p class="text-slate-400 text-center py-8">No hay historial médico para esta mascota</p>';
        }
    } catch (error) {
        console.error('Error:', error);
        historialContenedor.innerHTML = '<p class="text-red-400 text-center py-8">Error al cargar historial</p>';
    }
}

// Mostrar información de mascota
function mostrarInfoMascota(mascota) {
    document.getElementById('info-especie').textContent = mascota.especie;
    document.getElementById('info-raza').textContent = mascota.raza;
    document.getElementById('info-edad').textContent = `${mascota.edad} años`;
    document.getElementById('info-peso').textContent = mascota.peso ? `${mascota.peso} kg` : 'No especificado';
    mascotaInfo.classList.remove('hidden');
}

// Mostrar historial filtrado
function mostrarHistorial(registros) {
    const textoBusqueda = buscarInput.value.toLowerCase();
    const tipoFiltro = filtroTipo.value;

    let filtrados = registros.filter(registro => {
        const cumpleBusqueda = !textoBusqueda || 
            registro.descripcion.toLowerCase().includes(textoBusqueda) ||
            registro.notas?.toLowerCase().includes(textoBusqueda);
        
        const cumpleTipo = !tipoFiltro || registro.tipo === tipoFiltro;
        
        return cumpleBusqueda && cumpleTipo;
    });

    historialContenedor.innerHTML = '';

    if (filtrados.length === 0) {
        historialContenedor.innerHTML = '<p class="text-slate-400 text-center py-8">No hay registros que coincidan con los filtros</p>';
        return;
    }

    // Ordenar por fecha descendente
    filtrados.sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

    filtrados.forEach(registro => {
        const registroDiv = document.createElement('div');
        registroDiv.className = 'bg-slate-800 border-l-4 border-emerald-500 p-6 rounded-lg';
        
        const iconoTipo = getIconoTipo(registro.tipo);
        const fecha = new Date(registro.fecha).toLocaleDateString('es-ES', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });

        registroDiv.innerHTML = `
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">${iconoTipo}</span>
                    <div>
                        <h4 class="font-bold text-emerald-300 text-lg">${registro.tipo}</h4>
                        <p class="text-slate-400 text-sm">${fecha}</p>
                    </div>
                </div>
                ${registro.veterinario ? `<span class="text-sm bg-slate-700 px-3 py-1 rounded text-slate-300">${registro.veterinario}</span>` : ''}
            </div>
            <p class="text-slate-200 mb-2">${registro.descripcion}</p>
            ${registro.resultado ? `<p class="text-slate-300 text-sm mb-2"><span class="font-semibold">Resultado:</span> ${registro.resultado}</p>` : ''}
            ${registro.medicamento ? `<p class="text-slate-300 text-sm mb-2"><span class="font-semibold">Medicamento:</span> ${registro.medicamento}</p>` : ''}
            ${registro.dosis ? `<p class="text-slate-300 text-sm mb-2"><span class="font-semibold">Dosis:</span> ${registro.dosis}</p>` : ''}
            ${registro.notas ? `<p class="text-slate-400 italic text-sm mt-3">📝 ${registro.notas}</p>` : ''}
        `;
        historialContenedor.appendChild(registroDiv);
    });
}

// Obtener ícono según tipo
function getIconoTipo(tipo) {
    const iconos = {
        'Consulta': '🩺',
        'Vacunación': '💉',
        'Cirugía': '🔬',
        'Análisis': '🔍',
        'Medicamento': '💊'
    };
    return iconos[tipo] || '📋';
}

// Event listeners para filtros
mascotaSelectHistorial.addEventListener('change', cargarHistorial);
buscarInput.addEventListener('input', () => mostrarHistorial(historialCompleto));
filtroTipo.addEventListener('change', () => mostrarHistorial(historialCompleto));

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('authToken');
    if (!token) {
        window.location.href = 'login.html';
        return;
    }
    cargarMascotasHistorial();
});
