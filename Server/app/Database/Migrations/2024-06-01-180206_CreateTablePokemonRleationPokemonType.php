<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTablePokemonRleationPokemonType extends Migration
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
            "pokemonTypeId" => [
                "type" => "BIGINT",
                "constraint" => 20,
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

        $this->forge->addUniqueKey(['pokemonId', 'pokemonTypeId']);
        $this->forge->createTable('pokemon_relation_pokemon_type', true);
    }

    public function down()
    {
        $this->forge->dropTable('pokemon_relation_pokemon_type');
    }
}
