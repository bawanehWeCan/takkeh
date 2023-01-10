<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ResponseTrait;
use App\Http\Requests\UserRequest;
use App\Repositorys\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Address;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Helpers\GeoHash;

class UserController extends Controller
{

    use ResponseTrait;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepositry;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepositry)
    {
        $this->userRepositry =  $userRepositry;
    }

    /**
     * list function
     *
     * @return void
     */
    public function list()
    {
        $users = $this->userRepositry->allUsers();
        return $this->returnData('users', UserResource::collection($users), __('Succesfully'));
    }

    /**
     * store function
     *
     * @param UserRequest $request
     * @return void
     */
    public function store(UserRequest $request)
    {
        $user = $this->userRepositry->saveUser($request);

        if ($user) {
            $user->wallet()->create([
                'name' => rand(0, 100000) . "_" . $user->name  . "_" . ($user->lname == null ? "wallet" : $user->lname) . "_" . Carbon::now()->year,
                'user_id' => $user->id
            ]);
            return response([
                $this->returnData('user', UserResource::make($user), __('User created succesfully')),
                $this->returnData('wallet', WalletResource::make($user->wallet), __('Wallet created succesfully')),
            ]);
        }

        return $this->returnError(__('Sorry! Failed to create user!'));
    }

    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id)
    {
        $user = $this->userRepositry->getUserByID($id);

        if ($user) {
            return $this->returnData('user', UserResource::make($user), __('Get User succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get user!'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->userRepositry->deleteUser($id);

        return $this->returnSuccessMessage(__('Delete User succesfully!'));
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
        $user = $this->userRepositry->asignRoleToUser($id, $request->roles);

        if ($user) {
            return $this->returnSuccessMessage(__('Roles changed successfully!'));
        }

        return $this->returnError(__('Sorry! User not found'));
    }


    public function updateUser(ProfileUpdateRequest $request, $id)
    {
        $user = User::find($id);
        // check unique email except this user
        if (isset($request->email)) {
            $check = User::where('email', $request->email)->where('email', '!=', $user->email)
                ->first();

            if ($check) {

                return $this->returnError('The email address is already used!');
            }
        }
        if (isset($request->phone)) {
            $check = User::where('phone', $request->phone)->where('phone', '!=', $user->phone)
                ->first();

            if ($check) {

                return $this->returnError('The phone is already used!');
            }
        }

        if ($request->has('image')) {
            unlink($user->image);
        }
        if ($request->has('cover')) {
            unlink($user->cover);
        }

        $user->update(
            $request->only([
                'name',
                'lname',
                'email',
                'image',
                'cover',
                'phone',
            ])
        );


        return $this->returnData('user', UserResource::make($user), 'successful');
    }

    public function updateStatusDriver(Request $request)
    {

        $driver = User::where('id', $request->driver_id)->first();

        $driver->update([
            'active' => $request->active_status,
        ]);

        return $this->returnData('driver', UserResource::make($driver), 'successful');
    }


    public function updateLatLong(Request $request)
    {

        $user = User::where('id', $request->user_id)->first();

        $user->update([
            'lat' => $request->lat,
            'long' => $request->long,
        ]);

        return $this->returnData('user', UserResource::make($user), 'successful');
    }

    public function addDriver(UserRequest $request)
    {
        $request['type'] = 'driver';
        $user = $this->userRepositry->saveUser($request);
        $user->type = 'driver';
        $user->save();
        if ($user) {
            $user->wallet()->create([
                'name' => rand(0, 100000) . "_" . $user->name  . "_" . ($user->lname == null ? "wallet" : $user->lname) . "_" . Carbon::now()->year,
                'user_id' => $user->id
            ]);
            return $this->returnData('user', UserResource::make($user), __('User created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create user!'));
    }
    public function list_driver()
    {
        $users = User::where('type', 'driver')->get();
        return $this->returnData('users', UserResource::collection($users), __('Succesfully'));
    }

    public function updateOnline(Request $request)
    {
        $user = Auth::user();
        $user->online = $request->online;
        $user->save();

        if ($request->online == 1) {

            $order = Order::where('driver_id', 0)->orderBy('id', 'desc')->first();


            if ($order) {

                $order->driver_id = Auth::user()->id;
                $order->save();


                $discount = 0;

                if ($order->codes()->first()) {
                    $c = $order->codes()->first();
                    if ($c->type == 'Fixed') {
                        $discount = $c->value;
                    } else {
                        $discount = ($c->value / 100) * $order->total;
                    }
                }




                $address = Address::find($order->address_id);

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

                $driver_id = 0;

                $drivers = User::where('type', 'driver')->where('online', 1)->get();

                if (count($drivers) > 0) {
                    $driver = User::find($this->getNearByDriverID($order));

                    if (!empty($driver?->id)) {
                        $driver_id = $driver->id;
                    }
                }

                $order->driver_id = $driver_id;
                $order->save();

                $orderfire = app('firebase.firestore')->database()->collection('orders')->document($order->id)
                    ->update([
                        ['path' => 'driver_id', 'value' => Auth::user()->id],
                        ['path' => 'driver_image', 'value' => Auth::user()->image],
                        ['path' => 'driver_name', 'value' => Auth::user()->name],
                        ['path' => 'driver_phone', 'value' => Auth::user()->phone]
                    ]);


                // $orderfire = app('firebase.firestore')->database()->collection('orders')->document($order->id);
                // $orderfire->set([

                //     'created_at' => $order->created_at,
                //     'delivery_fee' => (float)$order->restaurant->delivery_fees,
                //     'discount' => $discount,

                //     'driver_id' => Auth::user()->id,
                //     'driver_image' => Auth::user()->image,
                //     'driver_name' => Auth::user()->name,
                //     'driver_phone' => Auth::user()->phone,

                //     'drop_point_address' => $address->name,
                //     'drop_point_id' => $user->id,
                //     'drop_point_image' => (string)$user->image,
                //     'drop_point_name' => $user->name,
                //     'drop_point_phone' => $user->phone,
                //     'drop_point_position' => array('geohash' => $g->encode($address->lat, $address->long), 'geopoint' =>  new \Google\Cloud\Core\GeoPoint($address->lat, $address->long)),

                //     'final_price' => $order->total - ($discount),
                //     'note' => $order->note,

                //     'order_details' => $fire,

                //     'order_id' => $order->id,
                //     'payment_method' => 'cash',

                //     'pickup_point_address' => $order->restaurant->address,
                //     'pickup_point_id' => $order->restaurant->id,
                //     'pickup_point_image' => $order->restaurant->logo,
                //     'pickup_point_name' => $order->restaurant->name,
                //     'pickup_point_phone' => $order->restaurant->user->phone,
                //     'pickup_point_position' => array('geohash' => $g->encode($order->restaurant->lat, $order->restaurant->long), 'geopoint' =>  new \Google\Cloud\Core\GeoPoint($order->restaurant->lat, $order->restaurant->long)),

                //     'status' => 'hold',
                //     'tax' => 0,
                //     'total_price' => $order->total,
                //     'type' => 'restaurant',
                //     'user_name' => $user->name,

                // ]);
            }
        }

        return $this->returnSuccessMessage(__('Status changed successfully!'));
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
        $drivers = User::where('type', 'driver')->where('online', 1)->get();


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
