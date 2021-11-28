<?php

declare(strict_types=1);

/**
 * Contains the WooCommerceImportCommand class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Console\Commands;

use Illuminate\Console\Command;
use Vanilo\Category\Contracts\Taxonomy;
use Vanilo\Category\Models\TaxonomyProxy;
use Vanilo\WooCommerce\Import\CsvExportLoader;
use Vanilo\WooCommerce\Import\ProductCollectionToPropertiesTransformer;
use Vanilo\WooCommerce\Import\ProductCollectionToTaxaTransformer;
use Vanilo\WooCommerce\Import\WooToVaniloProductTransformer;
use Vanilo\WooCommerce\Models\Product;
use Vanilo\WooCommerce\Models\ProductCollection;

class WooCommerceImportCommand extends Command
{
    protected $signature = 'woocom:import
                            {file : The path of the CSV file}
                            {--taxonomy= : The name of the taxonomy tree to map Categories onto. The first taxonomy will be used if unspecified}';

    protected $description = 'Import CSV export files from WooCommerce';

    public function handle(
        CsvExportLoader                    $loader,
        ProductCollectionToTaxaTransformer $taxaTransformer,
        ProductCollectionToPropertiesTransformer $propertiesTransformer,
        WooToVaniloProductTransformer $productTransformer,
    )
    {
        $products = $loader->parseFile($this->argument('file'));
        $taxaCreated = $this->processCategories($products, $taxaTransformer);
        $propertyValuesCreated = $propertiesTransformer->handle($products);

        $productsCreated = 0;
        $productsUpdated = 0;
        $productsSkipped = 0;
        /** @var Product $product */
        foreach ($products as $product) {
            /** @var \Vanilo\Product\Models\Product $converted */
            $converted = $productTransformer->handle($product);
            if ($converted->wasRecentlyCreated) {
                $converted->save();
                $productsCreated++;
            } elseif ($converted->isDirty()) {
                $converted->save();
                $productsUpdated++;
            } else {
                $productsSkipped++;
            }
        }

        $this->table(
            ['Entry', 'Created', 'Updated', 'Skipped'],
            [
                'Categories', $taxaCreated, '-', '-',
                'Properties', $propertyValuesCreated, '-', '-',
                'Products', $productsCreated, $productsUpdated, $productsSkipped,
            ]
        );
    }

    private function getTaxonomy(): ?Taxonomy
    {
        $intended = $this->option('taxonomy');
        if (null !== $intended) {
            $found = TaxonomyProxy::findOneByName($intended);
            if (null === $found) {
                $this->error("Taxonomy `$intended` was not found");
                exit(31);
            }

            return $found;
        }

        $found = Taxonomy::first();
        if (null === $found) {
            $this->error("There is no taxonomy in the system at all");
            exit(32);
        }

        return $found;
    }

    private function processCategories(ProductCollection $products, ProductCollectionToTaxaTransformer $taxaTransformer): int
    {
        if ($products->hasNoCategories()) {
            return 0;
        }

        return $taxaTransformer->handle($products, $this->getTaxonomy());
    }
}
