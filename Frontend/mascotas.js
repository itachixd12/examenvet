// Función para subir imagen a Firebase (ya definida en firebase-config.js)
// solo para referencia

// Funciones globales
function abrirModalMascota() {
    document.getElementById('modal-agregar').classList.remove('hidden');
}

function cerrarModalMascota() {
    document.getElementById('modal-agregar').classList.add('hidden');
    document.getElementById('form-mascota').reset();
    document.getElementById('foto').value = '';
    document.getElementById('foto-archivo').value = '';
    document.getElementById('nombre-archivo').textContent = '';
}

function editarMascota(id) {
    alert('Funcionalidad en desarrollo');
}

async function eliminarMascota(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta mascota?')) {
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
            mostrarToast('Mascota eliminada', 'success');
            location.reload();
        } else {
            mostrarToast('Error al eliminar', 'error');
        }
    } catch (error) {
        mostrarToast('Error de conexión', 'error');
    }
}

function mostrarToast(mensaje, tipo = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white font-semibold z-50 ${
        tipo === 'success' ? 'bg-emerald-600' : tipo === 'error' ? 'bg-red-600' : 'bg-blue-600'
    }`;
    toast.textContent = mensaje;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar variables del DOM
    const contenedorMascotas = document.getElementById("mascotas-contenedor");
    const formMascota = document.getElementById("form-mascota");
    const inputFotoArchivo = document.getElementById('foto-archivo');
    const btnFoto = document.getElementById('btn-foto');
    const inputFotoHidden = document.getElementById('foto');
    const estadoCarga = document.getElementById('estado-carga');
    const nombreArchivo = document.getElementById('nombre-archivo');

    // Función para cargar mascotas
    async function cargarMascotas() {
        try {
            const token = localStorage.getItem('authToken');
            if (!token) {
                window.location.href = 'login.html';
                return;
            }

            console.log('Token:', token);
            const respuesta = await fetch("http://127.0.0.1:8000/api/mascotas", {
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                }
            });

            if (!respuesta.ok) {
                const errorText = await respuesta.text();
                console.error('Response status:', respuesta.status);
                console.error('Response:', errorText);
                throw new Error("Error " + respuesta.status);
            }

            const mascotas = await respuesta.json();
            mostrarMascotas(mascotas);
        } catch (error) {
            console.error("Error al cargar mascotas: ", error);
            if (contenedorMascotas) {
                contenedorMascotas.innerHTML = "<p class='text-red-400 text-center col-span-full'>Error: " + error.message + "</p>";
            }
        }
    }

    // Función para mostrar mascotas
    function mostrarMascotas(mascotas) {
        contenedorMascotas.innerHTML = "";
        
        if (mascotas.length === 0) {
            contenedorMascotas.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-400 text-xl mb-4">No tienes mascotas registradas aún</p>
                    <button onclick="abrirModalMascota()" class="bg-emerald-600 text-white px-6 py-2 rounded hover:bg-emerald-700">
                        Agregar tu primera mascota
                    </button>
                </div>
            `;
        } else {
            mascotas.forEach((mascota) => {
                const mascotaDiv = document.createElement("div");
                mascotaDiv.className = "bg-slate-900/80 border border-emerald-600 rounded-lg shadow-lg p-6 hover:shadow-emerald-500/50 transition";
                mascotaDiv.innerHTML = `
                    <div class="text-center mb-4">
                        ${mascota.foto ? `<img src="${mascota.foto}" alt="${mascota.nombre}" class="w-32 h-32 object-cover rounded-full mx-auto border-4 border-emerald-600">` : `<div class="w-32 h-32 bg-slate-800 rounded-full mx-auto flex items-center justify-center text-4xl border-4 border-emerald-600">🐾</div>`}
                    </div>
                    <h3 class="text-2xl font-bold text-emerald-300 mb-2 text-center">${mascota.nombre}</h3>
                    <div class="space-y-1 text-slate-300 mb-4">
                        <p><span class="font-semibold text-emerald-400">Especie:</span> ${mascota.especie}</p>
                        <p><span class="font-semibold text-emerald-400">Raza:</span> ${mascota.raza}</p>
                        <p><span class="font-semibold text-emerald-400">Edad:</span> ${mascota.edad} años</p>
                        ${mascota.peso ? `<p><span class="font-semibold text-emerald-400">Peso:</span> ${mascota.peso} kg</p>` : ''}
                    </div>
                    ${mascota.notas ? `<p class="text-slate-400 text-sm mb-4 italic">${mascota.notas}</p>` : ''}
                    <div class="flex gap-2">
                        <a href="historial.html?mascota=${mascota.id}" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded text-center hover:bg-blue-700 transition font-semibold text-sm">
                            Ver Historial
                        </a>
                        <button onclick="editarMascota(${mascota.id})" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded hover:bg-slate-600 transition font-semibold text-sm">
                            Editar
                        </button>
                        <button onclick="eliminarMascota(${mascota.id})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition font-semibold text-sm">
                            Eliminar
                        </button>
                    </div>
                `;
                contenedorMascotas.appendChild(mascotaDiv);
            });
        }
    }

    // Botón foto - abrir selector
    if (btnFoto) {
        btnFoto.addEventListener('click', function(e) {
            e.preventDefault();
            inputFotoArchivo.click();
        });
    }

    // Manejar selección de archivo de foto
    if (inputFotoArchivo) {
        inputFotoArchivo.addEventListener('change', async function(e) {
            const archivo = e.target.files[0];
            
            if (!archivo) {
                nombreArchivo.textContent = '';
                inputFotoHidden.value = '';
                return;
            }

            // Validar que sea imagen
            if (!archivo.type.startsWith('image/')) {
                alert('Por favor selecciona un archivo de imagen');
                inputFotoArchivo.value = '';
                return;
            }

            // Mostrar estado de carga
            estadoCarga.classList.remove('hidden');
            nombreArchivo.textContent = '⏳ Subiendo...';

            try {
                // Subir imagen a Firebase
                const urlFirebase = await subirImagenFirebase(archivo, 'mascotas');
                
                // Guardar la URL en el campo oculto
                inputFotoHidden.value = urlFirebase;
                
                // Mostrar confirmación
                nombreArchivo.textContent = '✓ Imagen subida';
                nombreArchivo.classList.add('text-emerald-400');
                
                setTimeout(() => {
                    nombreArchivo.classList.remove('text-emerald-400');
                    nombreArchivo.textContent = `Archivo: ${archivo.name}`;
                }, 2000);
            } catch (error) {
                console.error('Error al subir imagen:', error);
                nombreArchivo.textContent = '✗ Error: ' + error.message;
                nombreArchivo.classList.add('text-red-400');
                inputFotoArchivo.value = '';
                inputFotoHidden.value = '';
            } finally {
                estadoCarga.classList.add('hidden');
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

            console.log('📝 Datos a enviar:', mascotaData);
            console.log('🔐 Token:', token ? 'Presente' : 'Faltante');

            try {
                const respuesta = await fetch('http://127.0.0.1:8000/api/mascotas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(mascotaData)
                });

                console.log('📡 Status de respuesta:', respuesta.status);

                if (respuesta.ok) {
                    const responseData = await respuesta.json();
                    console.log('✅ Respuesta exitosa:', responseData);
                    cerrarModalMascota();
                    formMascota.reset();
                    inputFotoHidden.value = '';
                    await cargarMascotas();
                    mostrarToast('Mascota agregada exitosamente', 'success');
                } else {
                    const error = await respuesta.json();
                    console.error('❌ Error del servidor:', error);
                    mostrarToast(error.message || 'Error al agregar mascota: ' + respuesta.status, 'error');
                }
            } catch (error) {
                console.error('🚨 Error de conexión:', error);
                mostrarToast('Error de conexión: ' + error.message, 'error');
            }
        });
    }

    // Cargar mascotas al iniciar
    cargarMascotas();
    
    // Botón agregar mascota
    const btnAgregarMascota = document.getElementById('btn-agregar-mascota');
    if (btnAgregarMascota) {
        btnAgregarMascota.addEventListener('click', abrirModalMascota);
    }
});
