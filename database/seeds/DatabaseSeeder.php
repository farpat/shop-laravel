<?php

use App\Models\{Category, Image, Product, ProductReference, Tax, User};
use App\Repositories\{CartRepository, CategoryRepository, ModuleRepository};
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{

    const STRING_PRODUCT_FIELDS = [
        'color'      => ['white', 'red', 'green', 'blue', 'yellow', 'orange'],
        'size'       => ['s', 'm', 'l', 'xs', 'xl', 'xxl'],
        'material'   => ['wood', 'plastic', 'metal'],
        'round'      => ['yes', 'no'],
        'watertight' => ['yes', 'no']
    ];

    const NUMBER_PRODUCT_FIELDS = [
        'storage space' => [8, 16, 32, 64, 128, 256],
        'weight in kg'  => [1, 4, 8, 16],
        'height in cm'  => [4, 16, 32, 64],
        'width in cm'   => [4, 16, 32, 64],
    ];

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

        foreach (['Computers', 'Phones', 'Printers', 'Cameras', 'Screens'] as $categoryLabel) {
            $category = factory(Category::class)->create(['label'        => $categoryLabel,
                                                          'nomenclature' => strtoupper($categoryLabel),
                                                          'slug'         => strtolower($categoryLabel)]);

            $productfields = $this->createProductfields($category);

            for ($i = 0; $i < 2; $i++) {
                $subCategory = $this->createSubCategory($category);
                $products = $this->createProducts($subCategory, $categoryLabel);

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

    private function createProductfields (Category $category)
    {
        $numbers = self::NUMBER_PRODUCT_FIELDS;
        $strings = self::STRING_PRODUCT_FIELDS;

        if (($count = random_int(0, 4)) > 0) {
            $productFieldsRequestData = [];

            for ($i = 0; $i < $count; $i++) {
                $type = $this->faker->boolean ? 'string' : 'number';
                if ($type === 'string') {
                    $label = array_rand($strings);
                    unset($strings[$label]);
                } else {
                    $label = array_rand($numbers);
                    unset($numbers[$label]);
                }

                $productFieldsRequestData[] = [
                    'type'        => $type,
                    'label'       => $label,
                    'is_required' => true,
                ];
            }
            return $this->categoryRepository->setProductFields($category, $productFieldsRequestData);
        }

        return null;
    }

    private function createSubCategory (Category $parentCategory): Category
    {
        $label = $parentCategory->label . ' ' . $this->faker->word;
        $slug = Str::slug($label);
        $nomenclature = $parentCategory->nomenclature . '.' . str_replace('-', '', Str::upper($slug));

        return factory(Category::class)->create([
            'label'        => $label,
            'slug'         => $slug,
            'nomenclature' => $nomenclature,
            'is_last'      => true,
        ]);
    }

    /**
     * @param Category $category
     *
     * @param string $rootCategoryLabel
     *
     * @return Collection|Product[]
     * @throws Exception
     */
    private function createProducts (Category $category, string $rootCategoryLabel)
    {
        return factory(Product::class, random_int(1, 30))->create([
            'label'       => substr($rootCategoryLabel, 0, -1) . ' ' . $this->faker->words(2, true),
            'category_id' => $category->id
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
        $count = random_int(1, 3);
        for ($i = 0; $i < $count; $i++) {
            $filledProductfields = [];
            $labelArray = [];

            if ($productFields) {
                foreach ($productFields as $productField) {
                    $values = $productField->type === 'string' ?
                        self::STRING_PRODUCT_FIELDS[$productField->label] :
                        self::NUMBER_PRODUCT_FIELDS[$productField->label];

                    $value = $values[array_rand($values)];

                    $filledProductfields[$productField->id] = $value;
                    $labelArray = [$productField->label . ' - ' . $value];
                }
            }


            $productReference = ProductReference::query()->create([
                'label'                      => !empty($labelArray) ? implode('|', $labelArray) : $product->label,
                'product_id'                 => $product->id,
                'unit_price_excluding_taxes' => $unitPriceExcludingTaxes,
                'unit_price_including_taxes' => $unitPriceIncludingTaxes,
                'filled_product_fields'      => $filledProductfields
            ]);

            $productReferences->push($productReference);
        }

        return $productReferences;
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
}
