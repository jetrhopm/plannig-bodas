# Validación local — Fase 9

## Evidencia ejecutada

- Motor activo: MySQL 8.0.41 local, conexión `mysql`.
- 32 migraciones de negocio aplicadas en la base `boda`.
- `npm run build` y `php artisan test` finalizaron correctamente; la suite contiene 40 pruebas y 132 aserciones.
- El listado de rutas contiene 72 rutas, con las operaciones sensibles dentro del grupo `auth`.

## Recorridos manuales a repetir

1. Empresa: iniciar como `admin@local.test`, abrir una boda, administrar familias, planeación, finanzas, regalos y recepción.
2. Pareja: iniciar como `pareja@local.test`; comprobar que solo ve su boda, sus conceptos financieros compartidos y propuestas permitidas.
3. Invitado: abrir una URL de `docs/demo-fase-3.md`, confirmar asistencia, solicitar lugares y consultar su pase QR.
4. Recepción: iniciar como `recepcion@local.test`, registrar entradas parciales y comprobar que no se exceden los confirmados.

## Límites verificados

- Invitaciones usan tokens opacos; no permiten registrar ingresos.
- Costos internos no se muestran a pareja sin visibilidad explícita.
- Reservas de regalo, cupos y entradas aplican validaciones transaccionales.
- Correo y WhatsApp son simulados localmente; ningún intento afirma entrega externa.
