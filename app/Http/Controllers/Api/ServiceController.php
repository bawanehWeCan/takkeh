<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\ServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Repositorys\ServiceRepository;
use App\Traits\ResponseTrait;

class ServiceController extends Controller
{

    use ResponseTrait;

    /**
     * @var ServiceRepositry
     */
    protected ServiceRepository $serviceRepositry;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ServiceRepository $serviceRepositry)
    {
        $this->serviceRepositry =  $serviceRepositry;
    }

    /**
     * list function
     *
     * @return void
     */
    public function list()
    {
        $categories = $this->serviceRepositry->allServices();
        return $this->returnData('Services', ServiceResource::collection($categories), __('Succesfully'));
    }

    /**
     * store function
     *
     * @param ServiceRequest $request
     * @return void
     */
    public function store(ServiceRequest $request)
    {
        $service = $this->serviceRepositry->saveService($request);

        if ($service) {
            return $this->returnData('Service', ServiceResource::make($service), __('Service created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create Service!'));
    }

    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id)
    {
        $service = $this->serviceRepositry->getServiceByID($id);

        if ($service) {
            return $this->returnData('Service', ServiceResource::make($service), __('Get Service succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get Service!'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->serviceRepositry->deleteService($id);

        return $this->returnSuccessMessage(__('Delete Service succesfully!'));
    }

    /**
     * changeRole function
     *
     * @param [type] $id
     * @param RoleChangeRequest $request
     * @return void
     */
    public function changeRole($id, RoleChangeRequest $request)
    {
        $service = $this->serviceRepositry->asignRoleToService($id, $request->roles);

        if ($service) {
            return $this->returnSuccessMessage(__('Roles changed successfully!'));
        }

        return $this->returnError(__('Sorry! Service not found'));
    }
}
