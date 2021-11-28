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
        // Iterate through the products
        // Build property => values tree (in memory)
        // Eg.
        //   [
        //    'Screen Size' => ['15"', '17"', '21.5"'],
        //    'Theme' => ['Bambino'],
        //   ]
        // The array
        //   => keys are the names of the attributes
        //   => the values are the possible values of the given attribute from all products in the collection
        //
        // Iterate through all the entries:
        // 1. Check if the Property exists, create if not, eg.:
        $property = PropertyProxy::findOneByName('Screen Size') ?? PropertyProxy::create(['name' => 'Screen Size']);

        // 2. Iterate through all the values and check if they exist or create it, eg.:
        $property->propertyValues()->firstOrCreate(['title' => '17"']);
        // inc $result++ on each item propertyValue created

        return $result;
    }
}
