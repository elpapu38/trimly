# Trimly

**Plataforma de reservas online para barberías, salones, spas y estudios de tatuajes en Argentina.**

Trimly conecta clientes con locales de belleza y estética permitiendo buscar servicios, ver el equipo, elegir horarios y reservar turnos en pocos pasos — sin depender de WhatsApp ni llamadas telefónicas.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.1+ (sin framework, MVC propio) |
| Base de datos | MySQL 8 / MariaDB 10.6+ |
| Frontend | Tailwind CSS (CDN), vanilla JS |
| Mapas | Leaflet.js + OpenStreetMap (sin API key) |
| Geocodificación | Nominatim (OpenStreetMap, gratuito) |
| Pagos | MercadoPago (integración pendiente) |
| Email | SMTP configurable (PHPMailer) |
| Servidor local | Laragon (Windows) |

---

## Estructura del proyecto

```
trimly/
├── index.php                        # Router principal + middleware
├── .env                             # Variables de entorno (NO commitear)
├── config/
│   └── app.php                      # Configuración general, estados de turno
├── routes/
│   └── web.php                      # Todas las rutas de la aplicación
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php       # Login, registro, recuperar contraseña
│   │   ├── BookingController.php    # Flujo de reserva (steps 1-4)
│   │   ├── ShopController.php       # Página pública del local
│   │   ├── ShopDashController.php   # Panel del dueño (turnos, agenda, historial)
│   │   ├── ShopSettingsController.php # Configuración del local
│   │   ├── EmployeeController.php   # CRUD empleados + vincular cuentas
│   │   ├── EmployeeDashController.php # Panel del empleado
│   │   ├── StatsController.php      # Estadísticas del local
│   │   ├── ReviewController.php     # Reseñas y respuestas
│   │   ├── ReportController.php     # Reportes de locales
│   │   ├── AdminController.php      # Panel superadmin
│   │   ├── AccountController.php    # Cambiar email/contraseña, eliminar cuenta
│   │   └── BaseController.php       # Métodos compartidos, auth, redirect, json
│   ├── Models/
│   │   ├── Models.php               # Todos los modelos (User, Shop, Employee, etc.)
│   │   └── Database.php             # Singleton PDO
│   └── Views/
│       ├── partials/                # header.php, footer.php, layout.php
│       ├── auth/                    # login, register, forgot-password
│       ├── booking/                 # step1, step2, step3, step4, confirmation
│       ├── shop/                    # show.php (página pública del local)
│       ├── shop_dash/               # Panel del dueño del local
│       ├── employee_dash/           # Panel del empleado
│       ├── admin/                   # Panel superadmin
│       ├── account/                 # Cambiar contraseña/email, eliminar cuenta
│       └── errors/                  # 403, 404, 500
├── public/
│   └── uploads/                     # Imágenes subidas (avatares, fotos, portfolios)
├── database/
│   └── migrations/
│       ├── trimly_db_completo.sql   # Schema completo + datos de prueba
│       ├── employee_photos.sql      # Portfolio de fotos de empleados
│       ├── ban_fields.sql           # Suspensión con tiempo y motivo
│       └── stat_periods.sql        # Períodos personalizados de estadísticas
└── storage/
    └── logs/                        # Logs de errores PHP
```

---

## Roles del sistema

| Rol | Panel | Descripción |
|---|---|---|
| `superadmin` | `/admin` | Gestión completa: locales, usuarios, reseñas, períodos de stats |
| `shop_owner` | `/panel` | Su local: empleados, servicios, turnos, agenda, estadísticas |
| `employee` | `/mi-panel` | Sus turnos, servicios asignados, portfolio de fotos, perfil |
| `client` | `/mis-turnos` | Historial de reservas, reseñas, favoritos |
| *(invitado)* | — | Puede reservar turnos ingresando sus datos manualmente |

---

## Instalación local (Laragon)

### Requisitos
- Laragon con PHP 8.1+ y MySQL 8
- El proyecto en `C:\laragon\www\trimly\`

### Pasos

**1. Copiar el proyecto**
```
C:\laragon\www\trimly\
```

**2. Configurar `.env`**
```env
APP_NAME=Trimly
APP_URL=http://localhost/trimly
APP_ENV=development
APP_DEBUG=true

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=trimly
DB_USER=root
DB_PASS=

MAIL_HOST=
MAIL_PORT=587
MAIL_USER=
MAIL_PASS=
MAIL_FROM=noreply@trimly.com

MP_PUBLIC_KEY=TEST-xxxxxxxx
MP_ACCESS_TOKEN=TEST-xxxxxxxx
```

**3. Crear la base de datos en phpMyAdmin**
- Crear base `trimly` con collation `utf8mb4_unicode_ci`
- Importar en orden:
  1. `database/migrations/trimly_db_completo.sql`
  2. `database/migrations/employee_photos.sql`
  3. `database/migrations/ban_fields.sql`
  4. `database/migrations/stat_periods.sql`

**4. Abrir en el navegador**
```
http://localhost/trimly
```

### Usuarios de prueba incluidos

| Email | Contraseña | Rol |
|---|---|---|
| admin@trimly.com | admin123 | superadmin |
| carlos@elnavajo.com | carlos123 | shop_owner (Barbería El Navajo) |
| lucas@elnavajo.com | wazawaza | employee (Barbería El Navajo) |

---

## Flujo de reserva

```
Paso 1 — Elegir servicio
     ↓
Paso 2 — Elegir profesional (o "cualquiera disponible")
     ↓
Paso 3 — Elegir fecha y horario
         → Sin turnos ese día: muestra tarjeta con próxima fecha disponible
         → El cliente puede aceptar la sugerencia o elegir otra fecha
     ↓
Paso 4 — Datos del cliente + método de pago
         → Logueado: datos pre-cargados, editables
         → Invitado: puede ingresar sus datos o registrarse
         → Con seña: "Pagar seña ahora" o "Pagar total online"
         → Sin seña: "Pagar en el local" o "Pagar total online"
         → Cuentas de negocio/empleado/admin: bloqueadas con aviso
```

---

## Funcionalidades por rol

### Clientes
- Buscar locales por tipo de servicio, ciudad y audiencia (hombres, mujeres, unisex)
- Ver página completa del local: servicios, equipo con portfolio, horarios, reseñas, mapa
- Reservar turnos con o sin cuenta registrada
- Cancelar turnos propios
- Escribir reseñas tras completar un turno
- Guardar locales como favoritos
- Ver historial de turnos
- Cambiar email y contraseña desde ⚙ Mi cuenta
- Eliminar su propia cuenta (reseñas se conservan anónimas, stats no se alteran)

### Dueños de local (`shop_owner`)
- Dashboard con resumen: turnos del día, métricas del mes, accesos rápidos
- Agenda semanal visual con navegación por flechas
- Gestión de turnos: confirmar, completar, cancelar, marcar "no asistió"
- **Historial** de turnos pasados con confirmación de asistencia pendiente
- Gestión completa de empleados:
  - Crear/editar empleados con horarios propios
  - Vincular empleado a una cuenta de usuario (le da acceso al panel `/mi-panel`)
  - Desvincular cuenta (revierte rol a cliente)
- Gestión de servicios y categorías con soporte de seña (%)
- Configuración del local:
  - Información general, horarios de atención, fotos del local
  - Mapa interactivo (Leaflet) para ubicar el local con geocodificación por dirección
- Estadísticas por año o períodos personalizados del superadmin
- Responder reseñas de clientes

### Empleados (`employee`)
- Dashboard con stats propias del mes y turnos de hoy
- **Turnos del día**: tabla con acciones por estado
- **Semana propia**: navegación por flechas, con contador completados/total por día y puntos en días con turnos
- **Historial propio**: turnos pasados con confirmación de asistencia
- **Cargar turno manual**: con selector de horarios reales (mismos slots que ven los clientes)
- **Mis servicios**: seleccionar cuáles de los servicios del local ofrecen
- **Portfolio de fotos** (máx. 12): galería con lightbox, captions visibles, swipe en móvil
- **Mi perfil**: nombre, especialidad, Instagram, bio, avatar
- Navegación mobile con barra inferior (igual que el panel del dueño)

### Superadmin
- Gestión de locales: ver, verificar, cambiar estado
- Gestión de usuarios: buscar, filtrar por rol/estado
- Suspender con duración (1/3/7/30 días o personalizado) y motivo visible al usuario
- Banear permanentemente con motivo
- Eliminar cuentas baneadas (datos anonimizados, reseñas y stats conservadas)
- Moderar reseñas reportadas: ocultar, restaurar, eliminar, advertir usuario
- **Crear períodos de estadísticas** (ej: "Verano 2025", "Q1 2026") que aparecen como filtros en el panel de todos los locales

---

## Portfolio de empleados en la página pública

Cada empleado puede subir hasta 12 fotos de sus trabajos desde `/mi-panel/fotos`.

En la página pública del local (sección "El equipo"), cada empleado muestra un carrusel con:
- Flechas de navegación (‹ ›)
- Dots indicadores con loop
- Swipe táctil en móvil
- Lightbox al hacer clic
- Máximo 12 fotos, límite aplicado en frontend y backend

---

## Sistema de ban / suspensión

**Suspensión temporal:**
- El superadmin elige duración (días) y motivo
- Al intentar ingresar, el usuario ve el motivo y la fecha exacta de vencimiento
- Las suspensiones vencidas se levantan automáticamente en el próximo intento de login

**Baneo permanente:**
- El usuario ve el motivo al intentar ingresar
- El superadmin puede eliminar la cuenta (anonimiza datos personales, conserva reseñas y estadísticas)

---

## Seguridad

- Contraseñas con `password_hash()` bcrypt cost-12
- CSRF tokens en todos los formularios POST
- Validación de roles en cada endpoint (no solo en vistas)
- Empleados solo pueden gestionar sus propios turnos
- Dueños de local no pueden reportar otros locales
- Cuentas de negocio/empleado/admin bloqueadas en el flujo de reserva
- Bloqueo server-side de email de cuentas no-cliente al cargar turno manual

---

## Variables de entorno

| Variable | Descripción |
|---|---|
| `APP_URL` | URL base (ej: `http://localhost/trimly`) |
| `APP_ENV` | `development` o `production` |
| `APP_DEBUG` | `true` o `false` |
| `DB_HOST` | Host MySQL |
| `DB_NAME` | Nombre de la base |
| `DB_USER` | Usuario MySQL |
| `DB_PASS` | Contraseña MySQL |
| `MP_PUBLIC_KEY` | Clave pública MercadoPago |
| `MP_ACCESS_TOKEN` | Token de acceso MercadoPago |

---

## Sobre la posibilidad de tiendas de productos

Sí, es totalmente posible extender Trimly para que los locales también vendan productos físicos (pomadas, cremas, accesorios de barbería, etc.). La idea sería:

**Lo que requeriría:**
- Tabla `products` (id, shop_id, name, description, price, stock, images, category, is_active)
- Tabla `orders` y `order_items` para el carrito y las compras
- Una sección "Tienda" en la página pública de cada local, separada del flujo de turnos
- Panel de gestión de productos en el dashboard del dueño
- Checkout propio (podría usar MercadoPago igual que los turnos)
- Gestión de stock y notificaciones de venta

**Cómo encajaría en Trimly:**
La página del local ya tiene pestañas (Servicios / El equipo / Horarios / Reseñas / Ubicación). Se agregaría una pestaña "Tienda" cuando el local tiene productos activos. El carrito podría ser global (mezclar productos de diferentes locales) o por local (más simple). La segunda opción es más directa para empezar.

**Esfuerzo estimado:** medio-alto. El modelo de datos no es complejo, pero el checkout, manejo de stock, órdenes y notificaciones agregan bastante superficie. Recomendaría implementarlo como una segunda fase del proyecto una vez que el flujo de turnos esté 100% estable en producción.

---

## Roadmap futuro

- Notificaciones push / SMS para recordatorio de turnos
- App móvil nativa (React Native)
- Integración con Google Calendar
- Sistema de descuentos y cupones
- Panel de analytics avanzado con exportación CSV/Excel
- Múltiples sucursales por local
- Sistema de paquetes de servicios
- **Tiendas de productos físicos** (ver sección anterior)

---

## Créditos

Desarrollado como proyecto full-stack.
Diseño UI: sistema de diseño propio sobre Tailwind CSS, paleta `ink` / `gold` / `cream`.
Mapas: © OpenStreetMap contributors — tiles via OpenStreetMap.org.
Geocodificación: Nominatim (uso libre, política de uso justo).
