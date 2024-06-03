<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTablePokemoAbility extends Migration
{
    public function up()
    {
        $this->forge->addField([
            "pokemonAbilityId" => [
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

        $this->forge->addKey('pokemonAbilityId', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('pokemon_ability', true);
    }

    public function down()
    {
        $this->forge->dropTable('pokemon_ability');
    }
}
