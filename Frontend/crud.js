const token = localStorage.getItem('authToken');

// ============== MASCOTAS ==============

// Cargar mascotas
async function cargarMascotas() {
    try {
        const respuesta = await fetch('http://127.0.0.1:8000/api/admin/mascotas', {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (respuesta.ok) {
            const mascotas = await respuesta.json();
            mostrarTablaMascotas(mascotas);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Mostrar tabla de mascotas
function mostrarTablaMascotas(mascotas) {
    const tbody = document.getElementById('tabla-mascotas');
    tbody.innerHTML = '';

    if (mascotas.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-slate-400">No hay mascotas registradas</td></tr>';
        return;
    }

    mascotas.forEach(mascota => {
        const fila = document.createElement('tr');
        fila.className = 'hover:bg-slate-700 transition';
        fila.innerHTML = `
            <td class="px-6 py-4">${mascota.id}</td>
            <td class="px-6 py-4 font-semibold text-emerald-300">${mascota.nombre}</td>
            <td class="px-6 py-4">${mascota.especie}</td>
            <td class="px-6 py-4">${mascota.raza}</td>
            <td class="px-6 py-4">${mascota.edad} años</td>
            <td class="px-6 py-4">${mascota.peso || 'N/A'} kg</td>
            <td class="px-6 py-4 text-center space-x-2 flex justify-center">
                <button onclick="editarMascota(${mascota.id})" class="text-blue-400 hover:text-blue-300">✏️</button>
                <button onclick="eliminarMascota(${mascota.id})" class="text-red-400 hover:text-red-300">🗑️</button>
            </td>
        `;
        tbody.appendChild(fila);
    });
}

// Crear/Editar mascota
document.getElementById('form-mascota').addEventListener('submit', async (e) => {
    e.preventDefault();

    const mascotaData = {
        nombre: document.getElementById('m-nombre').value,
        especie: document.getElementById('m-especie').value,
        raza: document.getElementById('m-raza').value,
        edad: parseFloat(document.getElementById('m-edad').value),
        peso: document.getElementById('m-peso').value ? parseFloat(document.getElementById('m-peso').value) : null
    };

    try {
        const respuesta = await fetch('http://127.0.0.1:8000/api/admin/mascotas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify(mascotaData)
        });

        if (respuesta.ok) {
            cerrarModalMascota();
            cargarMascotas();
            mostrarToast('Mascota guardada correctamente', 'success');
        } else {
            mostrarToast('Error al guardar mascota', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarToast('Error de conexión', 'error');
    }
});

// Eliminar mascota
async function eliminarMascota(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta mascota?')) return;

    try {
        const respuesta = await fetch(`http://127.0.0.1:8000/api/admin/mascotas/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (respuesta.ok) {
            cargarMascotas();
            mostrarToast('Mascota eliminada', 'success');
        } else {
            mostrarToast('Error al eliminar', 'error');
        }
    } catch (error) {
        mostrarToast('Error de conexión', 'error');
    }
}

// ============== SERVICIOS ==============

// Cargar servicios
async function cargarServicios() {
    try {
        const respuesta = await fetch('http://127.0.0.1:8000/api/admin/servicios', {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (respuesta.ok) {
            const servicios = await respuesta.json();
            mostrarTablaServicios(servicios);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Mostrar tabla de servicios
function mostrarTablaServicios(servicios) {
    const tbody = document.getElementById('tabla-servicios');
    tbody.innerHTML = '';

    if (servicios.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-slate-400">No hay servicios registrados</td></tr>';
        return;
    }

    servicios.forEach(servicio => {
        const fila = document.createElement('tr');
        fila.className = 'hover:bg-slate-700 transition';
        fila.innerHTML = `
            <td class="px-6 py-4">${servicio.id}</td>
            <td class="px-6 py-4 font-semibold text-emerald-300">${servicio.nombre}</td>
            <td class="px-6 py-4 text-sm">${servicio.descripcion}</td>
            <td class="px-6 py-4 text-green-400">$${parseFloat(servicio.precio).toFixed(2)}</td>
            <td class="px-6 py-4">${servicio.duracion} min</td>
            <td class="px-6 py-4 text-center space-x-2 flex justify-center">
                <button onclick="editarServicio(${servicio.id})" class="text-blue-400 hover:text-blue-300">✏️</button>
                <button onclick="eliminarServicio(${servicio.id})" class="text-red-400 hover:text-red-300">🗑️</button>
            </td>
        `;
        tbody.appendChild(fila);
    });
}

// Crear/Editar servicio
document.getElementById('form-servicio').addEventListener('submit', async (e) => {
    e.preventDefault();

    const servicioData = {
        nombre: document.getElementById('s-nombre').value,
        descripcion: document.getElementById('s-descripcion').value,
        precio: parseFloat(document.getElementById('s-precio').value),
        duracion: parseInt(document.getElementById('s-duracion').value)
    };

    try {
        const respuesta = await fetch('http://127.0.0.1:8000/api/admin/servicios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify(servicioData)
        });

        if (respuesta.ok) {
            cerrarModalServicio();
            cargarServicios();
            mostrarToast('Servicio guardado correctamente', 'success');
        } else {
            mostrarToast('Error al guardar servicio', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarToast('Error de conexión', 'error');
    }
});

// Eliminar servicio
async function eliminarServicio(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este servicio?')) return;

    try {
        const respuesta = await fetch(`http://127.0.0.1:8000/api/admin/servicios/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (respuesta.ok) {
            cargarServicios();
            mostrarToast('Servicio eliminado', 'success');
        } else {
            mostrarToast('Error al eliminar', 'error');
        }
    } catch (error) {
        mostrarToast('Error de conexión', 'error');
    }
}

// ============== VETERINARIOS ==============

// Cargar veterinarios
async function cargarVeterinarios() {
    try {
        const respuesta = await fetch('http://127.0.0.1:8000/api/admin/veterinarios', {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (respuesta.ok) {
            const veterinarios = await respuesta.json();
            mostrarTablaVeterinarios(veterinarios);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Mostrar tabla de veterinarios
function mostrarTablaVeterinarios(veterinarios) {
    const tbody = document.getElementById('tabla-veterinarios');
    tbody.innerHTML = '';

    if (veterinarios.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-slate-400">No hay veterinarios registrados</td></tr>';
        return;
    }

    veterinarios.forEach(vet => {
        const fila = document.createElement('tr');
        fila.className = 'hover:bg-slate-700 transition';
        fila.innerHTML = `
            <td class="px-6 py-4">${vet.id}</td>
            <td class="px-6 py-4 font-semibold text-emerald-300">${vet.nombre}</td>
            <td class="px-6 py-4">${vet.email}</td>
            <td class="px-6 py-4">${vet.telefono || 'N/A'}</td>
            <td class="px-6 py-4">${vet.especialidad || 'General'}</td>
            <td class="px-6 py-4 text-center space-x-2 flex justify-center">
                <button onclick="editarVeterinario(${vet.id})" class="text-blue-400 hover:text-blue-300">✏️</button>
                <button onclick="eliminarVeterinario(${vet.id})" class="text-red-400 hover:text-red-300">🗑️</button>
            </td>
        `;
        tbody.appendChild(fila);
    });
}

// Crear/Editar veterinario
document.getElementById('form-veterinario').addEventListener('submit', async (e) => {
    e.preventDefault();

    const vetData = {
        nombre: document.getElementById('v-nombre').value,
        email: document.getElementById('v-email').value,
        telefono: document.getElementById('v-telefono').value || null,
        especialidad: document.getElementById('v-especialidad').value || null
    };

    try {
        const respuesta = await fetch('http://127.0.0.1:8000/api/admin/veterinarios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify(vetData)
        });

        if (respuesta.ok) {
            cerrarModalVeterinario();
            cargarVeterinarios();
            mostrarToast('Veterinario guardado correctamente', 'success');
        } else {
            mostrarToast('Error al guardar veterinario', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarToast('Error de conexión', 'error');
    }
});

// Eliminar veterinario
async function eliminarVeterinario(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar este veterinario?')) return;

    try {
        const respuesta = await fetch(`http://127.0.0.1:8000/api/admin/veterinarios/${id}`, {
            method: 'DELETE',
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (respuesta.ok) {
            cargarVeterinarios();
            mostrarToast('Veterinario eliminado', 'success');
        } else {
            mostrarToast('Error al eliminar', 'error');
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

// Placeholder para editar
function editarMascota(id) {
    mostrarToast('Funcionalidad de edición próximamente', 'info');
}

function editarServicio(id) {
    mostrarToast('Funcionalidad de edición próximamente', 'info');
}

function editarVeterinario(id) {
    mostrarToast('Funcionalidad de edición próximamente', 'info');
}

// Inicializar
document.addEventListener('DOMContentLoaded', () => {
    cargarMascotas();
    cargarServicios();
    cargarVeterinarios();
});
