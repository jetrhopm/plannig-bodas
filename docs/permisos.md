# Matriz de permisos y aislamiento — Fase 0

## Regla de evaluación

Un permiso efectivo es la intersección de: identidad autenticada o enlace familiar válido, rol global, asignación a la boda y capacidad configurada para esa boda. La autorización se valida con Policies/Actions en backend para cada recurso y nunca solo con la interfaz.

Leyenda: **A** administra, **E** edita/ejecuta, **C** consulta, **P** según permiso explícito de la boda, **—** sin acceso.

| Recurso / acción | Administrador | Coordinador asignado | Finanzas asignado | Recepción asignada | Pareja | Responsable familiar |
| --- | --- | --- | --- | --- | --- | --- |
| Configuración global y empleados | A | — | — | — | — | — |
| Expediente de boda y entrevista | A | E | C económico | C operativo | C/P | — |
| Asignación de personal / permisos por boda | A | P delegado | — | — | — | — |
| Agenda, tareas y participantes | A | E | C si aplica | C del evento | C/P | C solo información publicada |
| Propuestas y decisiones | A | E | C económico | — | C/P aprobar/solicitar cambios | — |
| Costos internos, margen, negociación | A | P explícito | A | — | — por defecto | — |
| Cobros, pagos y archivos financieros | A | P explícito | A | — | C/P solo información económica autorizada | — |
| Familias, cupos y distribución de pases | A | E | — | C | C/P | E solo su familia y dentro del enlace |
| RSVP y restricciones familiares | A | E | — | C mínimo operativo | C/P | E solo su familia |
| Solicitudes de cupo | A | E | C cuando afecta importe | — | P resolver dentro de reglas | Crear/consultar su familia |
| Ampliación de cupo total | A | Proponer/E | C propuesta | — | Solicitar/aceptar si corresponde | — |
| Pase QR / datos de invitación | A | E | — | C | C/P | C solo pase de su familia |
| Registro de ingreso | A | E si asignado | — | E | — | — |
| Planeación visual, mesas, menú | A | E | — | C operativo | C/P | C solo información publicada |
| Regalos | A | E | — | — | C/P | C/E reservar dentro del enlace |
| Notificaciones | A | C de bodas asignadas | C financieras autorizadas | C operativas | C de su boda | C de su familia |
| Auditoría | A | C de sus acciones/bodas | C de sus acciones | C de sus acciones | C limitada a decisiones propias | — |

## Restricciones por contexto

- Un coordinador, finanzas o recepción solo puede operar bodas a las que esté asignado. El administrador puede asignar el acceso.
- Cada integrante de la pareja puede tener cuenta propia, pero ambas quedan restringidas a su única boda; sus capacidades configurables pueden diferir.
- El enlace familiar queda ligado a una sola familia y evento habilitado. No crea sesión de empleado, no permite entrada ni revela otras familias.
- Deshabilitar un módulo o permiso retira las acciones futuras, pero conserva información e historial; se debe mostrar el impacto antes de confirmar.
- Datos internos, contactos de terceros, alergias y notas no son parte de respuestas genéricas ni de avisos externos.

## Auditorías requeridas

Registrar actor, tipo/ID del sujeto, boda, antes/después estructurado, fecha y correlación de petición para: permisos, asignaciones, respuestas RSVP, solicitudes, resoluciones, ampliaciones, versiones/aprobaciones de propuestas, revocación de enlaces y movimientos de acceso. El cambio de un permiso debe explicar qué capacidades nuevas o retiradas produce sin modificar acuerdos existentes.
