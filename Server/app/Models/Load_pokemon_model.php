<?php

namespace App\Models;

use App\Libraries\FirmaAPI;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Load_pokemon_model extends Model
{
    protected $table = 'pokefirma.pokemon';
    protected $primaryKey = 'pokemonId';
    protected $useAutoIncrement = false;
    protected $returnType = \stdClass::class;
    protected $protectFields = false;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null, bool $isCacheOn = false, ResponseInterface $response = null)
    {
        parent::__construct($db, $validation);
        $this->setCache($isCacheOn);
    }

    /**
     * Verifica si existen nuevos pokemones en la API y los agrega a la base de datos
     * @param ResponseInterface $response
     * @return bool
     * @throws \ReflectionException
     */
    public function checkNewPokemon(ResponseInterface $response): bool
    {
        // Validamos si existe la flag que indica que se debe verificar si existen nuevos pokemones
        $checkNow = $this->getCache('dbpokemon_checkNow');
        if($checkNow !== null)
            return false;

        //En caso de que no se haya encontrado la flag, procedemos a buscar nuevos pokemones en la API
        $firmapi = new FirmaAPI($response);

        // Recuperamos el conteo de pokemones de la API
        $temp_request = $firmapi->doRequest("GET", getenv("POKEAPI_URL")."/pokemon/?limit=1");
        $dbpokemon_newCount = json_decode($temp_request)->count ?? 0;

        // Recuperamos la cantidad de pokemones actuales en la base de datos
        $dbpokemon_actualCount = $this->getCache('dbpokemon_actualCount');
        if($dbpokemon_actualCount === null) {
            $dbpokemon_actualCount = 0;

            // Guardamos el conteo en cache por 10 año, porque 10 años? porque si
            $this->saveCache('dbpokemon_actualCount', $dbpokemon_actualCount, 31557600 * 10);
        }

        // Guardamos una flag para que no se validen si existen nuevos pokemones durante 24 horas
        $this->saveCache('dbpokemon_checkNow', "true", 86400);

        // Si la cantidad de pokemones en la API es menor o igual a la cantidad de pokemones en la base de datos, no se realiza ninguna acción
        if((int)$dbpokemon_newCount <= (int)$dbpokemon_actualCount)
            return false;

        // Si la cantidad de pokemones en la API es mayor a la cantidad de pokemones en la base de datos, se procede a buscar los nuevos pokemones y eliminamos la cache de conteo de pokemones
        $this->removeCache('dbpokemon_actualCount');

        // hacemos la consulta a la API para recuperar los nuevos pokemones
        $temp_request = $firmapi->doRequest("GET", getenv("POKEAPI_URL")."/pokemon/?limit={$dbpokemon_newCount}");
        $db_pokemon_list = json_decode($temp_request);

        // Recuperamos los pokemones actuales en la base de datos
        $pokemonIds = $this
            ->select("pokemonId")
            ->findAll();

        $pokemonIds = array_column($pokemonIds, 'pokemonId');
        $newPokemon = [];

        foreach ($db_pokemon_list->results as $pokemon) {

            $pokemonId = explode("/", $pokemon->url);
            $pokemonId = $pokemonId[count($pokemonId) - 2];

            // Si el pokemon ya existe en la base de datos, se omite
            if(in_array($pokemonId, $pokemonIds))
                continue;

            $newPokemon[] = [
                'pokemonId' => $pokemonId,
                'name' => $pokemon->name
            ];
        }

        // Si existen nuevos pokemones, se insertan en la base de datos
        if(sizeof($newPokemon) > 0) {
            $this->insertBatch($newPokemon);
            self::loadPokemonTypes($firmapi);
        }

        // Guardamos el nuevo conteo de pokemones en cache por 10 años xd
        $this->saveCache('dbpokemon_actualCount', $dbpokemon_newCount, 31557600 * 10);

        return true;
    }


    /**
     * Carga los tipos de pokemones en la base de datos
     * @param FirmaAPI $firmapi
     * @return void
     * @throws \ReflectionException
     */
    public function loadPokemonTypes(FirmaAPI $firmapi): void
    {
        $temp_request = $firmapi->doRequest("GET", getenv("POKEAPI_URL") . "/type");
        $dbpokemontype = json_decode($temp_request);

        $pokemon_type_model = new \App\Models\Pokemon\Pokemon_type_model();
        $pokemon_type_data = $pokemon_type_model->getPokemonTypes();

        $pokemonTypeId = array_column($pokemon_type_data, 'pokemonTypeId');
        $newPokemonTypes = [];

        foreach ($dbpokemontype->results as $pokemontype) {

            $newPokemonTypeId = explode("/", $pokemontype->url);
            $newPokemonTypeId = $newPokemonTypeId[count($newPokemonTypeId) - 2];

            // Si el pokemon type ya existe en la base de datos, se omite
            if(in_array($newPokemonTypeId, $pokemonTypeId))
                continue;

            $newPokemonTypes[] = [
                'pokemonTypeId' => $newPokemonTypeId,
                'name' => $pokemontype->name
            ];
        }

        // Si existen nuevos tipos de pokemones, se insertan en la base de datos
        if(sizeof($newPokemonTypes) > 0)
            $pokemon_type_model->insertPokemonTypes($newPokemonTypes);
    }


    /**
     * Carga los stats de un pokemon en caso de que no existan en la base de datos y si existen los actualiza
     * @param int $pokemonId
     * @param ResponseInterface $response
     * @return bool
     * @throws \ReflectionException
     */
    public function loadPokemon(int $pokemonId, ResponseInterface $response): bool
    {
        $firmapi = new FirmaAPI($response);

        // hace la consulta a la api por 1 pokemon especifico
        $temp_req = $firmapi->doRequest("GET", getenv("POKEAPI_URL")."/pokemon/{$pokemonId}");
        $temp_req = json_decode($temp_req);

        // en caso de que el pokemon no exista en la api o este malo el json no carga nada
        if($temp_req === null)
            return false;

        // Variable que almacena los datos del pokemon y los stats
        [$data_pokemon, $data_pokemon_stats] = [
            [
                "pokemonId" => $pokemonId,
                "isLoaded" => 1,
                "image" => ((array)$temp_req->sprites->other)["official-artwork"]->front_default
            ],
            [
                "height" => $temp_req->height,
                "weight" => $temp_req->weight,
                "base_experience" => $temp_req->base_experience,
            ]
        ];

        $data_pokemon_types = array_map(function($types) use($pokemonId) {
            $newPokemonTypeId = explode("/", $types->type->url);
            $newPokemonTypeId = $newPokemonTypeId[count($newPokemonTypeId) - 2];
            return [
                "pokemonId" => $pokemonId,
                "pokemonTypeId" => $newPokemonTypeId
            ];
        }, $temp_req->types);

        // Actualiza los datos del pokemon en la tabla de pokemons
        $pokemon_model = new \App\Models\Pokemon\Pokemon_model();
        $pokemon_model->updatePokemonData($data_pokemon);

        // Actualiza los stats del pokemon en la tabla de pokemon_stats
        $pokemon_stats_model = new \App\Models\Pokemon\Pokemon_stats_model();
        $pokemon_stats_model->updatePokemonStats($pokemonId, $data_pokemon_stats);

        // Actualiza los types del pokemon en la tabla de relation_pokemon_type
        $pokemon_relation_pokemon_type_model = new \App\Models\Pokemon\Pokemon_relation_pokemon_type_model();
        $pokemon_relation_pokemon_type_model->updatePokemonTypes($pokemonId, $data_pokemon_types);

        return true;
    }
}