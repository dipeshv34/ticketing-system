# Multi‑Tenant Ticket Management System

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red)](https://laravel.com)  
[![PHP](https://img.shields.io/badge/PHP-8.4-blue)](https://www.php.net)  
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue)](https://www.postgresql.org)  
[![Docker](https://img.shields.io/badge/Docker-%3E%3D20.10-blue)](https://www.docker.com)  
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

A backend service built with **Laravel 12**, **PHP 8.4**, **PostgreSQL** and **Docker** powering a multi‑tenant ticketing platform.  
Super Admins manage clients and tickets globally; Admins and Clients manage tickets per‑client; threaded replies, file attachments, email queueing and dashboard metrics are all included.

---

## 🚀 Features

- **Multi‑Client Management**  
  - Super Admin: create, update, deactivate clients  
  - View per-client ticket stats & filter by status  
- **Role‑Based Access**  
  - `super_admin`, `admin`, `client`  
  - Middleware-enforced permissions  
- **Ticket Workflow**  
  - Clients raise tickets with subject, message, attachments  
  - Admins/Super Admin reply & change status (`open`, `closed`, `on_hold`)  
- **Threaded Replies & Attachments**  
- **Email Notification Queue**  
  - Notifications to all participants (except sender)  
  - Stored in `notifications` table for retry/reporting  
- **Admin Dashboard**  
  - Global ticket metrics: total, by status, recent activity  
- **Automated Tests**  
  - PHPUnit feature tests cover all core flows  

---

## 🛠️ Tech Stack

- **Framework:** Laravel 12  
- **Language:** PHP 8.4  
- **Database:** PostgreSQL 15  
- **Containers:** Docker & Docker Compose  
- **Testing:** PHPUnit (built-in), Pest optional  
- **Queue:** Database driver (configurable)  

---

## 🔧 Quick Start

### Prerequisites

- Docker & Docker Compose  
- (Optional) Node.js & npm/yarn for frontend asset compilation  

### 1. Clone the Repo

```bash
git clone https://github.com/your‑org/laravel-ticket-system.git
cd ticketing-system
```

### 2. Copy ENV
``` bash
cp src/.env.example src/.env
```

### 2. UPdate ENV

APP_NAME=TicketSystem
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

QUEUE_CONNECTION=database

### compose docker
docker-compose up -d --build


###install

# Enter the app container
docker-compose exec app bash

composer install
php artisan key:generate
php artisan migrate --seed
exit


Web UI: http://localhost:8000

API Base: http://localhost:8000/api

###Run the full test suite:
``` bash
docker-compose exec app php artisan test



```

Future Improvements
Real‑time updates via WebSockets (Laravel Echo)

Ticket priority levels & SLAs

Advanced search & filtering

Customizable email templates

Audit logging & soft deletes

Admin UI for role & permission management



