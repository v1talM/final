<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OauthController extends Controller
{
    protected $http;

    /**
     * OauthController constructor.
     * @param $http
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
        $this->middleware('cors');
    }

    public function oauth(Request $request)
    {
        try {
            $response = $this->http->post('http://final.dev/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    //本地
                    'client_secret' => 'ibVPr8njCDJA989NGrShvcdRUFuvDtFRgmjk5XwX',
                    //服务器上
                    //'client_secret' => 'W1GsIEpXqRbYn2ZrcT8hsQt0rCYFhR8lwPagR5Uf',
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                    'scope' => '*',
                ],
            ]);
        }catch (\Exception $e){
            return response()->json(['status' => 401, 'message' => '用户名和密码不匹配']);
        }
        //return json_decode((string) $response->getBody(), true);
        $accessToken =  Arr::get(json_decode((string) $response->getBody(), true),'access_token');
        return response()->json([
            'status' => 200 ,
            'accessToken' => $accessToken ,
            'message' => '登录成功',
            'redirect' => '/admin3d'
        ]);

    }

}
