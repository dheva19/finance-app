<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginStoreRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(){
        return view('pages.auth.login');
    }

    public function store(LoginStoreRequest $request){
        $requestData = $request->validated();

        if(Auth::attempt($requestData, $request->has('remember'))){
            return redirect()->route('dashboard.index')->with('success', 'Berhasil   login!');
        }else{
            return back()->with('error', 'Email atau password salah!')->withInput();
        }
    }
}
