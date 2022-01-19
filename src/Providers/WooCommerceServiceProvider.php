<?php

declare(strict_types=1);

/**
 * Contains the WooCommerceServiceProvider class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Providers;

use Illuminate\Support\ServiceProvider;
use Vanilo\WooCommerce\Console\Commands\WooCommerceImportCommand;

class WooCommerceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                WooCommerceImportCommand::class,
            ]);
        }
    }
}
