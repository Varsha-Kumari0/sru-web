@extends('layouts.app')

@section('title', 'Alumni Profile')

@section('content')

<div class="min-h-screen bg-gray-50">
    @if($profile)
        <!-- COVER PHOTO -->
        <div class="h-48 md:h-64 bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1200 600%22><circle cx=%22100%22 cy=%22100%22 r=%2280%22 fill=%22white%22 opacity=%220.1%22/><circle cx=%221100%22 cy=%22500%22 r=%22150%22 fill=%22white%22 opacity=%220.1%22/></svg>');"></div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <!-- PROFILE HEADER SECTION -->
            <div class="relative -mt-24 mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
                    <div class="flex flex-col md:flex-row gap-6 items-start md:items-end">
                        <!-- Profile Image -->
                        <div class="flex-shrink-0">
                            <img 
                                src="{{ $profile->profile_photo ? asset('storage/'.$profile->profile_photo) : 'data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22150%22 height=%22150%22 viewBox=%220 0 150 150%22><rect width=%22150%22 height=%22150%22 rx=%2275%22 fill=%22%23e0e7ff%22/><circle cx=%2275%22 cy=%2250%22 r=%2225%22 fill=%22%233b82f6%22/><path d=%22M30 120c5-20 25-35 45-35s40 15 45 35%22 fill=%22%233b82f6%22/></svg>' }}"
                                class="w-36 h-36 md:w-40 md:h-40 rounded-2xl border-4 border-white shadow-lg object-cover"
                            >
                        </div>

                        <!-- Profile Info -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 flex items-center gap-2">
                                        {{ $profile->full_name }}
                                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </h1>
                                    <p class="text-xl text-gray-600 mt-1">
                                        {{ $profile->company ?? 'Professional' }} 
                                        @if($profile->degree)
                                            • {{ $profile->degree }} - {{ $profile->branch }}
                                        @endif
                                    </p>
                                    <p class="text-gray-500 mt-2">
                                        📍 {{ $profile->city }}, {{ $profile->country }}
                                        @if($profile->passing_year)
                                            • Class of {{ $profile->passing_year }}
                                        @endif
                                    </p>
                                </div>

                                <!-- Status Badge -->
                                <div class="flex items-center gap-2">
                                    <span class="inline-block px-4 py-2 bg-gradient-to-r from-blue-100 to-cyan-100 text-blue-700 font-semibold rounded-full text-sm">
                                        ✨ Complete Profile
                                    </span>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="flex flex-wrap gap-3 mt-6">
                                <a href="/profile/edit" class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-md font-medium">
                                    ✏️ Edit Profile
                                </a>
                                <button class="inline-flex items-center gap-2 px-6 py-2 bg-white border-2 border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium">
                                    💬 Message
                                </button>
                                <button class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                    ⋯
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STATS SECTION -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition">
                    <div class="text-3xl font-bold text-blue-600">{{ $connectionCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600 mt-1">Connections</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition">
                    <div class="text-3xl font-bold text-green-600">{{ $profileViewsCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600 mt-1">Profile Views</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition">
                    <div class="text-3xl font-bold text-purple-600">{{ $skillsCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600 mt-1">Skills</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 text-center hover:shadow-md transition">
                    <div class="text-3xl font-bold text-yellow-600">{{ $achievementsCount ?? 0 }}</div>
                    <div class="text-sm text-gray-600 mt-1">Achievements</div>
                </div>
            </div>

            <!-- MAIN CONTENT GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- LEFT COLUMN (2/3) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- ABOUT SECTION -->
                    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-900">📋 About</h2>
                            <a href="{{ route('profile.edit-bio') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                ✏️ Edit
                            </a>
                        </div>
                        <p class="text-gray-700 leading-relaxed">
                            @if($profile->description)
                                {{ $profile->description }}
                            @else
                                <span class="text-gray-500 italic">No bio added yet. Click edit to add one.</span>
                            @endif
                        </p>
                    </div>

                    <!-- EXPERIENCE SECTION -->
                    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">💼 Experience</h2>
                        @forelse($experiences as $exp)
                            <div class="relative pb-8 last:pb-0">
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold shadow-md">
                                            {{ substr($exp->organization, 0, 1) }}
                                        </div>
                                        @if(!$loop->last)
                                            <div class="w-0.5 h-16 bg-gray-200 my-2"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $exp->role }}</h3>
                                        <p class="text-blue-600 font-medium">{{ $exp->organization }}</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            📅 {{ $exp->from }} → {{ $exp->to ?? 'Present' }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-2">
                                            📍 {{ $exp->location }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">No experience added yet. <a href="/profile/edit" class="text-blue-600 hover:underline">Add your experience</a></p>
                        @endforelse
                    </div>

                    <!-- SKILLS SECTION -->
                    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold text-gray-900">🎯 Skills</h2>
                            <a href="{{ route('skills.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                ⚙️ Manage
                            </a>
                        </div>
                        @if($skills && count($skills) > 0)
                            <div class="flex flex-wrap gap-3">
                                @foreach($skills as $skill)
                                    <div class="relative group">
                                        <div class="px-4 py-2 bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-lg hover:shadow-md transition cursor-pointer">
                                            <span class="text-gray-800 font-medium">{{ $skill->name }}</span>
                                            @if($skill->endorsements > 0)
                                                <span class="ml-2 inline-block px-2 py-1 bg-blue-500 text-white text-xs rounded-full font-bold">{{ $skill->endorsements }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">No skills added yet. <a href="/profile/edit" class="text-blue-600 hover:underline">Add your skills</a></p>
                        @endif
                    </div>

                    <!-- ACHIEVEMENTS SECTION -->
                    @if($achievements && count($achievements) > 0)
                        <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">🏆 Achievements</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($achievements as $achievement)
                                    <div class="flex gap-4 p-4 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg border border-yellow-200 hover:shadow-md transition">
                                        <div class="text-4xl">{{ $achievement->badge_icon ?? '⭐' }}</div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $achievement->title }}</h4>
                                            @if($achievement->description)
                                                <p class="text-sm text-gray-600 mt-1">{{ $achievement->description }}</p>
                                            @endif
                                            @if($achievement->earned_at)
                                                <p class="text-xs text-gray-500 mt-2">{{ $achievement->earned_at->format('M Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- RIGHT COLUMN (1/3) -->
                <div class="space-y-6">

                    <!-- CONTACT INFO -->
                    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">📞 Contact</h2>
                        <div class="space-y-3">
                            @if($profile->mobile)
                                <a href="tel:{{ $profile->mobile }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                                    <span class="text-xl">📱</span>
                                    <span class="text-gray-700">{{ $profile->mobile }}</span>
                                </a>
                            @endif
                            @if($profile->user && $profile->user->email)
                                <a href="mailto:{{ $profile->user->email }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition">
                                    <span class="text-xl">📧</span>
                                    <span class="text-gray-700 truncate">{{ $profile->user->email }}</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- SOCIAL LINKS -->
                    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">🔗 Social</h2>
                        <div class="flex gap-3 flex-wrap">
                            @if($profile->linkedin)
                                <a href="{{ $profile->linkedin }}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition" title="LinkedIn">
                                    in
                                </a>
                            @endif
                            @if($profile->facebook)
                                <a href="{{ $profile->facebook }}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition" title="Facebook">
                                    f
                                </a>
                            @endif
                            @if($profile->twitter)
                                <a href="{{ $profile->twitter }}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition" title="X">
                                    𝕏
                                </a>
                            @endif
                            @if($profile->instagram)
                                <a href="{{ $profile->instagram }}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-pink-100 text-pink-600 rounded-full hover:bg-pink-200 transition" title="Instagram">
                                    📷
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- EDUCATION -->
                    <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">🎓 Education</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $profile->degree ?? '-' }}</p>
                                <p class="text-sm text-gray-600">{{ $profile->branch ?? '-' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $profile->passing_year ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    @else
        <!-- EMPTY STATE -->
        <div class="min-h-screen flex items-center justify-center px-4">
            <div class="text-center">
                <div class="mb-6">
                    <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Profile Not Found</h2>
                <p class="text-gray-600 mb-6">Let's create your profile and join the alumni community!</p>
                <a href="/profile/create" class="inline-block px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Create Your Profile
                </a>
            </div>
        </div>
    @endif
</div>

@endsection
