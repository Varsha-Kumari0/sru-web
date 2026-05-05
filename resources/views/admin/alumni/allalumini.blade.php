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
@include('admin.partials.sidebar', ['activeSection' => 'alumni'])

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
							<th class="px-4 py-3 text-left">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if ($users->isEmpty()): ?>
							<tr>
								<td colspan="9" class="px-4 py-8 text-center text-slate-500">No alumni records found.</td>
							</tr>
						<?php else: ?>
							<?php foreach ($users as $user): ?>
								<?php
									$professionalRecords = collect($user->professionals ?? [])
										->sortByDesc(function ($record) {
											return $record->from ?? $record->created_at;
										})
										->values();

									$primaryProfessional = $professionalRecords->first() ?? $user->professional;

									$workExperiencesText = $professionalRecords
										->map(function ($record) {
											return implode(' | ', [
												$record->organization ?? '',
												$record->industry ?? '',
												$record->role ?? '',
												trim((string) ($record->from ?? '') . ' - ' . (string) ($record->to ?? 'Present')),
												$record->location ?? '',
											]);
										})
										->filter()
										->implode("\n");

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

									$achievementsText = collect($user->achievements ?? [])
										->map(function ($achievement) {
											return implode(' | ', [
												$achievement->title ?? '',
												$achievement->category ?? '',
												$achievement->earned_at ? $achievement->earned_at->format('d M Y') : '',
											]);
										})
										->filter()
										->values()
										->implode("\n");
									$achievementsText = $achievementsText !== '' ? $achievementsText : '-';

									$knownProfileFields = [
										'id',
										'user_id',
										'created_at',
										'updated_at',
										'profile_photo',
										'first_name',
										'last_name',
										'gender',
										'full_name',
										'father_name',
										'mobile',
										'contact_email',
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
										'pursuing_educational_level',
										'highest_completed_educational_level',
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
										'first_name' => $user->profile?->first_name ?? '-',
										'last_name' => $user->profile?->last_name ?? '-',
										'gender' => $user->profile?->gender ?? '-',
										'full_name' => $displayName,
										'profile_photo' => $profilePhotoUrl,
										'email' => $user->email,
										'contact_email' => $user->profile?->contact_email ?? '-',
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
										'pursuing_educational_level' => $user->profile?->pursuing_educational_level ?? '-',
										'highest_completed_educational_level' => $user->profile?->highest_completed_educational_level ?? '-',
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
										'organization' => $primaryProfessional?->organization ?? '-',
										'industry' => $primaryProfessional?->industry ?? '-',
										'role' => $primaryProfessional?->role ?? '-',
										'work_from' => $primaryProfessional?->from ?? '-',
										'work_to' => $primaryProfessional?->to ?? '-',
										'work_location' => $primaryProfessional?->location ?? '-',
										'work_experiences' => $workExperiencesText !== '' ? $workExperiencesText : '-',
										'skills' => $skillsText,
										'achievements' => $achievementsText,
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
									<td class="px-4 py-3 text-slate-700">{{ $primaryProfessional?->organization ?? '-' }}</td>
									<td class="px-4 py-3 text-slate-700">{{ $primaryProfessional?->role ?? '-' }}</td>
									<td class="px-4 py-3 text-slate-700">{{ $primaryProfessional?->location ?? '-' }}</td>
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
			['Username', data.user_name],
			['Full Name', data.full_name],
			['First Name', data.first_name],
			['Last Name', data.last_name],
			['Gender', data.gender],
			['Father Name', data.father_name],
			['Email', data.email],
			['Contact Email', data.contact_email],
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
			['Pursuing Educational Level', data.pursuing_educational_level],
			['Highest Completed Educational Level', data.highest_completed_educational_level],
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
			['All Work Experiences', data.work_experiences],
			['Skills', data.skills],
			['Achievements', data.achievements],
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
