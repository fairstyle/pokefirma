<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Libraries\FirmaAPI;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Pokemon extends BaseController
{
    protected FirmaAPI $firmapi;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->firmapi = new FirmaAPI($response);
    }

    /**
     * Recupera un pokemon por su id
     * @param int $pokemonId
     * @return ResponseInterface
     */
    public function getPokemon(int $pokemonId): ResponseInterface
    {
        $pokemon_model = new \App\Models\Pokemon\Pokemon_model(null, null, true, $this->response);
        $pokemon = $pokemon_model->getPokemon($pokemonId);

        $pokemon->back = $pokemon_model->getOptsPokemon($pokemonId, "back");
        $pokemon->next = $pokemon_model->getOptsPokemon($pokemonId);

        return $this->firmapi->defaultResponseOk($pokemon);
    }


    /**
     * Recupera la cantidad de pokemones
     * @return ResponseInterface
     */
    public function getCountPokemons(): ResponseInterface
    {
        $pokemon_model = new \App\Models\Pokemon\Pokemon_model(null, null, true, $this->response);
        $count = $pokemon_model->getCountPokemons();

        return $this->firmapi->defaultResponseOk($count);
    }

    /**
     * Recupera todos los pokemones, en caso de agregar limit y offset, se pueden agregar como parametros en la url
     * @return ResponseInterface
     */
    public function getPokemons(): ResponseInterface
    {
        $pokemon_model = new \App\Models\Pokemon\Pokemon_model(null, null, true, $this->response);
        $model = $pokemon_model->getPokemon();

        $limit = $this->firmapi->getGet("limit") ?? 20;
        $offset = $this->firmapi->getGet("offset") ?? 0;

        // Filtro por tipos
        $orderBy = explode(",", str_replace(array("[", "]"), "", trim($this->firmapi->getGet("orderby") ?? "")));
        $types = explode(",", str_replace(array("[", "]"), "", trim($this->firmapi->getGet("types") ?? "")));

        if (is_array($types) && sizeof($types) > 0 && $types[0] !== "" && $orderBy[0] === "") {
            $pokemon_relation_pokemon_type_model = new \App\Models\Pokemon\Pokemon_relation_pokemon_type_model();
            $pokemon_relation_pokemon_type_model = $pokemon_relation_pokemon_type_model->getPokemonTypesByPokemonTypeId($types);
            $pokemon_relation_pokemon_type_model = array_column($pokemon_relation_pokemon_type_model, "pokemonId");
            $model = $pokemon_model->getPokemon($pokemon_relation_pokemon_type_model);
        }

        // Filtro por orden
        if (is_array($orderBy) && $orderBy[0] !== "") {
            // Cargar toda la info de los pokemones, no se puede ordenar por estadisticas si no se han cargado.
            $model->findAll();

            $pokemon_stats_model = new \App\Models\Pokemon\Pokemon_stats_model();
            $model = $pokemon_stats_model->getPokemonStats(null);

            foreach ($orderBy as $oby) {
                //orderby=[hp:1,attack:1]
                $oby = explode(":", $oby);

                if(sizeof($oby) === 2)
                    $model->orderBy($oby[0], $oby[1] === "1" ? "ASC" : "DESC");
            }

            if(sizeof($types) > 0 && $types[0] !== "")
                $model = $model
                    ->join("pokemon_relation_pokemon_type", "pokemon_relation_pokemon_type.pokemonId = pokemon_stats.pokemonId", "right")
                    ->whereIn("pokemon_relation_pokemon_type.pokemonTypeId", $types);

            $model = $model->findAll($limit, $offset);
            $modelIds = array_column($model, "pokemonId");

            $implodeIds = implode(",", $modelIds);
            return $this->firmapi->defaultResponseOk($pokemon_model->getPokemon($modelIds)->orderBy("find_in_set(pokemonId, '{$implodeIds}')", 'ASC', false)->findAll());
        }

        return $this->firmapi->defaultResponseOk($model->findAll($limit, $offset));
    }

    /**
     * Busca un pokemon por su nombre
     * @param string $pokemonName
     * @return ResponseInterface
     */
    public function findPokemon(string $pokemonName): ResponseInterface
    {
        $pokemon_model = new \App\Models\Pokemon\Pokemon_model(null, null, true, $this->response);
        $pokemon = $pokemon_model->findPokemon($pokemonName);

        return $this->firmapi->defaultResponseOk($pokemon);
    }
}