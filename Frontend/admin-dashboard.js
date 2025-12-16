// Cargar datos del dashboard
async function cargarDatos() {
    try {
        const token = localStorage.getItem('authToken');
        
        // Cargar estadísticas
        const respEstadisticas = await fetch('http://127.0.0.1:8000/api/admin/estadisticas', {
            headers: { 'Authorization': 'Bearer ' + token }
        });
        
        if (respEstadisticas.ok) {
            const datos = await respEstadisticas.json();
            document.getElementById('citas-hoy').textContent = datos.citas_hoy || 0;
            document.getElementById('total-mascotas').textContent = datos.total_mascotas || 0;
            document.getElementById('clientes-activos').textContent = datos.clientes_activos || 0;
            document.getElementById('veterinarios-total').textContent = datos.veterinarios || 0;
        }

        // Cargar citas próximas
        const respCitas = await fetch('http://127.0.0.1:8000/api/admin/citas-proximas', {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (respCitas.ok) {
            const citas = await respCitas.json();
            mostrarCitasProximas(citas);
        }
    } catch (error) {
        console.error('Error al cargar datos:', error);
    }
}

// Mostrar citas próximas
function mostrarCitasProximas(citas) {
    const contenedor = document.getElementById('citas-proximas');
    contenedor.innerHTML = '';

    if (citas.length === 0) {
        contenedor.innerHTML = '<p class="text-slate-400 text-center py-8">No hay citas próximas</p>';
        return;
    }

    citas.forEach(cita => {
        const citaDiv = document.createElement('div');
        citaDiv.className = 'bg-slate-800 border-l-4 border-emerald-500 p-4 rounded flex justify-between items-center';
        citaDiv.innerHTML = `
            <div>
                <p class="font-bold text-emerald-300">${cita.mascota_nombre || 'Mascota'} - ${cita.cliente_nombre || 'Cliente'}</p>
                <p class="text-sm text-slate-400">${cita.servicio} | ${formatearFecha(cita.fecha)} ${cita.hora}</p>
            </div>
            <span class="text-sm px-3 py-1 rounded bg-emerald-900 text-emerald-300">${cita.estado || 'Pendiente'}</span>
        `;
        contenedor.appendChild(citaDiv);
    });
}

// Formatear fecha
function formatearFecha(fecha) {
    const date = new Date(fecha + 'T00:00:00');
    return date.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
}

// Inicializar
document.addEventListener('DOMContentLoaded', cargarDatos);
