<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Size;
use App\Models\Extra;
use App\Models\Group;
use App\Models\Product;
use App\Models\Category;
use App\Models\GroupItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
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
        $resturant = Restaurant::find($request->restaurant_id);
        if (!$resturant) {
            return $this->returnError('This resturant is not exists');
        }

        return $this->store($request);
    }

    public function store( $data )
    {

        DB::beginTransaction();
        $data['name'] = ['en'=>isset($data['name_en'])?$data['name_en']:$data['name'],'ar'=>isset($data['name_en'])?$data['name_en']:$data['name']];
        $data['content'] = ['en'=>isset($data['content_en'])?$data['content_en']:$data['content'],'ar'=>isset($data['content_en'])?$data['content_en']:$data['content']];
        $product = $this->repositry->save( $data );

        $groupRepo      = new Repository( app( Group::class ) );
        $groupItemRepo  = new Repository( app( GroupItem::class ) );

        foreach ($data['groups'] as $group) {
            $group['product_id'] = $product->id;
            $group['name'] = ['en'=>isset($group['name_en'])?$group['name_en']:$group['name'],'ar'=>isset($group['name_en'])?$group['name_en']:$group['name']];
            $group['type'] = ['en'=>isset($group['type_en'])?$group['type_en']:$group['type'],'ar'=>isset($group['type_en'])?$group['type_en']:$group['type']];
            // $group['type'] = ['en'=>isset($group['type_en'])?$group['type_en']:$group['type'],'ar'=>isset($group['type_en'])?$group['type_en']:$group['type']];
            $model = $groupRepo->save( $group );

            // dd( $model );

            foreach ($group['items'] as $item) {
                $item['group_id'] = $model['id'];
                $item['name'] = ['en'=>isset($item['name_en'])?$item['name_en']:$item['name'],'ar'=>isset($item['name_en'])?$item['name_en']:$item['name']];
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

    public function lookfor(Request $request){

        return $this->search('name',$request->keyword);

    }

    public function addCategory( Request $request ){

        $category   = Category::find( $request->category_id );
        $product = $this->model->find( $request->product_id );
        if (!$category || !$product) {
            return $this->returnError('Some thing has been wrong');
        }
        $cat = $product->categories()->save($category);
        $product->push($cat);
        return $this->returnData( 'data' , $this->resource::make( $product ), __('Succesfully'));

    }

    public function deleteCategory( Request $request ){

        $category   = Category::find( $request->category_id );
        $product = $this->model->find( $request->product_id );
        if (!$category || !$product) {
            return $this->returnError('Some thing has been wrong');
        }
        $cat = $product->categories()->detach($category);
        $product->push($cat);
        return $this->returnSuccessMessage( 'successful delete category from product');

    }

}
