<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessTokenFactory;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginApi(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $data = Customer::where('email', $credentials['email'])->first();

        if (!$data || !Hash::check($credentials['password'], $data->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $this->generateAccessToken($data);

        return response()->json([ 
            'success' => true,
   'message' => 'El usuario fue autenticado',
    'data' => $data,
   
    
    
], 201);
    }

    private function generateAccessToken(Customer $customer)
    {
        $accessToken = "JWT " . $customer->email . ':' . $customer->id .$customer->name . ':' . time();

        return base64_encode($accessToken);
    }
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function logoutApi(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Logout exitoso'], 200);
}
}


