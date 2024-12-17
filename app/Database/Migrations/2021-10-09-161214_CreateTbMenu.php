<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbMenu extends Migration
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
			'user_level_id' => [
				'type' => 'INT',
				'constraint' => 5,
			],
			'nama' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
			],
			'url' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
			],
			'icon' => [
				'type' => 'VARCHAR',
				'constraint' => 50,
			],
			'urutan' => [
				'type' => 'INT',
				'constraint' => 5,
			],
			'aktif' => [
				'type' => 'INT',
				'constraint' => 1,
			],
			'created_at' => [
				'type' => 'DATETIME',
			],
			'updated_at' => [
				'type' => 'DATETIME',
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbmenu');
	}

	public function down()
	{
		$this->forge->dropTable('tbmenu');
	}
}
