<?php

namespace App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class QueryLogger extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // FIXME: This should only be ran if environment variable `DEBUG` is set to true.
        DB::listen(function (QueryExecuted $query) {
            \Log::debug("\nRan query:");
            \Log::debug($query->sql);
            \Log::debug('Bound arguments: ');
            \Log::debug(implode(', ', $query->bindings));
            \Log::debug("Took $query->time ms. ");
        });
    }
}
