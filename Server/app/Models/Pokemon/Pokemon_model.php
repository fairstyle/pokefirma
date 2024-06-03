<?php

namespace App\Models\Pokemon;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_model extends Model
{
    protected $table = 'pokefirma.pokemon';
    protected $primaryKey = 'pokemonId';
    protected $useAutoIncrement = true;
    protected $returnType = \stdClass::class;
    protected $protectFields = false;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $afterFind = ['loadPokemon', 'loadStats', 'loadTypes', 'loadAbilities', 'loadEvolutions', 'loadMoves'];

    protected ResponseInterface $response;

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null, bool $isCacheOn = false, ResponseInterface $response = null)
    {
        parent::__construct($db, $validation);
        $this->setCache($isCacheOn);

        if($response !== null)
            $this->response = $response;
    }

    /**
     * Recupera el pokemon especificado o todos los pokemones
     * @param int|null $pokemonId
     * @return Model|\stdClass|array
     */
    public function getPokemon(int|array $pokemonId = null): Model|\stdClass|array
    {
        return $pokemonId === null ? $this : (is_array($pokemonId) ? $this->whereIn("pokemonId", $pokemonId) : $this->find($pokemonId));
    }


    /**
     * Recupera un pokemon por su nombre
     * @param string $pokemonName
     * @return array
     */
    public function findPokemon(string $pokemonName): array
    {
        return $this->like("name", strtolower($pokemonName))->findAll();
    }

    /**
     * Recupera el pokemon siguiente o anterior al pokemon especificado
     * @param int $pokemonId
     * @param string $typeOpts
     * @return \stdClass|null
     */
    public function getOptsPokemon(int $pokemonId, string $typeOpts = "next"): \stdClass|null
    {
        $this->afterFind = [];

        if($typeOpts === "next")
            return $this
                ->where("pokemonId >", $pokemonId)
                ->orderBy("pokemonId", "ASC")
                ->first();
        else
            return $this
                ->where("pokemonId <", $pokemonId)
                ->orderBy("pokemonId", "DESC")
                ->first();
    }

    public function getCountPokemons(): int
    {
        $this->afterFind = [];
        return $this->countAllResults();
    }

    /**
     * Carga la información del pokemon en caso de que no contenga información, este se ejecuta con el evento afterFind, el cual se lanza despues de un first, find o findAll
     * @param $pokemon
     * @return array
     * @throws \ReflectionException
     */
    protected function loadPokemon($pokemon): array
    {
        if($pokemon["data"] === null)
            return $pokemon;

        $load_pokemon_model = new \App\Models\Load_pokemon_model();
        $pokemons_array = array_map(function($eachPokemon) use ($load_pokemon_model) {

            // Verificar si el campo stats del pokemon es diferente a 1 lo que significa que no se han cargado los stats y se deben cargar
            if($eachPokemon->isLoaded !== "1") {

                // se carga toda la información del pokemon
                $load_pokemon_model->loadPokemon($eachPokemon->pokemonId, $this->response);

                // Se vuelve a obtener la información misma del pokemon, (ya que la imagen no se carga en un inicio)
                $eachPokemon = self::getPokemon($eachPokemon->pokemonId);
            }

            return $eachPokemon;
        }, is_array($pokemon["data"]) ? $pokemon["data"] : [$pokemon["data"]]);

        // Se actualiza la variable que contiene la información del pokemon dependiendo si se hizo un findAll o un find a un pokemon especifico
        $pokemon["data"] = is_array($pokemon["data"]) ? $pokemons_array : $pokemons_array[0];

        return $pokemon;
    }
    /**
     * Carga la información stats del pokemon, este se ejecuta con el evento afterFind, el cual se lanza despues de un first, find o findAll
     * @param $pokemon
     * @return array
     * @throws \ReflectionException
     */
    protected function loadStats($pokemon): array
    {
        if($pokemon["data"] === null)
            return $pokemon;

        $pokemon_stats_model = new \App\Models\Pokemon\Pokemon_stats_model();
        $pokemons_array = array_map(function($eachPokemon) use ($pokemon_stats_model) {

            // Se obtienen los stats del pokemon
            $eachPokemon->stats = $pokemon_stats_model->getPokemonStats($eachPokemon->pokemonId);

            return $eachPokemon;
        }, is_array($pokemon["data"]) ? $pokemon["data"] : [$pokemon["data"]]);

        // Se actualiza la variable que contiene la información del pokemon dependiendo si se hizo un findAll o un find a un pokemon especifico
        $pokemon["data"] = is_array($pokemon["data"]) ? $pokemons_array : $pokemons_array[0];

        return $pokemon;
    }

    /**
     * Carga la información de los types del pokemon, este se ejecuta con el evento afterFind, el cual se lanza despues de un first, find o findAll
     * @param $pokemon
     * @return array
     */
    protected function loadTypes($pokemon): array
    {
        if($pokemon["data"] === null)
            return $pokemon;

        $pokemon_relation_pokemon_type_model = new \App\Models\Pokemon\Pokemon_relation_pokemon_type_model();
        $pokemons_array = array_map(function($eachPokemon) use ($pokemon_relation_pokemon_type_model) {

            // Se obtienen los types del pokemon
            $eachPokemon->types = $pokemon_relation_pokemon_type_model->getPokemonTypes($eachPokemon->pokemonId);

            return $eachPokemon;
        }, is_array($pokemon["data"]) ? $pokemon["data"] : [$pokemon["data"]]);

        // Se actualiza la variable que contiene la información del pokemon dependiendo si se hizo un findAll o un find a un pokemon especifico
        $pokemon["data"] = is_array($pokemon["data"]) ? $pokemons_array : $pokemons_array[0];

        return $pokemon;
    }

    /**
     * Carga la información de las abilities del pokemon, este se ejecuta con el evento afterFind, el cual se lanza despues de un first, find o findAll
     * @param $pokemon
     * @return array
     */
    protected function loadAbilities($pokemon): array
    {
        if($pokemon["data"] === null)
            return $pokemon;

        $pokemon_relation_pokemon_ability_model = new \App\Models\Pokemon\Pokemon_relation_pokemon_ability_model();
        $pokemons_array = array_map(function($eachPokemon) use ($pokemon_relation_pokemon_ability_model) {

            // Se obtienen los types del pokemon
            $eachPokemon->abilities = $pokemon_relation_pokemon_ability_model->getPokemonAbilities($eachPokemon->pokemonId);

            return $eachPokemon;
        }, is_array($pokemon["data"]) ? $pokemon["data"] : [$pokemon["data"]]);

        // Se actualiza la variable que contiene la información del pokemon dependiendo si se hizo un findAll o un find a un pokemon especifico
        $pokemon["data"] = is_array($pokemon["data"]) ? $pokemons_array : $pokemons_array[0];

        return $pokemon;
    }


    /**
     * Carga la información de las evoluciones del pokemon, este se ejecuta con el evento afterFind, el cual se lanza despues de un first, find o findAll
     * @param $pokemon
     * @return array
     */
    protected function loadEvolutions($pokemon): array
    {
        if($pokemon["data"] === null)
            return $pokemon;

        $pokemon_relation_pokemon_evolution_model = new \App\Models\Pokemon\Pokemon_relation_pokemon_evolution_model(null, null, false, $this->response);
        $pokemons_array = array_map(function($eachPokemon) use ($pokemon_relation_pokemon_evolution_model) {

            // Se obtienen los types del pokemon
            $eachPokemon->evolutions = $pokemon_relation_pokemon_evolution_model->getPokemonEvolution($eachPokemon->pokemonId);

            return $eachPokemon;
        }, is_array($pokemon["data"]) ? $pokemon["data"] : [$pokemon["data"]]);

        // Se actualiza la variable que contiene la información del pokemon dependiendo si se hizo un findAll o un find a un pokemon especifico
        $pokemon["data"] = is_array($pokemon["data"]) ? $pokemons_array : $pokemons_array[0];

        return $pokemon;
    }

    /**
     * Carga la información de los moviemintos del pokemon, este se ejecuta con el evento afterFind, el cual se lanza despues de un first, find o findAll
     * @param $pokemon
     * @return array
     */
    protected function loadMoves($pokemon): array
    {
        if($pokemon["data"] === null)
            return $pokemon;

        $pokemon_relation_pokemon_move_model = new \App\Models\Pokemon\Pokemon_relation_pokemon_move_model(null, null, false, $this->response);
        $pokemons_array = array_map(function($eachPokemon) use ($pokemon_relation_pokemon_move_model) {

            // Se obtienen los types del pokemon
            $eachPokemon->moves = $pokemon_relation_pokemon_move_model->getPokemonMoves($eachPokemon->pokemonId);

            return $eachPokemon;
        }, is_array($pokemon["data"]) ? $pokemon["data"] : [$pokemon["data"]]);

        // Se actualiza la variable que contiene la información del pokemon dependiendo si se hizo un findAll o un find a un pokemon especifico
        $pokemon["data"] = is_array($pokemon["data"]) ? $pokemons_array : $pokemons_array[0];

        return $pokemon;
    }

    /**
     * Actualiza la información del pokemon que viene el la variable $data
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function updatePokemonData(array $data = []): bool
    {
        // Se actualiza la información del pokemon
        $this->save($data);
        return true;
    }


}