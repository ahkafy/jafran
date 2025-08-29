# Jafran MLM Platform - Deployment Guide

This guide provides detailed instructions for deploying the Jafran MLM platform to production servers.

## 🌐 Server Requirements

### Minimum System Requirements
- **Operating System**: Ubuntu 20.04+ / CentOS 8+ / Windows Server 2019+
- **PHP**: 8.2+ with required extensions
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: 2GB RAM minimum (4GB recommended)
- **Storage**: 20GB minimum (SSD recommended)
- **CPU**: 2 cores minimum

### Required PHP Extensions
```bash
php-cli php-fpm php-mysql php-mbstring php-xml php-curl 
php-zip php-gd php-intl php-bcmath php-soap php-json php-openssl
```

## 🚀 Production Deployment

### 1. Server Setup (Ubuntu/Debian)

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo add-apt-repository ppa:ondrej/php
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-intl php8.2-bcmath

# Install MySQL
sudo apt install mysql-server-8.0

# Install Nginx
sudo apt install nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js and NPM
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs
```

### 2. Application Deployment

```bash
# Clone repository to web directory
cd /var/www
sudo git clone https://github.com/ahkafy/jafran.git
cd jafran

# Set proper ownership
sudo chown -R www-data:www-data /var/www/jafran
sudo chmod -R 755 /var/www/jafran

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Set permissions
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure .env file
nano .env
```

#### Production .env Configuration
```env
APP_NAME="Jafran MLM"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jafran_production
DB_USERNAME=jafran_user
DB_PASSWORD=secure_password

BROADCAST_CONNECTION=log
CACHE_STORE=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls

GEOLOCATION_ENABLED=true
ALLOWED_COUNTRIES=US,CA,UK,AU,DE,FR
```

### 4. Database Setup

```bash
# Create database and user
sudo mysql -u root -p

CREATE DATABASE jafran_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'jafran_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON jafran_production.* TO 'jafran_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force

# Seed initial data
php artisan db:seed --class=InvestmentPackageSeeder
```

### 5. Web Server Configuration

#### Nginx Configuration
```nginx
# /etc/nginx/sites-available/jafran
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/jafran/public;

    # SSL Configuration
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Handle PHP files
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to hidden files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/jafran /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

#### Apache Configuration
```apache
# /etc/apache2/sites-available/jafran.conf
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    Redirect permanent / https://yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/jafran/public

    SSLEngine on
    SSLCertificateFile /path/to/ssl/certificate.crt
    SSLCertificateKeyFile /path/to/ssl/private.key

    <Directory /var/www/jafran/public>
        AllowOverride All
        Require all granted
    </Directory>

    # Security headers
    Header always set X-Frame-Options SAMEORIGIN
    Header always set X-Content-Type-Options nosniff
    Header always set X-XSS-Protection "1; mode=block"
</VirtualHost>
```

## ⏰ CRON Jobs Setup

### Method 1: Laravel Scheduler (Recommended)

```bash
# Edit crontab for www-data user
sudo crontab -u www-data -e

# Add Laravel scheduler
* * * * * cd /var/www/jafran && php artisan schedule:run >> /dev/null 2>&1
```

### Method 2: Individual CRON Jobs

```bash
# Add to crontab
sudo crontab -u www-data -e

# Withdrawal processing (1st and 16th at 9:00 AM)
0 9 1 * * cd /var/www/jafran && php artisan withdrawals:process >> /var/log/jafran-withdrawals.log 2>&1
0 9 16 * * cd /var/www/jafran && php artisan withdrawals:process >> /var/log/jafran-withdrawals.log 2>&1

# Daily processes
0 2 * * * cd /var/www/jafran && php artisan commissions:generate >> /var/log/jafran-commissions.log 2>&1
0 3 * * * cd /var/www/jafran && php artisan returns:process-daily >> /var/log/jafran-returns.log 2>&1

# Weekly rank updates
0 4 * * 0 cd /var/www/jafran && php artisan users:update-ranks >> /var/log/jafran-ranks.log 2>&1
```

### Method 3: Systemd Timers (Alternative)

```bash
# Create timer service
sudo nano /etc/systemd/system/jafran-scheduler.timer

[Unit]
Description=Jafran Laravel Scheduler
Requires=jafran-scheduler.service

[Timer]
OnCalendar=*:*:00
Persistent=true

[Install]
WantedBy=timers.target

# Create service
sudo nano /etc/systemd/system/jafran-scheduler.service

[Unit]
Description=Jafran Laravel Scheduler
After=mysql.service

[Service]
Type=oneshot
User=www-data
WorkingDirectory=/var/www/jafran
ExecStart=/usr/bin/php artisan schedule:run

# Enable and start
sudo systemctl enable jafran-scheduler.timer
sudo systemctl start jafran-scheduler.timer
```

## 🔒 Security Hardening

### 1. SSL Certificate (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Generate certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
0 12 * * * /usr/bin/certbot renew --quiet
```

### 2. Firewall Configuration
```bash
# UFW setup
sudo ufw allow ssh
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable

# Fail2ban for brute force protection
sudo apt install fail2ban
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
```

### 3. Database Security
```bash
# Run MySQL security script
sudo mysql_secure_installation

# Create backup user
CREATE USER 'backup'@'localhost' IDENTIFIED BY 'backup_password';
GRANT SELECT, LOCK TABLES ON jafran_production.* TO 'backup'@'localhost';
```

## 📊 Monitoring & Logging

### 1. Application Monitoring
```bash
# Setup log rotation
sudo nano /etc/logrotate.d/jafran

/var/www/jafran/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 644 www-data www-data
}

# Monitor disk space
df -h
du -sh /var/www/jafran/storage/logs/
```

### 2. Performance Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs

# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Monitor MySQL
sudo mysqladmin -u root -p processlist
```

### 3. Application Health Checks
```bash
# Create health check script
nano /var/www/jafran/health-check.sh

#!/bin/bash
cd /var/www/jafran

# Check if application is responding
curl -f http://localhost/up > /dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "Application health check failed!"
    exit 1
fi

# Check database connection
php artisan migrate:status > /dev/null 2>&1
if [ $? -ne 0 ]; then
    echo "Database connection failed!"
    exit 1
fi

echo "Health check passed"

# Make executable and add to cron
chmod +x /var/www/jafran/health-check.sh

# Add to crontab (every 5 minutes)
*/5 * * * * /var/www/jafran/health-check.sh >> /var/log/jafran-health.log 2>&1
```

## 🔄 Backup Strategy

### 1. Database Backup
```bash
# Create backup script
nano /var/www/jafran/backup-db.sh

#!/bin/bash
BACKUP_DIR="/var/backups/jafran"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="jafran_production"

mkdir -p $BACKUP_DIR

# Create database backup
mysqldump -u backup -pbackup_password $DB_NAME > $BACKUP_DIR/db_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/db_$DATE.sql

# Remove backups older than 7 days
find $BACKUP_DIR -name "db_*.sql.gz" -mtime +7 -delete

# Make executable
chmod +x /var/www/jafran/backup-db.sh

# Add to crontab (daily at midnight)
0 0 * * * /var/www/jafran/backup-db.sh
```

### 2. File Backup
```bash
# Create file backup script
nano /var/www/jafran/backup-files.sh

#!/bin/bash
BACKUP_DIR="/var/backups/jafran"
DATE=$(date +%Y%m%d_%H%M%S)
SOURCE="/var/www/jafran"

mkdir -p $BACKUP_DIR

# Backup application files (excluding vendor and node_modules)
tar -czf $BACKUP_DIR/files_$DATE.tar.gz \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='storage/logs' \
    --exclude='storage/framework/cache' \
    --exclude='storage/framework/sessions' \
    --exclude='storage/framework/views' \
    $SOURCE

# Remove backups older than 7 days
find $BACKUP_DIR -name "files_*.tar.gz" -mtime +7 -delete

# Make executable
chmod +x /var/www/jafran/backup-files.sh

# Add to crontab (weekly on Sunday)
0 1 * * 0 /var/www/jafran/backup-files.sh
```

## 🚀 Deployment Automation

### 1. Deployment Script
```bash
# Create deployment script
nano /var/www/jafran/deploy.sh

#!/bin/bash
cd /var/www/jafran

# Enable maintenance mode
php artisan down

# Pull latest changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev

# Build assets
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Disable maintenance mode
php artisan up

echo "Deployment completed successfully!"

# Make executable
chmod +x /var/www/jafran/deploy.sh
```

### 2. Zero-Downtime Deployment (Advanced)
```bash
# Create zero-downtime deployment script
nano /var/www/jafran/zero-downtime-deploy.sh

#!/bin/bash
REPO_DIR="/var/www/jafran"
RELEASES_DIR="/var/www/releases"
SHARED_DIR="/var/www/shared"
CURRENT_LINK="/var/www/current"
TIMESTAMP=$(date +%Y%m%d%H%M%S)
RELEASE_DIR="$RELEASES_DIR/$TIMESTAMP"

# Create directories
mkdir -p $RELEASES_DIR $SHARED_DIR/storage $SHARED_DIR/bootstrap/cache

# Clone repository
git clone https://github.com/ahkafy/jafran.git $RELEASE_DIR
cd $RELEASE_DIR

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Link shared directories
rm -rf $RELEASE_DIR/storage $RELEASE_DIR/bootstrap/cache
ln -s $SHARED_DIR/storage $RELEASE_DIR/storage
ln -s $SHARED_DIR/bootstrap/cache $RELEASE_DIR/bootstrap/cache

# Copy .env file
cp $SHARED_DIR/.env $RELEASE_DIR/.env

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Update symlink
ln -sfn $RELEASE_DIR $CURRENT_LINK

# Reload web server
sudo systemctl reload nginx

# Clean up old releases (keep last 3)
cd $RELEASES_DIR && ls -t | tail -n +4 | xargs rm -rf

echo "Zero-downtime deployment completed!"
```

## 📋 Post-Deployment Checklist

- [ ] Application loads without errors
- [ ] Database connection working
- [ ] CRON jobs configured and running
- [ ] SSL certificate installed and working
- [ ] Firewall configured
- [ ] Backup scripts tested
- [ ] Monitoring setup complete
- [ ] Log rotation configured
- [ ] Health checks working
- [ ] Email notifications working
- [ ] All features tested in production

## 🆘 Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data /var/www/jafran
   sudo chmod -R 775 storage bootstrap/cache
   ```

2. **Database Connection Issues**
   ```bash
   php artisan migrate:status
   mysql -u jafran_user -p jafran_production
   ```

3. **CRON Jobs Not Running**
   ```bash
   sudo crontab -u www-data -l
   tail -f /var/log/cron.log
   ```

4. **Cache Issues**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

5. **SSL Certificate Issues**
   ```bash
   sudo certbot certificates
   sudo certbot renew --dry-run
   ```

---

This deployment guide ensures a secure, scalable, and maintainable production environment for the Jafran MLM platform.
