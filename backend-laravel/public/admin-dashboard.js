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
            document.getElementById('total-mascotas').textContent = datos.mascotas_total || 0;
            document.getElementById('clientes-activos').textContent = datos.clientes_activos || 0;
            document.getElementById('veterinarios-total').textContent = datos.veterinarios || 0;
        }

        // Cargar citas próximas
        const respCitas = await fetch('http://127.0.0.1:8000/api/admin/citas', {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (respCitas.ok) {
            const response = await respCitas.json();
            const citas = response.data || response;
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

    if (!citas || citas.length === 0) {
        contenedor.innerHTML = '<p class="text-slate-400 text-center py-8">No hay citas próximas</p>';
        return;
    }

    citas.slice(0, 5).forEach(cita => {
        const citaDiv = document.createElement('div');
        citaDiv.className = 'bg-slate-800 border-l-4 border-emerald-500 p-4 rounded flex justify-between items-center';
        
        const mascotaNombre = cita.mascota?.nombre || 'Mascota';
        const clienteNombre = cita.user?.name || 'Cliente';
        const fecha = new Date(cita.fecha).toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
        const estado = cita.status || 'Pendiente';
        
        citaDiv.innerHTML = `
            <div>
                <p class="font-bold text-emerald-300">${mascotaNombre} - ${clienteNombre}</p>
                <p class="text-sm text-slate-400">${cita.servicio} | ${fecha} ${cita.hora}</p>
            </div>
            <span class="text-sm px-3 py-1 rounded bg-emerald-900 text-emerald-300">${estado}</span>
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
