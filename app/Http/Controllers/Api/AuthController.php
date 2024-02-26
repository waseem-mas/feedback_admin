<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GlobalResponseTrait;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use GlobalResponseTrait;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    
    public function register(Request $request){
        try {
            $response['token'] = '345667';
            $response['user'] = ['name' => 'waseem' , 'designation' => 'developer'];
            return $this->returnResponse('User registered Successfully', $response, 200);

            $data = $request->only([
                'first_name',
                'last_name',
                'email',
                'password',
                'confirm_password'
            ]);

            $validator = Validator::make(
                $data,
                [
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => [
                        'required',
                        'string',
                        'min:5',
                        'required_with:confirm_password',
                        'same:confirm_password'
                    ],
                ],
                [
                    'first_name.required' => 'First Name is required',
                    'last_name.unique' => 'Last Name is required',
                    'email.unique' => 'Email Already Exists. Please Sign- In!',

                ]
            );

            if ($validator->fails()) {
                return $this->returnResponseError(422, 'Parameters are not valid.', $validator->errors());
            }

            $signup_data = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ];

            $user = $this->user->create($signup_data);
            $response['token'] = $user->createToken('login')->plainTextToken;
            $response['user'] = $user;
            return $this->returnResponse('User registered Successfully', $response, 200);

        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->returnResponseError(999, false, 'Something went wrong. Please try again');
        }
    }

    public function login(Request $request){
        try{
           if(Auth::attempt(['email' => $request->email,'password' => $request->password])){
               $user = Auth::user();
               $response['token'] = $user->createToken('login')->plainTextToken;
               $response['user'] = $user;
               return $this->returnResponse('Login Successfully', $response, 200);
           }else{

            return $this->returnResponse('Invalid credentials', [], 200);
           }
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            return $this->returnResponseError(999, false, 'Something went wrong. Please try again');
        }
    }

    public function profile(Request $request){
        return 'Wellcome';
    }
}
