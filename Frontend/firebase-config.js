// ⚠️ IMPORTANTE: Configuración de Firebase
const firebaseConfig = {
    apiKey: "AIzaSyCE6U_F7ASS8iiB2haKML05WeFmyV3J7A",
    authDomain: "vete-dd3cf.firebaseapp.com",
    projectId: "vete-dd3cf",
    storageBucket: "vete-dd3cf.firebasestorage.app",
    messagingSenderId: "158283607206",
    appId: "1:158283607206:web:95a3ffc476e3fc6d405867"
};

// Inicializar Firebase
firebase.initializeApp(firebaseConfig);
const storage = firebase.storage();

// Función para subir imagen a Firebase Storage
window.subirImagenFirebase = async function(archivo, carpeta = 'mascotas') {
    try {
        if (!archivo) {
            throw new Error('No se seleccionó ningún archivo');
        }

        const timestamp = Date.now();
        const nombreArchivo = `${timestamp}_${archivo.name}`;
        const ruta = `${carpeta}/${nombreArchivo}`;
        
        // Crear referencia al almacenamiento
        const ref = storage.ref(ruta);
        
        // Subir archivo a Firebase Storage
        const snapshot = await ref.put(archivo);
        
        // Obtener URL de descarga permanente
        const url = await snapshot.ref.getDownloadURL();
        
        console.log('✓ Imagen subida exitosamente:', url);
        return url;
    } catch (error) {
        console.error('✗ Error al subir imagen:', error.message);
        throw new Error(`Error al subir imagen: ${error.message}`);
    }
};

// Función para eliminar imagen de Firebase Storage
window.eliminarImagenFirebase = async function(urlImagen) {
    try {
        if (!urlImagen) {
            throw new Error('URL de imagen no proporcionada');
        }

        const ref = storage.refFromURL(urlImagen);
        await ref.delete();
        
        console.log('✓ Imagen eliminada de Firebase');
        return true;
    } catch (error) {
        console.error('✗ Error al eliminar imagen:', error.message);
        throw new Error(`Error al eliminar imagen: ${error.message}`);
    }
};
