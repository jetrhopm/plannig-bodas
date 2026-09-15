# Operación local — Fase 1

## Servicios necesarios

La interfaz compilada no requiere Node.js ejecutándose. Use `npm run build` después de cambios de frontend. Para desarrollo visual con recarga use opcionalmente `npm run dev`.

Laravel usa sesiones, caché, cola y notificaciones con el driver `database`. El correo local está configurado en `log`: no envía mensajes reales y sus intentos aparecen en `storage/logs/laravel.log`.

## Planificador y trabajos

Cuando una fase agregue recordatorios o avisos diferidos, ejecute ambos procesos en terminales separadas:

```powershell
php artisan schedule:work
php artisan queue:work --tries=3
```

Actualmente Fase 1 no agenda recordatorios de negocio ni requiere que esos procesos estén activos para iniciar sesión, consultar bodas o leer avisos. La tabla de trabajos y la tabla de notificaciones ya existen para las fases siguientes.

## Datos demo y recuperación

Para recuperar únicamente la demostración SQLite:

```powershell
php artisan migrate:fresh --seed --no-interaction
```

Este comando elimina las tablas de la base seleccionada. Úselo solo sobre `database/database.sqlite` de demostración, nunca sobre una base con información real. El seeder se niega a ejecutarse en producción.
