<?php

declare(strict_types=1);

/**
 * Contains the ProductType class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Models;

use Konekt\Enum\Enum;

final class ProductType extends Enum
{
    public const __DEFAULT = self::SIMPLE;
    public const SIMPLE = 'simple';
    public const VARIABLE = 'variable';
    public const VARIATION = 'variation';
}
