<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Validator;

class UserController extends Controller
{

    public $successStatus = 200;

    public function getUser() {
        $user = User::all();

        return [
            'data' => $user
        ];
    }

    public function cekBearer() {
        return [
            'status' => 'success'
        ];
    }

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $token = $user->createToken(uniqid())->accessToken;

            $data_token = [
                'accessToken'    => $token,
                'name'    => $user->nama_lengkap,
                'email'    => $user->email,
                'tokenType'    => $user->bearer,
                'userId'    => $user->id_user,
            ];            
            return response()->json($data_token, $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);            
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user->name;

        return response()->json(['success'=>$success], $this->successStatus);
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
    }

    public function getAllUser() {
        $user = User::select(
            'id',
            'name',
            'nik',
            'departement'
        )->get();

        return [
            'user' => $user
        ];
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
} 