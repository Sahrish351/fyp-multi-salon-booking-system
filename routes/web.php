<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ConfirmPasswordController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PublicSalonController;
use App\Http\Controllers\Frontend\PublicServiceController;
use App\Http\Controllers\Frontend\PublicStylistController;
use App\Http\Controllers\Frontend\PublicGalleryController;
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
use App\Http\Controllers\Owner\OwnerAvailabilityController;
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
use App\Http\Controllers\Owner\OwnerSalonInfoController;


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
use App\Http\Controllers\Client\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==================== PUBLIC ROUTES ====================

Route::get('/', [HomeController::class, 'index'])->name('home');
// Fallback route for salon search
Route::get('/salons/search', [App\Http\Controllers\Frontend\PublicSalonController::class, 'search'])->name('salons.search');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Salon Routes
Route::get('/salons', [PublicSalonController::class, 'index'])->name('salons.index');
Route::get('/salons/{slug}', [PublicSalonController::class, 'show'])->name('salons.show');
Route::get('/salons/{slug}/gallery', [PublicSalonController::class, 'gallery'])->name('salons.gallery');

// ==================== PUBLIC BOOKING ROUTES ====================
Route::prefix('booking')->name('booking.')->group(function () {
    
    // Step 1: Services
    Route::get('/services/{salon_id}', [BookingController::class, 'step1Services'])->name('step1');
    Route::post('/services/{salon_id}', [BookingController::class, 'postStep1Services'])->name('step1.post');
    
    // Step 2: Stylist
    Route::get('/stylist/{salon_id}', [BookingController::class, 'step2Stylist'])->name('step2');
    Route::post('/stylist/{salon_id}', [BookingController::class, 'postStep2Stylist'])->name('step2.post');
    
    // Step 3: Date & Time
    Route::get('/datetime/{salon_id}', [BookingController::class, 'step3DateTime'])->name('step3');
    Route::post('/datetime/{salon_id}', [BookingController::class, 'postStep3DateTime'])->name('step3.post');
    
    // Step 4: Payment
    Route::get('/payment/{salon_id}', [BookingController::class, 'step4Payment'])->name('step4');
    Route::post('/payment/{salon_id}', [BookingController::class, 'postPayment'])->name('payment.post');
    
    // Confirmation
    Route::get('/confirmation/{booking_id}', [BookingController::class, 'confirmation'])->name('confirmation');
});

// Service Routes
Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/services/category/{slug}', [PublicServiceController::class, 'byCategory'])->name('services.by-category');
Route::get('/salons/{salonSlug}/services/{serviceId}', [PublicServiceController::class, 'show'])->name('services.show');

// Stylist Routes
// Route::get('/salons/{salonSlug}/stylists', [PublicStylistController::class, 'index'])->name('stylists.index');
// Route::get('/salons/{salonSlug}/stylists/{stylistId}', [PublicStylistController::class, 'show'])->name('stylists.show');
// Route::get('/salons/{salonSlug}/stylists/{stylistId}/availability', [PublicStylistController::class, 'getAvailability'])->name('stylists.availability');

// Gallery Routes
// Route::get('/salons/{salonSlug}/gallery', [PublicGalleryController::class, 'index'])->name('gallery.index');
// Route::get('/salons/{salonSlug}/gallery/category/{categorySlug}', [PublicGalleryController::class, 'category'])->name('gallery.category');
// Route::get('/salons/{salonSlug}/gallery/image/{imageId}', [PublicGalleryController::class, 'showImage'])->name('gallery.show');


// ============================================================
// REDIRECT ROUTES FOR NAVBAR/FOOTER (NO PARAMETER NEEDED)
// ============================================================
Route::get('/stylists', function() {
    return redirect()->route('salons.index');
})->name('stylists.index');

Route::get('/gallery', function() {
    return redirect()->route('salons.index');
})->name('gallery.index');

/// ==================== AUTH ROUTES ====================
Route::middleware('guest')->group(function () {
    
    // Selector Pages
    Route::view('/select-login', 'auth.login-selector')->name('select.login');
    Route::view('/select-register', 'auth.register-selector')->name('register.selector');

    // Redirect default login
    Route::redirect('/login', '/select-login')->name('login');

    // ========== CLIENT LOGIN ==========
    Route::get('/client/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('client.login.form');
Route::post('/client/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('client.login.submit');

    // ========== OWNER LOGIN ==========
    Route::get('/owner/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('owner.login.form');
Route::post('/owner/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('owner.login.submit');

    // ========== ADMIN LOGIN ==========
    Route::get('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('admin.login.submit');

    // ========== REGISTRATION ==========
    Route::get('/register/client', [App\Http\Controllers\Auth\RegisterController::class, 'showClientForm'])->name('register.client');
    Route::get('/register/owner', [App\Http\Controllers\Auth\RegisterController::class, 'showOwnerForm'])->name('register.owner');
    Route::post('/register/client', [App\Http\Controllers\Auth\RegisterController::class, 'registerClient'])->name('register.client.store');
    Route::post('/register/owner', [App\Http\Controllers\Auth\RegisterController::class, 'registerOwner'])->name('register.owner.store');
    
    // ========== FORGOT PASSWORD ==========
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
    
    Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    // ========== OTP VERIFICATION ==========
    Route::get('/otp/verify', [App\Http\Controllers\Auth\OtpController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('/otp/verify', [App\Http\Controllers\Auth\OtpController::class, 'verify'])->name('otp.verify.submit');
    Route::post('/otp/resend', [App\Http\Controllers\Auth\OtpController::class, 'resend'])->name('otp.resend');

    // ========== GOOGLE LOGIN ==========
    Route::get('/auth/google', [App\Http\Controllers\Auth\SocialLoginController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [App\Http\Controllers\Auth\SocialLoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// ==================== AUTHENTICATED ROUTES ====================
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
    
    // Email Verification
    Route::get('/email/verify', [VerificationController::class, 'showNotice'])->name('verification.notice');
    Route::get('/email/verify/send', [VerificationController::class, 'sendVerificationEmail'])->name('verification.send');
    Route::post('/email/verify', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    
    // Phone Verification
    Route::get('/phone/verify', [VerificationController::class, 'showPhoneForm'])->name('phone.verify');
    Route::post('/phone/verify/send', [VerificationController::class, 'sendPhoneOtp'])->name('phone.verify.send');
    Route::post('/phone/verify', [VerificationController::class, 'verifyPhone'])->name('phone.verify.submit');
    
    // Password Confirmation
    Route::get('/confirm-password', [ConfirmPasswordController::class, 'showForm'])->name('password.confirm');
    Route::post('/confirm-password', [ConfirmPasswordController::class, 'confirm']);
    
    // ==================== ADMIN ROUTES ====================
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Salon Requests
        Route::get('/salon-requests', [SalonRequestController::class, 'index'])->name('salon-requests.index');
        Route::get('/salon-requests/{salon}', [SalonRequestController::class, 'show'])->name('salon-requests.show');
        Route::post('/salon-requests/{salon}/approve', [SalonRequestController::class, 'approve'])->name('salon-requests.approve');
        Route::post('/salon-requests/{salon}/reject', [SalonRequestController::class, 'reject'])->name('salon-requests.reject');
        
        // Salon Management
        Route::resource('salons', SalonManagementController::class);
        Route::post('/salons/{salon}/suspend', [SalonManagementController::class, 'suspend'])->name('salons.suspend');
        Route::post('/salons/{salon}/restore', [SalonManagementController::class, 'restore'])->name('salons.restore');
        
        // Owner Management
        Route::get('/owners', [OwnerManagementController::class, 'index'])->name('owners.index');
        Route::get('/owners/{user}', [OwnerManagementController::class, 'show'])->name('owners.show');
        Route::post('/owners/{user}/toggle-status', [OwnerManagementController::class, 'toggleStatus'])->name('owners.toggle-status');
        
        // Client Management
        Route::get('/clients', [ClientManagementController::class, 'index'])->name('clients.index');
        Route::get('/clients/{user}', [ClientManagementController::class, 'show'])->name('clients.show');
        Route::post('/clients/{user}/toggle-status', [ClientManagementController::class, 'toggleStatus'])->name('clients.toggle-status');
        
        // Categories
        Route::resource('categories', CategoryController::class);
        
        // Appointments Monitor
        Route::get('/appointments', [AppointmentMonitorController::class, 'index'])->name('appointments.index');
        
        // Payments Monitor
        Route::get('/payments', [PaymentMonitorController::class, 'index'])->name('payments.index');
        
        // Reviews Management
        Route::get('/reviews', [ReviewManagementController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/toggle-approval', [ReviewManagementController::class, 'toggleApproval'])->name('reviews.toggle-approval');
        Route::delete('/reviews/{review}', [ReviewManagementController::class, 'destroy'])->name('reviews.destroy');
        
        // Complaints
        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
        Route::post('/complaints/{complaint}/reply', [ComplaintController::class, 'reply'])->name('complaints.reply');
        Route::post('/complaints/{complaint}/resolve', [ComplaintController::class, 'resolve'])->name('complaints.resolve');
        
        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/send-to-all', [NotificationController::class, 'sendToAll'])->name('notifications.send-to-all');
        Route::post('/notifications/send-to-owners', [NotificationController::class, 'sendToOwners'])->name('notifications.send-to-owners');
        
        // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        
        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        
        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/password', [AdminSettingController::class, 'updatePassword'])->name('settings.password');

        // Profile Route (missing tha)
Route::get('/profile', [AdminSettingController::class, 'index'])->name('profile');

// Analytics Route (missing tha)  
Route::get('/analytics', [AdminDashboardController::class, 'index'])->name('analytics');

// Missing toggles
Route::post('/clients/{user}/toggle', [ClientManagementController::class, 'toggleStatus'])->name('clients.toggle');
Route::post('/owners/{user}/toggle', [OwnerManagementController::class, 'toggleStatus'])->name('owners.toggle');

// Cache Clear
Route::post('/cache/clear', function() {
    \Artisan::call('cache:clear');
    \Artisan::call('view:clear');
    return back()->with('success', 'Cache cleared!');
})->name('cache.clear');

Route::get('/appointments/export', [AppointmentMonitorController::class, 'export'])->name('appointments.export');
Route::get('/appointments/{appointment}', [AppointmentMonitorController::class, 'show'])->name('appointments.show');

// Payments export
Route::get('/payments/export', [PaymentMonitorController::class, 'export'])->name('payments.export');

// Clients export
Route::get('/clients/export', [ClientManagementController::class, 'export'])->name('clients.export');

// Reviews toggle (name fix)
Route::post('/reviews/{review}/toggle', [ReviewManagementController::class, 'toggleApproval'])->name('reviews.toggle');

// Audit logs export
Route::get('/audit-logs/export', [AuditLogController::class, 'export'])->name('audit-logs.export');
        
        // Contact Messages
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::get('/contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
        Route::post('/contact-messages/{contactMessage}/reply', [ContactMessageController::class, 'reply'])->name('contact-messages.reply');
        Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
        Route::post('/contact-messages/bulk-delete', [ContactMessageController::class, 'bulkDelete'])->name('contact-messages.bulk-delete');
        
        // FAQs
        Route::resource('faqs', FaqController::class);
        Route::post('/faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
        Route::post('/faqs/update-order', [FaqController::class, 'updateOrder'])->name('faqs.update-order');
        
        // Hero Sliders
        Route::resource('hero-sliders', HeroSliderController::class);
        Route::post('/hero-sliders/{heroSlider}/toggle-status', [HeroSliderController::class, 'toggleStatus'])->name('hero-sliders.toggle-status');
        Route::post('/hero-sliders/update-order', [HeroSliderController::class, 'updateOrder'])->name('hero-sliders.update-order');
        
        // System Settings
        Route::get('/system-settings', [SystemSettingController::class, 'index'])->name('system-settings.index');
        Route::post('/system-settings/general', [SystemSettingController::class, 'updateGeneral'])->name('system-settings.general');
        Route::post('/system-settings/payment', [SystemSettingController::class, 'updatePayment'])->name('system-settings.payment');
        Route::post('/system-settings/email', [SystemSettingController::class, 'updateEmail'])->name('system-settings.email');
        Route::post('/system-settings/social', [SystemSettingController::class, 'updateSocial'])->name('system-settings.social');
        Route::post('/system-settings/test-email', [SystemSettingController::class, 'testEmail'])->name('system-settings.test-email');
        Route::post('/system-settings/clear-cache', [SystemSettingController::class, 'clearCache'])->name('system-settings.clear-cache');

         // Logout Route - YEH ADD KARO
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/admin/login');
    })->name('logout.custom');
    });
    

// ==================== OWNER ROUTES ====================
Route::prefix('owner')->middleware(['auth'])->name('owner.')->group(function () {
    
    // ========== 1. DASHBOARD ==========
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    
    // ========== 2. PROFILE ==========
    Route::get('/profile', [OwnerProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [OwnerProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-pic', [OwnerProfileController::class, 'uploadPicture'])->name('profile.upload-pic');
    Route::post('/profile/notifications', [OwnerProfileController::class, 'updateNotifications'])->name('profile.notifications');
    
    // ========== 3. SETTINGS ==========
    Route::get('/settings', [OwnerSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [OwnerSettingController::class, 'general'])->name('settings.general');
    Route::post('/settings/notifications', [OwnerSettingController::class, 'notifications'])->name('settings.notifications');
    Route::post('/settings/password', [OwnerSettingController::class, 'updatePassword'])->name('settings.password');
    
    // ========== 4. SALON MANAGEMENT ==========
    Route::resource('salons', OwnerSalonController::class);
    
    // ========== 5. SALON INFO ==========
    Route::get('/salon-info', [OwnerSalonController::class, 'editInfo'])->name('salon.edit');
    Route::put('/salon-info', [OwnerSalonController::class, 'updateInfo'])->name('salon.update');

    // ========== 6. SERVICES ==========
    Route::resource('services', OwnerServiceController::class);
    Route::post('/services/{service}/toggle-status', [OwnerServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    
    // ========== 7. CATEGORIES ==========
    Route::get('/services/categories', [OwnerServiceController::class, 'categories'])->name('services.categories');
    Route::post('/services/categories', [OwnerServiceController::class, 'categoriesStore'])->name('services.categories.store');
    Route::put('/services/categories/{id}', [OwnerServiceController::class, 'categoriesUpdate'])->name('services.categories.update');
    Route::delete('/services/categories/{id}', [OwnerServiceController::class, 'categoriesDestroy'])->name('services.categories.destroy');
// ========== CATEGORIES INDEX ROUTE (for link) ==========
Route::get('/categories', [OwnerServiceController::class, 'categories'])->name('categories.index');
    // ========== 8. STYLISTS (YEH SAHI HAI) ==========
    Route::resource('stylists', OwnerStylistController::class);
    
     // EXTRA ROUTES FOR AVAILABILITY & HOLIDAY (YEH BHI IMPORTANT HAI)
    Route::post('/stylists/{id}/availability', [OwnerStylistController::class, 'storeAvailability'])->name('stylists.availability.store');
    Route::post('/stylists/{id}/holiday', [OwnerStylistController::class, 'storeHoliday'])->name('stylists.holiday.store');

    // ========== 9. TIME SLOTS ==========
    Route::resource('time-slots', OwnerTimeSlotController::class);
    Route::post('/time-slots/generate', [OwnerTimeSlotController::class, 'generate'])->name('time-slots.generate');
    Route::post('/time-slots/{timeSlot}/toggle', [OwnerTimeSlotController::class, 'toggleStatus'])->name('time-slots.toggle');
    
    // ========== 10. APPOINTMENTS ==========
    Route::resource('appointments', OwnerAppointmentController::class);
    Route::post('/appointments/{id}/approve', [OwnerAppointmentController::class, 'approve'])->name('appointments.approve');
    Route::post('/appointments/{id}/complete', [OwnerAppointmentController::class, 'complete'])->name('appointments.complete');
    Route::post('/appointments/{id}/cancel', [OwnerAppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/{id}/invoice', [OwnerAppointmentController::class, 'invoice'])->name('appointments.invoice');
    Route::get('/appointments/export', [OwnerAppointmentController::class, 'export'])->name('appointments.export');
    
    // ========== 11. CLIENTS ==========
    Route::resource('clients', OwnerClientController::class);
    Route::post('/clients/{id}/toggle', [OwnerClientController::class, 'toggle'])->name('clients.toggle');
    Route::get('/clients/export', [OwnerClientController::class, 'export'])->name('clients.export');
    
    // ========== 12. PAYMENTS ==========
    Route::resource('payments', OwnerPaymentController::class);
    Route::post('/payments/{payment}/approve', [OwnerPaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [OwnerPaymentController::class, 'reject'])->name('payments.reject');
    Route::get('/payments/export', [OwnerPaymentController::class, 'export'])->name('payments.export');
    
    // ========== 13. PACKAGES ==========
    Route::resource('packages', OwnerPackageController::class);
    Route::post('/packages/{package}/toggle-status', [OwnerPackageController::class, 'toggleStatus'])->name('packages.toggle-status');
    Route::post('/packages/{package}/toggle-popular', [OwnerPackageController::class, 'togglePopular'])->name('packages.toggle-popular');
    
    // ========== 14. REVIEWS ==========
    Route::resource('reviews', OwnerReviewController::class);
    Route::post('/reviews/{review}/approve', [OwnerReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reply', [OwnerReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('/reviews/{review}/flag', [OwnerReviewController::class, 'toggleFlag'])->name('reviews.flag');
    
    // ========== 15. GALLERY ==========
    Route::resource('gallery', OwnerGalleryController::class);
    Route::post('/gallery/reorder', [OwnerGalleryController::class, 'reorder'])->name('gallery.reorder');
    
    // ========== 16. HOLIDAYS (Salon Closed Days) ==========
    Route::resource('holidays', OwnerSalonHolidayController::class);
    
    Route::resource('waitlist', OwnerWaitlistController::class);
    Route::post('/waitlist/{id}/notify', [OwnerWaitlistController::class, 'notify'])->name('waitlist.notify');
    // ========== 18. NOTIFICATIONS ==========
    Route::resource('notifications', OwnerNotificationController::class);
    Route::post('/notifications/send', [OwnerNotificationController::class, 'sendBulk'])->name('notifications.send');
    Route::post('/notifications/{id}/read', [OwnerNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [OwnerNotificationController::class, 'markAllRead'])->name('notifications.read-all');
    
    // ========== 19. REPORTS ==========
    Route::get('/reports', [OwnerReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [OwnerReportController::class, 'export'])->name('reports.export');  
    
    // ========== 20. ANALYTICS ==========
    Route::get('/analytics', [OwnerAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/revenue', [OwnerAnalyticsController::class, 'revenue'])->name('analytics.revenue');
    Route::get('/analytics/booking-trends', [OwnerAnalyticsController::class, 'bookingTrends'])->name('analytics.trends');
    
    // ========== 21. LOGOUT ==========
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});
    
    // ==================== CLIENT ROUTES ====================
   Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        
        // Search & Explore
        Route::get('/search', [SalonSearchController::class, 'index'])->name('search');
        Route::get('/salons/{slug}', [SalonDetailController::class, 'show'])->name('salons.show');

                          // ==================== BOOKING FLOW ====================

// Step 1 - Select Service
Route::get('/booking/{salon}/step1', [BookingController::class, 'step1'])->name('booking.step1');
Route::post('/booking/{salon}/step1', [BookingController::class, 'step1Post'])->name('booking.step1.post');

// Step 2 - Select Stylist
Route::get('/booking/{salon}/step2', [BookingController::class, 'step2'])->name('booking.step2');
Route::post('/booking/{salon}/step2', [BookingController::class, 'step2Post'])->name('booking.step2.post');

// Step 3 - Select Time
Route::get('/booking/{salon}/step3', [BookingController::class, 'step3'])->name('booking.step3');
Route::post('/booking/{salon}/step3', [BookingController::class, 'step3Post'])->name('booking.step3.post');

// Step 4 - Payment
Route::get('/booking/{salon}/step4', [BookingController::class, 'step4'])->name('booking.step4');
Route::post('/booking/{salon}/step4', [BookingController::class, 'step4Post'])->name('booking.step4.post');

Route::post('/stripe/payment', [App\Http\Controllers\Client\PaymentController::class, 'stripePost'])->name('stripe.post');

// Step 5 - Confirm (Optional, if you have separate confirm page)
Route::get('/booking/{salon}/confirm/{appointment}', [BookingController::class, 'confirm'])->name('booking.confirm');

// Booking AJAX endpoints
Route::get('/booking/{salon}/slots', [BookingStepController::class, 'getSlots'])->name('booking.slots');
Route::get('/booking/{salon}/dates', [BookingStepController::class, 'getAvailableDates'])->name('booking.dates');
        
        // Payment
        Route::get('/payment/{appointment}', [PaymentSubmitController::class, 'show'])->name('payment.show');
        Route::post('/payment/{appointment}', [PaymentSubmitController::class, 'store'])->name('payment.submit');
        
        // Appointments
        Route::get('/appointments', [AppointmentManageController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/{appointment}', [AppointmentManageController::class, 'show'])->name('appointments.show');
        Route::post('/appointments/{appointment}/cancel', [AppointmentManageController::class, 'cancel'])->name('appointments.cancel');
        
        // Reschedule
        Route::get('/reschedule/{appointment}', [RescheduleController::class, 'create'])->name('reschedule.create');
        Route::post('/reschedule/{appointment}', [RescheduleController::class, 'store'])->name('reschedule.store');
        
        // Waitlist
        Route::get('/waitlist', [WaitlistJoinController::class, 'index'])->name('waitlist.index');
        Route::post('/waitlist/join', [WaitlistJoinController::class, 'join'])->name('waitlist.join');
        Route::post('/waitlist/{waitlist}/accept', [WaitlistJoinController::class, 'accept'])->name('waitlist.accept');
        Route::post('/waitlist/{waitlist}/reject', [WaitlistJoinController::class, 'reject'])->name('waitlist.reject');
        
        // Favorites
        Route::get('/favorites', [FavoriteSalonController::class, 'index'])->name('favorites.index');
        Route::post('/favorites/{salon}/toggle', [FavoriteSalonController::class, 'toggle'])->name('favorites.toggle');
        
        // Reviews
        Route::post('/reviews/{appointment}', [ReviewSubmitController::class, 'store'])->name('reviews.store');
        
        // Complaints
        Route::get('/complaints', [ComplaintSubmitController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/create/{appointment}', [ComplaintSubmitController::class, 'create'])->name('complaints.create');
        Route::post('/complaints/{appointment}', [ComplaintSubmitController::class, 'store'])->name('complaints.store');
        Route::get('/complaints/{complaint}', [ComplaintSubmitController::class, 'show'])->name('complaints.show');
        
        // Notifications
        Route::get('/notifications', [ClientNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [ClientNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [ClientNotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::delete('/notifications/{id}', [ClientNotificationController::class, 'destroy'])->name('notifications.destroy');
        
        // Profile
        Route::get('/profile', [ClientProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
    });
});