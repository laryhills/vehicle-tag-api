<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Guid\Fields;
use Illuminate\Support\Facades\DB;

DB::enableQueryLog();

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = $request->user();
        if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'register' => false
            ], 200);
        }
        if ($user->tokenCan('admin:super')) {
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'password' => 'required|string|confirmed',
                'user_type' => 'required|integer'
            ]);
            // Create User
            $user_ = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => bcrypt($fields['password']),
                'user_type' => $fields['user_type'],
                'status' => '1'
            ]);

            // // Create Token
            // $token = $user->createToken($_ENV['TOKEN_KEY'])->plainTextToken;

            // if($token){
            //     $query = DB::getQueryLog();
            //     //Log query DB
            //     Log::create([
            //         'user' => $user->email,
            //         'action' => 'Register and Token Creation',
            //         'query' => $query[0]['query'],
            //         'parameters'=> preg_replace('/[^A-Za-z0-9\-,]/', '', json_encode($query[0]['bindings']))
            //     ]);
            //     $response = [
            //         'user' => $user,
            //         'token' => $token
            //     ];
            //     return response($response, 201);
            // }else{
            //     return 'Registration Failed';
            // }
            if($user_){
                $query = DB::getQueryLog();
                //Log query DB
                Log::create([
                    'user' => $user->email,
                    'action' => 'Added User',
                    'query' => $query[0]['query'],
                    'parameters'=> preg_replace('/[^A-Za-z0-9\-,.@]/', '', json_encode($query[0]['bindings']))
                ]);
                return response([
                    'message' => 'Registration Successful',
                    'register' => true
                ]);
            }else{
                return response([
                    'message' => 'Registration Failed',
                    'register' => false
                ]);    ;
            }
        }else{
            return response([
                'message' => 'Registration Failed',
                'register' => false
            ]);
        }
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged Out'
        ])->withCookie($cookie);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        // Check  Email
        $user = User::where('email', $fields['email'])->first();

        // Check Password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Wrong Email/Password',
                'login' => false
            ], 200);
        }
        if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'login' => false
                
            ], 200);
        }

        // Determine User Level
        if($user->user_type == 0) {
            // Checker Admin
            $ability = ['admin:checker'];
        }
        if($user->user_type == 1) {
            // Data Entry Admin
            $ability = ['admin:data_entry'];
        }
        if($user->user_type == 2) {
            // Stats Admin
            $ability = ['admin:stats'];
        }
        if($user->user_type == 3) {
            // Super Admin
            $ability = ['admin:super', 'admin:checker', 'admin:data_entry', 'admin:stats'];
        }
        // Create Token
        $token = $user->createToken('token', $ability)->plainTextToken;

/*        if($token){
           $query = DB::getQueryLog();
           //Log query DB
           Log::create([
               'user' => $user->email,
               'action' => 'Login and Token Creation',
               'query' => $query[0]['query'],
               'parameters'=> preg_replace('/[^A-Za-z0-9\-,]/', '', json_encode($query[0]['bindings']))
           ]);
           $response = [
               'user' => $user,
               'token' => $token
           ];
           return response($response, 200);
       }else{
           return 'Login Failed';
       } */
        if($token){
            $query = DB::getQueryLog();
            //Log query DB
            Log::create([
                'user' => $user->email,
                'action' => 'Login and Token Creation',
                'query' => $query[0]['query'],
                'parameters'=> preg_replace('/[^A-Za-z0-9\-,.@]/', '', json_encode($query[0]['bindings']))
            ]);

            // set jwt to cookies
            $cookie = cookie('jwt', $token, 60 * 24); // 1 day
            return response([
                'message' => 'Login Successful',
                'login' => true
            ])->withCookie($cookie);
        }else{
            return response([
                 'message'=>'Login Failed',
                 'login' => false
            ]);
        }
    }

    public function user(){
        return Auth::user();
    }


    public function index(Request $request)
    {
        $user = $request->user();
        if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'register' => false
            ], 200);
        }
        if ($user->tokenCan('admin:super')) {
            $result = User::all();
            if ($result->isNotEmpty()) {
                $query = DB::getQueryLog();
                //Log query DB
                Log::create([
                    'user' => $user->email,
                    'action' => 'Get All Tags',
                    'query' => $query[0]['query'],
                    'parameters' => preg_replace('/[^A-Za-z0-9\-,]/', '', json_encode($query[0]['bindings']))
                ]);
                return $result;
            } else {
                return 'no result';
            }
        }else{
            return 'no result';
        }
    }

    public function adminUpdate(Request $request, $id)
    {
        $user = $request->user();
        if($user->status == 0){
            return response([
                'message' => 'User Account Disabled',
                'update' => false
            ], 200);
        }
        if ($user->tokenCan('admin:super')) {
            $user_ = User::find($id);
            if($user_){
                $request->password = bcrypt($request->password);
                $user_->update([
                    'user_type' => (request('user_type')),
                    'password' => bcrypt(request('password')),
                ]);
                $query = DB::getQueryLog();
                //Log query DB
                Log::create([
                    'user' => $user->email,
                    'action' => 'Update User',
                    'query' => $query[0]['query'],
                    'parameters'=> preg_replace('/[^A-Za-z0-9\-,.@]/', '-', json_encode($request->all()))
                ]);
                return response([
                    'message' => 'Update Successful',
                    'update' => true
                ]);
            }else{
                return response([
                    'message' => 'Update Failed',
                    'update' => false
                ]);
            }
        }else{
            return response([
                'message' => 'Update Failed',
                'update' => false
            ]);
        }

    }
}
