<?php

namespace App\Http\Controllers;

use App\User;
use JWTFactory;
use JWTAuth;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Validator;
use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password!'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Couldn\'t create token'], 500);
        }
        if (Auth::validate($credentials))
        {
            $user = Auth::getUser();
        }

//        if (!$user->active_status) {
//            return response()->json(['error'=>'Your Account is Deactivated, Contact Admin'], 401);
//        }
//
//        $roles = array();
//        foreach ($user->roles as $role){
//            $roles[] = $role->name;
//        }
//        $user->roleNames = $roles;
//        $token = JWTAuth::fromUser($user);
        return response()->json(['token'=>$token, 'user'=>$user]);
    }

    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'password'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        $user = User::where('email', '=', $request->get('email'))->first();

        return Response::json(['result'=>'success', 'user'=>$user]);
    }

    public function getUsers() {
        $totalCount = count(User::all());
        $users = User::all();
        if ($totalCount == 0) {
            return response()->json(['totalCount'=>$totalCount, 'users'=>[]], 200);
        } else {
            return response()->json(['totalCount'=>$totalCount, 'users'=>$users], 200);
        }
    }
}
