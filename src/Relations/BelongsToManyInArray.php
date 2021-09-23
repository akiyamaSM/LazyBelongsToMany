<?php


namespace Inani\LazyBelongsToMany\Relations;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PhpParser\Builder;

class BelongsToManyInArray extends BelongsTo
{
    protected $instance;
    protected $query;
    protected $related;
    protected $localKey;
    protected static $constraints = false;

    public function __construct($query, Model $instance, $localKey)
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
            if(is_array($model->{$this->getLocalKey()})){
                $ids = array_merge($ids, $model->{$this->getLocalKey()});
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
            $records = $results->whereIn($this->related->getKeyName(), $model->{$this->getLocalKey()});
            $model->setRelation($relation, $records);
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
