<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ProductResource;
use App\Repositorys\ProductRepository;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Extra;
use App\Repositorys\ExtraRepository;
use App\Models\Size;
use App\Repositorys\SizeRepository;

class ProductController extends ApiController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->resource = ProductResource::class;
        $this->model = app( Product::class );
        $this->repositry =  new ProductRepository( $this->model ) ;
    }

    /**
     * @param ProductRequest $request
     * @return void
     */
    public function save( ProductRequest $request ){
        return $this->store( $request );
    }

    public function store( $data )
    {
        $extraRepo  = new ExtraRepository( app(Extra::class) );
        $sizeRepo   = new SizeRepository( app(Size::class) );
        $model = $this->repositry->save( $data );

        foreach ($data['sizes'] as  $value) {
            $value['product_id'] = $model->id;
            $extra = $extraRepo->save( $value );
        }

        foreach ($data['extras'] as  $value) {
            $value['product_id'] = $model->id;
            $extra = $sizeRepo->save( $value );
        }

        
        

        if ($model) {
            return $this->returnData( 'data' , new $this->resource( $model ), __('Succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create !'));
    }

    // public function pagination( $lenght = 10 )
    // {

    //     $data =  $this->repositry->pagination( $lenght );

    //     return $this->returnData( 'data' , ProductResource::collection( $data ), __('Succesfully'));


    // }

}
