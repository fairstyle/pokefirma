<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTablePokemonType extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "pokemonTypeId" => [
                "type" => "BIGINT",
                "constraint" => 20,
                "unsigned" => true,
            ],
            "name" => [
                "type" => "VARCHAR",
                "constraint" => 50,
                "null" => true
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "null" => false,
                "default" => new RawSql('CURRENT_TIMESTAMP')
            ],
            "`updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP"
        ]);

        $this->forge->addKey('pokemonTypeId', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('pokemon_type', true);
    }

    public function down()
    {
        $this->forge->dropTable('pokemon_type');
    }
}
