<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SkillController extends Controller
{
    public function index(): View
    {
        $skills = auth()->user()->skills()->with('endorsements')->get();
        return view('skills.index', compact('skills'));
    }

    public function create(): View
    {
        return view('skills.create');
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|in:beginner,intermediate,advanced,expert',
        ]);

        $skill = Skill::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'level' => $request->level ?? 'beginner',
            'endorsements_count' => 0,
        ]);

        ActivityLog::record(
            auth()->id(),
            auth()->id(),
            'skill_created',
            (auth()->user()?->name ?? 'Alumni') . ' added skill: ' . $skill->name,
            [
                'skill_id' => $skill->id,
                'name' => $skill->name,
                'level' => $skill->level,
            ]
        );

        return response()->json([
            'success' => true,
            'skill' => $skill,
            'message' => 'Skill added successfully!'
        ]);
    }

    public function edit(Skill $skill): View
    {
        $this->authorize('update', $skill);
        return view('skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $this->authorize('update', $skill);

        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:beginner,intermediate,advanced,expert',
        ]);

        $previousData = $skill->only(['name', 'level']);

        $skill->update([
            'name' => $request->name,
            'level' => $request->level,
        ]);

        ActivityLog::record(
            auth()->id(),
            auth()->id(),
            'skill_updated',
            (auth()->user()?->name ?? 'Alumni') . ' updated skill: ' . $skill->name,
            [
                'skill_id' => $skill->id,
                'before' => $previousData,
                'after' => $skill->only(['name', 'level']),
            ]
        );

        return redirect()->route('skills.index')->with('success', 'Skill updated successfully!');
    }

    public function destroy(Skill $skill): JsonResponse
    {
        // Ensure user owns the skill
        if ($skill->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $skillSnapshot = $skill->only(['id', 'name', 'level', 'endorsements_count']);

        $skill->delete();

        ActivityLog::record(
            auth()->id(),
            auth()->id(),
            'skill_deleted',
            (auth()->user()?->name ?? 'Alumni') . ' removed skill: ' . ($skillSnapshot['name'] ?? 'Skill'),
            [
                'skill' => $skillSnapshot,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Skill removed successfully!'
        ]);
    }

    public function endorse(Request $request, Skill $skill): JsonResponse
    {
        $user = auth()->user();

        // Check if user already endorsed this skill
        if ($skill->endorsements()->where('endorser_id', $user->id)->exists()) {
            return response()->json(['error' => 'You have already endorsed this skill'], 400);
        }

        $skill->endorsements()->create([
            'endorser_id' => $user->id,
            'endorser_name' => $user->name,
        ]);

        $skill->increment('endorsements_count');

        ActivityLog::record(
            $user->id,
            $skill->user_id,
            'skill_endorsed',
            ($user->name ?? 'Alumni') . ' endorsed skill ' . $skill->name,
            [
                'skill_id' => $skill->id,
                'skill_name' => $skill->name,
                'owner_user_id' => $skill->user_id,
                'endorsements_count' => $skill->endorsements_count,
            ]
        );

        return response()->json([
            'success' => true,
            'endorsements_count' => $skill->endorsements_count,
        ]);
    }

    public function removeEndorsement(Skill $skill): JsonResponse
    {
        $user = auth()->user();

        $endorsement = $skill->endorsements()->where('endorser_id', $user->id)->first();

        if (!$endorsement) {
            return response()->json(['error' => 'You have not endorsed this skill'], 400);
        }

        $endorsement->delete();
        $skill->decrement('endorsements_count');

        ActivityLog::record(
            $user->id,
            $skill->user_id,
            'skill_endorsement_removed',
            ($user->name ?? 'Alumni') . ' removed endorsement for skill ' . $skill->name,
            [
                'skill_id' => $skill->id,
                'skill_name' => $skill->name,
                'owner_user_id' => $skill->user_id,
                'endorsements_count' => $skill->endorsements_count,
            ]
        );

        return response()->json([
            'success' => true,
            'endorsements_count' => $skill->endorsements_count,
        ]);
    }
}
