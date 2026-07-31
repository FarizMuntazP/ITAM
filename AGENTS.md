# ITAM — IT Asset Management (Laravel 13)

## Quick start
- `composer setup` — full first-time setup (install, .env, key, migrate, npm build)
- `composer dev` — concurrent dev servers (php artisan serve + queue + logs + vite)
- `composer test` — runs `config:clear` then `php artisan test`
- `php artisan itam:backup` — ZIP backup of DB JSON + public storage
- `php artisan storage:link` — required once for photo/QR access

## Framework & config
- Laravel ^13.8, PHP ^8.3, SQLite default (DB_CONNECTION=sqlite in `.env.example`)
- Timezone: `Asia/Jakarta`
- Auth: username-based (not email), custom `AuthController`, default `admin`/`admin`
- Role column on users: `superadmin`, `admin`

## Key packages
- `maatwebsite/excel` — import/export
- `simplesoftwareio/simple-qrcode` — QR code generation (SVG)
- Photo compression via `AssetImageService` (GD lib). Settings in `config/itam_images.php` (max 1200px, 80 quality, 320px thumbnail)

## Routes & controllers
- Custom routes (`generate-id`, `export`, `import`, `template`, `qr/*`) must be declared **before** `Route::resource('assets')` to avoid resource wildcard capture
- All routes except `/login` are under `auth` middleware
- Controllers: `AssetController`, `ExcelController`, `AuthController`, `DashboardController`, `StoreController`, `CategoryController`, `EmployeeController`, `AssetLoanController`, `AssetMaintenanceController`, `ActivityLogController`

## Models & schema
- **Asset** — supports `asset_type` (`unit` = individual SN, `bulk` = qty without SN), `quantity`, `current_employee_id` (FK→employees)
- **Asset ID format**: `ITAM-{CATEGORY_CODE}-{SEQUENCE}` — generated via `Asset::generateAssetId()` using `categories.current_sequence` with `lockForUpdate()` for race condition safety
- **Employee code**: auto `EMP-XXXX`
- **AssetObserver** — logs all create/update changes to `asset_activities` table
- Migrations: 15 files covering users, stores, categories, assets, employees, asset_loans, asset_activities, asset_maintenances

## Import flow
1. Upload → preview (dry run) → session stores preview path
2. User confirms → `import_action=confirm` with `preview_path` → actual insert
3. Preview does NOT persist; `AssetsImport(dryRun: true)` skips DB writes
4. Store lookup supports: store code, numeric code (padded/unpadded), store name (case-insensitive)

## Frontend
- Tailwind CSS v4, Vite, Blade templates
- Custom dark theme: `#111111` bg, `#fecb00` yellow accent, `#1e1e1e` cards

## Testing
- `RefreshDatabase` trait + SQLite `:memory:` — fast, no external DB needed
- Main test file: `tests/Feature/ItamFlowTest.php` (1507 lines covering login, CRUD, import, export, maintenance, loans, sorting, activity logs, dashboard analytics, storage stats)
- Single test: `php artisan test --filter test_name`

## Sort & pagination
- Default sort: `added_at desc`
- Allowed sort columns for assets: `asset_id`, `asset_name`, `condition`, `status`, `added_at`, `purchase_price`, `category`, `store` (last two use JOIN)
- Pagination options: 10, 25 (default), 50 — per_page query param
- Employees sortable by: `store`, `assets_count`

## Seeder
- `DatabaseSeeder` calls: `UserSeeder` (admin/admin superadmin), `CategorySeeder`, `StoreSeeder` (60 stores), `AssetSeeder`, `EmployeeSeeder`

## Requirements & gotchas
- GD extension (`php_gd`) must be enabled in `php.ini` for QR image download (SVG→PNG) and photo compression
- QR codes stored as SVG in `storage/app/public/qrcodes/`
- Photo filenames: `{asset_id}_{timestamp}_{uniqid}.jpg`, stored in `assets/photos/` + thumbnails in `assets/photos/thumbnails/`
- Old photos/QR are auto-deleted on update
- Routes with `{asset}` parameter expect DB `id` (not `asset_id` string). Use `/assets/lookup/{assetId}` to find by asset_id string
