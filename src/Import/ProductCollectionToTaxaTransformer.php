<?php

declare(strict_types=1);

/**
 * Contains the ProductCollectionToTaxaTransformer class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Import;

use Vanilo\Category\Contracts\Taxonomy;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\WooCommerce\Models\ProductCollection;

/**
 * This class converts "categories" found in a WooCommerce product collection into Vanilo Taxons
 * @see https://vanilo.io/docs/2.x/categorization
 */
class ProductCollectionToTaxaTransformer
{
    /**
     * Returns the number of taxon entries created
     */
    public function handle(ProductCollection $products, Taxonomy $taxonomy): int
    {
        $result = 0;
        // Iterate through the products
        // Build hierarchical taxon tree (in memory)
        // Eg.
        //   Diapers
        //      └────╼ Printed Diapers
        //               └───────────────╼ Bambino
        // Iterate through the taxon tree
        // Check if each taxon exists, create if not, also set their parents
        // Create the taxon entries withing the passed $taxonomy
        //
        // Example:
        TaxonProxy::create([
           'taxonomy_id' => $taxonomy->id,
            'parent_id' => null, // or $parentTaxon->id,
            'name' => 'Printed Diapers'
        ]);
        //
        // inc $result++ on each item created

        return $result;
    }
}
