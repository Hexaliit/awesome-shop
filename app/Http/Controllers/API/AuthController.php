<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register
     * @param RegisterRequest $request
     * @return User
     */
    public function register(RegisterRequest $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return response()->json([
            'token' => $user->createToken("API_TOKEN")->plainTextToken
        ] , 200);
    }
    /**
     * Login user
     * @param Request $request
     * @return User
     */
    public function login(Request $request){
        $validated = Validator::make($request->all(),[
           'email' => 'required|email',
           'password' => 'required|min:3'
        ]);
        if ($validated->fails()){
            return \response()->json($validated->errors() , 422);
        }
        if (Auth::attempt(['email' => $request->email , 'password' => $request->password])){
            $user = $request->user();
            $token = $user->createToken('API_TOKEN')->plainTextToken;
            $cookie = cookie('token' , $token , 60*24); //1 day
            return response()->json([
                'token' => $token
            ] , 200)->withCookie($cookie);
        } else {
            return response()->json([
                'error' => 'Invalid email or password'
            ] , 401);
        }
    }
    public function user(){
        return \auth()->user();
    }
}
