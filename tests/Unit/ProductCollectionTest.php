<?php

declare(strict_types=1);

/**
 * Contains the ProductCollectionTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-11-28
 *
 */

namespace Vanilo\WooCommerce\Tests\Unit;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vanilo\WooCommerce\Models\Product;
use Vanilo\WooCommerce\Models\ProductCollection;

class ProductCollectionTest extends TestCase
{
    /** @test */
    public function product_instances_can_be_passed_via_the_constructor()
    {
        $products = new ProductCollection(
            new Product('123', '123'),
            new Product('124', '124'),
        );

        $this->assertCount(2, $products);
    }

    /** @test */
    public function product_instances_passed_via_the_constructor_are_keyed_by_product_id()
    {
        $products = new ProductCollection(
            new Product('107', 'k7'),
            new Product('108', 'k8'),
        );

        $this->assertInstanceOf(Product::class, $products->get('107'));
        $this->assertEquals('k7', $products->get('107')->name);
        $this->assertInstanceOf(Product::class, $products->get('108'));
        $this->assertEquals('k8', $products->get('108')->name);
    }

    /** @test */
    public function non_product_instances_can_not_be_passed_via_the_constructor()
    {
        $this->expectError();

        new ProductCollection('asd', new stdClass());
    }

    /** @test */
    public function product_instances_can_be_added()
    {
        $products = new ProductCollection();

        $products->add(new Product('123', 'Product'));
        $this->assertCount(1, $products);
    }

    /** @test */
    public function product_instances_added_using_the_add_method_will_be_keyed_by_product_id()
    {
        $products = new ProductCollection();

        $products->add(new Product('3711', 'Samuel T-Shirt'));
        $this->assertInstanceOf(Product::class, $products->get('3711'));
        $this->assertEquals('Samuel T-Shirt', $products->get('3711')->name);
    }

    /** @test */
    public function adding_a_non_product_instances_throws_an_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        (new ProductCollection())->add(new stdClass());
    }

    /** @test */
    public function products_can_be_put()
    {
        $products = new ProductCollection();

        $products->put('1', new Product('123', 'Product'));
        $this->assertCount(1, $products);
    }

    /** @test */
    public function it_can_tell_if_there_were_any_categories_within_the_collection()
    {
        $product1 = new Product('123', '123');
        $product1->categories = ['Diapers' => null];
        $product2 = new Product('222', '222');

        $collection = (new ProductCollection($product1, $product2));

        $this->assertTrue($collection->hasCategories());
    }

    /** @test */
    public function it_can_tell_if_there_were_no_categories_within_the_collection()
    {
        $products = new ProductCollection(
            new Product('1', 'Ha'),
            new Product('2', 'Ho'),
            new Product('3', 'Yo'),
        );

        $this->assertTrue($products->hasNoCategories());
    }
}
