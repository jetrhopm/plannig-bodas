# Demo local — Fase 3

Después de ejecutar `php artisan migrate:fresh --seed`, inicie sesión como `admin@local.test` / `password`, abra Sofía & Daniel y luego **Familias**.

Escenarios sembrados:

- Familia García: recibió 6 pases, confirmó 4; su asignación vigente es 4 y conserva 6 como asignación original.
- Familia Torres: 3 pases pendientes; siguen reservados porque el silencio no se interpreta como rechazo.
- Familia Cruz, de otra boda: rechazo y liberación de sus 2 pases.

Enlaces locales de prueba (no usar fuera de `local`):

- `/invitacion/demo-torres-pending`
- `/invitacion/demo-garcia-six-to-four`

El token se guarda cifrado mediante hash en la base; los valores anteriores son solamente semillas conocidas de demostración. Los enlaces creados desde el panel se muestran una sola vez en el mensaje de confirmación y no se almacenan en texto plano.
