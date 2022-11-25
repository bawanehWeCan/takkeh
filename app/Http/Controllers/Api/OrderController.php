<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\CartItem;
use App\Models\ProductItem;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\AddressResource;
use App\Http\Resources\MyOrdersResource;
use App\Http\Resources\OrderUpdateResource;

class OrderController extends Controller
{
    use ResponseTrait;
    public function store(Request $request)
    {
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->restaurant_id = $request->restaurant_id;
        $order->note = !empty($request->note) ? $request->note : '';
        $order->total = $request->total;
        $order->status = 'pending';
        $order->save();

        foreach ($request->products as $product) {

            $cart_item = new CartItem();
            $cart_item->product_id = $product['product_id'];
            $cart_item->order_id = $order->id;
            $cart_item->quantity = $product['quantity'];
            $cart_item->note = !empty($product['note']) ? $product['note'] : '';
            $cart_item->price = $product['price'];
            $cart_item->save();

            foreach ($product['groups'] as $group) {
                $product_item = new ProductItem();
                $product_item->group_id = $group['group_id'];
                $product_item->group_item_id = $group['item_id'];
                $product_item->cart_item_id = $cart_item->id;
                $product_item->save();
            }
        }

        return $this->returnData('data', new OrderResource($order), '');
    }

    public function update(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->lat = $request->lat;
        $order->long = $request->long;
        $order->save();
        $stuRef = app('firebase.firestore')->database()->collection('orders')->newDocument();
        $stuRef->set([
            'user_id' => $order->user_id,
            'restaurant_id' => $order->restaurant_id,
            'restaurant_name' => $order->restaurant->name,
            'status' => $order->status,
            'note' => $order->note,
            'lat' => $order->lat,
            'long' => $order->long,
            'total' => $order->total,
            'driver_id' => 0,
            'res_lat' => $order->restaurant->lat,
            'res_long' => $order->restaurant->lng,
            'res_zone' => $order->restaurant->zone,
            'created_at' => $order->created_at,
            'position' => array( 'geohas'=>'alaa','geopoint' => array( 'aaa','aaa' ) ),
        ]);

        return $this->returnData('order', new OrderUpdateResource($order), '');

    }

    public function user_orders($length = 10){
        $orders = Order::where('user_id',Auth::user()->id)->paginate($length);

        if (!$orders) {
            return $this->returnError(__('Sorry! Failed to get !'));
        }
        return $this->returnData('data',  MyOrdersResource::collection( $orders ), __('Get  succesfully'));
    }
}
