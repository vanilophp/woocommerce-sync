<?php

declare(strict_types=1);

/**
 * Contains the ProductCollection class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Models;

use Illuminate\Support\Collection;
use InvalidArgumentException;

class ProductCollection extends Collection
{
    public function __construct(Product ...$items)
    {
        $this->push(...$items);
    }

    /**
     * @param Product $item
     * @return ProductCollection
     */
    public function add($item)
    {
        $this->checkIfProperType($item);

        return $this->put($item->id, $item);
    }

    public function put($key, $value)
    {
        $this->checkIfProperType($value);

        return parent::put($key, $value);
    }

    public function push(...$values)
    {
        foreach ($values as $item) {
            $this->add($item);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function checkIfProperType($item): void
    {
        if ($item instanceof Product) {
            return;
        }

        throw new InvalidArgumentException(
            sprintf('Item is a `%s` and not a WooCommerce Product instance', get_class($item))
        );
    }
}
