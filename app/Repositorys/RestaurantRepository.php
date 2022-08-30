<?php

namespace App\Repositorys;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;

class RestaurantRepository extends AbstractRepository
{

    /**
     * @var Restaurant
     */
    //protected $model = Restaurant::class;

    public function __construct($resource)
    {
        $this->resource = $resource;
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
        $model->logo    = $data['logo'];
        $model->time    = $data['time'];
        $model->cover   = $data['cover'];
        $model->save();

        return $model->fresh();

    }
}
