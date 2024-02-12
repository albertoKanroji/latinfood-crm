<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerResetPasswordController extends Controller
{
public function showResetForm(Request $request, $token = null)
{
    return view('auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
}
}
