# Feed 404 Follow-Up (2026-04-28)

## Scope
- Focus only on `/feed` regression and related 500-error history.

## Runtime Verification
- `GET /feed` currently returns `404 Not Found`.
- `GET /dashboard` currently returns `302` when unauthenticated (expected redirect to login).

## Route Trace
- No explicit page route exists for `/feed` in `routes/web.php`.
- Feed UI is rendered inside `/dashboard`, not `/feed`.
- Feed interaction APIs are nested under `/dashboard/feed/*`.

## Exact Backend Cause of `/feed` 404
- Root cause: missing route definition for `/feed`.
- Existing implementation serves feed within dashboard route and dashboard blade.
- Any UI link/navigation pointing to `/feed` will always hit Laravel's 404 handler unless a dedicated route is added.

## 500 Issue Trace
- Historical 500s in logs were observed, but not from `/feed` missing route.
- Most relevant historical feed-related 500 entry:
  - `Route [dashboard.feed.posts.store] not defined` while rendering dashboard view.
  - This indicates an earlier route-name mismatch/declaration-order issue that appears resolved in current route list.
- Additional historical 500s were also found for unrelated gallery/profile issues.

## Current Status
- `/feed` issue is an active routing bug (404), not a current 500.
- No new feed-specific 500 error was reproduced during this follow-up.

## Recommended Fix Paths
1. Preferred: update frontend links/buttons to use `/dashboard` for feed view.
2. Optional compatibility route: add `Route::get('/feed', fn() => redirect()->route('dashboard'));` for backward compatibility.
3. Keep dashboard feed action routes under `/dashboard/feed/*` as currently implemented.

## Resolution Applied (2026-04-28)
- Added authenticated compatibility route for `/feed` that redirects to `/dashboard`.
- Improved forgot-password validation visibility by rendering a top alert and inline error text after failed submit.
- Added feature tests to lock regressions:
  - `tests/Feature/FeedRouteTest.php`
  - `tests/Feature/Auth/PasswordResetTest.php` (missing email validation case)

## Post-Fix Verification
- Browser check: unauthenticated `GET /feed` now redirects to login flow instead of 404.
- Browser check: submitting `/forgot-password` without email now shows visible validation feedback (`The email field is required.`).
- Automated tests: targeted auth/feed suite passing.
