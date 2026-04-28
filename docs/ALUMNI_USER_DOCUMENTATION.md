# Alumni User Documentation

## 1. Objective
This document explains the alumni user-side flow in the SRU web application.
It covers:
- registration and login
- profile creation and editing
- mandatory fields and validation rules
- user-visible routes
- common errors and fixes

## 2. Alumni User Journey

### 2.1 Register
- Route: GET /register
- Submit: POST /register
- Input required:
  - email
- Behavior:
  - account is created with default name "Alumni User"
  - system generates an 8-character random password
  - password is emailed to the registered address
  - user is redirected to login page

### 2.2 Login
- Route: GET /login
- Submit: POST /login
- Post-login redirects:
  - admin users -> /admin/dashboard
  - alumni users with existing profile -> /profile
  - alumni users without profile -> /profile/create

### 2.3 View Profile
- Route: GET /profile (auth required)
- Displays:
  - basic profile details
  - social links
  - education details
  - current status (studying or working)
  - current educational details when status is studying
  - professional experience list
- If no profile exists, the page prompts user to complete profile.

## 3. Profile Creation

### 3.1 Create Page
- Route: GET /profile/create
- Submit: POST /profile/store
- Auth required
- Form has 2 steps:
  - Step 1: basic details + education + social links
  - Step 2: current status choice and conditional details
    - Option A: currently studying -> educational details form
    - Option B: currently working -> professional experience entries

### 3.2 Mandatory Fields at Creation
Required fields:
- Full Name
- Father's Name
- Mobile Number
- City
- Country
- Degree
- Specialization / Branch
- Passing Year
- Current Status (studying or working)
- LinkedIn URL
- Instagram URL
- Facebook URL
- X URL

Optional fields:
- Profile photo

Conditionally required when Current Status = studying:
- Current Institution / College
- Current Degree
- Current Specialization / Branch
- Study From date
- Study To value (date or Present)

Conditionally required when Current Status = working:
- At least one professional experience row

### 3.3 Creation Validation Rules

Mobile:
- digits only
- length 10 to 15
- regex: ^[0-9]{10,15}$

Current status:
- required
- allowed values: studying, working

Current study fields:
- required only when current_status is studying
- study_from must be a valid date
- study_to accepts date text values so Present can be stored

Professional experience fields:
- at least one organization is required when current_status is working
- from uses date values
- to accepts date text values so Present can be stored

Social links:
- LinkedIn: required, valid URL, must be linkedin.com
- Instagram: required, valid URL, must be instagram.com
- Facebook: required, valid URL, must be facebook.com
- X: required, valid URL, must be x.com or twitter.com

Profile photo:
- optional image
- allowed: jpg, jpeg, png
- max size: 2 MB

Duplicate protection:
- If profile already exists for the logged-in user, creation is blocked.

### 3.4 Degree and Specialization Mapping
The specialization dropdown is dynamically populated from selected degree.
Current mapped degrees:
- B.Tech
- Business
- Agriculture
- B.Sc
- B.Com
- BCA

## 4. Profile Editing

### 4.1 Edit Page
- Route: GET /profile/edit
- Submit: POST /profile/update
- Auth required

### 4.2 Editable Fields
- City
- Country
- LinkedIn URL
- Instagram URL
- Facebook URL
- X URL
- Profile photo
- Professional experience rows

Note:
- The create flow includes status-based study/work inputs.
- The edit page currently focuses on social/location/photo and experience updates.

Read-only on edit page:
- Father's Name

### 4.3 Mandatory Fields at Edit
Required fields:
- City
- Country
- LinkedIn URL
- Instagram URL
- Facebook URL
- X URL

### 4.4 Edit Validation Rules
Social URL rules are the same as creation:
- LinkedIn must be linkedin.com
- Instagram must be instagram.com
- Facebook must be facebook.com
- X must be x.com or twitter.com

Profile photo rules are unchanged (optional image, jpg/jpeg/png, max 2 MB).

## 5. Status and Timeline Data
Current status is stored in profiles table:
- current_status (studying or working)

When status is studying, current educational details are stored in profiles table:
- study_institution
- study_degree
- study_branch
- study_from
- study_to

Experience is stored in professionals table with these values:
- organization
- role
- industry
- location
- from
- to

Present support:
- For currently studying users, study_to can be stored as Present.
- For currently working users, experience to can be stored as Present.

Update behavior:
- existing experience rows for user are deleted and recreated from submitted form data

## 6. Activity Tracking
User-side actions recorded in activity logs include:
- user_registered
- user_logged_in
- user_logged_out
- profile_created
- profile_updated

## 7. User Routes Summary
Public or guest:
- /register
- /login
- /forgot-password
- /reset-password/{token}

Authenticated alumni:
- /profile
- /profile/create
- /profile/store
- /profile/edit
- /profile/update
- /logout

## 7.1 Recent User-Facing Updates (2026-04-28)

### Feed Route Compatibility
- Canonical alumni feed experience remains inside dashboard route: GET /dashboard.
- Compatibility route added: GET /feed (name: feed).
- Behavior:
  - authenticated users on /feed are redirected to /dashboard
  - unauthenticated users on /feed are redirected to /login

### Forgot Password Validation Visibility
- Route: GET /forgot-password, Submit: POST /forgot-password.
- When email is missing or invalid, users now see:
  - a visible top-level alert explaining the validation error
  - an inline email field error message
- Accessibility improvements include:
  - aria-invalid state when email validation fails
  - aria-describedby linkage to the email error text

## 7.2 Local Login Host Consistency Note (2026-04-28)

- Local development access should use a single consistent host for auth flows.
- Recommended local URL: http://127.0.0.1:8000
- Mixing localhost and 127.0.0.1 in separate tabs can cause session/CSRF mismatch symptoms, including:
  - login POST returning HTTP 419
  - form submit failures even with a visible CSRF field
- Environment alignment applied:
  - APP_URL updated to http://127.0.0.1:8000 in local environment
- If a 419 occurs in local testing:
  - open only one host variant (127.0.0.1 or localhost, not both)
  - hard-refresh login page
  - clear cookies for both host variants if stale sessions remain

### Documentation Sync Note (2026-04-28)
- Documentation was refreshed after admin navigation consistency fixes.
- Cross-role behavior for alumni routes is unchanged; this note keeps user docs aligned with the latest admin-side navigation and local-auth troubleshooting updates.

## 8. Common Issues and Fixes

### 8.1 Unknown column father_name
Error example:
- SQLSTATE[42S22]: Unknown column 'father_name'

Cause:
- migration for father_name not applied in current database

Fix:
- run: php artisan migrate
- verify: php artisan migrate:status

### 8.2 Social link rejected
Cause:
- wrong domain in field (for example, non-facebook URL in Facebook field)

Fix:
- enter URL from required domain for that field

### 8.3 Mobile number rejected
Cause:
- contains non-digits or wrong length

Fix:
- enter only digits with total length 10 to 15

### 8.4 Login POST returns 419 in local environment
Cause:
- host mismatch between active browser tabs or cookies (localhost vs 127.0.0.1)
- stale session cookie after APP_URL changes

Fix:
- use a single host consistently: http://127.0.0.1:8000
- clear browser cookies for localhost and 127.0.0.1
- reload login page and retry authentication

## 9. Files Involved (User Side)

Routes and controllers:
- routes/web.php
- routes/auth.php
- app/Http/Controllers/Auth/RegisteredUserController.php
- app/Http/Controllers/Auth/AuthenticatedSessionController.php
- app/Http/Controllers/ProfileController.php

Views:
- resources/views/auth/register.blade.php
- resources/views/auth/login.blade.php
- resources/views/profile/create.blade.php
- resources/views/profile/edit.blade.php
- resources/views/profile/profile.blade.php

Models and migrations:
- app/Models/Profile.php
- app/Models/Professional.php
- app/Models/ActivityLog.php
- database/migrations/2026_04_23_042025_create_profiles_table.php
- database/migrations/2026_04_23_053819_create_professionals_table.php
- database/migrations/2026_04_25_120000_add_father_name_to_profiles_table.php
- database/migrations/2026_04_27_120000_add_current_study_fields_to_profiles_table.php
- database/migrations/2026_04_25_090000_create_activity_logs_table.php
