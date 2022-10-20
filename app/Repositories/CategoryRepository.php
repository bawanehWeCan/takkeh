<?php

namespace App\Repositorys;

class CategoryRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    //protected $model = Restaurant::class;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * saveRestaurant function
     *
     * @param Array $data
     * @return void
     */
    public function save($data)
    {

        $model = new $this->model;
        $model->name    = $data['name'];
        $model->image    = $data['image'];
        $model->save();

        return $model->fresh();

    }
}
