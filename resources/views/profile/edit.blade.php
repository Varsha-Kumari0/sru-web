@extends('layouts.app')

@section('content')

    <div class="max-w-5xl mx-auto mt-8">

        <div class="bg-white shadow rounded-lg p-8">

            <h2 class="text-2xl font-semibold mb-6">Edit Profile</h2>

            @if(!$profile)
                <div class="mb-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
                    <p>You haven't created a profile yet. Please visit the dashboard to create your profile first.</p>
                    <a href="/dashboard" class="mt-2 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Go to Dashboard</a>
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
                        <img src="{{ asset('storage/' . $profile->profile_photo) }}"
                            class="w-24 h-24 rounded-full mx-auto mb-3 object-cover border">
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

                        <div>
                            <label class="label">Father's Name</label>
                            <input value="{{ $profile->father_name ?? '-' }}" class="input bg-gray-100" disabled>
                        </div>

                    </div>
                @endif

                @if($profile)
                    @php
                        $selectedStatus = old('current_status', $profile->current_status ?? 'working');
                        $studyToValue = old('study_to', $profile->study_to ?? '');
                        $hasOldPreviousRows = old('previous_institution') !== null;

                        if ($hasOldPreviousRows) {
                            $previousEducationRows = [];
                            $oldInstitutions = old('previous_institution', []);
                            $oldDegrees = old('previous_degree', []);
                            $oldBranches = old('previous_branch', []);
                            $oldFrom = old('previous_from', []);
                            $oldTo = old('previous_to', []);

                            foreach ($oldInstitutions as $idx => $institution) {
                                $previousEducationRows[] = [
                                    'institution' => $institution,
                                    'degree' => $oldDegrees[$idx] ?? '',
                                    'branch' => $oldBranches[$idx] ?? '',
                                    'from' => $oldFrom[$idx] ?? '',
                                    'to' => $oldTo[$idx] ?? '',
                                ];
                            }
                        } else {
                            $previousEducationRows = $profile->previous_education ?? [];
                        }
                    @endphp

                    <!-- ✏️ EDITABLE -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="label">City <span class="text-red-500">*</span></label>
                            <input name="city" value="{{ old('city', $profile->city ?? '') }}" class="input">
                        </div>

                        <div>
                            <label class="label">Country <span class="text-red-500">*</span></label>
                            <input name="country" value="{{ old('country', $profile->country ?? '') }}" class="input">
                        </div>

                        <div>
                            <label class="label">LinkedIn URL <span class="text-red-500">*</span></label>
                            <input type="url" name="linkedin" value="{{ old('linkedin', $profile->linkedin ?? '') }}" class="input" placeholder="https://linkedin.com/in/username" required>
                            @error('linkedin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Instagram URL <span class="text-red-500">*</span></label>
                            <input type="url" name="instagram" value="{{ old('instagram', $profile->instagram ?? '') }}" class="input" placeholder="https://instagram.com/username" required>
                            @error('instagram')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Facebook URL <span class="text-red-500">*</span></label>
                            <input type="url" name="facebook" value="{{ old('facebook', $profile->facebook ?? '') }}" class="input" placeholder="https://facebook.com/username" required>
                            @error('facebook')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Twitter / X URL <span class="text-red-500">*</span></label>
                            <input type="url" name="twitter" value="{{ old('twitter', $profile->twitter ?? '') }}" class="input" placeholder="https://x.com/username" required>
                            @error('twitter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- ✅ CURRENT STATUS -->
                    <div class="mt-10">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Current Status</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <label class="border border-gray-300 rounded-md p-4 cursor-pointer">
                                <input type="radio" name="current_status" value="studying" class="mr-2"
                                    @checked($selectedStatus === 'studying')>
                                <span class="font-semibold text-gray-800">I am currently studying</span>
                            </label>

                            <label class="border border-gray-300 rounded-md p-4 cursor-pointer">
                                <input type="radio" name="current_status" value="working" class="mr-2"
                                    @checked($selectedStatus === 'working')>
                                <span class="font-semibold text-gray-800">I am currently working</span>
                            </label>
                        </div>

                        <!-- 🎓 CURRENT EDUCATION -->
                        <div id="studySection" style="display:none;" class="mb-6 border border-gray-300 p-4 rounded-md bg-gray-50">
                            <h4 class="text-base font-semibold text-gray-700 mb-4">Current Educational Details</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">Institution / College <span class="text-red-500">*</span></label>
                                    <input type="text" name="study_institution" value="{{ old('study_institution', $profile->study_institution ?? '') }}" class="input" placeholder="Institution name">
                                </div>

                                <div>
                                    <label class="label">Current Degree <span class="text-red-500">*</span></label>
                                    <input type="text" name="study_degree" value="{{ old('study_degree', $profile->study_degree ?? '') }}" class="input" placeholder="Degree you are pursuing">
                                </div>

                                <div>
                                    <label class="label">Specialization / Branch <span class="text-red-500">*</span></label>
                                    <input type="text" name="study_branch" value="{{ old('study_branch', $profile->study_branch ?? '') }}" class="input" placeholder="Current specialization">
                                </div>

                                <div>
                                    <label class="label">From Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="study_from" value="{{ old('study_from', $profile->study_from ?? '') }}" class="input">
                                </div>

                                <div>
                                    <label class="label">To Date</label>
                                    <input type="date" id="studyToDate" name="study_to"
                                        value="{{ $studyToValue !== 'Present' ? $studyToValue : '' }}" class="input">
                                    <input type="hidden" id="studyToHidden" value="Present">
                                </div>

                                <div class="flex items-end">
                                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <input type="checkbox" id="studyPresentCheckbox"
                                            @checked($studyToValue === 'Present')>
                                        I am presently studying
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 💼 EXPERIENCE -->
                    <div id="workSection" class="mt-10" style="display:none;">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Professional Experience</h3>

                        @error('organization')
                            <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div id="experienceContainer">

                            @foreach($experiences as $exp)
                                @php
                                    $isPresent = ($exp->to === 'Present');
                                @endphp
                                <div class="experience-item bg-gray-50 border rounded p-5 mb-4">

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
                                            <input type="date" class="input exp-to-date"
                                                @if(!$isPresent) name="to[]" value="{{ $exp->to }}" @endif>
                                            <input type="hidden" class="exp-to-hidden" value="Present"
                                                @if($isPresent) name="to[]" @endif>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                                                <input type="checkbox" class="exp-present-toggle" onchange="toggleExperiencePresent(this)"
                                                    @checked($isPresent)>
                                                I am currently working here
                                            </label>
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

                    <!-- 🎓 PREVIOUS EDUCATION -->
                    <div class="mt-10">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Previous Educational Details</h3>

                        <div id="previousEducationContainer">
                            @foreach($previousEducationRows as $row)
                                <div class="previous-education-item bg-gray-50 border rounded p-5 mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="label">Institution / College</label>
                                            <input name="previous_institution[]" value="{{ $row['institution'] ?? '' }}" class="input" placeholder="Institution name">
                                        </div>
                                        <div>
                                            <label class="label">Degree</label>
                                            <input name="previous_degree[]" value="{{ $row['degree'] ?? '' }}" class="input" placeholder="Degree name">
                                        </div>
                                        <div>
                                            <label class="label">Specialization / Branch</label>
                                            <input name="previous_branch[]" value="{{ $row['branch'] ?? '' }}" class="input" placeholder="Branch or specialization">
                                        </div>
                                        <div>
                                            <label class="label">From</label>
                                            <input type="date" name="previous_from[]" value="{{ $row['from'] ?? '' }}" class="input">
                                        </div>
                                        <div>
                                            <label class="label">To</label>
                                            <input type="date" name="previous_to[]" value="{{ $row['to'] ?? '' }}" class="input">
                                        </div>
                                    </div>

                                    <button type="button" onclick="this.parentElement.remove()"
                                        class="text-red-500 text-sm mt-3 hover:underline">
                                        Remove
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" onclick="addPreviousEducation()"
                            class="text-blue-600 text-sm font-medium mt-2 hover:underline">
                            + Add Previous Education
                        </button>
                    </div>
                @endif

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
        const statusRadios = document.querySelectorAll('input[name="current_status"]');
        const studySection = document.getElementById('studySection');
        const workSection = document.getElementById('workSection');
        const studyToDateInput = document.getElementById('studyToDate');
        const studyPresentCheckbox = document.getElementById('studyPresentCheckbox');
        const studyToHiddenInput = document.getElementById('studyToHidden');

        function setSectionEnabled(section, enabled) {
            if (!section) {
                return;
            }

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
            if (!studyPresentCheckbox || !studyToDateInput || !studyToHiddenInput) {
                return;
            }

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

        function handleStatusChange() {
            const selectedStatus = document.querySelector('input[name="current_status"]:checked')?.value;

            if (studySection) {
                studySection.style.display = selectedStatus === 'studying' ? 'block' : 'none';
            }

            if (workSection) {
                workSection.style.display = selectedStatus === 'working' ? 'block' : 'none';
            }

            setSectionEnabled(studySection, selectedStatus === 'studying');
            setSectionEnabled(workSection, selectedStatus === 'working');

            if (selectedStatus === 'studying') {
                toggleStudyPresent();
            }

            if (selectedStatus === 'working' && !document.querySelector('#experienceContainer .experience-item')) {
                addExperience();
            }
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

            const newExperience = document.createElement('div');
            newExperience.className = 'experience-item bg-gray-50 border rounded p-5 mb-4';
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
                        <input type="date" name="to[]" class="input exp-to-date">
                        <input type="hidden" class="exp-to-hidden" value="Present">
                    </div>
                    <div class="md:col-span-2">
                        <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                            <input type="checkbox" class="exp-present-toggle" onchange="toggleExperiencePresent(this)">
                            I am currently working here
                        </label>
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()"
                    class="text-red-500 text-sm mt-3 hover:underline">
                    Remove
                </button>
            `;

            container.appendChild(newExperience);
        }

        function addPreviousEducation() {
            const container = document.getElementById('previousEducationContainer');

            const newEducation = document.createElement('div');
            newEducation.className = 'previous-education-item bg-gray-50 border rounded p-5 mb-4';
            newEducation.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Institution / College</label>
                        <input name="previous_institution[]" class="input" placeholder="Institution name">
                    </div>
                    <div>
                        <label class="label">Degree</label>
                        <input name="previous_degree[]" class="input" placeholder="Degree name">
                    </div>
                    <div>
                        <label class="label">Specialization / Branch</label>
                        <input name="previous_branch[]" class="input" placeholder="Branch or specialization">
                    </div>
                    <div>
                        <label class="label">From</label>
                        <input type="date" name="previous_from[]" class="input">
                    </div>
                    <div>
                        <label class="label">To</label>
                        <input type="date" name="previous_to[]" class="input">
                    </div>
                </div>
                <button type="button" onclick="this.parentElement.remove()"
                    class="text-red-500 text-sm mt-3 hover:underline">
                    Remove
                </button>
            `;

            container.appendChild(newEducation);
        }

        statusRadios.forEach(function (radio) {
            radio.addEventListener('change', handleStatusChange);
        });

        if (studyPresentCheckbox) {
            studyPresentCheckbox.addEventListener('change', toggleStudyPresent);
        }

        document.querySelectorAll('.exp-present-toggle').forEach(function (checkbox) {
            toggleExperiencePresent(checkbox);
        });

        handleStatusChange();

        if (!document.querySelector('#previousEducationContainer .previous-education-item')) {
            addPreviousEducation();
        }
    </script>

@endsection