<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>All SRU Alumni - Admin</title>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	<style>
		body { font-family: 'DM Sans', sans-serif; }
		.font-display { font-family: 'Playfair Display', serif; }
		.nav-active {
			background: rgba(59,130,246,.14);
			color: #1d4ed8 !important;
		}
	</style>
</head>
<body class="min-h-screen flex bg-slate-50 text-slate-900 [background-image:radial-gradient(ellipse_at_10%_20%,rgba(59,130,246,.08)_0%,transparent_60%),radial-gradient(ellipse_at_90%_80%,rgba(148,163,184,.12)_0%,transparent_60%)]">

{{-- Admin sidebar navigation --}}
<aside class="w-64 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50 bg-white border-r border-slate-300">
	<div class="px-6 py-5 border-b border-slate-300 min-h-[89px] flex flex-col justify-center">
		@php($dashboardLogoPath = 'images/logos/sru_logo_new.png')

		@if(file_exists(public_path($dashboardLogoPath)))
			<img src="{{ asset($dashboardLogoPath) }}" alt="SRU Alumni Logo" class="h-12 w-auto object-contain">
		@else
			<h1 class="font-display text-xl font-bold text-sky-400 tracking-[0.02em] leading-tight">SRU<br>Alumni</h1>
		@endif

		<span class="text-xs font-semibold tracking-widest uppercase mt-1 block text-slate-500">Admin Control</span>
	</div>

	<nav class="flex-1 px-4 py-5 space-y-0.5">
		<p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-1 text-slate-500">Overview</p>

		<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
				<rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
			</svg>
			Dashboard
		</a>

		<a href="{{ route('admin.allalumini') }}" class="nav-active flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
				<circle cx="9" cy="7" r="4"/>
				<path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
			</svg>
			All SRU Alumni
		</a>

		<p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5 text-slate-500">Management</p>

		<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
				<polyline points="22,6 12,13 2,6"/>
			</svg>
			Messages
		</a>

		<div class="group relative">
			<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
				<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					<path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14l-4-2-4 2-4-2-4 2V5z"/>
					<line x1="8" y1="8" x2="16" y2="8"/>
					<line x1="8" y1="11" x2="16" y2="11"/>
				</svg>
				<span class="flex-1">News</span>
				<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
					<polyline points="6 9 12 15 18 9"/>
				</svg>
			</a>
			<div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
				<a href="{{ route('newsroom') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
				<a href="{{ route('admin.news.create') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
				<a href="{{ route('admin.news.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
			</div>
		</div>

		<div class="group relative">
			<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
				<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
					<line x1="16" y1="2" x2="16" y2="6"/>
					<line x1="8" y1="2" x2="8" y2="6"/>
					<line x1="3" y1="10" x2="21" y2="10"/>
				</svg>
				<span class="flex-1">Events</span>
				<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
					<polyline points="6 9 12 15 18 9"/>
				</svg>
			</a>
			<div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
				<a href="{{ route('events.index') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
				<a href="{{ route('admin.events.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
				<a href="{{ route('admin.events.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
			</div>
		</div>

		<div class="group relative">
			<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
				<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					<rect x="3" y="3" width="18" height="18" rx="2"></rect>
					<circle cx="8.5" cy="8.5" r="1.5"></circle>
					<polyline points="21 15 16 10 5 21"></polyline>
				</svg>
				<span class="flex-1">Gallery</span>
				<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
					<polyline points="6 9 12 15 18 9"/>
				</svg>
			</a>
			<div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
				<a href="{{ route('gallery') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
				<a href="{{ route('admin.gallery.create', ['section' => 'albums']) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
				<a href="{{ route('admin.gallery.manage', ['section' => 'albums']) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
			</div>
		</div>

		<div class="group relative">
			<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
				<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
					<rect x="3" y="3" width="18" height="18" rx="2"></rect>
					<path d="M7 12h10"></path>
					<path d="M7 16h10"></path>
				</svg>
				<span class="flex-1">Jobs</span>
				<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
					<polyline points="6 9 12 15 18 9"/>
				</svg>
			</a>
			<div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
				<a href="{{ route('jobs.index') }}" target="_blank" rel="noopener noreferrer" onclick="event.preventDefault(); event.stopPropagation(); window.open(this.href, '_blank');" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
				<a href="{{ route('admin.jobs.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
				<a href="{{ route('admin.jobs.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
			</div>
		</div>

		        <div class="group relative">
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
                <span class="flex-1">Engage</span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-transform duration-150 group-hover:rotate-180">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </a>
            <div class="absolute left-full top-0 z-50 hidden min-w-[11rem] flex-col gap-1 rounded-xl border border-slate-200 bg-white p-2 shadow-lg group-hover:flex">
                <a href="{{ route('engage') }}" target="_blank" rel="noopener noreferrer" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">View</a>
                <a href="{{ route('admin.engage.create') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">New</a>
                <a href="{{ route('admin.engage.manage') }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-slate-500 transition-colors duration-150 hover:bg-slate-100 hover:text-slate-900">Update/Delete</a>
            </div>
        </div>
<a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<path d="M3 3v18h18"/>
				<path d="M8 14l3-3 3 2 4-5"/>
			</svg>
			Activity Logs
		</a>

	</nav>

	<div class="px-4 py-5 border-t border-slate-300">
		<div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
			{{-- Clickable admin avatar: click to change photo --}}
			<form method="POST" action="{{ route('admin.profile.avatar') }}" enctype="multipart/form-data" id="adminAvatarForm">
				@csrf
				<label for="adminAvatarInput" title="Click to change profile photo"
					   onmouseover="this.querySelector('.av-overlay').style.opacity='1'"
					   onmouseout="this.querySelector('.av-overlay').style.opacity='0'"
					   style="cursor:pointer;position:relative;display:block;width:36px;height:36px;border-radius:50%;flex-shrink:0;overflow:hidden;">
					@if(auth()->user()->avatar)
						<img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Admin"
							 style="width:36px;height:36px;border-radius:50%;object-fit:contain;object-position:center;background:#fff;border:1px solid #dde3ec;">
					@else
						<div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#fff;">
							{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
						</div>
					@endif
					<div class="av-overlay" style="position:absolute;inset:0;background:rgba(0,0,0,.45);border-radius:50%;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .15s;">
						<svg width="13" height="13" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
					</div>
				</label>
				<input type="file" id="adminAvatarInput" name="avatar" accept="image/jpg,image/jpeg,image/png" class="hidden" onchange="this.form.submit()">
			</form>
			<div class="flex-1 min-w-0">
				<p class="text-sm font-semibold truncate text-slate-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
				<p class="text-xs text-slate-500">Super Admin</p>
			</div>
			<a href="{{ route('logout') }}"
			   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
			   title="Logout"
			   class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition-all duration-150 hover:bg-slate-100 hover:text-slate-900">
				<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-colors duration-150">
					<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
					<polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
				</svg>
			</a>
		</div>
		<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
	</div>
</aside>

{{-- Main content: alumni listing and row actions --}}
<main class="ml-64 flex-1 flex flex-col min-h-screen">
	<header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
		<div>
			<h2 class="font-display text-2xl font-semibold">All SRU Alumni</h2>
			<p class="text-xs mt-0.5 text-slate-500">{{ now()->format('l, d F Y') }} - Total records: {{ $users->count() }}</p>
		</div>
		<a href="{{ route('admin.allalumini.export', request()->query()) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
			<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
				<polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
			</svg>
			Export CSV
		</a>
	</header>

	<div class="p-9 flex-1">
		@if(session('success'))
			<div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
				{{ session('success') }}
			</div>
		@endif
		@if(session('error'))
			<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
				{{ session('error') }}
			</div>
		@endif

		<form id="alumniFilterForm" method="GET" action="{{ route('admin.allalumini') }}" class="mb-5 rounded-xl border border-slate-300 bg-white p-4">
			<div class="grid grid-cols-1 gap-3 md:grid-cols-[minmax(160px,220px)_1fr_auto_auto] md:items-end">
				<div>
					<label for="filter_by" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-slate-500">Filter By</label>
					<select id="filter_by" name="filter_by" onchange="handleFilterByChange()" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none">
						<option value="all" {{ ($selectedFilterBy ?? 'all') === 'all' ? 'selected' : '' }}>All Alumni</option>
						<option value="branch" {{ ($selectedFilterBy ?? 'all') === 'branch' ? 'selected' : '' }}>Branch</option>
						<option value="graduation_year" {{ ($selectedFilterBy ?? 'all') === 'graduation_year' ? 'selected' : '' }}>Graduation Year</option>
						<option value="organization" {{ ($selectedFilterBy ?? 'all') === 'organization' ? 'selected' : '' }}>Organization</option>
						<option value="role" {{ ($selectedFilterBy ?? 'all') === 'role' ? 'selected' : '' }}>Role</option>
						<option value="location" {{ ($selectedFilterBy ?? 'all') === 'location' ? 'selected' : '' }}>Location</option>
					</select>
				</div>
				<div>
					<label for="filter_value" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-slate-500">Value</label>
					<div class="relative">
						<input
							type="text"
							id="filter_value"
							name="filter_value"
							value="{{ $selectedFilterValue ?? '' }}"
							placeholder="Type to search values"
							autocomplete="off"
							onfocus="openValueDropdown()"
							onblur="queueCloseValueDropdown()"
							oninput="applyValueDropdownFilter()"
							class="w-full rounded-lg border border-slate-300 px-3 py-2 pr-9 text-sm text-slate-700 focus:border-blue-500 focus:outline-none"
						>
						<button
							type="button"
							onclick="toggleValueDropdown()"
							class="absolute inset-y-0 right-0 inline-flex items-center px-3 text-slate-500"
							tabindex="-1"
						>
							<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
								<polyline points="6 9 12 15 18 9"/>
							</svg>
						</button>
						<div id="filter_value_dropdown" class="absolute z-30 mt-1 hidden max-h-52 w-full overflow-y-auto rounded-lg border border-slate-300 bg-white shadow-lg">
							@foreach(($filterValues ?? collect()) as $value)
								<button
									type="button"
									data-filter-value-item
									data-value="{{ $value }}"
									onmousedown="selectFilterValue('{{ addslashes($value) }}')"
									class="block w-full px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-100"
								>
									{{ $value }}
								</button>
							@endforeach
							<div id="filter_value_no_match" class="hidden px-3 py-2 text-sm text-slate-500">No matching values</div>
						</div>
					</div>
				</div>
				<button type="submit" class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
					Apply Filter
				</button>
				<a href="{{ route('admin.allalumini') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
					Reset
				</a>
			</div>
		</form>

		<div class="rounded-xl overflow-hidden bg-white border border-slate-300">
			<div class="overflow-x-auto">
				<table class="min-w-full text-sm">
					<thead class="bg-slate-100 text-slate-700">
						<tr>
							<th class="px-4 py-3 text-left">Name</th>
							<th class="px-4 py-3 text-left">Email</th>
							<th class="px-4 py-3 text-left">Mobile</th>
							<th class="px-4 py-3 text-left">Branch</th>
							<th class="px-4 py-3 text-left">Graduation Year</th>
							<th class="px-4 py-3 text-left">Organization</th>
							<th class="px-4 py-3 text-left">Role</th>
							<th class="px-4 py-3 text-left">Location</th>
							<th class="px-4 py-3 text-left">Skills</th>
							<th class="px-4 py-3 text-left">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($users->isEmpty()): ?>
							<tr>
								<td colspan="10" class="px-4 py-8 text-center text-slate-500">No alumni records found.</td>
							</tr>
						<?php else: ?>
							<?php foreach ($users as $user): ?>
								<?php
									$displayName = $user->profile?->full_name ?? $user->name;
									$profilePhotoUrl = $user->profile?->profile_photo ? asset('storage/' . $user->profile->profile_photo) : null;
									$initials = strtoupper(substr($displayName, 0, 1));
									$avatarColors = ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899'];
									$avatarColor = $avatarColors[crc32($displayName) % count($avatarColors)];
									$skillsText = collect($user->skills ?? [])
										->pluck('name')
										->map(fn ($name) => is_string($name) ? trim($name) : '')
										->filter()
										->unique()
										->values()
										->implode(', ');
									$skillsText = $skillsText !== '' ? $skillsText : '-';

									$knownProfileFields = [
										'id',
										'user_id',
										'created_at',
										'updated_at',
										'profile_photo',
										'full_name',
										'father_name',
										'mobile',
										'city',
										'country',
										'linkedin',
										'facebook',
										'instagram',
										'twitter',
										'degree',
										'branch',
										'passing_year',
										'current_status',
										'company',
										'employment_from',
										'employment_to',
										'study_institution',
										'study_degree',
										'study_branch',
										'study_from',
										'study_to',
										'previous_education',
										'description',
									];

									$dynamicProfileFields = collect($user->profile?->getAttributes() ?? [])
										->except($knownProfileFields)
										->filter(function ($value) {
											if (is_null($value)) {
												return false;
											}

											if (is_string($value) && trim($value) === '') {
												return false;
											}

											return true;
										})
										->mapWithKeys(function ($value, $key) {
											$formattedValue = is_array($value)
												? json_encode($value, JSON_UNESCAPED_UNICODE)
												: (string) $value;

											return [ucwords(str_replace('_', ' ', $key)) => $formattedValue];
										})
										->all();

									$dynamicProfessionalFields = collect($user->professional?->getAttributes() ?? [])
										->except([
											'id',
											'user_id',
											'created_at',
											'updated_at',
											'organization',
											'industry',
											'role',
											'from',
											'to',
											'location',
										])
										->filter(function ($value) {
											if (is_null($value)) {
												return false;
											}

											if (is_string($value) && trim($value) === '') {
												return false;
											}

											return true;
										})
										->mapWithKeys(function ($value, $key) {
											$formattedValue = is_array($value)
												? json_encode($value, JSON_UNESCAPED_UNICODE)
												: (string) $value;

											return [ucwords(str_replace('_', ' ', $key)) => $formattedValue];
										})
										->all();
									$detailPayload = [
										'user_name' => $user->name,
										'full_name' => $displayName,
										'profile_photo' => $profilePhotoUrl,
										'email' => $user->email,
										'phone' => $user->profile?->mobile ?? '-',
										'father_name' => $user->profile?->father_name ?? '-',
										'city' => $user->profile?->city ?? '-',
										'country' => $user->profile?->country ?? '-',
										'linkedin' => $user->profile?->linkedin ?? '-',
										'facebook' => $user->profile?->facebook ?? '-',
										'instagram' => $user->profile?->instagram ?? '-',
										'twitter' => $user->profile?->twitter ?? '-',
										'degree' => $user->profile?->degree ?? '-',
										'branch' => $user->profile?->branch ?? '-',
										'passing_year' => $user->profile?->passing_year ?? '-',
										'current_status' => $user->profile?->current_status ?? '-',
										'company' => $user->profile?->company ?? '-',
										'employment_from' => $user->profile?->employment_from ?? '-',
										'employment_to' => $user->profile?->employment_to ?? '-',
										'study_institution' => $user->profile?->study_institution ?? '-',
										'study_degree' => $user->profile?->study_degree ?? '-',
										'study_branch' => $user->profile?->study_branch ?? '-',
										'study_from' => $user->profile?->study_from ?? '-',
										'study_to' => $user->profile?->study_to ?? '-',
										'description' => $user->profile?->description ?? '-',
										'previous_education' => collect($user->profile?->previous_education ?? [])
											->map(function ($row) {
												return implode(' | ', [
													$row['institution'] ?? '',
													$row['degree'] ?? '',
													$row['branch'] ?? '',
													$row['from'] ?? '',
													$row['to'] ?? '',
												]);
											})
											->filter()
											->implode("\n") ?: '-',
										'organization' => $user->professional?->organization ?? '-',
										'industry' => $user->professional?->industry ?? '-',
										'role' => $user->professional?->role ?? '-',
										'work_from' => $user->professional?->from ?? '-',
										'work_to' => $user->professional?->to ?? '-',
										'work_location' => $user->professional?->location ?? '-',
										'skills' => $skillsText,
										'registered' => $user->created_at?->format('d M Y') ?? '-',
										'dynamic_profile_fields' => $dynamicProfileFields,
										'dynamic_professional_fields' => $dynamicProfessionalFields,
									];
								?>
								<tr class="border-t border-slate-200 hover:bg-slate-50">
									<td class="px-4 py-3">
										<div class="flex items-center gap-2.5">
											@if($profilePhotoUrl)
												<img src="{{ $profilePhotoUrl }}" alt="{{ $displayName }}" style="width:36px;height:36px;border-radius:8px;object-fit:contain;object-position:center;background:#f8f9fc;flex-shrink:0;border:1px solid #dde3ec;">
											@else
												<div style="width:36px;height:36px;border-radius:8px;background:{{ $avatarColor }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;color:#fff;flex-shrink:0;">{{ $initials }}</div>
											@endif
											<span class="font-medium text-slate-900">{{ $displayName }}</span>
										</div>
									</td>
									<td class="px-4 py-3 text-slate-700">{{ $user->email }}</td>
									<td class="px-4 py-3 text-slate-700">{{ $user->profile?->mobile ?? '-' }}</td>
									<td class="px-4 py-3 text-slate-700">{{ $user->profile?->branch ?? '-' }}</td>
									<td class="px-4 py-3 text-slate-700">{{ $user->profile?->passing_year ?? '-' }}</td>
									<td class="px-4 py-3 text-slate-700">{{ $user->professional?->organization ?? '-' }}</td>
									<td class="px-4 py-3 text-slate-700">{{ $user->professional?->role ?? '-' }}</td>
									<td class="px-4 py-3 text-slate-700">{{ $user->professional?->location ?? '-' }}</td>
									<td class="px-4 py-3 text-slate-700 max-w-[220px] break-words">{{ $skillsText }}</td>
									<td class="px-4 py-3">
										<div class="flex items-center gap-2">
											<button type="button"
												title="View Details"
												class="h-[30px] w-[30px] rounded-[7px] border-none flex items-center justify-center"
												style="background:#ffffff;"
												onmouseover="this.style.background='#dbeafe'"
												onmouseout="this.style.background='#ffffff'"
												onclick='openDetails(@json($detailPayload))'>
												<svg width="13" height="13" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24">
													<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
													<circle cx="12" cy="12" r="3"/>
												</svg>
											</button>

											<a href="{{ route('admin.alumni.edit', $user->id) }}"
												title="Edit Details"
												class="h-[30px] w-[30px] rounded-[7px] border-none flex items-center justify-center transition-all"
												style="background:#ffffff; display:inline-flex;"
												onmouseover="this.style.background='#fef3c7'"
												onmouseout="this.style.background='#ffffff'">
												<svg width="13" height="13" fill="none" stroke="#f59e0b" stroke-width="2" viewBox="0 0 24 24">
													<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
													<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
												</svg>
											</a>

											<form method="POST" action="{{ route('admin.alumni.delete', $user->id) }}" onsubmit="return confirm('Delete this alumni record?');">
												@csrf
												@method('DELETE')
												<button type="submit" class="px-2.5 py-1.5 text-xs font-medium rounded-md bg-red-100 text-red-700 hover:bg-red-200">
													Delete
												</button>
											</form>
										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</main>

{{-- Alumni quick-view modal --}}
<div id="detailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
	<div class="w-full max-w-4xl overflow-hidden rounded-xl bg-white border border-slate-300 shadow-xl">
		<div class="flex items-center justify-between border-b border-slate-200 px-6 py-5">
			<h3 id="detailsTitle" class="text-xl font-semibold text-slate-900">Alumni Details</h3>
			<button type="button" class="text-slate-500 hover:text-slate-900" onclick="closeDetails()">Close</button>
		</div>
		<div class="max-h-[75vh] overflow-y-auto overflow-x-hidden p-6">
			<div id="detailsBody" class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4"></div>
		</div>
	</div>
</div>

<script>
let valueDropdownCloseTimer = null;

function toggleValueFilter() {
	const filterBy = document.getElementById('filter_by');
	const filterValue = document.getElementById('filter_value');
	const filterValueDropdown = document.getElementById('filter_value_dropdown');
	if (!filterBy || !filterValue) return;

	const useValue = filterBy.value !== 'all';
	filterValue.disabled = !useValue;
	filterValue.classList.toggle('bg-slate-100', !useValue);
	filterValue.classList.toggle('cursor-not-allowed', !useValue);
	filterValue.placeholder = useValue ? `Type to search ${getFilterValueLabel(filterBy.value).toLowerCase()}` : 'Select filter first';

	if (!useValue) {
		filterValue.value = '';
		if (filterValueDropdown) {
			filterValueDropdown.classList.add('hidden');
		}
	}
}

function openValueDropdown() {
	const filterValue = document.getElementById('filter_value');
	const dropdownEl = document.getElementById('filter_value_dropdown');
	if (!filterValue || !dropdownEl || filterValue.disabled) return;

	clearTimeout(valueDropdownCloseTimer);
	dropdownEl.classList.remove('hidden');
	applyValueDropdownFilter();
}

function closeValueDropdown() {
	const dropdownEl = document.getElementById('filter_value_dropdown');
	if (!dropdownEl) return;
	dropdownEl.classList.add('hidden');
}

function queueCloseValueDropdown() {
	clearTimeout(valueDropdownCloseTimer);
	valueDropdownCloseTimer = setTimeout(closeValueDropdown, 120);
}

function toggleValueDropdown() {
	const filterValue = document.getElementById('filter_value');
	const dropdownEl = document.getElementById('filter_value_dropdown');
	if (!filterValue || !dropdownEl || filterValue.disabled) return;

	if (dropdownEl.classList.contains('hidden')) {
		openValueDropdown();
		filterValue.focus();
	} else {
		closeValueDropdown();
	}
}

function applyValueDropdownFilter() {
	const filterValue = document.getElementById('filter_value');
	const items = document.querySelectorAll('[data-filter-value-item]');
	const noMatch = document.getElementById('filter_value_no_match');
	if (!filterValue || !items) return;

	const query = filterValue.value.trim().toLowerCase();
	let visibleCount = 0;

	items.forEach((item) => {
		const value = (item.getAttribute('data-value') || '').toLowerCase();
		const show = query === '' || value.includes(query);
		item.classList.toggle('hidden', !show);
		if (show) visibleCount += 1;
	});

	if (noMatch) {
		noMatch.classList.toggle('hidden', visibleCount > 0);
	}
}

function selectFilterValue(value) {
	const filterValue = document.getElementById('filter_value');
	if (!filterValue) return;
	filterValue.value = value;
	applyValueDropdownFilter();
	closeValueDropdown();
}

function getFilterValueLabel(filterBy) {
	const labels = {
		branch: 'Branch',
		graduation_year: 'Graduation Year',
		organization: 'Organization',
		role: 'Role',
		location: 'Location',
	};

	return labels[filterBy] || 'Value';
}

function handleFilterByChange() {
	const filterValue = document.getElementById('filter_value');
	if (filterValue) {
		filterValue.value = '';
	}

	const form = document.getElementById('alumniFilterForm');
	if (form) {
		form.submit();
	}
}

window.addEventListener('DOMContentLoaded', toggleValueFilter);

	// Render selected alumni details inside modal.
	function openDetails(data) {
		const modal = document.getElementById('detailsModal');
		const title = document.getElementById('detailsTitle');
		const body = document.getElementById('detailsBody');
		title.textContent = data.full_name || data.user_name || 'Alumni Details';

		const entries = [
			['Full Name', data.full_name],
			['Father Name', data.father_name],
			['Email', data.email],
			['Phone', data.phone],
			['City', data.city],
			['Country', data.country],
			['Bio / Description', data.description],
			['LinkedIn', data.linkedin],
			['Facebook', data.facebook],
			['Instagram', data.instagram],
			['Twitter', data.twitter],
			['Organization', data.organization],
			['Industry', data.industry],
			['Role', data.role],
			['Degree', data.degree],
			['Branch', data.branch],
			['Graduation Year', data.passing_year],
			['Current Status', data.current_status],
			['Company', data.company],
			['Employment From', data.employment_from],
			['Employment To', data.employment_to],
			['Study Institution', data.study_institution],
			['Study Degree', data.study_degree],
			['Study Branch', data.study_branch],
			['Study From', data.study_from],
			['Study To', data.study_to],
			['Previous Education', data.previous_education],
			['Work From', data.work_from],
			['Work To', data.work_to],
			['Work Location', data.work_location],
			['Skills', data.skills],
			['Registered', data.registered],
		];

		const dynamicProfileFields = Object.entries(data.dynamic_profile_fields || {})
			.map(([key, value]) => [`Profile ${key}`, value]);

		const dynamicProfessionalFields = Object.entries(data.dynamic_professional_fields || {})
			.map(([key, value]) => [`Professional ${key}`, value]);

		const mergedEntries = [...entries, ...dynamicProfileFields, ...dynamicProfessionalFields];

		let photoHtml = '';
		if (data.profile_photo) {
			photoHtml = `<div class="col-span-2 md:col-span-3 lg:col-span-4 flex justify-center mb-4"><img src="${data.profile_photo}" alt="Profile" style="width:150px;height:150px;border-radius:12px;object-fit:contain;object-position:center;background:#f8f9fc;border:1px solid #dde3ec;flex-shrink:0;"></div>`;
		}

		body.innerHTML = photoHtml + mergedEntries.map(([label, value]) => `
			<div class="min-w-0 rounded-lg bg-slate-100 p-3">
				<p class="mb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-700">${label}</p>
				<p class="text-sm text-slate-900" style="white-space: pre-wrap; overflow-wrap: anywhere; word-break: break-word;">${value ?? '-'}</p>
			</div>
		`).join('');

		modal.classList.remove('hidden');
		modal.classList.add('flex');
	}

	// Close modal and clear visual state.
	function closeDetails() {
		const modal = document.getElementById('detailsModal');
		modal.classList.add('hidden');
		modal.classList.remove('flex');
	}
</script>

</body>
</html>
