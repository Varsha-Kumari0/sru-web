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
<<<<<<< HEAD
=======
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;
>>>>>>> 8ee68d96137b7c70309db3b082c7dd2ac6ee0dbd

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
<<<<<<< HEAD
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
=======
    

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users'],
        ]);

        $password = Str::random(8);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        Mail::to($request->email)->send(new SendPasswordMail($password));

        return redirect('/login')->with('status', 'Password has been sent to your email');
>>>>>>> 8ee68d96137b7c70309db3b082c7dd2ac6ee0dbd
    }
}
