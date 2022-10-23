<?php

namespace App\Repositorys;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{

    /**
     * @var User
     */
    protected $user;

    /**
     * __construct function
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * allUsers function
     *
     * @return Collection
     */
    public function allUsers()
    {
        $users = $this->user->where('type', 'user')->get();
        return $users;
    }

    /**
     * saveUser function
     *
     * @param Array $data
     * @return void
     */
    public function saveUser($data)
    {

        $user = new $this->user;
        $user->name = $data['name'];
        $user->lname = $data['last_name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
         $user->image = $data['image'];
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user->fresh();
    }

    /**
     * getUserByID function
     *
     * @return Collection
     */
    public function getUserByID($id)
    {
        $user = $this->user->where('id', $id)->firstOrFail();
        return $user;
    }

    /**
     * deleteUser function
     *
     * @return bool
     */
    public function deleteUser($id)
    {
        User::destroy($id);
    }

    /**
     * asignRoleToUser function
     *
     * @return Collection
     */
    public function asignRoleToUser($id, $roles)
    {
        try {

            $user = $this->user->where('id', $id)->firstOrFail();
            $user->syncRoles($roles);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
