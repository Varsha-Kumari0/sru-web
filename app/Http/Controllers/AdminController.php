<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class AdminController extends Controller
{
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