# Modelo de datos y estados — Fase 0

## Convenciones

Todas las tablas de dominio tienen `id`, fechas de auditoría y claves foráneas. Las entidades de una boda contienen `wedding_id` (o se alcanzan inequívocamente por una relación ya filtrada) e índices compuestos que comienzan por ese alcance. Importes: decimal exacto y moneda ISO, nunca `float`. Fechas: instante UTC más zona IANA aplicable para presentación/reglas.

## Entidades principales

```text
users ──< wedding_members >── weddings ──< wedding_events
  │              │                 ├──< wedding_permissions
  │              ├── couple_profiles │
  │              └── employee_assignments
  │
weddings ──< families ──< family_members
              ├──< seat_assignments ──< rsvp_responses
              ├──< guest_passes ──< access_entries
              └──< additional_seat_requests

weddings ──< tasks, schedule_items, participants, proposals, proposal_versions
weddings ──< notifications, audit_logs, documents
```

| Área | Tablas iniciales | Notas/índices |
| --- | --- | --- |
| Identidad | `users`, `roles`, `permissions`, `role_permissions` | correo normalizado único; hash de contraseña. |
| Boda y acceso | `weddings`, `wedding_events`, `wedding_members`, `employee_assignments`, `wedding_permissions` | índice de pertenencia por `wedding_id,user_id`; evento principal explícito. |
| Configuración | `interviews`, `interview_answers`, `service_templates`, `wedding_services`, `responsibilities` | borrador reanudable y configuración versionable. |
| Familias | `families`, `family_members`, `seat_assignments`, `rsvp_responses`, `family_links` | token hashado y revocable; índices por boda y estado. |
| Solicitudes | `additional_seat_requests`, `capacity_change_requests`, `proposal_versions` | decisor, motivo visible e interno separados. |
| Pase/recepción | `guest_passes`, `access_entries`, `idempotency_keys` | pase por familia-evento; unicidad de referencia idempotente. |
| Planeación | `tasks`, `schedule_items`, `participants`, `proposals`, `proposal_versions`, `proposal_comments`, `table_plans`, `table_seats` | las aprobaciones pertenecen a versión, no al contenedor. |
| Finanzas | `vendors`, `vendor_services`, `quotes`, `payments`, `charges` | fase posterior; enlace extensible a ampliaciones. |
| Comunicación | `notifications`, `notification_deliveries`, `reminder_jobs` | evento/destinatario/canal único para deduplicar. |
| Regalos | `gift_items`, `gift_reservations` | cantidad reservada bajo transacción. |
| Transversal | `audit_logs`, `documents` | no exponer documentos sin Policy. |

## Capacidad, RSVP y QR

`wedding_events` guarda `authorized_capacity` nullable y `operational_capacity` nullable; el primero define disponibilidad del evento y el segundo solo sirve como restricción/aviso cuando existe. `seat_assignments` conserva cantidad original, cantidad vigente y un historial de ajustes. Pendiente mantiene lugares reservados; aceptar menos reduce la cantidad vigente y devuelve la diferencia; rechazar deja la vigente en cero. Una nueva respuesta no recompone lugares liberados sin disponibilidad y autorización.

`rsvp_responses.status`: `pending`, `accepted`, `declined`, `partial_accepted`, `change_requested`. La respuesta guarda cantidad confirmada y el responsable incluido solo si se indicó; no se suma automáticamente.

`additional_seat_requests.status`: `pending`, `approved`, `rejected`, `cancelled`, `expired` (no automatizar expiración inicialmente). Aprobar aumenta asignación vigente, no confirmados ni ingreso habilitado.

`guest_passes.status`: `active`, `revoked`, `completed`; su elegibilidad de acceso se deriva de asignación/RSVP/evento, no de un QR estático. `access_entries` registra cantidad positiva, empleado, evento, marca temporal y clave de idempotencia. Una corrección requiere tipo explícito, motivo y auditoría, preservando el movimiento original.

## Estados de dominio

| Entidad | Estados / transición controlada |
| --- | --- |
| Boda | `consultation → quoted → contracted → preparing → completed`; `cancelled` desde estados no terminados con auditoría. |
| Propuesta | `draft → pending_decision → approved` o `changes_requested`/`rejected`; nueva versión vuelve a pendiente. |
| Tarea | `pending → in_progress → completed` o `cancelled`; vencimiento es atributo, no sustituto de estado. |
| Notificación | `queued → delivered`/`failed`; lectura independiente `unread/read`. |
| Regalo | `available → reserved → purchased_or_delivered`; una reserva puede liberarse. |

## Integridad y concurrencia

Las mutaciones de asignación, resolución de cupo, entrada y reserva de regalo usan transacciones en MySQL/MariaDB y bloqueo de filas del evento/asignación relevante. Validar de nuevo dentro de la transacción, emplear FKs y `CHECK` solo si el motor confirmado las aplica de forma fiable; las invariantes críticas también se ejecutan en servicio. Las pruebas de concurrencia se harán contra el motor final, no solo SQLite.
