Beauty Blush Salons

Beauty Blush Salons is a multi-tenant salon booking platform built as a final-year project. It works as a marketplace where clients can browse salons and book appointments, while salon owners manage their own business through an isolated dashboard — all from a single shared codebase.

Tech Stack
Backend: Laravel 13, PHP 8.5
Database: MySQL
Frontend: Bootstrap 5, Blade Templating
Mail: Brevo SMTP
PDF Generation: barryvdh/laravel-dompdf
Roles & Permissions: Spatie Laravel Permission
Charts: Chart.js
Maps: Leaflet / OpenStreetMap
Key Features
For Clients
Browse and search salons by service, category, and location
Four-step booking flow: Services → Stylist → Date & Time → Payment
Manage appointments (view, cancel, reschedule)
Join a waitlist when preferred slots are unavailable
Submit payments (Easypaisa, JazzCash, bank transfer, cash, online) with screenshot upload
Leave reviews and ratings for salons and stylists
Save favorite salons
Raise and track complaints
Real-time email and in-app notifications for booking and payment updates
AI chatbot assistant ("Bella") for quick help and FAQs
For Salon Owners
Dedicated dashboard scoped to their own salon (multi-tenant, isolated by salon_id)
Manage services, categories, stylists, and time slots
Approve, reject, and track appointments and payments
Sales analytics dashboard with revenue charts
Manage salon gallery, holidays, and client waitlists
Respond to reviews and complaints
Export reports and client/payment data
For Admins
Platform-wide oversight: salons, owners, clients, appointments, and payments
Approve or reject new salon registration requests
Manage reviews, complaints, and system-wide notifications
Site settings: general, payment, email, and social configuration
Audit logs and reporting tools
Manage FAQs and homepage hero sliders
Authentication & Security
Role-based login (Client / Owner / Admin) with separate portals
Google and Facebook social login
OTP-based phone verification and email verification
Account lockout after repeated failed login attempts
Project Structure Highlights
app/Http/Controllers/Frontend — public-facing pages and the booking flow
app/Http/Controllers/Client — client dashboard and account features
app/Http/Controllers/Owner — salon owner dashboard and management tools
app/Http/Controllers/Admin — platform administration
app/Helpers/NotificationHelper.php — centralized in-app notification dispatch
app/Mail — transactional email templates (booking, payment, review, etc.)
Getting Started
bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure your database and mail (Brevo SMTP) credentials in .env

# Run migrations
php artisan migrate

# (Optional) Seed demo data
php artisan db:seed

# Build frontend assets
npm run dev

# Serve the application
php artisan serve
License

This project was developed as a final-year academic project and is not licensed for commercial use.