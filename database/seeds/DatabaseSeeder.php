<?php

use App\Models\{Category, Image, Product, ProductReference, Tax, User};
use App\Repositories\{CartRepository, CategoryRepository, ModuleRepository};
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
    /**
     * @var Tax
     */
    private $ecoTax5Cents;
    /**
     * @var Tax
     */
    private $tax20Percent;
    /**
     * @var CartRepository
     */
    private $cartRepository;
    /**
     * @var Guard
     */
    private $auth;

    public function __construct (ModuleRepository $moduleRepository, CategoryRepository $categoryRepository, CartRepository $cartRepository, Guard $auth)
    {
        $this->faker = Faker\Factory::create('fr_FR');

        $this->moduleRepository = $moduleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cartRepository = $cartRepository;
        $this->auth = $auth;
    }

    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run ()
    {
        $this->createTaxes();

        dump('Creation of products');
        $start = microtime(true);
        foreach (factory(Category::class, 4)->create() as $category) {

            for ($i = 0; $i < 2; $i++) {
                $subCategory = $this->createSubCategory($category);
                $productfields = $this->createProductfields($subCategory);
                $products = $this->createProducts($subCategory);

                foreach ($products as $product) {
                    $taxes = $this->attachTaxes($product);
                    $productReferences = $this->createProductReferences($product, $productfields, $taxes);
                    $this->createImages($product, $productReferences);
                }
            }
        }
        $end = microtime(true);
        dump('==> ' . round($end - $start, 2) . ' seconds');

        dump('Creation of carts');
        $start = microtime(true);
        foreach (factory(User::class, 10)->create() as $user) {
            $this->auth->login($user);
            $this->cartRepository->refreshItems();
            $this->cartRepository->addItem(random_int(1, 5), ProductReference::query()->inRandomOrder()->first());
        }
        $end = microtime(true);
        dump('==> ' . round($end - $start, 2) . ' seconds');

        $this->createHomeModule();
    }

    private function createSubCategory (Category $category): Category
    {
        $label = $this->faker->words(3, true);
        $slug = Str::slug($label);
        $nomenclature = $category->nomenclature . '.' . str_replace('-', '', Str::upper($slug));

        return factory(Category::class)->create([
            'label'        => $label,
            'slug'         => $slug,
            'nomenclature' => $nomenclature,
            'is_last'      => true,
        ]);
    }

    private function attachTaxes (Product $product): Collection
    {
        $taxes = collect([$this->tax20Percent]);
        if ($this->faker->boolean(25)) {
            $taxes->push($this->ecoTax5Cents);
        }

        $product->taxes()->attach($taxes->pluck('id'));
        $product->save();

        return $taxes;
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

    /**
     * @param Category $category
     *
     * @return Collection|Product[]
     * @throws Exception
     */
    private function createProducts (Category $category)
    {
        return factory(Product::class, random_int(1, 15))->create([
            'category_id' => $category->id
        ]);
    }

    /**
     * @param Product $product
     * @param Collection|null $productFields
     * @param Collection $taxes
     *
     * @return ProductReference[]|Collection
     * @throws Exception
     */
    private function createProductReferences (Product $product, ?Collection $productFields, Collection $taxes): Collection
    {
        $unitPriceExcludingTaxes = pow(10, random_int(1, 5));
        $totalTaxes = $taxes->reduce(function ($acc, Tax $tax) use ($unitPriceExcludingTaxes) {
            if ($tax->type === Tax::UNITY_TYPE) {
                $acc += $tax->value;
            } elseif ($tax->type === Tax::PERCENTAGE_TYPE) {
                $acc += $unitPriceExcludingTaxes * ($tax->value / 100);
            }

            return $acc;
        }, 0);
        $unitPriceIncludingTaxes = $totalTaxes + $unitPriceExcludingTaxes;

        $productReferences = collect();
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


            $productReference = ProductReference::query()->create([
                'label'                      => $this->faker->words(2, true),
                'product_id'                 => $product->id,
                'unit_price_excluding_taxes' => $unitPriceExcludingTaxes,
                'unit_price_including_taxes' => $unitPriceIncludingTaxes,
                'filled_product_fields'      => $filledProductfields
            ]);

            $productReferences->push($productReference);
        }

        return $productReferences;
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
            ],
            [
                'icon'  => 'fas fa-book',
                'title' => 'Book 3',
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
            Product::class . ':10'
        ];

        $this->moduleRepository->createModule('home', true, 'Home module');
        $this->moduleRepository->createParameter('home', 'products', [1, 2]);
        $this->moduleRepository->createParameter('home', 'categories', [1, 2]);
        $this->moduleRepository->createParameter('home', 'carousel', $slides);
        $this->moduleRepository->createParameter('home', 'elements', $elements);
        $this->moduleRepository->createParameter('home', 'navigation', $navigation);
        $this->moduleRepository->createParameter('home', 'currency', 'EUR');
    }

    /**
     * @param Product $product
     * @param Collection|ProductReference[] $productReferences
     *
     * @throws Exception
     */
    private function createImages (Product $product, Collection $productReferences): void
    {
        if (($count = random_int(0, 5)) === 0) {
            return;
        }

        $images = [];
        foreach ($productReferences as $productReference) {
            $images = $productReference->images()->createMany(factory(Image::class, $count)->make()->toArray());
            $productReference->update(['main_image_id' => $images[0]->id]);
        }

        $product->update(['main_image_id' => $images[0]->id]);
    }

    private function createTaxes ()
    {
        $this->tax20Percent = Tax::query()->create([
            'label' => 'VAT 20%',
            'type'  => Tax::PERCENTAGE_TYPE,
            'value' => 20
        ]);

        $this->ecoTax5Cents = Tax::query()->create([
            'label' => 'Eco tax',
            'type'  => Tax::UNITY_TYPE,
            'value' => 0.05,
        ]);
    }
}
