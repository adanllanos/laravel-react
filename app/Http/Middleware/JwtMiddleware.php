<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 5/26/2019
 * Time: 12:50 AM
 */


namespace App\Http\Middleware;


use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            /*if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'errcode' => 400004,
                    'errmsg' => 'user not found'
                ], 404);
            }*/
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'errcode' => 400002,
                'errmsg' => 'token expired'
            ]);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'errcode' => 400003,
                'errmsg' => 'token invalid'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'errcode' => 400001,
                'errmsg' => $e
            ]);
        }
        return $next($request);
    }
}
