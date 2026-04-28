---
name: webapp-functional-audit
description: 'Test a project URL end-to-end in the browser, check whether pages open correctly, detect visible errors or broken flows, capture screenshots, perform read-only database validation, and write a report with findings. Use when the user asks to test a web app, verify all major functionality, audit routes, investigate failing pages, or produce a screenshot-based QA report with backend data checks.'
argument-hint: 'Provide the project URL, any login details or roles, any critical flows to test, and how database checks should be performed.'
user-invocable: true
---

# Webapp Functional Audit

## When to Use

- Use for browser-based smoke testing or functional QA of a running web application.
- Use when the user wants confirmation that pages open correctly and core flows work.
- Use when the user wants a written report plus screenshots saved into the repository.
- Use when the user wants read-only database checks tied to the pages or flows being tested.

## Required Inputs

- Base URL to test.
- Authentication details if login is required.
- Any role-specific accounts that should be exercised.
- Any flows that are destructive or should be avoided unless explicitly approved.
- Database access strategy: framework command, local DB credentials, seeded test DB, or explicit instruction to skip DB verification.
- Any sensitive tables, records, or environments that must not be touched.

## Deliverables

Create a new timestamped folder under `reports/webapp-functional-audit/` for each run.

Use the standard templates at [./assets/report-template.md](./assets/report-template.md) and [./assets/database-checks-template.md](./assets/database-checks-template.md).
To initialize the folder structure and template files quickly, use [./scripts/initialize-report.ps1](./scripts/initialize-report.ps1).

Each run folder must contain:

- `report.md`: summary, findings, visited pages, failures, blocked areas, and follow-up actions.
- `database-checks.md`: read-only database observations, schema or migration status notes, and UI-to-data consistency checks.
- `screenshots/`: screenshots for failures and important checkpoints.
- `artifacts/`: optional extra notes or copied page snapshots when useful.

## Safety Rules

- Do not perform destructive actions unless the user explicitly asks for them.
- Prefer read-only verification first: navigation, search, filters, pagination, form validation, and preview flows.
- If a flow could mutate production data, stop before submission unless approval is explicit.
- If authentication is required and credentials are missing, test all public pages first and clearly mark protected areas as blocked.
- Keep database checks read-only unless the user explicitly approves writes in a non-production environment.
- Never run truncate, delete, update, or reseed operations as part of this audit.

## Procedure

1. Confirm the target URL and whether the app is already running.
2. Create a new run folder at `reports/webapp-functional-audit/<timestamp>/` with `screenshots/` and `artifacts/` subfolders.
3. Prefer standardized initialization:
   - Run `pwsh ./scripts/initialize-report.ps1 -RunFolder <absolute-or-relative-run-folder>` from the skill directory or adapt the same structure manually if script execution is unavailable.
4. Start `report.md` and `database-checks.md` immediately so evidence is captured as the audit runs.
5. Open the site in the integrated browser.
6. Record the landing page result in the report: success, redirect loop, server error, blank state, broken assets, or blocked login.
7. Discover major user journeys from visible navigation, landing-page links, dashboard menus, buttons, forms, and pagination controls.
8. Test each reachable page or flow one at a time.
9. For every page visited, verify:
   - The page loads and is not blank.
   - The page does not show a visible error message, exception, 403, 404, 419, or 500 page.
   - Core interactive elements appear usable.
   - Navigation away from the page still works.
10. For forms, verify only safe behavior unless destructive submission is approved:
   - Page opens.
   - Inputs accept text.
   - Validation messages appear when expected.
   - Submission result is recorded only when safe to execute.
11. Capture screenshots for:
   - Every failure.
   - Every blocked or suspicious page.
   - Key milestone pages such as login, dashboard, listings, detail pages, and form screens.
12. Run database checks using the safest available read-only method for the project:
   - Prefer framework-native commands and read-only queries.
   - Confirm database connectivity and record whether it succeeded.
   - Record migration status if the framework provides a safe status command.
   - For flows that display records, sample whether the UI appears consistent with backing data counts or recent rows.
   - If DB access is unavailable, record the exact blocker and continue with browser-only coverage.
13. When a page fails, triangulate with backend evidence when available: application logs, framework exceptions, or read-only database observations.
14. Update `report.md` and `database-checks.md` continuously during the run instead of waiting until the end.
15. Finish with a concise pass/fail summary and a prioritized issue list.

## Decision Rules

- If the app redirects to login, authenticate if credentials were provided; otherwise mark authenticated coverage as blocked.
- If a page crashes, fails to load, or shows a framework error page, capture a screenshot immediately and record exact reproduction steps.
- If a link opens a new page or tab, test that page too if it remains in scope.
- If navigation reveals repeated layout-only pages with the same shell and no distinct behavior, sample enough of them to establish the pattern and note the sampling rule in the report.
- If the app contains admin-only or role-gated areas, test each provided role separately and label findings by role.
- If the site exposes obviously broken assets, missing styles, or dead buttons, record them as defects even if the route itself loads.
- If DB credentials or connection details are missing, do not guess or try unsafe access methods; mark DB validation as blocked and state what is needed.
- If the environment appears to be production, avoid any action that creates, edits, or deletes records unless the user explicitly approved it.
- If UI behavior and DB state disagree, record both sides of the mismatch and include the exact query or command used for verification.

## Minimum Report Format

Write `report.md` with these sections:

1. Target
2. Test Time
3. Access Context
4. Coverage Summary
5. Passed Areas
6. Failed Areas
7. Blocked Areas
8. Findings Table
9. Screenshots
10. Recommended Next Actions

Write `database-checks.md` with these sections:

1. Database Access Method
2. Environment Notes
3. Connectivity Result
4. Migration or Schema Status
5. Data Consistency Checks
6. Blockers or Risks
7. Commands or Queries Used

The findings table should include:

- Severity
- Page or flow
- Problem
- Reproduction steps
- Screenshot path
- Status

## Completion Criteria

The audit is complete only when all of the following are true:

- A new timestamped report folder exists.
- `report.md` exists and lists what was tested.
- `database-checks.md` exists, even if only to document why DB checks were blocked.
- Failures and blocked areas are separated clearly.
- Every material failure has at least one screenshot.
- The report states any untested areas and why they were not covered.

## Example Invocation

`/webapp-functional-audit http://localhost:8000 alumni login flow dashboard jobs profile edit`