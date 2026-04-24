<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Profile;
use App\Models\Professional;

class ProfileController extends Controller
{
    /**
     * Show edit profile page
     */
    public function edit()
    {
        $profile = Profile::where('user_id', auth()->id())->first();
        $experiences = Professional::where('user_id', auth()->id())->get();

        if (!$profile) {
            return redirect('/profile/create');
        }

        return view('profile.edit', compact('profile', 'experiences'));
    }

    /**
     * Show create profile page
     */
    public function createProfile()
    {
        return view('profile.create');
    }

    /**
     * STORE PROFILE
     */
    public function storeProfile(Request $request)
    {
        // ❌ Prevent duplicate profile
        if (Profile::where('user_id', auth()->id())->exists()) {
            return redirect('/dashboard')->with('error', 'Profile already exists');
        }

        // ✅ VALIDATION (FIXED)
        $request->validate([
            'full_name' => 'required',
            'mobile' => 'required',
            'city' => 'required',
            'country' => 'required',
            'degree' => 'required',
            'branch' => 'required',
            'passing_year' => 'required',

            'linkedin'  => ['nullable', 'regex:/^https?:\/\/(www\.)?linkedin\.com/'],
            'instagram' => ['nullable', 'regex:/^https?:\/\/(www\.)?instagram\.com/'],
            'facebook'  => ['nullable', 'regex:/^https?:\/\/(www\.)?facebook\.com/'],
            'twitter'   => ['nullable', 'regex:/^https?:\/\/(www\.)?(twitter\.com|x\.com)/'],
        ], [
            'linkedin.regex' => 'Enter a valid LinkedIn URL',
            'instagram.regex' => 'Enter a valid Instagram URL',
            'facebook.regex' => 'Enter a valid Facebook URL',
            'twitter.regex' => 'Enter a valid Twitter/X URL',
        ]);

        // ✅ CREATE PROFILE
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

        return redirect('/dashboard')->with('success', 'Profile created successfully');
    }

    /**
     * UPDATE PROFILE
     */
    public function updateProfile(Request $request)
    {
        $profile = Profile::where('user_id', auth()->id())->first();

        if (!$profile) {
            return redirect('/profile/create');
        }

        // ✅ VALIDATION (FIXED)
        $request->validate([
            'city' => 'required',
            'country' => 'required',

            'linkedin'  => ['nullable', 'regex:/^https?:\/\/(www\.)?linkedin\.com/'],
            'instagram' => ['nullable', 'regex:/^https?:\/\/(www\.)?instagram\.com/'],
            'facebook'  => ['nullable', 'regex:/^https?:\/\/(www\.)?facebook\.com/'],
            'twitter'   => ['nullable', 'regex:/^https?:\/\/(www\.)?(twitter\.com|x\.com)/'],
        ], [
            'linkedin.regex' => 'Enter a valid LinkedIn URL',
            'instagram.regex' => 'Enter a valid Instagram URL',
            'facebook.regex' => 'Enter a valid Facebook URL',
            'twitter.regex' => 'Enter a valid Twitter/X URL',
        ]);

        // ✅ UPDATE PROFILE
        $profile->update([
            'city' => $request->city,
            'country' => $request->country,

            'linkedin' => $request->linkedin,
            'facebook' => $request->facebook,
            'instagram' => $request->instagram,
            'twitter' => $request->twitter,
        ]);

        // ✅ DELETE OLD EXPERIENCE
        Professional::where('user_id', auth()->id())->delete();

        // ✅ SAVE NEW EXPERIENCE
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

        return redirect('/dashboard')->with('success', 'Profile updated successfully');
    }
}