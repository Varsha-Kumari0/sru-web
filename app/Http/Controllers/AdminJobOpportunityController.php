<?php

namespace App\Http\Controllers;

use App\Models\JobOpportunity;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminJobOpportunityController extends Controller
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

    public function create(): View
    {
        $actor = Auth::user();

        $recentJobs = JobOpportunity::query()
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'title', 'company_name', 'type', 'created_at', 'updated_at']);

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_jobs_create_opened',
            ($actor?->name ?? 'Admin') . ' opened create jobs page',
            []
        );

        return view('admin.jobs.jobs-create', [
            'types' => $this->types,
            'jobAreas' => $this->jobAreas,
            'workModes' => $this->workModes,
            'experienceLevels' => $this->experienceLevels,
            'recentJobs' => $recentJobs,
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
            'application_deadline' => 'nullable|date',
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
            'admin_job_created',
            ($actor?->name ?? 'Admin') . ' created ' . $job->type . ' opportunity: ' . $job->title,
            [
                'job_id' => $job->id,
                'type' => $job->type,
                'title' => $job->title,
                'company_name' => $job->company_name,
            ]
        );

        return redirect()
            ->route('admin.jobs.manage')
            ->with('success', ucfirst($job->type) . ' created successfully.');
    }

    public function manage(): View
    {
        $jobs = JobOpportunity::query()
            ->with('user')
            ->latest('updated_at')
            ->get();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_jobs_manage_opened',
            ($actor?->name ?? 'Admin') . ' opened manage jobs page',
            []
        );

        return view('admin.jobs.jobs-manage', [
            'jobs' => $jobs,
            'types' => $this->types,
            'jobAreas' => $this->jobAreas,
            'workModes' => $this->workModes,
            'experienceLevels' => $this->experienceLevels,
        ]);
    }

    public function edit(int $id): View
    {
        $job = JobOpportunity::query()->findOrFail($id);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_jobs_edit_opened',
            ($actor?->name ?? 'Admin') . ' opened edit jobs page for: ' . $job->title,
            [
                'job_id' => $job->id,
            ]
        );

        return view('admin.jobs.jobs-edit', [
            'job' => $job,
            'types' => $this->types,
            'jobAreas' => $this->jobAreas,
            'workModes' => $this->workModes,
            'experienceLevels' => $this->experienceLevels,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $job = JobOpportunity::query()->findOrFail($id);

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
            'application_deadline' => 'nullable|date',
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
            'admin_job_updated',
            ($actor?->name ?? 'Admin') . ' updated ' . $job->type . ' opportunity: ' . $job->title,
            [
                'job_id' => $job->id,
                'type' => $job->type,
                'title' => $job->title,
            ]
        );

        return redirect()
            ->route('admin.jobs.manage')
            ->with('success', 'Job opportunity updated successfully.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $job = JobOpportunity::query()->findOrFail($id);

        if ($job->attachment) {
            Storage::disk('public')->delete($job->attachment);
        }

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_job_deleted',
            ($actor?->name ?? 'Admin') . ' deleted ' . $job->type . ' opportunity: ' . $job->title,
            [
                'job_id' => $job->id,
                'type' => $job->type,
                'title' => $job->title,
            ]
        );

        $job->delete();

        return redirect()
            ->route('admin.jobs.manage')
            ->with('success', 'Job opportunity deleted successfully.');
    }
}
