# Plan de ejecución por fases

## Dependencias

```text
F0 Descubrimiento → F1 Base técnica → F2 Expediente/configuración
                                      ├→ F3 Familias/RSVP → F4 Solicitudes → F5 QR/recepción
                                      ├→ F6 Planeación
                                      └→ F7 Finanzas
F3/F2 → F8 Regalos y comunicaciones ampliadas → F9 Validación integral → F10 Publicación
```

| Fase | Entrega verificable | Dependencias y criterio de salida |
| --- | --- | --- |
| 0 | Especificación, arquitectura, modelo, permisos, rutas de usuario y guía de entorno | Inspección de PHP/Composer/Node/DB/puertos y esqueleto Laravel arrancando; pendientes documentados sin inventar credenciales. |
| 1 | Autenticación, roles, bodas mínimas, layout y avisos base | Inicio por cada rol, Policies backend y aislamiento probado. |
| 2 | Expediente, entrevista reanudable, configuración y cuenta regresiva | Dos bodas configurables, historial de permisos y reprogramación consistente. |
| 3 | Familias, enlaces privados, RSVP, liberación y avisos | Caso 6→4, rechazo, idempotencia y aislamiento del enlace. |
| 4 | Solicitudes de cupo y ampliaciones versionadas mínimas | Revalidación concurrente y ninguna solicitud concede cupo/entrada por sí sola. |
| 5 | QR por familia y recepción | Entradas 4+2 de 6, idempotencia, revocación y evento correcto. |
| 6 | Planeación, agenda, propuestas, menú, mesas | Aprobación versionada y reporte operativo consistente. |
| 7 | Proveedores/finanzas y extensión de ampliaciones | Saldos y visibilidad financiera autorizada. |
| 8 | Regalos y adaptadores de comunicación ampliados | Reserva concurrente segura; fallos externos no revierten el negocio. |
| 9 | Pruebas integrales, accesibilidad, respaldo/documentación | Recorrido entrevista→recepción sin defectos críticos conocidos. |
| 10 | Preparación/publicación, solo con solicitud y accesos | Capacidades de hosting verificadas y reversión documentada. |

## Fase 0 — tareas concretas restantes

1. Inspeccionar versiones de PHP, extensiones, Composer, Node/npm, Vite, motor/puerto de BD y servicios XAMPP.
2. Crear/especificar el esqueleto Laravel solo si el directorio está destinado a este proyecto; no sobrescribir otros proyectos.
3. Configurar `.env` local exclusivamente con la contraseña que confirme el propietario; mantener `.env.example` ficticio con `DB_PASSWORD=<CONFIGURAR_LOCALMENTE>`.
4. Demostrar `php artisan`/servidor, migración sobre base de prueba y compilación frontend. Registrar comandos realmente ejecutados y resultados.
5. Decidir versiones exactas a partir de los hallazgos y actualizar esta documentación.

## Riesgos y estrategia

- MariaDB puede diferir de MySQL en restricciones/índices: verificar la versión antes de diseñar SQL específico.
- El hosting puede carecer de worker persistente o cron: los recordatorios deben tener alternativa de cron compatible y no se activan sin evidencia.
- Cámara QR puede requerir HTTPS o permisos: recepción mantiene búsqueda manual.
- Las integraciones de correo/WhatsApp son opcionales y se muestran como no configuradas hasta validarlas.
