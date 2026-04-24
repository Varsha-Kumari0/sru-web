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

<aside class="w-64 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50 bg-white border-r border-slate-300">
	<div class="px-7 py-8 border-b border-slate-300">
		<h1 class="font-display text-xl font-bold text-sky-400 tracking-[0.02em] leading-tight">SRU<br>Alumni</h1>
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
				<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
			</svg>
			Pending SRU Approvals
			@if($pendingCount > 0)
				<span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-sky-500">{{ $pendingCount }}</span>
			@endif
		</a>

		<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
				<polyline points="22,6 12,13 2,6"/>
			</svg>
			Messages
		</a>

		<a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-150 text-slate-500 hover:text-slate-900">
			<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
				<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
			</svg>
			Reports
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
							<th class="px-4 py-3 text-left">Passing Year</th>
							<th class="px-4 py-3 text-left">Organization</th>
							<th class="px-4 py-3 text-left">Role</th>
							<th class="px-4 py-3 text-left">Location</th>
							<th class="px-4 py-3 text-left">Status</th>
							<th class="px-4 py-3 text-left">Action</th>
						</tr>
					</thead>
					<tbody>
						@forelse($users as $user)
							@php
								$status = ucfirst($user->profile?->status ?? 'pending');
								$displayName = $user->profile?->full_name ?? $user->name;
								$detailPayload = [
									'user_name' => $user->name,
									'full_name' => $displayName,
									'email' => $user->email,
									'phone' => $user->profile?->mobile ?? '-',
									'city' => $user->profile?->city ?? '-',
									'country' => $user->profile?->country ?? '-',
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
									'status' => $status,
									'registered' => $user->created_at?->format('d M Y') ?? '-',
								];
							@endphp
							<tr class="border-t border-slate-200 hover:bg-slate-50">
								<td class="px-4 py-3 font-medium text-slate-900">{{ $displayName }}</td>
								<td class="px-4 py-3 text-slate-700">{{ $user->email }}</td>
								<td class="px-4 py-3 text-slate-700">{{ $user->profile?->mobile ?? '-' }}</td>
								<td class="px-4 py-3 text-slate-700">{{ $user->profile?->branch ?? '-' }}</td>
								<td class="px-4 py-3 text-slate-700">{{ $user->profile?->passing_year ?? '-' }}</td>
								<td class="px-4 py-3 text-slate-700">{{ $user->professional?->organization ?? '-' }}</td>
								<td class="px-4 py-3 text-slate-700">{{ $user->professional?->role ?? '-' }}</td>
								<td class="px-4 py-3 text-slate-700">{{ $user->professional?->location ?? '-' }}</td>
								<td class="px-4 py-3">
									<span class="px-2 py-1 rounded-full text-xs font-semibold {{ $status === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
										{{ $status }}
									</span>
								</td>
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

										@if($status === 'Pending')
											<form method="POST" action="{{ route('admin.alumni.approve', $user->id) }}">
												@csrf
												@method('PUT')
												<button type="submit" class="px-2.5 py-1.5 text-xs font-medium rounded-md bg-emerald-100 text-emerald-700 hover:bg-emerald-200">
													Approve
												</button>
											</form>
										@endif

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
						@empty
							<tr>
								<td colspan="10" class="px-4 py-8 text-center text-slate-500">No alumni records found.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</main>

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
	function openDetails(data) {
		const modal = document.getElementById('detailsModal');
		const title = document.getElementById('detailsTitle');
		const body = document.getElementById('detailsBody');
		title.textContent = data.full_name || data.user_name || 'Alumni Details';

		const entries = [
			['Full Name', data.full_name],
			['Email', data.email],
			['Phone', data.phone],
			['City', data.city],
			['Country', data.country],
			['Organization', data.organization],
			['Industry', data.industry],
			['Role', data.role],
			['Degree', data.degree],
			['Branch', data.branch],
			['Passing Year', data.passing_year],
			['Current Status', data.current_status],
			['Company', data.company],
			['Work From', data.work_from],
			['Work To', data.work_to],
			['Work Location', data.work_location],
			['Status', data.status],
			['Registered', data.registered],
		];

		body.innerHTML = entries.map(([label, value]) => `
			<div class="rounded-lg bg-slate-100 p-3">
				<p class="mb-1 text-[10px] font-semibold uppercase tracking-widest text-slate-700">${label}</p>
				<p class="text-sm text-slate-900">${value ?? '-'}</p>
			</div>
		`).join('');

		modal.classList.remove('hidden');
		modal.classList.add('flex');
	}

	function closeDetails() {
		const modal = document.getElementById('detailsModal');
		modal.classList.add('hidden');
		modal.classList.remove('flex');
	}
</script>

</body>
</html>
