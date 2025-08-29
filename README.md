# Jafran MLM Platform

A comprehensive Multi-Level Marketing (MLM) platform built with Laravel 11, featuring genealogy tracking, investment packages, commission systems, and wallet management.

## 🚀 Features

- **Multi-Level Marketing System**: 5-level deep genealogy tree with rank-based progression
- **Investment Packages**: Multiple investment tiers with daily returns
- **Commission System**: Automatic commission calculation and distribution
- **Wallet Management**: Isolated wallet system with withdrawal processing
- **Withdrawal Methods**: Support for Global Banks and MBook Wallet
- **Bi-Monthly Processing**: Automated withdrawal processing on 1st and 16th of each month
- **Geolocation Restrictions**: Country-based access control
- **Ranking System**: Dynamic user ranking based on network performance

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js 18+ and NPM
- XAMPP/WAMP (for local development)

## 🛠️ Installation

### 1. Clone Repository
```bash
git clone https://github.com/ahkafy/jafran.git
cd jafran
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jafran_mlm
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Migration
```bash
# Run migrations
php artisan migrate

# Seed initial data (investment packages)
php artisan db:seed --class=InvestmentPackageSeeder
```

### 6. Build Assets
```bash
# Compile assets for development
npm run dev

# Or for production
npm run build
```

### 7. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## ⚙️ CRON Setup (Production)

The platform requires scheduled tasks for automated processing. Set up the following CRON jobs on your server:

### Laravel Scheduler (Required)
Add this single CRON entry to run Laravel's task scheduler:

```bash
# Edit crontab
crontab -e

# Add this line (runs every minute)
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### Individual CRON Jobs (Alternative)
If you prefer individual CRON jobs instead of the scheduler:

```bash
# Withdrawal processing (1st of month at 9:00 AM)
0 9 1 * * cd /path/to/your/project && php artisan withdrawals:process

# Withdrawal processing (16th of month at 9:00 AM)  
0 9 16 * * cd /path/to/your/project && php artisan withdrawals:process

# Daily commission generation (2:00 AM daily)
0 2 * * * cd /path/to/your/project && php artisan commissions:generate

# Daily returns processing (3:00 AM daily)
0 3 * * * cd /path/to/your/project && php artisan returns:process-daily
```

### Windows Task Scheduler Setup
For Windows servers, use Task Scheduler:

1. Open Task Scheduler
2. Create Basic Task
3. Set trigger frequency
4. Set action to start program: `php`
5. Add arguments: `artisan schedule:run`
6. Set start directory: `C:\path\to\your\project`

## 🎯 Key Commands

### User Management
```bash
# Update user ranks manually
php artisan users:update-ranks

# Update specific user rank
php artisan users:update-ranks --user=123
```

### Wallet Management
```bash
# Test wallet isolation
php artisan wallet:test-isolation 1

# Process scheduled withdrawals manually
php artisan withdrawals:process
```

### Investment & Commission
```bash
# Generate commissions manually
php artisan commissions:generate

# Process daily returns manually
php artisan returns:process-daily
```

### Cache Management
```bash
# Clear all caches
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

## 💰 Wallet System

### Balance Types
- **Investment Balance**: Funds for investments only (non-withdrawable)
- **Commission Balance**: Network earnings (withdrawable)
- **Returns Balance**: Investment returns (withdrawable)
- **Withdrawable Balance**: Total available for withdrawal

### Withdrawal Methods
1. **US Banks**: 2% processing fee
2. **Global Banks**: 10% processing fee
3. **MBook Wallet**: 5% processing fee

### Processing Schedule
- **1st of month**: For requests submitted before 1st
- **16th of month**: For requests submitted before 16th
- **Minimum withdrawal**: $2.00 USD
- **Processing time**: 5-7 working days

## 🏆 Ranking System

### Rank Tiers
1. **Bronze** (Default)
2. **Silver** (10+ referrals, $1,000+ team volume)
3. **Gold** (25+ referrals, $5,000+ team volume)
4. **Platinum** (50+ referrals, $15,000+ team volume)
5. **Diamond** (100+ referrals, $50,000+ team volume)

## 🌍 Geolocation Features

- Country-based access restriction
- Automatic geolocation detection
- Customizable country whitelist/blacklist

## 🔧 Configuration

### Investment Packages
Configure investment packages in the database via seeder or admin panel:
- Package amounts and durations
- Daily return percentages
- Commission structures

### Geolocation Settings
Edit `.env` for geolocation configuration:
```env
GEOLOCATION_ENABLED=true
ALLOWED_COUNTRIES=US,CA,UK,AU
```

## 📊 Database Structure

### Key Tables
- `users` - User accounts and MLM structure
- `investment_packages` - Investment tier definitions
- `investments` - User investments
- `commissions` - Commission tracking
- `daily_returns` - Daily return records
- `withdrawal_requests` - Withdrawal processing

## 🔒 Security Features

- CSRF protection
- SQL injection prevention
- XSS protection
- Secure password hashing
- Input validation and sanitization

## 🚀 Deployment

### Production Checklist
1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false` in `.env`
3. Configure proper database credentials
4. Set up SSL certificate
5. Configure web server (Apache/Nginx)
6. Set up CRON jobs for automation
7. Configure email settings for notifications
8. Set up backup system
9. Configure monitoring and logging

### Server Requirements
- PHP 8.2+ with required extensions
- MySQL 8.0+
- Composer installed globally
- CRON daemon running
- SSL certificate configured
- Sufficient disk space for logs and uploads

## 📝 Support & Documentation

### Additional Documentation
- `RANKING_SYSTEM_SUMMARY.md` - Detailed ranking system information
- `/routes/web.php` - Application routes
- `/app/Models/` - Database models and relationships

### Troubleshooting
```bash
# Check application status
php artisan about

# View logs
tail -f storage/logs/laravel.log

# Test database connection
php artisan migrate:status

# Verify scheduled tasks
php artisan schedule:list
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests (if available)
5. Submit a pull request

## 📄 License

This project is proprietary software. All rights reserved.

## 📞 Contact

For support or questions, please contact the development team.

---

**Note**: This platform is designed for legitimate MLM business operations. Ensure compliance with local regulations and laws regarding multi-level marketing activities.
