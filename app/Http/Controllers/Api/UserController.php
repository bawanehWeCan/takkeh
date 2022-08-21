<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Repositorys\UserRepository;
use App\Traits\ResponseTrait;

class UserController extends Controller
{

    use ResponseTrait;

    /**
     * @var UserRepository
     */
    protected UserRepository $userRepositry;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepositry)
    {
        $this->userRepositry =  $userRepositry;
    }

    /**
     * list function
     *
     * @return void
     */
    public function list()
    {
        $users = $this->userRepositry->allUsers();
        return $this->returnData('users', UserResource::collection($users), __('Succesfully'));
    }

    /**
     * store function
     *
     * @param UserRequest $request
     * @return void
     */
    public function store(UserRequest $request)
    {
        $user = $this->userRepositry->saveUser($request);

        if ($user) {
            return $this->returnData('user', UserResource::make($user), __('User created succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to create user!'));
    }

    /**
     * profile function
     *
     * @param [type] $id
     * @return void
     */
    public function profile($id)
    {
        $user = $this->userRepositry->getUserByID($id);

        if ($user) {
            return $this->returnData('user', UserResource::make($user), __('Get User succesfully'));
        }

        return $this->returnError(__('Sorry! Failed to get user!'));
    }

    /**
     * delete function
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $this->userRepositry->deleteUser($id);

        return $this->returnSuccessMessage(__('Delete User succesfully!'));
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
        $user = $this->userRepositry->asignRoleToUser($id, $request->roles);

        if ($user) {
            return $this->returnSuccessMessage(__('Roles changed successfully!'));
        }

        return $this->returnError(__('Sorry! User not found'));
    }
}
