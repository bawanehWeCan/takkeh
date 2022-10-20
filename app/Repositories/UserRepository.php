<?php

namespace App\Repositorys;

use Illuminate\Support\Facades\Hash;

class UserRepository extends AbstractRepository
{

    /**
     * @var Model
     */
    //protected $model = Restaurant::class;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * allUsers function
     *
     * @return Collection
     */
    public function all()
    {
        $users = $this->user->where('type', 'user')->get();
        return $users;
    }

    /**
     * saveRestaurant function
     *
     * @param Array $data
     * @return void
     */
    public function save($data)
    {

        $model = new $this->model;
        $model->name = $data['name'];
        $model->lname = $data['last_name'];
        $model->email = $data['email'];
        $model->phone = $data['phone'];
        $model->image = $data['image'];
        $model ->password = Hash::make($data['password']);
        $model->save();

        return $model->fresh();

    }

    /**
     * asignRoleToUser function
     *
     * @return Collection
     */
    public function asignRoleToUser($id, $roles)
    {
        try {

            $user = $this->model->where('id', $id)->firstOrFail();
            $user->syncRoles($roles);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
