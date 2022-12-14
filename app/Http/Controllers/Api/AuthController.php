<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Nette\Utils\Json;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRequest;
use App\Repositorys\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PasswordRequest;
use App\Http\Resources\WalletResource;
use App\Http\Resources\SupplierResource;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Resources\UserResource2;

class AuthController extends Controller
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
    public function login(AuthRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        //dd( Hash::make( $request->password ) );

        Auth::login($user);

        if (!$user) {


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

        if (Auth::user()->type == 'driver') {

            return response(['status' => true, 'code' => 200, 'msg' => __('Log in success'), 'data' => [
                'token' => $accessToken,
                'user' => UserResource::make(Auth::user())
            ]]);
        }

        if (Auth::user()->type == 'restaurant') {

            return response(['status' => true, 'code' => 200, 'msg' => __('Log in success'), 'data' => [
                'token' => $accessToken,
                'user' => UserResource2::make(Auth::user())
            ]]);
        }
    }


    public function store(UserRequest $request)
    {
        $user = $this->userRepositry->saveUser($request);

        $otp = $this->sendOTP($request->phone);

        $user->otp = $otp;
        $user->save();

        Auth::login($user);

        $accessToken = $user->createToken('authToken')->accessToken;

        if ($user) {

            if (Auth::user()->type == 'user') {
                $user->wallet()->create([
                    'name' => rand(0, 100000) . "_" . $user->name  . "_" . ($user->lname == null ? "wallet" : $user->lname) . "_" . Carbon::now()->year,
                    'user_id' => $user->id
                ]);
                return response(['status' => true, 'code' => 200, 'msg' => __('User created succesfully'), 'data' => [
                    'token' => $accessToken,
                    'user' => UserResource::make(Auth::user()),
                    'wallet' => WalletResource::make(Auth::user()->wallet),
                ]]);
            }
        }

        return $this->returnError('Sorry! Failed to create user!');
    }

    public function check(Request $request)
    {

        if ($this->checkOTP($request->phone, $request->code)) {
            return $this->returnSuccessMessage('success');
        } else {
            return $this->returnError('Sorry! code not correct');
        }
    }


    public function list(Request $request)
    {
        $users = User::where('type', 'suppliers')->get();

        return $this->returnData('suppliers', SupplierResource::collection($users), 'succesfully');
    }


    public function storeSupplier(UserRequest $request)
    {
        // store user information

        $user               = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->phone        = $request->phone;
        $user->image        = $request->image;
        $user->cover        = $request->cover;
        $user->type         = 'suppliers';
        $user->location     = $request->location;
        $user->description  = $request->description;
        $user->password     = Hash::make($request->password);
        $user->save();
        // assign new role to the user
        //$role = $user->assignRole($request->role);

        if ($user) {


            return $this->returnData('supplier', SupplierResource::make($user), 'User created succesfully');
        }


        return $this->returnError('Sorry! Failed to create user!');
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

    public function password(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {

            $otp = $this->sendOTP($request->phone);

            $user->otp = $otp;
            $user->save();

            return $this->returnSuccessMessage('Code was sent');
        }

        return $this->returnError('Code not sent User not found');
    }


    public function changePassword(PasswordChangeRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if ($user) {

            User::find($user->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);

            return $this->returnSuccessMessage('Password has been changed');
        }

        return $this->returnError('Password not matched!');
    }


    public function updateProfile(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        // check unique email except this user
        if (isset($request->email)) {
            $check = User::where('email', $request->email)->where('email', '!=', $user->email)
                ->first();

            if ($check) {

                return $this->returnError('The email address is already used!');
            }
        }
        if (isset($request->phone)) {
            $check = User::where('phone', $request->phone)->where('phone', '!=', $user->phone)
                ->first();

            if ($check) {

                return $this->returnError('The phone is already used!');
            }
        }

        if ($request->has('image') && $user->image) {
            unlink($user->image);
        }

        if ($request->has('cover') && $user->cover) {
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


        return $this->returnData('user', UserResource::make(Auth::user()), 'successful');
    }

    public function updatePassword(PasswordRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'password' => bcrypt($request->new_password),
        ]);
        return $this->returnSuccessMessage('Password updated successfully');
    }

    public function sociallogin(Request $request)
    {

        $user = User::where([
            ['email', $request->email]
        ])->first();

        if ($user) {

            $accessToken = $user->createToken('authToken')->accessToken;

            //$user->token = $request->token;
            $user->save();
            Auth::login($user);

            return response(['status' => true, 'code' => 200, 'msg' => 'success', 'data' => [
                'token' => $accessToken,
                'user' => $user
            ]]);
        }


        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'phone' => $request->username,
            'image' => '',
            'password' => Hash::make('1234'),
        ]);

        $user->wallet()->create([
            'name' => rand(0, 100000) . "_" . $user->name  . "_" . ($user->lname == null ? "wallet" : $user->lname) . "_" . Carbon::now()->year,
            'user_id' => $user->id
        ]);


        // assign new role to the user
        // $role = $user->assignRole('Member');

        Auth::login($user);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['status' => true, 'code' => 200, 'msg' => 'success', 'data' => [
            'token' => $accessToken,
            'user' => UserResource::make(Auth::user()),
            'wallet' => WalletResource::make(Auth::user()->wallet),
        ]]);
    }

    public function updateDeviceToken(Request $request)
    {
        $user = User::find(Auth::user()->id)->update(['device_token' => $request->device_token]);

        return $this->returnData('user', UserResource::make(User::find(Auth::user()->id)), 'successful');
    }
    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return $this->returnSuccessMessage('Logged out succesfully!');
    }

    public function sendOTP($phone)
    {
        $otp = mt_rand(1000, 9999);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "://82.212.81.40:8080/websmpp/websms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "user=Wecan&pass=Suh12345&sid=TAKKEH&mno=" . $phone . "&text=Your OTP is " . $otp . " for your account&type=1&respformat=json",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer 2c1d0706b21b715ff1e5a480b8360d90"
            ),
        ));

        curl_exec($curl);

        curl_close($curl);

        return $otp;
    }

    public function checkOTP($phone, $otp)
    {
        $user = User::where('phone', $phone)->first();

        if ((string)$user->otp == (string)$otp) {
            return true;
        }

        return false;
    }
}
