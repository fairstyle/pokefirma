<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTablePokemoRelationPokemonAbility extends Migration
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
            "pokemonAbilityId" => [
                "type" => "BIGINT",
                "constraint" => 20,
                "unsigned" => true,
                "null" => false
            ],
            "isHidden" => [
                "type" => "BOOLEAN",
                "default" => 0
            ],
            "created_at" => [
                "type" => "TIMESTAMP",
                "null" => false,
                "default" => new RawSql('CURRENT_TIMESTAMP')
            ],
            "`updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP"
        ]);

        $this->forge->addUniqueKey(['pokemonId', 'pokemonAbilityId']);
        $this->forge->createTable('pokemon_relation_pokemon_ability', true);
    }

    public function down()
    {
        $this->forge->dropTable('pokemon_relation_pokemon_ability');
    }
}
