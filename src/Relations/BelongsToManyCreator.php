<?php


namespace Inani\LazyBelongsToMany\Relations;


use Illuminate\Database\Eloquent\Concerns\HasRelationships;

trait BelongsToManyCreator
{
    use HasRelationships;

    public static function bootBelongsToManyCreator()
    {
        static::$manyMethods = array_merge(static::$manyMethods, [
            'belongsToManyInArray'
        ]);
    }

    /**
     * Define a many-to-many relationship.
     *
     * @param string $related
     * @param null $localKey
     * @return BelongsToManyInArray
     */
    public function belongsToManyInArray($related, $localKey = null ): BelongsToManyInArray
    {
        $instance = $this->newRelatedInstance($related);

        if(is_null($localKey)){
            $localKey = $this->guessBelongsToManyRelation(). '_' . $instance->getKeyName();
        }

        return new BelongsToManyInArray( $instance->newQuery(), $this, $localKey);
    }
}
