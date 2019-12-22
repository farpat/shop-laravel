<?php

use Bezhanov\Faker\Provider\Commerce;
use App\Models\{Address, Category, Image, Product, ProductReference, Tax, User};
use App\Repositories\{CartRepository, CategoryRepository, ModuleRepository};
use App\Services\Bank\CartManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    const STRING_PRODUCT_FIELDS = [
        'color'    => ['white', 'red', 'green', 'blue', 'yellow', 'orange'],
        'size'     => ['s', 'm', 'l', 'xs', 'xl', 'xxl'],
        'material' => ['wood', 'plastic', 'metal'],
        'form'     => ['square', 'rectangle', 'round', 'diamond'],
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
    private $ecoTax;
    /**
     * @var Tax
     */
    private $VatTax;
    /**
     * @var CartManager
     */
    private $cartManager;

    public function __construct (ModuleRepository $moduleRepository, CategoryRepository $categoryRepository, CartManager $cartManager)
    {
        $this->faker = Faker\Factory::create('fr_FR');

        $this->moduleRepository = $moduleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->cartManager = $cartManager;

        $this->categoryLabels = collect(["Books", "Movies", "Music", "Games", "Electronics", "Computers", "Home",
            "Garden", "Tools", "Grocery", "Health", "Beauty", "Toys", "Kids", "Baby", "Clothing", "Shoes", "Jewelry",
            "Sports", "Outdoors", "Automotive", "Industrial"]);
    }

    private function startTime (string $label): float
    {
        dump($label);
        return microtime(true);
    }

    private function endTime (float $start): void
    {
        $end = microtime(true);
        dump('==> ' . round($end - $start, 2) . ' seconds');
    }

    private function storeCategoryLevel1 (Category $category): Category
    {
        $this->categoryLabels = $this->categoryLabels->shuffle();
        $categoryLabel = $this->categoryLabels->pop();
        $category->fill([
            'label'        => $categoryLabel,
            'slug'         => strtolower($categoryLabel),
            'nomenclature' => strtoupper($categoryLabel),
        ]);

        $category->save();

        return $category;
    }

    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run ()
    {
        $start = $this->startTime('Reset and Creation of modules');
        $this->emptyPrivateFiles();
        $this->createHomeModule();
        $this->createBillingModule();
        $this->endTime($start);

        $start = $this->startTime('Creation of products');
        $this->createTaxes();

        foreach (factory(Category::class, random_int(5, 10))->make() as $category) {
            $category = $this->storeCategoryLevel1($category);

            $productfields = $this->createProductfields($category);

            for ($i = 0; $i < 2; $i++) {
                $subCategory = $this->createSubCategory($category);

                foreach ($this->createProducts($subCategory) as $product) {
                    $productReferences = $this->createProductReferences(
                        $product,
                        $productfields,
                        $this->attachTaxes($product)
                    );

                    $this->createImages($product, $productReferences);
                }
            }
        }
        $this->endTime($start);

        $start = $this->startTime('Creation of carts');
        foreach (factory(User::class, 10)->create() as $user) {
            $addresses = $this->createAddresses($user);
            $this->createBillings($user, $addresses, random_int(1, 4));
            $this->createCart($user);
        }
        $this->endTime($start);
    }

    private function createBillings (User $user, Collection $addresses, int $countOfBillings)
    {
        for ($i = 0; $i < $countOfBillings; $i++) {
            $this->createCart($user);
            $this->cartManager->getCart()->update(['address_id' => $addresses->random()->id]);
            $this->cartManager->transformToBilling();
        }
    }

    private function createCart (User $user)
    {
        $this->cartManager->refresh($user);

        $productsReferences = ProductReference::query()
            ->limit(random_int(2, 5))
            ->get();

        foreach ($productsReferences as $productReference) {
            $this->cartManager->addItem(random_int(1, 10), $productReference);
        }
    }

    private function createTaxes ()
    {
        $this->VatTax = Tax::query()->create([
            'label' => 'VAT 20%',
            'type'  => Tax::PERCENTAGE_TYPE,
            'value' => 20
        ]);

        $this->ecoTax = Tax::query()->create([
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
                $arrayOfFields = $type . 's';
                $label = array_rand(${$arrayOfFields});
                unset(${$arrayOfFields}[$label]);

                $productFieldsRequestData[] = [
                    'type'        => $type,
                    'label'       => $label,
                    'is_required' => true,
                ];
            }
            return $this->categoryRepository->setProductFields($category, $productFieldsRequestData);
        }

        return collect();
    }

    private function createSubCategory (Category $parentCategory): Category
    {
        $label = $parentCategory->label . ' ' . $this->faker->unique()->word;
        $slug = Str::slug($label);
        $nomenclature = $parentCategory->nomenclature . '.' . str_replace('-', ' ', Str::upper($slug));

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
     *
     * @return Collection|Product[]
     * @throws Exception
     */
    private function createProducts (Category $category): Collection
    {
        $count = random_int(1, 20);
        $products = collect();
        $productLabels = ['Chair', 'Car', 'Computer', 'Gloves', 'Pants', 'Shirt', 'Table', 'Shoes', 'Hat', 'Plate',
            'Knife', 'Bottle',
            'Coat', 'Lamp', 'Keyboard', 'Bag', 'Bench', 'Clock', 'Watch', 'Wallet'];

        for ($i = 0; $i < $count; $i++) {
            $productKey = array_rand($productLabels);
            $productLabel = $productLabels[$productKey];
            unset($productLabels[$productKey]);

            $label = substr($category->label, 0, -1) . ' ' . strtolower($productLabel);
            $products[] = factory(Product::class)->create([
                'label'       => $label,
                'category_id' => $category->id,
                'slug'        => Str::slug($label)
            ]);
        }

        return $products;
    }

    private function attachTaxes (Product $product): Collection
    {
        $taxes = collect([$this->VatTax]);
        if ($this->faker->boolean(25)) {
            $taxes->push($this->ecoTax);
        }

        $product->taxes()->attach($taxes->pluck('id'));
        $product->save();

        return $taxes;
    }

    private function computePricesOfProduct (Product $product, Collection $taxes)
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

        return [$unitPriceExcludingTaxes, $unitPriceIncludingTaxes];
    }

    /**
     * @param Product $product
     * @param Collection $productFields
     * @param Collection $taxes
     *
     * @return ProductReference[]|Collection
     * @throws Exception
     */
    private function createProductReferences (Product $product, Collection $productFields, Collection $taxes): Collection
    {
        [$unitPriceExcludingTaxes, $unitPriceIncludingTaxes] = $this->computePricesOfProduct($product, $taxes);

        $productReferences = collect();
        $count = random_int(1, 3);
        for ($i = 0; $i < $count; $i++) {
            $filledProductfields = [];
            $labelsArray = [];

            if ($productFields->isNotEmpty()) {
                foreach ($productFields as $productField) {
                    $values = $productField->type === 'string' ?
                        self::STRING_PRODUCT_FIELDS[$productField->label] :
                        self::NUMBER_PRODUCT_FIELDS[$productField->label];

                    $value = $values[array_rand($values)];

                    $filledProductfields[$productField->id] = $value;
                    $labelsArray[] = $productField->label . ' - ' . $value;
                }
            }

            $productReference = ProductReference::query()->create([
                'label'                      => $product->label . (!empty($labelsArray) ? ' => ' . implode(' | ', $labelsArray) : ''),
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
        $this->moduleRepository->createModule('home', true, 'Home module');
        $this->moduleRepository->createParameter('home', 'products', [1, 2]);
        $this->moduleRepository->createParameter('home', 'categories', [1, 2]);
        $this->moduleRepository->createParameter('home', 'carousel', [
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
        ]);
        $this->moduleRepository->createParameter('home', 'elements', [
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
        ]);
        $this->moduleRepository->createParameter('home', 'navigation', [
            Category::class . ':2' => [Product::class . ':1', Product::class . ':2', Product::class . ':3'],
            Category::class . ':5' => [Product::class . ':4', Product::class . ':6', Product::class . ':5'],
            Product::class . ':10'
        ]);
    }

    private function createBillingModule ()
    {
        $this->moduleRepository->createModule('billing', true, 'Billing module');
        $this->moduleRepository->createParameter('billing', 'next_number', 1);
        $this->moduleRepository->createParameter('billing', 'currency', [
            'style' => 'right',
            'code' => 'EUR',
            'symbol' => '€'
        ]);
        $this->moduleRepository->createParameter('billing', 'address', factory(Address::class)->make()->toArray());
        $this->moduleRepository->createParameter('billing', 'phone_number', $this->faker->phoneNumber);
    }

    private function createAddresses (User $user): Collection
    {
        return factory(Address::class, 2)->create(['user_id' => $user->id]);
    }

    private function emptyPrivateFiles ()
    {
        $privateFileSystem = Storage::disk('private');
        foreach ($privateFileSystem->directories() as $directory) {
            $privateFileSystem->deleteDirectory($directory);
        }
    }
}
