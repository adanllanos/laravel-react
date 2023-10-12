<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Models\Usuario;
use http\Env\Response;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function signup(SignupRequest $request)
    {
        $data = $request->validated();
        /** @var \App\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('main')->plainTextToken;

        return response(compact('user', 'token'));
    }
    public function login(LoginRequest $request)
    {

        $credencials = $request->validated();
        if (!Auth::attempt($credencials)) {
            return response([
                'message' => 'Provided email address or password is incorrect'
            ], 422);
        }
        /** @var User $user */
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response(compact('user', 'token'));
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->currentAccessToken()->delete;
        return response('', 204);
    }


    public function postLogin(Request $request)
    {
        $rules = array(
            'correo' => array('required'),
            'contrasena' => array('required'),
        );
        $payload = app('request')->only('correo', 'contrasena');

        $validator = app('validator')->make($payload, $rules);
        if ($validator->fails()) {
            return response()->json(
                array(
                    'errcode' => -2,
                    'errmsg' => $validator->errors()
                ),
                200
            );
        }

        /*$captcha  = $request['recaptcha'];
        $secretKey = "6LcQoq0UAAAAAEuGTeO7lffnwYzRAIxY1tv8vfyR";
        $url = "https://www.google.com/recaptcha/api/siteverify";

        $data = [
            "secret" => $secretKey,
            "response" => $captcha
        ];
        $response = Http::asForm()->post($url, $data)->json();

        if($response['success'] == false) {
            return response()->json(
                array(
                    'errcode' => -2,
                    'errmsg' => "recaptcha fails"
                ), 400 );
        }*/

        $user = User::where('correo', '=', $payload['correo'])
            ->where('contrasena', '=', $payload['contrasena'])
            /* ->where('disponible', '=', true) */
            ->first();

        /*if (!Hash::check($request->input('password'), $user->password)) {
            return response()->json(array(
                'errcode' => 400104,
                'errmsg' => ''
            ), 404);
        }*/
        /*if ($request->input('password') != $user->password) {
            return response()->json(array(
                'errcode' => 400104,
                'errmsg' => ''
            ), 404);
        }*/

        if (!$user) {
            return response()->json(array(
                'errcode' => 400104,
                'errmsg' => ''
            ), 404);
        }
        /* $sucursal = Sucursal::find($user->sucursal_id);
        $role = TipoUsuario::find($user->tipo_usuario_id);
        $customClaims = array(
            'name' => $user->nombre,
            'tipo_usuario_id' => $user->tipo_usuario_id,
            'user_id' => $user->id,
            'login' => $user->login,
            'sucursal_id' => $user->sucursal_id,
            'sucursal' => $sucursal->nombre,
            'role' => $role->nombre
        ); */
        $token = null;
        try {
            JWTAuth::attempt($payload);
            $payload1 = JWTFactory::sub('token')->data($customClaims)->make();
            if (!$token = JWTAuth::encode($payload1)->get()) {

                return response()->json(array(
                    'errcode' => 400103,
                    'errmsg' => ''
                ));
            }
        } catch (JWTException $e) {
            return response()->json(
                array(
                    'errcode' => 400102,
                    'errmsg' => 'moshi'
                )
            );
        }
        $details = [
            'subject' => 'Code Razer zonagamepro-v login alert',
            'from' => 'razer-api-codes@zonagamepro-v.net',
        ];
        //Mail::to($user['email'])->send(new LoginAlert($details));
        return response()->json(compact('token'));
    }
}
