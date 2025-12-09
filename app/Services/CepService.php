<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CepService
{
    public function search(string $cep): array
    {
        $cleanCep = preg_replace('/[^0-9]/', '', $cep);

        if (strlen($cleanCep) !== 8) {
            return [
                'success' => false,
                'error' => 'CEP inválido. Deve conter 8 dígitos.',
                'status' => 422,
            ];
        }

        try {
            $response = Http::timeout(10)
                ->get("https://viacep.com.br/ws/{$cleanCep}/json/");

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'error' => 'Erro ao buscar CEP. Tente novamente.',
                    'status' => 500,
                ];
            }

            $data = $response->json();

            if (isset($data['erro']) && $data['erro'] === true) {
                return [
                    'success' => false,
                    'error' => 'CEP não encontrado.',
                    'status' => 404,
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'street' => $data['logradouro'] ?? '',
                    'neighborhood' => $data['bairro'] ?? '',
                    'city' => $data['localidade'] ?? '',
                    'state' => $data['uf'] ?? '',
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Erro ao buscar CEP. Verifique sua conexão.',
                'status' => 500,
            ];
        }
    }
}
