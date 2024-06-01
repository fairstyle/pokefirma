<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;
class CreateTablePokemon extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "pokemonId" => [
                "type" => "BIGINT",
                "constraint" => 20,
                "unsigned" => true,
                "auto_increment" => true
            ],
            "name" => [
                "type" => "VARCHAR",
                "constraint" => 100,
                "default" => "Pokemon sin nombre"
            ],
            "isLoaded" => [
                "type" => "BOOLEAN",
                "default" => 0
            ],
            "image" => [
                "type" => "VARCHAR",
                "constraint" => 512
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "null" => false,
                "default" => new RawSql('CURRENT_TIMESTAMP')
            ],
            "`updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP"
        ]);

        $this->forge->addKey('pokemonId', true);
        $this->forge->createTable('pokemon', true);
    }

    public function down()
    {
        $this->forge->dropTable('pokemon');
    }
}
