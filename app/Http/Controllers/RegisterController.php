<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function registrationPage()
    {
        return view('auth.register');
    }

    public function postRegister()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'birthday' => 'required|date',
        ]);
        
        $data['password'] = bcrypt($data['password']);
        $data['type'] = 'Patient'; // default value for type
        $user = User::create($data);
        return back()->withSuccess('success');
    }
}
