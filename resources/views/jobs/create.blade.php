@extends('layouts.app')

@section('title', 'Post Opportunity')

@section('content')

<style>
    .sru-hero-gradient {
        background: linear-gradient(135deg, #1a2d4a 0%, #1e4a52 50%, #2a9d8f 100%);
    }
    .input {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 0.75rem;
        padding: 0.65rem 0.85rem;
        font-size: 0.875rem;
        background: #ffffff;
    }
    .label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: 0.8rem;
        font-weight: 700;
        color: #334155;
    }
</style>

<div class="min-h-screen" style="background:#f0f0ee;">
    <div class="sru-hero-gradient relative overflow-hidden" style="height:150px;">
        <div class="absolute -bottom-16 right-16 w-80 h-80 rounded-full" style="background:rgba(255,255,255,0.03);"></div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-end pb-6">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color:#2a9d8f;">SRU Alumni Network</p>
                <h1 class="text-3xl font-bold text-white tracking-tight">Post Job or Internship</h1>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-xl font-bold" style="color:#1a2d4a;">Opportunity Details</h2>
                    <p class="text-sm mt-1" style="color:#64748b;">Share openings with students and alumni who fit your team.</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="text-sm font-bold hover:underline" style="color:#2a9d8f;">Back to jobs</a>
            </div>

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold mb-1">Please fix the following:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('jobs.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="label">Opportunity Type <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($types as $key => $label)
                            <label class="rounded-xl border border-gray-200 p-4 cursor-pointer hover:border-[#2a9d8f]">
                                <input type="radio" name="type" value="{{ $key }}" class="mr-2"
                                    @checked(old('type', $selectedType) === $key)>
                                <span class="font-bold" style="color:#1a2d4a;">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="label">Job / Internship Title <span class="text-red-500">*</span></label>
                        <input name="title" value="{{ old('title') }}" required class="input" placeholder="Frontend Developer Intern">
                    </div>
                    <div>
                        <label class="label">Company Name <span class="text-red-500">*</span></label>
                        <input name="company_name" value="{{ old('company_name') }}" required class="input" placeholder="Company or startup name">
                    </div>
                    <div>
                        <label class="label">Company Website</label>
                        <input type="url" name="company_website" value="{{ old('company_website') }}" class="input" placeholder="https://company.com">
                    </div>
                    <div>
                        <label class="label">Contact Email <span class="text-red-500">*</span></label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', auth()->user()->email ?? '') }}" required class="input">
                    </div>
                    <div>
                        <label class="label">Experience Level <span class="text-red-500">*</span></label>
                        <select name="experience_level" required class="input">
                            @foreach($experienceLevels as $key => $label)
                                <option value="{{ $key }}" @selected(old('experience_level') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Work Mode <span class="text-red-500">*</span></label>
                        <select name="work_mode" id="workMode" required class="input">
                            @foreach($workModes as $key => $label)
                                <option value="{{ $key }}" @selected(old('work_mode') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Location <span class="text-red-500" id="locationRequired">*</span></label>
                        <input name="location" id="locationInput" value="{{ old('location') }}" class="input" placeholder="Hyderabad, Bengaluru, Remote">
                    </div>
                    <div>
                        <label class="label">Job Area <span class="text-red-500">*</span></label>
                        <select name="job_area" required class="input">
                            @foreach($jobAreas as $key => $label)
                                <option value="{{ $key }}" @selected(old('job_area') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Skills <span class="text-red-500">*</span></label>
                        <input name="skills" value="{{ old('skills') }}" required class="input" placeholder="Laravel, React, SEO">
                        <p class="text-xs mt-1" style="color:#64748b;">Separate skills with commas.</p>
                    </div>
                    <div>
                        <label class="label">Salary / Stipend</label>
                        <input name="salary" value="{{ old('salary') }}" class="input" placeholder="₹25,000/month or Negotiable">
                    </div>
                    <div>
                        <label class="label">Application Deadline</label>
                        <input type="date" name="application_deadline" value="{{ old('application_deadline') }}" class="input">
                    </div>
                    <div>
                        <label class="label">Attachment</label>
                        <input type="file" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="input">
                        <p class="text-xs mt-1" style="color:#64748b;">Optional PDF, document, or image up to 4 MB.</p>
                    </div>
                </div>

                <div>
                    <label class="label">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="8" required class="input" placeholder="Describe responsibilities, eligibility, application steps, and anything candidates should know.">{{ old('description') }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3 pt-2">
                    <a href="{{ route('jobs.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-bold text-center hover:bg-gray-50" style="color:#1a2d4a;">Cancel</a>
                    <button class="rounded-xl px-5 py-2.5 text-sm font-bold text-white" style="background:#1a2d4a;">
                        Publish Opportunity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const workMode = document.getElementById('workMode');
    const locationInput = document.getElementById('locationInput');
    const locationRequired = document.getElementById('locationRequired');

    function syncLocationRequirement() {
        const isOnline = workMode.value === 'online';
        locationInput.required = !isOnline;
        locationRequired.style.display = isOnline ? 'none' : 'inline';
        if (isOnline && !locationInput.value.trim()) {
            locationInput.placeholder = 'Remote';
        }
    }

    workMode.addEventListener('change', syncLocationRequirement);
    syncLocationRequirement();
</script>

@endsection
