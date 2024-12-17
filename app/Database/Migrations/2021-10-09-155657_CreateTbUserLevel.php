<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbUserLevel extends Migration
{
	public function up()
	{
		$this->forge->addField([
			'id' => [
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => true,
				'auto_increment' => true,
			],
			'nama_level' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbuser_level');
	}

	public function down()
	{
		$this->forge->dropTable('tbuser_level');
	}
}
