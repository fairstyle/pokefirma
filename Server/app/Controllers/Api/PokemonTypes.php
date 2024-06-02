<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Libraries\FirmaAPI;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class PokemonTypes extends BaseController
{
    protected FirmaAPI $firmapi;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->firmapi = new FirmaAPI($response);
    }

    /**
     * Recupera todos los tipos de pokemon
     * @param int $pokemonId
     * @return ResponseInterface
     */
    public function getTypes(): ResponseInterface
    {
        $pokemontype_model = new \App\Models\Pokemon\Pokemon_type_model();
        $pokemontype = $pokemontype_model->getPokemonTypes();

        return $this->firmapi->defaultResponseOk($pokemontype);
    }
}