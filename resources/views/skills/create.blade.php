@extends('layouts.app')

@section('title', 'Add Skill')

@section('content')
<div class="min-h-screen bg-[#f4f6f9] py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-[#e2e8f0] p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-[#1a2d5a]">Add Skill</h1>
                <p class="text-sm text-gray-600 mt-1">Create a new skill entry for your profile.</p>
            </div>

            <form action="{{ route('skills.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-[#1a2d5a]">Skill Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="mt-1 block w-full rounded-md border border-[#e2e8f0] px-3 py-2 focus:border-[#1a2d5a] focus:ring-[#1a2d5a]"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="level" class="block text-sm font-medium text-[#1a2d5a]">Proficiency Level</label>
                    <select
                        id="level"
                        name="level"
                        class="mt-1 block w-full rounded-md border border-[#e2e8f0] px-3 py-2 focus:border-[#1a2d5a] focus:ring-[#1a2d5a]"
                    >
                        @foreach (['beginner', 'intermediate', 'advanced', 'expert'] as $level)
                            <option value="{{ $level }}" @selected(old('level', 'beginner') === $level)>
                                {{ ucfirst($level) }}
                            </option>
                        @endforeach
                    </select>
                    @error('level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('skills.index') }}" class="px-4 py-2 rounded-md border border-[#e2e8f0] text-[#1a2d5a] hover:bg-[#f4f6f9]">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 rounded-md bg-[#1a2d5a] text-white hover:bg-[#141d42]">
                        Save Skill
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection