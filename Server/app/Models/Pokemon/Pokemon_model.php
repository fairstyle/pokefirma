<?php

namespace App\Models\Pokemon;

use App\Libraries\FirmaAPI;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_model extends Model
{
    protected $table = 'pokefirma.pokemon';
    protected $primaryKey = 'pokemonId';
    protected $useAutoIncrement = false;
    protected $returnType = \stdClass::class;
    protected $protectFields = false;

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null, bool $isCacheOn = false)
    {
        parent::__construct($db, $validation);
        $this->setCache($isCacheOn);
    }

    public function getPokemon($pokemonId): \stdClass
    {
        return $this
            ->select("pokemonId")
            ->select("name")
            ->select("statsLoaded")
            ->find($pokemonId);
    }

    /**
     * @throws \ReflectionException
     */
    public function checkNewPokemon(ResponseInterface $response): bool
    {
        $firmapi = new FirmaAPI($response);

        // Recuperamos el conteo de pokemones de la API
        $temp_request = $firmapi->doRequest("GET", "https://pokeapi.co/api/v2/pokemon/?limit=1");
        $dbpokemon_newCount = json_decode($temp_request)->count ?? 0;

        $dbpokemon_actualCount = $this->getCache('dbpokemon_actualCount');
        if($dbpokemon_actualCount === null) {
            $dbpokemon_actualCount = 0;

            // Guardamos el conteo en cache por 10 año
            $this->saveCache('dbpokemon_actualCount', $dbpokemon_actualCount, 31557600 * 10);
        }

        $dbpokemon_newCount = (int)$dbpokemon_newCount;
        $dbpokemon_actualCount = (int)$dbpokemon_actualCount;

        if($dbpokemon_newCount <= $dbpokemon_actualCount)
            return false;

        $this->removeCache('dbpokemon_actualCount');

        $temp_request = $firmapi->doRequest("GET", "https://pokeapi.co/api/v2/pokemon/?limit={$dbpokemon_newCount}");
        $db_pokemon_list = json_decode($temp_request);

        $pokemonIds = $this
            ->select("pokemonId")
            ->findAll();

        $pokemonIds = array_column($pokemonIds, 'pokemonId');

        $newPokemon = [];
        foreach ($db_pokemon_list->results as $pokemon) {

            $pokemonId = explode("/", $pokemon->url);
            $pokemonId = $pokemonId[count($pokemonId) - 2];

            if(in_array($pokemonId, $pokemonIds))
                continue;


            $newPokemon[] = [
                'pokemonId' => $pokemonId,
                'name' => $pokemon->name
            ];
        }

        if(sizeof($newPokemon) > 0)
            $this->insertBatch($newPokemon);

        $this->saveCache('dbpokemon_actualCount', $dbpokemon_newCount, 31557600 * 10);

        return true;
    }

    public function loadPokemonStats(ResponseInterface $response, int $pokemonId): bool
    {
        $firmapi = new FirmaAPI($response);

        $temp_req = $firmapi->doRequest("GET", "https://pokeapi.co/api/v2/pokemon/{$pokemonId}");

        $temp_req = json_decode($temp_req);

        if($temp_req === null)
            return false;

        var_dump($temp_req->stats);
        die();
        return true;
    }
}