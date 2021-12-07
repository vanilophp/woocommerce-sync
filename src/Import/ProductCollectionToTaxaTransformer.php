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
use Vanilo\WooCommerce\Models\Product;
use Vanilo\WooCommerce\Models\ProductCollection;

/**
 * This class converts "categories" found in a WooCommerce product collection into Vanilo Taxons
 *
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

        $products->each(function (Product $product) use ($taxonomy, &$result) {
            foreach ($product->categories as $category => $parent) {
                if (!$parent) {
                    $parent = TaxonProxy::firstOrCreate(['name' => $category, 'taxonomy_id' => $taxonomy->id]);

                    if ($parent->wasRecentlyCreated) {
                        $result++;
                    }
                    continue;
                }

                $parent = TaxonProxy::where('name', $parent)->first();

                $child = TaxonProxy::firstOrCreate(['name' => $category, 'parent_id' => $parent->id, 'taxonomy_id' => $taxonomy->id]);

                if ($child->wasRecentlyCreated) {
                    $result++;
                }
            }
        });

        return $result;
    }
}
