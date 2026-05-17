# Grava

## Waitlist Management System
Grava is a powerful, feature-rich waitlist management platform built with PHP and MySQL. It helps you manage product launch waitlists with analytics, referral tracking, fraud detection, and an intuitive admin dashboard.

## Features

- **User Waitlist Management**
  - Simple, user-friendly signup form with validation
  - Rate limiting (3 submissions per hour per IP)
  - Honeypot protection against spam
  - Email confirmation and notifications
- **Referral System**
  - Unique referral codes for each user
  - Track referred users and build referral networks
  - Referral-based user acquisition analytics
- **Admin Dashboard**
  - Comprehensive analytics with overview, trends, and leaderboard
  - Member management and segmentation
  - Fraud detection and suspicious activity flagging
  - Search and filter applications by status, name, email, or role
  - Pagination support for large datasets
  - Secure authentication with admin login
- **Analytics & Insights**
  - Page view tracking
  - User segmentation by role and interests
  - Trend analysis over time
  - Referral leaderboard to identify top referrers
  - Fraud detection scoring
- **Email Notifications**
  - Automated welcome emails via SMTP
  - Uses PHPMailer for reliable email delivery
  - Configurable email templates

## Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Email**: SMTP (Gmail/custom SMTP servers)
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Dependencies**: PHPMailer 7.0

## Installation

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer
- Web server (Apache, Nginx, etc.)

### Setup Steps

1. **Clone or extract the repository**

```bash
cd /path/to/grava
```

2. **Install dependencies**

```bash
composer install
```

3. **Configure the database**
   - Create a MySQL database named `grava_db`
   - Import the database schema (the app initializes the schema automatically using `includes/db.php`)
   - Update credentials in `includes/config.php`

4. **Configure email settings**
   Edit `includes/config.php`:

```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
```

5. **Set permissions**
   Ensure the web server has write permissions to the project directory.

6. **Access the application**
   - Frontend: `http://localhost/grava`
   - Admin: `http://localhost/grava/admin.php`

## Configuration

### `includes/config.php`

Key configuration options:

```php
// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'grava_db');

// Email
define('MAIL_FROM', 'noreply@grava.in');
define('ADMIN_EMAIL', 'admin@example.com');

// Admin Credentials
define('ADMIN_USER', 'Tanoli');
define('ADMIN_PASS', 'FahadKhan786');
```

> **Security Note**: Change the default admin credentials before deploying to production!

## Project Structure

```
├── index.php                 # Frontend waitlist signup form
├── admin.php                 # Admin dashboard
├── composer.json             # PHP dependencies
├── includes/
│   ├── config.php            # Configuration and constants
│   ├── db.php                # Database connection helper
│   ├── helpers.php           # Utility functions
│   └── mailer.php            # Email notification handler
├── api/
│   ├── admin_ajax.php        # Admin panel AJAX endpoints
│   └── frontend_ajax.php     # Frontend AJAX endpoints
├── assets/
│   ├── css/                  # Stylesheets
│   │   ├── admin.css
│   │   └── site.css
│   └── js/                   # JavaScript
│       ├── admin.js
│       └── site.js
├── views/
│   ├── admin/                # Admin panel templates
│   │   ├── layout.php
│   │   ├── login.php
│   │   └── sections/
│   │       ├── overview.php
│   │       ├── members.php
│   │       ├── trend.php
│   │       ├── fraud.php
│   │       ├── leaderboard.php
│   │       └── segmentation.php
│   └── frontend/
│       └── home.php          # Public signup form
└── vendor/                   # Composer dependencies
```

## Features in Detail

### Waitlist Form (Frontend)

- Collects user information such as name, phone, role, and interests
- Built-in spam protection via honeypot field
- Rate limiting to prevent abuse
- Success flow with referral code generation

### Admin Dashboard

Requires authentication with admin credentials.

**Sections:**

- **Overview**: Key metrics and statistics
- **Members**: Full member list with search and filtering
- **Trends**: Historical data and growth analysis
- **Fraud Detection**: Flag suspicious applications
- **Leaderboard**: Top referrers and performers
- **Segmentation**: User distribution by role and interests

### Database Schema

The system tracks:

- User applications with contact information
- User roles and function interests
- Device and IP information
- Referral relationships
- Application status (`pending`, `reviewed`, `shortlisted`, `rejected`, `hired`)
- Fraud scoring and flags
- Rate limiting data

## API Endpoints

- `api/frontend_ajax.php` — Frontend form submission and data handling
- `api/admin_ajax.php` — Admin dashboard API calls

## Security Considerations

1. **Admin Credentials**: Change default credentials in `includes/config.php`
2. **Database**: Use strong passwords and restrict database access
3. **SMTP**: Use app-specific passwords for Gmail, not your actual account password
4. **Rate Limiting**: Built-in rate limiter prevents form spam
5. **Input Validation**: All user inputs are sanitized and validated
6. **Session Management**: Secure session handling for admin panel

## Support & Troubleshooting

### Email not sending?
- Verify SMTP credentials in `includes/config.php`
- Check firewall rules for SMTP port 587
- Use an app-specific password for Gmail
- Review `mail_log` table for delivery errors

### Database connection issues?
- Verify MySQL is running
- Check database credentials in `includes/config.php`
- Ensure database `grava_db` exists or allow the application to create it
- Confirm MySQL user permissions

### Admin login failing?
- Verify `ADMIN_USER` and `ADMIN_PASS` in `includes/config.php`
- Clear browser cache and cookies
- Check PHP session configuration

## License

This project is proprietary software. All rights reserved.

## Contact & Support

For questions, bug reports, or support requests, please contact the developer: muhammadfahad6923@gmail.com team or file an issue in the project repository.

## Version & Updates

**Current Version:** 1.0
**Last Updated:** May 2026
**Built with:** ❤️ by the StockMaster Developer
