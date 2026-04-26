<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;


class NewsController extends Controller
{
    private function validateNews(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string',
            'content' => 'nullable|string',
            'published_at' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    }

    private function storeNewsImage(Request $request, ?string $existingImage = null): ?string
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

        $recentUpdatedNews = News::query()
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'title', 'excerpt', 'published_at', 'updated_at']);

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_news_create_opened',
            ($actor?->name ?? 'Admin') . ' opened create news page',
            []
        );

        return view('admin.news.news-create', compact('recentUpdatedNews'));
    }

    public function adminManage(Request $request)
    {
        $mode = $request->query('mode', 'update');

        $newsItems = News::query()
            ->latest('updated_at')
            ->get();

        return view('admin.news.news-manage', compact('newsItems', 'mode'));
    }

    public function adminEdit($id)
    {
        $news = News::query()->findOrFail($id);

        return view('admin.news.news-edit', compact('news'));
    }

    public function adminStore(Request $request)
    {
        $validated = $this->validateNews($request);

        $imageName = $this->storeNewsImage($request);

        $news = News::create([
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
                'news_id' => $news->id,
                'title' => $validated['title'],
                'published_at' => $validated['published_at'],
                'has_image' => (bool) $imageName,
            ]
        );

        return redirect()->route('admin.news.create')->with('success', 'News item created successfully.');
    }

    public function adminUpdate(Request $request, $id)
    {
        $news = News::query()->findOrFail($id);
        $validated = $this->validateNews($request);
        $imageName = $this->storeNewsImage($request, $news->image);

        $originalData = $news->only(['title', 'excerpt', 'content', 'published_at', 'image']);

        $news->update([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'] ?? null,
            'published_at' => $validated['published_at'],
            'image' => $imageName,
        ]);

        $actor = Auth::user();
        $updatedData = $news->fresh()?->only(['title', 'excerpt', 'content', 'published_at', 'image']) ?? [];
        $changes = [];

        foreach (['title', 'excerpt', 'content', 'published_at', 'image'] as $field) {
            $oldValue = (string) ($originalData[$field] ?? '');
            $newValue = (string) ($updatedData[$field] ?? '');

            if ($oldValue === $newValue) {
                continue;
            }

            $changes[] = [
                'field' => ucfirst(str_replace('_', ' ', $field)),
                'from' => $oldValue === '' ? 'Empty' : $oldValue,
                'to' => $newValue === '' ? 'Empty' : $newValue,
            ];
        }

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'news_updated',
            ($actor?->name ?? 'Admin') . ' updated news: ' . $news->title,
            [
                'news_id' => $news->id,
                'changes' => $changes,
            ]
        );

        return redirect()->route('admin.news.manage', ['mode' => 'update'])->with('success', 'News item updated successfully.');
    }

    public function adminDestroy($id)
    {
        $news = News::query()->findOrFail($id);
        $actor = Auth::user();

        if ($news->image) {
            $imagePath = public_path('images/' . $news->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'news_deleted',
            ($actor?->name ?? 'Admin') . ' deleted news: ' . $news->title,
            [
                'news_id' => $news->id,
                'title' => $news->title,
            ]
        );

        $news->delete();

        return redirect()->route('admin.news.manage', ['mode' => 'delete'])->with('success', 'News item deleted successfully.');
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
