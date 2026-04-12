<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

class AppServiceProvider extends ServiceProvider
{
  public function register(): void
{
    // FIX untuk error UriInterface
    $this->app->bind(UriInterface::class, Uri::class);

    // Daftarkan Firebase Database
    $this->app->singleton(\Kreait\Firebase\Contract\Database::class, function ($app) {
        // cari directory
        $storagePath = base_path(env('FIREBASE_CREDENTIALS'));

        return (new \Kreait\Firebase\Factory)
            ->withServiceAccount($storagePath)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'))
            ->createDatabase();
    });
}

    public function boot(): void
    {
        //
    }
}
