<?php

namespace App\Repositorys;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class CategoryRepository
{

    /**
     * @var Category
     */
    protected $category;

    /**
     * __construct function
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * allCategorys function
     *
     * @return Collection
     */
    public function allCategorys()
    {
        $categorys = $this->category->all();
        return $categorys;
    }

    /**
     * saveCategory function
     *
     * @param Array $data
     * @return void
     */
    public function saveCategory($data)
    {

        $category = new $this->category;
        $category->name = $data['name'];
        $category->image = $data['image'];
        $category->save();

        return $category->fresh();
    }

    /**
     * getCategoryByID function
     *
     * @return Collection
     */
    public function getCategoryByID($id)
    {
        $category = $this->category->where('id', $id)->firstOrFail();
        return $category;
    }

    /**
     * deleteCategory function
     *
     * @return bool
     */
    public function deleteCategory($id)
    {
        Category::destroy($id);
    }

}
