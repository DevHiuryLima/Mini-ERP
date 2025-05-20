<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ViaCepService
{
    protected string $baseUrl = 'https://viacep.com.br/ws';

    /**
     * Busca um CEP e retorna o JSON decodificado.
     *
     * @param  string  $cep
     * @return array|null
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function consultaViaCep(string $cep): ?array
    {
        $cepLimpo = preg_replace('/\D/', '', $cep);

        $response = Http::timeout(5)->get("https://viacep.com.br/ws/{$cepLimpo}/json")->throw();

        $data = $response->json();

        return isset($data['erro']) ? null : $data;
    }
}
