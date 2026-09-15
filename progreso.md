# Progreso del proyecto

## Fase 0 — Descubrimiento y especificación ejecutable

Estado: **completada**.

Completado en documentación:

- Arquitectura de monolito modular, límites y contratos críticos.
- Matriz inicial de permisos y reglas de aislamiento.
- Modelo de datos, estados e invariantes de cupos/RSVP/QR.
- Dependencias, fases, riesgos y requisitos de entorno pendientes.

Evidencia de cierre:

- Entorno inspeccionado: PHP 8.2.12, Composer 2.9.7, Node 24.16.0, MariaDB XAMPP 10.4.32.
- Esqueleto Laravel 12.69 con Breeze/Inertia/Vue instalado.
- `php artisan migrate:fresh --seed`, `npm run build` y `php artisan test` ejecutados correctamente sobre SQLite durante el arranque inicial.

Actualización: MySQL Community Server 8.0.41 local quedó configurado en la base `boda`; sus migraciones y el seeder demo se ejecutaron correctamente. Se corrigió el orden de tres migraciones iniciales para que las claves foráneas funcionen también en MySQL.

## Fase 1 — Base técnica y sistema visual

Estado: **completada**.

- Autenticación por sesión, cuentas demo y roles iniciales.
- Bodas, membresías aisladas, evento principal y panel adaptable.
- Seeder idempotente bloqueado en producción y prueba de aislamiento de pareja.
- Acceso validado con Apache XAMPP por `localhost`, loopback e IP LAN; configuración de dominio documentada con `public/` como raíz.
- Policy central para boda y listado limitado a administradores, coordinadores o membresías asignadas; tabla persistente de notificaciones preparada.
- Centro de notificaciones operativo: listado por usuario, contador global, lectura individual o masiva y prueba de aislamiento.
- Operación de cola, planificador y correo local documentada; no hay enlaces a módulos de fases futuras.

## Fase 2 — Expediente, entrevista y configuración

Estado: **completada**.

- Expediente de boda con eventos editables, servicios, responsabilidades y miembros.
- Entrevista con borrador reanudable, validación y auditoría de cada cambio.
- Eventos guardan el instante de inicio en UTC y conservan fecha/hora/zona de la boda; cuenta regresiva visible sin valores negativos.
- Permisos efectivos por membresía editables desde el expediente y auditados en backend.
- Resumen revisable y activación de configuración dentro de transacción; la pareja recibe aviso persistente después del commit.
- Pruebas de entrevista reanudable, hora UTC y activación/notificación ejecutadas correctamente.

## Fase 3 — Familias, pases, invitaciones y RSVP

Estado: **completada**.

- Familias aisladas por boda con asignación original y vigente, responsable y enlace opaco revocable.
- Portal privado de invitación y RSVP; confirma menos o rechaza sin agregar automáticamente al responsable.
- Respuesta idempotente, historial de respuestas, necesidades por integrante y notificaciones persistentes para familia y cuentas asociadas.
- Pruebas verifican seis a cuatro, liberación en rechazo e inaccesibilidad de token desconocido.

## Fase 4 — Solicitudes de lugares y ampliación de cupo

Estado: **completada en entorno local**.

- El responsable familiar puede pedir lugares adicionales desde su invitación sin que la solicitud reserve ni altere pases.
- La empresa revisa cada solicitud desde Familias; al aprobar, la capacidad se vuelve a validar dentro de una transacción antes de modificar la asignación.
- La ampliación sigue el flujo solicitud → propuesta versionada (con costo cero o importe) → aceptación de pareja autorizada → autorización final de empresa; no asigna pases automáticamente.
- Pruebas cubren concurrencia lógica: dos aprobaciones no pueden exceder el cupo y una ampliación no concede lugares a una familia por sí misma.
- Pendiente de validación de infraestructura: ejecutar la suite de concurrencia completa contra MySQL local; migraciones y seeder ya fueron verificados contra MySQL 8.0.41.

## Fase 5 — Pase QR y recepción

Estado: **completada en entorno local**.

- Pase familiar QR en SVG generado localmente desde un identificador opaco y revocable; mostrarlo no registra ni autoriza un ingreso.
- Recepción autenticada por permisos, con registro manual por familia, cantidades parciales y operaciones idempotentes.
- Los ingresos se bloquean al llegar a los confirmados vigentes; se registra operador, momento y auditoría.
- Se verificó cuatro ingresos más dos, reintento idempotente y el bloqueo del séptimo ingreso; la compilación y 37 pruebas pasaron.
- La consulta dedicada acepta el enlace/código leído sin consumir el pase; administración puede corregir un movimiento indicando motivo y dejando auditoría.
- La lectura por cámara no se impone como requisito local: cualquier lector que entregue el enlace QR puede usarse, y la búsqueda manual permanece disponible.

## Fase 6 — Planeación y decisiones

Estado: **completada en entorno local**.

- Agenda, tareas con prioridad y seguimiento auditado, participantes y recursos de operación.
- Propuestas con importe, decisión de pareja autorizada y versiones nuevas que no heredan aprobaciones anteriores.
- Menú, mesas, asignación de integrantes con validación de capacidad y reporte de restricciones para cocina.
- Recursos logísticos distinguen recomendaciones, gestión, reservas y confirmaciones; tablero de inspiración con referencias opcionales.
- Migraciones, compilación y 38 pruebas/115 aserciones ejecutadas correctamente.

## Fase 9 — Validación integral local y documentación

Estado: **completada en entorno local**.

- Rutas, migraciones y conexión MySQL local revisadas; documentación de recorridos y respaldo/restauración añadida.
- La publicación externa, cámara QR y transportes reales de mensajería permanecen fuera de este cierre local.

## Fase 10 — Preparación de producción y publicación

Estado: **preparación completada; publicación pendiente de autorización**.

- Procedimiento de despliegue, comprobaciones de hosting y reversión documentado.
- No se publicó el sistema ni se verificaron capacidades reales de Hostinger porque no se proporcionó acceso autorizado.

## Datos ficticios de validación

- `ScenarioDemoSeeder` carga escenarios reproducibles en MySQL local sin eliminar datos manuales ajenos.
- Credenciales y enlaces de demostración documentados en `docs/credenciales-demo.md`.
