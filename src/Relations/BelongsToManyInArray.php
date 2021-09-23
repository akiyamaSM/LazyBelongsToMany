<?php


namespace Inani\LazyBelongsToMany\Relations;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BelongsToManyInArray extends BelongsTo
{
    protected $instance;
    protected $query;
    protected $related;
    protected $localKey;
    protected static $constraints = false;

    public function __construct($query, $instance, $localKey)
    {
        $this->query = $query;
        $this->instance = $instance;
        $this->related = $query->getModel();
        $this->localKey = $localKey;
    }


    public function get($columns = ['*'])
    {
        return $this->getResults();
    }

    public function addConstraints()
    {
        // silence is gold
    }

    public function addEagerConstraints(array $models)
    {
        $ids  = [];
        foreach ($models as $model){
            if(is_array($model->posts_id)){
                $ids = array_merge($ids, $model->posts_id);
            }
        }

        return $ids;
    }

    public function getLocalKey()
    {
        return $this->localKey;
    }
    public function match(array $models, Collection $results, $relation): array
    {
        $results = $this->query->whereIn(
            $this->getRelatedFullyKeyName(),
            $this->addEagerConstraints($models)
        )->get();

        foreach ($models as $model){
            $posts = $results->whereIn($this->getRelatedFullyKeyName(), $model->{$this->getLocalKey()});

            $model->setRelation(
                $relation, $this->related->newCollection($posts->all())
            );
        }

        return $models;
    }

    public function getResults()
    {
        if(!is_array($this->instance->{$this->getLocalKey()})){
            return $this->related->newCollection();
        }
        return $this->query->whereIn($this->getRelatedFullyKeyName(), $this->instance->{$this->getLocalKey()})->get();
    }

    public function getRelatedFullyKeyName(): string
    {
        return $this->related->getTable() . '.' . $this->related->getKeyName();
    }
}
