<?php

namespace Tests\Browser;

use App\Repositories\ModuleRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;

class HomeTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * @var ModuleRepository
     */
    private $moduleRepository;

    /** @test */
    public function get_home_navigation ()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new HomePage)
                ->screenshot('home with navigation')
                ->assertSeeIn('.navbar-brand', config('app.name'));
        });
    }

    protected function setUp (): void
    {
        parent::setUp();
        $this->moduleRepository = app(ModuleRepository::class);
        $this->moduleRepository->createModule('home', true, 'Home module');
    }
}
