@extends('layouts.app')

@section('title', 'Alumni-Profile')

@section('content')

<div class="max-w-7xl mx-auto mt-10 px-6">

    <div class="bg-white rounded-2xl shadow-lg p-8">

        @if($profile)

        <!-- HEADER -->
        <div class="flex items-center mb-10">

            <img 
                src="{{ $profile->profile_photo ? asset('storage/'.$profile->profile_photo) : 'data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"80\" height=\"80\" viewBox=\"0 0 80 80\"><rect width=\"80\" height=\"80\" rx=\"40\" fill=\"%23dbeafe\"/><circle cx=\"40\" cy=\"30\" r=\"14\" fill=\"%2393c5fd\"/><path d=\"M18 68c4-14 16-22 22-22s18 8 22 22\" fill=\"%2393c5fd\"/></svg>' }}"
                class="w-20 h-20 rounded-full border-4 border-blue-500 object-cover shadow"
            >

            <div class="ml-5">
                <h2 class="text-3xl font-bold text-gray-800">
                    {{ $profile->full_name }}
                </h2>
                <p class="text-gray-500 text-lg">
                    Alumni Member
                </p>
            </div>

            <div class="ml-auto">
                <a href="/profile/edit"
                   class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 hover:scale-105 transition">
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- LEFT CARD -->
            <div class="bg-gradient-to-br from-gray-50 to-white border rounded-2xl p-6 shadow-md hover:shadow-lg transition">

                <h3 class="text-xl font-semibold text-blue-700 mb-5">
                    Profile Details
                </h3>

                <div class="space-y-4 text-base">

                    <p><span class="font-semibold text-gray-800">Mobile:</span> {{ $profile->mobile }}</p>

                    <p><span class="font-semibold text-gray-800">Location:</span> 
                        {{ $profile->city }}, {{ $profile->country }}
                    </p>

                    <p><span class="font-semibold text-gray-800">Degree:</span> 
                        {{ ucfirst($profile->degree) }}
                    </p>

                    <p><span class="font-semibold text-gray-800">Branch:</span> 
                        {{ $profile->branch }}
                    </p>

                    <p><span class="font-semibold text-gray-800">Passing Year:</span> 
                        {{ $profile->passing_year }}
                    </p>

                    <!-- SOCIAL -->
                    <div>
                        <span class="font-semibold text-gray-800">Social:</span>

                        <div class="mt-2 flex gap-3 flex-wrap">

                            @if($profile->linkedin)
                                <a href="{{ $profile->linkedin }}" target="_blank"
                                   class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200">
                                   LinkedIn
                                </a>
                            @endif

                            @if($profile->instagram)
                                <a href="{{ $profile->instagram }}" target="_blank"
                                   class="px-3 py-1 bg-pink-100 text-pink-600 rounded-full text-sm hover:bg-pink-200">
                                   Instagram
                                </a>
                            @endif

                            @if($profile->facebook)
                                <a href="{{ $profile->facebook }}" target="_blank"
                                   class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm hover:bg-blue-200">
                                   Facebook
                                </a>
                            @endif

                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT CARD -->
            <div class="bg-gradient-to-br from-gray-50 to-white border rounded-2xl p-6 shadow-md hover:shadow-lg transition">

                <h3 class="text-xl font-semibold text-blue-700 mb-5">
                    Professional Details
                </h3>

                @forelse($experiences as $exp)
                    <div class="mb-5 pb-4 border-b last:border-none">

                        <p class="text-lg font-semibold text-gray-800">
                            {{ $exp->role }}
                        </p>

                        <p class="text-blue-600 font-medium">
                            {{ $exp->organization }}
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $exp->from }} → {{ $exp->to ?? 'Present' }}
                        </p>

                        <p class="text-sm text-gray-600">
                            📍 {{ $exp->location }}
                        </p>

                    </div>
                @empty
                    <p class="text-gray-400 text-base italic">
                        No experience added yet
                    </p>
                @endforelse

            </div>

        </div>

        @else

        <!-- EMPTY STATE -->
        <div class="text-center py-20">

            <p class="text-gray-600 text-lg mb-6">
                You have not completed your profile yet.
            </p>

            <a href="/profile/create"
               class="px-6 py-3 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                Complete Profile
            </a>

        </div>

        @endif

    </div>

</div>

@endsection