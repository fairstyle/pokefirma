<?php

namespace App\Models\Pokemon;

use App\Libraries\FirmaAPI;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_relation_pokemon_ability_model extends Model
{
    protected $table = 'pokefirma.pokemon_relation_pokemon_ability';

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
     * Recupera las abilities de un pokemon
     * @param int $pokemonId
     * @return array
     */
    public function getPokemonAbilities(int $pokemonId): array
    {
        return $this
            ->select("pokefirma.pokemon_ability.pokemonAbilityId, name, isHidden")
            ->join("pokefirma.pokemon_ability", "pokefirma.pokemon_ability.pokemonAbilityId = pokefirma.pokemon_relation_pokemon_ability.pokemonAbilityId")
            ->where("pokemonId", $pokemonId)
            ->findAll();
    }

    /**
     * @param array $pokemonAbilityId
     * @return array
     */
    public function getPokemonAbilitiesByPokemonAbilityId(array $pokemonAbilityId): array
    {
        return $this
            ->select("pokemonId")
            ->whereIn("pokemonAbilityId", $pokemonAbilityId)
            ->findAll();
    }

    /**
     * Inserta las abilities de un pokemon
     * @param int $pokemonId
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function updatePokemonAbilities(int $pokemonId, array $data): bool
    {
        $this->where("pokemonId", $pokemonId)->delete();
        return $this->insertBatch($data);
    }
}