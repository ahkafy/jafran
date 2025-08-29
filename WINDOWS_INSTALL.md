# Jafran MLM Platform - Windows Installation Guide

This guide provides step-by-step instructions for setting up the Jafran MLM platform on Windows using XAMPP.

## 🖥️ Windows Requirements

- Windows 10/11 or Windows Server 2019+
- XAMPP 8.2+ (includes PHP 8.2+, MySQL 8.0+, Apache)
- Composer (PHP dependency manager)
- Node.js 18+ and NPM
- Git for Windows

## 📥 Download and Install Prerequisites

### 1. Install XAMPP
1. Download XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Choose PHP 8.2 version or higher
3. Run the installer as Administrator
4. Install to `C:\xampp` (default location)
5. Select Apache, MySQL, and PHP components

### 2. Install Composer
1. Download from [https://getcomposer.org/download/](https://getcomposer.org/download/)
2. Run `Composer-Setup.exe` as Administrator
3. Follow the installation wizard
4. Verify installation: Open Command Prompt and run `composer --version`

### 3. Install Node.js
1. Download from [https://nodejs.org/](https://nodejs.org/)
2. Choose LTS version (18+)
3. Run the installer
4. Verify installation: Open Command Prompt and run `node --version` and `npm --version`

### 4. Install Git for Windows
1. Download from [https://git-scm.com/download/win](https://git-scm.com/download/win)
2. Run the installer
3. Choose default options (or customize as needed)
4. Verify installation: Open Command Prompt and run `git --version`

## 🚀 Installation Steps

### 1. Start XAMPP Services
1. Open XAMPP Control Panel as Administrator
2. Start **Apache** service
3. Start **MySQL** service
4. Verify services are running (green indicators)

### 2. Clone the Repository
```cmd
cd C:\xampp\htdocs
git clone https://github.com/ahkafy/jafran.git
cd jafran
```

### 3. Install PHP Dependencies
```cmd
composer install
```

### 4. Install Node.js Dependencies
```cmd
npm install
```

### 5. Environment Setup
```cmd
copy .env.example .env
php artisan key:generate
```

### 6. Configure Database

#### Option A: Using phpMyAdmin (Recommended for beginners)
1. Open browser and go to `http://localhost/phpmyadmin`
2. Click "New" to create a new database
3. Database name: `jafran_mlm`
4. Collation: `utf8mb4_unicode_ci`
5. Click "Create"

#### Option B: Using MySQL Command Line
```cmd
"C:\xampp\mysql\bin\mysql.exe" -u root -p
```
```sql
CREATE DATABASE jafran_mlm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 7. Configure Environment File
Edit `.env` file with your database settings:
```env
APP_NAME="Jafran MLM"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost/jafran/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jafran_mlm
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

GEOLOCATION_ENABLED=false
ALLOWED_COUNTRIES=US,CA,UK,AU
```

### 8. Run Database Migrations
```cmd
php artisan migrate
```

### 9. Seed Initial Data
```cmd
php artisan db:seed --class=InvestmentPackageSeeder
```

### 10. Build Frontend Assets
```cmd
npm run dev
```

### 11. Test the Installation
1. Open browser and go to `http://localhost/jafran/public`
2. You should see the Jafran MLM platform homepage

## ⚙️ Windows Task Scheduler Setup

Since CRON jobs don't exist on Windows, use Task Scheduler for automated tasks.

### 1. Open Task Scheduler
- Press `Win + R`, type `taskschd.msc`, press Enter
- Or search "Task Scheduler" in Start menu

### 2. Create Laravel Scheduler Task
1. Right-click "Task Scheduler Library" → "Create Basic Task"
2. Name: "Jafran Laravel Scheduler"
3. Trigger: Daily
4. Start time: Set to current time
5. Action: Start a program
6. Program: `C:\xampp\php\php.exe`
7. Arguments: `artisan schedule:run`
8. Start in: `C:\xampp\htdocs\jafran`
9. Finish and set properties:
   - Check "Run with highest privileges"
   - Configure for Windows 10
   - Settings tab: Check "Run task as soon as possible after a scheduled start is missed"

### 3. Set Up Specific Tasks (Alternative to Scheduler)

#### Withdrawal Processing Task (1st of month)
1. Create Basic Task: "Jafran Withdrawals 1st"
2. Trigger: Monthly → Day 1 → Time 9:00 AM
3. Program: `C:\xampp\php\php.exe`
4. Arguments: `artisan withdrawals:process`
5. Start in: `C:\xampp\htdocs\jafran`

#### Withdrawal Processing Task (16th of month)
1. Create Basic Task: "Jafran Withdrawals 16th"
2. Trigger: Monthly → Day 16 → Time 9:00 AM
3. Program: `C:\xampp\php\php.exe`
4. Arguments: `artisan withdrawals:process`
5. Start in: `C:\xampp\htdocs\jafran`

#### Daily Commission Generation
1. Create Basic Task: "Jafran Commissions"
2. Trigger: Daily → Time 2:00 AM
3. Program: `C:\xampp\php\php.exe`
4. Arguments: `artisan commissions:generate`
5. Start in: `C:\xampp\htdocs\jafran`

#### Daily Returns Processing
1. Create Basic Task: "Jafran Returns"
2. Trigger: Daily → Time 3:00 AM
3. Program: `C:\xampp\php\php.exe`
4. Arguments: `artisan returns:process-daily`
5. Start in: `C:\xampp\htdocs\jafran`

### 4. PowerShell Script Method (Advanced)
Create a PowerShell script for automated task management:

```powershell
# Create file: C:\xampp\htdocs\jafran\scheduler.ps1
Set-Location "C:\xampp\htdocs\jafran"

# Run Laravel scheduler
& "C:\xampp\php\php.exe" artisan schedule:run

# Log output
$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
Add-Content -Path "storage\logs\scheduler.log" -Value "[$timestamp] Scheduler executed"
```

Then create a task to run this PowerShell script every minute:
- Program: `powershell.exe`
- Arguments: `-ExecutionPolicy Bypass -File "C:\xampp\htdocs\jafran\scheduler.ps1"`

## 🔧 Development Tools and Commands

### Useful Artisan Commands
```cmd
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Test wallet isolation
php artisan wallet:test-isolation 1

# Update user ranks
php artisan users:update-ranks

# Process withdrawals manually
php artisan withdrawals:process

# Generate commissions manually
php artisan commissions:generate

# Start development server
php artisan serve
```

### Asset Building Commands
```cmd
# Development build with file watching
npm run dev

# Production build (optimized)
npm run build

# Watch for changes during development
npm run dev -- --watch
```

### Database Commands
```cmd
# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (caution: deletes all data)
php artisan migrate:fresh --seed

# Seed specific seeder
php artisan db:seed --class=InvestmentPackageSeeder
```

## 🛠️ Troubleshooting Common Windows Issues

### 1. PHP Path Issues
If `php` command is not recognized:
```cmd
# Add PHP to system PATH
# Go to System Properties → Advanced → Environment Variables
# Edit PATH variable and add: C:\xampp\php
```

### 2. Composer Memory Issues
If Composer runs out of memory:
```cmd
php -d memory_limit=-1 composer.phar install
```

### 3. Permission Issues
If you get permission errors:
- Run Command Prompt as Administrator
- Right-click on `C:\xampp\htdocs\jafran` → Properties → Security
- Give "Full Control" to your user account

### 4. MySQL Connection Issues
If database connection fails:
- Check MySQL service is running in XAMPP Control Panel
- Verify database name and credentials in `.env`
- Try connecting via phpMyAdmin first

### 5. Node.js/NPM Issues
If npm install fails:
```cmd
# Clear npm cache
npm cache clean --force

# Delete node_modules and reinstall
rmdir /s node_modules
npm install
```

### 6. Laravel Scheduler Not Running
To test if scheduler is working:
```cmd
php artisan schedule:list
php artisan schedule:work
```

### 7. File Watcher Issues (npm run dev)
If file watching doesn't work:
```cmd
# Use polling method
npm run dev -- --watch-poll
```

## 📊 Monitoring on Windows

### 1. Check Task Scheduler History
1. Open Task Scheduler
2. Click on your task
3. Go to "History" tab to see execution logs

### 2. Application Logs
Monitor Laravel logs:
```cmd
# View latest log entries
type storage\logs\laravel.log | more

# Monitor logs in real-time (using PowerShell)
Get-Content storage\logs\laravel.log -Wait -Tail 10
```

### 3. Performance Monitoring
Use Windows Performance Monitor:
- Press `Win + R`, type `perfmon`, press Enter
- Monitor CPU, Memory, and Disk usage

## 🔄 Backup on Windows

### 1. Database Backup Script
Create `backup-db.bat`:
```batch
@echo off
set BACKUP_DIR=C:\xampp\htdocs\jafran\backups
set DATE=%date:~-4,4%%date:~-10,2%%date:~-7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
set DATE=%DATE: =0%

if not exist "%BACKUP_DIR%" mkdir "%BACKUP_DIR%"

"C:\xampp\mysql\bin\mysqldump.exe" -u root jafran_mlm > "%BACKUP_DIR%\db_%DATE%.sql"

echo Database backup completed: %BACKUP_DIR%\db_%DATE%.sql
```

### 2. Automated Backup Task
1. Create Basic Task: "Jafran DB Backup"
2. Trigger: Daily → Time 1:00 AM
3. Program: `C:\xampp\htdocs\jafran\backup-db.bat`

## 🚀 Going Live from Windows

When ready to deploy to production:
1. Export database: Use phpMyAdmin → Export
2. Upload files via FTP/SFTP
3. Follow the main deployment guide for Linux server setup
4. Or use shared hosting with cPanel

## 📞 Support

For Windows-specific issues:
- Check XAMPP documentation
- Verify PHP version compatibility
- Ensure all services are running
- Check Windows firewall settings
- Run Command Prompt as Administrator

---

This guide should get you up and running with the Jafran MLM platform on Windows using XAMPP. For production deployment, consider using a Linux server for better performance and security.
