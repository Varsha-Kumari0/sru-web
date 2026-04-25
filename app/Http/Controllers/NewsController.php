<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Carbon\Carbon;


class NewsController extends Controller
{
    
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
