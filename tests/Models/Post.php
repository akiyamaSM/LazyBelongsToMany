<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Inani\LazyBelongsToMany\Relations\BelongsToManyCreator;

class Post extends Model
{
    use BelongsToManyCreator;

    protected $guarded = [];

    protected $casts = ['tags_id' => 'array'];

    public $timestamps = false;

    public function tags()
    {
        return $this->belongsToManyInArray(Tag::class);
    }
}