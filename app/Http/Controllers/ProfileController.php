<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Profile;
use App\Models\Professional;
use App\Models\Skill;
use App\Models\Achievement;
use App\Models\Connection;
use App\Models\ProfileView;

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
     * Show user profile with all details
     */
    public function showProfile()
    {
        $profile = Profile::where('user_id', auth()->id())->first();
        $experiences = Professional::where('user_id', auth()->id())->get();
        $skills = Skill::where('user_id', auth()->id())->get();
        $achievements = Achievement::where('user_id', auth()->id())->orderBy('earned_at', 'desc')->get();
        
        // Get connection count
        $connectionCount = Connection::where('user_id', auth()->id())
            ->where('status', 'connected')
            ->count();
        
        // Get profile views count
        $profileViewsCount = ProfileView::where('profile_user_id', auth()->id())->count();
        
        // Get counts for stats
        $skillsCount = $skills->count();
        $achievementsCount = $achievements->count();

        return view('profile.profile', compact(
            'profile',
            'experiences',
            'skills',
            'achievements',
            'connectionCount',
            'profileViewsCount',
            'skillsCount',
            'achievementsCount'
        ));
    }

    /**
     * Show create profile page
     */
    public function createProfile()
    {
        $selectDegree = [
            "B.Tech" => [
                "CSE (AI & ML)",
                "CSE (Cybersecurity)",
                "CSE (Data Science)",
                "ECE (VLSI)",
                "EEE (Renewable Energy)",
                "Mechanical (Smart Manufacturing)",
                "Civil (Robotics and Automation)",
            ],
            "Business" => [
                "BBA (Marketing)",
                "BBA (Finance)",
                "BBA (Operations)",
                "BBA (International Business)",
                "BBA (Business Analytics)",
            ],
            "Agriculture" => ["B.Sc (Hons) Agriculture"],
            "B.Sc" => [
                "B.Sc (Computer Science)",
                "B.Sc (Physics)",
                "B.Sc (Chemistry)",
                "B.Sc (Mathematics)",
                "B.Sc (Forensic Science)",
            ],
            "B.Com" => ["B.Com (Computer Applications)"],
            "BCA" => ["BCA General", "BCA (Cloud Computing)"],
        ];

        return view('profile.create', compact('selectDegree'));
    }

    /**
     * STORE PROFILE (FINAL FIXED VERSION)
     */
    public function storeProfile(Request $request)
    {
        // ❌ prevent duplicate profile
        if (Profile::where('user_id', Auth::id())->exists()) {
            return redirect()->route('profile')->with('error', 'Profile already exists');
        }

        // ✅ VALIDATION
        $request->validate([
            'full_name' => 'required',
            'father_name' => 'required|string|max:255',
            'mobile' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'city' => 'required',
            'country' => 'required',
            'degree' => 'required',
            'branch' => 'required',
            'passing_year' => 'required',

            // social links
            'linkedin' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(linkedin\.com)\/.+/i'],
            'instagram' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(instagram\.com)\/.+/i'],
            'facebook' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(facebook\.com)\/.+/i'],
            'twitter' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?((x\.com)|(twitter\.com))\/.+/i'],

            // image
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'linkedin.required' => 'LinkedIn link is required.',
            'linkedin.url' => 'Please enter a valid LinkedIn URL (example: https://linkedin.com/in/username).',
            'linkedin.regex' => 'LinkedIn link must be from linkedin.com.',
            'instagram.required' => 'Instagram link is required.',
            'instagram.url' => 'Please enter a valid Instagram URL (example: https://instagram.com/username).',
            'instagram.regex' => 'Instagram link must be from instagram.com.',
            'facebook.required' => 'Facebook link is required.',
            'facebook.url' => 'Please enter a valid Facebook URL (example: https://facebook.com/username).',
            'facebook.regex' => 'Facebook link must be from facebook.com.',
            'twitter.required' => 'X link is required.',
            'twitter.url' => 'Please enter a valid X URL (example: https://x.com/username).',
            'twitter.regex' => 'X link must be from x.com or twitter.com.',
            'mobile.regex' => 'Mobile number must contain only digits and be 10 to 15 characters long.',
        ]);

        // ✅ IMAGE UPLOAD
        $imagePath = null;

        if ($request->hasFile('profile_photo')) {
            $imagePath = $request->file('profile_photo')->store('profiles', 'public');
        }

        // ✅ SAVE PROFILE
        $profile = Profile::create([
            'user_id' => Auth::id(),

            'profile_photo' => $imagePath,

            'full_name' => $request->full_name,
            'father_name' => $request->father_name,
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
                    'user_id' => Auth::id(),
                    'organization' => $org,
                    'industry' => $request->industry[$i] ?? null,
                    'role' => $request->role[$i] ?? null,
                    'from' => $request->from[$i] ?? null,
                    'to' => $request->to[$i] ?? null,
                    'location' => $request->location_exp[$i] ?? null,
                ]);
            }
        }

        ActivityLog::record(
            Auth::id(),
            Auth::id(),
            'profile_created',
            (Auth::user()?->name ?? 'User') . ' created profile'
        );

        return redirect()->route('profile')->with('success', 'Profile created successfully');
    }

    /**
     * EDIT PROFILE (your custom one)
     */
    public function editProfile()
    {
        $profile = Profile::where('user_id', Auth::id())->first();
        $experiences = Professional::where('user_id', Auth::id())->get();

        return view('profile.edit', compact('profile', 'experiences'));
    }

    /**
     * UPDATE PROFILE (FINAL FIXED)
     */
    public function updateProfile(Request $request)
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        if (!$profile) {
            return redirect('/profile/create');
        }

        $originalProfileData = $profile->only([
            'city',
            'country',
            'linkedin',
            'instagram',
            'facebook',
            'twitter',
            'profile_photo',
        ]);

        $existingExperiences = Professional::where('user_id', Auth::id())
            ->orderBy('id')
            ->get(['organization', 'industry', 'role', 'from', 'to', 'location'])
            ->map(fn ($exp) => $exp->toArray())
            ->toArray();

        // ✅ VALIDATION
        $request->validate([
            'city' => 'required',
            'country' => 'required',

            'linkedin' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(linkedin\.com)\/.+/i'],
            'instagram' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(instagram\.com)\/.+/i'],
            'facebook' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(facebook\.com)\/.+/i'],
            'twitter' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?((x\.com)|(twitter\.com))\/.+/i'],

            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'linkedin.required' => 'LinkedIn link is required.',
            'linkedin.url' => 'Please enter a valid LinkedIn URL (example: https://linkedin.com/in/username).',
            'linkedin.regex' => 'LinkedIn link must be from linkedin.com.',
            'instagram.required' => 'Instagram link is required.',
            'instagram.url' => 'Please enter a valid Instagram URL (example: https://instagram.com/username).',
            'instagram.regex' => 'Instagram link must be from instagram.com.',
            'facebook.required' => 'Facebook link is required.',
            'facebook.url' => 'Please enter a valid Facebook URL (example: https://facebook.com/username).',
            'facebook.regex' => 'Facebook link must be from facebook.com.',
            'twitter.required' => 'X link is required.',
            'twitter.url' => 'Please enter a valid X URL (example: https://x.com/username).',
            'twitter.regex' => 'X link must be from x.com or twitter.com.',
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
        Professional::where('user_id', Auth::id())->delete();

        if ($request->has('organization')) {
            foreach ($request->organization as $i => $org) {

                if (!$org) continue;

                Professional::create([
                    'user_id' => Auth::id(),
                    'organization' => $org,
                    'industry' => $request->industry[$i] ?? null,
                    'role' => $request->role[$i] ?? null,
                    'from' => $request->from[$i] ?? null,
                    'to' => $request->to[$i] ?? null,
                    'location' => $request->location_exp[$i] ?? null,
                ]);
            }
        }

        $updatedProfile = Profile::where('user_id', Auth::id())->first();
        $updatedProfileData = $updatedProfile ? $updatedProfile->only([
            'city',
            'country',
            'linkedin',
            'instagram',
            'facebook',
            'twitter',
            'profile_photo',
        ]) : [];

        $updatedExperiences = Professional::where('user_id', Auth::id())
            ->orderBy('id')
            ->get(['organization', 'industry', 'role', 'from', 'to', 'location'])
            ->map(fn ($exp) => $exp->toArray())
            ->toArray();

        $changes = [];

        foreach ($originalProfileData as $field => $oldValue) {
            $newValue = $updatedProfileData[$field] ?? null;
            if ((string) ($oldValue ?? '') !== (string) ($newValue ?? '')) {
                $changes[] = [
                    'field' => $field,
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        if ($existingExperiences !== $updatedExperiences) {
            $changes[] = [
                'field' => 'professional_experiences',
                'old' => $existingExperiences,
                'new' => $updatedExperiences,
            ];
        }

        ActivityLog::record(
            Auth::id(),
            Auth::id(),
            'profile_updated',
            (Auth::user()?->name ?? 'User') . ' updated profile',
            [
                'changes' => $changes,
            ]
        );

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}