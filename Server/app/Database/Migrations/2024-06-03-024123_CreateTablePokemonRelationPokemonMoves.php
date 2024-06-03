<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTablePokemonRelationPokemonMoves extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "pokemonId" => [
                "type" => "BIGINT",
                "constraint" => 20,
                "unsigned" => true,
                "null" => false
            ],
            "pokemonMoveId" => [
                "type" => "BIGINT",
                "constraint" => 20,
                "unsigned" => true,
                "null" => false
            ],
            "level_learned_at" => [
                "type" => "INT",
                "default" => 0,
                "unsigned" => true,
                "null" => false
            ],
            "method_learned" => [
                "type" => "INT",
                "default" => 0,
                "unsigned" => true,
                "null" => false
            ],
            "version" => [
                "type" => "INT",
                "default" => 0,
                "unsigned" => true,
                "null" => false
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "null" => false,
                "default" => new RawSql('CURRENT_TIMESTAMP')
            ],
            "`updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP"
        ]);

        //$this->forge->addUniqueKey(['pokemonId', 'pokemonMoveId', 'level_learned_at', 'method_learned', 'version']);
        $this->forge->createTable('pokemon_relation_pokemon_moves', true);
    }

    public function down()
    {
        $this->forge->dropTable('pokemon_relation_pokemon_moves');
    }
}
