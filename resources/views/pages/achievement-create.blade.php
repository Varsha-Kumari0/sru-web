@extends('layouts.app')

@section('title', 'Add Achievement')

@section('content')
<div class="-m-6 min-h-screen" style="background:#f0f0ee;">
    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">

            <div class="p-8" style="background: linear-gradient(135deg, #1a2d4a 0%, #1e4a52 45%, #2a9d8f 100%);">
                <p class="text-xs font-bold uppercase tracking-[0.18em]" style="color:#c9a84c;">Your Profile</p>
                <h1 class="mt-3 text-3xl font-bold text-white">Add an Achievement</h1>
                <p class="mt-2 text-white/70 text-sm">Share a milestone, award, or career highlight with the alumni network.</p>
            </div>

            <div class="p-8">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('achievements.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color:#1a2d4a;">Achievement Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                            placeholder="e.g. Promoted to Senior Engineer"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2a9d8f]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color:#1a2d4a;">Category *</label>
                        <select name="category" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2a9d8f]">
                            <option value="">Select a category</option>
                            <option value="career" {{ old('category') == 'career' ? 'selected' : '' }}>Career</option>
                            <option value="leadership" {{ old('category') == 'leadership' ? 'selected' : '' }}>Leadership</option>
                            <option value="academic" {{ old('category') == 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="entrepreneurship" {{ old('category') == 'entrepreneurship' ? 'selected' : '' }}>Entrepreneurship</option>
                            <option value="community" {{ old('category') == 'community' ? 'selected' : '' }}>Community</option>
                            <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color:#1a2d4a;">Description</label>
                        <textarea name="description" rows="3"
                            placeholder="Brief description of this achievement..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2a9d8f] resize-none">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color:#1a2d4a;">Date Achieved</label>
                        <input type="month" name="earned_at" value="{{ old('earned_at') }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2a9d8f]">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1" style="color:#1a2d4a;">Reference Link <span class="font-normal text-gray-400">(optional)</span></label>
                        <input type="url" name="proof_url" value="{{ old('proof_url') }}"
                            placeholder="https://linkedin.com/..."
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#2a9d8f]">
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="px-6 py-3 rounded-xl text-sm font-bold text-white transition-opacity hover:opacity-90"
                            style="background: linear-gradient(135deg, #1a2d4a, #2a9d8f);">
                            Save Achievement
                        </button>
                        <a href="{{ route('profile') }}"
                            class="px-6 py-3 rounded-xl text-sm font-semibold border border-gray-200 text-gray-600 hover:bg-gray-50 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection