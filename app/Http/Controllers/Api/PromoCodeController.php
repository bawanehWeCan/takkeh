<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Repositories\Repository;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\PromoCodeRequest;
use App\Http\Resources\PromocodeResource;

class PromoCodeController extends ApiController
{
    use ResponseTrait;



    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = PromocodeResource::class;
        $this->model = app(PromoCode::class);
        $this->repositry =  new Repository($this->model);
    }




    /**
     * save
     *
     * @param  mixed $request
     * @return void
     */
    public function save( PromoCodeRequest $request ){
        return $this->store( $request->all() );

    }

    /**
     * addOrderToPromoCode
     *
     * @param  mixed $request
     * @param  mixed $code_id
     * @param  mixed $order_id
     * @return void
     */
    public function addCodeOrder(Request $request){
        $code = $this->repositry->getByID($request->code_id);
        $order = Order::find($request->order_id);
        if(!$code){
            return $this->returnError(__("Sorry! failed to find a promo code"));
        }
        if(!$order){
            return $this->returnError(__("Sorry! failed to find an order"));
        }

        $order->codes()->attach($code);
        return $this->returnSuccessMessage(__("An order added to a promo code successfully!"));
    }

    // /** */
    // public function list()
    // {
    //     $promoCodes = $this->repositry->all();
    //     return $this->returnData('data', $this->resource::collecton($promoCodes), __('Succesfully'));
    // }

    // public function store(PromoCodeRequest $request)
    // {
    //     $code = $this->repositry->save($request);

    //     if ($code) {
    //         return $this->returnData('data', new $this->resource($code), __('Code created succesfully'));
    //     }

    //     return $this->returnError(__('Sorry! Failed to create Code!'));
    // }

    // public function view($id)
    // {

    //     $code = $this->repositry->getByID($id);

    //     if ($code) {
    //         return $this->returnData('data',  new $this->resource($code), __('Get Code succesfully'));
    //     }

    //     return $this->returnError(__('Sorry! Failed to get Code!'));
    // }

    // public function delete($id)
    // {
    //     $this->repositry->deleteByID($id);

    //     return $this->returnSuccessMessage(__('Delete Code succesfully!'));
    // }
}
