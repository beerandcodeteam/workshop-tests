<?php

use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->user = \App\Models\User::factory()->create();
});

test('user can search for valid cep', function () {
    Http::fake([
        'https://viacep.com.br/*' => Http::response([
            'cep' => '01310-100',
            'logradouro' => 'Avenida Paulista',
            'complemento' => '',
            'bairro' => 'Bela Vista',
            'localidade' => 'São Paulo',
            'uf' => 'SP',
            'ibge' => '3550308',
            'gia' => '1004',
            'ddd' => '11',
            'siafi' => '7107',
        ], 200),
    ]);

    $this->actingAs($this->user)
        ->get(route('api.cep.search', '01310-100'))
        ->assertStatus(200)
        ->assertSuccessful();

    Http::assertSent(function ($request) {
        return str_contains($request->url(), '01310100');
    });
});

test('search cep returns error for non-existent cep', function () {
    Http::fake([
        'https://viacep.com.br/*' => Http::response([
            'error' => 'CEP não encontrado.',
        ], 200)
    ]);

    $this->actingAs($this->user)
        ->get(route('api.cep.search', '00000-000'))
        ->assertStatus(200)
        ->assertJson([
            "street" => "",
            "neighborhood" => "",
            "city" => "",
            "state" => "",
        ]);
});
