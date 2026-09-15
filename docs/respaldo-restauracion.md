# Respaldo y restauración local

La base activa es MySQL local `boda`. Ejecute estos comandos en una terminal que solicite la contraseña de forma interactiva; no incluya la contraseña en scripts, historial ni documentación.

## Respaldo

```powershell
& 'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe' -u root -p --single-transaction --routines --events boda > respaldo-boda.sql
```

Compruebe que `respaldo-boda.sql` no esté vacío y guárdelo fuera de `public/` y fuera del repositorio.

## Restauración

La restauración sobrescribe el contenido de la base indicada. Confirme primero que el destino es exactamente `boda` y que existe un respaldo válido.

```powershell
& 'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysql.exe' -u root -p boda < respaldo-boda.sql
```

Para crear una demostración limpia, use `php artisan migrate:fresh --seed` solo después de confirmar que no se requiere conservar información local.
