<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\GalleryAlbum;
use App\Models\GalleryAlbumPhoto;
use App\Models\GalleryVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class GalleryAdminController extends Controller
{
    private function sectionMap(): array
    {
        return [
            'albums' => [
                'label' => 'Albums',
                'model' => GalleryAlbum::class,
                'image_field' => 'cover_image',
                'title_field' => 'title',
            ],
            'videos' => [
                'label' => 'Videos',
                'model' => GalleryVideo::class,
                'image_field' => 'thumbnail_image',
                'title_field' => 'title',
            ],
        ];
    }

    private function resolveSection(?string $section): string
    {
        $sections = array_keys($this->sectionMap());

        return in_array($section, $sections, true) ? $section : 'albums';
    }

    private function validateRequest(Request $request, string $section): array
    {
        $request->validate([
            'section' => ['nullable', Rule::in(array_keys($this->sectionMap()))],
        ]);

        if ($section === 'albums') {
            return $request->validate([
                'title' => 'required|string|max:255',
                'summary' => 'nullable|string',
                'published_at' => 'nullable|date',
                'display_order' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'photos' => 'nullable|array',
                'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
                'delete_photos' => 'nullable|array',
                'delete_photos.*' => 'integer',
            ]);
        }

        if ($section === 'videos') {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'summary' => 'nullable|string',
                'video_url' => 'required|string|max:500',
                'duration' => 'nullable|string|max:20',
                'published_at' => 'nullable|date',
                'display_order' => 'nullable|integer|min:0',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $videoUrl = trim((string) ($validated['video_url'] ?? ''));
            if ($videoUrl !== '' && ! preg_match('#^https?://#i', $videoUrl)) {
                $videoUrl = 'https://' . $videoUrl;
            }

            if ($videoUrl !== '' && ! filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                return $request->validate([
                    'video_url' => 'required|url|max:500',
                ]);
            }

            $validated['video_url'] = $videoUrl !== '' ? $videoUrl : null;

            return $validated;
        }

        return $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'published_at' => 'nullable|date',
            'display_order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'integer',
        ]);
    }

    private function storeImage(Request $request, string $imageField, ?string $existingImage = null): ?string
    {
        if (!$request->hasFile('image')) {
            return $existingImage;
        }

        if ($existingImage) {
            $existingPath = public_path('images/' . $existingImage);
            if (File::exists($existingPath)) {
                File::delete($existingPath);
            }
        }

        $imageFile = $request->file('image');
        $imageName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $imageFile->getClientOriginalName());
        $imageFile->move(public_path('images'), $imageName);

        return $imageName;
    }

    private function listSectionItems(string $section)
    {
        $sectionInfo = $this->sectionMap()[$section];
        $modelClass = $sectionInfo['model'];

        return $modelClass::query()->latest('updated_at')->get();
    }

    private function buildPayload(Request $request, string $section, array $validated): array
    {
        if ($section === 'albums') {
            return [
                'title' => $validated['title'],
                'summary' => $validated['summary'] ?? null,
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active', true),
                'published_at' => $validated['published_at'] ?? null,
                'display_order' => (int) ($validated['display_order'] ?? 0),
            ];
        }

        if ($section === 'videos') {
            return [
                'title' => $validated['title'],
                'summary' => $validated['summary'] ?? null,
                'video_url' => $validated['video_url'] ?? null,
                'duration' => $validated['duration'] ?? null,
                'is_active' => $request->boolean('is_active', true),
                'published_at' => $validated['published_at'] ?? null,
                'display_order' => (int) ($validated['display_order'] ?? 0),
            ];
        }

        return [
            'title' => $validated['title'],
            'summary' => $validated['summary'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
            'published_at' => $validated['published_at'] ?? null,
            'display_order' => (int) ($validated['display_order'] ?? 0),
        ];
    }

    public function adminCreate(Request $request)
    {
        $section = $this->resolveSection($request->query('section'));
        $sections = $this->sectionMap();
        $sectionLabel = $sections[$section]['label'];

        $recentItems = $this->listSectionItems($section)->take(6);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_gallery_create_opened',
            ($actor?->name ?? 'Admin') . ' opened gallery create page (' . strtolower($sectionLabel) . ')',
            [
                'section' => $section,
            ]
        );

        return view('admin.gallery.gallery-create', compact('section', 'sections', 'sectionLabel', 'recentItems'));
    }

    public function adminManage(Request $request)
    {
        $section = $this->resolveSection($request->query('section'));
        $sections = $this->sectionMap();
        $sectionLabel = $sections[$section]['label'];
        $items = $this->listSectionItems($section);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_gallery_manage_opened',
            ($actor?->name ?? 'Admin') . ' opened gallery manage page (' . strtolower($sectionLabel) . ')',
            [
                'section' => $section,
            ]
        );

        return view('admin.gallery.gallery-manage', compact('section', 'sections', 'sectionLabel', 'items'));
    }

    public function adminEdit(string $section, int $id)
    {
        $section = $this->resolveSection($section);
        $sections = $this->sectionMap();
        $sectionLabel = $sections[$section]['label'];

        $modelClass = $sections[$section]['model'];
        $item = $modelClass::query()->findOrFail($id);

        $actor = Auth::user();
        $itemTitle = (string) ($item->{$sections[$section]['title_field']} ?? 'Gallery Item');
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_gallery_edit_opened',
            ($actor?->name ?? 'Admin') . ' opened gallery edit page for ' . strtolower($sectionLabel) . ': ' . $itemTitle,
            [
                'section' => $section,
                'item_id' => $id,
                'title' => $itemTitle,
            ]
        );

        return view('admin.gallery.gallery-edit', compact('section', 'sections', 'sectionLabel', 'item'));
    }

    public function adminStore(Request $request, string $section)
    {
        $section = $this->resolveSection($section);
        $sections = $this->sectionMap();
        $sectionInfo = $sections[$section];
        $modelClass = $sectionInfo['model'];

        $validated = $this->validateRequest($request, $section);
        $payload = $this->buildPayload($request, $section, $validated);
        $payload[$sectionInfo['image_field']] = $this->storeImage($request, $sectionInfo['image_field']);

        $item = $modelClass::create($payload);

        if ($section === 'albums' && $request->hasFile('photos')) {
            $order = 0;
            foreach ($request->file('photos') as $photo) {
                $photoName = time() . '_' . $order . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $photo->getClientOriginalName());
                $photo->move(public_path('images/albums'), $photoName);
                GalleryAlbumPhoto::create([
                    'gallery_album_id' => $item->id,
                    'file_name' => $photoName,
                    'display_order' => $order++,
                ]);
            }
            $item->update(['photo_count' => $item->photos()->count()]);
        }

        $actor = Auth::user();
        $itemTitle = (string) ($item->{$sectionInfo['title_field']} ?? 'Gallery Item');

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'gallery_' . rtrim($section, 's') . '_created',
            ($actor?->name ?? 'Admin') . ' created ' . strtolower($sectionInfo['label']) . ': ' . $itemTitle,
            [
                'section' => $section,
                'item_id' => $item->id,
                'title' => $itemTitle,
            ]
        );

        return redirect()
            ->route('admin.gallery.create', ['section' => $section])
            ->with('success', $sectionInfo['label'] . ' item created successfully.');
    }

    public function adminUpdate(Request $request, string $section, int $id)
    {
        $section = $this->resolveSection($section);
        $sections = $this->sectionMap();
        $sectionInfo = $sections[$section];
        $modelClass = $sectionInfo['model'];

        $item = $modelClass::query()->findOrFail($id);
        $validated = $this->validateRequest($request, $section);
        $payload = $this->buildPayload($request, $section, $validated);
        $payload[$sectionInfo['image_field']] = $this->storeImage($request, $sectionInfo['image_field'], $item->{$sectionInfo['image_field']});

        $item->update($payload);

        if ($section === 'albums') {
            // Delete selected photos
            if (!empty($validated['delete_photos'])) {
                $toDelete = GalleryAlbumPhoto::query()
                    ->where('gallery_album_id', $item->id)
                    ->whereIn('id', $validated['delete_photos'])
                    ->get();
                foreach ($toDelete as $photo) {
                    $path = public_path('images/albums/' . $photo->file_name);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                    $photo->delete();
                }
            }
            // Store new uploaded photos
            if ($request->hasFile('photos')) {
                $order = (int) ($item->photos()->max('display_order') ?? -1) + 1;
                foreach ($request->file('photos') as $photo) {
                    $photoName = time() . '_' . $order . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $photo->getClientOriginalName());
                    $photo->move(public_path('images/albums'), $photoName);
                    GalleryAlbumPhoto::create([
                        'gallery_album_id' => $item->id,
                        'file_name' => $photoName,
                        'display_order' => $order++,
                    ]);
                }
            }
            // Sync photo count
            $item->update(['photo_count' => $item->photos()->count()]);
        }

        $actor = Auth::user();
        $itemTitle = (string) ($item->{$sectionInfo['title_field']} ?? 'Gallery Item');

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'gallery_' . rtrim($section, 's') . '_updated',
            ($actor?->name ?? 'Admin') . ' updated ' . strtolower($sectionInfo['label']) . ': ' . $itemTitle,
            [
                'section' => $section,
                'item_id' => $item->id,
                'title' => $itemTitle,
            ]
        );

        return redirect()
            ->route('admin.gallery.manage', ['section' => $section])
            ->with('success', $sectionInfo['label'] . ' item updated successfully.');
    }

    public function adminDestroy(string $section, int $id)
    {
        $section = $this->resolveSection($section);
        $sections = $this->sectionMap();
        $sectionInfo = $sections[$section];
        $modelClass = $sectionInfo['model'];

        $item = $modelClass::query()->findOrFail($id);
        $itemTitle = (string) ($item->{$sectionInfo['title_field']} ?? 'Gallery Item');

        $imageField = $sectionInfo['image_field'];
        if ($item->{$imageField}) {
            $imagePath = public_path('images/' . $item->{$imageField});
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Delete all album photos from disk
        if ($section === 'albums') {
            foreach ($item->photos as $photo) {
                $path = public_path('images/albums/' . $photo->file_name);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
        }

        $item->delete();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'gallery_' . rtrim($section, 's') . '_deleted',
            ($actor?->name ?? 'Admin') . ' deleted ' . strtolower($sectionInfo['label']) . ': ' . $itemTitle,
            [
                'section' => $section,
                'title' => $itemTitle,
                'item_id' => $id,
            ]
        );

        return redirect()
            ->route('admin.gallery.manage', ['section' => $section])
            ->with('success', $sectionInfo['label'] . ' item deleted successfully.');
    }
}
