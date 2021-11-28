<?php

declare(strict_types=1);

/**
 * Contains the WooCommerceImportCommand class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Console\Commands;

use Illuminate\Console\Command;
use Vanilo\WooCommerce\Import\CsvExportLoader;

class WooCommerceImportCommand extends Command
{
    protected $signature = 'woocom:import {file}';

    protected $description = 'Import CSV export files from WooCommerce';

    public function handle(CsvExportLoader $loader)
    {
        $products = $loader->parseFile($this->argument('file'));
    }
}
