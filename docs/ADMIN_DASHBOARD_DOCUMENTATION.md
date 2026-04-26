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
- The previous legacy dashboard table/blank placeholder area has been replaced with two live content containers:
  - Latest News (top recently updated news items)
  - Upcoming Events (next scheduled events ordered by start date/time)
- Dashboard News/Event cards now open public detail pages (news.show / events.show).
- Dashboard "View All" links for News and Events open their public listing pages in a new tab.
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

### 2.5 Admin News Management
- Create route: GET /admin/news/new (name: admin.news.create)
- Store route: POST /admin/news (name: admin.news.store)
- Manage route: GET /admin/news/manage (name: admin.news.manage)
- Edit route: GET /admin/news/{id}/edit (name: admin.news.edit)
- Update route: PUT /admin/news/{id} (name: admin.news.update)
- Delete route: DELETE /admin/news/{id} (name: admin.news.delete)
- The News sidebar submenu now uses a single shared management entry labeled Update/Delete.
- On all admin pages, the News submenu remains hover-based rather than permanently expanded.
- News create page includes a right-side "Recent Updated News" panel.
- The panel is ordered by latest updated_at and shows:
  - title
  - excerpt
  - updated timestamp
  - published date
- News manage page lists existing news items with per-item Update and Delete buttons.
- News edit page supports updating title, excerpt, content, published date, and optional image replacement.
- Replacing an image removes the previous file from public/images.
- News manage and news edit pages include the same bottom sidebar admin card as the other admin pages:
  - admin avatar
  - admin name
  - Super Admin label
  - logout link

### 2.6 Admin Events Management
- Create route: GET /admin/events/new (name: admin.events.create)
- Store route: POST /admin/events (name: admin.events.store)
- Manage route: GET /admin/events/manage (name: admin.events.manage)
- Edit route: GET /admin/events/{id}/edit (name: admin.events.edit)
- Update route: PUT /admin/events/{id} (name: admin.events.update)
- Delete route: DELETE /admin/events/{id} (name: admin.events.delete)
- The Events sidebar submenu now uses a single shared management entry labeled Update/Delete.
- On all admin pages, the Events submenu remains hover-based with options: View / New / Update/Delete.
- Events create page includes a right-side "Recent Events" panel.
- The panel is ordered by latest updated_at and shows:
  - title
  - excerpt
  - updated timestamp
  - start date/time
- Events manage page lists existing events with per-item Update and Delete buttons.
- Events are displayed as cards with title, excerpt, event type badge, and date/time information.
- Event type values: campus-events, hackathons, reunions, webinars.
- Events edit page supports updating title, excerpt, description, event type, location, start/end dates, registration link, and optional image replacement.
- Replacing an image removes the previous file from public/images.
- Events manage and edit pages include the same bottom sidebar admin card as the other admin pages.
- Form validation enforces:
  - title (required, max 255 characters)
  - excerpt (nullable)
  - description (nullable)
  - event_type (required, must be one of: reunions, webinars, hackathons, campus-events)
  - location (nullable, max 255 characters)
  - start_at (required, must be a valid datetime)
  - end_at (nullable, must be after or equal to start_at)
  - registration_link (nullable, must be a valid URL)
  - image (nullable, must be jpg/jpeg/png/webp, max 2048 KB)
- Event model fields use datetime casting for start_at and end_at.

### 2.7 Activity Logs Page
- Route: GET /admin/activity-logs (name: admin.activity-logs)
- CSV export route: GET /admin/activity-logs/export (name: admin.activity-logs.export)
- Includes server-side filter form:
  - from date
  - to date
  - actor user dropdown (all users who have performed actions)
  - action type dropdown (all action types in logs)
- Apply Filters and Reset buttons control the query and CSV export respects filters.
- **Grouped Event Display:**
  - Repeated non-change events from the same actor/action/subject are automatically grouped into a single row.
  - Grouped row shows the latest event's timestamp and relative time (e.g., "20 minutes ago").
  - Main row displays only the action badge and description.
  - No entry count badge; grouped events appear as regular rows.
  - Hovering a grouped row expands a timeline panel showing:
    - "First in group: [timestamp]" header
    - All individual event timestamps in chronological order
- **Change-Based Actions:**
  - Actions such as alumni_updated and profile_updated remain as separate rows (not grouped).
  - For change-based actions, the description shows only the summary line by default.
  - Hovering a change row expands full per-field change details under the summary, e.g., "Degree: Empty → B.Tech".
  - Newer change log rows include a changes array in the properties JSON column.
  - Older rows without structured changes continue to show summary-only text.
- **Layout:**
  - Sidebar and topbar are aligned with consistent spacing across all admin pages.
  - Table uses fixed column sizing to fit within viewport on standard screen widths.
  - Actor/Subject emails are truncated with full email available on hover tooltip.
  - Table text is smaller on compact screens and scales up on wider viewports (`xl` breakpoint).

### 2.8 Admin Sidebar Profile Photo
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
- user_logged_in
- user_logged_out
- profile_created
- profile_updated (properties.changes array records per-field old/new values for user self-service profile edits)
- alumni_updated (properties.changes array records per-field old/new values)
- admin_news_create_opened
- news_created
- news_updated
- news_deleted
- admin_event_create_opened
- event_created
- event_updated
- event_deleted
- admin_avatar_updated
- alumni_deleted
- activity_logs_exported

## 4. Sidebar and UI Consistency
- Sidebar links are present across admin pages:
  - Dashboard
  - All SRU Alumni
  - News -> View / New / Update/Delete
  - Events -> View / New / Update/Delete
  - Activity Logs
  - Reports
  - System -> Settings
- The News and Events submenus on all admin pages use hover flyout menus that appear outside (to the right of) the sidebar.
- Admin sidebar profile avatar uses a consistent fit style across all admin pages (contain + center + white background + border).
- The bottom sidebar admin identity card is present across all admin pages, including News create, News manage, News edit, Events create, Events manage, and Events edit.
- Logout hover visibility was normalized for icon-style sidebar variants.
- The same shared logo is rendered across all admin pages using the file public/images/logos/sru_logo_new.png.

## 5. Files Involved

### Backend
- routes/web.php
- app/Http/Controllers/AdminController.php
- app/Http/Controllers/EventController.php
- app/Http/Controllers/NewsController.php
- app/Http/Controllers/ProfileController.php
- app/Http/Controllers/Auth/RegisteredUserController.php
- app/Models/User.php
- app/Models/Profile.php
- app/Models/News.php
- app/Models/Event.php
- app/Models/ActivityLog.php
- database/migrations/2026_04_25_090000_create_activity_logs_table.php
- database/migrations/2026_04_25_111645_create_events_table.php
- database/migrations/2026_04_25_120000_add_avatar_to_users_table.php

### Admin Views
- resources/views/admin/dashboard/panel.blade.php (view: admin.dashboard.panel)
- resources/views/admin/alumni/allalumini.blade.php (view: admin.alumni.allalumini)
- resources/views/admin/alumni/edit-alumni.blade.php (view: admin.alumni.edit-alumni)
- resources/views/admin/logs/activity-logs.blade.php (view: admin.logs.activity-logs)
- resources/views/admin/news/news-create.blade.php (view: admin.news.news-create)
- resources/views/admin/news/news-manage.blade.php (view: admin.news.news-manage)
- resources/views/admin/news/news-edit.blade.php (view: admin.news.news-edit)
- resources/views/admin/events/event-create.blade.php (view: admin.events.event-create)
- resources/views/admin/events/event-manage.blade.php (view: admin.events.event-manage)
- resources/views/admin/events/event-edit.blade.php (view: admin.events.event-edit)

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
2. Open dashboard and verify the old blank placeholder area is replaced with two live containers: Latest News and Upcoming Events.
3. In dashboard, click a News card and an Event card and verify each opens the corresponding public detail page.
4. In dashboard, click News "View All" and Events "View All" and verify both open public listing pages in a new browser tab.
5. Open dashboard and verify the shared logo renders in the sidebar.
6. Open All SRU Alumni and verify photo avatars match dashboard style (contain + border), social links, and details modal.
7. Edit an alumni record and verify updates, photo upload, required-field messages, and default name behavior.
8. In Edit Alumni, verify Degree populates Branch/Specialization options and Branch stays blank/disabled when Degree is empty.
9. After editing an alumni record, open Activity Logs and verify the table shows only the summary line by default; hover the row and verify it expands to show per-field change details.
10. Trigger the same non-change action multiple times (for example opening the same admin page repeatedly), then open Activity Logs and verify those repeated events are grouped into one row; hover the grouped row and verify the expanded panel shows "First in group" timestamp and every occurrence timestamp in the timeline.
11. Use the Export CSV button on both Dashboard and All SRU Alumni; verify the file has all 24 columns and opens correctly in Excel.
12. Click the admin sidebar avatar, upload a new image, and verify it updates on Dashboard, All SRU Alumni, Activity Logs, and Edit Alumni pages.
  Also verify the uploaded image is visually contained (not cropped) and aligns with the alumni dashboard fit style.
13. Open Activity Logs with various filters (date range, actor, action type); verify filters work correctly and Export CSV respects the filtered results.
14. On Activity Logs page, verify the layout fits within a standard browser window without horizontal scroll; verify sidebar logo area aligns with top header.
15. Open Events -> New and verify the right-side "Recent Events" panel is visible and ordered by latest update time.
16. On Events -> Create form, verify all event fields are present:
    - title (required, text input)
    - excerpt (optional, textarea)
    - description (optional, large textarea)
    - event_type (required, dropdown with 4 options: Campus Events, Hackathons, Reunions, Webinars)
    - location (optional, text input)
    - start_at (required, datetime-local input)
    - end_at (optional, datetime-local input)
    - registration_link (optional, URL input)
    - image (optional, file input for jpg/jpeg/png/webp)
17. Create a new event with all fields and verify it appears in Recent Events panel and can be viewed on the public events page.
18. Open Events -> Update/Delete and verify the page lists events as cards with:
    - title (truncated if long)
    - excerpt (max 2 lines)
    - event type badge
    - start date/time
    - end date/time or "No end date" if empty
    - location (if present)
    - Update and Delete buttons
19. Edit an event and verify:
    - all fields are pre-populated with current values
    - image replacement works and old image is deleted
    - Activity Logs records an event_updated entry
    - Changes appear immediately on public events page
20. Delete an event and verify:
    - the record is removed from manage page
    - any stored image file is deleted
    - Activity Logs records an event_deleted entry
    - event no longer appears on public events page
21. On Events create/manage/edit pages, verify:
  - the Events submenu appears on hover as an outside-sidebar flyout with View / New / Update/Delete options
    - the submenu is not permanently expanded
    - the correct sub-link is highlighted based on current page
    - the bottom sidebar admin card is visible
22. On News -> Update/Delete and News edit pages, verify the bottom sidebar admin card is visible and matches the rest of the admin pages.
23. On News create/manage/edit pages, verify the News submenu appears on hover as an outside-sidebar flyout and is not permanently expanded.
24. Edit a news item and verify updated values are saved, optional image replacement works, and Activity Logs records a news_updated entry.
25. Delete a news item and verify the record is removed, any stored image file is deleted, and Activity Logs records a news_deleted entry.

## 8. Notes
- The URL path now uses /admin/all-alumini while the route name remains admin.allalumini for compatibility.
- The older URL /admin/allalumini is preserved as a redirect.
- If renamed to allalumni in future, route names and view references must be updated together.
- Admin Blade files are now grouped by module under resources/views/admin (dashboard, alumni, logs, news, events) instead of a single flat folder.
- News and Events management modules use identical CRUD patterns:
  - Create page with recent items right panel
  - Manage page with card grid and per-item action buttons
  - Edit page with form and back link
  - Image upload support with automatic cleanup on replacement/deletion
  - Activity log recording for all create/update/delete operations
- Both modules are fully integrated into the admin sidebar with hover submenus on all admin pages.
- News and Events flyout submenus are rendered outside the sidebar so they remain visible without shrinking/stacking inside the nav column.
- Dashboard News and Events blocks are wired to live database data and link to public pages, while admin edit actions remain available through module manage pages.
