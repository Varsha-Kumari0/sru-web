<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Professional;

class ProfileController extends Controller
{
    /**
     * Show edit page
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
        if (Profile::where('user_id', auth()->id())->exists()) {
            return redirect('/dashboard')->with('error', 'Profile already exists');
        }

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
        ]);

        $profile = new Profile();
        $profile->user_id = auth()->id();
        $profile->full_name = $request->full_name;
        $profile->mobile = $request->mobile;
        $profile->city = $request->city;
        $profile->country = $request->country;
        $profile->linkedin = $request->linkedin;
        $profile->facebook = $request->facebook;
        $profile->instagram = $request->instagram;
        $profile->twitter = $request->twitter;
        $profile->degree = $request->degree;
        $profile->branch = $request->branch;
        $profile->passing_year = $request->passing_year;

        // ✅ PROFILE IMAGE SAVE
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $profile->profile_photo = $path;
        }

        $profile->save();

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

        $request->validate([
            'city' => 'required',
            'country' => 'required',

            'linkedin'  => ['nullable', 'regex:/^https?:\/\/(www\.)?linkedin\.com/'],
            'instagram' => ['nullable', 'regex:/^https?:\/\/(www\.)?instagram\.com/'],
            'facebook'  => ['nullable', 'regex:/^https?:\/\/(www\.)?facebook\.com/'],
            'twitter'   => ['nullable', 'regex:/^https?:\/\/(www\.)?(twitter\.com|x\.com)/'],
        ]);

        // ✅ UPDATE BASIC FIELDS
        $profile->city = $request->city;
        $profile->country = $request->country;
        $profile->linkedin = $request->linkedin;
        $profile->facebook = $request->facebook;
        $profile->instagram = $request->instagram;
        $profile->twitter = $request->twitter;

        // ✅ PROFILE IMAGE UPDATE (THIS WAS MISSING 🔥)
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $profile->profile_photo = $path;
        }

        $profile->save();

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