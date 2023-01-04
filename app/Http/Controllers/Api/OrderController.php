<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GeoHash;
use Google\Cloud\Core\GeoPoint;
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
use App\Http\Resources\FirebaseResource;
use App\Http\Resources\MyOrdersResource;
use App\Http\Resources\OrderUpdateResource;
use App\Models\Address;
use App\Models\Product;
use App\Repositories\Repository;
use App\Traits\NotificationTrait;

class OrderController extends Controller
{
    use ResponseTrait, NotificationTrait;
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
            $this->updateProductQuantity($product['product_id'], $product['quantity']);
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

        $discount = 0;

        if( $order->codes()->first() ){
            $c = $order->codes()->first();
            if( $c->type == 'Fixed' ){
                $discount = $c->value;
            }else{
                $discount = ( $c->value / 100 ) * $order->total;
            }
        }




        $address = Address::find($request->address_id);

        $g = new GeoHash();

        $user = User::find($order->user_id);

        $fire = $fireItem = array();
        foreach ($order->products as $product) {

            $fireItem['id'] = $product->id;
            $fireItem['name'] = $product->name;
            $fireItem['price'] = $product->price;
            $fireItem['quantity'] = $product->quantity;
            array_push($fire, $fireItem);

        }

        $driver = User::find( $this->getNearByDriverID($order) );



        if( empty( $driver?->id )  ){
            $driver = User::where('type','driver')->first();
        }




        $orderfire = app('firebase.firestore')->database()->collection('orders')->document($order->id);
        $orderfire->set([

            'created_at' => $order->created_at,
            'delivery_fee' => (double)$order->restaurant->delivery_fees,
            'discount' => $discount,

            'driver_id' => $driver->id,
            'driver_image' =>$driver->image,
            'driver_name' => $driver->name,
            'driver_phone' => $driver->phone,

            'drop_point_address' => $address->name,
            'drop_point_id' => $user->id,
            'drop_point_image' => (string)$user->image,
            'drop_point_name' => $user->name,
            'drop_point_phone' => $user->phone,
            'drop_point_position' => array('geohash' => $g->encode($address->lat, $address->long), 'geopoint' =>  new \Google\Cloud\Core\GeoPoint($address->lat, $address->long)),

            'final_price' => $order->total - ( $discount + $order->restaurant->delivery_fees ) ,
            'note' => $order->note,

            'order_details' => $fire,

            'order_id' => $order->id,
            'payment_method' => 'cash',

            'pickup_point_address' => $order->restaurant->address,
            'pickup_point_id' => $order->restaurant->id,
            'pickup_point_image' => $order->restaurant->logo,
            'pickup_point_name' => $order->restaurant->name,
            'pickup_point_phone' => $order->restaurant->user->phone,
            'pickup_point_position' => array('geohash' => $g->encode($order->restaurant->lat, $order->restaurant->long), 'geopoint' =>  new \Google\Cloud\Core\GeoPoint($order->restaurant->lat, $order->restaurant->long)),

            'status' => 'hold',
            'tax' => 0,
            'total_price' => $order->total,
            'type' => 'restaurant',
            'user_name' => $user->name,

        ]);

        dd( $orderfire->get('user_name') );


        $payment = app('firebase.firestore')->database()->collection('payments')->document($order->id);
        $payment->set([

            'date' => $order->created_at,
            'order_id' => $order->id,
            'method' => 'cash',
            'status' => 'pending',
            'amount' => $order->total - ( $discount + $order->restaurant->delivery_fees ),
            'user_name' => $user->name,

        ]);

        return $this->returnData('order', new OrderUpdateResource($order), '');
    }

    public function user_orders($length = 10)
    {
        $orders = Order::where('user_id', Auth::user()->id)->paginate($length);

        if (!$orders) {
            return $this->returnError(__('Sorry! Failed to get !'));
        }
        return $this->returnData('data',  MyOrdersResource::collection($orders), __('Get  succesfully'));
    }

    public function updateProductQuantity($id, $qty)
    {
        $product = Product::Find($id);
        $qty = $product->sold_quantity + $qty;
        $product->update(['sold_quantity' => $qty]);
    }

    public function updateStatus(Request $request)
    {

        try {
            $orederRepo = new Repository(app(Order::class));
            $user = new Repository(app(User::class));

            $order = $orederRepo->getByID($request->order_id);
            $user = $orederRepo->getByID($order->user_id);
            if ($request->status != $order->status) {
                $order->update($request->except('order_id'));

                dd($user->name);


                if ($order->status == 'pending') {
                    $this->testSend(__('Your Order has been Successfully recived'), $user->name); //send noti function in trait
                }
            }
        } catch (\Throwable $th) {
            //throw $th;

            dd($th);
        }
    }

    function distance($lat1, $lon1, $lat2, $lon2, $unit = 'k')
    {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);


        return ($miles * 1.609344) * 1000;
    }

    public function getNearByDriverID($order)
    {
        $drivers = User::where('type', 'driver')->get();

        $arr = array();


        $i = 0;
        foreach ($drivers as $driver) {
            # code...
            $arr[$i]['dis'] = $this->distance($order->restaurant->lat, $order->restaurant->long, $driver->lat, $driver->long);
            $arr[$i]['driver_id'] = $driver->id;

            // echo $driver->id . "   " . $this->distance($order->restaurant->lat, $order->restaurant->long, $driver->lat, $driver->long);
            $i++;
        }

        $minValue = $arr[0]['dis'];
        // get lowest or minimum value in array using foreach loop

        foreach ($arr as $val) {

            if ($minValue > $val['dis']) {
                $minValue = $val['driver_id'];
            }
        }
        return $minValue;
    }
}
