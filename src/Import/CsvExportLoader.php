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

use Vanilo\WooCommerce\Models\Product;
use Vanilo\WooCommerce\Models\ProductCollection;
use Vanilo\WooCommerce\Models\ProductType;

class CsvExportLoader
{
    public function parseFile(string $fileName): ProductCollection
    {
        $result = new ProductCollection();

        if (($handle = fopen($fileName, "r")) !== false) {
            $first = true;
            while (($row = fgetcsv($handle)) !== false) {
                if (!$first) {
                    $result->push($this->parseRow($row));
                }

                $first = false;
            }
            fclose($handle);
        }

        return $result;
    }

    private function parseRow(array $row): Product
    {
        $sku = empty($row[2]) ? null : $row[2];

        $product = new Product($row[0], $row[3], $sku, new ProductType($row[1]));

        $product->isPublished = (bool) $row[4];
        $product->shortDescription = $this->crlf($row[7]);
        $product->description = $this->crlf($row[8]);
        $product->inStock = $row[13];
        $product->salePrice = empty($row[24]) ? null : floatval($row[24]);
        $product->regularPrice = empty($row[25]) ? null : floatval($row[25]);
        $product->tags = empty($row[27]) ? [] : explode(', ', $row[27]);
        $product->images = empty($row[29]) ? [] : explode(', ', $row[29]);
        $product->parentId = empty($row[32]) ? null : str_replace('id:', '', $row[32]);
        $product->position = (int) $row[38];

        $this->parseCategories($product, $row);

        $attributes = [];
        if (!empty($row['40'])) {
            $attributes[$row['40']] = explode(', ', $row[41]);
        }

        if (!empty($row['45'])) {
            $attributes[$row['45']] = explode(', ', $row[46]);
        }

        $product->attributes = $attributes;

        return $product;
    }

    private function parseCategories(Product $product, array $row): void
    {
        if (empty($row[26])) {
            return;
        }

        $categories = explode(',', $row[26]);
        array_walk($categories, 'trim');
        $results = [];

        foreach ($categories as $category) {
            $nodes = explode('>', $category);
            $prev = null;

            foreach ($nodes as $key => $node) {
                $node = trim($node);
                $results[$node] = 0 === $key ? null : $prev;
                $prev = $node;
            }
        }

        $product->categories = $results;
    }

    /**
     * @see https://github.com/woocommerce/woocommerce/blob/6.1.0/plugins/woocommerce/includes/export/class-wc-product-csv-exporter.php#L584
     */
    private function crlf(?string $text): ?string
    {
        if (null === $text) {
            return null;
        }

        return str_replace("\x2E\x0D\\n", "\n\n", $text);
    }
}
