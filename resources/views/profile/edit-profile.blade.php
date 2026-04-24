@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto mt-8">

    <div class="bg-white shadow rounded-lg p-8">

        <h2 class="text-2xl font-semibold mb-6">Edit Profile</h2>

        <form method="POST" action="/profile/update" enctype="multipart/form-data">
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

            <!-- 🔒 LOCKED DETAILS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <input value="{{ $profile->full_name }}" class="input bg-gray-100" disabled>
                <input value="{{ $profile->mobile }}" class="input bg-gray-100" disabled>
                <input value="{{ auth()->user()->email }}" class="input bg-gray-100" disabled>
                <input value="{{ $profile->degree }}" class="input bg-gray-100" disabled>
                <input value="{{ $profile->branch }}" class="input bg-gray-100" disabled>
                <input value="{{ $profile->passing_year }}" class="input bg-gray-100" disabled>

            </div>

            <!-- ✏️ EDITABLE DETAILS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- CITY -->
                <div>
                    <input name="city" value="{{ old('city', $profile->city) }}" 
                        class="input @error('city') border-red-500 @enderror"
                        placeholder="City">
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- COUNTRY -->
                <div>
                    <input name="country" value="{{ old('country', $profile->country) }}" 
                        class="input @error('country') border-red-500 @enderror"
                        placeholder="Country">
                    @error('country')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- LINKEDIN -->
                <div>
                    <input name="linkedin" 
                        value="{{ old('linkedin', $profile->linkedin) }}" 
                        class="input @error('linkedin') border-red-500 @enderror"
                        placeholder="LinkedIn URL">
                    @error('linkedin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- INSTAGRAM -->
                <div>
                    <input name="instagram" 
                        value="{{ old('instagram', $profile->instagram) }}" 
                        class="input @error('instagram') border-red-500 @enderror"
                        placeholder="Instagram URL">
                    @error('instagram')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- FACEBOOK -->
                <div>
                    <input name="facebook" 
                        value="{{ old('facebook', $profile->facebook) }}" 
                        class="input @error('facebook') border-red-500 @enderror"
                        placeholder="Facebook URL">
                    @error('facebook')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TWITTER -->
                <div>
                    <input name="twitter" 
                        value="{{ old('twitter', $profile->twitter) }}" 
                        class="input @error('twitter') border-red-500 @enderror"
                        placeholder="Twitter / X URL">
                    @error('twitter')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- EXPERIENCE -->
            <div class="mt-10">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">
                    Professional Experience
                </h3>

                <div id="experienceContainer">

                    @foreach($experiences as $exp)
                    <div class="bg-gray-50 border rounded p-5 mb-4">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input name="organization[]" value="{{ $exp->organization }}" class="input" placeholder="Organization">
                            <input name="role[]" value="{{ $exp->role }}" class="input" placeholder="Role / Designation">
                            <input name="industry[]" value="{{ $exp->industry }}" class="input" placeholder="Industry">
                            <input name="location_exp[]" value="{{ $exp->location }}" class="input" placeholder="Location">
                            <input type="date" name="from[]" value="{{ $exp->from }}" class="input">
                            <input type="date" name="to[]" value="{{ $exp->to }}" class="input">
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

<!-- STYLES -->
<style>
.input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}
</style>

<!-- EXPERIENCE JS -->
<script>
function addExperience() {
    let container = document.getElementById('experienceContainer');

    let div = document.createElement('div');
    div.classList.add('bg-gray-50','border','rounded','p-5','mb-4');

    div.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input name="organization[]" class="input" placeholder="Organization">
            <input name="role[]" class="input" placeholder="Role / Designation">
            <input name="industry[]" class="input" placeholder="Industry">
            <input name="location_exp[]" class="input" placeholder="Location">
            <input type="date" name="from[]" class="input">
            <input type="date" name="to[]" class="input">
        </div>

        <button type="button" onclick="this.parentElement.remove()" 
            class="text-red-500 text-sm mt-3 hover:underline">
            Remove
        </button>
    `;

    container.prepend(div);
}
</script>

<!-- 🔥 LIVE VALIDATION -->
<script>
function validateField(input, regex, message) {
    let existingError = input.parentElement.querySelector('.live-error');

    if (existingError) existingError.remove();

    input.classList.remove('border-red-500', 'border-green-500');

    let value = input.value.trim();

    if (value === '') return;

    if (!regex.test(value)) {
        input.classList.add('border-red-500');

        let error = document.createElement('p');
        error.className = "text-red-500 text-xs mt-1 live-error";
        error.innerText = message;

        input.parentElement.appendChild(error);
    } else {
        input.classList.add('border-green-500');
    }
}

document.addEventListener('DOMContentLoaded', function () {

    const rules = {
        linkedin: { regex: /^https?:\/\/(www\.)?linkedin\.com/, message: "Enter valid LinkedIn URL" },
        instagram: { regex: /^https?:\/\/(www\.)?instagram\.com/, message: "Enter valid Instagram URL" },
        facebook: { regex: /^https?:\/\/(www\.)?facebook\.com/, message: "Enter valid Facebook URL" },
        twitter: { regex: /^https?:\/\/(www\.)?(twitter\.com|x\.com)/, message: "Enter valid Twitter/X URL" }
    };

    Object.keys(rules).forEach(name => {
        document.querySelectorAll(`input[name="${name}"]`).forEach(input => {
            input.addEventListener('input', () => {
                validateField(input, rules[name].regex, rules[name].message);
            });
        });
    });

});
</script>

@endsection