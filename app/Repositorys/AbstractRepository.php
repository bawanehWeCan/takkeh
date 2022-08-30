<?php

namespace App\Repositorys;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class AbstractRepository{


    /**
     * holds the specific model itself.
     *
     * @var Model
     */
    protected $model;

    /**
     * Create new Library class.
     *
     * this abstraction expects the child class to have a protected attribute named model.
     * that will hold the model name with its full namespace.
     */
    public function __construct($model)
    {
        $this->resource = $model;
    }
    

    /**
     * @return void
     */
    public function all(){
        $data = $this->resource->get();
        return $data;
    }

        /**
     * @return void
     */
    public function pagination($length = 10): LengthAwarePaginator
    {
        $data = $this->resource->paginate($length);
        return $data;
    }


    /**
     * @param [type] $model_id
     * @return void
     */
    function getByID( $model_id ){
        $model = $this->resource->where( 'id', $model_id )->firstOrFail();
        return $model;
    }

    /**
     * delete model by id
     *
     * @param [type] $model_id
     * @param boolean $force
     * @return void
     */
    public function deleteByID( $model_id, bool $force = false ):void
    {
        $model = $this->resource->where( 'id', $model_id )->firstOrFail();
        
        if ($force) {
            $model->forceDelete();
        }

        if (! $force) {
            $model->delete();
        }
    }


    /**
     * @return void
     */
    abstract function save( $data );


}