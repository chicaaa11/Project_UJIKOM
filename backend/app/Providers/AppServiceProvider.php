<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use App\Observers\PeminjamanObserver;
use App\Observers\PengembalianObserver;
use App\Observers\AlatObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Peminjaman::observe(PeminjamanObserver::class);
        Pengembalian::observe(PengembalianObserver::class);
        Alat::observe(AlatObserver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
