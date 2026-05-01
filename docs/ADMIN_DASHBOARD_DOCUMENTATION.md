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
- employment_from
- employment_to
- study_institution
- study_degree
- study_branch
- study_from
- study_to
- previous_education
- description

## 2. Current Admin Features

### 2.1 Admin Dashboard
- Route: GET /admin/dashboard (name: admin.dashboard)
- View: `resources/views/admin/dashboard/dashboard.blade.php` (previously `panel.blade.php`, renamed 2026-04-30)
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
- Built-in filters support:
  - all records
  - branch
  - graduation year
  - organization
  - role
  - location
- Filter value selection uses a searchable dropdown populated from distinct stored values.
- Export CSV respects the current filter selection.
- Row actions:
  - View details modal
  - Edit alumni record
  - Delete alumni record
- Profile photo support:
  - avatar shown in table using object-fit:contain with border, matching the dashboard style
  - photo shown in a larger details modal using the same contain/center fit behavior
- View details modal includes expanded alumni profile values from the profile table:
  - father_name
  - linkedin
  - facebook
  - instagram
  - twitter
  - employment_from
  - employment_to
  - study_institution
  - study_degree
  - study_branch
  - study_from
  - study_to
  - previous_education
  - description
- The details modal was widened for easier scanning of long alumni records.
- Export CSV button in page header exports the alumni list fields shown in the current export route.

### 2.3 Edit Alumni Page
- Route: GET /admin/alumni/{id}/edit (name: admin.alumni.edit)
- Update route: PUT /admin/alumni/{id} (name: admin.alumni.update)
- Supports updating:
  - user account fields
  - profile fields
  - professional fields
  - profile photo upload
- Profile-side current employment fields now include:
  - current_status
  - company
  - employment_from
  - employment_to
- Study-related profile fields now include:
  - study_institution
  - study_degree
  - study_branch
  - study_from
  - study_to
- Long-form profile fields now include:
  - description
  - previous_education_text (admin textarea input parsed into profile.previous_education JSON)
- Work experience end-date behavior:
  - "Currently Working" checkbox sets professional.to to "Present".
- Current employment end-date behavior:
  - "Currently Working Here" checkbox sets profile.employment_to to "Present".
- Name field behavior:
  - if the stored account name is the generic placeholder "Alumni User", the form shows profile.full_name as the default editable value instead.
- Validation behavior:
  - name and email are required
  - optional text fields are nullable, so empty values no longer trigger "must be a string" messages
- Academic input behavior:
  - Degree is now a dropdown select.
  - Branch/Specialization is a dependent dropdown populated from the selected Degree.
  - When Degree is not selected, Branch/Specialization stays blank and disabled.
- Previous education input behavior:
  - admins enter one education row per line using the format: Institution | Degree | Branch | From | To
  - the controller converts each non-empty line into a structured JSON row for storage
- Change auditing:
  - alumni edit activity logs now capture profile-side employment date changes in addition to existing profile/professional updates

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
  - created or updated timestamp (see badge logic below)
  - published date
- Badge logic in the Recent News panel:
  - If created_at equals updated_at, a green "Created" badge is shown and the timestamp is labeled "Created:"
  - If created_at differs from updated_at, a blue "Updated" badge is shown and the timestamps are labeled "Updated:" and "Created:" (both shown)
  - This is determined at render time by fetching both created_at and updated_at for each item
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
  - created or updated timestamp (see badge logic below)
  - start date/time
- Badge logic in the Recent Events panel:
  - If created_at equals updated_at, a green "Created" badge is shown and the timestamp is labeled "Created:"
  - If created_at differs from updated_at, a blue "Updated" badge is shown and the timestamps are labeled "Updated:" and "Created:" (both shown)
  - This is determined at render time by fetching both created_at and updated_at for each item
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

### 2.9 Platform Route and Auth UX Updates (2026-04-28)
- Feed compatibility route added:
  - GET /feed (name: feed)
  - Authenticated users are redirected to /dashboard (which then applies role-based routing).
  - Effective role landing after redirect:
    - alumni/user role -> /dashboard
    - admin role -> /admin/dashboard
  - Unauthenticated users are redirected to /login by auth middleware.
- Password reset request page (/forgot-password) now shows clearer validation feedback:
  - top alert banner when validation fails
  - inline email error message for the email field
  - improved accessibility state through aria-invalid and aria-describedby attributes
- These updates reduce route confusion from legacy feed links and improve password reset usability for all roles.

### 2.10 Admin Jobs Management (2026-04-28)
- Create route: GET /admin/jobs/new (name: admin.jobs.create)
- Store route: POST /admin/jobs (name: admin.jobs.store)
- Manage route: GET /admin/jobs/manage (name: admin.jobs.manage)
- Edit route: GET /admin/jobs/{id}/edit (name: admin.jobs.edit)
- Update route: PUT /admin/jobs/{id} (name: admin.jobs.update)
- Delete route: DELETE /admin/jobs/{id} (name: admin.jobs.delete)
- Jobs create page includes full opportunity form support for:
  - type (job or internship)
  - title
  - company name and website
  - contact email
  - job area
  - experience level
  - work mode
  - location
  - skills
  - salary or stipend
  - application deadline
  - attachment upload
  - description
- Jobs create page includes a right-side Recent Jobs panel.
- Jobs manage page lists existing opportunities with per-item Update and Delete actions.
- Jobs edit page supports full update flow with existing value prefill and optional attachment replacement.
- File attachments are cleaned up on replacement and delete.
- Activity log events are recorded for jobs create/update/delete flows.

### 2.11 Admin Sidebar Consistency Refresh (2026-04-28)
- Admin sidebars were synchronized so all major management flyouts are present across admin pages.
- News, Events, Gallery, and Jobs flyout menus now appear consistently on:
  - dashboard
  - all alumni
  - alumni edit
  - activity logs
  - news create/manage/edit
  - events create/manage/edit
  - gallery create/manage/edit
  - jobs create/manage/edit
- Gallery and Jobs links now remain visible even when navigating from non-module pages (for example Activity Logs and Alumni pages).

### 2.12 Documentation Refresh Sync (2026-04-28)
- Final consistency pass completed for admin sidebar navigation coverage.
- Confirmed Gallery flyout visibility on previously missing admin surfaces:
  - Activity Logs page
  - All Alumni page
  - Edit Alumni page
- Confirmed Jobs and Gallery flyouts are now consistently available across the full admin page set.

### 2.13 Admin Dashboard View Rename (2026-04-30)
- `resources/views/admin/dashboard/panel.blade.php` renamed to `dashboard.blade.php`.
- Admin dashboard route now resolves to view `admin.dashboard.dashboard`.
- All internal references updated accordingly.

### 2.14 Shared Admin Sidebar Partial (2026-04-30)
- A shared sidebar partial was created at `resources/views/admin/partials/sidebar.blade.php`.
- All 19 admin Blade files now include the sidebar via:
  `@include('admin.partials.sidebar', ['activeSection' => '<section>'])`
- The partial accepts an `$activeSection` string and computes per-link active/inactive CSS classes in PHP.
- Active section values: `dashboard`, `alumni`, `news`, `events`, `gallery`, `jobs`, `engage`, `logs`.
- Sidebar logo container uses custom padding: `padding-top: 1.25rem; padding-bottom: 1.36rem;` (inline style override).
- Admin header bar uses Tailwind arbitrary value `pb-[1.7em]` for bottom padding on all admin pages.

### 2.15 Engage Feed Moderation Expansion (2026-04-30)
- Admin Engage module now supports moderation of all feed types: `post`, `news`, `event`, `testimonial`.
- New route: `GET /admin/engage/feed/{feedType}/{feedId}/review` (name: `admin.engage.feed.review`).
  - Handled by `AdminEngageController@reviewFeed`.
  - Accepts any `feedType` string and loads the corresponding source record (News, Event, FeedPost, or Testimonial).
- `engage-manage.blade.php` redesigned as a unified feed list showing all feed types with a Review button per item.
- `engage-edit.blade.php` redesigned as a generic feed review page showing:
  - Source content details (title, body, author, date)
  - Comments list with per-comment Delete button
  - Reactions/Likes list with per-reaction Delete button
  - Whether admin can delete the source item (`$canDeleteSource`)
- Review buttons are also placed directly on News, Events, Gallery, and Jobs manage pages:
  - Each item card includes a Review link pointing to `route('admin.engage.feed.review', [$type, $id])`
- `AdminEngageController` private helpers for source loading:
  - `loadFeedSourceData()` — dispatcher
  - `loadPostSourceData()` — loads FeedPost
  - `loadNewsSourceData()` — loads News
  - `loadEventSourceData()` — loads Event
  - `loadTestimonialSourceData()` — loads Testimonial
- `destroyComment()` and `destroyReaction()` now redirect back to `admin.engage.feed.review` after deletion.

### 2.16 Messaging Module Enhancements (2026-05-01)
- Admin sidebar Messages entry now opens the shared messaging inbox route:
  - GET /messages (name: messages.index)
- Conversations use display-name fallback logic so generic placeholder names are replaced by profile full name where available.
- Chat header presence rendering is role-aware:
  - for normal users: Online or Last seen
  - for admin counterpart: Administrator label (no online/last-seen)
- Message composer supports attachments from both admin and alumni chats.
- Supported attachment formats:
  - jpg, jpeg, png, gif
  - pdf, doc, docx
  - xls, xlsx
  - txt, zip
- Max attachment size: 10 MB.
- File-only messages are supported (text content can be empty).
- Attachment rendering in conversation:
  - image attachments show inline preview
  - non-image attachments show download links
- Outgoing messages show WhatsApp-style delivery/read status:
  - gray double tick: delivered
  - blue double tick: read
- New message popup includes user search by name/full name/passing year and can open direct chat.
- Message send interaction behavior:
  - Enter sends immediately
  - Shift+Enter inserts newline
  - multi-line message text is preserved in bubble rendering
  - optimistic in-page append is used for instant send feedback
- Conversation URLs no longer expose numeric IDs:
  - encrypted user token route parameter is used in /messages/{userToken}
  - invalid token access resolves to 404
- Messaging avatars now render in chat header, conversation list, and new-message search results:
  - profile photo first, then account avatar fallback, then initial
  - contain-fit styling is used to avoid image crop inside circular frames
- Core messaging routes in use:
  - GET /messages (messages.index)
  - GET /messages/{userToken} (messages.show)
  - POST /messages/{userToken} (messages.store)
  - GET /messages/users/search (messages.users.search)

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
- message_sent
- messages_marked_read

## 4. Sidebar and UI Consistency
- All 19 admin pages share a single sidebar partial: `resources/views/admin/partials/sidebar.blade.php`.
- Sidebar links are present across all admin pages:
  - Dashboard
  - All SRU Alumni
  - Messages
  - News -> View / New / Update/Delete
  - Events -> View / New / Update/Delete
  - Gallery -> View / New / Update/Delete
  - Jobs -> View / New / Update/Delete
  - Engage
  - Activity Logs
- Active nav item is highlighted using PHP-computed CSS classes based on the `$activeSection` variable passed to the partial.
- The News, Events, Gallery, and Jobs submenus on all admin pages use hover flyout menus that appear outside (to the right of) the sidebar.
- Admin sidebar profile avatar uses a consistent fit style across all admin pages (contain + center + white background + border).
- The bottom sidebar admin identity card is present across all admin pages.
- Logout hover visibility was normalized for icon-style sidebar variants.
- The same shared logo is rendered across all admin pages using the file public/images/logos/sru_logo_new.png.
- Logo container padding: `padding-top: 1.25rem; padding-bottom: 1.36rem;` applied via inline style.
- Admin page top header bar uses `pb-[1.7em]` (Tailwind arbitrary value) for consistent bottom padding.

## 5. Files Involved

### Backend
- routes/web.php
- app/Http/Controllers/AdminController.php
- app/Http/Controllers/AdminEngageController.php
- app/Http/Controllers/MessageController.php
- app/Http/Controllers/EventController.php
- app/Http/Controllers/NewsController.php
- app/Http/Controllers/ProfileController.php
- app/Http/Controllers/Auth/RegisteredUserController.php
- app/Models/User.php
- app/Models/Profile.php
- app/Models/News.php
- app/Models/Event.php
- app/Models/Message.php
- app/Models/FeedPost.php
- app/Models/FeedComment.php
- app/Models/FeedReaction.php
- app/Models/ActivityLog.php
- app/Http/Middleware/UpdateUserLastSeen.php
- database/migrations/2026_04_25_090000_create_activity_logs_table.php
- database/migrations/2026_04_25_111645_create_events_table.php
- database/migrations/2026_04_25_120000_add_avatar_to_users_table.php
- database/migrations/2026_04_26_create_messages_table.php
- database/migrations/2026_05_01_000001_add_attachment_to_messages_table.php
- database/migrations/2026_05_01_000002_make_message_content_nullable.php
- database/migrations/2026_05_01_000003_add_last_seen_at_to_users_table.php

### Admin Views
- resources/views/admin/partials/sidebar.blade.php (shared sidebar partial, included by all admin pages)
- resources/views/admin/dashboard/dashboard.blade.php (view: admin.dashboard.dashboard; previously panel.blade.php)
- resources/views/admin/alumni/allalumini.blade.php (view: admin.alumni.allalumini)
- resources/views/admin/alumni/edit-alumni.blade.php (view: admin.alumni.edit-alumni)
- resources/views/admin/logs/activity-logs.blade.php (view: admin.logs.activity-logs)
- resources/views/admin/news/news-create.blade.php (view: admin.news.news-create)
- resources/views/admin/news/news-manage.blade.php (view: admin.news.news-manage)
- resources/views/admin/news/news-edit.blade.php (view: admin.news.news-edit)
- resources/views/admin/events/event-create.blade.php (view: admin.events.event-create)
- resources/views/admin/events/event-manage.blade.php (view: admin.events.event-manage)
- resources/views/admin/events/event-edit.blade.php (view: admin.events.event-edit)
- resources/views/admin/gallery/gallery-create.blade.php (view: admin.gallery.gallery-create)
- resources/views/admin/gallery/gallery-manage.blade.php (view: admin.gallery.gallery-manage)
- resources/views/admin/gallery/gallery-edit.blade.php (view: admin.gallery.gallery-edit)
- resources/views/admin/jobs/jobs-create.blade.php (view: admin.jobs.jobs-create)
- resources/views/admin/jobs/jobs-manage.blade.php (view: admin.jobs.jobs-manage)
- resources/views/admin/jobs/jobs-edit.blade.php (view: admin.jobs.jobs-edit)
- resources/views/admin/engage/engage-manage.blade.php (unified feed list for all feed types)
- resources/views/admin/engage/engage-edit.blade.php (generic feed review page for comments/reactions)
- resources/views/admin/engage/engage-create.blade.php

### Admin Controllers
- app/Http/Controllers/AdminEngageController.php
  - `manage()` — unified feed items list (post/news/event/testimonial)
  - `reviewFeed(feedType, feedId)` — load review page for any feed type
  - `edit(id)` — legacy post review (redirects to reviewFeed)
  - `destroyComment(comment)` — delete comment, redirect to feed.review
  - `destroyReaction(reaction)` — delete reaction, redirect to feed.review

## 6. Data and Validation Notes
- father_name is now part of the profile data model.
- users.avatar stores the admin sidebar profile photo path.
- passing_year is treated as a 4-digit year string.
- profile employment_from is stored as a date.
- profile employment_to is stored as a string so it can hold either a date-like value or "Present".
- professional from/to fields are stored as strings to support "Present".
- profile photo uploads are validated as jpg/jpeg/png with size limit.
- admin sidebar avatar uploads are validated as jpg/jpeg/png with 2MB limit.
- activity log filter inputs are validated before query execution.
- Admin detail modals now expose stored profile social links when present.
- Admin detail modals now expose expanded profile/study/employment fields and preserve multiline previous education rendering.
- Admin edit form treats optional text inputs as nullable and preserves clearer required-field validation for name and email.
- Profile create/update validation includes stricter social-link URL checks for LinkedIn, Instagram, Facebook, and X/Twitter.

## 6.1 CSV Export Columns
The All SRU Alumni export currently includes these 24 columns:
- ID, Account Name, Email
- Full Name, Father Name, Phone, City, Country
- Degree, Branch / Specialization, Graduation Year, Current Status, Company
- LinkedIn, Facebook, Instagram, Twitter
- Organization, Industry, Role, Work From, Work To, Work Location
- Registered

Export notes:
- Values containing commas or quotes are properly escaped (RFC 4180).
- Empty values are exported as a plain hyphen (-).
- The current export implementation does not prepend a UTF-8 BOM.

## 7. Verification Checklist
Recommended checks:

1. Run migrations.
2. Open dashboard and verify the old blank placeholder area is replaced with two live containers: Latest News and Upcoming Events.
3. In dashboard, click a News card and an Event card and verify each opens the corresponding public detail page.
4. In dashboard, click News "View All" and Events "View All" and verify both open public listing pages in a new browser tab.
5. Open dashboard and verify the shared logo renders in the sidebar.
6. Open All SRU Alumni and verify photo avatars match dashboard style (contain + border), social links, and details modal.
7. On All SRU Alumni, switch filter types and verify the searchable value dropdown updates to the correct set of distinct values.
8. Apply a filter on All SRU Alumni and verify both the on-screen results and CSV export respect the same filter.
9. Open the larger View Details modal and verify the profile photo is contained without cropping and the expanded alumni fields render correctly.
10. Edit an alumni record and verify updates, photo upload, required-field messages, default name behavior, and the previous education textarea parsing.
11. In Edit Alumni, verify the Current Employment section supports employment start/end dates and that the "Currently Working Here" checkbox sets Employment To to "Present".
12. In Edit Alumni, verify Degree populates Branch/Specialization options and Branch stays blank/disabled when Degree is empty.
13. After editing an alumni record, open Activity Logs and verify the table shows only the summary line by default; hover the row and verify it expands to show per-field change details, including employment date changes when edited.
14. Trigger the same non-change action multiple times (for example opening the same admin page repeatedly), then open Activity Logs and verify those repeated events are grouped into one row; hover the grouped row and verify the expanded panel shows "First in group" timestamp and every occurrence timestamp in the timeline.
15. Use the Export CSV button on All SRU Alumni; verify the file has the documented 24 columns and filtered exports only include matching rows.
16. Click the admin sidebar avatar, upload a new image, and verify it updates on Dashboard, All SRU Alumni, Activity Logs, and Edit Alumni pages.
  Also verify the uploaded image is visually contained (not cropped) and aligns with the alumni dashboard fit style.
17. Create a new News item and verify the Recent News panel shows a green "Created" badge with a "Created:" timestamp.
    Then edit that News item and verify the panel now shows a blue "Updated" badge with both "Updated:" and "Created:" timestamps.
18. Create a new Event and verify the Recent Events panel shows a green "Created" badge with a "Created:" timestamp.
    Then edit that Event and verify the panel now shows a blue "Updated" badge with both "Updated:" and "Created:" timestamps.
17. Open Activity Logs with various filters (date range, actor, action type); verify filters work correctly and Export CSV respects the filtered results.
18. On Activity Logs page, verify the layout fits within a standard browser window without horizontal scroll; verify sidebar logo area aligns with top header.
19. Open Events -> New and verify the right-side "Recent Events" panel is visible and ordered by latest update time.
20. On Events -> Create form, verify all event fields are present:
    - title (required, text input)
    - excerpt (optional, textarea)
    - description (optional, large textarea)
    - event_type (required, dropdown with 4 options: Campus Events, Hackathons, Reunions, Webinars)
    - location (optional, text input)
    - start_at (required, datetime-local input)
    - end_at (optional, datetime-local input)
    - registration_link (optional, URL input)
    - image (optional, file input for jpg/jpeg/png/webp)
21. Create a new event with all fields and verify it appears in Recent Events panel and can be viewed on the public events page.
22. Open Events -> Update/Delete and verify the page lists events as cards with:
    - title (truncated if long)
    - excerpt (max 2 lines)
    - event type badge
    - start date/time
    - end date/time or "No end date" if empty
    - location (if present)
    - Update and Delete buttons
23. Edit an event and verify:
    - all fields are pre-populated with current values
    - image replacement works and old image is deleted
    - Activity Logs records an event_updated entry
    - Changes appear immediately on public events page
24. Delete an event and verify:
    - the record is removed from manage page
    - any stored image file is deleted
    - Activity Logs records an event_deleted entry
    - event no longer appears on public events page
25. On Events create/manage/edit pages, verify:
  - the Events submenu appears on hover as an outside-sidebar flyout with View / New / Update/Delete options
    - the submenu is not permanently expanded
    - the correct sub-link is highlighted based on current page
    - the bottom sidebar admin card is visible
26. On News -> Update/Delete and News edit pages, verify the bottom sidebar admin card is visible and matches the rest of the admin pages.
27. On News create/manage/edit pages, verify the News submenu appears on hover as an outside-sidebar flyout and is not permanently expanded.
28. Edit a news item and verify updated values are saved, optional image replacement works, and Activity Logs records a news_updated entry.
29. Delete a news item and verify the record is removed, any stored image file is deleted, and Activity Logs records a news_deleted entry.

### Engage / Feed Moderation
30. Open Admin -> Engage and verify the manage page lists all feed types (posts, news, events, testimonials) in a unified list with a Review button per item.
31. Click Review on a feed post and verify the review page shows the source content, comments list, and reactions list with per-item Delete buttons.
32. Click Review on a News item from the News manage page and verify the review page loads the correct news source data and its associated comments/reactions.
33. Click Review on an Event item from the Events manage page and verify the review page loads the correct event source data.
34. Delete a comment from the feed review page and verify the page reloads to the same review URL with the comment removed.
35. Delete a reaction from the feed review page and verify the page reloads to the same review URL with the reaction removed.

## 8. Notes
- The URL path now uses /admin/all-alumini while the route name remains admin.allalumini for compatibility.
- The older URL /admin/allalumini is preserved as a redirect.
- If renamed to allalumni in future, route names and view references must be updated together.
- Admin Blade files are now grouped by module under resources/views/admin (dashboard, alumni, logs, news, events, gallery, jobs, engage, partials).
- A single shared sidebar partial (`resources/views/admin/partials/sidebar.blade.php`) is included by every admin page; sidebar changes need only be made in one place.
- Alumni admin editing now stores two parallel employment concepts:
  - profile-level current employment summary (company + employment_from + employment_to)
  - professional history/work details (organization + industry + role + from + to + location)
- News and Events management modules use identical CRUD patterns:
  - Create page with recent items right panel
  - Manage page with card grid and per-item action buttons
  - Edit page with form and back link
  - Image upload support with automatic cleanup on replacement/deletion
  - Activity log recording for all create/update/delete operations
- All four content modules (News, Events, Gallery, Jobs) include a Review button on their manage pages linking to the engage feed review route.
- Both modules are fully integrated into the admin sidebar with hover submenus on all admin pages.
- Jobs and Gallery modules are also fully integrated into the admin sidebar with hover submenus on all admin pages.
- News and Events flyout submenus are rendered outside the sidebar so they remain visible without shrinking/stacking inside the nav column.
- Gallery and Jobs flyout submenus use the same outside-sidebar rendering behavior.
- Dashboard News and Events blocks are wired to live database data and link to public pages, while admin edit actions remain available through module manage pages.
- The engage moderation page (`engage-edit.blade.php`) now works for any feed type (post/news/event/testimonial), not just posts.
- Admin dashboard view was renamed: `panel.blade.php` → `dashboard.blade.php` (route: `admin.dashboard.dashboard`).
