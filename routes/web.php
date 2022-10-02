<?php

use App\Http\Controllers\OrderController;
use App\Models\Order;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('orders', [OrderController::class,'index'] );


Route::get('/insert', function() {
    $order = Order::find(1);
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
        'position' => array( 'geohas'=>'alaa','geopoint' =>  ['aaa','aaa' ] ),
    ]);
echo "<h1>".'inserted'."</h1>";
});

