@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="max-w-6xl mx-auto mt-6">

    <div class="bg-white border rounded shadow-sm p-6">

        {{-- PROFILE HEADER --}}
        @if($profile)
        <div class="flex items-center mb-6">

            <!-- PROFILE IMAGE -->
            <img 
                src="{{ $profile->profile_image ? asset('storage/'.$profile->profile_image) : 'https://via.placeholder.com/60' }}"
                class="w-16 h-16 rounded-full mr-4 border object-cover"
            >

            <!-- NAME -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800 tracking-wide">
                    {{ strtoupper($profile->full_name) }}
                </h2>
                <p class="text-gray-500 text-sm">
                    Alumni
                </p>
            </div>

            <!-- EDIT BUTTON -->
            <div class="ml-auto">
                <a href="/profile/edit"
                   class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition">
                    Edit Profile
                </a>
            </div>
        </div>

        {{-- TWO COLUMN GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- LEFT: PROFILE DETAILS --}}
            <div class="border rounded p-5 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">
                    Profile Details
                </h3>

                <div class="space-y-2 text-sm text-gray-700">

                    <p><strong class="text-gray-800">Mobile:</strong> {{ $profile->mobile }}</p>

                    <p><strong class="text-gray-800">Location:</strong> 
                        {{ $profile->city }}, {{ $profile->country }}
                    </p>

                    <p><strong class="text-gray-800">Degree:</strong> 
                        {{ ucfirst($profile->degree) }}
                    </p>

                    <p><strong class="text-gray-800">Branch:</strong> 
                        {{ $profile->branch }}
                    </p>

                    <p><strong class="text-gray-800">Passing Year:</strong> 
                        {{ $profile->passing_year }}
                    </p>

                    <!-- SOCIAL -->
                    <p class="pt-2">
                        <strong class="text-gray-800">Social:</strong>

                        @if($profile->linkedin)
                            <a href="{{ $profile->linkedin }}" target="_blank" 
                               class="text-blue-600 ml-2 hover:underline">LinkedIn</a>
                        @endif

                        @if($profile->instagram)
                            <a href="{{ $profile->instagram }}" target="_blank" 
                               class="text-pink-500 ml-2 hover:underline">Instagram</a>
                        @endif

                        @if($profile->facebook)
                            <a href="{{ $profile->facebook }}" target="_blank" 
                               class="text-blue-500 ml-2 hover:underline">Facebook</a>
                        @endif
                    </p>

                </div>
            </div>

            {{-- RIGHT: EXPERIENCE --}}
            <div class="border rounded p-5 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 uppercase tracking-wide">
                    Professional Details
                </h3>

                @forelse($experiences as $exp)
                    <div class="mb-4 pb-3 border-b last:border-none">

                        <p class="font-medium text-gray-800">
                            {{ $exp->role }} at {{ $exp->organization }}
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $exp->from }} → {{ $exp->to ?? 'Present' }}
                        </p>

                        <p class="text-xs text-gray-600">
                            {{ $exp->location }}
                        </p>

                    </div>
                @empty
                    <p class="text-gray-400 text-sm">
                        No experience added
                    </p>
                @endforelse

            </div>

        </div>

        @else

        {{-- NO PROFILE CASE --}}
        <div class="text-center py-12">

            <p class="text-gray-600 mb-4">
                You have not completed your profile yet.
            </p>

            <a href="/profile/create"
               class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Complete Profile
            </a>

        </div>

        @endif

    </div>

</div>

@endsection