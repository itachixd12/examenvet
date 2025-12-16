# 🏥 PetCare - Backend Laravel Setup

## Resumen de Cambios

Se ha convertido el backend de una tienda online a un sistema completo de **Clínica Veterinaria** con gestión de:
- 🐾 Mascotas de clientes
- 📅 Sistema de citas veterinarias
- 👨‍⚕️ Gestión de veterinarios
- 💊 Servicios veterinarios
- 📋 Historial médico de mascotas
- 🗺️ Integración con Mapbox
- 🔥 Preparación para Firebase

---

## 📁 Estructura de Archivos Nuevos

### Models (`app/Models/`)
- `Mascota.php` - Modela mascotas/pacientes
- `Cita.php` - Sistema de citas
- `Veterinario.php` - Personal veterinario
- `Servicio.php` - Servicios ofrecidos
- `HistorialMedico.php` - Historial médico de mascotas
- `HorarioVeterinario.php` - Horarios de atención
- `ConfiguracionClinica.php` - Datos de la clínica

### Controllers (`app/Http/Controllers/`)
- `MascotaController.php` - CRUD de mascotas
- `CitaController.php` - CRUD de citas
- `VeterinarioController.php` - CRUD de veterinarios
- `ServicioController.php` - CRUD de servicios
- `HistorialMedicoController.php` - Gestión de historial
- `ConfiguracionClinicaController.php` - Configuración

### Migraciones (`database/migrations/`)
- `2025_12_14_000001_create_mascotas_table.php`
- `2025_12_14_000002_create_veterinarios_table.php`
- `2025_12_14_000003_create_servicios_table.php`
- `2025_12_14_000004_create_citas_table.php`
- `2025_12_14_000005_create_historial_medico_table.php`
- `2025_12_14_000006_create_horarios_veterinarios_table.php`
- `2025_12_14_000007_create_configuracion_clinica_table.php`

### Seeders (`database/seeders/`)
- `VeterinarioSeeder.php` - 4 veterinarios de ejemplo
- `ServicioSeeder.php` - 8 servicios veterinarios
- `ConfiguracionClinicaSeeder.php` - Datos de la clínica

### Config (`config/`)
- `firebase.php` - Configuración de Firebase

### Routes
- `routes/api.php` - APIs completamente rediseñadas

---

## 🚀 Instalación y Setup

### 1. Configurar Variables de Entorno

Edita `.env` y agrega:

```env
# Firebase (Opcional, para futuras integraciones)
FIREBASE_PROJECT_ID=petcare-clinica
FIREBASE_PRIVATE_KEY_ID=tu_private_key_id
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...tu_private_key...\n-----END PRIVATE KEY-----\n"
FIREBASE_CLIENT_EMAIL=firebase-adminsdk@petcare-clinica.iam.gserviceaccount.com
FIREBASE_CLIENT_ID=tu_client_id
FIREBASE_AUTH_PROVIDER_X509_CERT_URL=https://www.googleapis.com/oauth2/v1/certs
FIREBASE_CLIENT_X509_CERT_URL=tu_cert_url

# Mapbox Token (para geolocalización de clínica)
MAPBOX_TOKEN=pk.eyJ1IjoiZXJpY2tzdGV2ZW4xNyIsImEiOiJjbWl6M25jcjgwbTJ4M2tweTJ5dXEzc29iIn0.gCKA1vCnL0A1rY2qSL3uqQ
```

### 2. Ejecutar Migraciones

```bash
php artisan migrate
```

### 3. Ejecutar Seeders

```bash
php artisan db:seed
```

Esto creará:
- 4 veterinarios
- 8 servicios
- Configuración básica de la clínica

### 4. Iniciar el Servidor

```bash
php artisan serve
```

El servidor estará en `http://127.0.0.1:8000`

---

## 📡 Endpoints de API

### Públicos (sin autenticación)

```
GET    /api/servicios              - Listar todos los servicios
GET    /api/servicios/{id}         - Obtener un servicio
GET    /api/veterinarios           - Listar veterinarios
GET    /api/veterinarios/{id}      - Obtener veterinario
GET    /api/clinica/info           - Info de la clínica
```

### Autenticación

```
POST   /api/register               - Registrar usuario
POST   /api/login                  - Login
POST   /api/logout                 - Logout
```

### Usuarios (Autenticados)

#### Mascotas
```
GET    /api/mascotas               - Mis mascotas
GET    /api/mascotas/{id}          - Detalle de mascota
POST   /api/mascotas               - Crear mascota
PUT    /api/mascotas/{id}          - Editar mascota
DELETE /api/mascotas/{id}          - Eliminar mascota
```

#### Citas
```
GET    /api/citas                  - Mis citas
GET    /api/citas/{id}             - Detalle de cita
POST   /api/citas                  - Agendar cita
PUT    /api/citas/{id}             - Modificar cita
DELETE /api/citas/{id}             - Cancelar cita
```

#### Historial Médico
```
GET    /api/mascotas/{mascotaId}/historial  - Historial de mascota
```

### Admin (Autenticados + rol admin)

#### Servicios
```
POST   /api/admin/servicios        - Crear servicio
PUT    /api/admin/servicios/{id}   - Editar servicio
DELETE /api/admin/servicios/{id}   - Eliminar servicio
```

#### Veterinarios
```
POST   /api/admin/veterinarios     - Crear veterinario
PUT    /api/admin/veterinarios/{id}  - Editar veterinario
DELETE /api/admin/veterinarios/{id}  - Eliminar veterinario
```

#### Mascotas (Admin)
```
GET    /api/admin/mascotas         - Todas las mascotas
DELETE /api/admin/mascotas/{id}    - Eliminar mascota
```

#### Historial (Admin)
```
POST   /api/admin/historial        - Crear registro de historial
```

#### Configuración
```
PUT    /api/admin/clinica/config   - Actualizar configuración
```

#### Dashboard
```
GET    /api/admin/estadisticas     - Estadísticas generales
GET    /api/admin/citas-proximas   - Citas próximas
```

---

## 📝 Ejemplos de Requests

### Crear Mascota
```json
POST /api/mascotas
{
  "nombre": "Max",
  "especie": "Perro",
  "raza": "Golden Retriever",
  "edad": 3.5,
  "peso": 28.5,
  "foto": "https://example.com/max.jpg",
  "notas": "Alérgico a la lactosa"
}
```

### Agendar Cita
```json
POST /api/citas
{
  "mascota_id": 1,
  "servicio_id": 1,
  "veterinario_id": 2,
  "fecha": "2025-12-20",
  "hora": "14:30",
  "motivo": "Consulta de rutina",
  "notas": "Revisar vacunas"
}
```

### Crear Servicio (Admin)
```json
POST /api/admin/servicios
{
  "nombre": "Microchip",
  "descripcion": "Colocación de microchip de identificación",
  "precio": 30.00,
  "duracion": 15
}
```

### Crear Veterinario (Admin)
```json
POST /api/admin/veterinarios
{
  "nombre": "Dr. Roberto Torres",
  "email": "roberto.torres@petcare.com",
  "telefono": "+593 99 000 0005",
  "especialidad": "Oftalmología Veterinaria"
}
```

---

## 🔐 Autenticación

El backend usa **Laravel Sanctum** para autenticación por tokens.

### Login
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "usuario@example.com",
    "password": "password"
  }'
```

Respuesta:
```json
{
  "token": "1|LjuAv...",
  "message": "Login exitoso"
}
```

### Usar Token en Requests
```bash
curl -X GET http://127.0.0.1:8000/api/mascotas \
  -H "Authorization: Bearer 1|LjuAv..."
```

---

## 🗺️ Mapbox Integration

El token de Mapbox se configura en:
- `config/firebase.php`
- Base de datos (`configuracion_clinica`)
- Frontend (para mostrar ubicación de la clínica)

Coordenadas actuales de ejemplo:
- **Latitud**: -0.3523
- **Longitud**: -78.4834
- **Ubicación**: Sangolqui, Ecuador

---

## 🔥 Firebase (Próximamente)

Las credenciales de Firebase deben configurarse en `.env` para:
- Autenticación alternativa
- Storage de fotos de mascotas
- Notificaciones push
- Logs en tiempo real

---

## 📊 Base de Datos

Las migraciones crean automáticamente las tablas necesarias:

```
users                      (usuarios del sistema)
mascotas                   (mascotas/pacientes)
veterinarios               (personal veterinario)
servicios                  (servicios ofrecidos)
citas                      (citas agendadas)
historial_medico           (historial de cada mascota)
horarios_veterinarios      (horarios de atención)
configuracion_clinica      (datos de la clínica)
```

---

## ✅ Próximos Pasos

1. ✅ Modelos creados
2. ✅ Migraciones creadas
3. ✅ Controllers creados
4. ✅ Rutas configuradas
5. ✅ Seeders preparados
6. ⏳ Pruebas de endpoints
7. ⏳ Autenticación con Firebase (opcional)
8. ⏳ Notificaciones de citas
9. ⏳ Reportes y estadísticas

---

## 🐛 Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
```

### Migraciones fallidas
```bash
php artisan migrate:refresh --seed
```

### Token expirado
Solicita un nuevo token con `/api/login`

---

## 📚 Documentación Adicional

- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Mapbox API](https://docs.mapbox.com/)
- [Firebase Admin SDK](https://firebase.google.com/docs/admin/setup)

---

**PetCare © 2025 - Clínica Veterinaria**
