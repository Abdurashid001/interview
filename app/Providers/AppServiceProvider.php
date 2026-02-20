<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Interview;
use App\Policies\InterviewPolicy;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{

    protected $policies = [
        Interview::class => InterviewPolicy::class,
    ];
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
        // Current password validation rule
    Validator::extend('current_password', function ($attribute, $value, $parameters, $validator) {
        return Hash::check($value, Auth::user()->password);
    }, 'Joriy parol noto\'g\'ri!');
    }
}
