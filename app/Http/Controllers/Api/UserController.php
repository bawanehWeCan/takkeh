<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ResponseTrait;
use App\Http\Requests\UserRequest;
use App\Repositorys\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\WalletResource;
use App\Http\Requests\RoleChangeRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Carbon\Carbon;

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
            $user->wallet()->create([
                'name'=>rand(0,100000) . "_" . $user->name  . "_" . $user->lname . "_" . Carbon::now()->year,
                'user_id'=>$user->id
            ]);
            return response([
                $this->returnData('user', UserResource::make($user), __('User created succesfully')),
                $this->returnData('wallet', WalletResource::make($user->wallet), __('Wallet created succesfully')),
            ]);
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


    public function updateUser(ProfileUpdateRequest $request,$id)
    {
        $user = User::find($id);
        // check unique email except this user
        if (isset($request->email)) {
            $check = User::where('email', $request->email)->where('email','!=',$user->email)
                ->first();

            if ($check) {

                return $this->returnError('The email address is already used!');
            }
        }
        if (isset($request->phone)) {
            $check = User::where('phone', $request->phone)->where('phone','!=',$user->phone)
                ->first();

            if ($check) {

                return $this->returnError('The phone is already used!');
            }
        }

        if ($request->has('image')) {
            unlink($user->image);
        }
        if ($request->has('cover')) {
            unlink($user->cover);
        }

        $user->update(
            $request->only([
                'name',
                'lname',
                'email',
                'image',
                'cover',
                'phone',
            ])
        );


        return $this->returnData('user', UserResource::make($user), 'successful');
    }

}
