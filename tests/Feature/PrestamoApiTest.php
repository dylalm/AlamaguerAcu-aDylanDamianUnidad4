<?php

use App\Models\Herramienta;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('permite pedir prestada una herramienta con token', function () {
    $dueno = User::factory()->create();
    $vecino = User::factory()->create(['name' => 'Ana Torres']);
    $herramienta = Herramienta::factory()->for($dueno, 'propietario')->create();
    Sanctum::actingAs($vecino);

    $this->postJson("/api/v1/herramientas/{$herramienta->id}/prestamo")
        ->assertOk()
        ->assertJsonPath('data.disponible', false)
        ->assertJsonPath('data.ocupada_por', 'Ana Torres');
});

it('responde 409 si la herramienta ya está ocupada', function () {
    $dueno = User::factory()->create();
    $primero = User::factory()->create();
    $segundo = User::factory()->create();
    $herramienta = Herramienta::factory()->for($dueno, 'propietario')->ocupadaPor($primero)->create();
    Sanctum::actingAs($segundo);

    $this->postJson("/api/v1/herramientas/{$herramienta->id}/prestamo")->assertStatus(409);
});

it('responde 422 si el dueño pide su propia herramienta', function () {
    $dueno = User::factory()->create();
    $herramienta = Herramienta::factory()->for($dueno, 'propietario')->create();
    Sanctum::actingAs($dueno);

    $this->postJson("/api/v1/herramientas/{$herramienta->id}/prestamo")->assertStatus(422);
});

it('exige token para pedir prestada una herramienta', function () {
    $herramienta = Herramienta::factory()->create();

    $this->postJson("/api/v1/herramientas/{$herramienta->id}/prestamo")->assertUnauthorized();
});

it('la API muestra quién ocupa cada herramienta', function () {
    $vecino = User::factory()->create(['name' => 'Ana Torres']);
    $herramienta = Herramienta::factory()->ocupadaPor($vecino)->create();

    $this->getJson("/api/v1/herramientas/{$herramienta->id}")
        ->assertOk()
        ->assertJsonPath('data.ocupada_por', 'Ana Torres');
});
