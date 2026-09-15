# Preparación de despliegue en Hostinger

Estado: preparación documental. No se ha publicado la aplicación ni se ha verificado una cuenta o plan concreto.

## Requisitos a comprobar en el panel

- PHP 8.2 o superior con `pdo_mysql`, mbstring, openssl, fileinfo, zip, intl, curl y gd.
- MySQL/MariaDB, acceso para crear una base y usuario limitado.
- Acceso SSH o una alternativa para ejecutar Composer y migraciones.
- Posibilidad de configurar la raíz pública del dominio a `public/`.
- HTTPS activo antes de habilitar cookies seguras y enlaces compartidos.
- Cron para `php artisan schedule:run` cada minuto y, si se usan colas, un proceso worker supervisado.
- Almacenamiento suficiente fuera de `public/` para comprobantes privados.

## Publicación segura

1. Respaldar base y archivos actuales del hosting.
2. Subir el proyecto sin `.env`, `database/database.sqlite`, `storage/app/private` ni archivos de prueba.
3. Crear `.env` de producción con `APP_ENV=production`, `APP_DEBUG=false`, URL HTTPS, base MySQL de producción y credenciales exclusivas.
4. Ejecutar `composer install --no-dev --optimize-autoloader` y `php artisan migrate --force`.
5. Subir `public/build` generado localmente o compilar en el servidor si sus recursos lo permiten.
6. Ejecutar `php artisan storage:link` solo para archivos destinados a ser públicos; comprobantes financieros continúan privados.
7. Activar cachés: `php artisan config:cache`, `route:cache` y `view:cache`.
8. Verificar login, invitación, RSVP, recepción, finanzas y que `/public` no aparezca en la URL.

## Reversión

1. Activar mantenimiento: `php artisan down`.
2. Restaurar la versión anterior de código y el respaldo de base si la migración requiere reversión de datos.
3. Limpiar/reconstruir cachés y ejecutar `php artisan up`.

Nunca reutilizar las credenciales locales en producción ni desplegar cuentas demo.
