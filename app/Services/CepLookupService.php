<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class CepLookupService
{
    private const ENDPOINT = 'https://viacep.com.br/ws/%s/json/';

    /**
     * @return array{cep:string,rua:string,bairro:string,cidade:string,estado:string}
     */
    public function lookup(string $cep): array
    {
        $normalizedCep = preg_replace('/\D+/', '', $cep) ?? '';

        if (strlen($normalizedCep) !== 8) {
            throw ValidationException::withMessages([
                'cep' => 'O CEP deve conter 8 dígitos.',
            ]);
        }

        $response = Http::acceptJson()
            ->timeout(10)
            ->get(sprintf(self::ENDPOINT, $normalizedCep));

        if (! $response->successful()) {
            throw ValidationException::withMessages([
                'cep' => 'Não foi possível consultar o CEP informado.',
            ]);
        }

        $payload = $response->json();

        if (! is_array($payload) || ($payload['erro'] ?? false)) {
            throw ValidationException::withMessages([
                'cep' => 'CEP não encontrado.',
            ]);
        }

        return [
            'cep' => $normalizedCep,
            'rua' => trim((string) ($payload['logradouro'] ?? '')),
            'bairro' => trim((string) ($payload['bairro'] ?? '')),
            'cidade' => trim((string) ($payload['localidade'] ?? '')),
            'estado' => strtoupper(trim((string) ($payload['uf'] ?? ''))),
        ];
    }
}
