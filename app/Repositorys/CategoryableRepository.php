<?php

namespace App\Repositorys;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Collection;

class aRestaurantRepository 
{

    /**
     * @var Restaurant
     */
    protected $restaurant = Restaurant::class;



    /**
     * saveRestaurant function
     *
     * @param Array $data
     * @return void
     */
    public function save($data)
    {

        $restaurant = new $this->restaurant;
        $restaurant->name = $data['name'];
        $restaurant->logo = $data['logo'];
        $restaurant->save();
        

    
        return $restaurant->fresh();

    }
}
