<?php

use Bezhanov\Faker\Provider\Commerce;
use App\Models\{Category, Image, Product, ProductReference, Tax, User};
use App\Repositories\{CategoryRepository, ModuleRepository};
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
    private $ecoTax5Cents;
    /**
     * @var Tax
     */
    private $tax20Percent;
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

        foreach (factory(Category::class, random_int(4, 8))->create() as $category) {
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
            $this->createAddresses($user);
            $this->createOrderedCart($user, random_int(2, 5));
            $this->createOrderingCart($user);
        }
        $this->endTime($start);
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
        $taxes = collect([$this->tax20Percent]);
        if ($this->faker->boolean(25)) {
            $taxes->push($this->ecoTax5Cents);
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

    private function createOrderedCart (User $user, int $count)
    {
        for ($j = 0; $j < $count; $j++) {
            $this->cartManager->refresh($user);

            $productReferences = ProductReference::query()->inRandomOrder()->limit(random_int(2, 5))->get();

            $productReferences->map(function (ProductReference $productReference) {
                $this->cartManager->addItem(random_int(1, 5), $productReference);
            });

            $cart = $this->cartManager->getCart();
            $firstUserAddress = $user->addresses->first();
            $cart->fill([
                'address_id'          => $firstUserAddress->id,
                'address_text'        => $firstUserAddress->text,
                'address_line1'       => $firstUserAddress->line1,
                'address_line2'       => $firstUserAddress->line2,
                'address_postal_code' => $firstUserAddress->postal_code,
                'address_city'        => $firstUserAddress->city,
                'address_country'     => $firstUserAddress->country,
                'address_latitude'    => $firstUserAddress->latitude,
                'address_longitude'   => $firstUserAddress->longitude,
            ]);

            $this->cartManager->updateCartOnOrderedStatus();
        }

        $this->cartManager->refresh($user);
    }

    private function createOrderingCart (User $user)
    {
        $this->cartManager->refresh($user);
        $limit = random_int(2, 5);

        $productReferences = ProductReference::query()->inRandomOrder()->limit($limit)->get();

        for ($i = 0; $i < $limit; $i++) {
            $this->cartManager->addItem(random_int(1, 5), $productReferences[$i]);
        }
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
            Category::class . ':1' => [Product::class . ':4', Product::class . ':6', Product::class . ':5'],
            Product::class . ':10'
        ]);
    }

    private function createBillingModule ()
    {
        $this->moduleRepository->createModule('billing', true, 'Billing module');
        $this->moduleRepository->createParameter('billing', 'next_number', 1);
        $this->moduleRepository->createParameter('billing', 'currency', 'EUR');
        $this->moduleRepository->createParameter('billing', 'address', factory(\App\Models\Address::class)->make()->toArray());
        $this->moduleRepository->createParameter('billing', 'phone_number', $this->faker->phoneNumber);
    }

    private function createAddresses (User $user): Collection
    {
        return factory(\App\Models\Address::class, 2)->create(['user_id' => $user->id]);
    }

    private function emptyPrivateFiles ()
    {
        $privateFileSystem = Storage::disk('private');
        foreach ($privateFileSystem->directories() as $directory) {
            $privateFileSystem->deleteDirectory($directory);
        }
    }
}
