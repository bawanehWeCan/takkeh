<?php

namespace App\Repositorys;

class PageRepository extends AbstractRepository
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
        $model->title    = $data['title'];
        $model->content    = $data['content'];
        $model->save();

        return $model->fresh();

    }
}
