<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;

class AdminController extends Controller
{
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
        ]);

        // Update user information
        if (isset($validated['name'])) {
            $user->update(['name' => $validated['name']]);
        }
        if (isset($validated['email'])) {
            $user->update(['email' => $validated['email']]);
        }

        // Update or create profile
        $profileData = [];
        foreach (['full_name', 'mobile', 'city', 'country', 'degree', 'branch', 'passing_year', 'current_status', 'company'] as $field) {
            if (isset($validated[$field])) {
                $profileData[$field] = $validated[$field];
            }
        }
        
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
        
        if (!empty($professionalData)) {
            if ($user->professional) {
                $user->professional->update($professionalData);
            } else {
                $professionalData['user_id'] = $user->id;
                \App\Models\Professional::create($professionalData);
            }
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alumni updated successfully']);
        }

        return redirect()->route('admin.allalumini')->with('success', 'Alumni details updated successfully.');
    }
}