@extends('layouts.app')

@section('title', 'Edit Bio')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900">Edit Bio</h1>
                    <a href="{{ route('profile') }}" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </div>
            </div>

            <form action="{{ route('profile.update-bio') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        About Me
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-vertical"
                        placeholder="Tell us about yourself, your professional background, interests, and goals..."
                        maxlength="1000"
                        required
                    >{{ old('description', $profile->description) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        <span id="char-count">{{ strlen($profile->description ?? '') }}</span>/1000 characters
                    </p>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    There were some errors with your input:
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul role="list" class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('profile') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-lg hover:bg-blue-600 transition-colors duration-200">
                        Save Bio
                    </button>
                </div>
            </form>
        </div>

        <!-- Tips Section -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Bio Writing Tips
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul role="list" class="list-disc pl-5 space-y-1">
                            <li>Highlight your key skills and experiences</li>
                            <li>Mention your career goals and interests</li>
                            <li>Keep it professional yet personable</li>
                            <li>Use action verbs to describe your achievements</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('description');
    const charCount = document.getElementById('char-count');

    function updateCharCount() {
        const count = textarea.value.length;
        charCount.textContent = count;

        if (count > 900) {
            charCount.className = 'text-red-600';
        } else if (count > 800) {
            charCount.className = 'text-yellow-600';
        } else {
            charCount.className = 'text-gray-500';
        }
    }

    textarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
});
</script>
@endsection