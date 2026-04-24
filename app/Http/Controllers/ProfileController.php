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
     * Show create profile page
     */
    public function createProfile()
    {
        $selectDegree = [
        "BTech" => ["CSE", "ECE", "Mechanical", "Civil"],
        "BSc" => ["Physics", "Chemistry", "Maths"],
        "BCom" => ["General", "Honours"],
        "BCA" => ["Computer Applications"],
        "Business" => ["MBA Finance", "MBA Marketing"],
        "Agriculture" => ["Agri Science"]
    ];

    return view('profile.create', compact('selectDegree'));
    }

    /**
     * STORE PROFILE (FINAL FIXED VERSION)
     */
    public function storeProfile(Request $request)
    {
        // ❌ prevent duplicate profile
        if (Profile::where('user_id', auth()->id())->exists()) {
            return redirect()->route('profile')->with('error', 'Profile already exists');
        }

        // ✅ VALIDATION
        $request->validate([
            'full_name' => 'required',
            'mobile' => 'required',
            'city' => 'required',
            'country' => 'required',
            'degree' => 'required',
            'branch' => 'required',
            'passing_year' => 'required',

            // social links
            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',

            // image
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ IMAGE UPLOAD
        $imagePath = null;

        if ($request->hasFile('profile_photo')) {
            $imagePath = $request->file('profile_photo')->store('profiles', 'public');
        }

        // ✅ SAVE PROFILE
        $profile = Profile::create([
            'user_id' => auth()->id(),

            'profile_photo' => $imagePath,

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
        ]);

        // ✅ SAVE EXPERIENCE
        if ($request->has('organization')) {
            foreach ($request->organization as $i => $org) {

                if (!$org) continue;

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

        return redirect()->route('profile')->with('success', 'Profile created successfully');
    }

    /**
     * EDIT PROFILE (your custom one)
     */
    public function editProfile()
    {
        $profile = Profile::where('user_id', auth()->id())->first();
        $experiences = Professional::where('user_id', auth()->id())->get();

        return view('profile.edit', compact('profile', 'experiences'));
    }

    /**
     * UPDATE PROFILE (FINAL FIXED)
     */
    public function updateProfile(Request $request)
    {
        $profile = Profile::where('user_id', auth()->id())->first();

        if (!$profile) {
            return redirect('/profile/create');
        }

        // ✅ VALIDATION
        $request->validate([
            'city' => 'required',
            'country' => 'required',

            'linkedin' => 'nullable|url',
            'instagram' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',

            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ✅ IMAGE UPDATE
        if ($request->hasFile('profile_photo')) {
            $profile->profile_photo = $request->file('profile_photo')->store('profiles', 'public');
        }

        // ✅ UPDATE PROFILE
        $profile->update([
            'city' => $request->city,
            'country' => $request->country,

            'linkedin' => $request->linkedin,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
        ]);

        // ✅ RESET EXPERIENCES (simple approach)
        Professional::where('user_id', auth()->id())->delete();

        if ($request->has('organization')) {
            foreach ($request->organization as $i => $org) {

                if (!$org) continue;

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

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}