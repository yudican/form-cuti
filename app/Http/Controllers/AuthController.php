<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('username', $request->username)->first();


        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|recaptchav3:login,0.5'
        ]);

        if ($validate->fails()) {

            return redirect('login')->with(['error' => 'Silahkan isi semua form yang tersedia']);
        }

        if (!$user) {
            return redirect('login')->with(['error' => 'Username tidak terdaftar']);
        }



        if (!Hash::check($request->password, $user->password)) {
            return redirect('login')->with(['error' => 'Unathorized, password yang kamu masukkan tidak sesuai']);
        }

        Auth::login($user);


        return redirect('dashboard');
    }
}
