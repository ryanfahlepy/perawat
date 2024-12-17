<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbUser extends Migration
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
			'photo' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
			],
			'nama' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
			],
			'username' => [
				'type' => 'VARCHAR',
				'constraint' => 100,
			],
			'password' => [
				'type' => 'VARCHAR',
				'constraint' => 255,
			],
			'level_user' => [
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => true,
			],
			'status' => [
				'type' => 'VARCHAR',
				'constraint' => 15,
			],
			'created_at' => [
				'type' => 'DATETIME',
			],
			'updated_at' => [
				'type' => 'DATETIME',
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbuser');
	}

	public function down()
	{
		$this->forge->dropTable('tbuser');
	}
}
