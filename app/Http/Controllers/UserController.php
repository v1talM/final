<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

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

}
