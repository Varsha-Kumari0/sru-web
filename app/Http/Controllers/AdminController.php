<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;

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

        return view('admin.activity-logs', compact('logs', 'actors', 'actions'));
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

        ActivityLog::record(
            auth()->id(),
            auth()->id(),
            'activity_logs_exported',
            (auth()->user()?->name ?? 'Admin') . ' exported activity logs CSV',
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
        $user = \App\Models\User::find($id);
        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }

            return back()->with('error', 'User not found.');
        }
        $actorName = auth()->user()?->name ?? 'System';
        $targetName = $user->profile?->full_name ?: $user->name;

        ActivityLog::record(
            auth()->id(),
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
        $user = \App\Models\User::with(['profile', 'professional'])->find($id);
        
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        return view('admin.edit-alumni', compact('user'));
    }

    /**
     * Update alumni details (user, profile, and professional)
     */
    public function updateAlumni(Request $request, $id)
    {
        $user = \App\Models\User::find($id);

        if (!$user) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'User not found'], 404);
            }
            return back()->with('error', 'User not found.');
        }

        // Validate input
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'full_name' => 'sometimes|string|max:255',
            'mobile' => 'sometimes|string|max:20',
            'city' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'degree' => 'sometimes|string|max:255',
            'branch' => 'sometimes|string|max:255',
            'passing_year' => 'sometimes|nullable|regex:/^\d{4}$/',
            'current_status' => 'sometimes|string|max:255',
            'company' => 'sometimes|string|max:255',
            'organization' => 'sometimes|string|max:255',
            'industry' => 'sometimes|string|max:255',
            'role' => 'sometimes|string|max:255',
            'from' => 'sometimes|string|max:255',
            'to' => 'sometimes|nullable|string|max:255',
            'location' => 'sometimes|string|max:255',
            'is_current' => 'sometimes|boolean',
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
        foreach (['full_name', 'mobile', 'city', 'country', 'degree', 'branch', 'passing_year', 'current_status', 'company'] as $field) {
            if (isset($validated[$field])) {
                $profileData[$field] = $validated[$field];
            }
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $imagePath = $request->file('profile_photo')->store('profiles', 'public');
            $profileData['profile_photo'] = $imagePath;
        }
        
        $profileChanged = !empty($profileData);

        if (!empty($profileData)) {
            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                $profileData['user_id'] = $user->id;
                \App\Models\Profile::create($profileData);
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
                \App\Models\Professional::create($professionalData);
            }
        }

        if ($userChanged || $profileChanged || $professionalChanged) {
            $actorName = auth()->user()?->name ?? 'System';
            $targetName = $user->profile?->full_name ?: $user->name;

            ActivityLog::record(
                auth()->id(),
                $user->id,
                'alumni_updated',
                $actorName . ' updated alumni record for ' . $targetName,
                [
                    'user_fields_updated' => $userChanged,
                    'profile_fields_updated' => $profileChanged,
                    'professional_fields_updated' => $professionalChanged,
                ]
            );
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alumni updated successfully']);
        }

        return redirect()->route('admin.allalumini')->with('success', 'Alumni details updated successfully.');
    }
}