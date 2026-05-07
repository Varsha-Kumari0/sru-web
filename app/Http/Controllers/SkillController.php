<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Skill;
use App\Models\SkillEndorsement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        if (!$userId) {
            abort(401);
        }

        $user = User::findOrFail($userId);

        ActivityLog::record(
            $user->id,
            $user->id,
            'skills_index_opened',
            ($user->name ?? 'User') . ' opened skills page'
        );

        $skills = $user->skills()
            ->with(['endorsements.endorser.profile'])
            ->get();

        $endorsedSkillIds = SkillEndorsement::query()
            ->where('endorser_id', $user->id)
            ->pluck('skill_id')
            ->map(fn ($skillId) => (int) $skillId)
            ->all();

        $endorsementSkills = Skill::query()
            ->with(['user.profile', 'endorsements' => function ($query) use ($user) {
                $query->where('endorser_id', $user->id);
            }])
            ->where('user_id', '!=', $user->id)
            ->latest('updated_at')
            ->get();

        return view('skills.index', compact('skills', 'endorsementSkills', 'endorsedSkillIds'));
    }

    public function create(): View
    {
        $user = Auth::user();
        ActivityLog::record(
            $user?->id,
            $user?->id,
            'skill_create_opened',
            ($user?->name ?? 'User') . ' opened add skill page'
        );

        return view('skills.create');
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|in:beginner,intermediate,advanced,expert',
        ]);

        $skill = Skill::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'level' => $request->level ?? 'beginner',
            'endorsements' => 0,
            'endorsements_count' => 0,
        ]);

        ActivityLog::record(
            $user->id,
            $user->id,
            'skill_created',
            ($user->name ?? 'Alumni') . ' added skill: ' . $skill->name,
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

        $user = Auth::user();
        ActivityLog::record(
            $user?->id,
            $user?->id,
            'skill_edit_opened',
            ($user?->name ?? 'User') . ' opened skill edit page',
            [
                'skill_id' => $skill->id,
                'skill_name' => $skill->name,
            ]
        );

        return view('skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill): JsonResponse|RedirectResponse
    {
        $this->authorize('update', $skill);

        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

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
            $user->id,
            $user->id,
            'skill_updated',
            ($user->name ?? 'Alumni') . ' updated skill: ' . $skill->name,
            [
                'skill_id' => $skill->id,
                'before' => $previousData,
                'after' => $skill->only(['name', 'level']),
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'skill' => $skill,
                'message' => 'Skill updated successfully!',
            ]);
        }

        return redirect()->route('skills.index')->with('success', 'Skill updated successfully!');
    }

    public function destroy(Skill $skill): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        // Ensure user owns the skill
        if ($skill->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $skillSnapshot = $skill->only(['id', 'name', 'level', 'endorsements_count']);

        Skill::query()->whereKey($skill->getKey())->delete();

        ActivityLog::record(
            $user->id,
            $user->id,
            'skill_deleted',
            ($user->name ?? 'Alumni') . ' removed skill: ' . ($skillSnapshot['name'] ?? 'Skill'),
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
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        if ((int) $skill->user_id === (int) $user->id) {
            return response()->json(['error' => 'You cannot endorse your own skill'], 422);
        }

        // Check if user already endorsed this skill
        if ($skill->endorsements()->where('endorser_id', $user->id)->exists()) {
            return response()->json(['error' => 'You have already endorsed this skill'], 400);
        }

        $skill->endorsements()->create([
            'endorser_id' => $user->id,
            'endorser_name' => $user->name,
        ]);

        Skill::query()->whereKey($skill->getKey())->update([
            'endorsements' => DB::raw('endorsements + 1'),
            'endorsements_count' => DB::raw('endorsements_count + 1'),
        ]);
        $skill->refresh();

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
        $user = Auth::user();
        if (!$user) {
            abort(401);
        }

        $endorsementId = SkillEndorsement::query()
            ->where('skill_id', $skill->id)
            ->where('endorser_id', $user->id)
            ->value('id');

        if (!$endorsementId) {
            return response()->json(['error' => 'You have not endorsed this skill'], 400);
        }

        SkillEndorsement::query()->whereKey($endorsementId)->delete();
        Skill::query()->whereKey($skill->getKey())->update([
            'endorsements' => DB::raw('CASE WHEN endorsements > 0 THEN endorsements - 1 ELSE 0 END'),
            'endorsements_count' => DB::raw('CASE WHEN endorsements_count > 0 THEN endorsements_count - 1 ELSE 0 END'),
        ]);
        $skill->refresh();

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
