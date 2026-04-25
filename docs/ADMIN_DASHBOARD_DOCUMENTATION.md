# Admin Dashboard Documentation

## 1. Objective
The admin module provides a complete SRU alumni management interface with:
- dashboard analytics
- alumni profile and professional data management
- permanent activity auditing
- admin profile photo management in sidebar
- filtered export support

Primary data comes from:
- users
- profiles
- professionals
- activity_logs

Common relationship key across profile/professional records: user_id.

The profile record currently includes additional personal/social fields such as:
- father_name
- linkedin
- facebook
- instagram
- twitter

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
- Shared admin branding logo is loaded from public/images/logos/sru_logo_new.png with text fallback if the file is missing.

### 2.2 All SRU Alumni Page
- Route: GET /admin/all-alumini (name: admin.allalumini)
- Legacy URL redirect: /admin/allalumini -> /admin/all-alumini
- Table includes alumni data from users + profiles + professionals.
- Row actions:
  - View details modal
  - Edit alumni record
  - Delete alumni record
- Profile photo support:
  - avatar shown in table using object-fit:contain with border, matching the dashboard style
  - photo shown in details modal
- View details modal includes social profile values from the profile table:
  - father_name
  - linkedin
  - facebook
  - instagram
  - twitter
- Export CSV button in page header exports all alumni fields (same 24-column format as dashboard export).

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
- Name field behavior:
  - if the stored account name is the generic placeholder "Alumni User", the form shows profile.full_name as the default editable value instead.
- Validation behavior:
  - name and email are required
  - optional text fields are nullable, so empty values no longer trigger "must be a string" messages
- Academic input behavior:
  - Degree is now a dropdown select.
  - Branch/Specialization is a dependent dropdown populated from the selected Degree.
  - When Degree is not selected, Branch/Specialization stays blank and disabled.

### 2.4 Alumni Self-Service Profile Flow
- Profile model fillable fields now include father_name.
- Profile creation requires:
  - full_name
  - father_name
  - mobile
  - city
  - country
  - degree
  - branch
  - passing_year
  - linkedin
  - instagram
  - facebook
  - twitter
- Profile creation validates social links against their expected domains.
- Profile update also requires father_name and preserves social URL validation rules.

### 2.5 Activity Logs Page
- Route: GET /admin/activity-logs (name: admin.activity-logs)
- CSV export route: GET /admin/activity-logs/export (name: admin.activity-logs.export)
- Includes filters:
  - from date
  - to date
  - actor user
  - action type
- CSV export respects the current filter query.
- Description column for alumni_updated action shows only the summary line in the table.
- On hover, the same description cell shows the full per-field change details, e.g. "Degree: Empty to B.Tech".
- New log rows include a changes array in the properties JSON column. Existing older rows show only the summary sentence.

### 2.6 Admin Sidebar Profile Photo
- Upload route: POST /admin/profile/avatar (name: admin.profile.avatar)
- The sidebar avatar (bottom-left user card) is now clickable on all admin pages.
- Clicking the avatar opens file selection and auto-submits the upload form.
- Saved file path is stored in users.avatar.
- Uploaded images are stored on the public disk under avatars/.
- Display style now matches alumni dashboard/photo fit behavior: object-fit:contain, centered image, white background, and subtle border.
- Allowed file types: jpg, jpeg, png
- Max file size: 2 MB
- Changes reflect across all admin pages because the sidebar reads auth()->user()->avatar.

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
- alumni_updated (properties.changes array records per-field old/new values)
- admin_avatar_updated
- alumni_deleted
- activity_logs_exported

## 4. Sidebar and UI Consistency
- Sidebar links are present across admin pages:
  - Dashboard
  - All SRU Alumni
  - Activity Logs
  - Reports
  - System -> Settings
- Admin sidebar profile avatar uses a consistent fit style across all admin pages (contain + center + white background + border).
- Logout hover visibility was normalized for icon-style sidebar variants.
- The same shared logo is rendered across all admin pages using the file public/images/logos/sru_logo_new.png.

## 5. Files Involved

### Backend
- routes/web.php
- app/Http/Controllers/AdminController.php
- app/Http/Controllers/ProfileController.php
- app/Http/Controllers/Auth/RegisteredUserController.php
- app/Models/User.php
- app/Models/Profile.php
- app/Models/ActivityLog.php
- database/migrations/2026_04_25_090000_create_activity_logs_table.php
- database/migrations/2026_04_25_120000_add_avatar_to_users_table.php

### Admin Views
- resources/views/admin/panel.blade.php
- resources/views/admin/allalumini.blade.php
- resources/views/admin/edit-alumni.blade.php
- resources/views/admin/activity-logs.blade.php

## 6. Data and Validation Notes
- father_name is now part of the profile data model.
- users.avatar stores the admin sidebar profile photo path.
- passing_year is treated as a 4-digit year string.
- professional from/to fields are stored as strings to support "Present".
- profile photo uploads are validated as jpg/jpeg/png with size limit.
- admin sidebar avatar uploads are validated as jpg/jpeg/png with 2MB limit.
- activity log filter inputs are validated before query execution.
- Admin detail modals now expose stored profile social links when present.
- Admin edit form treats optional text inputs as nullable and preserves clearer required-field validation for name and email.
- Profile create/update validation includes stricter social-link URL checks for LinkedIn, Instagram, Facebook, and X/Twitter.

## 6.1 CSV Export Columns
Both the Dashboard and All SRU Alumni page export the same 24 columns:
- ID, Account Name, Email
- Full Name, Father Name, Phone, City, Country
- Degree, Branch / Specialization, Graduation Year, Current Status, Company
- LinkedIn, Facebook, Instagram, Twitter
- Organization, Industry, Role, Work From, Work To, Work Location
- Registered

Export notes:
- Values containing commas or quotes are properly escaped (RFC 4180).
- File includes a UTF-8 BOM so Excel opens it without garbled characters.

## 7. Verification Checklist
Recommended checks:

1. Run migrations.
2. Open dashboard and verify newest alumni appear first.
3. Open dashboard and verify the shared logo renders in the sidebar.
4. Open All SRU Alumni and verify photo avatars match dashboard style (contain + border), social links, and details modal.
5. Edit an alumni record and verify updates, photo upload, required-field messages, and default name behavior.
6. In Edit Alumni, verify Degree populates Branch/Specialization options and Branch stays blank/disabled when Degree is empty.
7. After editing an alumni record, open Activity Logs and verify the table shows only the summary line; hover the description and verify the per-field change details appear.
8. Use the Export CSV button on both Dashboard and All SRU Alumni; verify the file has all 24 columns and opens correctly in Excel.
9. Click the admin sidebar avatar, upload a new image, and verify it updates on Dashboard, All SRU Alumni, Activity Logs, and Edit Alumni pages.
  Also verify the uploaded image is visually contained (not cropped) and aligns with the alumni dashboard fit style.
10. Open Activity Logs, apply filters, and export CSV; verify exported rows match filters.

## 8. Notes
- The URL path now uses /admin/all-alumini while the route name remains admin.allalumini for compatibility.
- The older URL /admin/allalumini is preserved as a redirect.
- If renamed to allalumni in future, route names and view references must be updated together.
