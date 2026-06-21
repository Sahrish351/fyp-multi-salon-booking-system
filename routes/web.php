<?php
// FILE: routes/web.php — COMPLETE FIXED VERSION
 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ConfirmPasswordController;
 
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PublicSalonController;
use App\Http\Controllers\Frontend\PublicServiceController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\BookingController;
 
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SalonRequestController;
use App\Http\Controllers\Admin\SalonManagementController;
use App\Http\Controllers\Admin\OwnerManagementController;
use App\Http\Controllers\Admin\ClientManagementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AppointmentMonitorController;
use App\Http\Controllers\Admin\PaymentMonitorController;
use App\Http\Controllers\Admin\ReviewManagementController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\SystemSettingController;
 
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerSalonController;
use App\Http\Controllers\Owner\OwnerServiceController;
use App\Http\Controllers\Owner\OwnerStylistController;
use App\Http\Controllers\Owner\OwnerTimeSlotController;
use App\Http\Controllers\Owner\OwnerHolidayController;
use App\Http\Controllers\Owner\OwnerAppointmentController;
use App\Http\Controllers\Owner\OwnerPaymentController;
use App\Http\Controllers\Owner\OwnerWaitlistController;
use App\Http\Controllers\Owner\OwnerGalleryController;
use App\Http\Controllers\Owner\OwnerReviewController;
use App\Http\Controllers\Owner\OwnerClientController;
use App\Http\Controllers\Owner\OwnerNotificationController;
use App\Http\Controllers\Owner\OwnerReportController;
use App\Http\Controllers\Owner\OwnerAnalyticsController;
use App\Http\Controllers\Owner\OwnerSettingController;
use App\Http\Controllers\Owner\OwnerPackageController;
use App\Http\Controllers\Owner\OwnerProfileController;
use App\Http\Controllers\Owner\OwnerSalonHolidayController;
 
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\SalonSearchController;
use App\Http\Controllers\Client\SalonDetailController;
use App\Http\Controllers\Client\BookingStepController;
use App\Http\Controllers\Client\PaymentSubmitController;
use App\Http\Controllers\Client\AppointmentManageController;
use App\Http\Controllers\Client\RescheduleController;
use App\Http\Controllers\Client\WaitlistJoinController;
use App\Http\Controllers\Client\FavoriteSalonController;
use App\Http\Controllers\Client\ReviewSubmitController;
use App\Http\Controllers\Client\ComplaintSubmitController;
use App\Http\Controllers\Client\ClientNotificationController;
use App\Http\Controllers\Client\ClientProfileController;
 
// ============================================================
// PUBLIC ROUTES
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');
 
// Salon public routes
Route::get('/salons', [PublicSalonController::class, 'index'])->name('salons.index');
Route::get('/salons/search', [PublicSalonController::class, 'search'])->name('salons.search');
Route::get('/salons/{slug}', [PublicSalonController::class, 'show'])->name('salons.show');
Route::get('/salons/{slug}/gallery', [PublicSalonController::class, 'gallery'])->name('salons.gallery');
 

/// ==================== SERVICES ROUTES ====================
Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/services/category/{slug}', [PublicServiceController::class, 'byCategory'])->name('services.by-category');
Route::get('/salons/{salonSlug}/services/{serviceId}', [PublicServiceController::class, 'show'])->name('services.show');


// Services public
Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/services/category/{slug}', [PublicServiceController::class, 'byCategory'])->name('services.by-category');
Route::get('/salons/{salonSlug}/services/{serviceId}', [PublicServiceController::class, 'show'])->name('services.show');
 

// Redirect stubs
Route::get('/stylists', fn() => redirect()->route('salons.index'))->name('stylists.index');
Route::get('/gallery',  fn() => redirect()->route('salons.index'))->name('gallery.index');
 
// ============================================================
// ✅ BOOKING ROUTES — ALL STEPS REQUIRE LOGIN
// ============================================================
// ============================================================
Route::prefix('booking')->name('booking.')->middleware('auth')->group(function () {

    // Step 1 — Select Service
    Route::get('/services/{salon_id}',  [BookingController::class, 'step1Services'])->name('step1');
    Route::post('/services/{salon_id}', [BookingController::class, 'postStep1Services'])->name('step1.post');

    // Step 2 — Select Stylist
    Route::get('/stylist/{salon_id}',  [BookingController::class, 'step2Stylist'])->name('step2');
    Route::post('/stylist/{salon_id}', [BookingController::class, 'postStep2Stylist'])->name('step2.post');

    // Step 3 — Select Date & Time (+ Waitlist join)
    Route::get('/datetime/{salon_id}',  [BookingController::class, 'step3DateTime'])->name('step3');
    Route::post('/datetime/{salon_id}', [BookingController::class, 'postStep3DateTime'])->name('step3.post');

    // Step 4 — Payment (creates pending appointment + redirects to PayFast)
    Route::get('/payment/{salon_id}',  [BookingController::class, 'step4Payment'])->name('step4');
    Route::post('/payment/{salon_id}', [BookingController::class, 'postPayment'])->name('payment.post');

    // Confirmation page
    Route::get('/confirmation/{booking_id}', [BookingController::class, 'confirmation'])->name('confirmation');

    // ✅ AJAX: Get available time slots for a given date
    Route::get('/slots/{salon_id}', [BookingController::class, 'getSlots'])->name('slots');
});

Route::prefix('payfast')->name('payfast.')->group(function () {
    Route::get('/return',  [BookingController::class, 'payfastReturn'])->name('return');
    Route::get('/cancel',  [BookingController::class, 'payfastCancel'])->name('cancel');
    Route::post('/notify', [BookingController::class, 'payfastNotify'])->name('notify');
});


 
// ============================================================
// AUTH ROUTES (Guest only)
// ============================================================
Route::middleware('guest')->group(function () {
 
    Route::view('/select-login',    'auth.login-selector')->name('select.login');
    Route::view('/select-register', 'auth.register-selector')->name('register.selector');
    Route::redirect('/login', '/select-login')->name('login');
 
    // Client login
    Route::get('/client/login',  [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('client.login.form');
    Route::post('/client/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('client.login.submit');
 
    // Owner login
    Route::get('/owner/login',  [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('owner.login.form');
    Route::post('/owner/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('owner.login.submit');
 
    // Admin login
    Route::get('/admin/login',  [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin.login.submit');
 
    // Registration
    Route::get('/register/client',   [RegisterController::class, 'showClientForm'])->name('register.client');
    Route::get('/register/owner',    [RegisterController::class, 'showOwnerForm'])->name('register.owner');
    Route::post('/register/client',  [RegisterController::class, 'registerClient'])->name('register.client.store');
    Route::post('/register/owner',   [RegisterController::class, 'registerOwner'])->name('register.owner.store');
 
    // Forgot / Reset password
    Route::get('/forgot-password',   fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password',  [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', fn($t) => view('auth.reset-password', ['token' => $t]))->name('password.reset');
    Route::post('/reset-password',   [ResetPasswordController::class, 'reset'])->name('password.update');
 
    // OTP
    Route::get('/otp/verify',   [OtpController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('/otp/verify',  [OtpController::class, 'verify'])->name('otp.verify.submit');
    Route::post('/otp/resend',  [OtpController::class, 'resend'])->name('otp.resend');
 
    // Google / Facebook Social Login
    Route::get('/auth/google',           [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback',  [App\Http\Controllers\Auth\SocialLoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/auth/facebook',         [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToFacebook'])->name('facebook.redirect');
    Route::get('/auth/facebook/callback',[App\Http\Controllers\Auth\SocialLoginController::class, 'handleFacebookCallback'])->name('facebook.callback');
});
 
// ============================================================
// AUTHENTICATED ROUTES
// ============================================================
Route::middleware('auth')->group(function () {
 
    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
 
    // Email Verification
    Route::get('/email/verify',         [VerificationController::class, 'showNotice'])->name('verification.notice');
    Route::get('/email/verify/send',    [VerificationController::class, 'sendVerificationEmail'])->name('verification.send');
    Route::post('/email/verify',        [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');
 
    // Phone Verification
    Route::get('/phone/verify',        [VerificationController::class, 'showPhoneForm'])->name('phone.verify');
    Route::post('/phone/verify/send',  [VerificationController::class, 'sendPhoneOtp'])->name('phone.verify.send');
    Route::post('/phone/verify',       [VerificationController::class, 'verifyPhone'])->name('phone.verify.submit');
 
    // Password Confirm
    Route::get('/confirm-password',  [ConfirmPasswordController::class, 'showForm'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmPasswordController::class, 'confirm']);
 
    // ========================================================
    // ADMIN ROUTES
    // ========================================================
    Route::prefix('admin')->name('admin.')->group(function () {
 
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile',   [AdminSettingController::class, 'index'])->name('profile');
        Route::get('/analytics', [AdminDashboardController::class, 'index'])->name('analytics');
 
        // Cache clear
        Route::post('/cache/clear', function () {
            \Artisan::call('cache:clear');
            \Artisan::call('view:clear');
            return back()->with('success', 'Cache cleared!');
        })->name('cache.clear');
 
        // Salon Requests
        Route::get('/salon-requests',              [SalonRequestController::class, 'index'])->name('salon-requests.index');
        Route::get('/salon-requests/{salon}',      [SalonRequestController::class, 'show'])->name('salon-requests.show');
        Route::post('/salon-requests/{salon}/approve', [SalonRequestController::class, 'approve'])->name('salon-requests.approve');
        Route::post('/salon-requests/{salon}/reject',  [SalonRequestController::class, 'reject'])->name('salon-requests.reject');
 
        // Salons
        Route::resource('salons', SalonManagementController::class);
        Route::post('/salons/{salon}/suspend', [SalonManagementController::class, 'suspend'])->name('salons.suspend');
        Route::post('/salons/{salon}/restore', [SalonManagementController::class, 'restore'])->name('salons.restore');
 
        // Owners
        Route::get('/owners',                        [OwnerManagementController::class, 'index'])->name('owners.index');
        Route::get('/owners/{user}',                 [OwnerManagementController::class, 'show'])->name('owners.show');
        Route::post('/owners/{user}/toggle-status',  [OwnerManagementController::class, 'toggleStatus'])->name('owners.toggle-status');
        Route::post('/owners/{user}/toggle',         [OwnerManagementController::class, 'toggleStatus'])->name('owners.toggle');
 
        // Clients
        Route::get('/clients',                        [ClientManagementController::class, 'index'])->name('clients.index');
        Route::get('/clients/export',                 [ClientManagementController::class, 'export'])->name('clients.export');
        Route::get('/clients/{user}',                 [ClientManagementController::class, 'show'])->name('clients.show');
        Route::post('/clients/{user}/toggle-status',  [ClientManagementController::class, 'toggleStatus'])->name('clients.toggle-status');
        Route::post('/clients/{user}/toggle',         [ClientManagementController::class, 'toggleStatus'])->name('clients.toggle');
 
        // Categories
        Route::resource('categories', CategoryController::class);
 
        // Appointments
        Route::get('/appointments/export',         [AppointmentMonitorController::class, 'export'])->name('appointments.export');
        Route::get('/appointments',                [AppointmentMonitorController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}',  [AppointmentMonitorController::class, 'show'])->name('appointments.show');
 
        // Payments
        Route::get('/payments/export', [PaymentMonitorController::class, 'export'])->name('payments.export');
        Route::get('/payments',        [PaymentMonitorController::class, 'index'])->name('payments.index');
 
        // Reviews
        Route::get('/reviews',                          [ReviewManagementController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/toggle-approval',[ReviewManagementController::class, 'toggleApproval'])->name('reviews.toggle-approval');
        Route::post('/reviews/{review}/toggle',         [ReviewManagementController::class, 'toggleApproval'])->name('reviews.toggle');
        Route::delete('/reviews/{review}',              [ReviewManagementController::class, 'destroy'])->name('reviews.destroy');
 

// COMPLAINT ROUTES

Route::get('/complaints', [ComplaintSubmitController::class, 'index'])->name('complaints.index');
Route::get('/complaints/create', [ComplaintSubmitController::class, 'create'])->name('complaints.create');
Route::post('/complaints', [ComplaintSubmitController::class, 'store'])->name('complaints.store');
Route::get('/complaints/{complaint}', [ComplaintSubmitController::class, 'show'])->name('complaints.show');
        // Notifications
        Route::get('/notifications',                       [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/send-to-all',          [NotificationController::class, 'sendToAll'])->name('notifications.send-to-all');
        Route::post('/notifications/send-to-owners',       [NotificationController::class, 'sendToOwners'])->name('notifications.send-to-owners');
 
        // Reports
        Route::get('/reports',         [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
 
        // Audit Logs
        Route::get('/audit-logs',        [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
 
        // Settings
        Route::get('/settings',           [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings',          [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/password', [AdminSettingController::class, 'updatePassword'])->name('settings.password');
 
        // Contact Messages
        Route::get('/contact-messages',                        [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::get('/contact-messages/{contactMessage}',       [ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::post('/contact-messages/{contactMessage}/reply',[ContactMessageController::class, 'reply'])->name('contact-messages.reply');
        Route::delete('/contact-messages/{contactMessage}',    [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
        Route::post('/contact-messages/bulk-delete',           [ContactMessageController::class, 'bulkDelete'])->name('contact-messages.bulk-delete');
 
        // FAQs
        Route::resource('faqs', FaqController::class);
        Route::post('/faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
        Route::post('/faqs/update-order',        [FaqController::class, 'updateOrder'])->name('faqs.update-order');
 
        // Hero Sliders
        Route::resource('hero-sliders', HeroSliderController::class);
        Route::post('/hero-sliders/{heroSlider}/toggle-status', [HeroSliderController::class, 'toggleStatus'])->name('hero-sliders.toggle-status');
        Route::post('/hero-sliders/update-order',               [HeroSliderController::class, 'updateOrder'])->name('hero-sliders.update-order');
 
        // System Settings
        Route::get('/system-settings',                [SystemSettingController::class, 'index'])->name('system-settings.index');
        Route::post('/system-settings/general',       [SystemSettingController::class, 'updateGeneral'])->name('system-settings.general');
        Route::post('/system-settings/payment',       [SystemSettingController::class, 'updatePayment'])->name('system-settings.payment');
        Route::post('/system-settings/email',         [SystemSettingController::class, 'updateEmail'])->name('system-settings.email');
        Route::post('/system-settings/social',        [SystemSettingController::class, 'updateSocial'])->name('system-settings.social');
        Route::post('/system-settings/test-email',    [SystemSettingController::class, 'testEmail'])->name('system-settings.test-email');
        Route::post('/system-settings/clear-cache',   [SystemSettingController::class, 'clearCache'])->name('system-settings.clear-cache');
    });
 
    // ========================================================
    // OWNER ROUTES
    // ========================================================
    Route::prefix('owner')->name('owner.')->group(function () {
 
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
 
        // Profile
        Route::get('/profile',              [OwnerProfileController::class, 'index'])->name('profile');
        Route::put('/profile',              [OwnerProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/upload-pic',  [OwnerProfileController::class, 'uploadPicture'])->name('profile.upload-pic');
 
        // Settings
        Route::get('/settings',             [OwnerSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/general',    [OwnerSettingController::class, 'general'])->name('settings.general');
        Route::post('/settings/password',   [OwnerSettingController::class, 'updatePassword'])->name('settings.password');
        Route::post('/settings',            [OwnerSettingController::class, 'general'])->name('settings.update');
 
        // Salons
        Route::resource('salons', OwnerSalonController::class);
 
        // Services
        Route::resource('services', OwnerServiceController::class);
        Route::post('/services/{service}/toggle-status', [OwnerServiceController::class, 'toggleStatus'])->name('services.toggle-status');
 
        // Categories
        Route::get('/categories', [OwnerServiceController::class, 'categories'])->name('categories.index');
 
        // Stylists
        Route::resource('stylists', OwnerStylistController::class);
        Route::post('/stylists/{id}/availability', [OwnerStylistController::class, 'storeAvailability'])->name('stylists.availability.store');
        Route::post('/stylists/{id}/holiday',      [OwnerStylistController::class, 'storeHoliday'])->name('stylists.holiday.store');
 
        // Stylist Availability & Holidays (these are used in owner layout navbar)
        Route::get('/stylists/{stylist}/availability',        [OwnerStylistController::class, 'availability'])->name('stylists.availability.index');
        Route::delete('/stylists/{stylist}/availability/{day}',[OwnerStylistController::class, 'destroyAvailability'])->name('stylists.availability.destroy');
        Route::get('/stylists/{stylist}/holidays',            [OwnerHolidayController::class, 'index'])->name('stylists.holidays.index');
        Route::post('/stylists/{stylist}/holidays',           [OwnerHolidayController::class, 'store'])->name('stylists.holidays.store');
        Route::delete('/stylists/{stylist}/holidays/{holiday}',[OwnerHolidayController::class, 'destroy'])->name('stylists.holidays.destroy');
 
        // Time Slots
        Route::get('/time-slots',                       [OwnerTimeSlotController::class, 'index'])->name('time-slots.index');
        Route::post('/time-slots/generate',             [OwnerTimeSlotController::class, 'generate'])->name('time-slots.generate');
        Route::post('/time-slots/{timeSlot}/toggle',    [OwnerTimeSlotController::class, 'toggleStatus'])->name('time-slots.toggle');
 
        // Appointments
        Route::get('/appointments/export',             [OwnerAppointmentController::class, 'export'])->name('appointments.export');
        Route::resource('appointments', OwnerAppointmentController::class);
        Route::post('/appointments/{id}/approve',      [OwnerAppointmentController::class, 'approve'])->name('appointments.approve');
        Route::post('/appointments/{id}/confirm',      [OwnerAppointmentController::class, 'approve'])->name('appointments.confirm');
        Route::post('/appointments/{id}/complete',     [OwnerAppointmentController::class, 'complete'])->name('appointments.complete');
        Route::post('/appointments/{id}/cancel',       [OwnerAppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::get('/appointments/{id}/invoice',       [OwnerAppointmentController::class, 'invoice'])->name('appointments.invoice');
 
        // Payments
        Route::get('/payments/export',                 [OwnerPaymentController::class, 'export'])->name('payments.export');
        Route::resource('payments', OwnerPaymentController::class);
        Route::post('/payments/{payment}/approve',     [OwnerPaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{payment}/reject',      [OwnerPaymentController::class, 'reject'])->name('payments.reject');
 
        // Packages
        Route::resource('packages', OwnerPackageController::class);
        Route::post('/packages/{package}/toggle-status', [OwnerPackageController::class, 'toggleStatus'])->name('packages.toggle-status');
 
        // Reviews
        Route::resource('reviews', OwnerReviewController::class);
        Route::post('/reviews/{review}/approve', [OwnerReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{review}/reply',   [OwnerReviewController::class, 'reply'])->name('reviews.reply');
        Route::post('/reviews/{review}/flag',    [OwnerReviewController::class, 'toggleFlag'])->name('reviews.flag');
 
        // Gallery
        Route::resource('gallery', OwnerGalleryController::class);
        Route::post('/gallery/reorder', [OwnerGalleryController::class, 'reorder'])->name('gallery.reorder');
 
        // Holidays (Salon closed days)
        Route::resource('holidays', OwnerSalonHolidayController::class);
 
        // Waitlist
        Route::resource('waitlist', OwnerWaitlistController::class);
        Route::post('/waitlist/{id}/notify', [OwnerWaitlistController::class, 'notify'])->name('waitlist.notify');
        Route::get('/waitlist/{waitlist}',   [OwnerWaitlistController::class, 'show'])->name('waitlist.show');
        Route::delete('/waitlist/{waitlist}',[OwnerWaitlistController::class, 'remove'])->name('waitlist.remove');

        // Clients
        Route::get('/clients/export',        [OwnerClientController::class, 'export'])->name('clients.export');
        Route::resource('clients', OwnerClientController::class);
        // Waitlist
        
        // Notifications
        Route::get('/notifications',                    [OwnerNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read',         [OwnerNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all',          [OwnerNotificationController::class, 'markAllRead'])->name('notifications.read-all');
 
        // Reports
        Route::get('/reports',         [OwnerReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/export', [OwnerReportController::class, 'export'])->name('reports.export');
 
        // Analytics
        Route::get('/analytics',         [OwnerAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/revenue', [OwnerAnalyticsController::class, 'revenue'])->name('analytics.revenue');
    });
 
    // ========================================================
    // CLIENT ROUTES
    // ========================================================
    Route::prefix('client')->name('client.')->group(function () {
 
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
 
        // Search
        Route::get('/search',        [SalonSearchController::class, 'index'])->name('search');
        Route::get('/salons/{slug}', [SalonDetailController::class, 'show'])->name('salons.show');
 
        // AJAX slots (client panel)
        Route::get('/booking/{salon}/slots', [BookingStepController::class, 'getSlots'])->name('booking.slots');
        Route::get('/booking/{salon}/dates', [BookingStepController::class, 'getAvailableDates'])->name('booking.dates');
 
        // Payment
        Route::get('/payment/{appointment}',  [PaymentSubmitController::class, 'show'])->name('payment.show');
        Route::post('/payment/{appointment}', [PaymentSubmitController::class, 'store'])->name('payment.submit');
 
        // Appointments
        Route::get('/appointments',                        [AppointmentManageController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}',          [AppointmentManageController::class, 'show'])->name('appointments.show');
        Route::post('/appointments/{appointment}/cancel',  [AppointmentManageController::class, 'cancel'])->name('appointments.cancel');
        Route::get('/appointments/{appointment}/reschedule',[RescheduleController::class, 'create'])->name('appointments.reschedule');
 
        // Reschedule
        Route::get('/reschedule/{appointment}',  [RescheduleController::class, 'create'])->name('reschedule.create');
        Route::post('/reschedule/{appointment}', [RescheduleController::class, 'store'])->name('reschedule.store');
 
        // Waitlist
        Route::get('/waitlist',                      [WaitlistJoinController::class, 'index'])->name('waitlist.index');
        Route::post('/waitlist/join',                [WaitlistJoinController::class, 'join'])->name('waitlist.join');
        Route::post('/waitlist/{waitlist}/accept',   [WaitlistJoinController::class, 'accept'])->name('waitlist.accept');
        Route::post('/waitlist/{waitlist}/reject',   [WaitlistJoinController::class, 'reject'])->name('waitlist.reject');
 
        // Favorites
        Route::get('/favorites',                     [FavoriteSalonController::class, 'index'])->name('favorites.index');
        Route::post('/favorites/{salon}/toggle',     [FavoriteSalonController::class, 'toggle'])->name('favorites.toggle');
 
        // Reviews
        Route::post('/reviews/{appointment}', [ReviewSubmitController::class, 'store'])->name('reviews.store');
 
        // Complaints
        Route::get('/complaints',                      [ComplaintSubmitController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/create/{appointment}', [ComplaintSubmitController::class, 'create'])->name('complaints.create');
        Route::post('/complaints/{appointment}',       [ComplaintSubmitController::class, 'store'])->name('complaints.store');
        Route::get('/complaints/{complaint}',          [ComplaintSubmitController::class, 'show'])->name('complaints.show');
 
        // Notifications
        Route::get('/notifications',                    [ClientNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read',         [ClientNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all',          [ClientNotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::delete('/notifications/{id}',            [ClientNotificationController::class, 'destroy'])->name('notifications.destroy');
 
        // Profile
        Route::get('/profile',  [ClientProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
    });
 
}); // end auth middleware


Route::get('/test-complaint', function () {
    return 'Test route is working!';
});

// TEST ROUTE - COMPLAINTS CREATE
Route::get('/client/complaints/create', function () {
    return view('client.complaints.create');
});