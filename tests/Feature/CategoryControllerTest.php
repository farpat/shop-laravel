<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_success_when_category_slug_is_ok ()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->get($category->url);

        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertText($category->label, $crawler->filter('head>title')->text());
        $this->assertText($category->meta_description, $crawler->filter('head>meta[name=description]')->attr('content'));
    }

    /** @test */
    public function get_redirect_when_get_page_one ()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->get(route('categories.show', ['slug' => $category->slug, 'category' => $category->id, 'page' => 1]));

        $response->assertRedirect($category->url);
    }

    /** @test */
    public function get_redirect_when_category_slug_is_not_ok ()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->get(route('categories.show', ['slug' => $category->slug . 'toto', 'category' => $category->id]));

        $response->assertRedirect($category->url);
    }

    /** @test */
    public function get_404_when_category_id_doesnt_exist_even_if_slug_exists ()
    {
        $category = factory(Category::class)->create();

        $response = $this->get(route('categories.show', ['slug' => $category->slug, 'category' => 2]));

        $response->assertNotFound();
    }

    /** @test */
    public function get_404_when_category_slug_is_malformed ()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->get(route('categories.show', ['slug' => Str::upper($category->slug), 'category' => $category->id]));

        $response->assertNotFound();
    }

    /** @test */
    public function get_page_with_zero_products ()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();

        $response = $this->get($category->url);

        $crawler = $this->getCrawler($response);

        $productsElement = $crawler->filter('.products');
        $this->assertCount(1, $productsElement);
        $this->assertCount(0, $productsElement->filter('.product'));
        $this->assertText(__('This category has no products.'), $productsElement->text());
    }

    /** @test */
    public function get_page_with_some_products_without_pagination ()
    {
        $productsCount = Category::PRODUCTS_PER_PAGE - 1;
        /** @var Category $category */
        $category = factory(Category::class)->create();
        factory(Product::class, $productsCount)->create([
            'category_id' => $category->id
        ]);

        $response = $this->get($category->url);

        $crawler = $this->getCrawler($response);

        $productsElement = $crawler->filter('.products');
        $this->assertCount(1, $productsElement);
        $this->assertCount(0, $productsElement->filter('.pagination'));
    }

    /** @test */
    public function get_page_with_some_products_with_pagination ()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        $pagesCount = 5;
        $productsCount = Category::PRODUCTS_PER_PAGE * ($pagesCount - 1) + (Category::PRODUCTS_PER_PAGE - 1);
        factory(Product::class, $productsCount)->create([
            'category_id' => $category->id
        ]);

        $response = $this->get($category->url);

        $crawler = $this->getCrawler($response);

        $productsElement = $crawler->filter('.products');
        $this->assertCount(1, $productsElement->filter('.pagination'));
        $this->assertCount($pagesCount, $productsElement->filter('.page-item'));
        $this->assertCount(1, $productsElement->filter('.page-item.active'));
    }

    /** @test */
    public function get_page_greather_than_page_one ()
    {
        /** @var Category $category */
        $category = factory(Category::class)->create();
        $pagesCount = 5;
        $productsCount = Category::PRODUCTS_PER_PAGE * ($pagesCount - 1) + (Category::PRODUCTS_PER_PAGE - 1);
        factory(Product::class, $productsCount)->create([
            'category_id' => $category->id
        ]);

        $response = $this->get($category->url);

        $crawler = $this->getCrawler($response);

        $productsElement = $crawler->filter('.products');
        $this->assertCount(Category::PRODUCTS_PER_PAGE - 1, $productsElement->filter('.product'));
        $this->assertCount($pagesCount, $productsElement->filter('.page-item.active'));
    }

    protected function setUp (): void
    {
        parent::setUp();
    }
}
