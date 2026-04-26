<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class NewsController extends Controller
{
    public function adminCreate()
    {
        $actor = Auth::user();

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_news_create_opened',
            ($actor?->name ?? 'Admin') . ' opened create news page',
            []
        );

        return view('admin.news-create');
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'nullable|string',
            'published_at' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $imageFile->getClientOriginalName());
            $imageFile->move(public_path('images'), $imageName);
        }

        News::create([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'] ?? null,
            'published_at' => $validated['published_at'],
            'image' => $imageName,
        ]);

        $actor = Auth::user();

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'news_created',
            ($actor?->name ?? 'Admin') . ' created news: ' . $validated['title'],
            [
                'title' => $validated['title'],
                'published_at' => $validated['published_at'],
                'has_image' => (bool) $imageName,
            ]
        );

        return redirect()->route('admin.news.create')->with('success', 'News item created successfully.');
    }

    
    public function index(Request $request)
    {
        $archive = $request->query('archive');
        $selectedMonth = null;

        $query = News::query()->latest('published_at');

        if ($archive) {
            try {
                $parsed = Carbon::createFromFormat('Y-m', $archive);
                $query->whereYear('published_at', $parsed->year)
                      ->whereMonth('published_at', $parsed->month);
                $selectedMonth = $parsed->format('F Y');
            } catch (\Exception $e) {
                $archive = null;
            }
        }

        $news = $query->get();

        $archives = News::selectRaw("DATE_FORMAT(published_at, '%Y-%m') as month_key, DATE_FORMAT(published_at, '%M %Y') as month_label")
            ->orderByDesc('published_at')
            ->get()
            ->groupBy('month_key')
            ->map(function ($items, $key) {
                return [
                    'label' => $items->first()->month_label,
                    'count' => $items->count(),
                ];
            });

        return view('news.index', compact('news', 'archives', 'selectedMonth', 'archive'));
    }

    public function show($id)
    {
        $news = \App\Models\News::findOrFail($id);

        return view('news.show', compact('news'));
    }

}
