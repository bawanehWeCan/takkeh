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
use Nette\Utils\Json;

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

    public function countries(){
        return $this->returnData('countries', Countries::getList('en', 'json'), 'succesfully');
    }

    public function store(UserRequest $request)
    {
        $user = $this->userRepositry->saveUser($request);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.releans.com/v2/otp/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "sender=We Can&mobile=" . $request->phone . "&channel=sms",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer 48e9d03083973825dc3a697ee09f7f39"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

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


        return $this->returnError('Sorry! Failed to create user!');
    }

    public function check(Request $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.releans.com/v2/otp/check",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "mobile=" . $request->phone . "&code=" . $request->code,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer 48e9d03083973825dc3a697ee09f7f39"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $d = Json::decode($response);

        if ($d->status == 200) {
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
        $user = User::where( 'phone',$request->phone )->first();
        if ($user) {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.releans.com/v2/otp/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "sender=We Can&mobile=" . $request->phone . "&channel=sms",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer 48e9d03083973825dc3a697ee09f7f39"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            return $this->returnSuccessMessage('Code was sent');
        }

        return $this->returnError('Code not sent User not found');
    }


    public function changePassword(PasswordChangeRequest $request)
    {
        $user = User::where( 'phone',$request->phone )->first();

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
            $check = User::where('email', $request->email)
                ->first();

            if ($check) {

                return $this->returnError('The email address is already used!');
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


        return $this->returnData('user', UserResource::make(Auth::user()), 'successful');
    }


    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return $this->returnSuccessMessage('Logged out succesfully!');
    }
}
