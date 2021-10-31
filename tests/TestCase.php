<?php

namespace Tests;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase as Base;
use Tests\Models\Post;
use Tests\Models\Tag;

abstract class TestCase extends Base
{
    protected function setUp(): void
    {
        parent::setUp();

        $config = require __DIR__.'/config/database.php';

        $db = new DB();
        $db->addConnection($config['sqlite']);
        $db->setAsGlobal();
        $db->bootEloquent();

        $this->migrate();
        $this->seed();
    }

    /**
     * Migrate the database.
     *
     * @return void
     */
    protected function migrate()
    {
        DB::schema()->dropAllTables();

        DB::schema()->create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('content');
            $table->json('tags_id')->nullable();
        });

        DB::schema()->create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
    }

    protected function seed()
    {
        Tag::query()->insert([
            ['id' => 1, 'name' => 'javascript'],
            ['id' => 2, 'name' => 'java'],
            ['id' => 3, 'name' => 'C#'],
            ['id' => 4, 'name' => 'PHP']
        ]);

        Post::query()->insert([
            ['id' => 1, 'content' => 'Post 1', 'tags_id' => json_encode([1, 3])],
            ['id' => 2, 'content' => 'Post 2', 'tags_id' => json_encode([2, 3, 4])],
            ['id' => 3, 'content' => 'Post 3', 'tags_id' => json_encode([3, 4])],
            ['id' => 4, 'content' => 'Post 4', 'tags_id' => json_encode([2])]
        ]);
    }
}