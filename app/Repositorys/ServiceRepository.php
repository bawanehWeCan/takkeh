<?php

namespace App\Repositorys;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class ServiceRepository
{

    /**
     * @var Service
     */
    protected $service;

    /**
     * __construct function
     *
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * allServices function
     *
     * @return Collection
     */
    public function allServices()
    {
        $services = $this->service->all();
        return $services;
    }

    /**
     * saveService function
     *
     * @param Array $data
     * @return void
     */
    public function saveService($data)
    {

        $service = new $this->service;
        $service->name = $data['name'];
        $service->image = $data['image'];
        $service->content = $data['content'];
        $service->price = $data['price'];
        $service->category_id = $data['category_id'];
        $service->user_id = $data['supplier_id'];
        $service->save();

        return $service->fresh();
    }

    /**
     * getServiceByID function
     *
     * @return Collection
     */
    public function getServiceByID($id)
    {
        $service = $this->service->where('id', $id)->firstOrFail();
        return $service;
    }

    /**
     * deleteService function
     *
     * @return bool
     */
    public function deleteService($id)
    {
        Service::destroy($id);
    }

}
