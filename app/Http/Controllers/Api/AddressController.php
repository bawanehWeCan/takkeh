<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use App\Models\Extra;
use App\Models\Address;
use App\Repositories\Repository;
use App\Repositorys\SizeRepository;
use App\Repositorys\ExtraRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use App\Repositorys\AddressRepository;
use App\Http\Controllers\ApiController;
use App\Http\Resources\AddressResource;
use Exception;

class AddressController extends ApiController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = AddressResource::class;
        $this->model = app( Address::class );
        $this->repositry =  new Repository( $this->model ) ;
    }

    /**
     * @param AddressRequest $request
     * @return void
     */
    public function save( AddressRequest $request ){

        try {
            $request['user_id'] = Auth::user()->id;
            return $this->store( $request->all() );
        } catch (Exception $e) {
            dd( $e );
        }

    }

    public function user_address(){
        $address = Auth::user()->addresses;

        if ($address) {
            return $this->returnData('data',  $this->resource::collection( $address ), __('Get  succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get !'));
    }

}
