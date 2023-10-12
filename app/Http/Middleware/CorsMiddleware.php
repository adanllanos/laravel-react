<?php
/**
 * Created by PhpStorm.
 * User: guido
 * Date: 5/25/2019
 * Time: 9:09 PM
 */
namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            //'Access-Control-Allow-Origin'      => 'https://testui.zonagamepro-v.net',
            'Access-Control-Allow-Origin'      => 'http://localhost:4200',
            //'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'GET, HEAD, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '60',
            'Access-Control-Allow-Headers'     => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
        ];

        if ($request->isMethod('OPTIONS'))
        {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);
        foreach($headers as $key => $value)
        {
            $response->header($key, $value);
        }

        return $response;
    }
}
