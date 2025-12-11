# Sembark URL Shortner

A Laravel 10 based URL shortener service with role-based access control.

## Features

- **Multi-company support**: Each company can have multiple users
- **Role-based access control**: SuperAdmin, Admin, Member, Sales, Manager
- **URL Shortening**: Generate short URLs (Sales and Manager only)
- **Analytics**: Track hits and view URL statistics
- **Date filtering**: Filter and download URLs by date intervals
- **Team management**: Admins can invite team members, SuperAdmins can invite clients

## Requirements

- PHP >= 8.1
- MySQL
- Composer
- Laravel 10

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd shortner_url
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shortner_url
DB_USERNAME=root
DB_PASSWORD=
```

6. Run migrations:
```bash
php artisan migrate
```

7. Seed the database (creates SuperAdmin):
```bash
php artisan db:seed
```

## Default SuperAdmin Credentials

- **Email**: superadmin@example.com
- **Password**: password

## User Roles & Permissions

### SuperAdmin
- Can invite new clients (companies)
- Can view filtered short URLs (by date interval only)
- Cannot create short URLs
- Cannot see all URLs at once (must use date filter)

### Admin
- Can invite Sales/Manager users to their company
- Cannot invite Admin or Member roles
- Can view short URLs NOT created in their own company
- Cannot create short URLs

### Member
- Can view short URLs NOT created by themselves
- Cannot create short URLs

### Sales / Manager
- Can create short URLs
- Can view all URLs from their company
- Can download URLs with date filtering

## URL Shortening

- Short URLs are publicly accessible at `/s/{shortCode}`
- Each redirect increments the hit counter
- URLs can be filtered by date intervals (Today, Last Week, Last Month, This Month)

## Testing

Run the test suite:
```bash
php artisan test
```

## Project Structure

- `app/Http/Controllers/` - Application controllers
- `app/Models/` - Eloquent models (User, Company, ShortUrl)
- `database/migrations/` - Database migrations
- `database/seeders/` - Database seeders
- `resources/views/` - Blade templates
- `routes/web.php` - Web routes

## Routes

- `/login` - Login page
- `/dashboard` - Dashboard (redirects based on role)
- `/urls` - URL management (Sales/Manager)
- `/admin/urls` - Admin URL view
- `/admin/team-members` - Team management
- `/admin/invite` - Invite team member
- `/member/urls` - Member URL view
- `/superadmin/clients` - Client management
- `/superadmin/urls` - SuperAdmin URL view (filtered)
- `/superadmin/invite-client` - Invite new client
- `/s/{shortCode}` - Public short URL redirect

## License

MIT
