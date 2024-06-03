<?php

namespace App\Models\Pokemon;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_ability_model extends Model
{
    protected $table = 'pokefirma.pokemon_ability';
    protected $primaryKey = 'pokemonAbilityId';
    protected $useAutoIncrement = true;
    protected $returnType = \stdClass::class;
    protected $protectFields = false;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected ResponseInterface $response;

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null, bool $isCacheOn = false)
    {
        parent::__construct($db, $validation);
        $this->setCache($isCacheOn);
    }

    /**
     * Recupera todos los tipos de pokemones
     * @return array
     */
    public function getPokemonAbilities(): array
    {
        return $this
            ->findAll();
    }

    /**
     * Inserta un nuevo tipo de pokemon
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function insertPokemonAbility(array $data): bool
    {
        return $this->insertBatch($data);
    }
}