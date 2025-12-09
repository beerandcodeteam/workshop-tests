<?php

namespace App\Http\Controllers;

use App\Services\CepService;
use Illuminate\Http\JsonResponse;

class CepController extends Controller
{
    public function __construct(
        protected CepService $cepService
    ) {}

    public function search(string $cep): JsonResponse
    {
        $result = $this->cepService->search($cep);

        if (! $result['success']) {
            return response()->json([
                'error' => $result['error'],
            ], $result['status']);
        }

        return response()->json($result['data']);
    }
}
