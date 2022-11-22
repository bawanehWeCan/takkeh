<?php

namespace App\Repositorys;

class RestaurantRepository extends AbstractRepository
{

    /**
     * @var Restaurant
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
        $model->create($data);
        return $model->fresh();

    }
}
