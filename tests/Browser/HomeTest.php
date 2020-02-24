<?php

namespace Tests\Browser;

use App\Models\{Category, Module, ModuleParameter, Product};
use App\Repositories\ModuleRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class HomeTest extends DuskTestCase
{
    //DatabaseMigration handle migration between tests
    //RefreshDatabase execute SQL request into transaction
    use DatabaseMigrations, RefreshDatabase;
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    /** @test */
    public function navigation_is_good ()
    {
        $navigation = [
            Category::class . ':2' => [Product::class . ':1', Product::class . ':2', Product::class . ':3'],
            Category::class . ':1' => [Product::class . ':4', Product::class . ':5', Product::class . ':6'],
            Product::class . ':7'
        ];

        $categories = factory(Category::class, 2)->create();
        $products = factory(Product::class, 7)->create(['category_id' => 1]);

        $this->moduleRepository->createParameter('home', 'navigation', $navigation);

        $this->browse(function (Browser $browser) use ($products, $categories) {
            $browser->visit(new HomePage)
                ->assertSeeIn('.navbar-brand', config('app.name'))
                ->assertSeeIn('.nav-item:nth-child(1)', __('All categories'))
                ->click('.nav-item:nth-child(2) .nav-link')
                ->screenshot('Navigation is good - first button')
                ->assertSeeIn('.nav-item:nth-child(2) .nav-link', $categories[1]->label)
                ->assertSeeIn('.nav-item:nth-child(2) .dropdown-item:nth-child(1)', $products[0]->label)
                ->assertSeeIn('.nav-item:nth-child(2) .dropdown-item:nth-child(2)', $products[1]->label)
                ->assertSeeIn('.nav-item:nth-child(2) .dropdown-item:nth-child(3)', $products[2]->label)
                ->click('.nav-item:nth-child(3) .nav-link')
                ->screenshot('Navigation is good - second button')
                ->assertSeeIn('.nav-item:nth-child(3) .nav-link', $categories[0]->label)
                ->assertSeeIn('.nav-item:nth-child(3) .dropdown-item:nth-child(1)', $products[3]->label)
                ->assertSeeIn('.nav-item:nth-child(3) .dropdown-item:nth-child(2)', $products[4]->label)
                ->assertSeeIn('.nav-item:nth-child(3) .dropdown-item:nth-child(3)', $products[5]->label)
                ->assertSeeIn('.nav-item:nth-child(4) .nav-link', $products[6]->label);
        });
    }

    protected function setUp (): void
    {
        parent::setUp();

        $this->moduleRepository = app(ModuleRepository::class);
        $this->moduleRepository->createModule('home', true, 'Home module');
        $this->moduleRepository->createParameter('home', 'display', ['carousel', 'categories', 'products', 'elements']);

        $this->moduleRepository->createModule('billing', true, 'Billing module');
        $this->moduleRepository->createParameter('billing', 'currency', [
            'style'  => 'right',
            'code'   => 'EUR',
            'symbol' => '€'
        ]);
    }
}
