<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTbUserLevelAkses extends Migration
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
				'unsigned' => true,
			],
			'menu_akses_id' => [
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => true,
			],
			'created_at' => [
				'type' => 'DATETIME',
			],
			'updated_at' => [
				'type' => 'DATETIME',
			]
		]);
		$this->forge->addKey('id', true);
		$this->forge->createTable('tbuser_level_akses');
	}

	public function down()
	{
		$this->forge->dropTable('tbuser_level_akses');
	}
}
