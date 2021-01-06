<?php

namespace Ejoy\Reservation;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ReservationServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Reservation $extension)
    {
        if (! Reservation::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'reservation');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/ejoy/reservation')],
                'reservation'
            );
        }

        $this->app->booted(function () {
            Route::group([], __DIR__.'/../routes/web.php');
//            Reservation::routes(__DIR__.'/../routes/web.php');
        });
    }
}
