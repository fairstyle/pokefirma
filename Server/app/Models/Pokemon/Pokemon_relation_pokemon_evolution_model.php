<?php

namespace App\Models\Pokemon;

use App\Libraries\FirmaAPI;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_relation_pokemon_evolution_model extends Model
{
    protected $table = 'pokefirma.pokemon_relation_pokemon_evolution';

    protected $returnType = \stdClass::class;
    protected $protectFields = false;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $afterFind = ['loadEvolutionPokemon'];

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null, bool $isCacheOn = false, ResponseInterface $response = null)
    {
        parent::__construct($db, $validation);
        $this->setCache($isCacheOn);

        if($response !== null)
            $this->response = $response;
    }

    /**
     * Recupera las evoluciones de un pokemon
     * @param int $pokemonId
     * @return array
     */
    public function getPokemonEvolution(int $pokemonId): array
    {
        return $this
            ->select("pokefirma.pokemon.pokemonId, pokefirma.pokemon.name, pokefirma.pokemon.image, pokefirma.pokemon_relation_pokemon_evolution.level")
            ->select("pokefirma.pokemon_relation_pokemon_evolution.pokemonEvolutionId")
            ->join("pokefirma.pokemon", "pokefirma.pokemon.pokemonId = pokefirma.pokemon_relation_pokemon_evolution.pokemonEvolutionId")
            ->where("pokefirma.pokemon_relation_pokemon_evolution.pokemonId", $pokemonId)
            ->orderBy("pokefirma.pokemon_relation_pokemon_evolution.order")
            ->findAll();
    }

    /**
     * Inserta las evoluciones de un pokemon
     * @param int $pokemonId
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function updatePokemonEvolution(int $pokemonId, array $data): bool
    {
        $this->where("pokemonId", $pokemonId)->delete();
        return $this->insertBatch($data);
    }

    protected function loadEvolutionPokemon($pokemon): array
    {
        if($pokemon["data"] === null)
            return $pokemon;

        $pokemon_model = new Pokemon_model(null, null, false, $this->response);
        foreach ($pokemon["data"] as $key => $value) {
            if($value->image === "")
                $value->image = $pokemon_model->getPokemon($value->pokemonId)->image ?? "Sin imagen";
        }

        return $pokemon;
    }
}