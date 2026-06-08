<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        View::composer('*', function ($view) {
            $waNumber = '';
            $waTemplate = '';
            
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                    $waNumber = Setting::where('key', 'wa_number')->value('value') ?? '';
                    $waTemplate = Setting::where('key', 'wa_template')->value('value') ?? '';
                }
            } catch (\Exception $e) {
                // Keep default empty values if database isn't ready
            }

            $waLink = '';
            if (!empty($waNumber)) {
                // Strip any non-numeric characters from wa_number
                $sanitizedNumber = preg_replace('/[^0-9]/', '', $waNumber);
                
                // If it starts with 0, replace the 0 with 62
                if (strpos($sanitizedNumber, '0') === 0) {
                    $sanitizedNumber = '62' . substr($sanitizedNumber, 1);
                }
                
                // Use urlencode() on the wa_template to ensure spaces and special characters work in the URL
                $encodedTemplate = urlencode($waTemplate);
                
                // Combine them into a $waLink variable
                $waLink = "https://wa.me/{$sanitizedNumber}?text={$encodedTemplate}";
            }

            $view->with('waLink', $waLink);
        });
    }
}
