// Toast de notificación
function mostrarToast(mensaje, tipo = 'success') {
    let toast = document.getElementById('toast-msg');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast-msg';
        toast.className = 'fixed top-6 right-6 z-50 px-6 py-3 rounded-lg shadow-lg text-lg font-bold';
        document.body.appendChild(toast);
    }
    toast.textContent = mensaje;
    toast.className = 'fixed top-6 right-6 z-50 px-6 py-3 rounded-lg shadow-lg text-lg font-bold ' +
        (tipo === 'success' ? 'bg-emerald-600 text-white' : 'bg-red-600 text-white');
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 2200);
}

// Inicialización de la página principal
document.addEventListener("DOMContentLoaded", () => {
    // Mensaje de bienvenida
    const token = localStorage.getItem('authToken');
    if (token) {
        console.log('Usuario autenticado en PetCare');
    }
});