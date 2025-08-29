# Jafran MLM Platform - Documentation Index

Welcome to the Jafran MLM Platform documentation. This index provides links to all available documentation files to help you get started, deploy, and maintain the platform.

## 📚 Documentation Overview

### 🚀 Getting Started
- **[README.md](README.md)** - Main documentation with features, installation, and CRON setup
- **[WINDOWS_INSTALL.md](WINDOWS_INSTALL.md)** - Complete Windows/XAMPP installation guide
- **[API_DOCS.md](API_DOCS.md)** - API endpoints, commands, and database schema

### 🌐 Deployment & Production
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Complete production deployment guide for Linux servers
- **CRON Setup Instructions** - Found in both README.md and DEPLOYMENT.md

### 🏗️ System Architecture
- **[RANKING_SYSTEM_SUMMARY.md](RANKING_SYSTEM_SUMMARY.md)** - MLM ranking system implementation
- **[GENEALOGY_RANKS_IMPLEMENTATION.md](GENEALOGY_RANKS_IMPLEMENTATION.md)** - Genealogy tree and rank calculations
- **[MLM_SEEDER_SUMMARY.md](MLM_SEEDER_SUMMARY.md)** - Database seeding and initial data setup

### 📋 Project Updates
- **[PROJECT_UPDATES.md](PROJECT_UPDATES.md)** - Historical project changes and updates

## 🎯 Quick Navigation

### For Developers
1. Start with **[README.md](README.md)** for platform overview
2. Use **[WINDOWS_INSTALL.md](WINDOWS_INSTALL.md)** for local development setup
3. Reference **[API_DOCS.md](API_DOCS.md)** for commands and database structure
4. Study **[RANKING_SYSTEM_SUMMARY.md](RANKING_SYSTEM_SUMMARY.md)** for MLM logic

### For System Administrators
1. Follow **[DEPLOYMENT.md](DEPLOYMENT.md)** for production setup
2. Implement CRON jobs as described in deployment guide
3. Use **[API_DOCS.md](API_DOCS.md)** for maintenance commands
4. Monitor using health check scripts provided

### For Business Users
1. Review **[README.md](README.md)** for feature overview
2. Understand **[RANKING_SYSTEM_SUMMARY.md](RANKING_SYSTEM_SUMMARY.md)** for MLM structure
3. Check **[PROJECT_UPDATES.md](PROJECT_UPDATES.md)** for recent changes

## ⚙️ CRON Jobs Setup (Quick Reference)

### Production (Linux) - Single Command
```bash
# Add to crontab for automated task scheduling
* * * * * cd /var/www/jafran && php artisan schedule:run >> /dev/null 2>&1
```

### Development (Windows) - Task Scheduler
Create Windows Task Scheduler tasks for:
- Laravel Scheduler: Run every minute
- Or individual tasks for withdrawals, commissions, and returns

**Detailed instructions available in:**
- [README.md - CRON Setup section](README.md#%EF%B8%8F-cron-setup-production)
- [DEPLOYMENT.md - CRON Jobs Setup](DEPLOYMENT.md#-cron-jobs-setup)
- [WINDOWS_INSTALL.md - Task Scheduler Setup](WINDOWS_INSTALL.md#%EF%B8%8F-windows-task-scheduler-setup)

## 🛠️ Key Commands Reference

### Essential Artisan Commands
```bash
# Wallet management
php artisan wallet:test-isolation 1
php artisan withdrawals:process

# MLM operations
php artisan users:update-ranks
php artisan commissions:generate
php artisan returns:process-daily

# Maintenance
php artisan cache:clear
php artisan view:clear
php artisan migrate:status
```

### Scheduled Tasks
- **Withdrawals**: Process on 1st and 16th of each month at 9:00 AM
- **Commissions**: Generate daily at 2:00 AM
- **Returns**: Process daily at 3:00 AM
- **Ranks**: Update weekly or as needed

## 🔧 System Features

### MLM Features
- ✅ 5-level deep genealogy tree
- ✅ Automated commission calculations
- ✅ Rank-based progression system
- ✅ Investment packages with daily returns
- ✅ Bi-monthly withdrawal processing

### Wallet Features
- ✅ Isolated wallet system (Investment/Commission/Returns)
- ✅ Multiple withdrawal methods (Banks/MBook)
- ✅ Processing fees and scheduling
- ✅ Minimum withdrawal amounts

### Security Features
- ✅ Geolocation restrictions
- ✅ Account verification
- ✅ Secure payment processing
- ✅ Audit trails

## 📞 Support & Troubleshooting

### Common Issues
1. **Installation Problems** → See [WINDOWS_INSTALL.md](WINDOWS_INSTALL.md#-troubleshooting-common-windows-issues)
2. **Deployment Issues** → See [DEPLOYMENT.md](DEPLOYMENT.md#-troubleshooting)
3. **CRON Not Running** → Check CRON setup in deployment guide
4. **Database Errors** → Verify migration status with `php artisan migrate:status`

### Log Files
- Application: `storage/logs/laravel.log`
- Web server: `/var/log/nginx/` or `/var/log/apache2/`
- CRON: `/var/log/cron.log` (Linux) or Task Scheduler History (Windows)

### Health Checks
```bash
php artisan about              # Application status
php artisan schedule:list      # Scheduled tasks
curl http://localhost/up       # Health endpoint
```

## 📋 File Structure Summary

```
jafran/
├── README.md                           # Main documentation
├── DEPLOYMENT.md                       # Production deployment guide
├── WINDOWS_INSTALL.md                  # Windows installation guide
├── API_DOCS.md                         # API and commands reference
├── RANKING_SYSTEM_SUMMARY.md           # MLM ranking system
├── GENEALOGY_RANKS_IMPLEMENTATION.md   # Genealogy implementation
├── MLM_SEEDER_SUMMARY.md              # Database seeding guide
├── PROJECT_UPDATES.md                  # Historical updates
├── DOCS_INDEX.md                       # This file
├── app/                                # Laravel application code
├── database/migrations/                # Database migrations
├── resources/views/                    # Blade templates
├── routes/                            # Application routes
└── storage/logs/                      # Application logs
```

---

**Need help?** Start with the [README.md](README.md) for general information, then navigate to the specific guide you need based on your role and requirements.
