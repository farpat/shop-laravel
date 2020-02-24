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
        $this->command->comment(PHP_EOL . "Start: $label");
        return microtime(true);
    }

    private function endTime (float $start): void
    {
        $end = microtime(true);
        $this->command->info(sprintf("=> Finish: %s seconds", round($end - $start, 2)));
    }

    private function resetAndCreationOfModules ()
    {
        $start = $this->startTime('Reset and Creation of modules');
        $this->emptyPrivateFiles();
        $this->createHomeModule();
        $this->createBillingModule();
        $this->endTime($start);
    }

    private function creationOfCategories ()
    {
        $start = $this->startTime('Creation of categories');
        $categoriesCount = random_int(10, 15);
        $categoryProgress = $this->command->getOutput()->createProgressBar($categoriesCount);

        [$vatTax, $ecoTax] = $this->createTaxes();

        foreach (factory(Category::class, $categoriesCount)->create() as $category) {
            $productfields = $this->createProductfields($category);

            foreach ($this->createSubCategories($category) as $subCategory) {
                $products = $this->createProducts($subCategory);
                foreach ($products as $product) {
                    $productReferences = $this->createProductReferences(
                        $product,
                        $productfields,
                        $this->attachTaxes($product, $vatTax, $ecoTax)
                    );

                    $this->createImages($product, $productReferences);
                }
            }
            $categoryProgress->advance();
        }
        echo PHP_EOL;
        $this->endTime($start);
    }

    /**
     * Seed the application's database.
     *
     * @return void
     * @throws Exception
     */
    public function run ()
    {
        $this->resetAndCreationOfModules();

        $this->creationOfCategories();

        $this->creationOfUsers();

        $this->command->line(PHP_EOL);
    }

    private function createBillings (User $user, Collection $addresses)
    {
        $countOfBillings = random_int(1, 4);

        for ($i = 0; $i < $countOfBillings; $i++) {
            $this->createCart($user);
            $this->cartManager->getCart()->update(['address_id' => $addresses->random()->id]);
            $this->cartManager->transformToBilling(); //The $user cart becomes empty
        }
    }

    private function createCart (User $user)
    {
        $this->cartManager->refresh($user);

        $productsReferences = ProductReference::query()
            ->limit(random_int(2, 5))
            ->inRandomOrder()
            ->get();

        foreach ($productsReferences as $productReference) {
            $this->cartManager->addItem(random_int(1, 10), $productReference);
        }
    }

    /**
     * @return Tax[]
     */
    private function createTaxes ()
    {
        $vatTax = Tax::query()->create([
            'label' => 'VAT 20%',
            'type'  => Tax::PERCENTAGE_TYPE,
            'value' => 20
        ]);

        $ecoTax = Tax::query()->create([
            'label' => 'Eco tax',
            'type'  => Tax::UNITY_TYPE,
            'value' => 0.05,
        ]);

        return [$vatTax, $ecoTax];
    }

    private function createProductfields (Category $category)
    {
        $numbers = self::NUMBER_PRODUCT_FIELDS;
        $strings = self::STRING_PRODUCT_FIELDS;

        $count = random_int(0, 4);

        if ($count === 0) {
            return collect();
        }

        $productFieldsRequestData = [];

        for ($i = 0; $i < $count; $i++) {
            $type = $this->faker->boolean ? 'string' : 'number';
            $label = array_rand(${$type . 's'});
            unset(${$type . 's'}[$label]);

            $productFieldsRequestData[] = [
                'type'        => $type,
                'label'       => $label,
                'is_required' => true,
            ];
        }

        return $category->product_fields()->createMany($productFieldsRequestData);
    }

    private function createSubCategories (Category $parentCategory): Collection
    {
        $subCategoriesInArray = [];
        $slugs = [];

        for ($i = 0; $i < 2; $i++) {
            $label = $parentCategory->label . ' ' . $this->faker->unique()->word;
            $slug = Str::slug($label);
            $slugs[] = $slug;
            $nomenclature = $parentCategory->nomenclature . '.' . str_replace('-', ' ', Str::upper($slug));

            $subCategoriesInArray[] = factory(Category::class)->raw([
                'label'        => $label,
                'slug'         => $slug,
                'nomenclature' => $nomenclature,
                'is_last'      => true,
            ]);
        }

        Category::insert($subCategoriesInArray);

        return Category::where('slug', $slugs)->get();
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
        $productsInArray = factory(Product::class, random_int(10, 20))->raw(['category_id' => $category->id]);

        Product::insert($productsInArray);

        return Product::where('category_id', $category->id)->get();
    }

    private function attachTaxes (Product $product, Tax $vatTax, Tax $ecoTax): Collection
    {
        $taxes = collect([$vatTax]);
        if ($this->faker->boolean(25)) {
            $taxes->push($ecoTax);
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
     * @return Collection|ProductReference[]
     * @throws Exception
     */
    private function createProductReferences (Product $product, Collection $productFields, Collection $taxes): Collection
    {
        [$unitPriceExcludingTaxes, $unitPriceIncludingTaxes] = $this->computePricesOfProduct($product, $taxes);

        $productReferences = [];
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

            $productReferences[] = [
                'label'                      => $product->label . (!empty($labelsArray) ? ' => ' . implode(' | ', $labelsArray) : ''),
                'product_id'                 => $product->id,
                'unit_price_excluding_taxes' => $unitPriceExcludingTaxes,
                'unit_price_including_taxes' => $unitPriceIncludingTaxes,
                'filled_product_fields'      => json_encode($filledProductfields)
            ];
        }

        ProductReference::insert($productReferences);

        return ProductReference::query()->where('product_id', $product->id)->get();
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

        $this->moduleRepository->createParameter('home', 'navigation', [
            Category::class . ':2' => [Product::class . ':1', Product::class . ':2', Product::class . ':3'],
            Category::class . ':5' => [Product::class . ':4', Product::class . ':6', Product::class . ':5'],
            Product::class . ':10'
        ]);

        $this->moduleRepository->createParameter('home', 'display', ['carousel', 'categories', 'products', 'elements']);
        $this->moduleRepository->createParameter('home', 'products', [1, 2]);
        $this->moduleRepository->createParameter('home', 'categories', [1, 2]);
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
    }

    private function createBillingModule ()
    {
        $this->moduleRepository->createModule('billing', true, 'Billing module');
        $this->moduleRepository->createParameter('billing', 'next_number', 1);
        $this->moduleRepository->createParameter('billing', 'currency', [
            'style'  => 'right',
            'code'   => 'EUR',
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

    private function creationOfUsers ()
    {
        $start = $this->startTime('Creation of users');
        $usersCount = 10;
        $userProgress = $this->command->getOutput()->createProgressBar($usersCount);

        foreach (factory(User::class, $usersCount)->create() as $user) {
            $addresses = $this->createAddresses($user);
            $this->createBillings($user, $addresses);
            $this->createCart($user);

            $userProgress->advance();
        }
        echo PHP_EOL;
        $this->endTime($start);
    }
}
