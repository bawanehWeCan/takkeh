<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositorys\CategoryRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    use ResponseTrait;

    /**
     * @var CategoryRepositry
     */
    protected CategoryRepository $categoryRepositry;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CategoryRepository $categoryRepositry)
    {
        $this->categoryRepositry =  $categoryRepositry;
    }

    /**
     * list function
     *
     * @return void
     */
    public function list()
    {
        $categories = Category::whereBetween('id',[ 1,16 ])->get();
        return $this->returnData('Categorys', CategoryResource::collection($categories), __('Succesfully'));
    }

    /**
     * store function
     *
     * @param CategoryRequest $request
     * @return void
     */
    public function store(CategoryRequest $request)
    {
        $request['name'] = ['en'=>$request['name_en'],'ar'=>$request['name_ar']];

        $category = $this->categoryRepositry->saveCategory($request->except(['name_en','name_ar']));

        return $this->returnData('Category', CategoryResource::make($category), __('Category created succesfully'));

    }

    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id)
    {
        $category = $this->categoryRepositry->getCategoryByID($id);

        if ($category) {
            return $this->returnData('Category', CategoryResource::make($category), __('Get Category succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get Category!'));
    }

    public function edit( Request $request, $id ){
        $category = Category::find( $id );

        $request['name'] = ['en'=>$request['name_en'],'ar'=>$request['name_ar']];

        $category->update( $request->except(['name_en','name_ar']) );

        if ($category) {
            return $this->returnData('Category', CategoryResource::make($category), __('Get Category succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get Category!'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->categoryRepositry->deleteCategory($id);

        return $this->returnSuccessMessage(__('Delete Category succesfully!'));
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
        $category = $this->categoryRepositry->asignRoleToCategory($id, $request->roles);

        if ($category) {
            return $this->returnSuccessMessage(__('Roles changed successfully!'));
        }

        return $this->returnError(__('Sorry! Category not found'));
    }
}
