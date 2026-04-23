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
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
        // Delete related profile and professional records
        $user->profile()?->delete();
        $user->professional()?->delete();
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Alumni deleted successfully']);
    }
    public function approveAlumni($id)
    {
        $profile = Profile::where('user_id', $id)->first();

        if (!$profile) {
            return response()->json(['success' => false, 'message' => 'Profile not found'], 404);
        }

        $profile->update(['status' => 'active']);

        return response()->json(['success' => true, 'message' => 'Alumni approved successfully']);
    }
}