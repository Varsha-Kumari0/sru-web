<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */


    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users'],
        ]);

        $password = Str::random(8);

        User::create([
            'name' => 'Alumni User',
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        Mail::to($request->email)->send(new SendPasswordMail($password));

        return redirect()->route('login')->with('status', 'A password has been sent to your email. Use it to log in.');
    }
}
