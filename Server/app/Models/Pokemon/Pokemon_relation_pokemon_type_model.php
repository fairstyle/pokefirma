<?php

namespace App\Models\Pokemon;

use App\Libraries\FirmaAPI;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_relation_pokemon_type_model extends Model
{
    protected $table = 'pokefirma.pokemon_relation_pokemon_type';

    protected $returnType = \stdClass::class;
    protected $protectFields = false;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null, bool $isCacheOn = false)
    {
        parent::__construct($db, $validation);
        $this->setCache($isCacheOn);
    }

    /**
     * Recupera los types de un pokemon
     * @param int $pokemonId
     * @return array
     */
    public function getPokemonTypes(int $pokemonId): array
    {
        return $this
            ->select("pokefirma.pokemon_type.pokemonTypeId, name")
            ->join("pokefirma.pokemon_type", "pokefirma.pokemon_type.pokemonTypeId = pokefirma.pokemon_relation_pokemon_type.pokemonTypeId")
            ->where("pokemonId", $pokemonId)
            ->findAll();
    }

    public function getPokemonTypesByPokemonTypeId(array $pokemonTypeId): array
    {
        return $this
            ->select("pokemonId")
            ->whereIn("pokemonTypeId", $pokemonTypeId)
            ->findAll();
    }


    /**
     * Inserta los types de un pokemon
     * @param int $pokemonId
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function updatePokemonTypes(int $pokemonId, array $data): bool
    {
        $this->where("pokemonId", $pokemonId)->delete();
        return $this->insertBatch($data);
    }
}