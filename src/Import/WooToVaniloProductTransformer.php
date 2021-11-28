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
use Vanilo\WooCommerce\Models\Product;

class WooToVaniloProductTransformer
{
    public function handle(Product $product): VaniloProduct
    {
        $result = ProductProxy::firstOrNew($product->sku);
        // @todo fill attributes

        // to store the images:
        foreach ($product->images as $remoteImage) {
            if (method_exists($result, 'addMediaFromUrl')) {
                $result->addMediaFromUrl($remoteImage)->toMediaCollection();
            }
        }

        // Don't save the result, just return it

        return $result;
    }
}
