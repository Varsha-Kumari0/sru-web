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
	<div class="px-7 py-8 border-b border-slate-300">
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

		<a href="{{ route('admin.activity-logs') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<path d="M3 3v18h18"/>
				<path d="M8 14l3-3 3 2 4-5"/>
			</svg>
			Activity Logs
		</a>

		<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
			</svg>
			Reports
		</a>

		<p class="text-xs font-semibold tracking-widest uppercase px-3 mb-2 mt-5 text-slate-500">System</p>

		<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<circle cx="12" cy="12" r="3"/>
				<path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M4.93 19.07l1.41-1.41M19.07 19.07l-1.41-1.41M12 2v2M12 20v2M2 12h2M20 12h2"/>
			</svg>
			Settings
		</a>
	</nav>

	<div class="px-4 py-5 border-t border-slate-300">
		<div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50">
			<div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white flex-shrink-0 bg-blue-600">
				{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
			</div>
			<div class="flex-1 min-w-0">
				<p class="text-sm font-semibold truncate text-slate-900">{{ auth()->user()->name ?? 'Administrator' }}</p>
				<p class="text-xs text-slate-500">Super Admin</p>
			</div>
			<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout" class="text-slate-500 hover:text-slate-900">Logout</a>
		</div>
		<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
	</div>
</aside>

{{-- Main content: alumni listing and row actions --}}
<main class="ml-64 flex-1 flex flex-col min-h-screen">
	<header class="sticky top-0 z-40 flex items-center justify-between px-9 py-5 bg-white border-b border-slate-300">
		<div>
			<h2 class="font-display text-2xl font-semibold">All SRU Alumni</h2>
			<p class="text-xs mt-0.5 text-slate-500">{{ now()->format('l, d F Y') }} - Total records: {{ $users->count() }}</p>
		</div>
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
									$displayName = $user->profile?->full_name ?? $user->name;
									$profilePhotoUrl = $user->profile?->profile_photo ? asset('storage/' . $user->profile->profile_photo) : null;
									$initials = strtoupper(substr($displayName, 0, 1));
									$avatarColors = ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4', '#ec4899'];
									$avatarColor = $avatarColors[crc32($displayName) % count($avatarColors)];
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
										'organization' => $user->professional?->organization ?? '-',
										'industry' => $user->professional?->industry ?? '-',
										'role' => $user->professional?->role ?? '-',
										'work_from' => $user->professional?->from ?? '-',
										'work_to' => $user->professional?->to ?? '-',
										'work_location' => $user->professional?->location ?? '-',
										'registered' => $user->created_at?->format('d M Y') ?? '-',
									];
								?>
								<tr class="border-t border-slate-200 hover:bg-slate-50">
									<td class="px-4 py-3">
										<div class="flex items-center gap-2.5">
											@if($profilePhotoUrl)
												<img src="{{ $profilePhotoUrl }}" alt="{{ $displayName }}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;background:#f8f9fc;flex-shrink:0;">
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
	<div class="w-full max-w-xl rounded-xl bg-white border border-slate-300 shadow-xl">
		<div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
			<h3 id="detailsTitle" class="text-lg font-semibold text-slate-900">Alumni Details</h3>
			<button type="button" class="text-slate-500 hover:text-slate-900" onclick="closeDetails()">Close</button>
		</div>
		<div class="max-h-[340px] overflow-y-auto p-5">
			<div id="detailsBody" class="grid grid-cols-2 gap-3"></div>
		</div>
	</div>
</div>

<script>
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
			['Work From', data.work_from],
			['Work To', data.work_to],
			['Work Location', data.work_location],
			['Registered', data.registered],
		];

		let photoHtml = '';
		if (data.profile_photo) {
			photoHtml = `<div class="col-span-2 flex justify-center mb-2"><img src="${data.profile_photo}" alt="Profile" style="width:80px;height:80px;border-radius:10px;object-fit:cover;background:#f8f9fc;border:2px solid #e2e8f0;"></div>`;
		}

		body.innerHTML = photoHtml + entries.map(([label, value]) => `
			<div class="rounded-lg bg-slate-100 p-3">
				<p class="mb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-700">${label}</p>
				<p class="text-sm text-slate-900">${value ?? '-'}</p>
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
