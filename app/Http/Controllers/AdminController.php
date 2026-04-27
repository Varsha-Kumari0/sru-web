<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Profile;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Reusable query builder for activity logs list and export endpoints.
    private function buildActivityLogsQuery(Request $request)
    {
        $query = ActivityLog::query()->with(['actor', 'subject']);

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->string('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->string('to_date'));
        }

        if ($request->filled('actor_user_id')) {
            $query->where('actor_user_id', (int) $request->input('actor_user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        return $query;
    }

    public function activityLogs(Request $request)
    {
        // Validate filter parameters before building the query.
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'actor_user_id' => 'nullable|integer|exists:users,id',
            'action' => 'nullable|string|max:100',
        ]);

        $logs = $this->buildActivityLogsQuery($request)
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        // Actor dropdown includes only users who have produced logs.
        $actors = User::query()
            ->whereIn(
                'id',
                ActivityLog::query()->whereNotNull('actor_user_id')->distinct()->pluck('actor_user_id')
            )
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $actions = ActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        return view('admin.logs.activity-logs', compact('logs', 'actors', 'actions'));
    }

    public function exportActivityLogsCsv(Request $request)
    {
        // Keep CSV export filters identical to on-screen filter rules.
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date',
            'actor_user_id' => 'nullable|integer|exists:users,id',
            'action' => 'nullable|string|max:100',
        ]);

        $logs = $this->buildActivityLogsQuery($request)
            ->latest('created_at')
            ->get();

        $actor = Auth::user();

        ActivityLog::record(
            Auth::id(),
            Auth::id(),
            'activity_logs_exported',
            ($actor?->name ?? 'Admin') . ' exported activity logs CSV',
            [
                'from_date' => $request->input('from_date'),
                'to_date' => $request->input('to_date'),
                'actor_user_id' => $request->input('actor_user_id'),
                'action' => $request->input('action'),
            ]
        );

        $filename = 'activity_logs_' . now()->format('Y-m-d') . '.csv';

        // Stream rows to avoid high memory usage on larger exports.
        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Date Time',
                'Action',
                'Description',
                'Actor Name',
                'Actor Email',
                'Subject Name',
                'Subject Email',
            ]);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->id,
                    $log->created_at?->format('Y-m-d H:i:s'),
                    $log->action,
                    $log->description,
                    $log->actor?->name ?? '-',
                    $log->actor?->email ?? '-',
                    $log->subject?->name ?? '-',
                    $log->subject?->email ?? '-',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Remove an alumni user and all related data (profile, professional).
     */
    public function deleteAlumni($id)
    {
        /** @var User|null $user */
        $user = User::query()->find($id);
        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            return back()->with('error', 'User not found.');
        }

        $actorName = Auth::user()?->name ?? 'System';
        $targetName = $user->profile?->full_name ?: $user->name;

        ActivityLog::record(
            Auth::id(),
            $user->id,
            'alumni_deleted',
            $actorName . ' deleted alumni record for ' . $targetName,
            [
                'email' => $user->email,
                'subject_name' => $targetName,
            ]
        );

        // Delete related profile and professional records
        $user->profile()?->delete();
        $user->professional()?->delete();
        $user->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alumni deleted successfully']);
        }

        return back()->with('success', 'Alumni deleted successfully.');
    }

    /**
     * Show the edit form for an alumni
     */
    public function editAlumni($id)
    {
        /** @var User|null $user */
        $user = User::query()->with(['profile', 'professional'])->find($id);
        
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $selectDegree = [
            'B.Tech' => [
                'CSE (AI & ML)',
                'CSE (Cybersecurity)',
                'CSE (Data Science)',
                'ECE (VLSI)',
                'EEE (Renewable Energy)',
                'Mechanical (Smart Manufacturing)',
                'Civil (Robotics and Automation)',
            ],
            'Business' => [
                'BBA (Marketing)',
                'BBA (Finance)',
                'BBA (Operations)',
                'BBA (International Business)',
                'BBA (Business Analytics)',
            ],
            'Agriculture' => ['B.Sc (Hons) Agriculture'],
            'B.Sc' => [
                'B.Sc (Computer Science)',
                'B.Sc (Physics)',
                'B.Sc (Chemistry)',
                'B.Sc (Mathematics)',
                'B.Sc (Forensic Science)',
            ],
            'B.Com' => ['B.Com (Computer Applications)'],
            'BCA' => ['BCA General', 'BCA (Cloud Computing)'],
        ];

        // Compute previous education text for the view
        $previousEducationTextDefault = collect($user->profile?->previous_education ?? [])
            ->map(function ($row) {
                return implode(' | ', [
                    $row['institution'] ?? '',
                    $row['degree'] ?? '',
                    $row['branch'] ?? '',
                    $row['from'] ?? '',
                    $row['to'] ?? '',
                ]);
            })
            ->filter()
            ->implode("\n") ?? '';

        return view('admin.alumni.edit-alumni', compact('user', 'selectDegree', 'previousEducationTextDefault'));
    }

    /**
     * Update alumni details (user, profile, and professional)
     */
    public function updateAlumni(Request $request, $id)
    {
        /** @var User|null $user */
        $user = User::query()->find($id);

        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }
            return back()->with('error', 'User not found.');
        }

        $originalUserData = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        $originalProfileData = $user->profile
            ? $user->profile->only([
                'full_name',
                'father_name',
                'mobile',
                'city',
                'country',
                'linkedin',
                'facebook',
                'instagram',
                'twitter',
                'degree',
                'branch',
                'passing_year',
                'current_status',
                'company',
                'employment_from',
                'employment_to',
                'study_institution',
                'study_degree',
                'study_branch',
                'study_from',
                'study_to',
                'description',
                'previous_education',
                'profile_photo',
            ])
            : [];

        $originalProfessionalData = $user->professional
            ? $user->professional->only(['organization', 'industry', 'role', 'from', 'to', 'location'])
            : [];

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'full_name' => 'sometimes|nullable|string|max:255',
            'father_name' => 'sometimes|nullable|string|max:255',
            'mobile' => 'sometimes|nullable|string|max:20',
            'city' => 'sometimes|nullable|string|max:255',
            'country' => 'sometimes|nullable|string|max:255',
            'linkedin' => 'sometimes|nullable|string|max:255',
            'facebook' => 'sometimes|nullable|string|max:255',
            'instagram' => 'sometimes|nullable|string|max:255',
            'twitter' => 'sometimes|nullable|string|max:255',
            'degree' => 'sometimes|nullable|string|max:255',
            'branch' => 'sometimes|nullable|string|max:255',
            'passing_year' => 'sometimes|nullable|regex:/^\d{4}$/',
            'current_status' => 'sometimes|nullable|string|max:255',
            'company' => 'sometimes|nullable|string|max:255',
            'employment_from' => 'sometimes|nullable|date',
            'employment_to' => 'sometimes|nullable|string|max:255',
            'study_institution' => 'sometimes|nullable|string|max:255',
            'study_degree' => 'sometimes|nullable|string|max:255',
            'study_branch' => 'sometimes|nullable|string|max:255',
            'study_from' => 'sometimes|nullable|date',
            'study_to' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string|max:2000',
            'previous_education_text' => 'sometimes|nullable|string|max:5000',
            'organization' => 'sometimes|nullable|string|max:255',
            'industry' => 'sometimes|nullable|string|max:255',
            'role' => 'sometimes|nullable|string|max:255',
            'from' => 'sometimes|nullable|string|max:255',
            'to' => 'sometimes|nullable|string|max:255',
            'location' => 'sometimes|nullable|string|max:255',
            'is_current' => 'sometimes|boolean',
            'is_current_employment' => 'sometimes|boolean',
            'profile_photo' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $userChanged = false;

        // Update user information
        if (isset($validated['name'])) {
            $user->update(['name' => $validated['name']]);
            $userChanged = true;
        }
        if (isset($validated['email'])) {
            $user->update(['email' => $validated['email']]);
            $userChanged = true;
        }

        // Update or create profile
        $profileData = [];
        foreach ([
            'full_name',
            'father_name',
            'mobile',
            'city',
            'country',
            'linkedin',
            'facebook',
            'instagram',
            'twitter',
            'degree',
            'branch',
            'passing_year',
            'current_status',
            'company',
            'employment_from',
            'employment_to',
            'study_institution',
            'study_degree',
            'study_branch',
            'study_from',
            'study_to',
            'description',
        ] as $field) {
            if (isset($validated[$field])) {
                $profileData[$field] = $validated[$field];
            }
        }

        // Parse previous education from textarea rows: institution | degree | branch | from | to
        if (array_key_exists('previous_education_text', $validated)) {
            $lines = preg_split('/\r\n|\r|\n/', (string) $validated['previous_education_text']);
            $parsedPreviousEducation = [];

            foreach ($lines as $line) {
                $line = trim((string) $line);
                if ($line === '') {
                    continue;
                }

                $parts = array_map('trim', explode('|', $line));

                $parsedPreviousEducation[] = [
                    'institution' => $parts[0] ?? '',
                    'degree' => $parts[1] ?? '',
                    'branch' => $parts[2] ?? '',
                    'from' => $parts[3] ?? '',
                    'to' => $parts[4] ?? '',
                ];
            }

            $profileData['previous_education'] = !empty($parsedPreviousEducation) ? $parsedPreviousEducation : null;
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $imagePath = $request->file('profile_photo')->store('profiles', 'public');
            $profileData['profile_photo'] = $imagePath;
        }
        
        // Handle 'employment_to' field - if is_current_employment is checked, set to 'Present', otherwise use provided value
        if (isset($validated['is_current_employment']) && $validated['is_current_employment']) {
            $profileData['employment_to'] = 'Present';
        } elseif (isset($validated['employment_to'])) {
            $profileData['employment_to'] = $validated['employment_to'];
        }
        
        $profileChanged = !empty($profileData);

        if (!empty($profileData)) {
            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                $profileData['user_id'] = $user->id;
                Profile::create($profileData);
            }
        }

        // Update or create professional record
        $professionalData = [];
        foreach (['organization', 'industry', 'role', 'from', 'location'] as $field) {
            if (isset($validated[$field])) {
                $professionalData[$field] = $validated[$field];
            }
        }
        
        // Handle 'to' field - if is_current is checked, set to 'Present', otherwise use provided value
        if (isset($validated['is_current']) && $validated['is_current']) {
            $professionalData['to'] = 'Present';
        } elseif (isset($validated['to'])) {
            $professionalData['to'] = $validated['to'];
        }
        
        $professionalChanged = !empty($professionalData);

        if (!empty($professionalData)) {
            if ($user->professional) {
                $user->professional->update($professionalData);
            } else {
                $professionalData['user_id'] = $user->id;
                Professional::create($professionalData);
            }
        }

        if ($userChanged || $profileChanged || $professionalChanged) {
            $user->refresh();
            $user->load(['profile', 'professional']);

            $updatedUserData = [
                'name' => $user->name,
                'email' => $user->email,
            ];

            $updatedProfileData = $user->profile
                ? $user->profile->only([
                    'full_name',
                    'father_name',
                    'mobile',
                    'city',
                    'country',
                    'linkedin',
                    'facebook',
                    'instagram',
                    'twitter',
                    'degree',
                    'branch',
                    'passing_year',
                    'current_status',
                    'company',
                    'employment_from',
                    'employment_to',
                    'study_institution',
                    'study_degree',
                    'study_branch',
                    'study_from',
                    'study_to',
                    'description',
                    'previous_education',
                    'profile_photo',
                ])
                : [];

            $updatedProfessionalData = $user->professional
                ? $user->professional->only(['organization', 'industry', 'role', 'from', 'to', 'location'])
                : [];

            $normalizeValue = static function ($value): string {
                if (is_null($value) || $value === '') {
                    return 'Empty';
                }

                if (is_bool($value)) {
                    return $value ? 'Yes' : 'No';
                }

                if (is_array($value)) {
                    return json_encode($value, JSON_UNESCAPED_UNICODE) ?: '[]';
                }

                return (string) $value;
            };

            $fieldChanges = [];

            $collectChanges = static function (array $labels, array $before, array $after, string $group) use (&$fieldChanges, $normalizeValue): void {
                foreach ($labels as $field => $label) {
                    $oldRaw = $before[$field] ?? null;
                    $newRaw = $after[$field] ?? null;

                    if ((string) ($oldRaw ?? '') === (string) ($newRaw ?? '')) {
                        continue;
                    }

                    $fieldChanges[] = [
                        'group' => $group,
                        'field' => $label,
                        'from' => $normalizeValue($oldRaw),
                        'to' => $normalizeValue($newRaw),
                    ];
                }
            };

            $collectChanges(
                [
                    'name' => 'Account Name',
                    'email' => 'Email',
                ],
                $originalUserData,
                $updatedUserData,
                'Account'
            );

            $collectChanges(
                [
                    'full_name' => 'Full Name',
                    'father_name' => 'Father Name',
                    'mobile' => 'Mobile',
                    'city' => 'City',
                    'country' => 'Country',
                    'linkedin' => 'LinkedIn',
                    'facebook' => 'Facebook',
                    'instagram' => 'Instagram',
                    'twitter' => 'Twitter / X',
                    'degree' => 'Degree',
                    'branch' => 'Branch / Specialization',
                    'passing_year' => 'Passing Year',
                    'current_status' => 'Current Status',
                    'company' => 'Company',
                    'employment_from' => 'Employment From',
                    'employment_to' => 'Employment To',
                    'study_institution' => 'Study Institution',
                    'study_degree' => 'Study Degree',
                    'study_branch' => 'Study Branch',
                    'study_from' => 'Study From',
                    'study_to' => 'Study To',
                    'description' => 'Bio / Description',
                    'previous_education' => 'Previous Education',
                    'profile_photo' => 'Profile Photo',
                ],
                $originalProfileData,
                $updatedProfileData,
                'Profile'
            );

            $collectChanges(
                [
                    'organization' => 'Organization',
                    'industry' => 'Industry',
                    'role' => 'Role',
                    'from' => 'Work From',
                    'to' => 'Work To',
                    'location' => 'Work Location',
                ],
                $originalProfessionalData,
                $updatedProfessionalData,
                'Professional'
            );

            $actorName = Auth::user()?->name ?? 'System';
            $targetName = $user->profile?->full_name ?: $user->name;
            $changeCount = count($fieldChanges);

            ActivityLog::record(
                Auth::id(),
                $user->id,
                'alumni_updated',
                $actorName . ' updated alumni record for ' . $targetName . ($changeCount > 0 ? ' (' . $changeCount . ' changes)' : ''),
                [
                    'user_fields_updated' => $userChanged,
                    'profile_fields_updated' => $profileChanged,
                    'professional_fields_updated' => $professionalChanged,
                    'changes' => $fieldChanges,
                ]
            );
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alumni updated successfully']);
        }

        return redirect()->route('admin.allalumini')->with('success', 'Alumni details updated successfully.');
    }

    /**
     * Upload and save the admin's own profile photo (avatar).
     */
    public function updateAdminAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        /** @var User|null $admin */
        $admin = Auth::user();

        if (!$admin) {
            return back()->with('error', 'Admin user not found.');
        }

        // Delete old avatar file if it exists.
        if ($admin->avatar) {
            Storage::disk('public')->delete($admin->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        $admin->update(['avatar' => $path]);

        ActivityLog::record(
            $admin->id,
            $admin->id,
            'admin_avatar_updated',
            ($admin->name ?? 'Admin') . ' updated their profile photo',
            []
        );

        return back()->with('success', 'Profile photo updated.');
    }
}