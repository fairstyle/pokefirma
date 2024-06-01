<?php

namespace App\Models\Pokemon;

use App\Libraries\FirmaAPI;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_stats_model extends Model
{
    protected $table = 'pokefirma.pokemon_stats';
    protected $primaryKey = 'pokemonId';
    protected $useAutoIncrement = false;
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
     * Recupera los stats de un pokemon
     * @param int $pokemonId
     * @return \stdClass|null
     */
    public function getPokemonStats(int $pokemonId): \stdClass|null
    {
        return $this
            ->find($pokemonId);
    }


    /**
     * Actualiza los stats de un pokemon en caso de encontrarlos, en caso contrario inserta los stats
     * @param int $pokemonId
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function updatePokemonStats(int $pokemonId, array $data = []): bool
    {
        if($this->find($pokemonId) === null)
            $this
                ->set("pokemonId", $pokemonId)
                ->insert($data);
        else
            $this->update($pokemonId, $data);

        return true;
    }
}