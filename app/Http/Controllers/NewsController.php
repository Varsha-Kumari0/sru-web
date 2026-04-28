<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\News;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\View\View;


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

    public function adminCreate(): View
    {
        $actor = Auth::user();

        $recentUpdatedNews = News::query()
            ->latest('updated_at')
            ->limit(6)
            ->get(['id', 'title', 'excerpt', 'published_at', 'created_at', 'updated_at']);

        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_news_create_opened',
            ($actor?->name ?? 'Admin') . ' opened create news page',
            []
        );

        return view('admin.news.news-create', compact('recentUpdatedNews'));
    }

    public function adminManage(Request $request): View
    {
        $mode = $request->query('mode', 'update');

        $newsItems = News::query()
            ->latest('updated_at')
            ->get();

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_news_manage_opened',
            ($actor?->name ?? 'Admin') . ' opened manage news page',
            [
                'mode' => $mode,
            ]
        );

        return view('admin.news.news-manage', compact('newsItems', 'mode'));
    }

    public function adminEdit(int $id): View
    {
        $news = News::query()->findOrFail($id);

        $actor = Auth::user();
        ActivityLog::record(
            $actor?->id,
            $actor?->id,
            'admin_news_edit_opened',
            ($actor?->name ?? 'Admin') . ' opened edit news page for: ' . $news->title,
            [
                'news_id' => $news->id,
                'title' => $news->title,
            ]
        );

        return view('admin.news.news-edit', compact('news'));
    }

    public function adminStore(Request $request): RedirectResponse
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

    public function adminUpdate(Request $request, int $id): RedirectResponse
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

    public function adminDestroy(int $id): RedirectResponse
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

        News::query()->whereKey($news->getKey())->delete();

        return redirect()->route('admin.news.manage', ['mode' => 'delete'])->with('success', 'News item deleted successfully.');
    }

    
    public function index(Request $request): View
    {
        $archive = $request->query('archive');
        $selectedMonth = null;

        $query = News::query()->latest('published_at');

        if ($archive) {
            try {
                $parsed = Carbon::createFromFormat('Y-m', $archive);
                $query->where('published_at', '>=', $parsed->copy()->startOfMonth())
                    ->where('published_at', '<=', $parsed->copy()->endOfMonth());
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

    public function show(int $id): View
    {
        $news = \App\Models\News::findOrFail($id);

        return view('news.show', compact('news'));
    }

}
