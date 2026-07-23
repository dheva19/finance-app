<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterStoreRequest;
use App\Models\Pocket;
use App\Models\User;

class RegisterController extends Controller
{
    public function index(){
        return view('pages.auth.register');
    }

    public function store(RegisterStoreRequest $request){
        $requestData = $request->validated();
        $user = User::create($requestData);
        Pocket::create([
            'user_id' => $user->id,
            'name' => 'Kantong Utama',
            'amount' => 0,
            'is_primary' => true
        ]);
        return redirect()->route('login')->with('success', 'Akun anda berhasil di daftarkan!');
    }
}
