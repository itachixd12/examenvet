const formCita = document.getElementById('form-cita');
const mascotaSelect = document.getElementById('mascota-select');
const veterinarioSelect = document.getElementById('veterinario-select');
const citasContenedor = document.getElementById('citas-contenedor');
const horaSelect = document.getElementById('hora-cita');
const fechaSelect = document.getElementById('fecha-cita');

// Cargar horarios disponibles para un veterinario en una fecha
async function cargarHorariosDisponibles() {
    try {
        const token = localStorage.getItem('authToken');
        const veterinarioNombre = veterinarioSelect.value;
        const fecha = fechaSelect.value;

        if (!veterinarioNombre || !fecha) {
            horaSelect.innerHTML = '<option value="">Selecciona veterinario y fecha</option>';
            return;
        }

        // Primero obtener el ID del veterinario
        const respuestaVets = await fetch("http://127.0.0.1:8000/api/veterinarios");
        const veterinarios = await respuestaVets.json();
        const veterinario = veterinarios.find(v => v.nombre === veterinarioNombre);
        
        if (!veterinario) {
            horaSelect.innerHTML = '<option value="">Veterinario no encontrado</option>';
            return;
        }

        // Obtener horarios disponibles
        const respuesta = await fetch('http://127.0.0.1:8000/api/horarios-disponibles', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({
                veterinario_id: veterinario.id,
                fecha: fecha
            })
        });

        if (respuesta.ok) {
            const data = await respuesta.json();
            horaSelect.innerHTML = '<option value="">Selecciona una hora</option>';
            
            if (data.horarios_disponibles.length === 0) {
                horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
                return;
            }

            data.horarios_disponibles.forEach(hora => {
                const option = document.createElement('option');
                option.value = hora;
                option.textContent = hora + ' (Disponible)';
                horaSelect.appendChild(option);
            });
        } else {
            horaSelect.innerHTML = '<option value="">Error cargando horarios</option>';
        }
    } catch (error) {
        console.error('Error al cargar horarios disponibles:', error);
        horaSelect.innerHTML = '<option value="">Error cargando horarios</option>';
    }
}

// Cargar veterinarios del sistema
async function cargarVeterinarios() {
    try {
        console.log('Iniciando carga de veterinarios...');
        const respuesta = await fetch("http://127.0.0.1:8000/api/veterinarios");
        console.log('Respuesta de API:', respuesta.status);
        
        if (respuesta.ok) {
            const veterinarios = await respuesta.json();
            console.log('Veterinarios cargados:', veterinarios);
            veterinarioSelect.innerHTML = '<option value="">Sin preferencia</option>';
            veterinarios.forEach(vet => {
                const option = document.createElement('option');
                option.value = vet.nombre;
                option.textContent = vet.nombre;
                veterinarioSelect.appendChild(option);
                console.log('Agregado veterinario:', vet.nombre);
            });
        } else {
            console.error('Error en API, status:', respuesta.status);
        }
    } catch (error) {
        console.error('Error al cargar veterinarios:', error);
    }
}

// Cargar mascotas del usuario para el selector
async function cargarMascotasSelect() {
    try {
        const token = localStorage.getItem('authToken');
        const respuesta = await fetch("http://127.0.0.1:8000/api/mascotas", {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            const mascotas = await respuesta.json();
            mascotaSelect.innerHTML = '<option value="">Selecciona una mascota</option>';
            mascotas.forEach(mascota => {
                const option = document.createElement('option');
                option.value = mascota.id;
                option.textContent = `${mascota.nombre} (${mascota.especie})`;
                mascotaSelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error al cargar mascotas:', error);
    }
}

// Cargar citas del usuario
async function cargarCitas() {
    try {
        const token = localStorage.getItem('authToken');
        const respuesta = await fetch("http://127.0.0.1:8000/api/citas", {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            const citas = await respuesta.json();
            mostrarCitas(citas);
        }
    } catch (error) {
        console.error('Error al cargar citas:', error);
        citasContenedor.innerHTML = '<p class="text-slate-400">Error al cargar citas</p>';
    }
}

// Mostrar citas agendadas
function mostrarCitas(citas) {
    citasContenedor.innerHTML = '';

    if (citas.length === 0) {
        citasContenedor.innerHTML = '<p class="text-slate-400 text-center py-8">No tienes citas agendadas aún</p>';
        return;
    }

    citas.forEach(cita => {
        const estado = obtenerEstadoCita(cita.fecha, cita.hora);
        const citaDiv = document.createElement('div');
        citaDiv.className = 'bg-slate-800 border-l-4 border-emerald-500 p-4 rounded';
        citaDiv.innerHTML = `
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h4 class="font-bold text-emerald-300">${cita.mascota ? cita.mascota.nombre : 'Mascota sin asignar'}</h4>
                    <p class="text-sm text-slate-400">${cita.servicio ? cita.servicio.nombre : 'Sin servicio'}</p>
                </div>
                <span class="px-3 py-1 rounded text-sm font-semibold ${
                    estado === 'Completada' ? 'bg-green-900 text-green-300' :
                    estado === 'Próxima' ? 'bg-blue-900 text-blue-300' :
                    'bg-yellow-900 text-yellow-300'
                }">${estado}</span>
            </div>
            <div class="text-slate-300 text-sm space-y-1">
                <p>📅 ${formatearFecha(cita.fecha)} a las ${cita.hora}</p>
                <p>👨‍⚕️ ${cita.veterinario ? cita.veterinario.nombre : 'Sin veterinario asignado'}</p>
                <p>💬 ${cita.motivo}</p>
            </div>
            <div class="flex gap-2 mt-4">
                <button onclick="editarCita(${cita.id})" class="text-sm px-3 py-1 bg-slate-700 text-white rounded hover:bg-slate-600">
                    Editar
                </button>
                <button onclick="cancelarCita(${cita.id})" class="text-sm px-3 py-1 bg-red-700 text-white rounded hover:bg-red-600">
                    Cancelar
                </button>
            </div>
        `;
        citasContenedor.appendChild(citaDiv);
    });
}

// Obtener estado de la cita
function obtenerEstadoCita(fecha, hora) {
    const ahora = new Date();
    const citaDate = new Date(`${fecha}T${hora}`);
    
    if (citaDate < ahora) {
        return 'Completada';
    } else if (citaDate - ahora <= 7 * 24 * 60 * 60 * 1000) {
        return 'Próxima';
    } else {
        return 'Agendada';
    }
}

// Formatear fecha
function formatearFecha(fecha) {
    const date = new Date(fecha + 'T00:00:00');
    return date.toLocaleDateString('es-ES', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

// Agendar cita
formCita.addEventListener('submit', async function(e) {
    e.preventDefault();

    const token = localStorage.getItem('authToken');
    if (!token) {
        alert('Debes iniciar sesión');
        return;
    }

    const citaData = {
        mascota_id: mascotaSelect.value,
        servicio: document.getElementById('servicio-select').value,
        veterinario: document.getElementById('veterinario-select').value || null,
        fecha: document.getElementById('fecha-cita').value,
        hora: document.getElementById('hora-cita').value,
        motivo: document.getElementById('motivo').value,
        notas: document.getElementById('notas').value || null
    };

    try {
        const respuesta = await fetch('http://127.0.0.1:8000/api/citas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify(citaData)
        });

        if (respuesta.ok) {
            formCita.reset();
            cargarCitas();
            mostrarToast('Cita agendada exitosamente', 'success');
            window.scrollTo(0, document.getElementById('citas-contenedor').offsetTop);
        } else {
            const error = await respuesta.json();
            mostrarToast(error.message || 'Error al agendar cita', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarToast('Error de conexión', 'error');
    }
});

// Cancelar cita
async function cancelarCita(id) {
    if (!confirm('¿Deseas cancelar esta cita?')) {
        return;
    }

    const token = localStorage.getItem('authToken');
    try {
        const respuesta = await fetch(`http://127.0.0.1:8000/api/citas/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            cargarCitas();
            mostrarToast('Cita cancelada', 'success');
        } else {
            mostrarToast('Error al cancelar cita', 'error');
        }
    } catch (error) {
        mostrarToast('Error de conexión', 'error');
    }
}

// Toast notifications
function mostrarToast(mensaje, tipo = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white font-semibold ${
        tipo === 'success' ? 'bg-emerald-600' : tipo === 'error' ? 'bg-red-600' : 'bg-blue-600'
    }`;
    toast.textContent = mensaje;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Inicializar
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('authToken');
    if (!token) {
        window.location.href = 'login.html';
        return;
    }
    cargarVeterinarios();
    cargarMascotasSelect();
    cargarCitas();
    
    // Event listeners para cargar horarios disponibles
    veterinarioSelect.addEventListener('change', cargarHorariosDisponibles);
    fechaSelect.addEventListener('change', cargarHorariosDisponibles);
});
