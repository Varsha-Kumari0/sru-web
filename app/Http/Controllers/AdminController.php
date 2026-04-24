<?php

namespace App\Http\Controllers;

use App\Models\Profile;
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

    public function approveAlumni($id)
    {
        $profile = Profile::where('user_id', $id)->first();

        if (!$profile) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Profile not found'], 404);
            }

            return back()->with('error', 'Profile not found.');
        }

        $profile->update(['status' => 'active']);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Alumni approved successfully']);
        }

        return back()->with('success', 'Alumni approved successfully.');
    }
}