<?php

namespace App\Providers;

use App\Models\WasteReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

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
        ViewFacade::composer('components.sidebar', function (View $view): void {
            $user = Auth::user();
            $pickupCount = null;

            if ($user?->role === 'petugas') {
                $lastSeenReportId = (int) session()->get(
                    'petugas_pickups_last_seen_'.$user->getAuthIdentifier(),
                    0
                );

                $pickupCount = WasteReport::query()
                    ->whereIn('status', ['reported', 'approved'])
                    ->where('id', '>', $lastSeenReportId)
                    ->count();
            }

            $view->with('pickupCount', $pickupCount);
        });
    }
}
