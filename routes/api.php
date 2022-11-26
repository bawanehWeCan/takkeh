<?php

use Nette\Utils\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SpecialController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountriesController;
use App\Http\Controllers\Api\PromoCodeController;


use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\TransactionController;

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
//home
Route::get('/home', [HomeController::class,'home']);

//Auth
Route::post('login', [AuthController::class, 'login']);

Route::post('/user-reg', [AuthController::class, 'store']);

Route::post('/otb-check', [AuthController::class, 'check']);

Route::post('/password-otb', [AuthController::class, 'password']);

Route::post('change-password', [AuthController::class, 'changePassword']);



//Reviews
Route::get('reviews', [ReviewController::class, 'index']);

Route::get('review/{id}', [ReviewController::class, 'show']);
Route::post('review/delete/{id}', [ReviewController::class, 'destroy']);



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
Route::get('restaurants', [RestaurantController::class, 'list_reviews']);
Route::post('restaurants-create', [RestaurantController::class, 'save']);
Route::get('restaurants/{id}', [RestaurantController::class, 'view']);
Route::get('restaurants/delete/{id}', [RestaurantController::class, 'delete']);

Route::post('restaurants/category', [RestaurantController::class, 'addCategory']);

Route::post('restaurants/search', [RestaurantController::class, 'lookfor']);
Route::post('restaurant/review', [RestaurantController::class, 'addReviewToResturant']);
Route::post('restaurant/availabitlity', [RestaurantController::class, 'updateAvailability']);

Route::get('restaurant/products/{id}', [RestaurantController::class, 'resturantWithProducts']);

// Route::post('restaurants/search/{value}', [RestaurantController::class, 'search']);

//tags
Route::get('tags', [TagController::class, 'list']);
Route::post('tags-create', [TagController::class, 'save']);
Route::get('tags/{id}', [TagController::class, 'view']);
Route::get('tags/delete/{id}', [TagController::class, 'delete']);

//only those have manage_user permission will get access
Route::get('products', [ProductController::class, 'pagination']);
Route::post('products-create', [ProductController::class, 'save']);
Route::get('products/{id}', [ProductController::class, 'view']);
Route::get('products/delete/{id}', [ProductController::class, 'delete']);
Route::post('products/category', [ProductController::class, 'addCategory']);



Route::middleware(['auth:api'])->group(function () {

	Route::get('logout', [AuthController::class, 'logout']);

	Route::get('profile', [AuthController::class, 'profile']);

	Route::post('update-profile', [AuthController::class, 'updateProfile']);
	Route::post('update-password', [AuthController::class, 'updatePassword']);


    Route::post('/review/edit/{id}', [ReviewController::class, 'editRev']);
    Route::post('review-create', [ReviewController::class, 'save']);

	//only those have manage_user permission will get access
	Route::get('/users', [UserController::class, 'list']);
	Route::post('/user-create', [UserController::class, 'store']);
	Route::get('/user/{id}', [UserController::class, 'profile']);
	Route::post('/user/update/{id}', [UserController::class, 'updateUser']);
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

    // Address
    Route::get('address', [AddressController::class, 'pagination']);
    Route::post('address-create', [AddressController::class, 'save']);
    Route::get('address/{id}', [AddressController::class, 'view']);
    Route::get('address/delete/{id}', [AddressController::class, 'delete']);
    Route::get('my-address', [AddressController::class, 'user_address']);

    //only those have manage_user permission will get access
    Route::get('promo-code', [PromoCodeController::class, 'list']);
    Route::post('promo-code-create', [PromoCodeController::class, 'save']);
    Route::get('promo-code/{id}', [PromoCodeController::class, 'view']);
    Route::get('promo-code/delete/{id}', [PromoCodeController::class, 'delete']);
    Route::post('add-code-to-order', [PromoCodeController::class, 'addCodeOrder']);

    Route::get('faq', [FaqController::class, 'list']);
    Route::post('faq-create', [FaqController::class, 'save']);
    Route::get('faq/{id}', [FaqController::class, 'view']);
    Route::get('faq/delete/{id}', [FaqController::class, 'delete']);

    Route::get('wallet', [WalletController::class, 'list']);
    Route::post('wallet-create', [WalletController::class, 'save']);
    Route::get('wallet/{id}', [WalletController::class, 'view']);
    Route::get('my-wallet', [WalletController::class, 'myWallet']);
    Route::get('wallet/delete/{id}', [WalletController::class, 'delete']);

    Route::post('transaction', [TransactionController::class, 'transaction']);
    Route::get('my-orders',[ OrderController::class, 'user_orders' ]);

});

Route::get('country-list', [CountriesController::class, 'getCountries']);


Route::post('make-order',[ OrderController::class, 'store' ]);
Route::post('update-order',[ OrderController::class, 'update' ]);



//pages

Route::get('pages', [PageController::class, 'list']);
Route::post('pages-create', [PageController::class, 'save']);
Route::get('pages/{id}', [PageController::class, 'profile']);
Route::get('pages/delete/{id}', [PageController::class, 'delete']);


