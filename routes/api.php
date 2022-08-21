<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\ServiceController;

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
Route::get('services', [ServiceController::class, 'list']);
Route::post('service-create', [ServiceController::class, 'store']);
Route::get('services/{id}', [ServiceController::class, 'profile']);
Route::get('services/delete/{id}', [ServiceController::class, 'delete']);


Route::middleware(['auth:api'])->group(function () {

	Route::get('logout', [AuthController::class, 'logout']);

	Route::get('profile', [AuthController::class, 'profile']);
	Route::post('change-password', [AuthController::class, 'changePassword']);
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
});
