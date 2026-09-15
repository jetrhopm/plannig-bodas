# Casa de Bodas

Sistema web para organizar bodas, con operación por roles, portal de pareja, RSVP, recepción, planeación, finanzas, regalos y comunicaciones. Incluye datos de demostración locales y documentación de operación/despliegue.

## Stack verificado

- Laravel 12.69 / PHP 8.2.12
- Vue 3 + Inertia + Vite 7 + Tailwind
- MySQL Community Server 8.0.41 local para la demostración; SQLite permanece disponible para pruebas aisladas.

## Arranque local

```powershell
composer install
npm install
if (!(Test-Path database\database.sqlite)) { New-Item -ItemType File database\database.sqlite }
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Abra `http://127.0.0.1:8000` e ingrese con cualquiera de las cuentas demo. La contraseña de todas es `password`, exclusivamente en `local` o `testing`.

| Cuenta | Rol |
| --- | --- |
| admin@local.test | Administrador |
| coordinador@local.test | Coordinador |
| finanzas@local.test | Finanzas |
| recepcion@local.test | Recepción |
| pareja@local.test / pareja2@local.test | Pareja de Sofía & Daniel |
| otra.pareja@local.test | Pareja aislada de otra boda |

La demostración actual usa MySQL local en la base `boda`. Las credenciales reales permanecen exclusivamente en `.env`; `.env.example` conserva valores ficticios. XAMPP incluye MariaDB, pero el puerto 3306 está atendido por MySQL Community Server local; vea [docs/entorno.md](docs/entorno.md).

## Verificación ejecutada

```powershell
php artisan migrate:fresh --seed --no-interaction
npm run build
php artisan test
```

Las tres comprobaciones finalizaron correctamente en SQLite. La prueba de integración confirma que una cuenta de pareja no recibe bodas fuera de su membresía.

La operación local de cola, planificador y correo de prueba está documentada en [docs/operacion-local.md](docs/operacion-local.md). Node no necesita permanecer activo después de `npm run build`.

## URL y Apache

La instalación se puede abrir desde `localhost/boda`, `127.0.0.1/boda` o la IP LAN sin cambiar código. Para un dominio, configure la raíz pública del sitio como `public/`, no como la carpeta del proyecto. La guía y el ejemplo de VirtualHost están en [docs/acceso-web.md](docs/acceso-web.md).

## Módulos disponibles

- Gestión de bodas, expediente, eventos, responsabilidades y servicios.
- Familias, RSVP, pases, recepción y solicitudes de lugares.
- Entrevista inicial, planeación, tareas, agenda, propuestas, logística, inspiración, mesas y menú.
- Finanzas, cotizaciones, pagos y comprobantes privados.
- Mesa de regalos y liberación trazable de reservas.
- Preferencias de comunicación, avisos, permisos por rol y auditoría.

## Documentación

- [Arquitectura](docs/arquitectura.md)
- [Modelo de datos](docs/modelo-datos.md)
- [Permisos](docs/permisos.md)
- [Operación local](docs/operacion-local.md)
- [Acceso por IP, subcarpeta y dominio](docs/acceso-web.md)
- [Despliegue en Hostinger](docs/despliegue-hostinger.md)
- [Respaldo y restauración](docs/respaldo-restauracion.md)
- [Validación local](docs/validacion-fase-9.md)

## Nota sobre móvil

La aplicación se puede instalar bajo una subcarpeta como `/boda` sin URLs fijas. Ejecute siempre `npm run build` después de cambios de interfaz para regenerar los recursos con hash. Los módulos Vite se construyen con ruta relativa para que CSS y JavaScript funcionen tanto en IP LAN como en dominio.
