<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\JobOpportunity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JobOpportunityController extends Controller
{
    private array $types = [
        'job' => 'Jobs',
        'internship' => 'Internships',
    ];

    private array $jobAreas = [
        'web-development' => 'Website Development',
        'app-development' => 'App Development',
        'seo' => 'SEO',
        'ui-ux' => 'UI/UX Design',
        'data-analytics' => 'Data Analytics',
        'marketing' => 'Marketing',
        'other' => 'Other',
    ];

    private array $workModes = [
        'online' => 'Online',
        'offline' => 'Offline',
        'hybrid' => 'Hybrid',
    ];

    private array $experienceLevels = [
        'fresher' => 'Fresher',
        '0-1' => '0-1 years',
        '1-3' => '1-3 years',
        '3-5' => '3-5 years',
        '5+' => '5+ years',
    ];

    public function index(Request $request): View
    {
        $query = JobOpportunity::query()->with('user')->latest();

        $selectedType = $this->validFilter($request->query('type'), array_keys($this->types));
        $selectedArea = $this->validFilter($request->query('area'), array_keys($this->jobAreas));
        $selectedMode = $this->validFilter($request->query('mode'), array_keys($this->workModes));
        $selectedExperience = $this->validFilter($request->query('experience'), array_keys($this->experienceLevels));
        $selectedLocation = trim((string) $request->query('location', ''));
        $selectedSkill = trim((string) $request->query('skill', ''));

        if ($selectedType) {
            $query->where('type', $selectedType);
        }

        if ($selectedArea) {
            $query->where('job_area', $selectedArea);
        }

        if ($selectedMode) {
            $query->where('work_mode', $selectedMode);
        }

        if ($selectedExperience) {
            $query->where('experience_level', $selectedExperience);
        }

        if ($selectedLocation !== '') {
            $query->where('location', 'like', '%' . $selectedLocation . '%');
        }

        if ($selectedSkill !== '') {
            $query->whereJsonContains('skills', $selectedSkill);
        }

        $jobs = $query->get();

        $locations = JobOpportunity::query()
            ->pluck('location')
            ->filter(fn ($location) => !is_null($location) && $location !== '')
            ->unique()
            ->sort()
            ->values();

        $skills = JobOpportunity::query()
            ->pluck('skills')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('jobs.index', [
            'jobs' => $jobs,
            'types' => $this->types,
            'jobAreas' => $this->jobAreas,
            'workModes' => $this->workModes,
            'experienceLevels' => $this->experienceLevels,
            'locations' => $locations,
            'skills' => $skills,
            'selectedType' => $selectedType,
            'selectedArea' => $selectedArea,
            'selectedMode' => $selectedMode,
            'selectedExperience' => $selectedExperience,
            'selectedLocation' => $selectedLocation,
            'selectedSkill' => $selectedSkill,
        ]);
    }

    public function create(Request $request): View
    {
        $selectedType = $this->validFilter($request->query('type'), array_keys($this->types)) ?? 'job';

        return view('jobs.create', [
            'types' => $this->types,
            'jobAreas' => $this->jobAreas,
            'workModes' => $this->workModes,
            'experienceLevels' => $this->experienceLevels,
            'selectedType' => $selectedType,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys($this->types)),
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_website' => 'nullable|url|max:500',
            'experience_level' => 'required|in:' . implode(',', array_keys($this->experienceLevels)),
            'work_mode' => 'required|in:' . implode(',', array_keys($this->workModes)),
            'location' => 'required_unless:work_mode,online|nullable|string|max:255',
            'contact_email' => 'required|email|max:255',
            'job_area' => 'required|in:' . implode(',', array_keys($this->jobAreas)),
            'skills' => 'required|string|max:500',
            'salary' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date|after_or_equal:today',
            'description' => 'required|string|min:30',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
        ]);

        $skillItems = collect(explode(',', $validated['skills']))
            ->map(fn ($skill) => trim($skill))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $attachmentPath = null;
        $attachmentName = null;

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentPath = $attachment->store('job-attachments', 'public');
            $attachmentName = $attachment->getClientOriginalName();
        }

        $job = JobOpportunity::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'title' => $validated['title'],
            'company_name' => $validated['company_name'],
            'company_website' => $validated['company_website'] ?? null,
            'experience_level' => $validated['experience_level'],
            'work_mode' => $validated['work_mode'],
            'location' => $validated['location'] ?? null,
            'contact_email' => $validated['contact_email'],
            'job_area' => $validated['job_area'],
            'skills' => $skillItems,
            'salary' => $validated['salary'] ?? null,
            'application_deadline' => $validated['application_deadline'] ?? null,
            'description' => $validated['description'],
            'attachment' => $attachmentPath,
            'attachment_original_name' => $attachmentName,
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'job_opportunity_created',
            ($actor?->name ?? 'Alumni') . ' created ' . $job->type . ' opportunity: ' . $job->title,
            [
                'job_id' => $job->id,
                'type' => $job->type,
                'title' => $job->title,
                'company_name' => $job->company_name,
                'work_mode' => $job->work_mode,
            ]
        );

        return redirect()
            ->route('jobs.index', ['type' => $job->type])
            ->with('success', ucfirst($job->type) . ' posted successfully.');
    }

    public function edit(JobOpportunity $job): View
    {
        $this->authorizeOwner($job);

        return view('jobs.edit', [
            'job' => $job,
            'types' => $this->types,
            'jobAreas' => $this->jobAreas,
            'workModes' => $this->workModes,
            'experienceLevels' => $this->experienceLevels,
        ]);
    }

    public function update(Request $request, JobOpportunity $job): RedirectResponse
    {
        $this->authorizeOwner($job);

        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys($this->types)),
            'title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_website' => 'nullable|url|max:500',
            'experience_level' => 'required|in:' . implode(',', array_keys($this->experienceLevels)),
            'work_mode' => 'required|in:' . implode(',', array_keys($this->workModes)),
            'location' => 'required_unless:work_mode,online|nullable|string|max:255',
            'contact_email' => 'required|email|max:255',
            'job_area' => 'required|in:' . implode(',', array_keys($this->jobAreas)),
            'skills' => 'required|string|max:500',
            'salary' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date|after_or_equal:today',
            'description' => 'required|string|min:30',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:4096',
        ]);

        $skillItems = collect(explode(',', $validated['skills']))
            ->map(fn ($skill) => trim($skill))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $attachmentPath = $job->attachment;
        $attachmentName = $job->attachment_original_name;

        if ($request->hasFile('attachment')) {
            if ($job->attachment) {
                Storage::disk('public')->delete($job->attachment);
            }

            $attachment = $request->file('attachment');
            $attachmentPath = $attachment->store('job-attachments', 'public');
            $attachmentName = $attachment->getClientOriginalName();
        }

        $job->update([
            'type' => $validated['type'],
            'title' => $validated['title'],
            'company_name' => $validated['company_name'],
            'company_website' => $validated['company_website'] ?? null,
            'experience_level' => $validated['experience_level'],
            'work_mode' => $validated['work_mode'],
            'location' => $validated['location'] ?? null,
            'contact_email' => $validated['contact_email'],
            'job_area' => $validated['job_area'],
            'skills' => $skillItems,
            'salary' => $validated['salary'] ?? null,
            'application_deadline' => $validated['application_deadline'] ?? null,
            'description' => $validated['description'],
            'attachment' => $attachmentPath,
            'attachment_original_name' => $attachmentName,
        ]);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'job_opportunity_updated',
            ($actor?->name ?? 'Alumni') . ' updated ' . $job->type . ' opportunity: ' . $job->title,
            [
                'job_id' => $job->id,
                'type' => $job->type,
                'title' => $job->title,
                'company_name' => $job->company_name,
                'work_mode' => $job->work_mode,
            ]
        );

        return redirect()
            ->route('jobs.index', ['type' => $job->type])
            ->with('success', 'Opportunity updated successfully.');
    }

    private function authorizeOwner(JobOpportunity $job): void
    {
        abort_unless(Auth::id() !== null && Auth::id() === (int) $job->user_id, 403);
    }

    private function validFilter(?string $value, array $allowed): ?string
    {
        return in_array($value, $allowed, true) ? $value : null;
    }
}
