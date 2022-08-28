<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\SpecialRequest;
use App\Http\Resources\SpecialResource;
use App\Repositorys\SpecialRepository;
use App\Traits\ResponseTrait;

class SpecialController extends Controller
{

    use ResponseTrait;

    /**
     * @var SpecialRepositry
     */
    protected SpecialRepository $specialRepositry;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SpecialRepository $specialRepositry)
    {
        $this->specialRepositry =  $specialRepositry;
    }

    /**
     * list function
     *
     * @return void
     */
    public function list()
    {
        $categories = $this->specialRepositry->allSpecials();
        return $this->returnData('Specials', SpecialResource::collection($categories), __('Succesfully'));
    }

    /**
     * store function
     *
     * @param SpecialRequest $request
     * @return void
     */
    public function store(SpecialRequest $request)
    {
        $special = $this->specialRepositry->saveSpecial($request);

        if ($special) {
            return $this->returnData('Special', SpecialResource::make($special), __('Special created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create Special!'));
    }

    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id)
    {
        $special = $this->specialRepositry->getSpecialByID($id);

        if ($special) {
            return $this->returnData('Special', SpecialResource::make($special), __('Get Special succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get Special!'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->specialRepositry->deleteSpecial($id);

        return $this->returnSuccessMessage(__('Delete Special succesfully!'));
    }


}
