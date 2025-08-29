# Jafran MLM Platform - API Documentation

This document provides information about the available APIs and commands for the Jafran MLM platform.

## 🛠️ Artisan Commands

### User Management Commands

#### Update User Ranks
```bash
# Update all user ranks based on current criteria
php artisan users:update-ranks

# Update specific user rank
php artisan users:update-ranks --user=123

# Force rank recalculation for all users
php artisan users:update-ranks --force
```

### Wallet Management Commands

#### Test Wallet Isolation
```bash
# Test wallet isolation for specific user
php artisan wallet:test-isolation {user_id}

# Example
php artisan wallet:test-isolation 1
```

#### Process Scheduled Withdrawals
```bash
# Process withdrawals for current period
php artisan withdrawals:process

# Process with verbose output
php artisan withdrawals:process --verbose

# Force process even if not scheduled
php artisan withdrawals:process --force
```

### Commission & Returns Commands

#### Generate Commissions
```bash
# Generate daily commissions
php artisan commissions:generate

# Generate for specific date
php artisan commissions:generate --date=2025-08-29

# Force regeneration (skip duplicates check)
php artisan commissions:generate --force
```

#### Process Daily Returns
```bash
# Process daily investment returns
php artisan returns:process-daily

# Process for specific date
php artisan returns:process-daily --date=2025-08-29

# Process with detailed output
php artisan returns:process-daily --verbose
```

## 🔄 Scheduled Tasks

The platform uses Laravel's built-in scheduler. All tasks are defined in `routes/console.php`:

### Automatic Scheduling
```php
// Withdrawal processing (bi-monthly)
Schedule::command('withdrawals:process')
    ->monthlyOn(1, '09:00')    // 1st of month at 9:00 AM
    ->monthlyOn(16, '09:00');  // 16th of month at 9:00 AM

// Daily commission generation
Schedule::command('commissions:generate')
    ->daily()
    ->at('02:00');  // 2:00 AM daily

// Daily returns processing
Schedule::command('returns:process-daily')
    ->daily()
    ->at('03:00');  // 3:00 AM daily
```

### Manual Scheduler Execution
```bash
# Run all scheduled tasks immediately
php artisan schedule:run

# List all scheduled tasks
php artisan schedule:list

# Work scheduler continuously (for testing)
php artisan schedule:work
```

## 🗄️ Database Schema

### Key Tables and Relationships

#### Users Table
```sql
users:
- id (Primary Key)
- name
- email
- sponsor_id (Foreign Key to users.id)
- rank (bronze, silver, gold, platinum, diamond)
- investment_balance (decimal)
- commission_balance (decimal)
- returns_balance (decimal)
- total_withdrawn (decimal)
- created_at
- updated_at
```

#### Investment Packages Table
```sql
investment_packages:
- id (Primary Key)
- name
- amount (decimal)
- duration_days (integer)
- daily_return_percentage (decimal)
- status (active/inactive)
- created_at
- updated_at
```

#### Investments Table
```sql
investments:
- id (Primary Key)
- user_id (Foreign Key)
- package_id (Foreign Key)
- amount (decimal)
- start_date
- end_date
- status (active/completed/cancelled)
- total_returned (decimal)
- created_at
- updated_at
```

#### Withdrawal Requests Table
```sql
withdrawal_requests:
- id (Primary Key)
- user_id (Foreign Key)
- amount (decimal)
- method (bank/mbook)
- processing_fee_percentage (decimal)
- processing_fee_amount (decimal)
- net_amount (decimal)
- bank_name
- account_holder_name
- account_number
- routing_number
- bank_country
- account_type
- mbook_name
- mbook_country
- mbook_currency
- mbook_wallet_id
- status (pending/processing/completed/cancelled)
- processed_at
- created_at
- updated_at
```

#### Commissions Table
```sql
commissions:
- id (Primary Key)
- user_id (Foreign Key)
- from_user_id (Foreign Key)
- investment_id (Foreign Key)
- amount (decimal)
- level (1-5)
- percentage (decimal)
- type (referral/level)
- date
- created_at
- updated_at
```

#### Daily Returns Table
```sql
daily_returns:
- id (Primary Key)
- user_id (Foreign Key)
- investment_id (Foreign Key)
- amount (decimal)
- percentage (decimal)
- date
- created_at
- updated_at
```

## 🔧 Configuration Files

### Environment Variables (.env)
```env
# Application
APP_NAME="Jafran MLM"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jafran_production
DB_USERNAME=jafran_user
DB_PASSWORD=secure_password

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

# Geolocation
GEOLOCATION_ENABLED=true
ALLOWED_COUNTRIES=US,CA,UK,AU
```

### Key Configuration Files
- `config/app.php` - Application settings
- `config/database.php` - Database configuration
- `config/mail.php` - Email settings
- `routes/web.php` - Web routes
- `routes/console.php` - Console commands and scheduling

## 🚀 API Endpoints (Web Routes)

### Authentication Routes
```php
// Login/Register
GET  /login
POST /login
GET  /register
POST /register
POST /logout
```

### Dashboard Routes
```php
// Main dashboard
GET  /dashboard

// Wallet management
GET  /wallet
POST /wallet/withdrawal
GET  /wallet/history
```

### Investment Routes
```php
// Investment packages
GET  /investments
POST /investments/create
GET  /investments/history
```

### MLM Routes
```php
// Genealogy tree
GET  /mlm/genealogy
GET  /mlm/genealogy/api/{userId}

// Commissions
GET  /mlm/commissions
GET  /mlm/commissions/api
```

## 📊 Database Queries and Models

### User Model Relationships
```php
// Get user's referrals (direct downline)
$user->referrals()

// Get user's sponsor (upline)
$user->sponsor()

// Get user's investments
$user->investments()

// Get user's commissions
$user->commissions()

// Get user's withdrawal requests
$user->withdrawalRequests()

// Calculate withdrawable balance
$user->withdrawable_balance  // Accessor
```

### Investment Model Methods
```php
// Get active investments
Investment::active()

// Get completed investments
Investment::completed()

// Calculate total returns for investment
$investment->totalReturns()

// Check if investment is active
$investment->isActive()
```

### Commission Calculations
```php
// Commission percentages by level
Level 1: 10%
Level 2: 5%
Level 3: 3%
Level 4: 2%
Level 5: 1%

// Rank requirements
Bronze: Default (0 referrals)
Silver: 10+ referrals, $1,000+ team volume
Gold: 25+ referrals, $5,000+ team volume
Platinum: 50+ referrals, $15,000+ team volume
Diamond: 100+ referrals, $50,000+ team volume
```

## 🔍 Monitoring and Debugging

### Log Files
```bash
# Application logs
storage/logs/laravel.log

# Web server logs (varies by server)
/var/log/nginx/access.log
/var/log/nginx/error.log
/var/log/apache2/access.log
/var/log/apache2/error.log
```

### Debug Commands
```bash
# Check application status
php artisan about

# View migration status
php artisan migrate:status

# Inspect routes
php artisan route:list

# Check scheduled tasks
php artisan schedule:list

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Performance Monitoring
```bash
# Check queue status (if using queues)
php artisan queue:work --verbose

# Monitor failed jobs
php artisan queue:failed

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🛡️ Security Considerations

### Data Protection
- All financial calculations use decimal precision
- Password hashing with Laravel's bcrypt
- CSRF protection on all forms
- SQL injection prevention with Eloquent ORM
- XSS protection with Blade templating

### Withdrawal Security
- Two-step verification for withdrawals
- Manual review process for large amounts
- Audit trail for all transactions
- Bank account verification requirements

### Commission Security
- Duplicate prevention mechanisms
- Level-based commission caps
- Audit logging for all commission calculations
- Automatic rank verification

## 📞 Support and Maintenance

### Regular Maintenance Tasks
```bash
# Weekly tasks
php artisan users:update-ranks
php artisan cache:clear

# Monthly tasks
# - Review withdrawal requests
# - Check commission calculations
# - Update investment packages
# - Review user rankings
```

### Health Checks
```bash
# Application health
curl http://localhost/up

# Database health
php artisan migrate:status

# Scheduler health
php artisan schedule:list
```

---

This API documentation provides comprehensive information for developers working with the Jafran MLM platform. For additional support, refer to the main README.md and deployment guides.
