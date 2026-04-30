# SRU Alumni Portal

A web platform for Sir Syed University of Engineering & Technology (SRUET) alumni — connecting graduates, sharing opportunities, and enabling institutional engagement.

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Features](#features)
- [Project Structure](#project-structure)
- [Getting Started](#getting-started)
- [Environment Configuration](#environment-configuration)
- [Running the Application](#running-the-application)
- [Database](#database)
- [User Roles](#user-roles)
- [Admin Panel](#admin-panel)
- [Documentation](#documentation)

---

## Overview

The SRU Alumni Portal allows university alumni to:
- Register and maintain professional profiles
- Connect with fellow alumni
- Browse and engage with news, events, and job opportunities
- Share posts, like and comment on content
- View the alumni gallery and institutional updates

Administrators can manage all content and alumni data through a dedicated admin panel with full moderation capabilities.

---

## Tech Stack

| Layer       | Technology                                         |
|-------------|----------------------------------------------------|
| Backend     | PHP 8.2+, Laravel 12                               |
| Frontend    | Blade templates, Tailwind CSS 3, Alpine.js         |
| Build Tool  | Vite 7                                             |
| Database    | MySQL / SQLite (configurable)                      |
| Testing     | PestPHP 3                                          |
| Mail        | SMTP (configurable via `.env`)                     |

---

## Features

### Alumni Side
- **Registration & Login** — email-based registration; system-generated password sent by email
- **Profile** — two-step profile creation with personal, academic, and professional details; social links (LinkedIn, Facebook, Instagram, X/Twitter)
- **News** — public news listing and detail pages
- **Events** — upcoming and past events with registration links
- **Gallery** — photo albums and videos
- **Jobs** — job and internship opportunities posted by alumni/admin
- **Engage (Feed)** — post updates, like and comment on posts/news/events/testimonials
- **Connections** — connect with other alumni
- **Messages** — direct messaging between alumni
- **Achievements** — showcase personal and professional achievements
- **Skills & Endorsements** — add skills and receive peer endorsements
- **Testimonials** — give and receive testimonials

### Admin Side
- **Dashboard** — live stats (total alumni, news, events), recent activity feed
- **Alumni Management** — view, search, filter, edit, and delete alumni records; CSV export
- **News Management** — create, edit, and delete news articles with image upload
- **Events Management** — create, edit, and delete events with full field support
- **Gallery Management** — manage photo albums and videos
- **Jobs Management** — manage job and internship postings
- **Engage / Feed Moderation** — review and delete comments and reactions across all feed types (posts, news, events, testimonials)
- **Activity Logs** — full audit trail with grouping, hover-expanded change details, and CSV export
- **Admin Profile** — sidebar avatar upload

---

## Project Structure

```
app/
  Http/Controllers/       # All controllers (admin and alumni)
  Models/                 # Eloquent models
  Mail/                   # Mailable classes
resources/
  views/
    admin/                # Admin Blade templates (grouped by module)
      partials/           # Shared sidebar partial
      dashboard/
      alumni/
      news/
      events/
      gallery/
      jobs/
      engage/
      logs/
    pages/                # Public-facing alumni pages
database/
  migrations/             # All migration files
  seeders/                # Database seeders
public/
  images/                 # Uploaded images and logos
routes/
  web.php                 # All application routes
docs/                     # Extended feature documentation
```

---

## Getting Started

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18 and npm
- A database (MySQL recommended for production; SQLite works for local dev)
- Mail server or Mailtrap credentials

### Installation

```bash
# Clone the repository
git clone <repository-url>
cd sru-web

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

---

## Environment Configuration

Edit `.env` with your local values:

```env
APP_NAME="SRU Alumni Portal"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sru_alumni
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=no-reply@sru-alumni.com
MAIL_FROM_NAME="SRU Alumni Portal"
```

---

## Running the Application

```bash
# Run database migrations
php artisan migrate

# (Optional) Seed the database with sample data
php artisan db:seed

# Start the Laravel development server
php artisan serve

# In a separate terminal, compile frontend assets (watch mode)
npm run dev
```

For a production build:

```bash
npm run build
```

---

## Database

All schema changes are handled via Laravel migrations in `database/migrations/`.

Key tables:

| Table                    | Purpose                                      |
|--------------------------|----------------------------------------------|
| `users`                  | Authentication and account data              |
| `profiles`               | Alumni personal, academic, and social data   |
| `professionals`          | Work history entries per alumni              |
| `news`                   | News articles                                |
| `events`                 | Events with scheduling fields                |
| `job_opportunities`      | Job and internship listings                  |
| `feed_posts`             | Alumni feed posts                            |
| `feed_comments`          | Comments on any feed type                    |
| `feed_reactions`         | Likes/reactions on any feed type             |
| `gallery_albums`         | Photo albums                                 |
| `gallery_album_photos`   | Photos within albums                         |
| `gallery_videos`         | Gallery video entries                        |
| `connections`            | Alumni-to-alumni connection records          |
| `messages`               | Direct messages between alumni               |
| `skills`                 | Alumni skills                                |
| `skill_endorsements`     | Peer endorsements for skills                 |
| `achievements`           | Personal and professional achievements       |
| `testimonials`           | Alumni testimonials                          |
| `activity_logs`          | Full admin audit trail                       |

---

## User Roles

| Role    | Access                                          |
|---------|-------------------------------------------------|
| `admin` | Full admin panel + all alumni-side pages        |
| `alumni`| Alumni dashboard, profile, feed, gallery, etc.  |

Role is stored on the `users` table and enforced via the `admin` middleware.

### Seeding an Admin Account

```bash
php artisan db:seed --class=AdminUserSeeder
```

---

## Admin Panel

The admin panel is accessible at `/admin/dashboard` (requires admin role).

Key routes:

| Section        | URL                          |
|----------------|------------------------------|
| Dashboard      | `/admin/dashboard`           |
| All Alumni     | `/admin/all-alumini`         |
| News           | `/admin/news/manage`         |
| Events         | `/admin/events/manage`       |
| Gallery        | `/admin/gallery/manage`      |
| Jobs           | `/admin/jobs/manage`         |
| Engage/Feed    | `/admin/engage/manage`       |
| Activity Logs  | `/admin/activity-logs`       |

All admin pages share a single sidebar partial (`resources/views/admin/partials/sidebar.blade.php`) with active-state highlighting per section.

---

## Documentation

Extended documentation is in the `docs/` folder:

- [`docs/ADMIN_DASHBOARD_DOCUMENTATION.md`](docs/ADMIN_DASHBOARD_DOCUMENTATION.md) — full admin feature reference, routes, validation rules, and verification checklist
- [`docs/ALUMNI_USER_DOCUMENTATION.md`](docs/ALUMNI_USER_DOCUMENTATION.md) — alumni user journey, profile flow, and validation rules

---

## License

This project is proprietary software developed for Sir Syed University of Engineering & Technology alumni management.
