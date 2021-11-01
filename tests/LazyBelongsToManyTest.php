<?php

namespace Tests;

use Tests\Models\Post;
use Tests\Models\Tag;

class LazyBelongsToManyTest extends TestCase
{
    public function testLazyLoading()
    {
        $post = Post::find(1);

        $this->assertEquals(2, $post->tags->count());
        $this->assertInstanceOf(Tag::class, $post->tags->first());
        $this->assertEquals(['javascript', 'C#'], $post->tags->pluck('name')->all());
    }

    public function testEagerLoading()
    {
        $posts = Post::with('tags')->get();

        $this->assertEquals([1, 3], $posts[0]->tags->pluck('id')->all());
        $this->assertEquals([2, 3, 4], $posts[1]->tags->pluck('id')->all());
    }
}