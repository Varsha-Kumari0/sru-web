<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{


    public function create()
    {
        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'achievement_create_opened',
            ($actor?->name ?? 'User') . ' opened achievement create page'
        );

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

        $achievement = Achievement::create([
            'user_id'     => Auth::id(),
            'title'       => $validated['title'],
            'category'    => $validated['category'],
            'description' => $validated['description'] ?? null,
            'earned_at'   => $validated['earned_at'] ?? null,
            'proof_url'   => $validated['proof_url'] ?? null,
            'badge_icon'  => $icons[$validated['category']],
            'source'      => 'self_reported',
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'achievement_created',
            ($actor?->name ?? 'User') . ' added achievement: ' . $achievement->title,
            [
                'achievement_id' => $achievement->id,
                'title' => $achievement->title,
                'category' => $achievement->category,
            ]
        );

        return redirect()->route('profile')->with('status', 'Achievement added successfully!');
    }
}