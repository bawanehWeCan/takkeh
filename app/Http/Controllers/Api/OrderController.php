<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Traits\ResponseTrait;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\ProductItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseTrait;
    public function store( Request $request )
    {
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->restaurant_id = $request->restaurant_id;
        $order->note = $request->note;
        $order->total = $request->total;
        $order->status = 'pending';
        $order->save();

        foreach ($request->products as $product) {

            $cart_item = new CartItem();
            $cart_item->product_id = $product['product_id'];
            $cart_item->order_id = $order->id;
            $cart_item->quantity = $product['quantity'];
            $cart_item->size_id = $product['size_id'];
            $cart_item->note = $product['note'];
            $cart_item->price = $product['price'];
            $cart_item->save();

            foreach ($product['extras'] as $extra) {
                $product_item = new ProductItem();
                $product_item->cart_item_id = $cart_item->id;
                $product_item->extra_id = $extra['extra_id'];
                $product_item->save();
            }
        }

        return $this->returnData('data',new OrderResource( $order ),'');
    }

    public function update( Request $request ){
        $order = Order::find( $request->order_id );
        $order->lat = $request->lat;
        $order->long = $request->long;
        $order->save();

        return $this->returnData('data',new OrderResource( $order ),'');

    }
}
