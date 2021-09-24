# LazyBelongsToMany
A lightweigth implementation of Laravel's belongs To many relationship in cases you don't need pivot table.

## Installation

First, install the package through Composer.
```php
composer require inani/lazy-belongs-to-many
```

### Usage

Suppose we have the following database structure. A ``User`` can be linked to one or many ``Tag``. instead of creating the pivot table we can save tags id list in a column on the user table.
````
| id | name              | tags_id       |
|----|-------------------|---------------|
| 1  | El Houssain inani | [1, 10, 4, 33]|
````

````php

<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Inani\LazyBelongsToMany\Relations\BelongsToManyCreator;

class User extends Model
{
    use BelongsToManyCreator;
    
    protected $casts = [
      'tags_id' => 'array' // <=== IMPORTANT
    ];
    
    
    /**
    * Tags
     * @return mixed
    */
    public function tags()
    {
        return $this->belongsToManyInArray(Tag::class);
    }
}
````

For the time being there is no implementation for the inverse. I'll be happy to see your contribution at any way.

Happy Coding.

