<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $query = Event::query();

        if ($currentFilter === 'upcoming') {
            $query->where('start_at', '>=', $now)->orderBy('start_at', 'asc');
        } elseif ($currentFilter === 'past') {
            $query->where('start_at', '<', $now)->orderBy('start_at', 'desc');
        } else {
            $query->orderBy('start_at', 'desc');
        }

        $events = $query->get();

        $eventTypes = [
            'reunions' => 'Reunions',
            'webinars' => 'Webinars',
            'hackathons' => 'Hackathons',
            'campus-events' => 'Campus Events',
        ];

        $typeCounts = Event::selectRaw('event_type, count(*) as total')
            ->groupBy('event_type')
            ->pluck('total', 'event_type')
            ->toArray();

        return view('events.index', compact('events', 'allowedFilters', 'currentFilter', 'eventTypes', 'typeCounts', 'now'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', compact('event'));
    }
}
