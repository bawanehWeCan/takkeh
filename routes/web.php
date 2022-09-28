<?php

use App\Http\Controllers\OrderController;
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
    $stuRef = app('firebase.firestore')->database()->collection('User')->newDocument();
    $stuRef->set([
       'firstname' => 'Seven',
       'lastname' => 'Stac',
       'age'    => 19
]);
echo "<h1>".'inserted'."</h1>";
});

