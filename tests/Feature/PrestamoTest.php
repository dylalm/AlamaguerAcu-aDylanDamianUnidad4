<?php

use App\Models\Herramienta;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('marca la herramienta como ocupada por quien la pide', function () {
    $dueno = User::factory()->create();
    $vecino = User::factory()->create(['name' => 'Ana Torres']);
    $herramienta = Herramienta::factory()->for($dueno, 'propietario')->create();

    $this->actingAs($vecino)
        ->post(route('herramientas.prestamo', $herramienta))
        ->assertRedirect();

    $herramienta->refresh();

    expect($herramienta->disponible)->toBeFalse();
    expect($herramienta->prestado_a)->toEqual($vecino->id);
});

it('el catálogo muestra el nombre de quien ocupa la herramienta', function () {
    $dueno = User::factory()->create();
    $vecino = User::factory()->create(['name' => 'Ana Torres']);
    Herramienta::factory()->for($dueno, 'propietario')->ocupadaPor($vecino)->create();

    $this->actingAs($vecino)
        ->get(route('herramientas.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->where('herramientas.0.disponible', false)
            ->where('herramientas.0.ocupada_por', 'Ana Torres'));
});

it('no deja pedir una herramienta que ya está ocupada', function () {
    $dueno = User::factory()->create();
    $primero = User::factory()->create();
    $segundo = User::factory()->create();
    $herramienta = Herramienta::factory()->for($dueno, 'propietario')->ocupadaPor($primero)->create();

    $this->actingAs($segundo)
        ->post(route('herramientas.prestamo', $herramienta))
        ->assertSessionHasErrors('prestamo');

    expect($herramienta->fresh()->prestado_a)->toEqual($primero->id);
});

it('no deja pedir prestada la propia herramienta', function () {
    $dueno = User::factory()->create();
    $herramienta = Herramienta::factory()->for($dueno, 'propietario')->create();

    $this->actingAs($dueno)
        ->post(route('herramientas.prestamo', $herramienta))
        ->assertSessionHasErrors('prestamo');

    expect($herramienta->fresh()->disponible)->toBeTrue();
});

it('exige iniciar sesión para pedir una herramienta', function () {
    $herramienta = Herramienta::factory()->create();

    $this->post(route('herramientas.prestamo', $herramienta))
        ->assertRedirect(route('login'));

    expect($herramienta->fresh()->disponible)->toBeTrue();
});

it('al marcarla como disponible, el dueño libera a quien la tenía', function () {
    $dueno = User::factory()->create();
    $vecino = User::factory()->create();
    $herramienta = Herramienta::factory()->for($dueno, 'propietario')->ocupadaPor($vecino)->create();

    $this->actingAs($dueno)
        ->patch(route('herramientas.disponibilidad', $herramienta))
        ->assertRedirect();

    $herramienta->refresh();

    expect($herramienta->disponible)->toBeTrue();
    expect($herramienta->prestado_a)->toBeNull();
});

it('solo una persona consigue la herramienta cuando dos la piden', function () {
    $dueno = User::factory()->create();
    $a = User::factory()->create();
    $b = User::factory()->create();
    $herramienta = Herramienta::factory()->for($dueno, 'propietario')->create();

    expect($herramienta->prestarA($a))->toBeTrue();
    expect($herramienta->prestarA($b))->toBeFalse();
    expect($herramienta->fresh()->prestado_a)->toEqual($a->id);
});
