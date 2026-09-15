# Arquitectura — Fase 0

## Estado observado

Al 2026-09-14 se creó y verificó el esqueleto Laravel 12.69 con Breeze, Inertia, Vue 3, Vite y Tailwind. Migraciones, seeder local, compilación y pruebas se ejecutaron correctamente sobre SQLite. La conexión MariaDB/MySQL espera la base y credenciales que proporcione el propietario.

## Decisiones técnicas

| Decisión | Elección | Motivo / límite |
| --- | --- | --- |
| Forma de aplicación | Monolito modular Laravel | Una empresa, muchas bodas; evita complejidad de microservicios y multiempresa. |
| Servidor | Laravel/PHP en versión estable compatible con el entorno inspeccionado | La versión concreta se decide después de comprobar PHP, Composer y extensiones. |
| Cliente | Vue 3, Composition API y TypeScript estricto mediante Inertia | Una sola aplicación con rutas y autorización del servidor. |
| Estilos | Tailwind CSS y shadcn-vue adaptado a identidad propia | Componentes accesibles y consistentes, sin depender de un tema externo. |
| Persistencia | MySQL o MariaDB compatible, Eloquent, migraciones y transacciones | La variante y versión local deben confirmarse antes de crear migraciones que dependan de características específicas. |
| Autenticación | Sesiones Laravel, cookies seguras, CSRF y hash de contraseñas | No se usará JWT en navegador sin un consumidor API que lo justifique. |
| Tiempo | Instantes UTC persistidos; `America/Mexico_City` configurable por negocio/boda | La UI calcula cuentas regresivas desde la fecha real, sin negativos. |
| Trabajo asíncrono | Cola y planificador Laravel; notificaciones después de `commit` | En local se documentará un worker/planificador; producción depende de Hostinger verificado. |
| Integraciones | Puertos/adaptadores de correo y WhatsApp, transporte local explícito | No se simulará entrega real sin credenciales e integración. |

## Límites de módulos

```text
Núcleo: Identidad, autorización, bodas, auditoría, archivos, configuración
 ├─ Entrevista y configuración
 ├─ Planeación (agenda, tareas, participantes, propuestas)
 ├─ Familias y pases
 │   ├─ Invitación / RSVP
 │   ├─ Solicitudes de cupo
 │   └─ Recepción QR
 ├─ Proveedores y finanzas
 ├─ Regalos
 └─ Comunicaciones y notificaciones
```

Dependencias permitidas: recepción depende de pases y autorización; RSVP depende de familias/pases; solicitudes dependen de pases y disponibilidad; comunicaciones escucha eventos publicados tras una transacción. Planeación, regalos y finanzas no bloquean RSVP ni recepción. Finanzas podrá extender una propuesta mínima de ampliación sin reemplazar su identidad ni crear un segundo flujo incompatible.

## Implementación actual de Fase 2

`wedding_interviews` conserva cada entrevista como borrador reanudable; `wedding_services` y `wedding_responsibilities` separan los servicios contratados de las responsabilidades operativas. `wedding_audits` registra actor, cambio previo y posterior para entrevista, evento y permisos.

Los eventos conservan `event_date` y `event_time` para la experiencia de negocio, y `starts_at` como instante UTC para el cálculo real de la cuenta regresiva. Si falta fecha u hora, la interfaz lo indica explícitamente; al alcanzarse el inicio muestra que el evento inició, sin inferir que terminó.

## Contratos de aplicación mínimos

Controladores delgados delegan reglas a Actions/Services y validan mediante Form Requests. Policies centralizan autorización por recurso y boda. Las Actions que alteren cupos, reservas o ingresos abren una transacción y bloquean los registros de capacidad/afectados antes de recalcular.

| Acción | Precondiciones | Resultado único y auditable |
| --- | --- | --- |
| `ResponderRSVP` | enlace familiar vigente, asignación vigente | guarda respuesta, ajusta solo la asignación vigente y publica evento tras commit |
| `ResolverSolicitudCupo` | permiso efectivo, solicitud pendiente | revalida disponibilidad bajo bloqueo; aprueba/rechaza con decisor y motivo |
| `RegistrarIngreso` | empleado autorizado, pase confirmado, evento correcto | movimiento idempotente por referencia de petición; nunca supera confirmados |
| `CambiarPermisosBoda` | administrador autorizado | persiste antes/después, actor y fecha; no borra datos de módulos deshabilitados |
| `VersionarPropuesta` | autor autorizado | la nueva versión nace pendiente; no hereda aprobación |

Todas las consultas y mutaciones de un recurso de boda deben partir del alcance de boda autorizado en servidor, no de un ID enviado por cliente ni de botones ocultos.

## Recorridos iniciales

1. Empresa: iniciar sesión → ver bodas asignadas, pendientes y avisos → abrir expediente autorizado.
2. Pareja: iniciar sesión → abrir exclusivamente su boda → consultar avances y ejecutar únicamente acciones configuradas.
3. Familia: abrir enlace opaco → consultar invitación → confirmar/rechazar/solicitar dentro de su asignación → ver comprobante.
4. Recepción: empleado autenticado → escanear o buscar pase → consultar estado → registrar cantidad con confirmación propia.

## Seguridad y privacidad

- Los tokens QR y enlaces familiares serán opacos, de alta entropía, revocables y no contendrán PII, alergias ni secretos.
- Archivos se validarán por tipo/tamaño y se servirán con autorización; las notas internas, costos y salud son privados por defecto.
- Auditoría inmutable de cambios de permisos, RSVP, cupos, aprobaciones y entradas; no registrar tokens completos ni datos de salud en logs.
- Las notificaciones se deduplican por evento de dominio/entrega y nunca revierten la operación de negocio si falla el transporte.

## Invariantes críticos

`disponibles = cupo_total_autorizado - suma(asignaciones_familiares_vigentes)`.

`ingresos_registrados <= confirmados_vigentes <= asignacion_familiar_vigente`.

Una confirmación es estado de una asignación, no una segunda reserva. Solicitar lugares no reserva ni habilita ingreso. Una reducción no puede dejar una asignación menor que sus ingresos. Los cambios de fecha deben reprogramar recordatorios y recalcular la cuenta regresiva desde datos del servidor.
