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
                    <label class="block text-sm font-semibold text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter first name" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter last name" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select gender</option>
                        <option value="male" @selected(old('gender') === 'male')>Male</option>
                        <option value="female" @selected(old('gender') === 'female')>Female</option>
                        <option value="other" @selected(old('gender') === 'other')>Other</option>
                        <option value="prefer_not_to_say" @selected(old('gender') === 'prefer_not_to_say')>Prefer not to say</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Contact Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', auth()->user()->email ?? '') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter contact email" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter your name" required>
                </div> -->

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pursuing Educational Level</label>
                    <select name="pursuing_educational_level" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Select level</option>
                        <option value="school" @selected(old('pursuing_educational_level') === 'school')>School</option>
                        <option value="ug" @selected(old('pursuing_educational_level') === 'ug')>UG</option>
                        <option value="pg" @selected(old('pursuing_educational_level') === 'pg')>PG</option>
                        <option value="other" @selected(old('pursuing_educational_level') === 'other')>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Highest Completed Educational Level</label>
                    <select name="highest_completed_educational_level" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Select level</option>
                        <option value="school" @selected(old('highest_completed_educational_level') === 'school')>School</option>
                        <option value="ug" @selected(old('highest_completed_educational_level') === 'ug')>UG</option>
                        <option value="pg" @selected(old('highest_completed_educational_level') === 'pg')>PG</option>
                        <option value="other" @selected(old('highest_completed_educational_level') === 'other')>Other</option>
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
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Current Status</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <label class="border border-gray-300 rounded-md p-4 cursor-pointer hover:border-blue-500 transition">
                    <input type="radio" name="current_status" value="studying" class="mr-2" @checked(old('current_status') === 'studying')>
                    <span class="font-semibold text-gray-800">I am currently studying</span>
                    <p class="text-sm text-gray-600 mt-1">Add your current educational details.</p>
                </label>

                <label class="border border-gray-300 rounded-md p-4 cursor-pointer hover:border-blue-500 transition">
                    <input type="radio" name="current_status" value="working" class="mr-2" @checked(old('current_status') === 'working')>
                    <span class="font-semibold text-gray-800">I am currently working</span>
                    <p class="text-sm text-gray-600 mt-1">Add your work experience details.</p>
                </label>
            </div>

            <div id="studySection" style="display:none;" class="mb-6 border border-gray-300 p-4 rounded-md bg-gray-50">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Educational Details</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Institution / College <span class="text-red-500">*</span></label>
                        <input type="text" name="study_institution" value="{{ old('study_institution') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Where are you currently studying?">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Current Degree <span class="text-red-500">*</span></label>
                        <input type="text" name="study_degree" value="{{ old('study_degree') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Degree you are pursuing">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Specialization / Branch <span class="text-red-500">*</span></label>
                        <input type="text" name="study_branch" value="{{ old('study_branch') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Current specialization">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">From Date <span class="text-red-500">*</span></label>
                        <input type="date" name="study_from" value="{{ old('study_from') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">To Date</label>
                        <input type="date" name="study_to" value="{{ old('study_to') !== 'Present' ? old('study_to') : '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" id="studyToDate">
                        <input type="hidden" id="studyToHidden" value="Present">
                    </div>

                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <input type="checkbox" id="studyPresentCheckbox" @checked(old('study_to') === 'Present')>
                            I am presently studying
                        </label>
                    </div>
                </div>
            </div>

            <div id="workSection" style="display:none;" class="mb-6">
                <h4 id="workSectionTitle" class="text-xl font-bold mb-2 text-gray-800">Professional Experience</h4>
                <p id="workSectionHelp" class="text-sm text-gray-600 mb-4"></p>

                <div id="experienceContainer"></div>

                <button type="button" onclick="addExperience()" class="text-blue-600 mb-6 font-semibold">
                    + Add Experience
                </button>
            </div>

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
            'CSE',
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
    const studySection = document.getElementById('studySection');
    const workSection = document.getElementById('workSection');
    const statusRadios = document.querySelectorAll('input[name="current_status"]');
    const studyToDateInput = document.getElementById('studyToDate');
    const studyPresentCheckbox = document.getElementById('studyPresentCheckbox');
    const studyToHiddenInput = document.getElementById('studyToHidden');
    const workSectionTitle = document.getElementById('workSectionTitle');
    const workSectionHelp = document.getElementById('workSectionHelp');

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

    statusRadios.forEach(function (radio) {
        radio.addEventListener('change', handleStatusChange);
    });

    studyPresentCheckbox.addEventListener('change', toggleStudyPresent);

    profileForm.addEventListener('submit', function (event) {
        if (!validateStep1() || !validateStep2()) {
            event.preventDefault();
            return;
        }
    });

    function validateStep1() {
        const firstName = document.querySelector('input[name="first_name"]').value.trim();
        const lastName = document.querySelector('input[name="last_name"]').value.trim();
        const gender = document.querySelector('select[name="gender"]').value;
        const contactEmail = document.querySelector('input[name="contact_email"]').value.trim();
        const fatherName = document.querySelector('input[name="father_name"]').value.trim();
        const mobile = document.querySelector('input[name="mobile"]').value.trim();
        const city = document.querySelector('input[name="city"]').value.trim();
        const country = document.querySelector('input[name="country"]').value.trim();
        const degree = document.getElementById('degree').value;
        const branch = document.getElementById('branch').value;
        const passingYear = document.querySelector('select[name="passing_year"]').value;

        const mobileValid = validateMobileField();

        if (!firstName || !lastName || !gender || !contactEmail || !fatherName || !mobile || !city || !country || !degree || !branch || !passingYear) {
            alert('Please fill all required fields in basic profile information and education details.');
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

    function handleStatusChange() {
        const selectedStatus = document.querySelector('input[name="current_status"]:checked')?.value;

        studySection.style.display = selectedStatus === 'studying' ? 'block' : 'none';
        workSection.style.display = selectedStatus ? 'block' : 'none';

        setSectionEnabled(studySection, selectedStatus === 'studying');
        setSectionEnabled(workSection, !!selectedStatus);

        if (selectedStatus === 'studying') {
            workSectionTitle.textContent = 'Previous Work Experience';
            workSectionHelp.textContent = 'Optional: add internships, jobs, or roles you held before your current studies.';
        } else if (selectedStatus === 'working') {
            workSectionTitle.textContent = 'Professional Experience';
            workSectionHelp.textContent = 'Add your current or previous work experience details.';
        }

        if (selectedStatus === 'studying') {
            toggleStudyPresent();
        }

        if (selectedStatus === 'working' && !document.querySelector('#experienceContainer .experience-item')) {
            addExperience();
        }
    }

    function setSectionEnabled(section, enabled) {
        const fields = section.querySelectorAll('input, select, textarea, button');
        fields.forEach(function (field) {
            if (field.type === 'radio') {
                return;
            }

            if (field.id === 'studyPresentCheckbox') {
                field.disabled = !enabled;
                return;
            }

            field.disabled = !enabled;
        });
    }

    function toggleStudyPresent() {
        if (studyPresentCheckbox.checked) {
            studyToDateInput.value = '';
            studyToDateInput.removeAttribute('name');
            studyToDateInput.disabled = true;
            studyToHiddenInput.setAttribute('name', 'study_to');
        } else {
            studyToDateInput.setAttribute('name', 'study_to');
            studyToDateInput.disabled = false;
            studyToHiddenInput.removeAttribute('name');
        }
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

    function validateStep2() {
        const selectedStatus = document.querySelector('input[name="current_status"]:checked')?.value;

        if (!selectedStatus) {
            alert('Please choose whether you are currently studying or working.');
            return false;
        }

        if (selectedStatus === 'studying') {
            const institution = document.querySelector('input[name="study_institution"]').value.trim();
            const degree = document.querySelector('input[name="study_degree"]').value.trim();
            const branch = document.querySelector('input[name="study_branch"]').value.trim();
            const from = document.querySelector('input[name="study_from"]').value;
            const hasTo = studyPresentCheckbox.checked || !!document.querySelector('input[name="study_to"]')?.value;

            if (!institution || !degree || !branch || !from || !hasTo) {
                alert('Please complete all required educational details.');
                return false;
            }
        }

        if (selectedStatus === 'working') {
            const experiences = document.querySelectorAll('#experienceContainer .experience-item');
            if (!experiences.length) {
                alert('Please add at least one work experience.');
                return false;
            }

            for (const exp of experiences) {
                const org = exp.querySelector('input[name="organization[]"]').value.trim();
                const role = exp.querySelector('input[name="role[]"]').value.trim();
                const industry = exp.querySelector('input[name="industry[]"]').value.trim();
                const location = exp.querySelector('input[name="location_exp[]"]').value.trim();
                const from = exp.querySelector('input[name="from[]"]').value;
                const toInput = exp.querySelector('input[name="to[]"]');
                const presentChecked = exp.querySelector('.exp-present-toggle').checked;
                const toValue = toInput ? toInput.value : '';

                if (!org || !role || !industry || !location || !from || (!presentChecked && !toValue)) {
                    alert('Please complete all required experience details. Use Present if still working there.');
                    return false;
                }
            }
        }

        if (selectedStatus === 'studying') {
            const experiences = document.querySelectorAll('#experienceContainer .experience-item');

            for (const exp of experiences) {
                const org = exp.querySelector('input[name="organization[]"]').value.trim();
                const role = exp.querySelector('input[name="role[]"]').value.trim();
                const industry = exp.querySelector('input[name="industry[]"]').value.trim();
                const location = exp.querySelector('input[name="location_exp[]"]').value.trim();
                const from = exp.querySelector('input[name="from[]"]').value;
                const toInput = exp.querySelector('input[name="to[]"]');
                const toValue = toInput ? toInput.value : '';
                const hasAnyValue = org || role || industry || location || from || toValue;

                if (hasAnyValue && (!org || !role || !industry || !location || !from || !toValue)) {
                    alert('Please complete all previous work details, or remove the incomplete experience.');
                    return false;
                }
            }
        }

        return true;
    }

    function toggleExperiencePresent(checkbox) {
        const card = checkbox.closest('.experience-item');
        const toDateInput = card.querySelector('.exp-to-date');
        const toHiddenInput = card.querySelector('.exp-to-hidden');

        if (checkbox.checked) {
            toDateInput.value = '';
            toDateInput.removeAttribute('name');
            toDateInput.disabled = true;
            toHiddenInput.setAttribute('name', 'to[]');
        } else {
            toDateInput.setAttribute('name', 'to[]');
            toDateInput.disabled = false;
            toHiddenInput.removeAttribute('name');
        }
    }

    function addExperience() {
        const container = document.getElementById('experienceContainer');
        const selectedStatus = document.querySelector('input[name="current_status"]:checked')?.value;
        const presentOption = selectedStatus === 'working'
            ? `<div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <input type="checkbox" class="exp-present-toggle" onchange="toggleExperiencePresent(this)">
                        I am presently working here
                    </label>
                </div>`
            : '';
        const div = document.createElement('div');
        div.classList.add('experience-item', 'border', 'border-gray-300', 'p-4', 'mb-4', 'rounded-md', 'bg-gray-50');

        div.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Organization</label>
                    <input type="text" name="organization[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Organization name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                    <input type="text" name="role[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Your role">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Industry</label>
                    <input type="text" name="industry[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Industry">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                    <input type="text" name="location_exp[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500" placeholder="Location">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">From Date</label>
                    <input type="date" name="from[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to[]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 exp-to-date">
                    <input type="hidden" class="exp-to-hidden" value="Present">
                </div>
                ${presentOption}
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 font-semibold text-sm">Remove Experience</button>
        `;

        container.appendChild(div);
    }

    // Preserve old value on validation error reload.
    loadBranches('{{ old('degree') }}', '{{ old('branch') }}');
    handleStatusChange();
    toggleStudyPresent();

    if ('{{ old('current_status') }}' === 'working' && !document.querySelector('#experienceContainer .experience-item')) {
        addExperience();
    }
</script>
@endsection
