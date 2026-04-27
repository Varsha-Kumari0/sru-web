<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $allowedFilters = [
            'all' => 'All',
            'upcoming' => 'Upcoming',
            'past' => 'Past',
        ];

        $currentFilter = array_key_exists($filter, $allowedFilters) ? $filter : 'all';
        $now = Carbon::now();

        $eventTypes = [
            'reunions' => 'Reunions',
            'webinars' => 'Webinars',
            'hackathons' => 'Hackathons',
            'campus-events' => 'Campus Events',
        ];

        $selectedType = $request->query('type');
        $currentType = array_key_exists($selectedType, $eventTypes) ? $selectedType : null;

        $query = Event::query();

        if ($currentType) {
            $query->where('event_type', $currentType);
        }

        if ($currentFilter === 'upcoming') {
            $query->where('start_at', '>=', $now)->orderBy('start_at', 'asc');
        } elseif ($currentFilter === 'past') {
            $query->where('start_at', '<', $now)->orderBy('start_at', 'desc');
        } else {
            $query->orderBy('start_at', 'desc');
        }

        $events = $query->get();

        $typeCounts = Event::selectRaw('event_type, count(*) as total')
            ->groupBy('event_type')
            ->pluck('total', 'event_type')
            ->toArray();

        return view('events.index', compact('events', 'allowedFilters', 'currentFilter', 'eventTypes', 'typeCounts', 'now', 'currentType'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', compact('event'));
    }

    // ─── Admin CRUD ───────────────────────────────────────────────────────────

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'title'             => 'required|string|max:255',
            'excerpt'           => 'nullable|string',
            'description'       => 'nullable|string',
            'event_type'        => 'required|string|in:reunions,webinars,hackathons,campus-events',
            'location'          => 'nullable|string|max:255',
            'start_at'          => 'required|date',
            'end_at'            => 'nullable|date|after_or_equal:start_at',
            'registration_link' => 'nullable|url|max:500',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    private function storeEventImage(Request $request, ?string $existingImage = null): ?string
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

    public function adminCreate()
    {
        $actor = Auth::user();

        $recentEvents = Event::query()
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'title', 'excerpt', 'start_at', 'created_at', 'updated_at']);

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_event_create_opened',
            ($actor?->name ?? 'Admin') . ' opened create event page',
            []
        );

        return view('admin.events.event-create', compact('recentEvents'));
    }

    public function adminStore(Request $request)
    {
        $validated = $this->validateEvent($request);
        $validated['image'] = $this->storeEventImage($request);

        $event = Event::create($validated);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'event_created',
            ($actor?->name ?? 'Admin') . ' created event: ' . $event->title,
            ['event_id' => $event->id, 'title' => $event->title]
        );

        return redirect()->route('admin.events.create')->with('success', 'Event created successfully.');
    }

    public function adminManage()
    {
        $events = Event::query()->latest('start_at')->get();

        return view('admin.events.event-manage', compact('events'));
    }

    public function adminEdit($id)
    {
        $event = Event::query()->findOrFail($id);

        return view('admin.events.event-edit', compact('event'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $event = Event::query()->findOrFail($id);
        $validated = $this->validateEvent($request);
        $validated['image'] = $this->storeEventImage($request, $event->image);

        $event->update($validated);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'event_updated',
            ($actor?->name ?? 'Admin') . ' updated event: ' . $event->title,
            ['event_id' => $event->id]
        );

        return redirect()->route('admin.events.manage')->with('success', 'Event updated successfully.');
    }

    public function adminDestroy($id)
    {
        $event = Event::query()->findOrFail($id);

        if ($event->image) {
            $imagePath = public_path('images/' . $event->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'event_deleted',
            ($actor?->name ?? 'Admin') . ' deleted event: ' . $event->title,
            ['event_id' => $event->id, 'title' => $event->title]
        );

        $event->delete();

        return redirect()->route('admin.events.manage')->with('success', 'Event deleted successfully.');
    }
}
