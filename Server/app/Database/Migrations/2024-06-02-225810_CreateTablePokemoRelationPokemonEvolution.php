<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTablePokemoRelationPokemonEvolution extends Migration
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
            "pokemonEvolutionId" => [
                "type" => "BIGINT",
                "constraint" => 20,
                "unsigned" => true,
                "null" => false
            ],
            "level" => [
                "type" => "INT",
                "default" => 0
            ],
            "order" => [
                "type" => "INT",
                "default" => 0
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "null" => false,
                "default" => new RawSql('CURRENT_TIMESTAMP')
            ],
            "`updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP"
        ]);

        $this->forge->addUniqueKey(['pokemonId', 'pokemonEvolutionId']);
        $this->forge->createTable('pokemon_relation_pokemon_evolution', true);
    }

    public function down()
    {
        $this->forge->dropTable('pokemon_relation_pokemon_evolution');
    }
}
