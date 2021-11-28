<?php

declare(strict_types=1);

/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\WooCommerce\Console\Commands\WooCommerceImportCommand;

class ModuleServiceProvider extends BaseModuleServiceProvider
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
