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
use App\Models\SkillEndorsement;
use App\Models\User;

class ProfileController extends Controller
{
    private function buildEducationRowsFromSectionRequest(Request $request): array
    {
        $sections = ['school', 'ug', 'pg', 'other'];
        $rows = [];

        foreach ($sections as $section) {
            $institution = trim((string) $request->input($section . '_institution', ''));
            $degree = trim((string) $request->input($section . '_degree', ''));
            $branch = trim((string) $request->input($section . '_branch', ''));
            $from = $request->input($section . '_from');
            $to = $request->input($section . '_to');

            if ($institution === '' && $degree === '' && $branch === '' && blank($from) && blank($to)) {
                continue;
            }

            $rows[] = [
                'section' => $section,
                'institution' => $institution,
                'degree' => $degree,
                'branch' => $branch,
                'from' => $from,
                'to' => $to,
            ];
        }

        return $rows;
    }

    /**
     * Default Laravel profile edit (leave as it is)
     */
    public function edit(Request $request): View
    {
        ActivityLog::record(
            $request->user()?->id,
            $request->user()?->id,
            'profile_account_edit_opened',
            ($request->user()?->name ?? 'User') . ' opened account edit page'
        );

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Default update (leave as it is)
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $before = $request->user()->only(['name', 'email']);

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        ActivityLog::record(
            $request->user()->id,
            $request->user()->id,
            'profile_account_updated',
            ($request->user()->name ?? 'User') . ' updated account profile details',
            [
                'before' => $before,
                'after' => $request->user()->only(['name', 'email']),
            ]
        );

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

        ActivityLog::record(
            $user?->id,
            $user?->id,
            'account_deleted',
            ($user?->name ?? 'User') . ' deleted account',
            [
                'email' => $user?->email,
            ]
        );

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
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'profile_viewed',
            ($actor?->name ?? 'User') . ' viewed profile page'
        );

        return $this->renderProfile($actor, true);
    }

    public function showAlumniProfile(User $user)
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ((int) $user->id === (int) Auth::id()) {
            return redirect()->route('profile');
        }

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $user->id,
            'profile_viewed',
            ($actor?->name ?? 'User') . ' viewed profile page for ' . ($user->display_name ?? 'Alumni')
        );

        ProfileView::firstOrCreate([
            'profile_user_id' => $user->id,
            'visitor_user_id' => $actor?->id,
        ]);

        return $this->renderProfile($user, false);
    }

    private function renderProfile(User $profileUser, bool $isOwnProfile): View
    {
        $profileUserId = $profileUser->id;
        $viewerId = Auth::id();

        $profile = Profile::where('user_id', $profileUserId)->first();
        $experiences = Professional::where('user_id', $profileUserId)->get();
        $skills = Skill::where('user_id', $profileUserId)
            ->with(['endorsements.endorser.profile'])
            ->get();
        $achievements = Achievement::where('user_id', $profileUserId)->orderBy('earned_at', 'desc')->get();
        $endorsedSkillIds = SkillEndorsement::query()
            ->where('endorser_id', $viewerId)
            ->pluck('skill_id')
            ->map(fn ($skillId) => (int) $skillId)
            ->all();

        // Get connection count
        $connectionCount = Connection::where('user_id', $profileUserId)
            ->where('status', 'connected')
            ->count();
        
        // Get profile views count
        $profileViewsCount = ProfileView::where('profile_user_id', $profileUserId)->count();
        
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
            'achievementsCount',
            'endorsedSkillIds',
            'isOwnProfile',
            'profileUser'
        ));
    }

    /**
     * Show create profile page
     */
    public function createProfile()
    {
        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'profile_create_opened',
            ($actor?->name ?? 'User') . ' opened create profile page'
        );

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other,prefer_not_to_say',
            'contact_email' => 'required|email|max:255',
            'father_name' => 'required|string|max:255',
            'mobile' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'city' => 'required',
            'country' => 'required',
            'degree' => 'required',
            'branch' => 'required',
            'passing_year' => 'required',
            'current_status' => 'required|in:studying,working',
            'pursuing_educational_level' => 'nullable|string|max:255',
            'highest_completed_educational_level' => 'nullable|string|max:255',

            // current study details (required only when user is studying)
            'study_institution' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_degree' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_branch' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_from' => 'required_if:current_status,studying|nullable|date',
            'study_to' => 'required_if:current_status,studying|nullable|string|max:255',

            // professional details (required only when user is working, optional for studying users with past work)
            'organization' => 'required_if:current_status,working|array|min:1',
            'organization.*' => 'nullable|string|max:255',
            'industry' => 'nullable|array',
            'industry.*' => 'nullable|string|max:255',
            'role' => 'nullable|array',
            'role.*' => 'nullable|string|max:255',
            'location_exp' => 'nullable|array',
            'location_exp.*' => 'nullable|string|max:255',
            'from' => 'nullable|array',
            'from.*' => 'nullable|date',
            'to' => 'nullable|array',
            'to.*' => 'nullable|string|max:255',

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

        if ($request->current_status === 'working') {
            $organizations = collect($request->organization ?? [])->filter(fn ($value) => !empty(trim((string) $value)));

            if ($organizations->isEmpty()) {
                return back()
                    ->withErrors(['organization' => 'Please add at least one work experience if you are currently working.'])
                    ->withInput();
            }
        }

        // ✅ IMAGE UPLOAD
        $imagePath = null;

        if ($request->hasFile('profile_photo')) {
            $imagePath = $request->file('profile_photo')->store('profiles', 'public');
        }

        // ✅ SAVE PROFILE
        $profile = Profile::create([
            'user_id' => Auth::id(),

            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'profile_photo' => $imagePath,

            'full_name' => trim($request->first_name . ' ' . $request->last_name),
            'father_name' => $request->father_name,
            'mobile' => $request->mobile,
            'contact_email' => $request->contact_email,

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
            'pursuing_educational_level' => $request->pursuing_educational_level,
            'highest_completed_educational_level' => $request->highest_completed_educational_level,
            'study_institution' => $request->current_status === 'studying' ? $request->study_institution : null,
            'study_degree' => $request->current_status === 'studying' ? $request->study_degree : null,
            'study_branch' => $request->current_status === 'studying' ? $request->study_branch : null,
            'study_from' => $request->current_status === 'studying' ? $request->study_from : null,
            'study_to' => $request->current_status === 'studying' ? $request->study_to : null,
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
     * Update profile bio
     */
    public function updateBio(Request $request): RedirectResponse
    {
        $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        $profile = Profile::where('user_id', Auth::id())->first();

        if (!$profile) {
            return redirect()->route('profile.create')->with('error', 'Please create your profile first');
        }

        $oldDescription = $profile->description;

        $profile->update([
            'description' => $request->description,
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'profile_bio_updated',
            ($actor?->name ?? 'User') . ' updated profile bio',
            [
                'old' => $oldDescription,
                'new' => $profile->description,
            ]
        );

        return redirect()->route('profile')->with('success', 'Bio updated successfully');
    }

    /**
     * Show bio edit form
     */
    public function editBio(): View|RedirectResponse
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        if (!$profile) {
            return redirect()->route('profile.create')->with('error', 'Please create your profile first');
        }

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'profile_bio_edit_opened',
            ($actor?->name ?? 'User') . ' opened bio edit page'
        );

        return view('profile.edit-bio', compact('profile'));
    }
    /**
     * EDIT PROFILE (your custom one)
     */
    public function editProfile()
    {
        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'profile_edit_opened',
            ($actor?->name ?? 'User') . ' opened profile edit page'
        );

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
            'current_status',
            'study_institution',
            'study_degree',
            'study_branch',
            'study_from',
            'study_to',
            'previous_education',
        ]);

        $existingExperiences = Professional::where('user_id', Auth::id())
            ->orderBy('id')
            ->get(['organization', 'industry', 'role', 'from', 'to', 'location'])
            ->map(fn ($exp) => (array) $exp)
            ->toArray();

        // ✅ VALIDATION
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other,prefer_not_to_say',
            'contact_email' => 'required|email|max:255',
            'city' => 'required',
            'country' => 'required',
            'current_status' => 'required|in:studying,working',
            'pursuing_educational_level' => 'nullable|string|max:255',
            'highest_completed_educational_level' => 'nullable|string|max:255',

            // current study details (required only when user is studying)
            'study_institution' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_degree' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_branch' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_from' => 'required_if:current_status,studying|nullable|date',
            'study_to' => 'required_if:current_status,studying|nullable|string|max:255',

            // professional details (required only when user is working, optional for studying users with past work)
            'organization' => 'nullable|array',
            'organization.*' => 'nullable|string|max:255',
            'industry' => 'nullable|array',
            'industry.*' => 'nullable|string|max:255',
            'role' => 'nullable|array',
            'role.*' => 'nullable|string|max:255',
            'location_exp' => 'nullable|array',
            'location_exp.*' => 'nullable|string|max:255',
            'from' => 'nullable|array',
            'from.*' => 'nullable|date',
            'to' => 'nullable|array',
            'to.*' => 'nullable|string|max:255',

            // previous education rows
            'previous_institution' => 'nullable|array',
            'previous_institution.*' => 'nullable|string|max:255',
            'previous_degree' => 'nullable|array',
            'previous_degree.*' => 'nullable|string|max:255',
            'previous_branch' => 'nullable|array',
            'previous_branch.*' => 'nullable|string|max:255',
            'previous_from' => 'nullable|array',
            'previous_from.*' => 'nullable|date',
            'previous_to' => 'nullable|array',
            'previous_to.*' => 'nullable|date',

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

        if ($request->current_status === 'working') {
            $organizations = collect($request->organization ?? [])->filter(fn ($value) => !empty(trim((string) $value)));

            if ($organizations->isEmpty()) {
                return back()
                    ->withErrors(['organization' => 'Please add at least one work experience if you are currently working.'])
                    ->withInput();
            }
        }

        $previousEducation = [];
        $previousInstitutions = $request->input('previous_institution', []);

        foreach ($previousInstitutions as $i => $institution) {
            $institution = trim((string) ($institution ?? ''));
            $degree = trim((string) ($request->previous_degree[$i] ?? ''));
            $branch = trim((string) ($request->previous_branch[$i] ?? ''));
            $from = $request->previous_from[$i] ?? null;
            $to = $request->previous_to[$i] ?? null;

            if ($institution === '' && $degree === '' && $branch === '' && empty($from) && empty($to)) {
                continue;
            }

            $previousEducation[] = [
                'institution' => $institution,
                'degree' => $degree,
                'branch' => $branch,
                'from' => $from,
                'to' => $to,
            ];
        }

        // ✅ IMAGE UPDATE
        if ($request->hasFile('profile_photo')) {
            $profile->profile_photo = $request->file('profile_photo')->store('profiles', 'public');
        }

        // ✅ UPDATE PROFILE
        $profile->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'city' => $request->city,
            'country' => $request->country,
            'contact_email' => $request->contact_email,

            'linkedin' => $request->linkedin,
            'instagram' => $request->instagram,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'current_status' => $request->current_status,
            'pursuing_educational_level' => $request->pursuing_educational_level,
            'highest_completed_educational_level' => $request->highest_completed_educational_level,
            'study_institution' => $request->current_status === 'studying' ? $request->study_institution : null,
            'study_degree' => $request->current_status === 'studying' ? $request->study_degree : null,
            'study_branch' => $request->current_status === 'studying' ? $request->study_branch : null,
            'study_from' => $request->current_status === 'studying' ? $request->study_from : null,
            'study_to' => $request->current_status === 'studying' ? $request->study_to : null,
            'previous_education' => $previousEducation,
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
            'first_name',
            'last_name',
            'gender',
            'city',
            'country',
            'contact_email',
            'linkedin',
            'instagram',
            'facebook',
            'twitter',
            'profile_photo',
            'current_status',
            'pursuing_educational_level',
            'highest_completed_educational_level',
            'study_institution',
            'study_degree',
            'study_branch',
            'study_from',
            'study_to',
            'previous_education',
        ]) : [];

        $updatedExperiences = Professional::where('user_id', Auth::id())
            ->orderBy('id')
            ->get(['organization', 'industry', 'role', 'from', 'to', 'location'])
            ->map(fn ($exp) => (array) $exp)
            ->toArray();

        $changes = [];

        foreach ($originalProfileData as $field => $oldValue) {
            $newValue = $updatedProfileData[$field] ?? null;
            $normalizedOld = is_array($oldValue) ? json_encode($oldValue) : (string) ($oldValue ?? '');
            $normalizedNew = is_array($newValue) ? json_encode($newValue) : (string) ($newValue ?? '');

            if ($normalizedOld !== $normalizedNew) {
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

    public function updateBasicProfile(Request $request): RedirectResponse
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other,prefer_not_to_say',
            'contact_email' => 'required|email|max:255',
            'full_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mobile' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'passing_year' => 'required',
            'pursuing_educational_level' => 'nullable|string|max:255',
            'highest_completed_educational_level' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        } else {
            unset($validated['profile_photo']);
        }

        $profile->update($validated);

        return redirect()->route('profile')->with('success', 'Basic profile details updated successfully');
    }

    public function updateEducationProfile(Request $request): RedirectResponse
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        $request->validate([
            'current_status' => 'required|in:studying,working',
            'study_institution' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_degree' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_branch' => 'required_if:current_status,studying|nullable|string|max:255',
            'study_from' => 'required_if:current_status,studying|nullable|date',
            'study_to' => 'required_if:current_status,studying|nullable|string|max:255',
            'school_institution' => 'nullable|string|max:255',
            'school_degree' => 'nullable|string|max:255',
            'school_branch' => 'nullable|string|max:255',
            'school_from' => 'nullable|date',
            'school_to' => 'nullable|date',
            'ug_institution' => 'nullable|string|max:255',
            'ug_degree' => 'nullable|string|max:255',
            'ug_branch' => 'nullable|string|max:255',
            'ug_from' => 'nullable|date',
            'ug_to' => 'nullable|date',
            'pg_institution' => 'nullable|string|max:255',
            'pg_degree' => 'nullable|string|max:255',
            'pg_branch' => 'nullable|string|max:255',
            'pg_from' => 'nullable|date',
            'pg_to' => 'nullable|date',
            'other_institution' => 'nullable|string|max:255',
            'other_degree' => 'nullable|string|max:255',
            'other_branch' => 'nullable|string|max:255',
            'other_from' => 'nullable|date',
            'other_to' => 'nullable|date',
        ]);

        $profile->update([
            'current_status' => $request->current_status,
            'study_institution' => $request->current_status === 'studying' ? $request->study_institution : null,
            'study_degree' => $request->current_status === 'studying' ? $request->study_degree : null,
            'study_branch' => $request->current_status === 'studying' ? $request->study_branch : null,
            'study_from' => $request->current_status === 'studying' ? $request->study_from : null,
            'study_to' => $request->current_status === 'studying' ? $request->study_to : null,
            'previous_education' => $this->buildEducationRowsFromSectionRequest($request),
        ]);

        return redirect()->route('profile')->with('success', 'Education details updated successfully');
    }

    public function updateWorkProfile(Request $request): RedirectResponse
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        $request->validate([
            'organization' => 'nullable|array',
            'organization.*' => 'nullable|string|max:255',
            'industry' => 'nullable|array',
            'industry.*' => 'nullable|string|max:255',
            'role' => 'nullable|array',
            'role.*' => 'nullable|string|max:255',
            'location_exp' => 'nullable|array',
            'location_exp.*' => 'nullable|string|max:255',
            'from' => 'nullable|array',
            'from.*' => 'nullable|date',
            'to' => 'nullable|array',
            'to.*' => 'nullable|string|max:255',
        ]);

        Professional::where('user_id', Auth::id())->delete();

        foreach (($request->organization ?? []) as $index => $organization) {
            $organization = trim((string) $organization);

            if ($organization === '') {
                continue;
            }

            Professional::create([
                'user_id' => Auth::id(),
                'organization' => $organization,
                'industry' => $request->industry[$index] ?? null,
                'role' => $request->role[$index] ?? null,
                'from' => $request->from[$index] ?? null,
                'to' => $request->to[$index] ?? null,
                'location' => $request->location_exp[$index] ?? null,
            ]);
        }

        return redirect()->route('profile')->with('success', 'Work experience updated successfully');
    }

    public function updateSocialProfile(Request $request): RedirectResponse
    {
        $profile = Profile::where('user_id', Auth::id())->first();

        if (! $profile) {
            return redirect()->route('profile.create');
        }

        $validated = $request->validate([
            'linkedin' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(linkedin\.com)\/.+/i'],
            'instagram' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(instagram\.com)\/.+/i'],
            'facebook' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(facebook\.com)\/.+/i'],
            'twitter' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?((x\.com)|(twitter\.com))\/.+/i'],
        ]);

        $profile->update($validated);

        return redirect()->route('profile')->with('success', 'Social links updated successfully');
    }
}
