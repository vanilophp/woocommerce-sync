<?php

declare(strict_types=1);

/**
 * Contains the Product class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Models;

final class Product
{
    public string $id;

    public string $sku;

    public string $name;

    public array $categories = [];

    public array $attributes = [];

    public array $tags = [];

    public array $images = [];

    public int $position = 0;

    public bool $isPublished = true;

    public ?string $shortDescription = null;

    public ?string $description = null;

    public string|int $inStock = 'backorder';

    public ?float $salePrice = null;

    public ?float $regularPrice = null;

    public ?string $parentId = null;

    public ProductType $type;

    public function __construct(
        string $id,
        string $name,
        ?string $sku = null,
        ?ProductType $type = null,
    ) {
        $this->id = $id;
        $this->sku = $sku ?? $this->id;
        $this->type = $type ?? ProductType::create();
        $this->name = $name;
    }

    public function hasParent(): bool
    {
        return null !== $this->parentId;
    }
}
