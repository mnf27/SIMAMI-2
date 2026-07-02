<?php

namespace App\Providers;

use App\Models\Periode;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        Paginator::useTailwind();

        if (! session()->has('periode')) {
            $activePeriode = Periode::where('is_active', true)->first();
            if (! $activePeriode) {
                return;
            }
            if (! session()->has('periode')) {
                session([
                    'periode' => $activePeriode->kode
                ]);
                return;
            }
            $lastActive = session('last_active_periode');
            if (
                $lastActive &&
                session('periode') === $lastActive &&
                $activePeriode->kode !== $lastActive
            ) {
                session([
                    'periode' => $activePeriode->kode
                ]);
            }
            session([
                'last_active_periode' => $activePeriode->kode
            ]);
        }
    }
}
