<?php

namespace App\Models\Pokemon;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_stats_model extends Model
{
    protected $table = 'pokefirma.pokemon_stats as pf_post';
    protected $primaryKey = 'pf_post.pfpo_pokemonId';
    protected $useAutoIncrement = true;
    protected $returnType = \stdClass::class;

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null, bool $isCacheOn = false)
    {
        parent::__construct($db, $validation);
        $this->setCache($isCacheOn);
    }

    public function getPokemonStats(int $pokemonId): \stdClass
    {
        return $this->where("pfpo_pokemonId", $pokemonId)->first();
    }
}