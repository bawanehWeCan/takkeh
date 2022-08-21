<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repositorys\CategoryRepository;
use App\Traits\ResponseTrait;

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
        $categories = $this->categoryRepositry->allCategorys();
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
        $category = $this->categoryRepositry->saveCategory($request);

        if ($category) {
            return $this->returnData('Category', CategoryResource::make($category), __('Category created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create Category!'));
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
