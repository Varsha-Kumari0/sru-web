<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Profile;
use App\Models\Professional;

class ProfileController extends Controller
{
    /**
     * Default Laravel profile edit (leave as it is)
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Default update (leave as it is)
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Default delete (leave as it is)
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Show alumni form
     */
    public function createProfile()
    {
        return view('profile.create');
    }

    /**
     * Store alumni data (MAIN FUNCTION 🔥)
     */
    public function storeProfile(Request $request)
{

if (Profile::where('user_id', auth()->id())->exists()) {
    return redirect('/dashboard')->with('error', 'Profile already exists');
}
    // 🔒 optional validation (recommended)
    $request->validate([
        'full_name' => 'required',
        'mobile' => 'required',
        'city' => 'required',
        'country' => 'required',
        'degree' => 'required',
        'branch' => 'required',
        'passing_year' => 'required',
    ]);

    // save profile
    $profile = Profile::create([
        'user_id' => auth()->id(),

        'full_name' => $request->full_name,
        'mobile' => $request->mobile,

        'city' => $request->city,
        'country' => $request->country,

        'linkedin' => $request->linkedin,
        'facebook' => $request->facebook,
        'instagram' => $request->instagram,
        'twitter' => $request->twitter,

        'degree' => $request->degree,
        'branch' => $request->branch,
        'passing_year' => $request->passing_year,

        'current_status' => $request->current_status,
        'company' => $request->company,
    ]);

    // save experiences safely
    if ($request->has('organization')) {
        foreach ($request->organization as $i => $org) {

            if (!$org) continue; // skip empty rows

            Professional::create([
                'user_id' => auth()->id(),
                'organization' => $org,
                'industry' => $request->industry[$i] ?? null,
                'role' => $request->role[$i] ?? null,
                'from' => $request->from[$i] ?? null,
                'to' => $request->to[$i] ?? null,
                'location' => $request->location_exp[$i] ?? null,
            ]);
        }
    }

    return redirect('/dashboard')->with('success', 'Profile saved successfully');
}
}