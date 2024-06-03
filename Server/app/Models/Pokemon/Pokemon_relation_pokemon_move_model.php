<?php

namespace App\Models\Pokemon;

use App\Libraries\FirmaAPI;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class Pokemon_relation_pokemon_move_model extends Model
{
    protected $table = 'pokefirma.pokemon_relation_pokemon_moves';

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
    public function getPokemonMoves(int $pokemonId): array
    {

        $learnmethod_model = new \App\Models\Pokemon\Pokemon_learnmethod_model();
        $learnmethod_data = $learnmethod_model->getPokemonLearnMethods();

        $data =  $this
            ->select("pokefirma.pokemon_move.name")
            ->select("pokefirma.pokemon_relation_pokemon_moves.level_learned_at")
            #->select("pokefirma.pokemon_relation_pokemon_moves.method_learned")
            ->select("pokefirma.pokemon_relation_pokemon_moves.version")
            ->join("pokefirma.pokemon_move", "pokefirma.pokemon_move.pokemonMoveId = pokefirma.pokemon_relation_pokemon_moves.pokemonMoveId")
            ->where("pokemonId", $pokemonId)
            ->orderBy("pokefirma.pokemon_relation_pokemon_moves.level_learned_at")
            ->groupBy("pokefirma.pokemon_move.name, pokefirma.pokemon_relation_pokemon_moves.level_learned_at");

        foreach ($learnmethod_data as $learnmethod) {
            $name = str_replace("-", "", $learnmethod->name);
            $data->select("SUM(pokefirma.pokemon_relation_pokemon_moves.method_learned = {$learnmethod->pokemonLearnMethodId}) as 'ml_{$name}'");
        }

        return $data->findAll();
    }

    /**
     * Inserta las abilities de un pokemon
     * @param int $pokemonId
     * @param array $data
     * @return bool
     * @throws \ReflectionException
     */
    public function updatePokemonMoves(int $pokemonId, array $data): bool
    {
        $this->where("pokemonId", $pokemonId)->delete();
        return $this->insertBatch($data);
    }
}