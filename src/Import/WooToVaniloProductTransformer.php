<?php

declare(strict_types=1);

/**
 * Contains the WooToVaniloProductTransformer class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Import;

use Vanilo\Category\Contracts\Taxon;
use Vanilo\Category\Models\TaxonProxy;
use Vanilo\Product\Contracts\Product as VaniloProduct;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Product\Models\ProductState;
use Vanilo\WooCommerce\Models\Product as WooProduct;

class WooToVaniloProductTransformer
{
    private array $taxonCache = [];

    public function handle(WooProduct $source): VaniloProduct
    {
        $result = ProductProxy::firstOrCreate(
            ['sku' => $source->sku],
            [
                'name' => $source->name,
                'price' => $source->regularPrice,
                'excerpt' => $source->shortDescription,
                'description' => $source->description,
                'state' => $source->isPublished ? ProductState::ACTIVE : ProductState::INACTIVE,
                'stock' => is_int($source->inStock) ? $source->inStock : 0,
            ]
        );

        if ($result->wasRecentlyCreated) {
            if (method_exists($result, 'addMediaFromUrl')) {
                foreach ($source->images as $remoteImage) {
                    $result->addMediaFromUrl($remoteImage)->toMediaCollection();
                }
            }

            if ($source->isAssignedToCategories() && method_exists($result, 'addTaxon')) {
                foreach ($source->categories as $category => $parent) {
                    if ($taxon = $this->fetchTaxon($category, $parent)) {
                        $result->addTaxon($taxon);
                    }
                }
            }
        }

        return $result;
    }

    private function fetchTaxon(string $name, ?string $parent): ?Taxon
    {
        $key = sprintf('%s/%s', $parent ?? '', $name);
        if (!isset($this->taxonCache[$key])) {
            $query = TaxonProxy::where('name', $name);
            if (null !== $parent) {
                if ($parentTaxon = TaxonProxy::where('name', $parent)) {
                    $query->where('parent_id', $parentTaxon->id);
                }
            }
            $this->taxonCache[$key] = TaxonProxy::where('name', $name)->first();
        }

        return $this->taxonCache[$key];
    }
}
