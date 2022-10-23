<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\SpecialController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\ِApi\PromocodeController;

use Nette\Utils\Json;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Auth
Route::post('login', [AuthController::class, 'login']);

Route::post('/user-reg', [AuthController::class, 'store']);

Route::post('/otb-check', [AuthController::class, 'check']);

Route::post('/password-otb', [AuthController::class, 'password']);

Route::post('change-password', [AuthController::class, 'changePassword']);




//supp
Route::post('/user-supplier', [AuthController::class, 'storeSupplier']);
Route::get('/suppliers', [AuthController::class, 'list']);

// cat

//only those have manage_user permission will get access
Route::get('categories', [CategoryController::class, 'list']);
Route::post('category-create', [CategoryController::class, 'store']);
Route::get('category/{id}', [CategoryController::class, 'profile']);
Route::get('category/delete/{id}', [CategoryController::class, 'delete']);




// cat

//only those have manage_user permission will get access
Route::get('sliders', [SliderController::class, 'list']);
Route::post('sliders-create', [SliderController::class, 'store']);
Route::get('sliders/{id}', [SliderController::class, 'profile']);
Route::get('sliders/delete/{id}', [SliderController::class, 'delete']);


// cat

//only those have manage_user permission will get access
Route::get('offers', [OfferController::class, 'list']);
Route::post('offers-create', [OfferController::class, 'store']);
Route::get('offers/{id}', [OfferController::class, 'profile']);
Route::get('offers/delete/{id}', [OfferController::class, 'delete']);



// cat

//only those have manage_user permission will get access
Route::get('specials', [SpecialController::class, 'list']);
Route::post('specials-create', [SpecialController::class, 'store']);
Route::get('specials/{id}', [SpecialController::class, 'profile']);
Route::get('specials/delete/{id}', [SpecialController::class, 'delete']);


// cat

//only those have manage_user permission will get access
Route::get('services', [ServiceController::class, 'list']);
Route::post('service-create', [ServiceController::class, 'store']);
Route::get('services/{id}', [ServiceController::class, 'profile']);
Route::get('services/delete/{id}', [ServiceController::class, 'delete']);


//only those have manage_user permission will get access
Route::get('restaurants', [RestaurantController::class, 'getPagination']);
Route::post('restaurants-create', [RestaurantController::class, 'save']);
Route::get('restaurants/{id}', [RestaurantController::class, 'view']);
Route::get('restaurants/delete/{id}', [RestaurantController::class, 'delete']);

Route::post('restaurants/category', [RestaurantController::class, 'addCategory']);


Route::post('restaurants/search/{value}', [RestaurantController::class, 'search']);



//only those have manage_user permission will get access
Route::get('products', [ProductController::class, 'pagination']);
Route::post('products-create', [ProductController::class, 'save']);
Route::get('products/{id}', [ProductController::class, 'view']);
Route::get('products/delete/{id}', [ProductController::class, 'delete']);



Route::middleware(['auth:api'])->group(function () {

	Route::get('logout', [AuthController::class, 'logout']);

	Route::get('profile', [AuthController::class, 'profile']);

	Route::post('update-profile', [AuthController::class, 'updateProfile']);

	//only those have manage_user permission will get access
	Route::get('/users', [UserController::class, 'list']);
	Route::post('/user-create', [UserController::class, 'store']);
	Route::get('/user/{id}', [UserController::class, 'profile']);
	Route::get('/user/delete/{id}', [UserController::class, 'delete']);
	Route::post('/user/change-role/{id}', [UserController::class, 'changeRole']);

	//only those have manage_role permission will get access
	Route::group(['middleware' => 'can:manage_role|manage_user'], function () {
		Route::get('/roles', [RolesController::class, 'list']);
		Route::post('/role/create', [RolesController::class, 'store']);
		Route::get('/role/{id}', [RolesController::class, 'show']);
		Route::get('/role/delete/{id}', [RolesController::class, 'delete']);
		Route::post('/role/change-permission/{id}', [RolesController::class, 'changePermissions']);
	});


	//only those have manage_permission permission will get access
	Route::group(['middleware' => 'can:manage_permission|manage_user'], function () {
		Route::get('/permissions', [PermissionController::class, 'list']);
		Route::post('/permission/create', [PermissionController::class, 'store']);
		Route::get('/permission/{id}', [PermissionController::class, 'show']);
		Route::get('/permission/delete/{id}', [PermissionController::class, 'delete']);
	});

    //only those have manage_user permission will get access
    Route::get('promo-code', [PromocodeController::class, 'list']);
    Route::post('promo-code-create', [PromocodeController::class, 'save']);
    Route::get('promo-code/{id}', [PromocodeController::class, 'view']);
    Route::get('promo-code/delete/{id}', [PromocodeController::class, 'delete']);
});



Route::post('make-order',[ OrderController::class, 'store' ]);
Route::post('update-order',[ OrderController::class, 'update' ]);



//pages

Route::get('pages', [PageController::class, 'list']);
Route::post('pages-create', [PageController::class, 'save']);
Route::get('pages/{id}', [PageController::class, 'profile']);
Route::get('pages/delete/{id}', [PageController::class, 'delete']);


