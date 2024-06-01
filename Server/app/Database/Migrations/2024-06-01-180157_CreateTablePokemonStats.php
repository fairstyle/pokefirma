<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTablePokemonStats extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "pokemonId" => [
                "type" => "BIGINT",
                "constraint" => 20,
                "unsigned" => true,
            ],
            "height" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
                "null" => true
            ],
            "weight" => [
                "type" => "INT",
                "constraint" => 11,
                "unsigned" => true,
                "null" => true
            ],
            "base_experience" => [
                "type" => "INT",
                "constraint" => 11,
                "null" => true
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "null" => false,
                "default" => new RawSql('CURRENT_TIMESTAMP')
            ],
            "`updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP"
        ]);

        $this->forge->addKey('pokemonId', true);
        $this->forge->addForeignKey('pokemonId', 'pokemon', 'pokemonId', '', '', 'fk_pokemon_stats_pokemon');
        $this->forge->createTable('pokemon_stats', true);
    }

    public function down()
    {
        $this->forge->dropTable('pokemon_stats');
    }
}
