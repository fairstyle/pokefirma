<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTablePokemonStats extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pokemon_stats', [
            'hp' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'after' => 'base_experience'
            ],
            'attack' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'after' => 'hp'
            ],
            'defense' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'after' => 'attack'
            ],
            'special_attack' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'after' => 'defense'
            ],
            'special_defense' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'after' => 'special_attack'
            ],
            'speed' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
                'after' => 'special_defense'
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
