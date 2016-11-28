<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $user;

    /**
     * AuthController constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    public function register(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'avatar' => 'http://lorempixel.com/240/240/?'.mt_rand(10000,99999)
        ];
        $this->validator($data);
        try{
            $this->user->create($data);
        }catch (\Exception $e){

            return response()->json(['error' => '注册失败,邮箱已存在'], 422);
        }



        return response()->json([
            'status' => 200,
            'message' => '注册成功',
            'redirect' => '/'
        ]);
    }

}
