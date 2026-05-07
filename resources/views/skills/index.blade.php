@extends('layouts.app')

@section('title', 'My Skills')

@section('content')
<div class="min-h-screen bg-[#f4f6f9] py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-[#e2e8f0]">
            <div class="px-6 py-4 border-b border-[#e2e8f0] flex justify-between items-center">
                <h1 class="text-2xl font-bold text-[#1a2d5a]">My Skills</h1>
                <button onclick="openAddSkillModal()" class="bg-[#1a2d5a] hover:bg-[#141d42] text-white px-4 py-2 rounded-lg transition-colors duration-200">
                    Add Skill
                </button>
            </div>

            <div class="p-6">
                @if($skills->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($skills as $skill)
                            <div class="border border-[#e2e8f0] rounded-lg p-4 hover:shadow-md transition-shadow bg-white">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-semibold text-[#1a2d5a]">{{ $skill->name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $skill->level_color }}">
                                            {{ $skill->level_text }}
                                        </span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="editSkill({{ $skill->id }}, @js($skill->name), @js($skill->level))"
                                                class="text-[#c0006a] hover:text-[#9a0052] text-sm">
                                            Edit
                                        </button>
                                        <button onclick="deleteSkill({{ $skill->id }})"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-600">
                                            {{ $skill->endorsements_count }} endorsement{{ $skill->endorsements_count !== 1 ? 's' : '' }}
                                        </span>
                                        @if($skill->endorsements_count > 0)
                                            <button onclick="showEndorsers({{ $skill->id }})"
                                                    class="text-[#c0006a] hover:text-[#9a0052] text-sm underline">
                                                View
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-[#1a2d5a]">No skills added yet</h3>
                        <p class="mt-1 text-sm text-gray-600">Start building your professional profile by adding your skills.</p>
                        <div class="mt-6">
                            <button onclick="openAddSkillModal()" class="bg-[#1a2d5a] hover:bg-[#141d42] text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                Add Your First Skill
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="mt-8 bg-white rounded-lg shadow-sm border border-[#e2e8f0]">
            <div class="px-6 py-4 border-b border-[#e2e8f0]">
                <h2 class="text-xl font-bold text-[#1a2d5a]">Endorse Alumni Skills</h2>
                <p class="mt-1 text-sm text-gray-600">Recognize skills shared by other alumni.</p>
            </div>

            <div class="p-6">
                @if(($endorsementSkills ?? collect())->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($endorsementSkills as $skill)
                            @php
                                $ownerName = $skill->user?->profile?->full_name ?: ($skill->user?->name ?? 'Alumni');
                                $isEndorsed = in_array($skill->id, $endorsedSkillIds ?? [], true);
                            @endphp
                            <div class="border border-[#e2e8f0] rounded-lg p-4 hover:shadow-md transition-shadow bg-white">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-[#1a2d5a] truncate">{{ $skill->name }}</h3>
                                        <a href="{{ route('profile.show', $skill->user_id) }}" class="mt-1 block text-sm text-gray-600 truncate hover:text-[#1a2d5a] hover:underline">{{ $ownerName }}</a>
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $skill->level_color }}">
                                                {{ $skill->level_text }}
                                            </span>
                                            <span class="text-sm text-gray-600" data-endorsement-count="{{ $skill->id }}">
                                                {{ $skill->endorsements_count }} endorsement{{ $skill->endorsements_count !== 1 ? 's' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        data-endorse-button="{{ $skill->id }}"
                                        data-endorsed="{{ $isEndorsed ? 'true' : 'false' }}"
                                        onclick="toggleEndorsement({{ $skill->id }})"
                                        class="shrink-0 rounded-lg px-3 py-2 text-sm font-semibold {{ $isEndorsed ? 'border border-[#1a2d5a] text-[#1a2d5a] bg-white hover:bg-[#f4f6f9]' : 'bg-[#1a2d5a] text-white hover:bg-[#141d42]' }}">
                                        {{ $isEndorsed ? 'Endorsed' : 'Endorse' }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-10">
                        <h3 class="text-sm font-medium text-[#1a2d5a]">No alumni skills available yet</h3>
                        <p class="mt-1 text-sm text-gray-600">When other alumni add skills, you can endorse them here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Skill Modal -->
<div id="addSkillModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-[#1a2d5a] mb-4">Add New Skill</h3>
            <form id="addSkillForm" onsubmit="addSkill(event)">
                @csrf
                <div class="mb-4">
                    <label for="skillName" class="block text-sm font-medium text-[#1a2d5a]">Skill Name</label>
                    <input type="text" id="skillName" name="name" required
                           class="mt-1 block w-full px-3 py-2 border border-[#e2e8f0] rounded-md shadow-sm focus:outline-none focus:ring-[#1a2d5a] focus:border-[#1a2d5a]">
                </div>
                <div class="mb-4">
                    <label for="skillLevel" class="block text-sm font-medium text-[#1a2d5a]">Proficiency Level</label>
                    <select id="skillLevel" name="level" required
                            class="mt-1 block w-full px-3 py-2 border border-[#e2e8f0] rounded-md shadow-sm focus:outline-none focus:ring-[#1a2d5a] focus:border-[#1a2d5a]">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="expert">Expert</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddSkillModal()"
                            class="px-4 py-2 text-sm font-medium text-[#1a2d5a] bg-[#f4f6f9] border border-[#e2e8f0] rounded-md hover:bg-[#e9ecf1]">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[#1a2d5a] border border-transparent rounded-md hover:bg-[#141d42]">
                        Add Skill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Skill Modal -->
<div id="editSkillModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-[#1a2d5a] mb-4">Edit Skill</h3>
            <form id="editSkillForm" onsubmit="updateSkill(event)">
                @csrf
                @method('PUT')
                <input type="hidden" id="editSkillId" name="skill_id">
                <div class="mb-4">
                    <label for="editSkillName" class="block text-sm font-medium text-[#1a2d5a]">Skill Name</label>
                    <input type="text" id="editSkillName" name="name" required
                           class="mt-1 block w-full px-3 py-2 border border-[#e2e8f0] rounded-md shadow-sm focus:outline-none focus:ring-[#1a2d5a] focus:border-[#1a2d5a]">
                </div>
                <div class="mb-4">
                    <label for="editSkillLevel" class="block text-sm font-medium text-[#1a2d5a]">Proficiency Level</label>
                    <select id="editSkillLevel" name="level" required
                            class="mt-1 block w-full px-3 py-2 border border-[#e2e8f0] rounded-md shadow-sm focus:outline-none focus:ring-[#1a2d5a] focus:border-[#1a2d5a]">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="expert">Expert</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditSkillModal()"
                            class="px-4 py-2 text-sm font-medium text-[#1a2d5a] bg-[#f4f6f9] border border-[#e2e8f0] rounded-md hover:bg-[#e9ecf1]">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-[#1a2d5a] border border-transparent rounded-md hover:bg-[#141d42]">
                        Update Skill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="endorsersModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 max-w-[92vw] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-[#1a2d5a]">Endorsed by</h3>
                <button type="button" onclick="closeEndorsersModal()" class="text-gray-500 hover:text-[#1a2d5a]">Close</button>
            </div>
            <div id="endorsersModalBody" class="space-y-2 text-sm text-gray-700"></div>
        </div>
    </div>
</div>

<script>
const skillEndorsers = @json($skills->mapWithKeys(function ($skill) {
    $endorsements = $skill->relationLoaded('endorsements')
        ? $skill->getRelation('endorsements')
        : collect();

    return [
        $skill->id => $endorsements->map(function ($endorsement) {
            return $endorsement->endorser?->profile?->full_name
                ?: ($endorsement->endorser_name ?: 'Alumni');
        })->values(),
    ];
}));

function openAddSkillModal() {
    document.getElementById('addSkillModal').classList.remove('hidden');
}

function closeAddSkillModal() {
    document.getElementById('addSkillModal').classList.add('hidden');
    document.getElementById('addSkillForm').reset();
}

function openEditSkillModal() {
    document.getElementById('editSkillModal').classList.remove('hidden');
}

function closeEditSkillModal() {
    document.getElementById('editSkillModal').classList.add('hidden');
    document.getElementById('editSkillForm').reset();
}

function addSkill(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);

    fetch('/skills', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddSkillModal();
            location.reload();
        } else {
            alert('Error adding skill');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding skill');
    });
}

function editSkill(id, name, level) {
    document.getElementById('editSkillId').value = id;
    document.getElementById('editSkillName').value = name;
    document.getElementById('editSkillLevel').value = level;
    openEditSkillModal();
}

function updateSkill(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const skillId = formData.get('skill_id');
    formData.delete('skill_id');

    const data = Object.fromEntries(formData);

    fetch(`/skills/${skillId}`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditSkillModal();
            location.reload();
        } else {
            alert('Error updating skill');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating skill');
    });
}

function deleteSkill(id) {
    if (!confirm('Are you sure you want to remove this skill?')) {
        return;
    }

    fetch(`/skills/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error removing skill');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error removing skill');
    });
}

function showEndorsers(skillId) {
    const modal = document.getElementById('endorsersModal');
    const body = document.getElementById('endorsersModalBody');
    const endorsers = skillEndorsers[skillId] || [];

    body.innerHTML = endorsers.length
        ? endorsers.map(name => `<div class="rounded-lg border border-[#e2e8f0] px-3 py-2">${escapeHtml(name)}</div>`).join('')
        : '<p class="text-gray-600">No endorsements yet.</p>';

    modal.classList.remove('hidden');
}

function closeEndorsersModal() {
    document.getElementById('endorsersModal').classList.add('hidden');
}

function toggleEndorsement(skillId) {
    const button = document.querySelector(`[data-endorse-button="${skillId}"]`);
    if (!button) {
        return;
    }

    const isEndorsed = button.getAttribute('data-endorsed') === 'true';
    button.disabled = true;

    fetch(`/skills/${skillId}/endorse`, {
        method: isEndorsed ? 'DELETE' : 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json().then(data => ({ ok: response.ok, data })))
    .then(({ ok, data }) => {
        if (!ok || !data.success) {
            alert(data.error || 'Could not update endorsement');
            return;
        }

        setEndorsementState(skillId, !isEndorsed, data.endorsements_count);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Could not update endorsement');
    })
    .finally(() => {
        button.disabled = false;
    });
}

function setEndorsementState(skillId, isEndorsed, count) {
    const button = document.querySelector(`[data-endorse-button="${skillId}"]`);
    const countLabel = document.querySelector(`[data-endorsement-count="${skillId}"]`);

    if (button) {
        button.setAttribute('data-endorsed', isEndorsed ? 'true' : 'false');
        button.textContent = isEndorsed ? 'Endorsed' : 'Endorse';
        button.className = isEndorsed
            ? 'shrink-0 rounded-lg px-3 py-2 text-sm font-semibold border border-[#1a2d5a] text-[#1a2d5a] bg-white hover:bg-[#f4f6f9]'
            : 'shrink-0 rounded-lg px-3 py-2 text-sm font-semibold bg-[#1a2d5a] text-white hover:bg-[#141d42]';
    }

    if (countLabel) {
        countLabel.textContent = `${count} endorsement${count === 1 ? '' : 's'}`;
    }
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value;
    return div.innerHTML;
}
</script>
@endsection
