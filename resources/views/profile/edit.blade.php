@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto mt-8">

    <div class="bg-white shadow rounded-lg p-8">

        <h2 class="text-2xl font-semibold mb-6">Edit Profile</h2>

        @if(!$profile)
            <div class="mb-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                <p>You haven't created a profile yet. Please create your profile first.</p>
                <a href="/profile/create" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Profile</a>
            </div>
        @endif

        <form id="profileForm" method="POST" action="/profile/update" enctype="multipart/form-data">
            @csrf

            <!-- 🔴 GLOBAL ERRORS -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 🖼 PROFILE IMAGE -->
            <div class="mb-6 text-center">

                <label class="block text-sm font-semibold text-gray-600 mb-2">
                    Profile Picture
                </label>

@if($profile && $profile->profile_photo)
                    <img 
                        src="{{ asset('storage/'.$profile->profile_photo) }}" 
                        class="w-24 h-24 rounded-full mx-auto mb-3 object-cover border"
                    >
                @endif

                <input type="file" name="profile_photo" class="text-sm">

            </div>

            <!-- 🔒 LOCKED DETAILS -->
            @if($profile)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <div>
                    <label class="label">Full Name</label>
                    <input value="{{ $profile->full_name }}" class="input bg-gray-100" disabled>
                </div>

                <div>
                    <label class="label">Mobile Number</label>
                    <input value="{{ $profile->mobile }}" class="input bg-gray-100" disabled>
                </div>

                <div>
                    <label class="label">Email</label>
                    <input value="{{ auth()->user()->email }}" class="input bg-gray-100" disabled>
                </div>

                <div>
                    <label class="label">Degree</label>
                    <input value="{{ $profile->degree }}" class="input bg-gray-100" disabled>
                </div>

                <div>
                    <label class="label">Branch</label>
                    <input value="{{ $profile->branch }}" class="input bg-gray-100" disabled>
                </div>

                <div>
                    <label class="label">Passing Year</label>
                    <input value="{{ $profile->passing_year }}" class="input bg-gray-100" disabled>
                </div>

            </div>
            @endif

            <!-- ✏️ EDITABLE -->
            @if($profile)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="label">City</label>
                    <input name="city" value="{{ old('city', $profile->city ?? '') }}" class="input">
                </div>

                <div>
                    <label class="label">Country</label>
                    <input name="country" value="{{ old('country', $profile->country ?? '') }}" class="input">
                </div>

                <div>
                    <label class="label">LinkedIn URL</label>
                    <input name="linkedin" value="{{ old('linkedin', $profile->linkedin ?? '') }}" class="input">
                </div>

                <div>
                    <label class="label">Instagram URL</label>
                    <input name="instagram" value="{{ old('instagram', $profile->instagram ?? '') }}" class="input">
                </div>

                <div>
                    <label class="label">Facebook URL</label>
                    <input name="facebook" value="{{ old('facebook', $profile->facebook ?? '') }}" class="input">
                </div>

                <div>
                    <label class="label">Twitter / X URL</label>
                    <input name="twitter" value="{{ old('twitter', $profile->twitter ?? '') }}" class="input">
                </div>

            </div>
            @endif

            <!-- 💼 EXPERIENCE -->
            <div class="mt-10">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Professional Experience
                </h3>

                <div id="experienceContainer">

                    @foreach($experiences as $exp)
                    <div class="bg-gray-50 border rounded p-5 mb-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="label">Organization</label>
                                <input name="organization[]" value="{{ $exp->organization }}" class="input">
                            </div>

                            <div>
                                <label class="label">Role</label>
                                <input name="role[]" value="{{ $exp->role }}" class="input">
                            </div>

                            <div>
                                <label class="label">Industry</label>
                                <input name="industry[]" value="{{ $exp->industry }}" class="input">
                            </div>

                            <div>
                                <label class="label">Location</label>
                                <input name="location_exp[]" value="{{ $exp->location }}" class="input">
                            </div>

                            <div>
                                <label class="label">From</label>
                                <input type="date" name="from[]" value="{{ $exp->from }}" class="input">
                            </div>

                            <div>
                                <label class="label">To</label>
                                <input type="date" name="to[]" value="{{ $exp->to }}" class="input">
                            </div>

                        </div>

                        <button type="button" onclick="this.parentElement.remove()" 
                            class="text-red-500 text-sm mt-3 hover:underline">
                            Remove
                        </button>

                    </div>
                    @endforeach

                </div>

                <button type="button" onclick="addExperience()" 
                    class="text-blue-600 text-sm font-medium mt-2 hover:underline">
                    + Add Experience
                </button>
            </div>

            <!-- SUBMIT -->
            <div class="mt-8">
                <button class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Update Profile
                </button>
            </div>
        </form>

    </div>

</div>

<!-- 🎨 STYLE -->
<style>
.input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
.label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #555;
    margin-bottom: 4px;
}
</style>

<!-- 🎯 JAVASCRIPT -->
<script>
function addExperience() {
    const container = document.getElementById('experienceContainer');
    
    const newExperience = document.createElement('div');
    newExperience.className = 'bg-gray-50 border rounded p-5 mb-4';
    newExperience.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="label">Organization</label>
                <input name="organization[]" class="input">
            </div>
            <div>
                <label class="label">Role</label>
                <input name="role[]" class="input">
            </div>
            <div>
                <label class="label">Industry</label>
                <input name="industry[]" class="input">
            </div>
            <div>
                <label class="label">Location</label>
                <input name="location_exp[]" class="input">
            </div>
            <div>
                <label class="label">From</label>
                <input type="date" name="from[]" class="input">
            </div>
            <div>
                <label class="label">To</label>
                <input type="date" name="to[]" class="input">
            </div>
        </div>
        <button type="button" onclick="this.parentElement.remove()" 
            class="text-red-500 text-sm mt-3 hover:underline">
            Remove
        </button>
    `;
    
    container.appendChild(newExperience);
}
</script>

@endsection