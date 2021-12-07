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

use Vanilo\Product\Contracts\Product as VaniloProduct;
use Vanilo\Product\Models\ProductProxy;
use Vanilo\Product\Models\ProductState;
use Vanilo\WooCommerce\Models\Product;

class WooToVaniloProductTransformer
{
    public function handle(Product $product): VaniloProduct
    {
        $result = ProductProxy::firstOrNew(
            ['sku' => $product->sku],
            [
                'name' => $product->name,
                'price' => $product->salePrice,
                'excerpt' => $product->shortDescription,
                'description' => $product->description,
                'state' => $product->isPublished ? ProductState::ACTIVE : ProductState::INACTIVE,
                'stock' => is_int($product->inStock) ? $product->inStock : 0,
            ]
        );

        if (!$result->exists()) {
            foreach ($product->images as $remoteImage) {
                if (method_exists($result, 'addMediaFromUrl')) {
                    $result->addMediaFromUrl($remoteImage)->toMediaCollection();
                }
            }
        }

        return $result;
    }
}
