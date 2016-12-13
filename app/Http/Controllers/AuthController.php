<?php

namespace App\Http\Controllers;

use App\User;
use App\Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $user;
    protected $message = [
        'required' => ':attribute是必要的。',
        'unique' => ':attribute已经存在。',
        'max' => ':attribute的长度不能超过:max。',
        'min' => ':attribute的长度不能少于:min。',
        'confirmed' => '两次输入的密码不匹配'
    ];
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
            'name' => 'required|max:7|unique:users',
            'phone' => 'required|min:11|max:11|unique:users',
            'password' => 'required|min:6|max:16|confirmed',
        ],$this->message);
    }

    protected function checkVerify(array $verify){
        return Verify::where('id', '=', $verify['verify_id'])
            ->where('verify_code', '=', $verify['verify_code'])
            ->first();
    }

    public function register(Request $request)
    {
        $verify = [
          'verify_code' => $request->input('verify'),
          'verify_id' => $request->input('verify_id')
        ];

        if(! $check = $this->checkVerify($verify)){
            return response()->json([
                'status' => 422,
                'message' => '验证码错误'
            ]);
        }

        $data = [
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'avatar' => 'http://lorempixel.com/240/240/?'.mt_rand(10000,99999)
        ];

        $validator = $this->validator($data);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ]);
        }
        try{
            $data['password'] = bcrypt($data['password']);
            $this->user->create($data);
        }catch (\Exception $e){
            return response()->json(['error' => '注册失败'], 422);
        }

        return response()->json([
            'status' => 200,
            'message' => '注册成功',
            'redirect' => '/'
        ]);
    }

    public function getVerify()
    {
        $sides = [
            3 => '三',
            4 => '四',
            5 => '五',
            6 => '六',
            7 => '七',
            8 => '八',
            9 => '九'
        ];
        $rand_key = array_rand($sides);
        $rand_val = $sides[$rand_key];


        $verify_id = 0;
        try{
            $verify_id = Verify::create([
                'verify_code' => $rand_key
            ]);
        }catch (\Exception $e){
            return response()->json([
                'status' => 500,
                'message' => '获取验证码失败'
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => '获取验证码成功',
            'data' => [
                'side' => $rand_key,
                'name' => $rand_val,
                'verify_id' => $verify_id->id
            ]
        ]);

    }

}
