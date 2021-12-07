<?php

declare(strict_types=1);

/**
 * Contains the ProductCollectionToPropertiesTransformer class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Import;

use Vanilo\Properties\Models\PropertyProxy;
use Vanilo\Properties\Types\Text;
use Vanilo\WooCommerce\Models\Product;
use Vanilo\WooCommerce\Models\ProductCollection;

/**
 * This class converts "attributes" found in a WooCommerce product collection into Vanilo Product Properties
 * @see https://vanilo.io/docs/2.x/properties
 */
class ProductCollectionToPropertiesTransformer
{
    /**
     * Returns the number of product property values created
     */
    public function handle(ProductCollection $products): int
    {
        $result = 0;

        $products->each(function (Product $product) use (&$result) {
            foreach ($product->attributes as $name => $values) {
                $property = PropertyProxy::findOneByName($name) ?? PropertyProxy::create(['name' => $name, 'type' => Text::class]);

                foreach ($values as $value) {
                    $property->propertyValues()->firstOrCreate(['title' => $value]);
                }

                if ($property->wasRecentlyCreated) {
                    $result++;
                }
            }
        });

        return $result;
    }
}
