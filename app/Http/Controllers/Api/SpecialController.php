<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Restaurant;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialRequest;
use App\Repositorys\SpecialRepository;
use App\Http\Resources\SpecialResource;
use App\Http\Requests\RoleChangeRequest;

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

        if (isset($request->resturant_id) && $request->product_id == 0) {
            $resturant = Restaurant::find($request->resturant_id);
            if (!$resturant) {
                return $this->returnError('This resturant is not exists');
            }
            $request['offerable_id'] = $request->resturant_id;
            $request['offerable_type'] = get_class($resturant);
            unset($request['product_id']);
            unset($request['resturant_id']);
            $special = $this->specialRepositry->saveSpecial($request->all());

            if ($special) {
                return $this->returnData('Special', SpecialResource::make($special), __('Special created succesfully'));
            }
        }

        if (isset($request->product_id) && $request->resturant_id == 0) {
            $product = Product::find($request->product_id);
            if (!$product) {
                return $this->returnError('This product is not exists');
            }
            $request['offerable_id'] = $request->product_id;
            $request['offerable_type'] = get_class($product);
            unset($request['product_id']);
            unset($request['resturant_id']);
            $special = $this->specialRepositry->saveSpecial($request->all());

            if ($special) {
                return $this->returnData('Special', SpecialResource::make($special), __('Special created succesfully'));
            }
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
