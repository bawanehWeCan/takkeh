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
use App\Http\Resources\PromoCodeResource;

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
        $this->resource = PromoCodeResource::class;
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
        if (!  $order->codes->contains($code->id)) {
            $order->codes()->attach($code);
            return $this->returnSuccessMessage(__("An order added to a promo code successfully!"));
        }else{
            return $this->returnError("Sorry! This code is added before");
        }
    }
}
