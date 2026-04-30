<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Update Gallery Item - SRU Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .nav-active { background: rgba(59,130,246,.14); color: #1d4ed8 !important; }
    </style>
</head>
<body class="min-h-screen flex bg-slate-50 text-slate-900 [background-image:radial-gradient(ellipse_at_10%_20%,rgba(59,130,246,.08)_0%,transparent_60%),radial-gradient(ellipse_at_90%_80%,rgba(148,163,184,.12)_0%,transparent_60%)]">
@include('admin.partials.sidebar', ['activeSection' => 'gallery'])

<main class="ml-64 flex-1 flex flex-col min-h-screen">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold">Update Gallery Item</h2>
            <p class="text-xs mt-0.5 text-slate-500">Edit and save {{ strtolower($sectionLabel) }} content.</p>
        </div>
        <a href="{{ route('admin.gallery.manage', ['section' => $section]) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Back to Manage</a>
    </header>

    <div class="p-9">
        <div class="max-w-4xl rounded-xl border border-slate-300 bg-white p-6">
            @if($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="mb-1 font-semibold">Please fix the following:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.gallery.update', ['section' => $section, 'id' => $item->id]) }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $item->title) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>

                <div>
                    <label for="summary" class="mb-1.5 block text-sm font-semibold text-slate-700">Summary</label>
                    <textarea id="summary" name="summary" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('summary', $item->summary) }}</textarea>
                </div>

                @if($section === 'albums')
                    @php $existingPhotos = $item->photos; @endphp
                    @if($existingPhotos->isNotEmpty())
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Existing Photos ({{ $existingPhotos->count() }})</label>
                            <div class="grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-6">
                                @foreach($existingPhotos as $photo)
                                    <div class="relative">
                                        <img src="{{ asset('images/albums/' . $photo->file_name) }}" alt="Photo" class="h-20 w-full rounded-lg object-cover ring-1 ring-slate-200">
                                        <label class="absolute top-1 right-1 cursor-pointer">
                                            <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="sr-only peer">
                                            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-white/90 text-xs text-red-600 ring-1 ring-red-300 peer-checked:bg-red-600 peer-checked:text-white">✕</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-1 text-xs text-slate-400">Click the ✕ on a photo to mark it for deletion.</p>
                        </div>
                    @endif
                    <div>
                        <label for="photos" class="mb-1.5 block text-sm font-semibold text-slate-700">Add More Photos <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/jpg,image/webp" multiple
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        <p id="photo-count-hint" class="mt-1 text-xs text-slate-400">No new photos selected.</p>
                    </div>
                @endif

                @if($section === 'videos')
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="video_url" class="mb-1.5 block text-sm font-semibold text-slate-700">Video URL <span class="text-red-500">*</span></label>
                            <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $item->video_url) }}" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label for="duration" class="mb-1.5 block text-sm font-semibold text-slate-700">Duration</label>
                            <input type="text" id="duration" name="duration" value="{{ old('duration', $item->duration) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="published_at" class="mb-1.5 block text-sm font-semibold text-slate-700">Publish Date</label>
                        <input type="date" id="published_at" name="published_at" value="{{ old('published_at', optional($item->published_at)->toDateString()) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="display_order" class="mb-1.5 block text-sm font-semibold text-slate-700">Display Order</label>
                        <input type="number" min="0" id="display_order" name="display_order" value="{{ old('display_order', $item->display_order) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label for="image" class="mb-1.5 block text-sm font-semibold text-slate-700">{{ $section === 'albums' ? 'Replace Cover Image' : ($section === 'videos' ? 'Replace Thumbnail Image' : 'Replace Image') }}</label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600">
                        Active
                    </label>

                    @if($section === 'albums')
                        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600">
                            Featured Album
                        </label>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.gallery.manage', ['section' => $section]) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</main>
<script>
    const photosInput = document.getElementById('photos');
    if (photosInput) {
        photosInput.addEventListener('change', function () {
            const hint = document.getElementById('photo-count-hint');
            const count = photosInput.files.length;
            hint.textContent = count > 0 ? count + ' new photo' + (count !== 1 ? 's' : '') + ' selected.' : 'No new photos selected.';
        });
    }
</script>
</body>
</html>
