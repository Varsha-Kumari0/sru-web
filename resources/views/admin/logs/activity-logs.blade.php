<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Activity Logs - SRU Admin</title>
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

{{-- Shared admin sidebar --}}
@include('admin.partials.sidebar', ['activeSection' => 'activity-logs'])

{{-- Activity logs page with server-side filters --}}
<main class="ml-64 flex-1 flex flex-col min-h-screen max-w-[calc(100vw-16rem)] overflow-x-hidden">
    <header class="sticky top-0 z-40 flex items-center justify-between px-6 pt-[1.9rem] pb-[1.7em] xl:px-9 bg-white border-b border-slate-300">
        <div>
            <h2 class="font-display text-2xl font-semibold" style="letter-spacing:.01em;">Activity Logs</h2>
            <p class="text-xs mt-0.5 text-slate-500">{{ now()->format('l, d F Y') }} &mdash; Permanent audit records</p>
        </div>
          <a href="{{ route('admin.activity-logs.export', request()->query()) }}"
              class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export CSV
        </a>
    </header>

    <div class="px-6 py-7 xl:px-9 flex-1 space-y-6">
        {{-- Filter form keeps query params for list + CSV export --}}
        <form method="GET" action="{{ route('admin.activity-logs') }}" class="rounded-xl border border-slate-300 bg-white p-4 xl:p-5">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label for="from_date" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-slate-500">From Date</label>
                    <input type="date" id="from_date" name="from_date" value="{{ request('from_date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="to_date" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-slate-500">To Date</label>
                    <input type="date" id="to_date" name="to_date" value="{{ request('to_date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                </div>
                <div>
                    <label for="actor_user_id" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-slate-500">Actor</label>
                    <select id="actor_user_id" name="actor_user_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">All Actors</option>
                        @foreach($actors as $actor)
                            <option value="{{ $actor->id }}" {{ (string) request('actor_user_id') === (string) $actor->id ? 'selected' : '' }}>
                                    {{ $actor->display_name }} ({{ $actor->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="action" class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-slate-500">Action Type</label>
                    <select id="action" name="action" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.activity-logs') }}" class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        {{-- Audit table: newest first, paginated on backend --}}
        <div class="overflow-hidden rounded-xl border border-slate-300 bg-white cursor-pointer">
            <div class="overflow-x-auto">
                <table class="min-w-full table-fixed text-[11px] xl:text-xs">
                    <colgroup>
                        <col class="w-[140px] xl:w-[150px]">
                        <col class="w-[105px] xl:w-[115px]">
                        <col>
                        <col class="w-[145px] xl:w-[165px]">
                        <col class="w-[145px] xl:w-[165px]">
                    </colgroup>
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-3 py-2 text-left whitespace-nowrap">Date & Time</th>
                            <th class="px-3 py-2 text-left whitespace-nowrap">Action</th>
                            <th class="px-3 py-2 text-left">Description</th>
                            <th class="px-3 py-2 text-left whitespace-nowrap">Actor</th>
                            <th class="px-3 py-2 text-left whitespace-nowrap">Subject</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $groupedLogs = [];
                            $currentGroup = null;

                            $extractChangeDetails = static function ($log): array {
                                $rawChanges = $log->properties['changes'] ?? null;

                                if (!is_array($rawChanges) || empty($rawChanges)) {
                                    return [];
                                }

                                $details = [];
                                foreach ($rawChanges as $change) {
                                    $details[] = [
                                        'field' => $change['field'] ?? 'Field',
                                        'from' => $change['from'] ?? ($change['old'] ?? 'Empty'),
                                        'to' => $change['to'] ?? ($change['new'] ?? 'Empty'),
                                    ];
                                }

                                return $details;
                            };

                            foreach ($logs as $log) {
                                $changeDetails = $extractChangeDetails($log);
                                $hasChangeDetails = !empty($changeDetails);

                                $groupKey = implode('|', [
                                    (string) ($log->actor_user_id ?? '0'),
                                    (string) $log->action,
                                    (string) $log->description,
                                    (string) ($log->subject_user_id ?? '0'),
                                ]);

                                if (!$hasChangeDetails && $currentGroup && $currentGroup['key'] === $groupKey) {
                                    $currentGroup['count']++;
                                    $currentGroup['last_at'] = $log->created_at;
                                    $currentGroup['entries'][] = [
                                        'created_at' => $log->created_at,
                                        'description' => $log->description,
                                    ];
                                    continue;
                                }

                                if ($currentGroup) {
                                    $groupedLogs[] = $currentGroup;
                                }

                                $currentGroup = [
                                    'key' => $groupKey,
                                    'log' => $log,
                                    'count' => 1,
                                    'first_at' => $log->created_at,
                                    'last_at' => $log->created_at,
                                    'change_details' => $changeDetails,
                                    'has_change_details' => $hasChangeDetails,
                                    'entries' => [
                                        [
                                            'created_at' => $log->created_at,
                                            'description' => $log->description,
                                        ],
                                    ],
                                ];

                                // Keep field-level change rows separate so hover details remain accurate.
                                if ($hasChangeDetails) {
                                    $groupedLogs[] = $currentGroup;
                                    $currentGroup = null;
                                }
                            }

                            if ($currentGroup) {
                                $groupedLogs[] = $currentGroup;
                            }
                        @endphp

                        @forelse($groupedLogs as $group)
                            @php
                                $log = $group['log'];
                                $changeDetails = $group['change_details'];
                                $hasChangeDetails = $group['has_change_details'];
                                $repeatCount = $group['count'];
                                $groupEntries = $group['entries'] ?? [];
                                $latestAt = $groupEntries[0]['created_at'] ?? $group['first_at'];
                                $firstInGroupAt = $groupEntries[count($groupEntries) - 1]['created_at'] ?? $group['last_at'];
                            @endphp
                            <tr class="group border-t border-slate-200 hover:bg-slate-50">
                                <td class="px-3 py-2 align-top text-slate-700 whitespace-nowrap">
                                    <div class="font-medium text-slate-900">{{ $latestAt?->format('d M y, h:i A') }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $latestAt?->diffForHumans() }}</div>
                                </td>
                                <td class="px-3 py-2 align-top text-slate-700">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex max-w-full break-all rounded-md bg-blue-50 px-1.5 py-0.5 text-[10px] font-semibold text-blue-700">{{ $log->action }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2 align-top text-slate-900 break-words">
                                    @if($hasChangeDetails)
                                        <div>
                                            <div class="cursor-help underline decoration-dotted underline-offset-4">
                                                {{ $log->description }}
                                            </div>
                                            <div class="max-h-0 overflow-hidden opacity-0 transition-all duration-200 ease-out group-hover:mt-3 group-hover:max-h-40 group-hover:opacity-100">
                                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-xs text-slate-700">
                                                    <div class="mb-2 font-semibold text-slate-900">Change Details</div>
                                                    <ul class="space-y-2">
                                                    @foreach($changeDetails as $detail)
                                                        <li>
                                                            <span class="font-semibold text-slate-900">{{ $detail['field'] }}:</span>
                                                            {{ $detail['from'] }} to {{ $detail['to'] }}
                                                        </li>
                                                    @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div>{{ $log->description }}</div>
                                        @if($repeatCount > 1)
                                            <div class="mt-1 cursor-help text-xs text-slate-500 underline decoration-dotted underline-offset-4">
                                                Grouped {{ $repeatCount }} repeated events with the same actor and action. Hover to see all timestamps.
                                            </div>
                                            <div class="max-h-0 overflow-hidden opacity-0 transition-all duration-200 ease-out group-hover:mt-3 group-hover:max-h-52 group-hover:opacity-100">
                                                <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-xs text-slate-700">
                                                    <div class="mb-2 font-semibold text-slate-900">Grouped Event Timeline</div>
                                                    <div class="mb-2 text-slate-600">First in group: {{ $firstInGroupAt?->format('d M Y, h:i A') }}</div>
                                                    <ul class="space-y-2">
                                                        @foreach($groupEntries as $entry)
                                                            <li class="flex items-start justify-between gap-3">
                                                                <span class="text-slate-900">
                                                                    {{ $entry['created_at']?->format('d M Y, h:i:s A') ?? '-' }}
                                                                </span>
                                                                <span class="text-right text-slate-600">{{ $entry['description'] ?? '-' }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-3 py-2 align-top text-slate-700 break-words">
                                    @if($log->actor)
                                        <div class="font-medium text-slate-900">{{ $log->actor->display_name }}</div>
                                        <div class="text-[10px] text-slate-500 truncate" title="{{ $log->actor->email }}">{{ $log->actor->email }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-3 py-2 align-top text-slate-700 break-words">
                                    @if($log->subject)
                                        <div class="font-medium text-slate-900">{{ $log->subject->display_name }}</div>
                                        <div class="text-[10px] text-slate-500 truncate" title="{{ $log->subject->email }}">{{ $log->subject->email }}</div>
                                    @else
                                        {{ $log->properties['subject_name'] ?? '-' }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-500">No activity logs found for the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $logs->links() }}
        </div>
    </div>
</main>

</body>
</html>
