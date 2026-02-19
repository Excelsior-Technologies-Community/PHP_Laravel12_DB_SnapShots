# PHP_Laravel12_DB_SnapShots
## Overview

Laravel Database Snapshots Application is a complete backup and restore management system built with Laravel 12 and the spatie/laravel-db-snapshots package.

This project allows developers and administrators to:

* Create database snapshots
* Restore database state from snapshots
* Download snapshot files
* Compare snapshots
* Schedule automatic backups
* Manage snapshots through a clean web interface

The system includes a full Post CRUD module to demonstrate real-time snapshot functionality.

---

## Features

### Core Features

* Create database snapshots (Web + CLI)
* Restore database from snapshot
* Download snapshot files
* Delete snapshots
* Compare snapshot metadata
* Snapshot compression support
* Scheduled automatic backups
* Old snapshot cleanup automation

### Application Features

* Post CRUD system
* Seeder and Factory support
* Pagination
* Tailwind CSS UI
* Snapshot statistics dashboard

### Technical Stack

* Laravel 12 MVC Architecture
* MySQL Database
* Eloquent ORM
* Tailwind CSS
* Spatie Laravel DB Snapshots Package
* Artisan Console Commands

---

## Prerequisites

* PHP 8.2 or higher
* Composer
* MySQL 5.7 or higher
* Node.js (optional)

---

## Installation Guide

### Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel laravel-db-snapshots-app
cd laravel-db-snapshots-app
```

### Step 2: Install Snapshot Package

```bash
composer require spatie/laravel-db-snapshots
```

### Step 3: Publish Configuration

```bash
php artisan vendor:publish --provider="Spatie\DbSnapshots\DbSnapshotsServiceProvider"
```

### Step 4: Configure Database

Update `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_snapshots
DB_USERNAME=root
DB_PASSWORD=

SNAPSHOT_DISK=local
```

Create database manually in MySQL.

### Step 5: Configure Filesystem Disk

Add in `config/filesystems.php`:

```
'snapshots' => [
    'driver' => 'local',
    'root' => database_path('snapshots'),
],
```

Update `config/db-snapshots.php`:

```
'disk' => env('SNAPSHOT_DISK', 'snapshots'),
'default_connection' => env('DB_CONNECTION', 'mysql'),
'compress' => true,
```

---

## Database Setup

### Run Migrations

```bash
php artisan migrate
```

### Seed Sample Data

```bash
php artisan db:seed --class=PostSeeder
```

---

## Running the Application

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000
```
<img width="1713" height="967" alt="image" src="https://github.com/user-attachments/assets/4e469bf8-4716-48be-81e5-a7da3db9f45c" />
<img width="1686" height="764" alt="image" src="https://github.com/user-attachments/assets/eb5a76ec-5d7d-4938-888b-2329c1092fe6" />
<img width="1693" height="966" alt="image" src="https://github.com/user-attachments/assets/4ef40ec5-6384-4bb6-914b-1e24a1ab68b7" />

---

## Snapshot Management

### Create Snapshot (CLI)

```bash
php artisan snapshot:create "initial-data"
```

### Create Snapshot (Web)

Go to:

```
/snapshots
```

Enter snapshot name and click Create.

### Load Snapshot

```bash
php artisan db:snapshot:load "initial-data"
```

Or use Load button in UI.

### Delete Snapshot

```bash
php artisan db:snapshot:delete "initial-data"
```

### List Snapshots

```bash
php artisan db:snapshot:list
```

---

## Snapshot Scheduling

In `app/Console/Kernel.php`:

* Daily backup at midnight
* Weekly backup on Sunday
* Automatic cleanup of old snapshots

Ensure scheduler is running:

```bash
php artisan schedule:work
```

Or configure cron job.

---

## Snapshot Comparison

The comparison feature displays:

* Snapshot name
* Creation date
* File size
* Connection used
* Time difference
* Size difference

---

## Project Structure

```
laravel-db-snapshots-app/
├── app/
│   ├── Console/Commands/
│   ├── Http/Controllers/
│   └── Models/
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── seeders/
│   └── snapshots/
├── resources/views/
├── routes/web.php
└── config/
```

---

## Real-World Use Cases

* Production database backups
* Pre-deployment safety snapshots
* Testing data restoration
* Multi-environment data migration
* Disaster recovery planning

---

## Best Practices

* Always test restore process
* Enable compression for production
* Limit stored snapshot count
* Store critical backups on remote storage (S3)
* Schedule automated daily backups

---

## Future Enhancements

* REST API for snapshot management
* Remote cloud storage integration
* Snapshot metadata logging in database
* Backup email notifications
* Multi-tenant snapshot isolation

---

## License

MIT License

---

## Author

Mihir Mehta
Laravel Developer

---

This project demonstrates full backup lifecycle management in Laravel using a professional architecture suitable for production-level applications.

