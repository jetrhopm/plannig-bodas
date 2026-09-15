# Acceso web portable

La aplicación no fija una IP ni dominio en rutas, redirecciones o recursos. Laravel toma el host y la base de la petición entrante. Por eso las direcciones siguientes funcionan con la misma instalación XAMPP:

- `http://localhost/boda/login`
- `http://127.0.0.1/boda/login`
- `http://192.168.1.130/boda/login`

Se comprobó el 2026-09-14 mediante Apache XAMPP: las tres devuelven HTTP 200 y las referencias compiladas se generan bajo `/boda/build/...`.

## XAMPP en subcarpeta

El archivo raíz `.htaccess` mantiene `public/` fuera de la URL, envía las rutas amistosas a `index.php` y deja disponibles `build/` y `storage/`. No se debe navegar a `/boda/public`.

Requisito: Apache debe tener habilitados `mod_rewrite` y `AllowOverride All` para `C:/xampp/htdocs`. XAMPP los habilita normalmente; si se modifica su configuración, reinicie Apache.

## Dominio o subdominio

En producción no se usa el `.htaccess` de subcarpeta como sustituto de una raíz segura. El host virtual, el dominio de Hostinger o el subdominio debe apuntar directamente a la carpeta `public/` del proyecto. Así el sitio abre en `https://www.boda.com/login` sin `/boda` y sin exponer `.env`, `vendor/`, `database/` o los archivos fuente.

Ejemplo Apache local (ajuste las rutas y habilite SSL en producción):

```apache
<VirtualHost *:80>
    ServerName www.boda.test
    ServerAlias boda.test
    DocumentRoot "C:/xampp/htdocs/boda/public"

    <Directory "C:/xampp/htdocs/boda/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Para probar ese ejemplo localmente, agregue de forma manual los nombres a su archivo `hosts` y reinicie Apache. El dominio público real exige que el DNS y la raíz pública del hosting queden configurados por el propietario. No se configuraron DNS, SSL ni Hostinger durante esta fase.

## Variables de entorno

`APP_URL` no debe usarse para atar la navegación web a `localhost`, una IP o un dominio. Úselo como URL canónica solo para enlaces generados por tareas de consola, correo o colas cuando ya exista un dominio definido. No defina `ASSET_URL` mientras el proyecto pueda vivir tanto en una subcarpeta como en la raíz de un dominio: los assets se resuelven desde la petición actual.
