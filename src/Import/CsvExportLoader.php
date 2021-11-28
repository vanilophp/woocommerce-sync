<?php

declare(strict_types=1);

/**
 * Contains the CsvExportLoader class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Import;

use Vanilo\WooCommerce\Models\ProductCollection;

class CsvExportLoader
{
    public function parseFile(string $fileName): ProductCollection
    {
        $result = new ProductCollection();
        /** @todo Implement this */
        // Get the first row of the CSV, it contains the column names
        // Store the column "map" (column name => column index)

        // iterate through the CSV file lines
        // Read the products into new Product()
        // Fill and transform all attributes
        // Add the item to the collection

        return $result;
    }
}
