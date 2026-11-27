# Préstamo de herramientas

Cuando un vecino pide prestada una herramienta, queda marcada como **ocupada** a su nombre.

| Qué | Dónde |
|-----|-------|
| Columna `prestado_a` (vecino que la tiene) | Migración `add_prestado_a_to_herramientas_table` |
| Regla de negocio (operación atómica) | `Herramienta::prestarA()` en `app/Models/Herramienta.php` |
| Pedir desde la pantalla | `POST /herramientas/{id}/prestamo` → `PrestamoController` |
| Pedir desde la API | `POST /api/v1/herramientas/{id}/prestamo` → `Api\PrestamoController` |
| Liberar la herramienta | El dueño pulsa «Marcar como disponible» en su panel |

Reglas:

- Hay que iniciar sesión para pedir una herramienta.
- No se puede pedir una herramienta ocupada (la pantalla avisa y la API responde `409`).
- El dueño no puede pedir su propia herramienta (la API responde `422`).
- Si dos vecinos la piden al mismo tiempo, la base de datos garantiza que solo uno la consigue.

## Cómo demostrarlo (capturas)

1. Iniciar sesión como `carlos@manovecina.test` (contraseña `password`) y abrir `/herramientas`.
2. Pulsar «Pedir prestada» en una herramienta de otro vecino: el cuadro naranja confirma y la tarjeta cambia a «Ocupada por Carlos Ibarra».
3. Iniciar sesión como el dueño y abrir `/dashboard`: en «Mis herramientas» aparece «Ocupada por Carlos Ibarra».
4. Intentar pedir la misma herramienta con otra cuenta: aparece el aviso de que ya está ocupada.
5. En MySQL: `SELECT h.nombre, u.name AS ocupada_por FROM herramientas h LEFT JOIN users u ON u.id = h.prestado_a;`
6. Pruebas: `php artisan test --filter=Prestamo`
