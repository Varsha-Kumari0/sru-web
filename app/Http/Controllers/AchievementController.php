<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{


    public function create()
    {
        return view('pages.achievement-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|in:career,leadership,academic,entrepreneurship,community,other',
            'description' => 'nullable|string|max:500',
            'earned_at'   => 'nullable|date',
            'proof_url'   => 'nullable|url|max:500',
        ]);

        $icons = [
            'career'          => '💼',
            'leadership'      => '🏆',
            'academic'        => '🎓',
            'entrepreneurship'=> '🚀',
            'community'       => '🤝',
            'other'           => '⭐',
        ];

        Achievement::create([
            'user_id'     => Auth::id(),
            'title'       => $validated['title'],
            'category'    => $validated['category'],
            'description' => $validated['description'] ?? null,
            'earned_at'   => $validated['earned_at'] ?? null,
            'proof_url'   => $validated['proof_url'] ?? null,
            'badge_icon'  => $icons[$validated['category']],
            'source'      => 'self_reported',
        ]);

        return redirect()->route('profile')->with('status', 'Achievement added successfully!');
    }
}