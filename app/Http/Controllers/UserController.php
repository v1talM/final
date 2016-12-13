<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $user;
    protected $messages = [
        'required' => ':attribute是必要的。',
        'unique' => ':attribute已经存在。',
        'max' => ':attribute的长度不能超过:max。',
        'min' => ':attribute的长度不能少于:min。'
    ];
    /**
     * UserController constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->middleware('cors');
    }

    public function getAllUsers()
    {
        try{
            $users = $this->user->all()->toArray();
        }catch (\Exception $e){
            return response()->json([
                'status' => 422,
                'data' => [],
                'message' => '获取数据失败'
            ]);
        }
        return response()->json([
            'status' => 200,
            'data' => $users,
            'message' => '获取数据成功'
        ]);
    }

    public function delUserById(Request $request)
    {
        try{
            $this->user->where('id','=',$request->input('id'))->delete();
        }catch (\Exception $e){
            return response()->json(['status' => 422, 'message' => '删除用户失败']);
        }
        return response()->json(['status' => 200, 'message' => '删除用户成功']);
    }

    public function checkUsername(Request $request)
    {
        $username = $request->all();
        $validator = Validator::make($username, [
            'name' => 'required|max:7|unique:users'
        ],$this->messages);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => '恭喜用户名可用'
        ]);
    }

    public function checkPhone(Request $request)
    {
        $phone = $request->all();
        $validator = Validator::make($phone, [
            'phone' => 'required|max:11|min:11|unique:users'
        ],$this->messages);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => '手机号码验证通过'
        ]);
    }

}
