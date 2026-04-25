# Admin Dashboard Documentation

## 1. Objective
The admin module provides a complete SRU alumni management interface with:
- dashboard analytics
- alumni profile and professional data management
- permanent activity auditing
- filtered export support

Primary data comes from:
- users
- profiles
- professionals
- activity_logs

Common relationship key across profile/professional records: user_id.

## 2. Current Admin Features

### 2.1 Admin Dashboard
- Route: GET /admin/dashboard (name: admin.dashboard)
- Displays key stats:
  - total alumni
  - graduation batches
  - unread messages placeholder
- Includes:
  - department breakdown panel
  - recent activity panel (now backed by activity_logs table)
- Alumni list defaults to latest registered first by created_at descending.

### 2.2 All SRU Alumni Page
- Route: GET /admin/all-alumini (name: admin.allalumini)
- Legacy URL redirect: /admin/allalumini -> /admin/all-alumini
- Table includes alumni data from users + profiles + professionals.
- Row actions:
  - View details modal
  - Edit alumni record
  - Delete alumni record
- Profile photo support:
  - avatar shown in table (photo or initials fallback)
  - photo shown in details modal

### 2.3 Edit Alumni Page
- Route: GET /admin/alumni/{id}/edit (name: admin.alumni.edit)
- Update route: PUT /admin/alumni/{id} (name: admin.alumni.update)
- Supports updating:
  - user account fields
  - profile fields
  - professional fields
  - profile photo upload
- Work experience end-date behavior:
  - "Currently Working" checkbox sets professional.to to "Present".

### 2.4 Activity Logs Page
- Route: GET /admin/activity-logs (name: admin.activity-logs)
- CSV export route: GET /admin/activity-logs/export (name: admin.activity-logs.export)
- Includes filters:
  - from date
  - to date
  - actor user
  - action type
- CSV export respects the current filter query.

## 3. Permanent Activity Audit

### 3.1 activity_logs Table
Migration: 2026_04_25_090000_create_activity_logs_table.php

Columns:
- id
- actor_user_id (nullable FK -> users.id)
- subject_user_id (nullable FK -> users.id)
- action
- description
- properties (json, nullable)
- created_at, updated_at

### 3.2 ActivityLog Model
File: app/Models/ActivityLog.php

Provides a helper:
- ActivityLog::record(actorUserId, subjectUserId, action, description, properties)

### 3.3 Logged Events
Current events include:
- user_registered
- profile_created
- profile_updated
- alumni_updated
- alumni_deleted
- activity_logs_exported

## 4. Sidebar and UI Consistency
- Sidebar links are present across admin pages:
  - Dashboard
  - All SRU Alumni
  - Activity Logs
  - Reports
  - System -> Settings
- Logout hover visibility was normalized for icon-style sidebar variants.

## 5. Files Involved

### Backend
- routes/web.php
- app/Http/Controllers/AdminController.php
- app/Http/Controllers/ProfileController.php
- app/Http/Controllers/Auth/RegisteredUserController.php
- app/Models/ActivityLog.php
- database/migrations/2026_04_25_090000_create_activity_logs_table.php

### Admin Views
- resources/views/admin/panel.blade.php
- resources/views/admin/allalumini.blade.php
- resources/views/admin/edit-alumni.blade.php
- resources/views/admin/activity-logs.blade.php

## 6. Data and Validation Notes
- passing_year is treated as a 4-digit year string.
- professional from/to fields are stored as strings to support "Present".
- profile photo uploads are validated as jpg/jpeg/png with size limit.
- activity log filter inputs are validated before query execution.

## 7. Verification Checklist
Recommended checks:

1. Run migrations.
2. Open dashboard and verify newest alumni appear first.
3. Open All SRU Alumni and verify photo avatars and details modal.
4. Edit an alumni record and verify updates + photo upload.
5. Open Activity Logs and verify entries are present.
6. Apply filters and export CSV; verify exported rows match filters.

## 8. Notes
- The URL path now uses /admin/all-alumini while the route name remains admin.allalumini for compatibility.
- The older URL /admin/allalumini is preserved as a redirect.
- If renamed to allalumni in future, route names and view references must be updated together.
