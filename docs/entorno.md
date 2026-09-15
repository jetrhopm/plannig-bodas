# Guía de entorno y datos por confirmar — Fase 0

## Verificado el 2026-09-14

| Dato | Evidencia |
| --- | --- |
| PHP y extensiones | PHP 8.2.12; presentes `pdo_mysql`, `pdo_sqlite`, mbstring, openssl, fileinfo, zip, intl, curl y gd. |
| Composer / Node / npm | Composer 2.9.7, Node 24.16.0 y npm 11.13.0. |
| Aplicación | Laravel 12.69, Vue 3/Inertia y Vite 7 instalados. |
| Base de demostración | MySQL Community Server 8.0.41 en la base `boda`; migraciones y seeder ejecutados correctamente. SQLite se conserva para pruebas aisladas. |
| XAMPP | Incluye MariaDB 10.4.32; su puerto 3306 está ocupado por `MySQL80`, por lo que MariaDB de XAMPP no puede arrancar ahí. |

## No confirmado aún

| Dato | Estado requerido |
| --- | --- |
| Contraseña local de `root` | Debe confirmarla el propietario; no se intenta adivinar. |
| Nombre de base de datos destinada a este sistema | Debe confirmarse o proponerse antes de crearla. |
| Puertos libres para servidor Laravel/Vite | Inspeccionar. |
| Dominio, panel y capacidades Hostinger | Fuera de esta fase; verificar antes de publicar. |
| Correo/WhatsApp, credenciales y remitentes | No requeridos localmente; pendientes para integración real. |

## Convenciones de configuración

- `.env` es local y nunca se versiona ni se comparte. No incluir secretos en logs, commits, capturas o documentación.
- `.env.example` usa valores ficticios; para la base local debe documentar `DB_PASSWORD=<CONFIGURAR_LOCALMENTE>` como marcador, no contraseña válida.
- Correo local: transporte de prueba/base de datos sin envíos externos. WhatsApp local: compartir enlace manual; no afirmar entrega.
- Seeder demo: solo `local`/`testing`, idempotente y bloqueado explícitamente en producción; la contraseña `password` solo aplica a las cuentas ficticias señaladas en el brief.

## Verificación antes de cerrar la fase

Evidencia registrada: `php artisan migrate:fresh --seed --no-interaction`, `npm run build` y `php artisan test` finalizaron correctamente con SQLite. Para cerrar la conexión MariaDB/MySQL falta que el propietario defina la instancia, BD y contraseña, sin que esto impida la demostración SQLite.
