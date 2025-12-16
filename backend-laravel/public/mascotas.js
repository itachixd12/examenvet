// Funciones globales
function abrirModalMascota() {
    document.getElementById('modal-agregar')?.classList.remove('hidden');
}

function cerrarModalMascota() {
    document.getElementById('modal-agregar')?.classList.add('hidden');
    document.getElementById('form-mascota')?.reset();
    const fotoField = document.getElementById('foto');
    if (fotoField) fotoField.value = '';
    const fotoArchivo = document.getElementById('foto-archivo');
    if (fotoArchivo) fotoArchivo.value = '';
    const nombreArchivo = document.getElementById('nombre-archivo');
    if (nombreArchivo) nombreArchivo.textContent = '';
}

function editarMascota(id) {
    alert('Funcionalidad en desarrollo');
}

async function eliminarMascota(id) {
    if (!confirm('¿Estás seguro?')) {
        return;
    }

    const token = localStorage.getItem('authToken');
    try {
        const respuesta = await fetch(`http://127.0.0.1:8000/api/mascotas/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });

        if (respuesta.ok) {
            mostrarToast('Eliminado', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarToast('Error', 'error');
        }
    } catch (error) {
        mostrarToast('Error de conexión', 'error');
    }
}

function mostrarToast(mensaje, tipo = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded text-white font-semibold z-50 ${
        tipo === 'success' ? 'bg-emerald-600' : tipo === 'error' ? 'bg-red-600' : 'bg-blue-600'
    }`;
    toast.textContent = mensaje;
    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const contenedorMascotas = document.getElementById("mascotas-contenedor");
    const formMascota = document.getElementById("form-mascota");
    const inputFotoArchivo = document.getElementById('foto-archivo');
    const btnFoto = document.getElementById('btn-foto');
    const inputFotoHidden = document.getElementById('foto');
    const estadoCarga = document.getElementById('estado-carga');
    const nombreArchivo = document.getElementById('nombre-archivo');

    // Cargar mascotas
    async function cargarMascotas() {
        try {
            const token = localStorage.getItem('authToken');
            if (!token) {
                window.location.href = 'login.html';
                return;
            }

            const respuesta = await fetch("http://127.0.0.1:8000/api/mascotas", {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });

            if (!respuesta.ok) throw new Error("Error");
            const data = await respuesta.json();
            const mascotas = Array.isArray(data) ? data : (data.data || []);
            mostrarMascotas(mascotas);
        } catch (error) {
            console.error(error);
            if (contenedorMascotas) {
                contenedorMascotas.innerHTML = "<p class='text-red-400 text-center col-span-full'>Error al cargar</p>";
            }
        }
    }

    // Mostrar mascotas
    function mostrarMascotas(mascotas) {
        if (!contenedorMascotas) return;
        contenedorMascotas.innerHTML = "";
        
        if (!mascotas || mascotas.length === 0) {
            contenedorMascotas.innerHTML = `<div class="col-span-full text-center py-12"><p class="text-slate-400">No hay mascotas</p></div>`;
        } else {
            mascotas.forEach((mascota) => {
                const div = document.createElement("div");
                div.className = "bg-slate-900/80 border border-emerald-600 rounded-lg shadow-lg p-6";
                div.innerHTML = `
                    <div class="text-center mb-4">
                        ${mascota.foto ? `<img src="${mascota.foto}" alt="${mascota.nombre}" class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-emerald-600">` : `<div class="w-32 h-32 bg-slate-800 rounded-full mx-auto flex items-center justify-center text-4xl border-4 border-emerald-600">🐾</div>`}
                    </div>
                    <h3 class="text-2xl font-bold text-emerald-300 mb-2 text-center">${mascota.nombre}</h3>
                    <div class="space-y-1 text-slate-300 mb-4">
                        <p><span class="font-semibold text-emerald-400">Usuario:</span> ${mascota.user?.nombre || 'N/A'}</p>
                        <p><span class="font-semibold text-emerald-400">Especie:</span> ${mascota.especie}</p>
                        <p><span class="font-semibold text-emerald-400">Raza:</span> ${mascota.raza}</p>
                        <p><span class="font-semibold text-emerald-400">Edad:</span> ${mascota.edad} años</p>
                        ${mascota.peso ? `<p><span class="font-semibold text-emerald-400">Peso:</span> ${mascota.peso} kg</p>` : ''}
                    </div>
                    <div class="flex gap-2">
                        <button onclick="editarMascota(${mascota.id})" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded hover:bg-slate-600">Editar</button>
                        <button onclick="eliminarMascota(${mascota.id})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
                    </div>
                `;
                contenedorMascotas.appendChild(div);
            });
        }
    }

    // Botón foto
    if (btnFoto) {
        btnFoto.addEventListener('click', (e) => {
            e.preventDefault();
            inputFotoArchivo?.click();
        });
    }

    // Manejar archivo
    if (inputFotoArchivo) {
        inputFotoArchivo.addEventListener('change', async (e) => {
            const archivo = e.target.files[0];
            
            if (!archivo) {
                if (nombreArchivo) nombreArchivo.textContent = '';
                if (inputFotoHidden) inputFotoHidden.value = '';
                return;
            }

            if (!archivo.type.startsWith('image/')) {
                alert('Solo imágenes');
                inputFotoArchivo.value = '';
                return;
            }

            if (estadoCarga) estadoCarga.classList.remove('hidden');
            if (nombreArchivo) nombreArchivo.textContent = '⏳ Subiendo...';

            try {
                const urlFirebase = await subirImagenFirebase(archivo, 'mascotas');
                if (inputFotoHidden) inputFotoHidden.value = urlFirebase;
                if (nombreArchivo) {
                    nombreArchivo.textContent = '✓ Listo';
                    nombreArchivo.classList.add('text-emerald-400');
                    setTimeout(() => {
                        nombreArchivo.classList.remove('text-emerald-400');
                        nombreArchivo.textContent = `${archivo.name}`;
                    }, 2000);
                }
            } catch (error) {
                console.error(error);
                if (nombreArchivo) {
                    nombreArchivo.textContent = '✗ Error: ' + error.message;
                    nombreArchivo.classList.add('text-red-400');
                }
                inputFotoArchivo.value = '';
                if (inputFotoHidden) inputFotoHidden.value = '';
            } finally {
                if (estadoCarga) estadoCarga.classList.add('hidden');
            }
        });
    }

    // Agregar nueva mascota
    if (formMascota) {
        formMascota.addEventListener("submit", async function(e) {
            e.preventDefault();

            const token = localStorage.getItem('authToken');
            if (!token) {
                alert('Debes iniciar sesión');
                return;
            }

            const mascotaData = {
                nombre: document.getElementById('nombre').value,
                especie: document.getElementById('especie').value,
                raza: document.getElementById('raza').value,
                edad: parseFloat(document.getElementById('edad').value),
                peso: document.getElementById('peso').value ? parseFloat(document.getElementById('peso').value) : null,
                foto: inputFotoHidden.value || null,
                notas: document.getElementById('notas').value || null
            };

            try {
                const respuesta = await fetch('http://127.0.0.1:8000/api/mascotas', {
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
                    mostrarToast('Mascota agregada exitosamente', 'success');
                } else {
                    const error = await respuesta.json();
                    mostrarToast(error.message || 'Error al agregar mascota', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarToast('Error de conexión', 'error');
            }
        });
    }

    // Cargar al iniciar
    cargarMascotas();
    
    const btnAgregar = document.getElementById('btn-agregar-mascota');
    if (btnAgregar) {
        btnAgregar.addEventListener('click', abrirModalMascota);
    }
});
