<div align="center">

# Beauty Blush Salons

A salon booking platform where many salons run on one system.
Final-year project by Sahrish.

</div>

---

## About the project

Beauty Blush Salons is a web application for booking salon appointments online. Clients can look through different salons, choose services and a stylist, pick a time and pay. Salon owners get their own dashboard to run their salon, and an admin looks after the whole platform.

It is a multi-tenant system. All salons use the same code and the same database, but every record belongs to a salon through `salon_id`, so an owner can only see and change their own salon's data.

The project has three separate portals:

- **Client:** search salons, book, pay, review
- **Owner:** manage one salon (services, stylists, bookings, payments)
- **Admin:** manage the full platform

## Features

### Client side

- Search salons by service, category and location
- Booking in four steps: Services, Stylist, Date and Time, Payment
- View, cancel and reschedule appointments
- Waitlist when the slot you want is already taken
- Payment by Easypaisa, JazzCash, bank transfer, cash or online, with screenshot upload
- Reviews and ratings for salons and stylists
- Favorite salons
- Complaints, with status tracking
- Email and in-app notifications for bookings and payments
- Chatbot called "Bella" for quick help and FAQs

### Salon owner side

- Dashboard limited to the owner's own salon
- Manage services, categories, stylists and time slots
- Approve or reject appointments and payments
- Sales analytics with revenue charts
- Manage gallery, holidays and waitlists
- Reply to reviews and complaints
- Export reports and client/payment data

### Admin side

- Overview of salons, owners, clients, appointments and payments
- Approve or reject new salon registration requests
- Manage reviews, complaints and system notifications
- Site settings (general, payment, email, social)
- Audit logs and reports
- Manage FAQs and homepage hero sliders

### Login and security

- Separate login for Client, Owner and Admin
- Google and Facebook login
- Phone verification with OTP and email verification
- Account lock after too many failed login attempts

## Tech stack

- **Backend:** Laravel 13, PHP 8.5
- **Database:** MySQL
- **Frontend:** Blade templates, Bootstrap 5
- **Roles and permissions:** Spatie Laravel Permission
- **Email:** Brevo SMTP
- **PDF:** barryvdh/laravel-dompdf
- **Charts:** Chart.js
- **Maps:** Leaflet with OpenStreetMap

## How a booking works

1. The client picks one or more services.
2. The client picks a stylist.
3. The client picks a date and time. If the slot is full, they can join the waitlist.
4. The client pays and uploads the payment proof if needed.
5. The salon owner approves or rejects the booking.
6. The client gets an email and an in-app notification about the result.

## Folder structure

The main folders are inside `app/`:

```text
app/
  Helpers/
    NotificationHelper.php    in-app notifications
  Http/Controllers/
    Frontend/                 public pages and booking flow
    Client/                   client dashboard
    Owner/                    owner dashboard
    Admin/                    admin panel
  Mail/                       email templates (booking, payment, review, etc.)
```

## Installation

You need PHP, Composer, Node.js with npm, and MySQL installed.

```bash
# install packages
composer install
npm install

# create the env file and app key
cp .env.example .env
php artisan key:generate
```

Open `.env` and add your database and mail details (see the next section). Then run:

```bash
# create the tables
php artisan migrate

# optional: add demo data
php artisan db:seed

# build the assets
npm run dev

# start the project
php artisan serve
```

Now open `http://127.0.0.1:8000` in your browser.

## Environment setup

These are the main values to change in `.env`:

```env
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_login
MAIL_PASSWORD=your_brevo_smtp_key
MAIL_FROM_ADDRESS=your_sender_email
MAIL_FROM_NAME="Beauty Blush Salons"
```

For Google and Facebook login, you also need to add your own client ID and secret in `.env`.

## License

This is an academic project made for my final year. It is not licensed for commercial use.

## Author

Sahrish, final-year student.