<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Size;
use App\Models\Extra;
use App\Models\Group;
use App\Models\Product;
use App\Models\GroupItem;
use App\Models\Restaurant;
use App\Repositories\Repository;
use Illuminate\Support\Facades\DB;
use App\Repositorys\SizeRepository;
use App\Repositorys\ExtraRepository;
use App\Http\Requests\ProductRequest;
use App\Repositorys\ProductRepository;
use App\Http\Controllers\ApiController;
use App\Http\Resources\ProductResource;

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
        $this->model = app(Product::class);
        $this->repositry =  new ProductRepository($this->model);
    }

    /**
     * @param ProductRequest $request
     * @return void
     */
    public function save(ProductRequest $request)
    {
        $resturant = Restaurant::find($request->resturant_id);
        if (!$resturant) {
            return $this->returnError('This resturant is not exists');
        }
        return $this->store($request);
    }

    public function store($data)
    {


        DB::beginTransaction();

        $product = $this->repositry->save( $data );

        $groupRepo      = new Repository( app( Group::class ) );
        $groupItemRepo  = new Repository( app( GroupItem::class ) );

        foreach ($data['groups'] as $group) {
            $group['product_id'] = $product->id;

            $model = $groupRepo->save( $group );

            // dd( $model );

            foreach ($group['items'] as $item) {
                $item['group_id'] = $model['id'];
                $groupItemRepo->save($item);
            }
        }
        DB::commit();

        if ($product) {
            return $this->returnData( 'data' , new $this->resource( $product ), __('Succesfully'));
        }else{
            DB::rollback();
            return $this->returnError(__('Sorry! Failed to create !'));
        }
    }







    // public function pagination( $lenght = 10 )
    // {

    //     $data =  $this->repositry->pagination( $lenght );

    //     return $this->returnData( 'data' , ProductResource::collection( $data ), __('Succesfully'));


    // }

    public function lookfor(ProductRequest $request){

        return $this->search('name',$request->keyword);

    }

}
