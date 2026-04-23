@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Welcome -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-semibold text-blue-700">
            You are logged in 👋
        </h2>
    </div>

    <!-- PROFILE -->
    @if($profile)

    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold text-blue-600 mb-4">Your Profile</h3>

        <p><strong>Name:</strong> {{ $profile->full_name }}</p>
        <p><strong>Mobile:</strong> {{ $profile->mobile }}</p>
        <p><strong>Location:</strong> {{ $profile->city }}, {{ $profile->country }}</p>
        <p><strong>Degree:</strong> {{ $profile->degree }}</p>
        <p><strong>Branch:</strong> {{ $profile->branch }}</p>
        <p><strong>Passing Year:</strong> {{ $profile->passing_year }}</p>

        @if($profile->company)
            <p><strong>Company:</strong> {{ $profile->company }}</p>
        @endif

        @if($profile->current_status)
            <p><strong>Status:</strong> {{ $profile->current_status }}</p>
        @endif

        <!-- Social -->
        <div class="mt-3 space-x-3">
            @if($profile->linkedin)
                <a href="{{ $profile->linkedin }}" target="_blank" class="text-blue-500">LinkedIn</a>
            @endif
            @if($profile->facebook)
                <a href="{{ $profile->facebook }}" target="_blank" class="text-blue-500">Facebook</a>
            @endif
            @if($profile->instagram)
                <a href="{{ $profile->instagram }}" target="_blank" class="text-blue-500">Instagram</a>
            @endif
        </div>
    </div>

    <!-- EXPERIENCE -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold text-blue-600 mb-4">Professional Experience</h3>

        @forelse($experiences as $exp)
            <div class="border-b pb-3 mb-3">
                <p><strong>{{ $exp->role }}</strong> at {{ $exp->organization }}</p>
                <p class="text-sm text-gray-500">
                    {{ $exp->from }} → {{ $exp->to ?? 'Present' }}
                </p>
                <p class="text-sm">{{ $exp->location }}</p>
            </div>
        @empty
            <p class="text-gray-500">No experience added.</p>
        @endforelse
    </div>

    @else

    <!-- NO PROFILE -->
    <div class="bg-white p-6 rounded-xl shadow text-center">
        <p class="mb-4">You haven’t completed your profile yet.</p>

        <a href="/profile/create"
           class="px-5 py-2 bg-blue-600 text-white rounded-lg">
            Complete Profile
        </a>
    </div>

    @endif

</div>

@endsection