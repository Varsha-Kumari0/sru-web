<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:        #0d0f14;
            --surface:   #13161e;
            --card:      #181c26;
            --border:    #252a38;
            --accent:    #c9a84c;
            --accent2:   #e8c97a;
            --text:      #e8e6df;
            --muted:     #7a7f90;
            --danger:    #e05c5c;
            --success:   #4caf7d;
            --radius:    12px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ─────────────────────────────── */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 32px 28px 24px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-logo h1 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--accent2);
            letter-spacing: 0.02em;
            line-height: 1.2;
        }

        .sidebar-logo span {
            font-size: 11px;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 16px;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 0 12px;
            margin: 20px 0 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 9px;
            font-size: 14px;
            font-weight: 500;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.18s;
            text-decoration: none;
            margin-bottom: 2px;
        }

        .nav-item:hover { background: var(--card); color: var(--text); }

        .nav-item.active {
            background: rgba(201,168,76,.13);
            color: var(--accent2);
        }

        .nav-item svg { flex-shrink: 0; }

        .sidebar-footer {
            padding: 20px 16px;
            border-top: 1px solid var(--border);
        }

        .admin-badge {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 12px 14px;
            background: var(--card);
            border-radius: var(--radius);
        }

        .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #8b6a28);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: #fff;
            flex-shrink: 0;
        }

        .admin-info { flex: 1; min-width: 0; }
        .admin-name { font-size: 13px; font-weight: 600; color: var(--text); }
        .admin-role { font-size: 11px; color: var(--muted); }

        /* ── Main Content ────────────────────────── */
        .main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .topbar {
            padding: 20px 36px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-title h2 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .topbar-title p { font-size: 13px; color: var(--muted); margin-top: 2px; }

        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.18s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-primary {
            background: var(--accent);
            color: #0d0f14;
        }

        .btn-primary:hover { background: var(--accent2); transform: translateY(-1px); }

        .btn-ghost {
            background: var(--card);
            color: var(--muted);
            border: 1px solid var(--border);
        }

        .btn-ghost:hover { color: var(--text); border-color: var(--muted); }

        /* ── Content Area ────────────────────────── */
        .content { padding: 32px 36px; flex: 1; }

        /* Stats row */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 22px 24px;
            position: relative;
            overflow: hidden;
            transition: transform 0.18s;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: var(--accent);
        }

        .stat-card:hover { transform: translateY(-2px); }

        .stat-label { font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); }
        .stat-value { font-family: 'Playfair Display', serif; font-size: 36px; font-weight: 700; color: var(--text); margin: 6px 0 4px; }
        .stat-change { font-size: 12px; color: var(--success); }
        .stat-icon {
            position: absolute; right: 18px; top: 18px;
            opacity: 0.12;
        }

        /* Table section */
        .table-section {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        .table-header {
            padding: 22px 26px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .table-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            font-weight: 600;
        }

        .table-controls { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

        .search-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 14px;
            transition: border-color 0.18s;
        }

        .search-box:focus-within { border-color: var(--accent); }

        .search-box input {
            background: none;
            border: none;
            outline: none;
            color: var(--text);
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            width: 200px;
        }

        .search-box input::placeholder { color: var(--muted); }

        .filter-select {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 9px 14px;
            color: var(--muted);
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            cursor: pointer;
            transition: border-color 0.18s;
        }

        .filter-select:focus { border-color: var(--accent); color: var(--text); }

        /* Table */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            padding: 13px 20px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
        }

        thead th:hover { color: var(--text); }

        thead th.sort-asc::after { content: ' ↑'; color: var(--accent); }
        thead th.sort-desc::after { content: ' ↓'; color: var(--accent); }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.14s;
            cursor: default;
        }

        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,.025); }

        tbody td {
            padding: 16px 20px;
            font-size: 13.5px;
            vertical-align: middle;
        }

        .alumni-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alumni-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; color: #fff;
            flex-shrink: 0;
        }

        .alumni-name { font-weight: 600; font-size: 14px; }
        .alumni-id { font-size: 11px; color: var(--muted); margin-top: 1px; }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        .badge-active   { background: rgba(76,175,125,.15); color: var(--success); }
        .badge-pending  { background: rgba(201,168,76,.15); color: var(--accent2); }
        .badge-inactive { background: rgba(122,127,144,.12); color: var(--muted); }

        .row-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.18s;
        }

        tbody tr:hover .row-actions { opacity: 1; }

        .icon-btn {
            width: 30px; height: 30px;
            border-radius: 7px;
            background: var(--surface);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
            color: var(--muted);
        }

        .icon-btn:hover { border-color: var(--accent); color: var(--accent2); }
        .icon-btn.danger:hover { border-color: var(--danger); color: var(--danger); }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 26px;
            border-top: 1px solid var(--border);
        }

        .pagination-info { font-size: 13px; color: var(--muted); }

        .pagination-controls { display: flex; align-items: center; gap: 6px; }

        .page-btn {
            width: 34px; height: 34px;
            border-radius: 7px;
            background: var(--surface);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            transition: all 0.15s;
        }

        .page-btn:hover, .page-btn.active {
            background: rgba(201,168,76,.15);
            border-color: var(--accent);
            color: var(--accent2);
        }

        /* Modal */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,.6);
            backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            z-index: 200;
            opacity: 0; pointer-events: none;
            transition: opacity 0.22s;
        }

        .modal-overlay.open { opacity: 1; pointer-events: all; }

        .modal {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            width: 520px;
            max-width: 90vw;
            box-shadow: 0 40px 80px rgba(0,0,0,.5);
            transform: translateY(16px);
            transition: transform 0.22s;
        }

        .modal-overlay.open .modal { transform: translateY(0); }

        .modal-header {
            padding: 24px 28px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .modal-header h3 { font-family: 'Playfair Display', serif; font-size: 18px; }

        .modal-close {
            width: 30px; height: 30px;
            border-radius: 7px;
            background: var(--surface);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--muted);
            font-size: 16px;
            transition: all 0.15s;
        }

        .modal-close:hover { color: var(--text); border-color: var(--muted); }

        .modal-body { padding: 28px; }

        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }

        .detail-item label {
            display: block;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 5px;
        }

        .detail-item p {
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
        }

        .detail-item.full { grid-column: 1 / -1; }

        .modal-footer {
            padding: 18px 28px;
            border-top: 1px solid var(--border);
            display: flex; justify-content: flex-end; gap: 10px;
        }

        /* Toast */
        .toast {
            position: fixed; bottom: 28px; right: 28px;
            background: var(--card);
            border: 1px solid var(--border);
            border-left: 3px solid var(--success);
            border-radius: 10px;
            padding: 14px 20px;
            font-size: 13px;
            font-weight: 500;
            box-shadow: 0 16px 40px rgba(0,0,0,.4);
            z-index: 300;
            transform: translateY(80px);
            opacity: 0;
            transition: all 0.28s cubic-bezier(.34,1.56,.64,1);
        }

        .toast.show { transform: translateY(0); opacity: 1; }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }

        .empty-state svg { margin-bottom: 16px; opacity: 0.4; }
        .empty-state p { font-size: 14px; }

        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .sidebar { width: 220px; }
            .main { margin-left: 220px; }
            .content { padding: 24px 20px; }
        }
    </style>
</head>
<body>

<!-- ─── Sidebar ─────────────────────────────────── -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <h1>AlumniHub</h1>
        <span>Administration</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Overview</div>
        <a class="nav-item active" href="#">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a class="nav-item" href="#">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Alumni
        </a>
        <a class="nav-item" href="#">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending Approvals
        </a>

        <div class="nav-label">Manage</div>
        <a class="nav-item" href="#">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Batch / Year
        </a>
        <a class="nav-item" href="#">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            Email Broadcasts
        </a>
        <a class="nav-item" href="#">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            Reports
        </a>

        <div class="nav-label">System</div>
        <a class="nav-item" href="#">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93L4.93 19.07M4.93 4.93l14.14 14.14"/><circle cx="12" cy="12" r="10"/></svg>
            Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-badge">
            <div class="avatar">A</div>
            <div class="admin-info">
                <div class="admin-name">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</div>
                <div class="admin-role">Super Admin</div>
            </div>
        </div>
    </div>
</aside>

<!-- ─── Main ─────────────────────────────────────── -->
<main class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-title">
            <h2>Alumni Registry</h2>
            <p>Manage and view all registered alumni</p>
        </div>
        <div class="topbar-actions">
            <button class="btn btn-ghost" onclick="exportCSV()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </button>
            <button class="btn btn-primary" onclick="showToast('Invite link copied!')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Invite Alumni
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="content">

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <svg class="stat-icon" width="60" height="60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <div class="stat-label">Total Alumni</div>
                <div class="stat-value" id="stat-total">—</div>
                <div class="stat-change">↑ 12 this month</div>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" width="60" height="60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <div class="stat-label">Active</div>
                <div class="stat-value" id="stat-active">—</div>
                <div class="stat-change">↑ 4.2% vs last month</div>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" width="60" height="60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <div class="stat-label">Pending Approval</div>
                <div class="stat-value" id="stat-pending">—</div>
                <div class="stat-change" style="color:var(--accent2)">Needs review</div>
            </div>
            <div class="stat-card">
                <svg class="stat-icon" width="60" height="60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <div class="stat-label">Graduation Years</div>
                <div class="stat-value" id="stat-years">—</div>
                <div class="stat-change">Across all batches</div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-section">
            <div class="table-header">
                <h3>All Registered Alumni</h3>
                <div class="table-controls">
                    <div class="search-box">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="searchInput" placeholder="Search alumni…" oninput="filterTable()">
                    </div>
                    <select class="filter-select" id="statusFilter" onchange="filterTable()">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Pending">Pending</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                    <select class="filter-select" id="yearFilter" onchange="filterTable()">
                        <option value="">All Years</option>
                    </select>
                </div>
            </div>

            <div class="table-wrap">
                <table id="alumniTable">
                    <thead>
                        <tr>
                            <th onclick="sortTable('name')">Alumni</th>
                            <th onclick="sortTable('department')">Department</th>
                            <th onclick="sortTable('graduation_year')">Batch Year</th>
                            <th onclick="sortTable('email')">Email</th>
                            <th onclick="sortTable('status')">Status</th>
                            <th onclick="sortTable('created_at')">Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    <p>Loading alumni data…</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <span class="pagination-info" id="paginationInfo">Showing 0 of 0 alumni</span>
                <div class="pagination-controls" id="paginationControls"></div>
            </div>
        </div>

    </div><!-- /content -->
</main>

<!-- ─── Detail Modal ──────────────────────────────── -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
    <div class="modal">
        <div class="modal-header">
            <h3>Alumni Details</h3>
            <div class="modal-close" onclick="closeModalDirect()">✕</div>
        </div>
        <div class="modal-body">
            <div class="detail-grid" id="modalContent"></div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeModalDirect()">Close</button>
            <button class="btn btn-primary" id="modalApproveBtn">Approve</button>
        </div>
    </div>
</div>

<!-- ─── Toast ─────────────────────────────────────── -->
<div class="toast" id="toast"></div>

<script>
// ── Sample data (replace with real Laravel/API data) ──────────────────────
// or via API:
//   const res = await fetch('/api/admin/alumni');
//   const alumni = await res.json();

const COLORS = ['#c9a84c','#4caf7d','#5b8dee','#e05c5c','#9b59b6','#e67e22'];
const getColor = (i) => COLORS[i % COLORS.length];

const alumni = [
    { id:1,  name:"Priya Sharma",     email:"priya.sharma@gmail.com",      phone:"9876543210", department:"Computer Science",       graduation_year:2019, status:"Active",   location:"Hyderabad", created_at:"2024-01-15" },
    { id:2,  name:"Arjun Reddy",      email:"arjun.reddy@outlook.com",     phone:"9123456780", department:"Electronics & Comm.",    graduation_year:2020, status:"Active",   location:"Bengaluru", created_at:"2024-02-03" },
    { id:3,  name:"Meena Iyer",       email:"meena.iyer@yahoo.com",        phone:"9988776655", department:"Mechanical Engg.",       graduation_year:2018, status:"Inactive", location:"Chennai",   created_at:"2023-11-20" },
    { id:4,  name:"Rahul Verma",      email:"rahul.verma@company.in",      phone:"8877665544", department:"Civil Engineering",      graduation_year:2021, status:"Pending",  location:"Mumbai",    created_at:"2024-04-01" },
    { id:5,  name:"Sneha Patel",      email:"sneha.patel@mail.com",        phone:"7766554433", department:"Computer Science",       graduation_year:2022, status:"Active",   location:"Pune",      created_at:"2024-03-18" },
    { id:6,  name:"Vikram Nair",      email:"vikram.nair@techco.io",       phone:"9345678901", department:"Information Technology", graduation_year:2019, status:"Active",   location:"Kochi",     created_at:"2024-01-28" },
    { id:7,  name:"Kavya Menon",      email:"kavya.menon@univ.edu",        phone:"9456781234", department:"Electronics & Comm.",    graduation_year:2023, status:"Pending",  location:"Thrissur",  created_at:"2024-04-10" },
    { id:8,  name:"Sanjay Kumar",     email:"sanjay.kumar@corp.net",       phone:"8234567890", department:"Mechanical Engg.",       graduation_year:2017, status:"Active",   location:"Delhi",     created_at:"2023-09-05" },
    { id:9,  name:"Anjali Singh",     email:"anjali.singh@startup.com",    phone:"9654321098", department:"Computer Science",       graduation_year:2021, status:"Active",   location:"Hyderabad", created_at:"2024-02-22" },
    { id:10, name:"Deepak Pillai",    email:"deepak.pillai@gmail.com",     phone:"7654321234", department:"Civil Engineering",      graduation_year:2020, status:"Inactive", location:"Trivandrum",created_at:"2023-12-14" },
    { id:11, name:"Riya Choudhary",   email:"riya.c@design.co",            phone:"9871234560", department:"Information Technology", graduation_year:2022, status:"Active",   location:"Jaipur",    created_at:"2024-03-30" },
    { id:12, name:"Aditya Bose",      email:"aditya.bose@analytics.in",   phone:"8765432109", department:"Computer Science",       graduation_year:2018, status:"Pending",  location:"Kolkata",   created_at:"2024-04-08" }
];

// ── State ─────────────────────────────────────────
let filtered = [...alumni];
let sortKey = 'name', sortDir = 1;
let currentPage = 1;
const perPage = 7;

// ── Init ──────────────────────────────────────────
window.addEventListener('DOMContentLoaded', () => {
    populateYearFilter();
    updateStats();
    renderTable();
});

function populateYearFilter() {
    const years = [...new Set(alumni.map(a => a.graduation_year))].sort((a,b) => b-a);
    const sel = document.getElementById('yearFilter');
    years.forEach(y => {
        const o = document.createElement('option');
        o.value = y; o.textContent = y;
        sel.appendChild(o);
    });
}

function updateStats() {
    document.getElementById('stat-total').textContent   = alumni.length;
    document.getElementById('stat-active').textContent  = alumni.filter(a => a.status === 'Active').length;
    document.getElementById('stat-pending').textContent = alumni.filter(a => a.status === 'Pending').length;
    const years = new Set(alumni.map(a => a.graduation_year));
    document.getElementById('stat-years').textContent   = years.size;
}

// ── Filter ────────────────────────────────────────
function filterTable() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    const year   = document.getElementById('yearFilter').value;

    filtered = alumni.filter(a => {
        const matchQ = !q ||
            a.name.toLowerCase().includes(q) ||
            a.email.toLowerCase().includes(q) ||
            a.department.toLowerCase().includes(q) ||
            String(a.graduation_year).includes(q);
        const matchS = !status || a.status === status;
        const matchY = !year   || a.graduation_year == year;
        return matchQ && matchS && matchY;
    });

    currentPage = 1;
    renderTable();
}

// ── Sort ──────────────────────────────────────────
function sortTable(key) {
    if (sortKey === key) sortDir *= -1;
    else { sortKey = key; sortDir = 1; }

    document.querySelectorAll('thead th').forEach(th => {
        th.classList.remove('sort-asc','sort-desc');
    });
    // Highlight active header
    const idx = ['name','department','graduation_year','email','status','created_at'].indexOf(key);
    if (idx >= 0) {
        const ths = document.querySelectorAll('thead th');
        ths[idx].classList.add(sortDir === 1 ? 'sort-asc' : 'sort-desc');
    }

    filtered.sort((a,b) => {
        const av = a[key] ?? '', bv = b[key] ?? '';
        if (av < bv) return -1 * sortDir;
        if (av > bv) return  1 * sortDir;
        return 0;
    });

    renderTable();
}

// ── Render ────────────────────────────────────────
function renderTable() {
    const tbody = document.getElementById('tableBody');
    const total = filtered.length;
    const pages = Math.max(1, Math.ceil(total / perPage));
    currentPage = Math.min(currentPage, pages);
    const start = (currentPage - 1) * perPage;
    const slice = filtered.slice(start, start + perPage);

    if (slice.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7">
            <div class="empty-state">
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <p>No alumni match your filters.</p>
            </div>
        </td></tr>`;
    } else {
        tbody.innerHTML = slice.map((a, i) => {
            const initials = a.name.split(' ').map(n => n[0]).join('').slice(0,2).toUpperCase();
            const color    = getColor(a.id);
            const badgeClass = a.status === 'Active' ? 'badge-active' : a.status === 'Pending' ? 'badge-pending' : 'badge-inactive';
            const date = new Date(a.created_at).toLocaleDateString('en-IN', { day:'numeric', month:'short', year:'numeric' });
            return `
            <tr>
                <td>
                    <div class="alumni-cell">
                        <div class="alumni-avatar" style="background:${color}">${initials}</div>
                        <div>
                            <div class="alumni-name">${a.name}</div>
                            <div class="alumni-id">#ALM-${String(a.id).padStart(4,'0')}</div>
                        </div>
                    </div>
                </td>
                <td>${a.department}</td>
                <td>${a.graduation_year}</td>
                <td style="color:var(--muted)">${a.email}</td>
                <td><span class="badge ${badgeClass}">${a.status}</span></td>
                <td style="color:var(--muted)">${date}</td>
                <td>
                    <div class="row-actions">
                        <div class="icon-btn" title="View Details" onclick="openModal(${a.id})">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </div>
                        ${a.status === 'Pending' ? `<div class="icon-btn" title="Approve" onclick="approveAlumni(${a.id})">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>` : ''}
                        <div class="icon-btn danger" title="Remove" onclick="removeAlumni(${a.id})">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/></svg>
                        </div>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    document.getElementById('paginationInfo').textContent =
        `Showing ${Math.min(start+1, total)}–${Math.min(start+perPage, total)} of ${total} alumni`;

    // Pagination buttons
    const ctrl = document.getElementById('paginationControls');
    ctrl.innerHTML = '';
    const addBtn = (label, page, active) => {
        const btn = document.createElement('div');
        btn.className = 'page-btn' + (active ? ' active' : '');
        btn.textContent = label;
        btn.onclick = () => { currentPage = page; renderTable(); };
        ctrl.appendChild(btn);
    };
    if (currentPage > 1) addBtn('‹', currentPage-1, false);
    for (let p = 1; p <= pages; p++) addBtn(p, p, p === currentPage);
    if (currentPage < pages) addBtn('›', currentPage+1, false);
}

// ── Modal ─────────────────────────────────────────
function openModal(id) {
    const a = alumni.find(x => x.id === id);
    if (!a) return;
    const color = getColor(a.id);
    const initials = a.name.split(' ').map(n => n[0]).join('').slice(0,2).toUpperCase();
    const badgeClass = a.status === 'Active' ? 'badge-active' : a.status === 'Pending' ? 'badge-pending' : 'badge-inactive';

    document.getElementById('modalContent').innerHTML = `
        <div class="detail-item full" style="display:flex;align-items:center;gap:16px;margin-bottom:8px">
            <div class="alumni-avatar" style="background:${color};width:52px;height:52px;font-size:18px">${initials}</div>
            <div>
                <div style="font-size:18px;font-weight:700;font-family:'Playfair Display',serif">${a.name}</div>
                <div style="font-size:12px;color:var(--muted);margin-top:2px">#ALM-${String(a.id).padStart(4,'0')}</div>
            </div>
        </div>
        <div class="detail-item"><label>Email</label><p>${a.email}</p></div>
        <div class="detail-item"><label>Phone</label><p>${a.phone}</p></div>
        <div class="detail-item"><label>Department</label><p>${a.department}</p></div>
        <div class="detail-item"><label>Graduation Year</label><p>${a.graduation_year}</p></div>
        <div class="detail-item"><label>Location</label><p>${a.location}</p></div>
        <div class="detail-item"><label>Status</label><p><span class="badge ${badgeClass}">${a.status}</span></p></div>
        <div class="detail-item"><label>Registered On</label><p>${new Date(a.created_at).toLocaleDateString('en-IN',{day:'numeric',month:'long',year:'numeric'})}</p></div>
    `;

    const approveBtn = document.getElementById('modalApproveBtn');
    if (a.status === 'Pending') {
        approveBtn.style.display = '';
        approveBtn.onclick = () => { approveAlumni(id); closeModalDirect(); };
    } else {
        approveBtn.style.display = 'none';
    }

    document.getElementById('modalOverlay').classList.add('open');
}

function closeModal(e) {
    if (e.target === document.getElementById('modalOverlay')) closeModalDirect();
}

function closeModalDirect() {
    document.getElementById('modalOverlay').classList.remove('open');
}

// ── Actions ───────────────────────────────────────
function approveAlumni(id) {
    const a = alumni.find(x => x.id === id);
    if (!a) return;
    a.status = 'Active';
    updateStats();
    filterTable();
    showToast(`${a.name} approved successfully`);
    // In production: axios.put(`/admin/alumni/${id}/approve`)
}

function removeAlumni(id) {
    const a = alumni.find(x => x.id === id);
    if (!a || !confirm(`Remove ${a.name} from the registry?`)) return;
    alumni.splice(alumni.indexOf(a), 1);
    filtered = filtered.filter(x => x.id !== id);
    updateStats();
    renderTable();
    showToast(`${a.name} removed`);
    // In production: axios.delete(`/admin/alumni/${id}`)
}

function exportCSV() {
    const headers = ['ID','Name','Email','Phone','Department','Graduation Year','Status','Location','Registered'];
    const rows = alumni.map(a => [a.id, a.name, a.email, a.phone, a.department, a.graduation_year, a.status, a.location, a.created_at]);
    const csv = [headers, ...rows].map(r => r.join(',')).join('\n');
    const blob = new Blob([csv], { type: 'text/csv' });
    const url  = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url; link.download = 'alumni_export.csv'; link.click();
    showToast('CSV exported successfully');
}

// ── Toast ─────────────────────────────────────────
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
</body>
</html>