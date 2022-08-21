<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositorys\UserRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseTrait;

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
    public function login(AuthRequest $request)
    {
        $auth = Auth::attempt(
            $request->only([
                'email',
                'password',
            ])
        );
        if (!$auth) {


            return response()->json([
                'status' => false,
                'code' => 500,
                'msg' => __('Invalid credentials!'),
            ], 500);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        if (Auth::user()->type == 'user') {

            return response(['status' => true, 'code' => 200, 'msg' => __('Log in success'), 'data' => [
                'token' => $accessToken,
                'user' => UserResource::make(Auth::user())
            ]]);
        } 
    }

    public function store(UserRequest $request)
    {
        $user = $this->userRepositry->saveUser($request);

        Auth::login($user);

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        if ($user) {
            // return $this->returnData( 'user', UserResource::make($user), '');

            if (Auth::user()->type == 'user') {

                return response(['status' => true, 'code' => 200, 'msg' => __('User created succesfully'), 'data' => [
                    'token' => $accessToken,
                    'user' => UserResource::make(Auth::user())
                ]]);
            }
        }


        return $this->returnError( 'Sorry! Failed to create user!');
    }
    

    public function list(Request $request)
    {
        $users = User::where('type','suppliers')->get();

        return $this->returnData( 'suppliers', SupplierResource::collection($users), 'succesfully');
    }


    public function storeSupplier(UserRequest $request)
    {
        // store user information

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->image = $request->image;
        $user->cover = $request->cover;
        $user->type = 'suppliers';
        $user->location = $request->location;
        $user->description = $request->description;
        $user->password = Hash::make($request->password);
        $user->save();
        // assign new role to the user
        //$role = $user->assignRole($request->role);

        if ($user) {


            return $this->returnData('supplier', SupplierResource::make($user), 'User created succesfully');
        }


        return $this->returnError( 'Sorry! Failed to create user!');
    }



    public function profile(Request $request)
    {
        $user = Auth::user();
        // $roles = $user->getRoleNames();
        // $permission = $user->getAllPermissions();

        // return response([
        //     'user' => $user,
        //     'success' => 1,
        // ]);


        return $this->returnData('user', UserResource::make(Auth::user()), 'successful');
    }


    public function changePassword(PasswordChangeRequest $request)
    {

        // match old password
        if (Hash::check($request->old_password, Auth::user()->password)) {
            User::find(auth()->user()->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);

            return $this->returnSuccessMessage( 'Password has been changed');
        }

        return $this->returnError( 'Password not matched!');
    }


    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        // check unique email except this user
        if (isset($request->email)) {
            $check = User::where('email', $request->email)
                ->first();

            if ($check) {

                return $this->returnError( 'The email address is already used!');
            }
        }

        $user->update(
            $request->only([
                'name',
                'email',
                'image',
                'phone',
            ])
        );


        return $this->returnData( 'user', UserResource::make(Auth::user()), 'successful');
    }


    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return $this->returnSuccessMessage( 'Logged out succesfully!');
    }
}
