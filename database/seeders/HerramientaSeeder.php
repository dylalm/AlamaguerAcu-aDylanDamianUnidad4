<?php

namespace Database\Seeders;

use App\Models\Herramienta;
use App\Models\User;
use Illuminate\Database\Seeder;

class HerramientaSeeder extends Seeder
{
    public function run(): void
    {
        $centro = config('manovecina.centro');

        $vecinos = collect([
            ['Lucía Mendoza', 'lucia@manovecina.test'],
            ['Carlos Ibarra', 'carlos@manovecina.test'],
            ['Rosa Villarreal', 'rosa@manovecina.test'],
            ['Mateo Salinas', 'mateo@manovecina.test'],
        ])->map(fn (array $datos) => User::firstOrCreate(
            ['email' => $datos[1]],
            ['name' => $datos[0], 'password' => bcrypt('password'), 'email_verified_at' => now()],
        ));

        $porCorreo = $vecinos->keyBy('email');

        // nombre, descripción, categoría, barrio, desplazamiento lat, desplazamiento lng, disponible, ocupada por (correo)
        $herramientas = [
            ['Taladro inalámbrico', 'Incluye dos baterías y un juego de brocas para pared y madera.', 'construccion', 'Col. Jardines', 0.004, 0.002, true, null],
            ['Escalera de tijera', 'Aluminio, 2 metros. Ideal para pintar o cambiar focos en techos altos.', 'hogar', 'Col. Jardines', -0.003, 0.004, true, null],
            ['Podadora eléctrica', 'Cable de 15 metros incluido. Corta pasto de jardines chicos y medianos.', 'jardin', 'Fracc. Las Palmas', 0.009, -0.006, true, null],
            ['Tijeras de podar', 'Para ramas de hasta 3 cm. Se entregan afiladas y limpias.', 'jardin', 'Col. Centro', 0.001, -0.001, false, 'lucia@manovecina.test'],
            ['Batidora de pie', 'Potente, con tres aspas. Buena para masas y merengues.', 'cocina', 'Fracc. Las Palmas', -0.008, -0.004, true, null],
            ['Multímetro digital', 'Mide voltaje, corriente y continuidad. Con puntas nuevas.', 'electricidad', 'Col. Centro', 0.002, 0.005, true, null],
            ['Juego de llaves combinadas', '24 piezas, métricas y en pulgadas, con estuche.', 'construccion', 'Col. Lomas', 0.014, 0.010, true, null],
            ['Hidrolavadora', 'Para lavar cochera, patio o coche. Incluye manguera y boquillas.', 'hogar', 'Col. Lomas', -0.012, 0.011, false, 'rosa@manovecina.test'],
            ['Olla de presión grande', '10 litros, para preparar comida para reuniones familiares.', 'cocina', 'Col. Jardines', 0.003, -0.003, true, null],
            ['Carretilla', 'Con llanta neumática. Útil para mover tierra, escombro o macetas.', 'jardin', 'Fracc. Las Palmas', -0.006, 0.008, true, null],
        ];

        foreach ($herramientas as $i => [$nombre, $descripcion, $categoria, $barrio, $difLat, $difLng, $disponible, $ocupadaPor]) {
            Herramienta::updateOrCreate(
                ['nombre' => $nombre],
                [
                    'user_id' => $vecinos[$i % $vecinos->count()]->id,
                    'descripcion' => $descripcion,
                    'categoria' => $categoria,
                    'barrio' => $barrio,
                    'latitud' => $centro['latitud'] + $difLat,
                    'longitud' => $centro['longitud'] + $difLng,
                    'disponible' => $disponible,
                    'prestado_a' => $ocupadaPor ? $porCorreo[$ocupadaPor]->id : null,
                ],
            );
        }
    }
}
