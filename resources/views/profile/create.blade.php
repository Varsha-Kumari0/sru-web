@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto mt-8">
    <div class="bg-white shadow rounded-lg p-8">

        <h2 class="text-2xl font-semibold mb-6">Create Profile</h2>

        <form method="POST" action="/profile/store" enctype="multipart/form-data">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- FULL NAME -->
                <div>
                    <input name="full_name" value="{{ old('full_name') }}" placeholder="Full Name"
                        class="input @error('full_name') border-red-500 @enderror">
                    @error('full_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- MOBILE -->
                <div>
                    <input name="mobile" value="{{ old('mobile') }}" placeholder="Mobile"
                        class="input @error('mobile') border-red-500 @enderror">
                    @error('mobile')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CITY -->
                <div>
                    <input name="city" value="{{ old('city') }}" placeholder="City"
                        class="input @error('city') border-red-500 @enderror">
                    @error('city')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- COUNTRY -->
                <div>
                    <input name="country" value="{{ old('country') }}" placeholder="Country"
                        class="input @error('country') border-red-500 @enderror">
                    @error('country')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- DEGREE -->
                <div>
                    <input name="degree" value="{{ old('degree') }}" placeholder="Degree"
                        class="input @error('degree') border-red-500 @enderror">
                    @error('degree')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- BRANCH -->
                <div>
                    <input name="branch" value="{{ old('branch') }}" placeholder="Branch"
                        class="input @error('branch') border-red-500 @enderror">
                    @error('branch')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PASSING YEAR -->
                <div>
                    <input name="passing_year" value="{{ old('passing_year') }}" placeholder="Passing Year"
                        class="input @error('passing_year') border-red-500 @enderror">
                    @error('passing_year')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- LinkedIn -->
                <div>
                    <input name="linkedin" value="{{ old('linkedin') }}" 
                        placeholder="LinkedIn URL"
                        class="input @error('linkedin') border-red-500 @enderror">
                    @error('linkedin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Instagram -->
                <div>
                    <input name="instagram" value="{{ old('instagram') }}" 
                        placeholder="Instagram URL"
                        class="input @error('instagram') border-red-500 @enderror">
                    @error('instagram')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Facebook -->
                <div>
                    <input name="facebook" value="{{ old('facebook') }}" 
                        placeholder="Facebook URL"
                        class="input @error('facebook') border-red-500 @enderror">
                    @error('facebook')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Twitter -->
                <div>
                    <input name="twitter" value="{{ old('twitter') }}" 
                        placeholder="Twitter / X URL"
                        class="input @error('twitter') border-red-500 @enderror">
                    @error('twitter')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <button class="mt-6 px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Create Profile
            </button>

        </form>
    </div>
</div>

<!-- ✅ LIVE VALIDATION SCRIPT -->
<script>
function validateField(input, regex, message) {
    let existingError = input.parentElement.querySelector('.live-error');

    if (existingError) {
        existingError.remove();
    }

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
        linkedin: {
            regex: /^https?:\/\/(www\.)?linkedin\.com/,
            message: "Enter valid LinkedIn URL"
        },
        instagram: {
            regex: /^https?:\/\/(www\.)?instagram\.com/,
            message: "Enter valid Instagram URL"
        },
        facebook: {
            regex: /^https?:\/\/(www\.)?facebook\.com/,
            message: "Enter valid Facebook URL"
        },
        twitter: {
            regex: /^https?:\/\/(www\.)?(twitter\.com|x\.com)/,
            message: "Enter valid Twitter/X URL"
        }
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