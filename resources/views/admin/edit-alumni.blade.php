<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Alumni - SRU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }

        .nav-active {
            background: rgba(59,130,246,.14);
            color: #1d4ed8 !important;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.15s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-section {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
        }

        .form-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .button-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .error-text {
            color: #dc2626;
            font-size: 13px;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body class="min-h-screen flex bg-slate-50 text-slate-900">

{{-- Sidebar: shared admin navigation --}}
<aside class="w-64 min-h-screen flex flex-col fixed left-0 top-0 bottom-0 z-50 bg-white border-r border-slate-300">
    <div class="px-7 py-8 border-b border-slate-300">
        <h1 class="font-display text-xl font-bold text-sky-400 tracking-[0.02em] leading-tight">
            SRU<br>Alumni
        </h1>
        <span class="text-xs font-semibold tracking-widest uppercase mt-1 block text-slate-500">
            Admin Control
        </span>
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
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout" class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 transition-all duration-150 hover:bg-slate-100 hover:text-slate-900">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="transition-colors duration-150">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </a>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</aside>

{{-- Main Content: admin edit form for selected alumni --}}
<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-9 py-5 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Edit Alumni</h2>
            <p class="text-xs mt-0.5 text-slate-500">
                {{ $user->profile?->full_name ?? $user->name }} — Update profile and professional details
            </p>
        </div>
    </header>

    <div class="p-9 flex-1">
        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="alert alert-error">
                <strong>Please correct the errors below:</strong>
                <ul style="margin-top: 0.5rem; margin-left: 1.5rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.alumni.update', $user->id) }}" class="max-w-4xl" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- User Information Section --}}
            <div class="form-section">
                <h3>👤 User Account Information</h3>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="name">Username / Full Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Profile Information Section --}}
            <div class="form-section">
                <h3>📋 Profile Information</h3>

                {{-- Profile Photo --}}
                <div class="form-group">
                    <label>Profile Photo</label>
                    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:0.75rem;">
                        @if($user->profile?->profile_photo)
                            <img id="currentPhotoPreview" src="{{ asset('storage/' . $user->profile->profile_photo) }}" alt="Current photo" style="width:80px;height:80px;border-radius:10px;object-fit:cover;background:#f8f9fc;border:2px solid #e2e8f0;">
                        @else
                            <div id="currentPhotoPreview" style="width:80px;height:80px;border-radius:10px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#94a3b8;">
                                {{ strtoupper(substr($user->profile?->full_name ?? $user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/jpg" style="font-size:13px;" onchange="previewPhoto(event)">
                            <p style="margin-top:0.4rem;font-size:12px;color:#94a3b8;">JPG or PNG, max 2MB. Leave empty to keep current photo.</p>
                        </div>
                    </div>
                    @error('profile_photo')<span class="error-text">{{ $message }}</span>@enderror
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->profile?->full_name ?? '') }}">
                        @error('full_name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile Number</label>
                        <input type="text" id="mobile" name="mobile" value="{{ old('mobile', $user->profile?->mobile ?? '') }}">
                        @error('mobile')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $user->profile?->city ?? '') }}">
                        @error('city')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="{{ old('country', $user->profile?->country ?? '') }}">
                        @error('country')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>

                <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600; font-size: 14px; color: #475569;">Academic Information</h4>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="degree">Degree</label>
                        <input type="text" id="degree" name="degree" value="{{ old('degree', $user->profile?->degree ?? '') }}">
                        @error('degree')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="branch">Branch / Department</label>
                        <input type="text" id="branch" name="branch" value="{{ old('branch', $user->profile?->branch ?? '') }}">
                        @error('branch')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="passing_year">Passing Year</label>
                        <input type="number" id="passing_year" name="passing_year" min="1900" max="2099" value="{{ old('passing_year', $user->profile?->passing_year ?? '') }}" placeholder="e.g., 2020" title="Enter a 4-digit year between 1900 and 2099">
                        @error('passing_year')<span class="error-text">Please enter a valid 4-digit year</span>@enderror
                    </div>
                </div>

                <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600; font-size: 14px; color: #475569;">Current Employment</h4>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="current_status">Current Status</label>
                        <input type="text" id="current_status" name="current_status" value="{{ old('current_status', $user->profile?->current_status ?? '') }}" placeholder="e.g., Working, Studying">
                        @error('current_status')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" value="{{ old('company', $user->profile?->company ?? '') }}">
                        @error('company')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Professional Information Section --}}
            <div class="form-section">
                <h3>💼 Professional Details</h3>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="organization">Organization</label>
                        <input type="text" id="organization" name="organization" value="{{ old('organization', $user->professional?->organization ?? '') }}">
                        @error('organization')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="industry">Industry</label>
                        <input type="text" id="industry" name="industry" value="{{ old('industry', $user->professional?->industry ?? '') }}">
                        @error('industry')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="role">Job Role / Position</label>
                        <input type="text" id="role" name="role" value="{{ old('role', $user->professional?->role ?? '') }}">
                        @error('role')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="location">Work Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $user->professional?->location ?? '') }}">
                        @error('location')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="from">Work From (Date) 📅</label>
                        <input type="date" id="from" name="from" value="{{ old('from', $user->professional?->from ?? '') }}" title="Select start date">
                        @error('from')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="to">Work To (Date) 📅</label>
                        <input type="date" id="to" name="to" value="{{ old('to', $user->professional?->to !== 'Present' ? $user->professional?->to : '') }}" title="Select end date or use 'Currently Working' checkbox">
                        @error('to')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>

                {{-- Currently Working Checkbox --}}
                <div style="margin-top: 1rem; padding: 1rem; background: #dbeafe; border-radius: 0.5rem; border: 1px solid #7dd3fc;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <input type="checkbox" id="is_current" name="is_current" value="1" 
                               {{ old('is_current', $user->professional?->to === 'Present') ? 'checked' : '' }}
                               onchange="toggleToField()">
                        <label for="is_current" style="margin: 0; cursor: pointer; font-weight: 500; color: #0369a1;">
                            ✓ Currently Working Here (Sets end date to "Present")
                        </label>
                    </div>
                </div>
            </div>

            {{-- Button Group --}}
            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Save Changes
                </button>
                <a href="{{ route('admin.allalumini') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</main>

<script>
// Show a local preview when admin selects a new profile image.
function previewPhoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    const preview = document.getElementById('currentPhotoPreview');
    const reader = new FileReader();
    reader.onload = function(e) {
        if (preview.tagName === 'IMG') {
            preview.src = e.target.result;
        } else {
            const img = document.createElement('img');
            img.id = 'currentPhotoPreview';
            img.src = e.target.result;
            img.alt = 'New photo';
            img.style = 'width:80px;height:80px;border-radius:10px;object-fit:cover;background:#f8f9fc;border:2px solid #e2e8f0;';
            preview.replaceWith(img);
        }
    };
    reader.readAsDataURL(file);
}

// Disable "to" date when currently working is checked.
function toggleToField() {
    const checkbox = document.getElementById('is_current');
    const toField = document.getElementById('to');
    
    if (checkbox.checked) {
        toField.disabled = true;
        toField.value = '';
        toField.style.backgroundColor = '#f1f5f9';
        toField.style.cursor = 'not-allowed';
    } else {
        toField.disabled = false;
        toField.style.backgroundColor = '';
        toField.style.cursor = 'pointer';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleToField();
});
</script>

</body>
</html>
