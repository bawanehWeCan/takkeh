<?php

namespace App\Repositorys;

use App\Http\Resources\SpecialResource;
use App\Models\Special;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class SpecialRepository
{

    /**
     * @var Special
     */
    protected $special;

    /**
     * __construct function
     *
     * @param Special $special
     */
    public function __construct(Special $special)
    {
        $this->special = $special;
    }

    /**
     * allSpecials function
     *
     * @return Collection
     */
    public function allSpecials()
    {
        $specials = $this->special->all();
        return $specials;
    }

    /**
     * saveSpecial function
     *
     * @param Array $data
     * @return void
     */
    public function saveSpecial($data)
    {

        $special = new $this->special;
        $special->image = $data['image'];
        $special->save();

        return $special->fresh();
    }

    /**
     * getSpecialByID function
     *
     * @return Collection
     */
    public function getSpecialByID($id)
    {
        $special = $this->special->where('id', $id)->firstOrFail();
        return $special;
    }

    /**
     * deleteSpecial function
     *
     * @return bool
     */
    public function deleteSpecial($id)
    {
        Special::destroy($id);
    }

}
