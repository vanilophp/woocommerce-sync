<?php

declare(strict_types=1);

/**
 * Contains the ImportCommandTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Vanilo\WooCommerce\Tests\TestCase;

class ImportCommandTest extends TestCase
{
    /** @test */
    public function the_artisan_command_exists()
    {
        $this->assertEquals(0, Artisan::call('woocom:import', ['--help']));
    }

    ////$this->assertEquals(Concord::VERSION, rtrim(Artisan::output()));
}
