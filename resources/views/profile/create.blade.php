@extends('layouts.app')

@section('title', 'Create Profile')

@section('content')
<div class="max-w-5xl mx-auto mt-10 px-6">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Create Profile</h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/profile/store" enctype="multipart/form-data" id="profileForm">
        @csrf

        <div id="step1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter your name" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Father's Name <span class="text-red-500">*</span></label>
                    <input type="text" name="father_name" value="{{ old('father_name') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter father's name" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="mobile" value="{{ old('mobile') }}"
                        inputmode="numeric" pattern="[0-9]{10,15}" maxlength="15"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter mobile number" required>
                    @error('mobile')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter city" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Country <span class="text-red-500">*</span></label>
                    <input type="text" name="country" value="{{ old('country') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter country" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Degree <span class="text-red-500">*</span></label>
                    <select name="degree" id="degree"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Degree</option>
                        <option value="B.Tech" @selected(old('degree') === 'B.Tech')>B.Tech</option>
                        <option value="Business" @selected(old('degree') === 'Business')>Business</option>
                        <option value="Agriculture" @selected(old('degree') === 'Agriculture')>Agriculture</option>
                        <option value="B.Sc" @selected(old('degree') === 'B.Sc')>B.Sc</option>
                        <option value="B.Com" @selected(old('degree') === 'B.Com')>B.Com</option>
                        <option value="BCA" @selected(old('degree') === 'BCA')>BCA</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Specialization / Branch <span class="text-red-500">*</span></label>
                    <select name="branch" id="branch"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Specialization</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Profile Photo</label>
                <input type="file" name="profile_photo"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Passing Year <span class="text-red-500">*</span></label>
                <select name="passing_year"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    @for($year = date('Y'); $year >= 2000; $year--)
                        <option value="{{ $year }}" @selected((string) old('passing_year', date('Y')) === (string) $year)>{{ $year }}</option>
                    @endfor
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Instagram <span class="text-red-500">*</span></label>
                    <input type="url" name="instagram" value="{{ old('instagram') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="https://instagram.com/username"
                        inputmode="url" required>
                    @error('instagram')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">LinkedIn <span class="text-red-500">*</span></label>
                    <input type="url" name="linkedin" value="{{ old('linkedin') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="https://linkedin.com/in/username"
                        inputmode="url" required>
                    @error('linkedin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook <span class="text-red-500">*</span></label>
                    <input type="url" name="facebook" value="{{ old('facebook') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="https://facebook.com/username"
                        inputmode="url" required>
                    @error('facebook')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">X (Twitter) <span class="text-red-500">*</span></label>
                    <input type="url" name="twitter" value="{{ old('twitter') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="https://x.com/username"
                        inputmode="url" required>
                    @error('twitter')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="button" onclick="nextStep()"
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                Next -> Professional Details
            </button>
        </div>

        <div id="step2" style="display:none;">
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Professional Experience</h3>

            <div id="experienceContainer"></div>

            <button type="button" onclick="addExperience()" class="text-blue-600 mb-6 font-semibold">
                + Add Experience
            </button>

            <div class="flex gap-4">
                <button type="button" onclick="prevStep()"
                    class="bg-gray-400 text-white px-6 py-2 rounded-md hover:bg-gray-500 transition">
                    <- Back
                </button>

                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                    Save Profile
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const specializationData = {
        'B.Tech': [
            'CSE (AI & ML)',
            'CSE (Cybersecurity)',
            'CSE (Data Science)',
            'ECE (VLSI)',
            'EEE (Renewable Energy)',
            'Mechanical (Smart Manufacturing)',
            'Civil (Robotics and Automation)'
        ],
        'Business': [
            'BBA (Marketing)',
            'BBA (Finance)',
            'BBA (Operations)',
            'BBA (International Business)',
            'BBA (Business Analytics)'
        ],
        'Agriculture': [
            'B.Sc (Hons) Agriculture'
        ],
        'B.Sc': [
            'B.Sc (Computer Science)',
            'B.Sc (Physics)',
            'B.Sc (Chemistry)',
            'B.Sc (Mathematics)',
            'B.Sc (Forensic Science)'
        ],
        'B.Com': [
            'B.Com (Computer Applications)'
        ],
        'BCA': [
            'BCA General',
            'BCA (Cloud Computing)'
        ]
    };

    const degreeSelect = document.getElementById('degree');
    const branchSelect = document.getElementById('branch');
    const profileForm = document.getElementById('profileForm');
    const mobileInput = document.querySelector('input[name="mobile"]');

    function validateMobileField() {
        const value = mobileInput.value.trim();

        mobileInput.setCustomValidity('');

        if (!value) {
            mobileInput.setCustomValidity('Mobile number is required.');
            return false;
        }

        if (!/^\d{10,15}$/.test(value)) {
            mobileInput.setCustomValidity('Mobile number must contain only digits and be 10 to 15 characters long.');
            return false;
        }

        return true;
    }

    function loadBranches(degree, selectedBranch = '') {
        branchSelect.innerHTML = '<option value="">Select Specialization</option>';

        if (!specializationData[degree]) {
            return;
        }

        specializationData[degree].forEach(function (branch) {
            const option = document.createElement('option');
            option.value = branch;
            option.textContent = branch;

            if (selectedBranch && selectedBranch === branch) {
                option.selected = true;
            }

            branchSelect.appendChild(option);
        });
    }

    degreeSelect.addEventListener('change', function () {
        loadBranches(this.value);
    });

    mobileInput.addEventListener('input', validateMobileField);
    mobileInput.addEventListener('blur', validateMobileField);

    profileForm.addEventListener('submit', function (event) {
        if (!validateStep1()) {
            event.preventDefault();
            return;
        }
    });

    function validateStep1() {
        const fullName = document.querySelector('input[name="full_name"]').value.trim();
        const fatherName = document.querySelector('input[name="father_name"]').value.trim();
        const mobile = document.querySelector('input[name="mobile"]').value.trim();
        const city = document.querySelector('input[name="city"]').value.trim();
        const country = document.querySelector('input[name="country"]').value.trim();
        const degree = document.getElementById('degree').value;
        const branch = document.getElementById('branch').value;
        const passingYear = document.querySelector('select[name="passing_year"]').value;

        const mobileValid = validateMobileField();

        if (!fullName || !fatherName || !mobile || !city || !country || !degree || !branch || !passingYear) {
            alert('Please fill all required fields including degree and specialization');
            return false;
        }

        if (!mobileValid) {
            mobileInput.reportValidity();
            mobileInput.focus();
            return false;
        }

        if (!validateSocialLinks()) {
            return false;
        }

        return true;
    }

    function validateSocialLinks() {
        const socialRules = [
            { name: 'instagram', domains: ['instagram.com'], label: 'Instagram' },
            { name: 'facebook', domains: ['facebook.com'], label: 'Facebook' },
            { name: 'twitter', domains: ['x.com', 'twitter.com'], label: 'X (Twitter)' },
            { name: 'linkedin', domains: ['linkedin.com'], label: 'LinkedIn' }
        ];

        for (const rule of socialRules) {
            const field = document.querySelector(`input[name="${rule.name}"]`);
            const value = field.value.trim();
            field.setCustomValidity('');

            if (!value) {
                field.setCustomValidity(`${rule.label} link is required.`);
                field.reportValidity();
                field.focus();
                return false;
            }

            let parsed;
            try {
                parsed = new URL(value);
            } catch (e) {
                field.setCustomValidity(`${rule.label} link must be a valid URL starting with http:// or https://`);
                field.reportValidity();
                field.focus();
                return false;
            }

            const host = parsed.hostname.replace(/^www\./i, '').toLowerCase();
            const isAllowed = rule.domains.some(domain => host === domain || host.endsWith('.' + domain));

            if (!isAllowed) {
                field.setCustomValidity(`${rule.label} link must be from ${rule.domains.join(' or ')}`);
                field.reportValidity();
                field.focus();
                return false;
            }
        }

        return true;
    }

    function nextStep() {
        if (!validateStep1()) {
            return;
        }

        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
        window.scrollTo(0, 0);
    }

    function prevStep() {
        document.getElementById('step1').style.display = 'block';
        document.getElementById('step2').style.display = 'none';
        window.scrollTo(0, 0);
    }

    function addExperience() {
        const container = document.getElementById('experienceContainer');
        const div = document.createElement('div');
        div.classList.add('border', 'border-gray-300', 'p-4', 'mb-4', 'rounded-md', 'bg-gray-50');

        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Organization</label>
                    <input type="text" name="organization[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Organization name" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                    <input type="text" name="role[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Your role" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Industry</label>
                    <input type="text" name="industry[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Industry" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                    <input type="text" name="location_exp[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Location" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">From Date</label>
                    <input type="date" name="from[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 font-semibold text-sm">Remove Experience</button>
        `;

        container.appendChild(div);
    }

    // Preserve old value on validation error reload.
    loadBranches('{{ old('degree') }}', '{{ old('branch') }}');
</script>
@endsection
