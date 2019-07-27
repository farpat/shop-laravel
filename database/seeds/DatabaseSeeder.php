<?php

use App\Models\{Category, Product, ProductReference};
use App\Repositories\{CategoryRepository, ModuleRepository};
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{

    /**
     * @var ModuleRepository
     */
    private $moduleRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function __construct (ModuleRepository $moduleRepository, CategoryRepository $categoryRepository)
    {
        $this->moduleRepository = $moduleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run ()
    {
        $categories = factory(Category::class, 30)->create();
        foreach ($categories as $category) {
            $productfields = $this->createProductfields($category);
            $products = $this->createProducts($category);

            foreach ($products as $product) {
                $this->createProductReferences($product, $productfields);
            }
        }

        $this->createHomeModule();
    }

    private function createProductfields (Category $category)
    {
        if (($count = random_int(0, 4)) > 0) {
            $productFieldsRequestData = [];

            for ($i = 0; $i < $count; $i++) {
                static $types = ['number', 'string'];

                $productFieldsRequestData[] = [
                    'type'        => $types[array_rand($types)],
                    'label'       => $this->faker->unique()->words(2, true),
                    'is_required' => true,
                ];
            }
            return $this->categoryRepository->setProductFields($category, $productFieldsRequestData);
        }

        return null;
    }

    private function createProducts (Category $category)
    {
        return factory(Product::class, random_int(2, 10))->create([
            'category_id' => $category->id
        ]);
    }

    private function createProductReferences (Product $product, ?Collection $productFields)
    {
        $count = random_int(1, 4);

        for ($i = 0; $i < $count; $i++) {
            $filledProductfields = [];

            if ($productFields) {
                foreach ($productFields as $productField) {
                    $filledProductfields[$productField->id] = ($productField->type === 'number') ?
                        random_int(1, 10) :
                        $this->faker->words(2, true);
                }
            }

            ProductReference::query()->create([
                'product_id'                 => $product->id,
                'unit_price_excluding_taxes' => pow(10, random_int(1, 5)),
                'filled_product_fields'      => $filledProductfields
            ]);
        }
    }

    private function createHomeModule ()
    {
        $elements = [
            [
                'icon'  => 'fas fa-book',
                'title' => 'Book 1',
            ],
            [
                'icon'  => 'fas fa-book',
                'title' => 'Book 2',
            ]
        ];

        $slides = [
            [
                'title'       => 'Slide 1',
                'description' => 'Slide 1',
                'img'         => 'https://picsum.photos/id/1/1000/400/'
            ],
            [
                'title'       => 'Slide 2',
                'description' => 'Slide 2',
                'img'         => 'https://picsum.photos/id/2/1000/400'
            ]
        ];

        $navigation = [
            Category::class . ':2' => [Product::class . ':1', Product::class . ':2', Product::class . ':3'],
            Category::class . ':1' => [Product::class . ':4', Product::class . ':6', Product::class . ':5'],
            Category::class . ':3' => [Product::class . ':7', Product::class . ':8', Product::class . ':9'],
            Product::class . ':10'
        ];

        $this->moduleRepository->createModule('home', true, 'Home module');
        $this->moduleRepository->createParameter('home', 'products', [1, 2]);
        $this->moduleRepository->createParameter('home', 'categories', [1, 2]);
        $this->moduleRepository->createParameter('home', 'carousel', $slides);
        $this->moduleRepository->createParameter('home', 'elements', $elements);
        $this->moduleRepository->createParameter('home', 'navigation', $navigation);
    }
}
