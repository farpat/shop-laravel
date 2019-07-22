<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Repositories\ModuleRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    /** @test */
    public function get_four_products_in_home ()
    {
        $this->moduleRepository->createParameter('home', 'products', [1, 2, 3, 4]);
        factory(Product::class, 14)->create();

        $response = $this->get(route('home.index'));
        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertCount(1, $crawler->filter('.products'));
        $this->assertCount(4, $crawler->filter('.product'));
    }

    /** @test */
    public function get_zero_products_in_home ()
    {
        $this->moduleRepository->createParameter('home', 'products', []);
        factory(Product::class, 14)->create();

        $response = $this->get(route('home.index'));
        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertCount(0, $crawler->filter('.products'));
        $this->assertCount(0, $crawler->filter('.product'));
    }

    /** @test */
    public function get_four_categories_in_home ()
    {
        $this->withoutExceptionHandling();

        $this->moduleRepository->createParameter('home', 'categories', [1, 2, 3, 4]);
        factory(Category::class, 14)->create();

        $response = $this->get(route('home.index'));
        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertCount(1, $crawler->filter('.categories'));
        $this->assertCount(4, $crawler->filter('.category'));
    }

    /** @test */
    public function get_zero_categories_in_home ()
    {
        $this->moduleRepository->createParameter('home', 'categories', []);
        factory(Category::class, 14)->create();

        $response = $this->get(route('home.index'));
        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertCount(0, $crawler->filter('.categories'));
        $this->assertCount(0, $crawler->filter('.category'));
    }

    /** @test */
    public function get_three_elements_in_home ()
    {
        $this->withoutExceptionHandling();

        $elements = [
            [
                'icon'  => 'fas fa-book',
                'title' => 'Book 1',
            ],
            [
                'icon'  => 'fas fa-book',
                'title' => 'Book 2',
            ],
            [
                'icon'  => 'fas fa-book',
                'title' => 'Book 3',
            ],
        ];
        $this->moduleRepository->createParameter('home', 'elements', $elements);

        $response = $this->get(route('home.index'));
        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertCount(1, $crawler->filter('.elements'));
        $this->assertCount(count($elements), $crawler->filter('.element'));
    }

    /** @test */
    public function get_zero_elements_in_home ()
    {
        $elements = [];

        $this->moduleRepository->createParameter('home', 'elements', $elements);

        $response = $this->get(route('home.index'));
        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertCount(0, $crawler->filter('.elements'));
        $this->assertCount(0, $crawler->filter('.element'));
    }

    /** @test */
    public function get_two_carousel_slides_in_home ()
    {
        $slides = [
            [
                'title'       => 'Slide 1',
                'description' => 'Slide 1',
                'img'         => 'https://picsum.photos/300'
            ],
            [
                'title'       => 'Slide 2',
                'description' => 'Slide 2',
                'img'         => 'https://picsum.photos/300'
            ]
        ];
        $this->moduleRepository->createParameter('home', 'carousel', $slides);

        $response = $this->get(route('home.index'));
        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertCount(1, $crawler->filter('.slides'));
        $this->assertCount(count($slides), $crawler->filter('.carousel-item'));
    }

    /** @test */
    public function get_zero_carousel_slides_in_home ()
    {
        $slides = [];
        $this->moduleRepository->createParameter('home', 'carousel', $slides);

        $response = $this->get(route('home.index'));
        $response->assertSuccessful();
        $crawler = $this->getCrawler($response);
        $this->assertCount(0, $crawler->filter('.slides'));
        $this->assertCount(0, $crawler->filter('.carousel-item'));
    }

    protected function setUp (): void
    {
        parent::setUp();
        $this->moduleRepository = app(ModuleRepository::class);
        $this->moduleRepository->createModule('home', true, 'Home module');
    }
}
