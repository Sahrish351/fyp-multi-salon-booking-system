<?php
 
namespace App\Providers;
 
use Illuminate\Support\ServiceProvider;
 
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
 
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mail::alwaysTo() yahan se hata diya gaya hai.
        // Ab har mail apne asal recipient (client ya owner ki real email) ko jayegi —
        // koi bhi single address par force-redirect nahi hogi.
    }
}