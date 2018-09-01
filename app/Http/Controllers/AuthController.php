<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
        public function __construct()
        {
            $this->middleware('auth:api',['except' => ['login']]);
        }

    /**
     *  获取token
     * @return \Illuminate\Http\JsonResponse
     */
        public function login()
        {
            $credentials = request(['email','password']);
            if (! $token = auth('api')->attempt($credentials)){
                return response()->json(['error' => 'Unauthorized'],401);
            }

            return $this->respondWithToken($token);
        }

    /**
     *  获取用户信息
     * @return \Illuminate\Http\JsonResponse
     */
        public function me()
        {
            return response()->json(auth('api')->user());
        }

    /**
     *  退出登录
     * @return \Illuminate\Http\JsonResponse
     */
        public function logout()
        {
            auth('api')->logout();

            return response()->json(['message' => 'Successfully logged out']);
        }

    /**
     *  刷新token
     * @return mixed
     */
        public function refresh()
        {
            return $this->respondWithToken(auth('api')->refresh());
        }

    /**
     *  返回token信息
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL()*60
            ]);
        }
}
