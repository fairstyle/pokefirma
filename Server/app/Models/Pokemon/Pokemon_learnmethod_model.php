<?php

namespace App\Models\Pokemon;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_learnmethod_model extends Model
{
    protected $table = 'pokefirma.pokemon_learnmethod';
    protected $primaryKey = 'pokemonLearnMethodId';
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
     * Recupera todos los metodos de aprendizaje de pokemones
     * @return array
     */
    public function getPokemonLearnMethods(): array
    {
        return $this
            ->findAll();
    }

    /**
     * Inserta un nuevo metodos de aprendizaje de pokemon
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function insertPokemonLearnMethods(array $data): bool
    {
        return $this->insertBatch($data);
    }
}